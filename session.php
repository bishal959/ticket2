<?php
if (isset($_SESSION['id'], $_SESSION['username'])) {
header('location:login.php');
}
?>
<html>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <script src="https://cdn.tailwindcss.com"></script>
</html>