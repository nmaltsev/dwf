<?php

class WidgetAssistent extends \Core\AController {
	private $classes = array();
	private $widgetDir;
	
	function __construct(){
		if (array_key_exists('w', $_GET)) {
			$this->classes = explode(',', $_GET['w']);
		}
		
		$this->widgetDir = FRAMEWORK_ROOT . '_widget' . DS; 
	}

	private function collectProperties($mime, $propertyName) {
		$res = new \Core\ExtendFormatAction('', $mime);
		foreach ($this->classes as $widgetName) {
			$widget_path = $this->widgetDir . $widgetName . '.class.php';
			
			if (
				class_exists($widgetName) ||
				(\Core\__autoload($widget_path) && class_exists($widgetName))
			) {
				if (defined($widgetName . '::' . $propertyName)) {
					// It does not work in php 5.2:
					// $res->content .= $widgetName::style; 
					$res->content .= constant($widgetName . '::' . $propertyName);
				}
			}
		}
		return $res;
	}
	
	// get /core/wa/widget.css
	// @return {ExtendFormatAction}
	function style() {
		return $this->collectProperties('text/css', 'style');
	}

	// get /core/wa/widget.js
	// @return {ExtendFormatAction}
	function script(){
		return $this->collectProperties('text/javascript', 'script');
	}
}
?>
