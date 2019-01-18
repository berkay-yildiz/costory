<?php
session_start();

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

$newURL = "login.php?e=2";
header('Location: '.$newURL);
?>