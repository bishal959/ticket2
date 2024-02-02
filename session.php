<?php
if (!isset($_SESSION['user_id'], $_SESSION['username'])) {
header('location:login.php');
}
?>
