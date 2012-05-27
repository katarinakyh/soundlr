<?php
$PDO = new PDO("mysql:host=localhost;dbname=music_rating", "root", "");

function executeQuery($query, $id, $PDO){
	$stmt = $PDO -> prepare($query);
	// skapa ett nytt object
	
	$binds = array(':id' => $id);
	
	// Do query (you can make several bind the same query)
	
	$stmt -> execute($binds);
	//print_r($binds);
	
	// Fetch and send data
	$result_set = $stmt -> fetch(PDO::FETCH_ASSOC);
	// fetch LIMIT 1
	return $result_set;
}


$track = @$_GET['track'];
$id = @$_GET['id'];
//echo $id;

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
$result_set = executeQuery($query, $id,$PDO);

if(isset($_POST['submit_rate'])){
	$query = "
	INSERT INTO ratings 
	(song_id, ratings, user_id, categories_id)
	
	VALUES
	(:id,".$_POST['love'].",".$_SESSION['user_id'].", 1),
	(:id,".$_POST['rage'].",".$_SESSION['user_id'].", 2),
	(:id,".$_POST['speed'].",".$_SESSION['user_id'].", 3),
	(:id,".$_POST['hate'].",".$_SESSION['user_id'].", 4);
	
	";	
//	$posted_results = executeQuery($query, $id); FUNKAR INTE ÄN
}

?>

<?php
include ('header.php');
?>
<div class="wrapper">
	<table>
		<tr>
			<th>Track</th><th>Love</th><th>Rage</th><th>Speed</th><th>Hate</th><th>Artist</th><th>Album</th>
		</tr>
		<?php

		$i = 0;
		$j = 1;
		while ($i < $j) {
		echo "<tr>
		<td>". $result_set['track']."</td>
		<td>"
		?>
		<form action="post" action="<?php $_SERVER['PHP_SELF'];?>">
			<select name="love">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="4" selected="selected">5</option>
				<option value="1">6</option>
				<option value="2">7</option>
				<option value="3">8</option>
				<option value="4">9</option>
				<option value="4">10</option>
			</select>
			</td> <td>
			<select name="rage">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="4" selected="selected">5</option>
				<option value="1">6</option>
				<option value="2">7</option>
				<option value="3">8</option>
				<option value="4">9</option>
				<option value="4">10</option>
			</select></td>
			<td>
			<select name="speed">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="4" selected="selected">5</option>
				<option value="1">6</option>
				<option value="2">7</option>
				<option value="3">8</option>
				<option value="4">9</option>
				<option value="4">10</option>
			</select></td>
			<td>
			<select name="hate">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="4" selected="selected">5</option>
				<option value="1">6</option>
				<option value="2">7</option>
				<option value="3">8</option>
				<option value="4">9</option>
				<option value="4">10</option>
			</select>
		</form>
		<?php echo "</td>
<td>" . $result_set['artist'] . "</td>
<td>" . $result_set['album'] . "</td></tr>";
			$i++;
			}
		?>
		<tr>
			<td> <audio controls=\"controls\">
			<source src=\"media/juicy.ogg\" type=\"audio/ogg\" />
			<source src=\"song.mp3\" type=\"audio/mp3\" />
			Your browser does not support the audio tag.
			</audio>
			</td>
			<th colspan=4>
			<input type="submit" name="submit_rate" value="Vote" />
			</th>
			<td></td>
			<td></td>
			</tr>
			</table>
			</div>
			<?php
			include ('footer.php');
			?>
