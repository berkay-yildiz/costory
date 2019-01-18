<?php
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
$email = $_POST["email"];

$sql = "SELECT name, email FROM users WHERE name = '$username' OR email = '$email' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
        if( $row["name"]  == $username) {$newURL = "signup.php?e=1";}
		else if( $row["email"]  == $email) {$newURL = "signup.php?e=2";}
    }
    header('Location: '.$newURL);
} else {
	$sql = "INSERT INTO users (name, password, email)
	VALUES ('$username', '$password', '$email')";

	if ($conn->query($sql) === TRUE) {
		$newURL = "signup.php?e=3";
		header('Location: '.$newURL);
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}


$conn->close();
?>