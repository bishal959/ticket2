<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION["user_id"] == 1) {
    echo "Welcome, to the Admin Page";
} else {
    header('location: ../login.php');
    exit(); 
}
include("../config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_url'])) {
   
    $title = $_POST['title'];
    $releaseDate = $_POST['release_date'];
    $genre = $_POST['genre'];
    $runTime = $_POST['runTime'];
    $director = $_POST['director'];
    $cast = $_POST['cast'];

    
    $imageFileName = '../image/' . $_FILES['image_url']['name'];
    $imageFilePath = '../image/' . $imageFileName;

    
    move_uploaded_file($_FILES['image_url']['tmp_name'], $imageFilePath);

    
    $genreString = implode(",", $genre);

    
    $stmt = $conn->prepare("INSERT INTO movies (title, release_date, genre, runTime, director, cast, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $title, $releaseDate, $genreString, $runTime, $director, $cast, $imageFileName);

    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

   
    $lastMovieId = $conn->insert_id;


    $stmt->close();

    
    $seatStmt = $conn->prepare("INSERT INTO seats (movie_id, availability_status, theater_id, seat_number, price) VALUES (?, 'Available', 1, ?, '100.00')");

    if (!$seatStmt) {
        die("Error preparing seat statement: " . $conn->error);
    }

  
    $seatData = array(
        '1A', '1B', '1C', '1D', '1E', '1F', '1G', '1H', '1I', '1J',
        '2A', '2B', '2C', '2D', '2E', '2F', '2G', '2H', '2I', '2J',
        '3A', '3B', '3C', '3D', '3E', '3F', '3G', '3H', '3I', '3J',
        '4A', '4B', '4C', '4D', '4E', '4F', '4G', '4H', '4I', '4J',
        '5A', '5B', '5C', '5D', '5E', '5F', '5G', '5H', '5I', '5J',
        '6A', '6B', '6C', '6D', '6E', '6F', '6G', '6H', '6I', '6J',
        '7A', '7B', '7C', '7D', '7E', '7F', '7G', '7H', '7I', '7J',
        '8A', '8B', '8C', '8D', '8E', '8F', '8G', '8H', '8I', '8J',
        '9A', '9B', '9C', '9D', '9E', '9F', '9G', '9H', '9I', '9J',
        '10A', '10B', '10C', '10D', '10E', '10F', '10G', '10H', '10I', '10J'
      
    );

    foreach ($seatData as $seatNumber) {
        $seatStmt->bind_param("is", $lastMovieId, $seatNumber);

        if (!$seatStmt->execute()) {
            die("Error executing seat statement: " . $seatStmt->error);
        }
    }


    $seatStmt->close();

    echo "Data inserted successfully";
    header ('Location: profile.php'); 
}


$conn->close();

?>
