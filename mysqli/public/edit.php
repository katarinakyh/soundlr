<?php
		include_once('header.php');
		include_once('../server/connect.php');		

		$playlistid = @$_GET['id']; // the song 
		$playlistname = @$_GET['name']; // the playlist
		$playlistowner = @$_GET['owner']; // 
		$user = $_SESSION['userid'];

		if(isset($_POST['submit_rights'])) {
		echo $_POST['rights']; 
			
			$checkquery = "
				SELECT users_id FROM playlist_rights
				WHERE playlist_id = :playlistid
				AND users_id = :userid
			";
			$bindcheck = array(':playlistid' => $playlistid, ':userid' => $_POST['users']);
			
			$checkuser = executeQuery($checkquery, $bindcheck, $PDO);
			$ifDelete = false;
			$ifqurry = true;
			if($checkuser['affected_rows'] == 1){
				if($_POST['rights'] == 0){
					$right_query = "
						DELETE FROM playlist_rights
						WHERE users_id = :userid AND playlist_id = :playlistid";
						$ifDelete = true;
				}else{
					$right_query = "
								UPDATE playlist_rights
								SET right_to_change = :rights
								WHERE users_id = :userid AND playlist_id = :playlistid";
				}
			}else if($_POST['rights'] != 0){
				$right_query = "INSERT INTO playlist_rights (users_id, playlist_id, right_to_change)
								VALUES (:userid, :playlistid, :rights)";
			
			}
			else{
				$ifqurry = false;	
			}
			if($ifqurry){
					if($ifDelete){
						$bindsright = array(
											":userid" => $_POST['users'], 
											":playlistid" => $_POST['playlist']
											);
					}
					else{
						$bindsright = array(
											":userid" => $_POST['users'], 
											":playlistid" => $_POST['playlist'], 
											":rights" => ($_POST['rights'] -1)
											);
						}
					$right_query = executeQuery($right_query, $bindsright, $PDO);
				}
		}

		if(isset($_POST['remove'])) { 
			$remove_query = $PDO -> prepare("DELETE FROM playlist_song WHERE playlist_song.id = :songid"); 
			$bindsdel = array(":songid" => $_POST['playlistsong']);
			$remove_query -> execute($bindsdel);
		}

		$stmt2 = $PDO -> prepare(
					"SELECT song.name AS songs,  
					playlist_song.id AS songsid, 
					artist.name as artists,
					GROUP_CONCAT(album.name) as albums
					
					FROM song

					LEFT JOIN playlist_song 
					ON playlist_song.song_id = song.id

					LEFT JOIN artist
					ON artist.id = song.artist_id

					LEFT JOIN song_album
					ON song_album.song_id = song.id

					LEFT JOIN album
					ON album.id = song_album.album_id

					WHERE playlist_song.playlist_id = :playlistid
					GROUP BY song.name
					ORDER BY song.name ASC"
					
					);

		$binds2 = array(":playlistid" => $playlistid);
		$stmt2 -> execute($binds2);
		$playlists = $stmt2 -> fetchAll(PDO::FETCH_ASSOC);
	
		$count = count($playlists);
		echo "<h1>".$playlistname."</h1>";
		echo "<table><tr><th>Song</th><th>Artist</th><th>Album</th><th>Remove</th></tr>";		
		for($i = 0; $i < count($playlists); $i++) {
			echo "<tr><td>" . $playlists[$i]['songs']."</td>";
			echo "<td>".$playlists[$i]['artists']."</td>";
			echo "<td>". $playlists[$i]['albums']."</td>";
			?><td> <form action="" method="post">
				<input type="hidden" name="playlistsong" value="<?php echo $playlists[$i]['songsid'];  ?>">
				<input type="submit" name="remove" value="X"> 
				</form></td></tr> <?php
		}
		echo "</table>";

	
		if($playlistowner == $user){
			
		$stmt_user = $PDO -> prepare(
					"SELECT user.user_name AS username, user.id AS userid 
					FROM user
					WHERE NOT user.id = :userid
					ORDER BY user.user_name ASC"
					);

		$binds = array(":userid" => $playlistowner);
		$stmt_user -> execute($binds);
		$userlist = $stmt_user -> fetchAll(PDO::FETCH_ASSOC);
	?>
	<table><tr><th>Playlist name</th><th>User</th><th>Privileges</th><th>Save</th></tr>		
		<tr>
			<td><?php echo $playlistname; ?> </td>
			<td>
				<form action="" method="post">
				<select name="users" class="width">"; 
				<?php
					for($j = 0; $j < count($userlist); $j++) { 
						echo "<option value=\"".$userlist[$j]['userid']."\">". $userlist[$j]['username'] ."</option>";
					}
				?>
				</select>
			</td>
			<td> 
				<input type="hidden" name="owner" value="<?php echo $_SESSION['userid'];  ?>">						
				<input type="hidden" name="playlist" value="<?php echo $playlistid;  ?>">
				<select name="rights" class="width">
					<option value="0" selected="selected">no privileges</option>
					<option value="1">can listen</option>
					<option value="2">can edit and listen</option>
				</select>
			</td>
			<td>
				<input type="submit" name="submit_rights" value="save" />				
			</form> 
			</td>
		</tr>
	</table>
<br /><br />
<?php } ?>