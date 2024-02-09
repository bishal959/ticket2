<?php

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "movie";

$servername = "sql108.infinityfree.com";
$username = "epiz_34107324";
$password = "bb7P9jJ0VpqzCv";
$dbname = "epiz_34107324_movie";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
     echo "Connected successfully";
}
?>
