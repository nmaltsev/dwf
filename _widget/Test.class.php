<?php

class Test extends \Core\Utils\AWidget{
	const style = '';
	const script = '';
	
	public function __construct(){
		parent::__construct();
	}

	public function render(){ ?>
<p>Test widget</p>
	<?php
		}
}

?>