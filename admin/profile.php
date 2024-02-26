<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_style.css">
    <title>Admin Menu</title>
</head>
<body>
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
</style>
</html>
