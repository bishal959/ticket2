<?php
if (!isset($_SESSION['id'], $_SESSION['username'])) {
header('location: login.php');
}
?>
