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
$from = $_GET["f"];

if ($from == 1) {
	$sql2 = "SELECT id FROM invitations WHERE user_id = '$user_id' AND story_id = '$story_id'";
	$result2 = $conn->query($sql2);
	if ($result2->num_rows > 0) {
		$contiune = 1;
	}
}

if($contiune == 1) {
	
	$color_code = rand_color();
	
	$sql = "INSERT INTO writers (user_id, story_id, color_code)
		VALUES ('$admin_id', '$story_id', '$color_code')";
		
	if ($conn->query($sql) === TRUE) {
		
		$newURL = "story_index.php?i=$story_id&e=4";
		header('Location: '.$newURL);
		
	} else {
		//echo "Error: " . $sql . "<br>" . $conn->error;
		$newURL = "create_story.php?e=4";
		header('Location: '.$newURL);
	}
	
}

$conn->close();

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}
?>