<?php
require('../user/function.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_url'])) {
    $title = $_POST['title'];
    $releaseDate = $_POST['release_date'];
    $genre = implode(",", $_POST['genre']);
    $runTime = $_POST['runTime'];
    $director = $_POST['director'];
    $cast = $_POST['cast'];
    $imageFileName = $_FILES['image_url']['name'];
    $imageFilePath = '../image/' . $imageFileName;

    move_uploaded_file($_FILES['image_url']['tmp_name'], $imageFilePath);

    insertMovieData($title, $releaseDate, $genre, $runTime, $director, $cast, $imageFileName, $imageFilePath);
}

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
    <form action="" method="post">
        <input type="text" name="title" placeholder="Movie Title">
        <input type="date" name="release_date" placeholder="Release Date">
        <label for="genre">Genre:</label>
        <select name="genre[]" id="genre" multiple>
            <option value="action">Action</option>
            <option value="comedy">Comedy</option>
            <option value="drama">Drama</option>
            <!-- Add more genre options as needed -->
        </select>
        
        <input type="text" name="runTime" id="time" placeholder="Run Time">
        <input type="text" name="director" placeholder="Director">
        <input type="text" name="cast" placeholder="Cast (comma separated)">
        <input type="file" name="image_url" placeholder="Image URL">
        <input type="submit" value="Add Movie">
    </form>
</body>
<style>
 body {
     font-family: Arial, sans-serif;
     background-color: #f4f4f4;
     padding: 20px;
 }
 
 form {
     max-width: 400px;
     margin: 0 auto;
     background-color: #fff;
     padding: 20px;
     border-radius: 5px;
     box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
 }
 
 input {
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
 label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
</style>
<script src="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
<link href="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>
<script>
    var timepicker = new TimePicker('time', {
  lang: 'en',
  theme: 'dark'
});

var input = document.getElementById('time');

timepicker.on('change', function(evt) {
  
  var value = (evt.hour || '00') + ':' + (evt.minute || '00');
  evt.element.value = value;

});
</script>
</html>