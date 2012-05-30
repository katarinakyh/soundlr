<?php
		include_once ('header.php');
		include_once ('../server/connect.php');
		
		$track = @$_GET['track'];
		$id = @$_GET['id'];
		$rate = 4;	
		
		//basic query to load the right song
		$query = "
			SELECT song.name as track, artist.name as artist, album.name as album
			FROM song
		
			LEFT JOIN song_artist
			ON song_artist.song_id = song.id
			
			LEFT JOIN artist
			ON artist.id = song_artist.artist_id
			
			LEFT JOIN song_album
			ON song_album.song_id = song.id
			
			LEFT JOIN album
			ON album.id = song_album.album_id
			
			WHERE song.id = :id
			
			";
		$binds = array(':id' => $id);
		
		$result_set = executeQuery($query, $binds, $PDO);
		$count = $result_set['affected_rows'];
		$result_set = $result_set['rows'][0];
		//print_r($result_set);
		?>
		
		<div class="wrapper">
			<form method="post" action="">
				<table border = "1">
					<tr>
						<th>Track</th><th>Love</th><th>Rage</th><th>Speed</th><th>Hate</th><th>Artist</th><th>Album</th>
					</tr>
					<?php
		
		$i = 0;
		$j = 1;
		while ($i < $j) {
		echo "<tr>
		<td>". $result_set['track']."</td>"?>
		<td>
					
					<select name="love">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5" selected="selected">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
					</td> <td>
					<select name="rage">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5" selected="selected">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select></td>
					<td>
					<select name="speed">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5" selected="selected">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select></td>
					<td>
					<select name="hate">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5" selected="selected">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
			</form>
			<?php echo "</td>
		<td>" . $result_set['artist'] . "</td>
		<td>" . $result_set['album'] . "</td></tr>";
				$i++;
				}
			?>
			<tr>
				<td rowspan="2">
					<audio controls="controls">
						<source src="media/juicy.ogg" type="audio/ogg" />
						<source src="song.mp3" type="audio/mp3" />
						Your browser does not support the audio tag.
					</audio>
				</td>
				
					<?php
					if (isset($_POST['submit'])) {
	
						$rate = array($_POST['love'],$_POST['rage'],$_POST['speed'],$_POST['hate']);
						$checkquery = "
							SELECT users_id FROM ratings
							WHERE song_id = :id
							AND users_id = :userid
							";
						$checkbinds = array(':id' => $id, 'userid' => $_SESSION['userid']);
						$check = executeQuery($checkquery, $checkbinds, $PDO);
						//print_r($check);
						if($check['rows'] == NULL){
							$playlistquery = "
								INSERT INTO ratings (song_id, rating, users_id, categories_id)
								VALUES (:song_id, :rating, :userid, :catid)
							";
						}else{
							$playlistquery = "
								UPDATE ratings
								SET rating = :rating
								WHERE song_id = :song_id AND users_id = :userid AND categories_id = :catid
							";
						}
						
						for($i = 0; $i < count($rate); $i++){
							$binds2 = array(
								':song_id'=> $id,
								':rating' => $rate[$i],
								':userid' => $_SESSION['userid'],
								':catid' => $i + 1
								);
							 executeQuery($playlistquery, $binds2, $PDO);
						
						}
					}

					$rates = array();
					$empty = array();
					$ratequery = "
						SELECT AVG(ratings.rating) AS avg, ratings.categories_id 
						FROM ratings 
						INNER JOIN categories ON ratings.categories_id = categories.id 
						WHERE ratings.categories_id = :catid 
						AND song_id = :id
						GROUP BY ratings.categories_id";

					for($j = 0; $j < 4; $j++){
						$ratebinds = array(':catid' => $j + 1, ':id'=> $id);
						$temp = executeQuery($ratequery, $ratebinds, $PDO);
						if($temp['affected_rows'] < 1){
							
						}else{
							$rates[] = $temp['rows'];
						}
					}
					//print_r($rates);
					if($rates){
						for($k = 0; $k < 4; $k++){
							echo "<td>". round($rates[$k][0]['avg'],1). "</td>";
						}
					}else{
						for($l = 0; $l < 4; $l++){
							echo "<td>0</td>";
						}
					}
					unset($rates);

						

					 

					?>
				<td rowspan="2"></td>
				<td rowspan="2"></td>
			</tr>
			<tr>	
					<th colspan=4 class="rate"><input type="submit" name="submit" value="Vote" /></th>
			</tr>
				
				
			</tr>
			</table>
		</div>
		
		<?php
		include_once('genre.php');
		include_once('footer.php');
		?>
		