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

$story_id = $_POST["story_id"];
$tmp_user_name = $_POST["user_name"];
$user_id = $_SESSION["id"];

$sql2 = "SELECT can_writers_invite, admin_id FROM stories WHERE id = '$story_id'";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();
$can_writers_invite = $row2["can_writers_invite"];
$admin_id = $row2["admin_id"];

if($can_writers_invite == 0) {
	if($admin_id != $user_id) {
		$newURL = "invite_user.php?i=$story_id&e=1";
		header('Location: '.$newURL);
	}
} else {
	$sql2 = "SELECT id FROM writers WHERE user_id = '$user_id' AND story_id = '$story_id'";
	if (!($result->num_rows > 0)) {
		$newURL = "invite_user.php?i=$story_id&e=2";
		header('Location: '.$newURL);
	}
}

$sql2 = "SELECT id FROM users WHERE name = '$tmp_user_name'";
$result2 = $conn->query($sql2);
if (!($result2->num_rows > 0)) {
	$newURL = "invite_user.php?i=$story_id&e=5";
	header('Location: '.$newURL);

} else {
	$row2 = $result2->fetch_assoc();
	$tmp_user_id = $row2["id"];
	
	$sql = "INSERT INTO invitations (user_id, story_id)
			VALUES ('$tmp_user_id', '$story_id')";
			
	if ($conn->query($sql) === TRUE) {
		$newURL = "invite_user.php?i=$story_id&e=4";
		header('Location: '.$newURL);
	} else {
		//echo "Error: " . $sql . "<br>" . $conn->error;
		$newURL = "invite_user.php?i=$story_id&e=3";
		header('Location: '.$newURL);
	}
}



?>