
<?php include('header.php'); ?>
		<div class="admin">
		<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">
			<table>
				<tr>
						<th colspan="2">Soundlr Sign-up</th>
					</tr>
				<tr>
					<td>Username:</td><td>
					<input type="text" placeholder="Username" value="" name="username" />
					</td>
				</tr>
				<tr>
					<td>Password:</td><td>
					<input type="password" placeholder="******" value="" name="password" />
					</td>
				</tr>
				<tr>
					<td>Email:</td><td>
					<input type="email" placeholder="email@email.com" value="" name="email" />
					</td>
				</tr>
				<tr>
					<td>
					<input type="reset" value="Reset" name="reset" />
					</td><td>
					<input type="submit" name="submit" value="Submit" />
					</td>
				</tr>
			</table>
		</form>
		</div> 

		<?php

		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			
					//Create query
			include_once ('../server/connect.php');
			$stmt = $PDO -> prepare("INSERT INTO user (user_name, user_email, password, create_date) VALUES (:name,:email,:password, NOW())");
			$binds = array(':name' => $username, ':email' => $email, ':password' => $password);
		
			$stmt -> execute($binds);
			$insert_id = $PDO -> lastInsertId();
				if($insert_id){
				$_SESSION['username'] = $username;
				$_SESSION['userid'] = $insert_id;
				$_SESSION['LOGIN_FAIL'] = FALSE;
				$_SESSION['LOGIN_ERR'] = FALSE;
				header('location: index.php');
			}else{
				echo "The username is already in use, please try another one.";
			}			
		}
		?>
	
<?php include('footer.php'); ?>

