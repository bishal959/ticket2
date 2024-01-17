<?php
// Assuming you have a database connection established in config.php
include('config.php');

// Check if the movieId is set in the URL
if (isset($_GET['movieId'])) {
    $movieId = $_GET['movieId'];

    // Fetch movie details from the database
    $query = "SELECT * FROM movies WHERE id = $movieId";
    $result = $conn->query($query);

    if (!$result) {
        // Handle the error, such as redirecting to an error page
        die("Query failed: " . $conn->error);
    }

    $movie = $result->fetch_assoc();
    $conn->close();
} else {
    // Redirect to an error page if movieId is not set
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking - <?php echo $movie['title']; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Movie Booking - <?php echo $movie['title']; ?></h1>
</header>

<section id="seat-details">
    <h2>Seat Details for <?php echo $movie['title']; ?></h2>
    <!-- Add seat details display here -->
</section>

<section id="booking-form">
    <h2>Book Tickets</h2>
    <form action="process_booking.php" method="post">
        <!-- Add input fields for booking form (e.g., number of tickets, user details) -->
        <label for="numTickets">Number of Tickets:</label>
        <input type="number" name="numTickets" id="numTickets" required>
        
        <!-- Add more input fields as needed -->

        <input type="hidden" name="movieId" value="<?php echo $movieId; ?>">
        <input type="submit" value="Book Now">
    </form>
</section>

<footer>
    <p>&copy; 2024 Movie Ticket System</p>
</footer>

<script src="script.js"></script>
</body>
</html>
