<?php
session_start(); // Start the session

// Clear cookies for username and password
setcookie("username", "", time() - 3600, "/"); // Clear username cookie
setcookie("password", "", time() - 3600, "/"); // Clear password cookie

// Destroy the session
session_destroy(); 

// Redirect to login page
header("Location: index.php");
exit;
?>
