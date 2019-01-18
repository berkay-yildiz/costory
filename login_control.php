<?php
session_start();
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

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT id, name, email FROM users WHERE (name = '$username' OR email = '$username') AND password = '$password' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	
	while($row = $result->fetch_assoc()) {
		$_SESSION["id"] = $row["id"];
        $_SESSION["username"] = $row["name"];
		$_SESSION["email"] = $row["email"];
    }
	$newURL = "index.php?e=1";
	header('Location: '.$newURL);
} else {
	$newURL = "login.php?e=1";
    header('Location: '.$newURL);
}
$conn->close();
?>