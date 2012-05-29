<?php

if ((isset($_POST["add_playlist"])) && ($_POST["add_playlist"] == "playlist_form")) {

	include_once('../server/connect.php');
	$name = $_POST['playlist_name'];
	$stmt1 = $PDO -> prepare("INSERT INTO playlists (name, user_id) 
							VALUES (:name, :user_id)");
	$binds1 = array(":name" => $name, ":user_id" => $_SESSION['userid']);
	$stmt1 -> execute($binds1);
	
    echo "<div class=\"alert\"> playlist added </div>";
	
	//get all playlists by logged in user
	function updatePlaylists($PDO){
		$stmt2 = $PDO -> prepare("SELECT * FROM playlists WHERE user_id = :userid");
		$binds2 = array(":userid" => $_SESSION['userid']);
		$stmt2 -> execute($binds2);
		$result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC); // fetch LIMIT 1
		print_r($result2);
	}
	updatePlaylists($PDO);
}
	
?>
<form action="" method="post" name="playlist_form" id="playlist_form">
 <table>
   <tr>
     <th colspan="3" >Add new Playlist</td>
   </tr>

   <tr>
     <td>name</th>
       <td><input type="text" value="" name="playlist_name" ></td>
     <td> <input class="submit_button" type="submit" value="Add" /></td></td>
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
   
   <tr>
     <td>First play list</td>
     <td>  </td>
       <td>13</td>
     <td> 2012-02-12 </td>
      </tr>

 <tr>
     <td>Summer 2012</td>
     <td> *** </td>
       <td>4</td>
     <td> 2012-02-16 </td>
     
   </tr>
 </table>


