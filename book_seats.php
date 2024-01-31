<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $userId = $_SESSION["user_id"];
    $selectedSeats = json_decode($_POST["selectedSeats"]);
    $price = json_decode($_POST["price"]);
    $movieId = isset($_POST['movieId']) ? $_POST['movieId'] : null;
    $bookType = isset($_POST['bookType']) ? $_POST['bookType'] : 'Booked';
    $showDate = isset($_POST['showDate']) ? $_POST['showDate'] : null; // Get showDate from POST

    try {
        // Convert selected seats array to a comma-separated string
        $bookedSeatsString = implode(",", $selectedSeats);

        // Insert the booking details into the 'bookings' table
        $insertQuery = "INSERT INTO bookings (user_id, movie_id, booked_seats, price, show_date) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);

        if (!$insertStmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $insertStmt->bind_param("iisss", $userId, $movieId, $bookedSeatsString, $bookType, $showDate);
        if (!$insertStmt->execute()) {
            throw new Exception("Execute failed: (" . $insertStmt->errno . ") " . $insertStmt->error);
        }

        // Update the availability status for the booked seats
        $deleteQuery = "UPDATE `seats` SET `availability_status` = 'Booked' WHERE seat_number IN (" . implode(",", array_fill(0, count($selectedSeats), "?")) . ")";
        $deleteStmt = $conn->prepare($deleteQuery);

        if (!$deleteStmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        // Dynamically bind parameters for each seat
        $types = str_repeat('s', count($selectedSeats));
        $deleteStmt->bind_param($types, ...$selectedSeats);

        if (!$deleteStmt->execute()) {
            throw new Exception("Execute failed: (" . $deleteStmt->errno . ") " . $deleteStmt->error);
        }

        echo "Seats booked successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn->close();
}
?>
