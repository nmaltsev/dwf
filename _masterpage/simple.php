<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo $this->get("title");?></title>
	<!-- Фавикон предотвращает второй запрос браузера к серверу (поумолчанию ищет фавикон)-->
	<link rel="icon" type="image/x-icon" href="/_static/img/favicon.ico">
</head>
<body>
<?php echo $content; ?>	
</body>
</html>
