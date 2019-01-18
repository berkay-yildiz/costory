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
$story_id = $_POST["story_id"];
$content = nl2br(addslashes($_POST["content"]));


$sql2 = "SELECT writers.id as id, stories.current_turn as current_turn FROM writers INNER JOIN stories ON writers.story_id = stories.id WHERE writers.user_id = '$user_id' AND writers.id = stories.current_turn";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
	$contiune = 1;
	$row2 = $result2->fetch_array();
	$writer_id = $row2["id"];
	$current_turn = $row2["current_turn"];
} else {
	$newURL = "story_index.php?i=$story_id&e=7";
	header('Location: '.$newURL);

}


if($contiune == 1) {
	
	$color_code = rand_color();
	$save_time = time();
	$sql = "INSERT INTO entries (writer_id, story_id, content, save_time)
		VALUES ('$writer_id', '$story_id', '$content', '$save_time')";
		
	if ($conn->query($sql) === TRUE) {
		
		$sql = "UPDATE stories SET current_turn = (CASE WHEN (SELECT id FROM writers WHERE id > '$current_turn' AND story_id = '$story_id' LIMIT 1) IS NULL THEN (SELECT id FROM writers WHERE id > 0 AND story_id = '$story_id' LIMIT 1) ELSE (SELECT id FROM writers WHERE id > $current_turn AND story_id = '$story_id' LIMIT 1) END) WHERE id = '$story_id'";

		if ($conn->query($sql) === TRUE) {
			$newURL = "story_index.php?i=$story_id&e=5";
			header('Location: '.$newURL);
		} else {
			//echo "Error updating record: " . $conn->error;
			$newURL = "story_index.php?i=$story_id&e=8";
			header('Location: '.$newURL);
		}

	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
		$newURL = "story_index.php?i=$story_id&e=6";
		//header('Location: '.$newURL);
	}
	
}

$conn->close();

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
?>