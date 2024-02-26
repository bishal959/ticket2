<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_url'])) {
    // Retrieve form data
    $title = $_POST['title'];
    $releaseDate = $_POST['release_date'];
    $genre = $_POST['genre'];
    $runTime = $_POST['runTime'];
    $director = $_POST['director'];
    $cast = $_POST['cast'];

    // Validate and sanitize input (add your validation logic here)

    // Handle file upload
    $imageFileName = $_FILES['image_url']['name'];
    $imageFilePath = '../image/' . $imageFileName;

    // Move the uploaded file to the destination directory
    move_uploaded_file($_FILES['image_url']['tmp_name'], $imageFilePath);

    // Convert the $genre array to a comma-separated string
    $genreString = implode(",", $genre);

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO movies (title, release_date, genre, runTime, director, cast, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $title, $releaseDate, $genreString, $runTime, $director, $cast, $imageFileName);

    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    echo "Data inserted successfully"; // You can remove or modify this line based on your needs

    $stmt->close();
}

// Close the database connection (optional, depending on your needs)
$conn->close();

?>
