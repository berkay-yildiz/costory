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
$story_ids = array();
$story_ids_text = " ";
$sql = "SELECT story_id FROM writers WHERE user_id = '$user_id'";
$result = $conn->query($sql);
if($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		array_push($story_ids, $row["story_id"]);
	}
	
	foreach ($story_ids as $value) {
		if ($value != $story_ids[0]) {
			$story_ids_text = $story_ids_text . ", ";
		}
		$story_ids_text = $story_ids_text .  "id=" . $value . " DESC";
	}
} else {
	$story_ids_text = "id DESC";
}
	



$sql = "SELECT id, name, admin_id, join_status FROM stories ORDER BY $story_ids_text";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	echo "
	<table>
		<tr>
			<th>Story Name</th>
			<th>Admin</th>
			<th>Numb of Writers</th>
			<th>Join Status</th>
			<th>Join / Enter</th>
		</tr>
	";
	while($row = $result->fetch_assoc()) {
		
		$admin_id = $row["admin_id"];
		$story_id = $row["id"];
		$join_status = $row["join_status"];
		
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
			$action_button_content = "<button>join</button>";
		} else if ($join_status == 1) {
			$join_status_text = "need to apply";
			$action_button_content = "<button>apply</button>";
		} else if ($join_status == 2) {
			$join_status_text = "only invited people";
			$action_button_content = "";
		}
		
		
		if(in_array($story_id, $story_ids)) {
			$action_button_content = "<a href='story_index.php?i=" . $story_id ."'>enter</button>";
		}
		
		echo "<tr>";
			echo "<td>" . $row["name"] . "</td>";
			echo "<td>" . $admin_name . "</td>";
			echo "<td>" . $number_of_writers . "</td>";
			echo "<td>" . $join_status_text . "</td>";
			echo "<td>" . $action_button_content . "</td>";
		echo "</tr>";
	}
	echo "</table>";
} else {
	echo "empty";
}
$conn->close();
?>

</body>
</html>
