<?php 
	include_once('session.php');
		$_SESSION['username'] = 'none';
		$_SESSION['user_id'] = 0;
	include_once('header.php'); 
	
?>
		<div class="admin">
			<form id="loginForm" name="loginForm" method="post" action="login-exec.php">
				<table border="0" align="center" cellpadding="2" cellspacing="0">
					<tr>
						<th colspan="2">Soundlr</th>
					</tr>
					<tr>
						<td><b>Login name</b></td>
						<td>
						<input name="username" type="text" id="username" />
						</td>
					</tr>
					<tr>
						<td><b>Password</b></td>
						<td>
						<input name="password" type="password" id="password" />
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
						<input type="submit" name="submit" value="Login" />
						</td>
					</tr>
					<tr>
						<td><a href="new_user.php"> Sign Up</a></td>
						<td> Getting started with Soundlr is easy.</td>
					
					</tr>
					<?php?>
				</table>
			</form>
			<?php
			if ((isset($_SESSION['LOGIN_FAIL']) && $_SESSION['LOGIN_FAIL'] == TRUE) || (isset($_SESSION['LOGIN_ERR']) && $_SESSION['LOGIN_ERR'] == TRUE)) {
				echo "You have entered the wrong username or password!";
			}
			?>
			</p>
		</div>
	
<?php include('footer.php'); ?>

      
