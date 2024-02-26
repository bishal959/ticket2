<?php

include("../config.php");

// Retrieve all movies from the database
$result = $conn->query("SELECT * FROM movies");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $movieId = $_POST['movie_id'];
    $showtime = $_POST['showtime'];
    $theaterId = 1; // Assuming theater_id is always 1 based on your comment

    // Validate and sanitize input (add your validation logic here)

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO screenings (id,movie_id, showtime, theater_id) VALUES (?,?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("iiss",$movieId, $movieId, $showtime, $theaterId);

    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    echo "Screening added successfully"; // You can remove or modify this line based on your needs

    // Close the statement
    $stmt->close();
}

// Close the database connection (optional, depending on your needs)
$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_style.css">
    <title>Admin Menu</title>
</head>
<body>
<div class="seperator">
    <form action="add.php" method="post" enctype="multipart/form-data">
        <label for="title">Movie Title:</label>
        <input type="text" name="title" placeholder="Movie Title" required>
        
        <label for="release_date">Release Date:</label>
        <input type="date" name="release_date" placeholder="Release Date" required>

        <label for="genre">Genre:</label>
        <select name="genre[]" id="genre" multiple required>
            <option value="Action">Action</option>
            <option value="Comedy">Comedy</option>
            <option value="Drama">Drama</option>
            <option value="Horror">Horror</option>
            <option value="Romance">Romance</option>
        </select>

        <label for="runTime">Run Time:</label>
        <input type="text" name="runTime" id="time" placeholder="Run Time" required>
        <label for="director">Director:</label>
        <input type="text" name="director" placeholder="Director" required>

        <label for="cast">Cast (comma separated):</label>
        <input type="text" name="cast" placeholder="Cast (comma separated)" required>

        <label for="image_url">Image URL:</label>
        <input type="file" name="image_url" accept="image/*" required>

        <input type="submit" value="Add Movie">
    </form>
    </div>
    <div class="seperator">
    <form action="" method="post" enctype="multipart/form-data">
    <h1>Add Screnning</h1>
        <label for="movie_id">Movie:</label>
        <?php
        if ($result->num_rows > 0) {
            // Output the opening <select> tag
            echo '<div class="movie">';
            echo '<select name="movie_id" id="movie_id">';
            echo '<option value="" selected disabled>Select a Movie</option>';
            
        
            // Loop through each row
            while ($row = $result->fetch_assoc()) {
                // Output an <option> tag for each movie title
                echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
            }
        
            // Output the closing </select> tag
            echo '</select>';
            echo '</div>';
        } else {
            // If there are no movies in the database
            echo "No movies found.";
        }
        
        // Close the database connection
        
        ?>
        <label for="release_date">show Date:</label>
        <input type="date" name="showtime" placeholder="Release Date" required>
        <input type="submit" value="Add Screening">
    </form>
    </div>

    <script src="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    <link href="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>
    <script>
       var timepicker = new TimePicker('time', {
        lang: 'en',
        theme: 'dark'
    });

    var input = document.getElementById('time');

    timepicker.on('change', function(evt) {
        var value = (evt.hour || '00') + 'Hrs' + (evt.minute || '00') + 'Mins';
        evt.element.value = value;
    });
    </script>
</body>
<style>
    .seperator {
        display: flex;
        justify-content: center;
        padding: 50px;
        max-width: 400px;
    }
    body {
        font-family: Arial, sans-serif;
        display: flex;
    }
    
    form {
        margin: 0 ;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input, select {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    input[type="submit"] {
        background-color: #4caf50;
        color: #fff;
        border: none;
        padding: 15px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }
    
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    
    input[type="submit"]:focus {
        outline: none;
    }
    .movie{
       
        max-width: 300px;
    }
</style>
</html>
