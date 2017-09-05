<?php
	
	$self = htmlspecialchars($_SERVER['PHP_SELF']);
	
	require_once $_SERVER['DOCUMENT_ROOT'] . "/script/mysql.php";
	if($boolAdmin==true){
		require $_SERVER['DOCUMENT_ROOT'] . "/script/adminpanel.php";
	}
	
?>

<!doctype html5>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700' rel='stylesheet' type='text/css'>
		<meta charset="utf-8">
		<title><?php echo $pageTitle; ?> | Bottlec[r]ap Zine</title>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<?php if($boolAdmin==true && !isset($previewSite)){ echo '<link rel="stylesheet" type="text/css" href="/css/admin.css" />';} ?>
	</head>
	<body>
		<div id="body">
			
			<?php require $_SERVER['DOCUMENT_ROOT'] . "/script/leftbar.php"; ?>
			
			<div id="contentbody">	
				<div id="content">