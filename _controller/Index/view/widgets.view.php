<?php 
$this->useMasterPage('simple');
$this->set('title', 'Demo page with widgets');
?>
<link rel="stylesheet" type="text/css" href="/core/wa/widget.css?w=[[widgets]]">
<h1>Widgets</h1>
<div>
    <?php echo Widgets::get('AlertButton')->render(); ?>
</div>
<div>
    <?php echo Widgets::get('AlertButton')->render(); ?>
</div>
<script src="/core/wa/widget.js?w=[[widgets]]"></script>