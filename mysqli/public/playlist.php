
<form action="" method="post" name="playlist_form" id="playlist_form">
	<table>
		<tr>
			<th colspan="3" >Add new Playlist</td>
		</tr>
		<tr>
			<td>name</th> <td>
			<input type="text" value="" name="playlist_name" >
			</td>
			<td>
			<input class="submit_button" type="submit" value="Add" />
			</td></td>
			<input type="hidden" name="company_id" value="" />
			<input type="hidden" name="add_playlist" value="playlist_form" />
</form>
</tr>
</table>
<table>
	<tr>
		<th colspan="4" >Your Playlists</td>
	</tr>
	<tr>
		<th>Name</th>
		<th> Current </th>
		<th># Tracks</th>
		<th> Date </th>
	</tr>
	<?php
		include_once ('../server/connect.php');
		
		function updatePlaylists($PDO) {
			$stmt2 = $PDO -> prepare(
							"SELECT playlists.name, playlists.date, COUNT(playlist_song.song_id) AS songs 
							FROM playlists  
							LEFT JOIN playlist_song 
							ON playlists.id = playlist_song.playlist_id
							WHERE user_id = :userid
							GROUP BY playlists.id  
							ORDER BY playlists.id DESC"
						);
			$binds2 = array(":userid" => $_SESSION['userid']);
			$stmt2 -> execute($binds2);
			$playlists = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
			
			$count = count($playlists);
			//echo $count;
			for($i = 0; $i < count($playlists); $i++) {
				echo "<tr><td>" . $playlists[$i]['name']."</td>";
				echo "<td></td>";
				echo "<td>". $playlists[$i]['songs']."</td>";
				echo "<td>" . $playlists[$i]['date']."</td></tr>"; 
			}
		}


		if ((isset($_POST["add_playlist"])) && $_POST["playlist_name"] != NULL) {
		
			$name = $_POST['playlist_name'];
			$stmt1 = $PDO -> prepare("INSERT INTO playlists (name, user_id, date) 
									VALUES (:name, :user_id, NOW())");
			$binds1 = array(":name" => $name, ":user_id" => $_SESSION['userid']);
			$stmt1 -> execute($binds1);
			$_POST["playlist_name"] = NULL;
			//updatePlaylists($PDO);	
			//get all playlists by logged in user
		}
		updatePlaylists($PDO);

		?>
</table>
