<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
<link href="<?php echo getURL('assets/css/styles.css');?>" type="text/css" rel="stylesheet" />
<style type="text/css">
body, h1, p {
	font-family: 'Roboto', sans-serif;
	font-weight:300;
}
#logo {
	width:300px;
}
</style>
<title>Manifesto</title>

</head>

<body>
    <img id="logo" src="<?php echo getURL('assets/img/manifesto.svg');?>"/>
    <?php include $_view->find($_partial);?>

</body>
</body>
</html>