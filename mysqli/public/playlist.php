<?php
	if(isset($_POST['currentlist'])){
		$_SESSION['currentlist'] = $_POST['currentlist'];
		echo $_SESSION['currentlist']; 
	}	
?>
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
	<form action="" method="post" name="currentplaylist" id="currentplaylist">
	<table>
		<tr>
			<th colspan="5" >Your Playlists</td>
		</tr>
		<tr>
			<th> Current </th>
			<th>Name</th>
			<th> Edit </th>
			<th># Tracks</th>
			<th> Date </th>
		</tr>
		<?php
			include_once ('../server/connect.php');
			
			function updatePlaylists($PDO) {
				$updatequery = "SELECT playlists.id, playlists.name, playlists.user_id, playlists.date, 
								COUNT(playlist_song.song_id) AS songs 
								FROM playlists  
								LEFT JOIN playlist_song 
								ON playlists.id = playlist_song.playlist_id
								WHERE playlists.user_id = :userid
								GROUP BY playlists.id  
								ORDER BY playlists.id DESC";
				$udatebinds = array(":userid" => $_SESSION['userid']);
				$playlists = executeQuery($updatequery,$udatebinds, $PDO);
				$playlistcount = $playlists['affected_rows'];
				$playlists = $playlists['rows'];
				
				for($i = 0; $i < $playlistcount; $i++) {
					echo "<tr><td> <input type=\"radio\" class=\"currentlist\" name=\"currentlist\" value=\"" .$playlists[$i]['id']."\"";
					if(isset($_SESSION['currentlist']) && $_SESSION['currentlist'] == $playlists[$i]['id']){
						echo "checked = \"checked\"";	
					} 
					echo "/>";
					echo "</td>";	
					echo "<td>" . $playlists[$i]['name']."</td>";
					echo "<td><a href=\"edit.php?id=".$_SESSION['currentlist']."&owner=".$playlists[$i]['user_id']."&name=".$playlists[$i]['name']."\"> Edit </a></td>";
					echo "<td>". $playlists[$i]['songs']."</td>";
					echo "<td>" . $playlists[$i]['date']."</td></tr>"; 
				}
			}
	
	
			if ((isset($_POST["add_playlist"])) && $_POST["playlist_name"] != NULL) {
				$addquery= "INSERT INTO playlists (name, user_id, date) 
							VALUES (:name, :user_id, NOW())";
				$binds1 = array(":name" => $_POST['playlist_name'], ":user_id" => $_SESSION['userid']);
				executeQuery($addquery,$binds1, $PDO);
				$_POST["playlist_name"] = NULL;
			}

			updatePlaylists($PDO);
		?>
	</table>
</form>
<script type="text/javascript" src="js/jquery-1.7.min.js"></script>

<script>
	$('.currentlist').click(function(){
		$('#currentplaylist').submit();
	});
</script>
<script>
	
</script>