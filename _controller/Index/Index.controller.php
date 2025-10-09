<?php
class Index extends \Core\AController{

	function __construct(){}

	function a_index($conf) {
		global $log;
		
		$view = $this->view('index');
		// $log->error('From Index action');
		// $log->debug([
		// 	'type'=> get_class($view)
		// ]);
		
		$view->setOptionsArray(array(
			'time' => date('Y/m/d H:i:s')
	   	));

	   	return $view;
	}

	function a_widgets($conf) {
		global $log;
		$log->info('test123');
		// Demo page with widgets
		return $this->view('widgets');
	}
}
