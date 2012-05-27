<?php
include('../server/connect.php');

$table1 = 'song';
$table2 = 'song_artist';
$var = '';
$var = @$_GET['q'];

$stmt = $PDO->prepare("
	SELECT song.name as track, song.id as id, artist.name as artist, artist.id as artist_id, album.name as album FROM song 

	INNER JOIN song_artist
	ON song_artist.song_id = song.id 
	LEFT JOIN artist
	ON artist.id = song_artist.artist_id

	INNER JOIN song_album
	ON song_album.song_id = song.id 
	LEFT JOIN album
	ON album.id = song_album.album_id

	WHERE song.name LIKE CONCAT(:find) ");

$binds = array(':find' => "%".$var."%");

// Do query (you can make several bind the same query)

$stmt->execute($binds);
$count = $stmt->rowCount();

// Fetch and send data 
$result_set = $stmt->fetchAll(PDO::FETCH_ASSOC); // fetch LIMIT 1
// print_r($result_set); 

?>
<?php include('header.php'); ?>


<div class="wrapper">
	<div class="playlist"><?php include('playlist.php'); ?></div>
	<div class="admin">
	<table>
	  <tr>
	     <th colspan="3" >Search</td>
		<form name="form" action="index.php" method="get">
	   </tr>
	   <tr>
	     <td>Term</td>
	     <td> <input type="text" name="q" /></td>
	     <td><input type="submit" name="Submit" value="Search" /></td>
	  </form>
	</table>
	
	<?php
	if ($var == "")
	  {
		  echo "<div class=\"result_message\">Please enter a search...</div>";
		  exit;
	  }
	
		
	if ($count == 0) {
		echo "<h4>Results</h4>";
		echo "<div class=\"result_message\">Sorry, your search: &quot;" . $var . "&quot; retured zero results</div>";
	} else {
		echo "<div class=\"result_message\">Your search for: &quot;" . $var . "&quot; returned " .count($result_set). " results</div>";
		
		$i = 0; ?>
	</div>
	
		<?php
		echo "<table class=\"searchresults\">";
		echo "<tr><th>Add</th><th>Track</th><th>Artist</th><th>Album</th></tr>";
		
		while ($i < $count) {
			echo "<tr><th>" ?>
			<form action="<?php $_SERVER['PHP_SELF'];?>" method="post">				
						<input type="hidden" name="song" value="tracknumber">
						<input type="submit" name="submit" value="+" />					
			</form>
	</th><td><?php echo "<a href=\"song.php?track=" . $result_set[$i]['track']. "&id=".$result_set[$i]['id']."&artist_id=\"". $result_set[$i]['artist_id']."\">". $result_set[$i]['track']. "</a></td><td> " .$result_set[$i]['artist'].  "</td><td>".$result_set[$i]['album']."</td></tr>";
			$i++; 
		}
		echo "</table>";
		
	}
	
	?>  
			</table>
		
	</div>
		
<?php include('footer.php'); ?>
