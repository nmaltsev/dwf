<?php

// 	Class for working with data stored in the PHP source file
class SourceStore {
	
	//	@memberOf SourceStore - save array in php source file
	//	@param {Array} $array - data
	//	@param {String} $fname
	// 	@return {Bool} - status of the operation 
	static function store($array, $fname) {
		file_put_contents($fname, '<?php return ' . var_export($array, true) . ';');    
		return true;
	}
	
	//	@memberOf SourceStore - restoring data from the php source file
	//	@param {Array} $array - data
	//	@param {String} $fname
	// 	@return {Array|Bool} - false if file not found
	static function restore($fname) {
		
		if (file_exists($fname)) {
			$result = include($fname);
		} else {
			$result = false;
		}
		
		return $result;
	}

}