<?php
session_start();
if(!(isset($_SESSION["username"]) && isset($_SESSION["id"]))){
	$newURL = "login.php?e=3";
	header('Location: '.$newURL);
};
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css">
<style>
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
</style>
</head>
<body>


<h4>Co Story</h4>

<a href="index.php">index</a><br><br>
<?php 
if ($_GET["e"] == 1) {echo "<br><b> err 1 </b><br>";} 
else if ($_GET["e"] == 2) {echo "<br><b> err 2 </b><br>";} 


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

$sql = "SELECT id, user_id, story_id, result FROM invitations WHERE user_id = '$user_id' ORDER BY FIELD(result, 0, 1, 2), id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "
	<table>
		<tr>
			<th>Story Name</th>
			<th>Story Admin</th>
			<th>Numb of Writers</th>
			<th>Join Status</th>
			<th>Status</th>
			<th>Accept / Refuse / Enter</th>
		</tr>
	";
	while($row = $result->fetch_assoc()) {
		
		$user_id = $row["user_id"];
		$story_id = $row["story_id"];
		$accept_status = $row["result"];
		$invite_id = $row["id"];
		
		$sql2 = "SELECT name, admin_id, join_status FROM stories WHERE id = '$story_id' LIMIT 1";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$story_name = $row2["name"];
		$admin_id = $row2["admin_id"];
		
		$sql2 = "SELECT name FROM users WHERE id = '$admin_id' LIMIT 1";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$admin_name = $row2["name"];
		
		$sql2 = "SELECT COUNT(id) as number_of_writers FROM writers WHERE story_id = '$story_id'";
		$result2 = $conn->query($sql2);
		$row2 = $result2->fetch_assoc();
		$number_of_writers = $row2["number_of_writers"];
		

		
		
		
		if($join_status == 0) {
			$join_status_text = "open to everyone";
			
		} else if ($join_status == 1) {
			$join_status_text = "need to apply";
			
		} else if ($join_status == 2) {
			$join_status_text = "only invited people";
			
		}
		
		if($accept_status == 0) {
			$accept_status_text = "waiting for reply";
			
		} else if ($accept_status == 1) {
			$accept_status_text = "you accept";
			
		} else if ($accept_status == 2) {
			$accept_status_text = "you decline";
			$action_button_content = "";
		}
		
		
		$sql2 = "SELECT id FROM writers WHERE user_id = '$user_id' AND story_id = '$story_id'";
		$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0) {
			$action_button_content = "<a href=\"story_index.php?i=$story_id\">enter</a>";
			$accept_status_text = "you accept";
		} else {
			$action_button_content = "<a href=\"answer_invitation.php?a=1&i=$invite_id\">accept</a><br><a href=\"answer_invitation.php?a=2&i=$invite_id\">refuse</a>";
			$accept_status_text = "waiting for reply";
		}
		
		
		echo "<tr>";	
			echo "<td>" . $story_name . "</td>";
			echo "<td>" . $admin_name . "</td>";
			echo "<td>" . $number_of_writers . "</td>";
			echo "<td>" . $join_status_text . "</td>";
			echo "<td>" . $accept_status_text . "</td>";
			echo "<td>" . $action_button_content . "</td>";	
		echo "</tr>";
		
		$sql = "UPDATE invitations SET seen=1 WHERE id='$invite_id'";

		if ($conn->query($sql) === TRUE) {
			//done..
		} else {
			//echo "Error updating record: " . $conn->error;
			echo "<br><b>err2</b>";
		}
		
	}
	echo "</table>";

} else {
	echo "empty";
}
$conn->close();
?>

</body>
</html>
