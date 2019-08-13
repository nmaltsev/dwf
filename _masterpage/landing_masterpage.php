<!DOCTYPE HTML>
<html>
<head>
	<title><?php echo $this->get("title");?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" type="image/x-icon" href="/_static/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="/_static/favicon.ico">
    <link rel="icon" type="image/png" href="/_static/favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="<?php echo $this->get('description');?>">
    <meta name="keywords" content="<?php echo $this->get('keywords');?>">
	<link rel="stylesheet" type="text/css" href="/_static/style/style.css"/>
</head>
<body>
<?php echo $content; ?>	
</body>
</html>
