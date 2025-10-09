<?php 
// DWF v0.41 08/10/2025 (based on v0.40 19/03/2015)

abstract class SystemFolders {
    const App = '_a';
    const Static = '_static';
    const Controller = '_controller';
    const Domain = '_domain';
    const AppCore = 'core';
    // TODO
    const StaticLog = 'log';
}

// Constants:
define('DS', DIRECTORY_SEPARATOR);
define('FRAMEWORK_ROOT', realpath(dirname(__FILE__) . DS) . DS);
define('APP_PATH', FRAMEWORK_ROOT . SystemFolders::App . DS . SystemFolders::AppCore . DS);
define('STATIC_PATH', FRAMEWORK_ROOT . SystemFolders::Static . DS);
define('CONTROLLER_PATH', FRAMEWORK_ROOT . SystemFolders::Controller . DS);
define('DOMAIN_PATH', FRAMEWORK_ROOT . SystemFolders::Domain . DS);

include(APP_PATH . 'core.php');

// To help the built-in PHP dev server, check if the request was actually for
// something which should probably be served as a static file
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    
    if (is_file($file)) {
        return false;
    } 
} 

// The application launch
$conf = \Core\System::init();
$project = new \Core\App($conf);
$project->execute();
