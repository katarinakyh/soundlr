<?php
//Start session
session_start();
	//echo $_POST['username'] . '<br/>';
	//echo $_POST['password'] . '<br/>';
	$error = false;
		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$password = $_POST['password'];
			//echo $username. ' '. $password;
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
				//exit();
			}

			//Create query
			$mysqli = new mysqli("localhost", "root", "", "music_rating");
			if ($mysqli->connect_errno) {
			    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			
			$result = $mysqli -> prepare("SELECT * FROM user WHERE user_name = ? AND password = ?");
			$result -> bind_param('ss', $username, $password);
			$result-> execute();

			if ($result) {
				$dbArray = $result -> fetch();
				if (count($dbArray) != 1) {
					echo '<p class="statusmsg">The username or password you entered is incorrect, or you haven\'t yet activated your account. Please try again.</p><br/><input class="submitButton" type="button" value="Retry" onClick="location.href=' . "'login.php'\">";
					$_SESSION['LOGIN_ERR'] = TRUE;
					return;
				}

				$_SESSION['username'] = $username;
				$_SESSION['userid'] = $dbArray[0]['id'];
				$_SESSION['LOGIN_FAIL'] = FALSE;
				$_SESSION['LOGIN_ERR'] = FALSE;
				
				echo '<p class="statusmsg"> You have successfully logged in. You will now be redirected to the homepage.</p>';
				header( 'location: index.php' ) ;
			}
			/* close statement and connection */
			$result->close();

			/* close connection */
			$mysqli->close();
			
		}
?>
