<?php

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "movie";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticket2";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // echo "Connected successfully";
}
?>