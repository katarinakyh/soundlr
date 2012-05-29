<?php include_once('session.php');?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<title>Soundlr | Login</title>
		<meta content='text/html; charset=UTF-8' http-equiv='Content-Type' />
		<link href='css/style.css' media='all' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<h1 class='logo_top'></h1>
		<h2>Welcome 
		<?php if(@$_GET['l']){
				echo "to Soundlr";
			}else if((isset($_SESSION['username']) && $_SESSION['username'] != 'none')){
				echo $_SESSION['username']. ' ' .$_SESSION['userid'];
				echo '<h2><a href="login.php?l=logout">Log out</a></h2>';
			}else{
				echo "to Soundlr";
			}
		?>	
		</h2>
		<hr />
		