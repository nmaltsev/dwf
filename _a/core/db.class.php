<?php

namespace Core\Utils;

defined('FRAMEWORK_ROOT') or die('execute script out of the scope!');
include(DOMAIN_PATH . 'dbconnection.php');

class DBC implements ADBConnection {

	static $pdo;

	// @memberOf Model - Creates connect with DB
	// @return {Bool} - True if the connection is a success
	static function connect(){
	// Attention: `?` must be escaped in the name of tables and columns.
	// <) pdo escaping (http://ruseller.com/lessons.php?rub=37&id=1381)
	// <)http://net.tutsplus.com/tutorials/php/php-database-access-are-you-doing-it-correctly/

	// Example of usage:
	// $preQuery = $this->pdo->prepare('SHOW ?');
	// $preQuery->execute(array('tables'));
	// print_r($preQuery->fetch());

		try {
			self::$pdo = new \PDO(
				// class properties:
				constant('DBC::connectionString'), 
				constant('DBC::login'), 
				constant('DBC::password'),
				array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'')
			);
			return true;
		} catch (\PDOException $e) {  
		    // TODO: make format display in log
			return false;
		}
	}

	// @memberOf Model
	// @return {Bool} - True if the connection is a success
	static function connectIfNot(){
		return (!isset(self::$pdo)) ? self::connect() : true;
	}
}

abstract class AModel {
	protected function includeORM(){
		if (!DBC::connectIfNot()) {
			echo 'trouble with connection\n';
			return;
		} 
		include(FRAMEWORK_ROOT . \SystemFolders::App . DS . 'notorm/NotORM.php');
		$this->db = new \NotORM(DBC::$pdo);
	}
}

?>