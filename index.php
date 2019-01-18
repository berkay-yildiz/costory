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

Welcome <?php echo $_SESSION["username"]; ?><br><br>
<a href="stories.php">stories</a><br>
<a href="create_story.php">create story</a><br>
<a href="invitations.php">invitations</a><br><br>

<a href="logout.php">logout</a><br>
<?php 
if ($_GET["e"] == 1) {echo "<br><b> log in succsesfull </b>";}
else if ($_GET["e"] == 2) {echo "<br><b> you tried to enter a story that you are not writer </b>";} 
else if ($_GET["e"] == 3) {echo "<br><b> you tried to enter a story's admin panel that you are not admin </b>";} 
?>
</body>
</html>
