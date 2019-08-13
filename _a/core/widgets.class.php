<?php

namespace Core\Utils;

abstract class AWidget implements \ArrayAccess {

	// Collection of widget properties
	private $stack = array();

	// @param {String} $key - key of property
	// @return {String} - value of property
	protected function get($key){
		return isset($this->stack[$key]) ? $this->stack[$key] : '';
	}

	public function puts($key){
		if (isset($this->stack[$key])) {
			echo $this->stack[$key];
		}
	}

	// @memberOf ArrayAccess - Checks if a property exist
	// @param {String} $offset - a key
	// @return {Bool}
	function offsetExists($offset) {
        return isset($this->stack[$offset]);
	}

	// @memberOf ArrayAccess - get property by index
	// @param {String} $offset - a key
	// @return {String} - value of property
	function offsetGet($offset) {
		return $this->get($offset);
	}

	// @memberOf ArrayAccess
	// @param {String} $offset - a key
	function offsetSet($offset, $value ) {
		$this->stack[$offset] = $value;
	}

	// @memberOf ArrayAccess
	// @param {String} $offset - a key
	function offsetUnset($offset) {
		unset($this->stack[$offset]);
	}
	private static $WID = 0; // Widget counter
	protected $wid; // ID of current widget

	public function __construct(){
		$this->wid = AWidget::$WID;
		AWidget::$WID++;
	}
	
	// @memberOf AWidget - returns html code of a widget
	// @return {String}
	public function execute(){
		ob_start();	
		$this->render();
		$wContent = ob_get_contents();
		ob_end_clean();	
		return $wContent;
	}

	public function id() {
		return $this->wid;
	}
}

class Widgets {
	static $list = array();

	// Returns an instance of widget
	// @param {String} $widgetName - the name of widget
	// @return {AWidget | null}
	static function get($widgetName){
		$widget_path = FRAMEWORK_ROOT . '_widget' . DS . $widgetName . '.class.php';

		if (
			class_exists($widgetName) ||
			(\Core\__autoload($widget_path) && class_exists($widgetName))
		) {
			$instance = new $widgetName();
			
			if (!in_array($widgetName, self::$list)) {
				array_push(self::$list, $widgetName);
			}
			return $instance;
		} else {
			return null;
		}
			
	}
}

?>
