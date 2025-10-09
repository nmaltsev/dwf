<?php 
$this->useMasterPage('simple');
$this->set('title', 'Home page');
?>
<h1>Home page</h1>
<p>Time: <?php echo $this->get('time'); ?></p>
<a href="/en/profile/3">Group</a>
<a href="/en/profile/12/1">Profile</a>

<style>
    nav a+a {
        margin-left: 1rem;
    }
</style>
<nav>
    <a href="/api/0.1/login" target="_blank">Login</a>
    <a href="/api/0.1/logout" target="_blank">Logout</a>
    <a href="/api/0.1/whoami" target="_blank">Whoami</a>
</nav>

