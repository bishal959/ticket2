<?php
session_start();
echo "User ID: " . $_SESSION['id'] . "<br>";
echo "Username: " . $_SESSION['username'] . "<br>";
// if (!isset($_SESSION['id'], $_SESSION['username'])) {
// header('location: login.php');
// }
?>
