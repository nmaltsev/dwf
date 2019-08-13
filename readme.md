# DWF

DWF is a web framework that follows the MVC design pattern.
(C) 2013-2015

## The development guide

### Controllers and Actions
Controllers:
- implements `AController` abstract class;
- hosted in the `_controller` folder;
- the controller file name template  is `{Class name}.controller.php`;

Action is a public method of the class.

``` php
class Error extends \Core\AController{
	function __construct(){}
  // When view belongs to the same class
  function a404(){
		return $this->view('404');
	}
	function a403(){
		return new \Core\ViewAction(__CLASS__, 'a404');
	}
}
```

An example of an action method with variables and options for the view:
``` php
// @param {Array} $conf - array with routing options
function a_index($conf) {
	$viewResult = new \Core\ViewAction(__CLASS__, 'index');
	// $viewResult = $this->view('index');

	// send options in view 
	$viewResult->setOptionsArray(array(
	 	'content' => $landingId,
	 	'conf' => $landingConf
	));
}
```

The routing options describes in the routing file `_domain/routing.php` 
``` php
  'promalp/{landing}/' => 'index::a_index',
```
So, the `$conf` will get an array:
``` php
array(
	'landing' => 'something'
)
```

### Views
- folder with views is hosted in the controller folder;
- template of view filename is `_controller/{Controller name}/view/{view name}.view.php`.

```html
<?php 
$this->useMasterPage('simple');
$this->set('title', 'An index page');
?>
<h1>Index view</h1>
<p><?php echo $this->get('time'); ?></p>
```

### Widgets
Widgets are blocks of HTML / CSS / JS code that integrates into a page. Widgets are stateless.


## Usefull libraries:
- An ORM library - (NotORM) [https://github.com/vrana/notorm] 
- Mail library - (PHPMailer)[https://github.com/PHPMailer/PHPMailer]
- Less compiler - (LessPHP)[https://github.com/leafo/lessphp/]

## Commands to:
1. run a web server: `./run.sh`
2. install dependences: `./install.sh`


