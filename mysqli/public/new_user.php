<?php session_start()?>
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
		include_once('user.php');

		if (isset($_POST['submit'])) {
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			
			
			
			//Create query
			include_once('connect.php');
			
			$mysqli = new Connect(); 
			$mysqli -> connectdb();
			
			$qry = "INSERT INTO user  (user_name, user_email, password) VALUES (?,?,?)";
			$result = $mysqli -> prepare($qry);
			$result -> bind_param('sss', $username, $email, $password);
			$result-> execute();

			printf("%d Row inserted.\n", $result->affected_rows);
			
			/* close statement and connection */
			$result->close();

			/* close connection */
			$mysqli->close();
		}
		?>
	
<?php include('footer.php'); ?>

