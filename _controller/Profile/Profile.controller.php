<?php
class Profile extends \Core\AController{

	function __construct(){}

	function a_index($conf) {
        $lang = $conf['lang'];
		
		$view = $this->view('index');
		$view->setOptionsArray(array(
			'time' => date('Y/m/d H:i:s'),
            'lang' => $lang,
	   ));

	   return $view;
	}
}
