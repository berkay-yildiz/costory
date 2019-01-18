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

session_start();
$story_name = $_POST["story_name"];
$join_status = $_POST["join_status"];
$content = nl2br(addslashes($_POST["beginning"]));
$can_invite = ($join_status == 0 || $_POST["can_invite"] == 1) ? 1 : 0;
$admin_id = $_SESSION["id"];
$color_code = rand_color();
$current_time = time();
$sql = "INSERT INTO stories (name, admin_id, join_status, can_writers_invite)
	VALUES ('$story_name', '$admin_id', '$join_status', '$can_invite')";

if ($conn->query($sql) === TRUE) {
	
	$sql = "SELECT id FROM stories WHERE name = '$story_name' AND admin_id = '$admin_id' ORDER BY id DESC LIMIT 1";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		
		while($row = $result->fetch_assoc()) {
			$story_id = $row["id"];
		}
		
		$sql = "INSERT INTO writers (user_id, story_id, color_code)
		VALUES ('$admin_id', '$story_id', '$color_code')";
		
		if ($conn->query($sql) === TRUE) {
			
			$sql2 = "SELECT id FROM writers WHERE user_id = '$admin_id' AND story_id = '$story_id'";
			$result2 = $conn->query($sql2);
			$row2 = $result2->fetch_assoc();
			$writer_id = $row2["id"];
			
			$sql = "INSERT INTO entries (writer_id, story_id, content, save_time)
			VALUES ('$writer_id', '$story_id', '$content', '$current_time')";
			
			if ($conn->query($sql) === TRUE) {
				
				
				$sql = "UPDATE stories SET current_turn='$writer_id' WHERE id='$story_id'";

				if ($conn->query($sql) === TRUE) {
					$newURL = "create_story.php?e=1";
					header('Location: '.$newURL);
				} else {
					//echo "Error updating record: " . $conn->error;
					$newURL = "create_story.php?e=9";
					header('Location: '.$newURL);
				}
		
		
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
				$newURL = "create_story.php?e=5";
				//header('Location: '.$newURL);
			}
			
		} else {
			//echo "Error: " . $sql . "<br>" . $conn->error;
			$newURL = "create_story.php?e=4";
			header('Location: '.$newURL);
		}
		
	} else {
		$newURL = "create_story.php?e=3";
		header('Location: '.$newURL);
	}
	
} else {
	//echo "Error: " . $sql . "<br>" . $conn->error;
	$newURL = "create_story.php?e=2";
	header('Location: '.$newURL);
}


$conn->close();

function rand_color() {
    return '#' . str_pad(dechex(mt_rand(0, 0x888888)), 6, '0', STR_PAD_LEFT);
}
?>

