<?php 
$this->useMasterPage('simple');
$this->set('title', 'Profile page');
?>
<h1>Profile page</h1>
<p>Lang: <?php echo $this->get('lang'); ?></p>
<p>Time: <?php echo $this->get('time'); ?></p>
<a href="/">Home</a>
