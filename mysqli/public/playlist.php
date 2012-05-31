<?php
	if(isset($_POST['currentlist'])){
		$_SESSION['currentlist'] = $_POST['currentlist'];
		//echo $_SESSION['currentlist']; 
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
		</tr>
	</table>
</form>
<form action="" method="post" name="currentplaylist" id="currentplaylist">
	<table class="kurew">
		<tr>
			<th colspan="5" >Your Playlists</td>
		</tr>
		<tr>
			<th> Current </th>
			<th> Name </th>
			<th> Edit </th>
			<th># Tracks</th>
			<th> Date </th>
		</tr>
		<?php
			include_once ('../server/connect.php');

			$updatequery = "
				SELECT playlists.id, playlists.name, playlists.user_id, playlists.date, 
				COUNT(playlist_song.song_id) AS songs 
				FROM playlists  
				LEFT JOIN playlist_song 
				ON playlists.id = playlist_song.playlist_id
				WHERE playlists.user_id = :userid
				GROUP BY playlists.id  
				ORDER BY playlists.id DESC";

			$adminquery = "
			 	SELECT playlists.id, playlists.name, playlists.user_id, 
				playlists.date, playlist_rights.right_to_change AS rights, 
				COUNT(playlist_song.song_id) AS songs 
				
				FROM playlists  
				LEFT JOIN playlist_song 
				ON playlists.id = playlist_song.playlist_id
				LEFT JOIN playlist_rights 
				ON playlists.id = playlist_rights.playlist_id
				
				WHERE playlist_rights.users_id = :userid
				
				GROUP BY playlists.id  
				ORDER BY playlists.id DESC";

				
			function updatePlaylists($qry, $PDO) {
				$updatebinds = array(":userid" => $_SESSION['userid']);
				$playlists = executeQuery($qry,$updatebinds, $PDO);
				$playlistcount = $playlists['affected_rows'];
				$playlists = $playlists['rows'];
				
				for($i = 0; $i < $playlistcount; $i++) {
						
					echo "<tr><td>";
					if(isset($playlists[$i]['rights']) && $playlists[$i]['rights'] == FALSE){
						echo " <input type=\"radio\" class=\"currentlist\" name=\"currentlist\" value=\"" .$playlists[$i]['id']."\" disabled=\"disabled\" />";
						
					}else{
						echo " <input type=\"radio\" class=\"currentlist\" name=\"currentlist\" value=\"" .$playlists[$i]['id']."\"";
						if(isset($_SESSION['currentlist']) && $_SESSION['currentlist'] == $playlists[$i]['id']){
							echo "checked = \"checked\"";	
						}
						echo "/>";
						
					}
					echo "</td>";	
					echo "<td>" . $playlists[$i]['name']."</td>";
					echo "<td>";
					if(!isset($playlists[$i]['rights'])){
						echo "<a href=\"edit.php?id=".$_SESSION['currentlist']."&owner=".$playlists[$i]['user_id'];
						echo "&name=".$playlists[$i]['name']."\"> Edit </a>";
					}
					echo "</td>";
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
			
			updatePlaylists($updatequery,$PDO); ?>
			<tr>
				<th colspan="5" >Other playlists</td>
			</tr>
			<tr>
				<th> Current </th>
				<th>Name</th>
				<th> Edit </th>
				<th># Tracks</th>
				<th> Date </th>
			</tr>
			
			
		<?php
			updatePlaylists($adminquery,$PDO);
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