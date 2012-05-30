<?php
	
	
	
	$genrequery = "
		SELECT song.name as track, artist.name as artist, album.name as album, GROUP_CONCAT(genre.name) AS genre, 		song.id AS id
		FROM song
		LEFT JOIN artist
		ON artist.id = song.artist_id
		LEFT JOIN song_album
		ON song_album.song_id = song.id
		LEFT JOIN album
		ON album.id = song_album.album_id
		LEFT JOIN genre_song
		ON genre_song.song_id = song.id
		LEFT JOIN genre
		ON genre.id = genre_song.genre_id
		WHERE genre_song.genre_id IN (SELECT genre_song.genre_id
						FROM genre_song
						WHERE song_id = :songid)
		GROUP BY song.name, artist.name, album.name
		ORDER BY song.name
		";
		
	$genrebinds = array(':songid' => $id);
	$genres = executeQuery($genrequery, $genrebinds, $PDO);
	$genrecount = $genres['affected_rows']; 
	$genres = $genres['rows'];
	//print_r($genres);
?>

			<table class="searchresults genre">
			<tr>
				<th>Song</th>
				<th>Artist</th>
				<th>Album</th>
				<th>Genre</th>
			</tr>
			
			<?php 
			
			while ($i < $genrecount) { ?>
			
			<td><?php echo "<a href=\"song.php?track=" . $genres[$i]['track']. "&id=".$genres[$i]['id']."\">". $genres[$i]['track']. "</a></td>
			<td>". $genres[$i]['artist']."</td>
			<td>". $genres[$i]['album']."</td>
			<td>". $genres[$i]['genre']."</td>
			</tr>";
			$i++; 
			}
			echo "</table>";		