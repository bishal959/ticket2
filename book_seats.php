<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $userId = $_SESSION["user_id"];
    $selectedSeats = json_decode($_POST["selectedSeats"]);
    $prices = json_decode($_POST["prices"]);
    $movieId = isset($_POST['movieId']) ? $_POST['movieId'] : null;
    $bookType = isset($_POST['bookType']) ? $_POST['bookType'] : 'Booked';
    $currentDate = date("Y-m-d");
    try {
        // Convert selected seats array to a comma-separated string
        $bookedSeatsString = implode(",", $selectedSeats);

        // Calculate the total price
        $totalPrice = array_sum($prices);

        // Insert the booking details into the 'bookings' table
        $insertQuery = "INSERT INTO bookings (user_id, movie_id, booked_seats, price, show_date) VALUES (?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);

        if (!$insertStmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $insertStmt->bind_param("iisds", $userId, $movieId, $bookedSeatsString, $totalPrice, $currentDate);
        if (!$insertStmt->execute()) {
            throw new Exception("Execute failed: (" . $insertStmt->errno . ") " . $insertStmt->error);
        }

        // Update the availability status for the booked seats
        $updateQuery = "UPDATE seats SET availability_status = 'Booked' WHERE seat_number = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if (!$updateStmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        // Dynamically bind parameters for each seat
        $updateStmt->bind_param("s", $seatNumber);

        foreach ($selectedSeats as $seatNumber) {
            // Update the seat and set it as booked
            $updateStmt->execute();
        }

        echo "Seats booked successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close the database connection
    $conn->close();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
