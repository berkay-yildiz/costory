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
</head>
<body>

<h4>Co Story</h4>
<a href="index.php">index</a>

<?php 
if ($_GET["e"] == 1) {echo "<br><b> stroy created succsesfully </b>";} 
else if ($_GET["e"] == 2) {echo "<br><b> err2 </b>";}
else if ($_GET["e"] == 3) {echo "<br><b> err3 </b>";}
else if ($_GET["e"] == 4) {echo "<br><b> err4 </b>";}
else {echo "<br><b>".$_GET["e"]."</b>";};
?><br>

<form action="create_story_control.php" method="post">
  story name:<br>
  <input type="text" name="story_name" placeholder="story name" required>
  <br><br>
  join status:<br>
  <input type="radio" name="join_status" value="0" checked> open to everyone<br>
  <input type="radio" name="join_status" value="1"> need to aply<br>
  <input type="radio" name="join_status" value="2"> only invited users
  <br><br>
  <input type="checkbox" name="can_invite" value="1" checked> writers can invite other users<br>
  <br><br>
  write the beginning of the story:<br>
	<textarea name="beginning" rows="10" cols="30" required>
		
	</textarea>
  <br><br>
  <input type="submit" value="create story">
</form> <br> 

</body>
</html>
