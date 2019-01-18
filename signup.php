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

<form action="signup_control.php" method="post">
  username:<br>
  <input type="text" name="username" placeholder="username">
  <br>
  email:<br>
  <input type="email" name="email" placeholder="email">
  <br>
  password:<br>
  <input type="password" name="password" placeholder="password">
  <br><br>
  <input type="submit" value="sign up">
</form> <br> <a href="login.php">login</a>

<?php 
if ($_GET["e"] == 1) {echo "<br><b> this user name is already taken </b>";} 
else if ($_GET["e"] == 2) {echo "<br><b> there is an accound acociated with this email </b>";}
else if ($_GET["e"] == 3) {echo "<br><b> accound created succsesfully </b>";}
?>

</body>
</html>
