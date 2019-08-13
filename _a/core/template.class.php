<?php

abstract class ATemplate {
	function __construct(){ }
	
	protected $masterPage;
	protected $stack = array();
	protected $masterPageFolder = '';
	
	abstract function render($viewName); // protected || public
	
	abstract protected function useMasterPage($name); 
			
	public function set($key, $val) {
		if (isset($key) && isset($val)) {
			$this->stack[$key]=$val;
			return true;
		} else {
			return false;
		}
	}
	
	protected function get($key) {
		return isset($this->stack[$key]) ? $this->stack[$key] : '';
	}

	function __destruct(){	}
}

// LayoutRender - A template engine

// - It uses a regular PHP script as a view.
// - The view should display the $echo variable
// - Method useMasterPage() is available in a view.
// - It is possible to add a caching
class LayoutRender extends ATemplate {
	
	function __construct($masterPageFolder) {
		$this->masterPageFolder = $masterPageFolder;
	}
	
	const w = '[[widgets]]';
	
	public function render($viewName) {
		static $loaded;

		if (!isset($loaded)) {
			$loaded = true;
		} else {
			return;
		}
		
		ob_start();		
			include($viewName);
			$content = ob_get_contents();
		ob_end_clean();	

		if (isset($this->masterPage) && file_exists($this->masterPage)) {
			ob_start();
				include($this->masterPage);
				$content = ob_get_contents();
			ob_end_clean();	
			$wList = implode(',' , Widgets::$list);
			$content = str_replace(self::w, $wList, $content); 
		}
		
		echo $content;
	}

	protected function useMasterPage($name) { 
		// private in child!
		$this->masterPage = $this->masterPageFolder . $name . '.php';
	}

	private $scriptQueue = array();
	
	// @memberOf LayoutRender - This method adds an URL to the $scriptQueue queue
	// @param {String} $url
	public function addScriptURL($url){
		array_push($this->scriptQueue, htmlspecialchars($url));
	}
	
	// @memberOf LayoutRender - Rendering script tags on the page
	public function renderScriptQueue(){
		foreach($this->scriptQueue as $scriptUrl){
			echo '<script src="' , $scriptUrl , '"></script>';
		}
	}

}

?>
