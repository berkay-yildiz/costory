<?php
session_start();
if(!(isset($_SESSION["username"]) && isset($_SESSION["id"]))){
	$newURL = "login.php?e=3";
	header('Location: '.$newURL);
};


$servername = "localhost";
$username = "stellaa2_costory";
$password = "CSO%YvAwSsTX";
$dbname = "stellaa2_costroy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$user_id = $_SESSION["id"];
$story_id = $_GET["i"];
$_SESSION["story_id"] = $story_id;

$sql = "SELECT id FROM writers WHERE user_id = '$user_id' AND story_id = '$story_id' LIMIT 1";
$result = $conn->query($sql);
if(!($result->num_rows > 0)) {
	$newURL = "index.php?e=2";
	header('Location: '.$newURL);
} else {
	$sql = "SELECT * FROM stories WHERE id = '$story_id' LIMIT 1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$story_name = $row["name"];
	$current_turn = $row["current_turn"];
	$admin_id = $row["admin_id"];
	$can_writers_invite = $row["can_writers_invite"];
	$isUsersTurn = 0;
	$current_time = time();
	$time_treshold = 6400;
	
	$sql2 = "SELECT writer_id, save_time FROM entries WHERE story_id = '$story_id' ORDER BY id DESC LIMIT 1";
	$result2 = $conn->query($sql2);
	$row2 = $result2->fetch_assoc();
	$last_save_time = $row2["save_time"];
	
	if($last_save_time + $time_treshold > $current_time) {
		$calcul = intval(($last_save_time - $current_time) / $time_treshold);
		
		for($k = 0; $k < $calcul; $k++) {
			
			$sql = "UPDATE stories SET current_turn = (CASE WHEN (SELECT id FROM writers WHERE id > '$current_turn' AND story_id = '$story_id' LIMIT 1) IS NULL THEN (SELECT id FROM writers WHERE id > 0 AND story_id = '$story_id' LIMIT 1) ELSE (SELECT id FROM writers WHERE id > $current_turn AND story_id = '$story_id' LIMIT 1) END) WHERE id = '$story_id'";

			if ($conn->query($sql) === TRUE) {
				$sql2 = "SELECT current_turn FROM stories WHERE story_id = '$story_id' LIMIT 1";
				$result2 = $conn->query($sql2);
				$row2 = $result2->fetch_assoc();
				$current_turn = $row2["current_turn"];
			} else {
				echo "Error updating record: " . $conn->error;
				$newURL = "story_index.php?i=$story_id&e=8";
				//header('Location: '.$newURL);
			}
			
		}
		
	}else {
		$calcul = 0;
	}
	
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<?php echo "<h4>Co Story . $story_name </h4>" ; 

?>
<a href="stories.php">stories</a>
<a href="index.php">index</a>
<?php 

echo "<br><br><b> Writers: </b>";


$sql = "SELECT id, user_id, color_code FROM writers WHERE story_id = '$story_id' ORDER BY id='$current_turn' DESC, id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	while($row = $result->fetch_assoc()) {
		$tmp_user_id = $row["user_id"];
		$tmp_writer_id = $row["id"];
		
		$sql2 = "SELECT name FROM users WHERE id = '$tmp_user_id' LIMIT 1";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$tmp_user_name = $row2["name"];
		
		//$quer_part = ($current_turn > $tmp_writer_id) ? " (id > '$current_turn' OR id > '$tmp_writer_id')" :  " (id > '$current_turn' OR id < '$tmp_writer_id')"
		
		$sql2 = "SELECT COUNT(id) as total FROM writers WHERE story_id = '$story_id' AND (id >= '$current_turn' OR id < '$tmp_writer_id') ";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$total = ($tmp_writer_id == $current_turn) ? "now" : $row2["total"];
		$isUsersTurn = ($tmp_user_id == $user_id && $total == "now" && $isUsersTurn != 1) ? 1 : $isUsersTurn;
		$color_code = $row["color_code"];
		echo " <b style = \"color: $color_code\" > $tmp_user_name ($total)</b> ";
	}
}

if($admin_id == $user_id OR $can_writers_invite == 1) {
	echo "<a href=\"invite_user.php?i=$story_id\">invite user</a>";
}

if($isUsersTurn == 1) {
	//echo "<br><br><b><a href=\"invite_user.php?i=$story_id\">write entry </a> (it is your turn!) </b>";
	echo "<br><br>
	<form action=\"add_entry.php\" method=\"post\">
	  <b>it is your turn!</b> write the your part:<br>
		<textarea name=\"content\" rows=\"10\" cols=\"50\" >
			
		</textarea>
		<input type=\"hidden\" name=\"story_id\" value= \"$story_id\">
	  <br><br>
	  <input type=\"submit\" value=\"send your part\">
	</form>
	";
}

if ($_GET["e"] == 1) {echo "<br><br><b> log in succsesfull </b>";}
else if ($_GET["e"] == 2) {echo "<br><br><b> you tried to enter a story that you are not a writer </b>";} 
else if ($_GET["e"] == 3) {echo "<br><br><b> you succesfully join the story</b>";} 
else if ($_GET["e"] == 4) {echo "<br><br><b> you succesfully join the story</b>";} 
else if ($_GET["e"] == 5) {echo "<br><br><b> your part added succesfully</b>";} 
else if ($_GET["e"] == 6) {echo "<br><br><b> err6</b>";}  
else if ($_GET["e"] == 7) {echo "<br><br><b> it is not your turn</b>";} 
else if ($_GET["e"] == 8) {echo "<br><br><b> err8 </b>";} 

$sql = "SELECT content, writer_id FROM entries WHERE story_id = '$story_id' ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	while($row = $result->fetch_assoc()) {
		$writer_id = $row["writer_id"];
		$sql2 = "SELECT color_code FROM writers WHERE id = '$writer_id' LIMIT 1";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$color_code = $row2["color_code"];
		echo "<p style = \"color: $color_code\">" . $row["content"] . "</p>";
	}
}

?>







</body>
</html>
