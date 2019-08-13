<?php
class Index extends \Core\AController{

	function __construct(){}

	function a_index($conf) {
		
		$view = $this->view('index');
		
		$view->setOptionsArray(array(
			'time' => date('Y/m/d H:i:s')
	   ));

	   return $view;
	}

	function a_widgets($conf) {
		\Core\Utils\Log::logger()->send('test123');
		// Demo page with widgets
		return $this->view('widgets');
	}
}
