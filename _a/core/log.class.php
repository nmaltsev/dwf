<?php

namespace Core\Utils;


class Log {
	
	static $defaultLogFilePath;

	static function resolveLogFile($fname): string {
		if (empty($fname)) {
			return self::$defaultLogFilePath;
		} else {
			return LOG_PATH . $fname;
		}

	}
	
	static function write($message, $fname = null) {
		if (empty(self::$defaultLogFilePath)) return;
		$logPath = self::resolveLogFile($fname);

		$fh = fopen($logPath, 'a') or die('Can not open log file '.$logPath);
		fwrite($fh, '[' . date('d/m/Y H:i:s (Z)') . '] ' . $message . "\n");
		fclose($fh);
	}

	// Example:
	// $logger = logger(__DIR__ . '/log');
	// $logger->send('Foo');
	// $logger->send('Bar');
	static function logger($fname = null) {
		if (empty(self::$defaultLogFilePath)) return;
		$logPath = self::resolveLogFile($fname);
		
		$fileHandle = fopen($logPath, 'a');
		while (true) {
			fwrite($fileHandle, yield . "\n");
		}
	}
	
	static function writeJSON($arr, $fname) {
		if (!is_array($arr)) return;
		if (empty(self::$defaultLogFilePath)) return;
		$logPath = self::resolveLogFile($fname);

		// PHP constants http://www.php.net/manual/en/json.constants.php
		// JSON_FORCE_OBJECT available since 5.3 !
		// $content = json_encode($arr, JSON_FORCE_OBJECT) . ',';
		$content = json_encode($arr) . ',';
		
		$fh = fopen($logPath, 'a') or die('Can not open log file '.$logPath);
		fwrite($fh, $content);
		fclose($fh);
	} 
}
?>
