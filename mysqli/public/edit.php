<?php
		include_once('header.php');
		include_once('../server/connect.php');		

		if(isset($_POST['submit_rights'])) {
			$rights;		
			echo $_POST['users']; 
			
			$rights = $_POST['users'];
			

			$right_query = $PDO -> prepare("INSERT INTO playlist_rights (users_id, playlist_id, right_to_change)
			VALUES (:userid, :playlistid, ".$rights .")");

			$bindsright = array(":userid" => $_POST['user'], ":playlistid" => $_POST['playlist']);
			$right_query -> execute($bindsright);
		
		}

		if(isset($_POST['remove'])) { 
			$remove_query = $PDO -> prepare("DELETE FROM playlist_song WHERE playlist_song.id = :songid"); 
			$bindsdel = array(":songid" => $_POST['playlistsong']);
			$remove_query -> execute($bindsdel);
		}


		$playlistid = @$_GET['id']; // the song 
		$playlistname = @$_GET['name']; // the playlist
		$playlistowner = @$_GET['owner']; // 
		$user = $_SESSION['userid'];


		
		$stmt2 = $PDO -> prepare(
					"SELECT song.name AS songs,  song.id AS songsid, artist.name AS artists, album.name AS albums
					FROM song
					LEFT JOIN playlist_song 
					ON playlist_song.id = song.id
					LEFT JOIN artist
					ON artist.id = song.artist_id
					LEFT JOIN song_album
					ON song_album.song_id = song.id
					LEFT JOIN album
					ON album.id = song_album.album_id
					WHERE playlist_song.playlist_id = :playlistid
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
	
		?> <table><form action="" method="post">
			<tr>
			<td><select name="users" class="width">  <?php
			for($j = 0; $j < count($userlist); $j++) { 
				echo "<option value=\"".$userlist[$j]['userid']."\">". $userlist[$j]['username'] ."</option>";
			}
			echo "</select></td>";
			echo "<td>"; ?> 				
		<input type="hidden" name="user" value="<?php echo $userlist[$j]['userid'];  ?>">						
		<input type="hidden" name="playlist" value="<?php echo $playlistid;  ?>">
		<select name="privilage" class="width">
				<option value="1">can edit</option>
				<option value="2">can edit and listen</option>
		</select>
	 
			</td><td>playlist: <?php echo "$playlistname"; ?></td>
			<td><input type="submit" name="submit_rights" value="share" />				
			</form> </td></tr>
		<?php
		echo "</table><br /><br />";
		} ?>
