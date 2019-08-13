<?php 
$this->useMasterPage('simple');
$this->set('title', 'An index page');
?>
<h1>Index view</h1>
<p>Welcome to DWF</p>
<p><?php echo $this->get('time'); ?></p>
