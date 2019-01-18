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

$story_id = $_GET["i"];

$sql2 = "SELECT name FROM stories WHERE id = '$story_id'";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();
$story_name = $row2["name"];

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<?php echo "<h4>Co Story . $story_name . Invite User</h4>" ; 
echo "<a href=\"story_index.php?i=$story_id\">back to story</a>";
?>

<a href="index.php">index</a><br><br>

<form action="invite_control.php" method="post">
  invite people:<br>
  <input type="text" name="user_name" placeholder="user name">
  <input type="hidden" name="story_id" value = "<?php echo $story_id ?>">
  <br><br>
  <input type="submit" value="invite">
</form>

<?php 
if ($_GET["e"] == 1) {echo "<br><b> only admin can invite on this story </b>";}
else if ($_GET["e"] == 2) {echo "<br><b> you are not a writer of this story </b>";} 
else if ($_GET["e"] == 3) {echo "<br><b> err3 </b>";} 
else if ($_GET["e"] == 4) {echo "<br><b> you invited user sucsesfully </b>";} 
else if ($_GET["e"] == 5) {echo "<br><b> there is no user that has this name </b>";} 
?>

</body>
</html>