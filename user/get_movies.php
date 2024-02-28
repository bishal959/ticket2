<?php

include('config.php');

$query = "SELECT id, title, release_date, genre, runTime, director, cast, image_url FROM movies";
$result = $conn->query($query);

if (!$result) {
    // If the query fails, output the error message
    die("Query failed: " . $conn->error);
}

$movies = [];
while ($row = $result->fetch_assoc()) {
    $castArray = ($row['cast'] !== null) ? explode(", ", $row['cast']) : [];
    
    $movies[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'releaseDate' => $row['release_date'],
        'genre' => $row['genre'],
        'runTime' => $row['runTime'],
        'director' => $row['director'],
        'cast' => $castArray,
        'imageUrl' => $row['image_url'],
    ];
}

// Close the connection after fetching data
$conn->close();

header("Content-Type: application/json");
echo json_encode(['movies' => $movies]);

?>
