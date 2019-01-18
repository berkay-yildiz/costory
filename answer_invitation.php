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
$invite_id = $_GET["i"];
$answer = $_GET["a"];

$sql2 = "SELECT story_id FROM invitations WHERE user_id = '$user_id' AND id = '$invite_id'";
$result2 = $conn->query($sql2);
if ($result2->num_rows > 0) {
	$row2 = $result2->fetch_array();
	$story_id = $row2["story_id"];
	if($answer == 2) {
		
	$sql = "UPDATE invitations SET result=2 WHERE id='$invite_id'";
	
	if ($conn->query($sql) === TRUE) {
		$newURL = "invitations.php?e=3";
		header('Location: '.$newURL);
	} else {
		//echo "Error updating record: " . $conn->error;
		$newURL = "invitations.php?e=4";
		header('Location: '.$newURL);
	}

	} else if($answer == 1) {
		
		$sql = "UPDATE invitations SET result=1 WHERE id='$invite_id'";
	
		if ($conn->query($sql) === TRUE) {
			$newURL = "include_user.php?f=1&i=$story_id";
			header('Location: '.$newURL);
		} else {
			//echo "Error updating record: " . $conn->error;
			$newURL = "invitations.php?e=6";
			header('Location: '.$newURL);
		}
		
	} else {
		$newURL = "invitations.php?e=1";
		header('Location: '.$newURL);
	}

} else {
	$newURL = "invitations.php?e=2";
	header('Location: '.$newURL);
}








?>