<?php
class Test1 extends \Core\AController{

    function __construct(){}

    function a_index($conf){
        $view = $this->view('index');
        
        return $view;
    }
}
?>
        