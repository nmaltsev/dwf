<?php
namespace Core;
require_once 'log.class.php';
$log = Utils\Log::create('Core');

function __autoload($filename) {
	if (file_exists($filename) == false){
		return false;
	}
    include ($filename);
    return true;
}

abstract class System {
	const InitScripts = [
		'router.class.php',
		'sourceStore.class.php',
		'template.class.php',
		'widgets.class.php'
	];

    static function init() {
		foreach(System::InitScripts as $script) {
			include(APP_PATH . $script);
		}
		$appConfig = Utils\SourceStore::restore(DOMAIN_PATH.'config.php');
		
		// if (isset($appConfig['default_log_file'])) {
		// 	Utils\Log::$defaultLogFilePath = $appConfig['default_log_file'];
		// }
		
		return $appConfig;
	}
}

// Main application
class App {
	private $isProd;
	public static $path;
	
	public function __construct($confArray){
		$this->isProd = $confArray['is_prod'];
		
		if ($this->isProd) {
			error_reporting(0); 
		}
		
		$query = $_SERVER['REQUEST_URI'];
		$routerQueryArray = explode('?', $query);
		
		// Creation an instance of the router 
		$router = new Utils\Router(DOMAIN_PATH . 'routing.php', !$this->isProd);
		self::$path = $routerQueryArray[0];
		// Parsing the request path
		$this->rout_data = $router->get(self::$path);
	}
	
	// @memberOf App - controller method execution
	public function execute(){
		global $log; 
		$controllerArray = $this->rout_data;
		$controllerClassName = $controllerArray['controller'];
		$classPath = CONTROLLER_PATH . $controllerClassName . DS . $controllerClassName . '.controller.php';
		
		if (__autoload($classPath) && class_exists($controllerClassName)) {
			$actionName = $controllerArray['action'];
			$objectOfController = new $controllerClassName();
			
			// Attention: 
			// `is_callable("{$class}::{$method}");` - will return false if method is not defined as static in PHP 5.2 
			// and will return true in PHP 5.3
			// is_callable($checkClassName), is_callable(array($controllerClassName,$actionName))
			if (method_exists($objectOfController, $actionName)) {
				$action_result = $objectOfController->$actionName(
					isset($controllerArray['vars']) ? $controllerArray['vars'] : array()
				);
				if ($action_result instanceof \Generator) {
					$action_result->next();
					$action_result->next();
					exit;
				}
				if ($action_result instanceof IActionResult) {
					// Execution an action
					$action_result->execute();
					exit;
				} 
				if (is_array($action_result)) {
					header('Content-Type: application/json');
					echo json_encode($action_result);
					exit;
				} 
					
				$log->error(
					'Trouble: ' . date('Y/m/d H:i:s') . 
					' controller-name: ' . $controllerArray['controller'] . 
					' action-name: ' . $controllerArray['action']
				);
				$log->error('Trouble action does not implements IAction result');
			} else {
				$log->error('Action not found: ' . $controllerClassName . '::' . $actionName);
			}
			throw new \Exception('The action could not be processed');
		} else {
			$errMessage = 'File "' . $classPath . 
			'" not found or class "' . $controllerArray['controller'] . 
			'" does not exist';
			$log->error($errMessage);
			throw new \Exception($errMessage);
		}
	}
}

abstract class AController {
	
	// Executing a ViewAction Instance
	// Attention: 
	// The action method cannot match the class name and begin with a digit.
	protected function view($viewName){
		return new ViewAction(get_class($this), $viewName);
	}
	
}

abstract class AAction {
	
	protected $headers;
	
	// @param {String} headerContent
	public function setHeader($headerContent){
		$this->headers []= $headerContent;
	}
	
	protected function renderHeaders(){
		foreach($this->headers as $header){
			header($header);
		};
	}
}

// The Interface that all actions must implement
interface IActionResult {
	// The method that will execute the action
	public function execute();
}

// Implementation of an Action of View
class ViewAction implements IActionResult{
	protected $viewName;
	protected $controllerName;
	protected $optArray;
	
	// @memberOf ViewAction
	// @param {Array} $optArray - an array of view properties
	function setOptionsArray($optArray){
		$this->optArray = $optArray;
	}
	
	// @memberOf ViewAction
	// @param {String} $controllerName
	// @param {String} $viewName
	function __construct($controllerName, $viewName){
		$this->viewName = $viewName;
		//$this->controllerName = strtolower($controllerName);
		$this->controllerName = $controllerName;
	}
	
	// @memberOf ViewAction - execution of an action of the view
	// <controllers folder>/{Controller name}/view/{view name}.view.php
	function execute(){
		$viewPath = CONTROLLER_PATH . $this->controllerName . DS . 'view' . DS . $this->viewName . '.view.php';
		$tempObj = new Utils\LayoutRender(FRAMEWORK_ROOT . '_masterpage' . DS);
		
		if (is_array($this->optArray)) {
			foreach($this->optArray as $key => $value) {
				$tempObj->set($key, $value);
			}
		}
		
		$tempObj->render($viewPath);
	}
}

class RedirectAction implements IActionResult{
	protected $url;

	// @memberOf RedirectAction
	// @param {String} $url - Url for redirection
	function __construct($url){
		$this->url = $url;
	}

	function execute(){
		if (isset($this->url)) {
			header("Location: " . $this->url);
		}
	}
}

// JSON action
class JsonAction extends AAction implements IActionResult{
	private $json;
	
	function __construct($arr){
		$this->parseArray($arr);
		$this->setHeader('Content-Type: application/json');
	}
	
	public function parseArray($arr){
		if(!is_array($arr)){
			$arr = array();
		}
		// Available since php 5.3
		// $this->json = json_encode($arr, JSON_FORCE_OBJECT);
		$this->json = json_encode($arr);
	}
	
	public function setPlainText($text){
		$this->json = $text;
	}
	
	function execute(){
		$this->renderHeaders();
		echo $this->json;
	}
}

// A custom mime type action
class ExtendFormatAction extends AAction implements IActionResult{
	public $content;
	
	function __construct($cont, $mime){
		$this->content = $cont;
		$this->setHeader("Content-type: " . $mime);
	}
	
	function execute(){
		$this->renderHeaders();
		echo $this->content;
	}
}

class EmptyAction implements IActionResult{
	function execute(){}
}

function response($payload = null, int $code = 200) {
    yield;

    http_response_code($code);

    if ($payload === null) return;

    if (is_array($payload) || is_object($payload)) {
        header('Content-Type: application/json');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    } else {
        header('Content-Type: text/plain; charset=utf-8');
        echo $payload;
    }
}

function strict_method(string $allowedMethod) {
    $currentMethod = $_SERVER['REQUEST_METHOD'] ?? '';

    if (strtoupper($currentMethod) !== strtoupper($allowedMethod)) {
        header('Content-Type: text/plain; charset=utf-8');
        http_response_code(405);
        echo "Method Not Allowed: only {$allowedMethod} requests are permitted.";
        die();
    }
}

function strict_xhr() {
    $isXHR = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
             strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if (!$isXHR) {
        header('Content-Type: text/plain; charset=utf-8');
        http_response_code(403);
        echo "Forbidden: this endpoint accepts only AJAX (XHR) requests.";
        die();
    }
}
