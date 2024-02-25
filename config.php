<?php
$envFile = __DIR__ . '/.env';
$envVariables = parse_ini_file($envFile);
$smtp_pass=$envVariables['SMTP_PASS'];
$servername = $envVariables['servername'];
$username = $envVariables['username'];
$password = $envVariables['password'];
$dbname = $envVariables['dbname'];

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "movie";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
