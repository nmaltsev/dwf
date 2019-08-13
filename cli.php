<?php 
echo "DWF 0.40, 2013-2019\n";

class CreateController {
    private $className;
    private $frameworkRoot;

    function __construct($controllerName, $frameworkRoot) {
        $this->className = $controllerName;
        $this->frameworkRoot = $frameworkRoot;
    }

    function create(): void {
        $controllerPath = $this->frameworkRoot . '_controller' . DIRECTORY_SEPARATOR . $this->className;

        if (file_exists($controllerPath)) {
            echo $this->className . " controller is already exist\n";
            return;
        }
        mkdir($controllerPath, 0777, true);
        $viewPath = $controllerPath . DIRECTORY_SEPARATOR . 'view';
        mkdir($viewPath, 0777, true);
        file_put_contents(
            $controllerPath . DIRECTORY_SEPARATOR . $this->className . '.controller.php',
            $this->createControllerClass()
        );
        file_put_contents(
            $viewPath . DIRECTORY_SEPARATOR . 'index.view.php',
            $this->createViewTemplate()
        );

        echo  $this->className . " controller created\n";
    }

    private function createControllerClass(): string {
        $template =  '<?php
class {className} extends \Core\AController{

    function __construct(){}

    function a_index($conf){
        $view = $this->view(\'index\');
        
        return $view;
    }
}
?>
        ';
        $template = str_replace('{className}', $this->className, $template);
        return $template;
    }

    private function createViewTemplate(): string {
        $template =  '<?php 
$this->useMasterPage(\'simple\');
$this->set(\'title\', \'An index page\');
?>
<h1>Index view</h1>
        ';
        return $template;
    }
}

if (isset($argv[1])) {
    (new createController(
        $argv[1],
        realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
    ))->create();
} else {
    echo "Creation of new controller: `cli.php <Controller class name>`\n";
}

