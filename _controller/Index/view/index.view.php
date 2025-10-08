<?php 
$this->useMasterPage('simple');
$this->set('title', 'Home page');
?>
<h1>Home page</h1>
<p>Time: <?php echo $this->get('time'); ?></p>
<a href="/en/profile/1">Profile</a>
