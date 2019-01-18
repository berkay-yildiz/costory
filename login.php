<?php
session_start();
if(isset($_SESSION["username"]) && isset($_SESSION["id"])){
	$newURL = "index.php?e=2";
	header('Location: '.$newURL);
};
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<h4>Co Story</h4>

<form action="login_control.php" method="post">
  username or email:<br>
  <input type="text" name="username" placeholder="username or email" required>
  <br>
  password:<br>
  <input type="password" name="password" placeholder="password" required>
  <br><br>
  <input type="submit" value="log in">
</form> <br> <a href="signup.php">signup</a>

<?php 
if ($_GET["e"] == 1) {echo "<br><b> username or password is wrong </b>";} 
else if ($_GET["e"] == 2) {echo "<br><b> log out succsesfull </b>";}
else if ($_GET["e"] == 3) {echo "<br><b> you must log in </b>";} 
?>

</body>
</html>
