<?php
class ResponseErrors extends AController {
	
	private $plugs = [
		['/\.js$/i', 'text/javascript', ''],
		['/\.css$/i', 'text/css', '']
	];
	
	function __construct(){
		$this->plugs[]= ['/\.(?:png|jpg|gif)$/i', 'image/png', file_get_contents(dirname(__FILE__) . '/view/noimage.png')];
	}

	function a404(){
		// get plugs for a static content

		foreach($this->plugs as $plug) {
			if(preg_match($plug[0], App::$path)){
				return new ExtendFormatAction($plug[2], $plug[1]);
			}
		}

		return $this->view('a404');	
	}

	function a403(){
		return $this->view('a404');
	}
}
?>
