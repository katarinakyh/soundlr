<?php
	include_once ('session.php');
	include_once ('../server/connect.php');
	
	$error = false;
	
	if (isset($_POST['submit'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		//Input Validations
		if ($username == '') {
			$error = true;
			$_SESSION['LOGIN_ERR'] = TRUE;
		}
	
		if ($password == '') {
			$error = true;
			$_SESSION['LOGIN_ERR'] = TRUE;
		}
	
		//If there are input validations, redirect back to the login form
		if ($error) {
			header("location: login.php");
		}
	
		//Create query
		$userquery = "SELECT * FROM user WHERE user_name = :name AND password = :password";
		$userbinds = array(':name' => $username, ':password' => $password);
	
		$result_set = executeQuery($userquery, $userbinds, $PDO);
		$count = $result_set['affected_rows'];
		$result_set = $result_set['rows'];
		
		if ($count == 1) {
			$_SESSION['username'] = $result_set[0]['user_name'];
			$_SESSION['userid'] = $result_set[0]['id'];
			$_SESSION['LOGIN_FAIL'] = FALSE;
			$_SESSION['LOGIN_ERR'] = FALSE;
			header('location: index.php');
		} else {
			$_SESSION['LOGIN_ERR'] = TRUE;
			header('location: login.php');
		}
	}
?>
