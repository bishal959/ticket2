<?php
session_start();
echo "User ID: " . $_SESSION['user_id'] . "<br>";
echo "Username: " . $_SESSION['username'] . "<br>";
if (!isset( $_SESSION['username'])) {
header('location: login.php');
}
?>
