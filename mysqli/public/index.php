<?php
	include_once('header.php');
	include_once('../server/connect.php');
	
	$var = '';
	$order_type = 'song.name';
	if(isset($_GET['submit']) XOR isset($_GET['sort_type'])){
			
		$var = @$_GET['search'];
		$order = @$_GET['sort_type'];
		
		 
		if ($order == "artist") { 
		 	$order_type = "artist.name";	
		} else if ($order == "song") { 
			$order_type = "song.name";
		} else {
			$order_type = "album.name"; 	
		}
		
		
		$query = "
			SELECT song.name as track, song.id as id, 
			GROUP_CONCAT(artist.name) as artist, artist.id as artist_id, 
			GROUP_CONCAT(album.name) as album
			
			FROM song 
			INNER JOIN artist
			ON artist.id = song.artist_id
		
			INNER JOIN song_album
			ON song_album.song_id = song.id 
			LEFT JOIN album
			ON album.id = song_album.album_id
		
			WHERE song.name LIKE CONCAT(:find) 
			OR artist.name LIKE CONCAT(:find)
			OR album.name LIKE CONCAT(:find) 
			
			GROUP BY song.name, album.name, artist.name
			ORDER BY ".$order_type." ASC";
		
		
		$binds = array(':find' => "%".$var."%");
		
		$result_set = executeQuery($query, $binds, $PDO);
		$count = $result_set['affected_rows'];
		$result_set = $result_set['rows'];
		
		//print_r($result_set);
		//echo $order_type;
	}

	if (isset($_POST['submit_song_to_playlist'])) {
		$playlist = $_SESSION['currentlist'];
		$song = $_POST['song'];
		
		$insertquery = "INSERT INTO playlist_song (playlist_id, song_id)
						VALUES (:playlist, :song)";
		$insertbinds = array(":playlist" => $playlist, ":song" => $song);
		
		$playlistresult = executeQuery($insertquery, $insertbinds, $PDO);
		$countplaylist = $playlistresult['affected_rows'];
		$playlistresult = $playlistresult['rows'];
//		print_r($playlistresult);	
//		echo "end of query ".$playlist.' song: '.$song; 
	}
	?>
	
	
	<div class="wrapper">
		<div class="playlist"><?php include('playlist.php'); ?></div>
		<div class="search">
		<table>
		  <tr>
		     <th colspan="3" >Search</td>
			<form name="form" action="index.php" method="get">
		   </tr>
		   <tr>
		     <td>Term</td>
		     <td> <input type="text" name="search" /></td>
		     <td><input type="submit" name="submit" value="Search" /></td>
		  </form>
		</table>
		
		<?php
		if ($var == "")
		  {
			  echo "<div class=\"result_message start\">Please enter a search...</div>";
			  exit;
		  }
			$i = 0; ?>
	
			<?php if (isset($_POST['submit_song_to_playlist'])) { 
				echo "You added ". $_POST['track'] ." to your current playlist."; 
			} ?>	</div>
		
			
			<table class="searchresults">
			<tr><th colspan="4">
			<?php
					if ($count == 0) {

			echo "<div class=\"result_message\">Sorry, your search: &quot;" . $var . "&quot; retured zero results</div>";
		} else {
			echo "<div class=\"result_message\">Your search for: &quot;" . $var . "&quot; returned " .count($result_set). " results</div>";
			?>
			
			</th></tr>	
			<tr><th>Add</th><th><a href="index.php?search=<?php echo $var ?>&sort_type=song">Song</a>
				</th><th><a href="index.php?search=<?php echo $var ?>&sort_type=artist">Artist</a></th>
				<th><a href="index.php?search=<?php echo $var ?>&sort_type=album">Album</a></th></tr>
			<?php
			while ($i < $count) {
				echo "<tr><th>" ?>
				<form action="" method="post">				
					<input type="hidden" name="song" value="<?php echo $result_set[$i]['id'];  ?>">	
					<input type="hidden" name="track" value="<?php echo $result_set[$i]['track'];  ?>">						
					<input type="hidden" name="playlist" value="<?php echo $_SESSION['currentlist'];  ?>">
					<input type="submit" name="submit_song_to_playlist" value="+" />				
				</form>
			</th>
			<td><?php echo "<a href=\"song.php?track=" . $result_set[$i]['track']. "&id=".$result_set[$i]['id']."&artist_id=\"". $result_set[$i]['artist_id']."\">". $result_set[$i]['track']. "</a></td><td> ". $result_set[$i]['artist']."</td><td> ". $result_set[$i]['album']. "</td></tr>";
				$i++; 
			}
			echo "</table>";
		}
		
		?>  
		</table>
			
		</div>
			
	<?php include('footer.php'); ?>


	

	
