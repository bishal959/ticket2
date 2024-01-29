<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $username = $_SESSION["username"];
    $theaterId = $_POST["theater_id"];
    $selectedSeats = json_decode($_POST["selected_seats"]);
    $movieId = isset($_POST['movieId']) ? $_POST['movieId'] : null;
    try {
        foreach ($selectedSeats as $seat) {
            $row = $seat->row;
            $seatNum = $seat->seatNum;

            // Save the booked seat in the database
            $insertQuery = "INSERT INTO bookings (username, movie_id, theater_id, row, seat_num) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertQuery);

            if (!$insertStmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $insertStmt->bind_param("siisi", $username, $movieId, $theaterId, $row, $seatNum);
            if (!$insertStmt->execute()) {
                throw new Exception("Execute failed: (" . $insertStmt->errno . ") " . $insertStmt->error);
            }

            // Remove the booked seat from available seats
            $deleteQuery = "DELETE FROM available_seats WHERE movie_id = ? AND theater_id = ? AND row = ? AND seat_num = ?";
            $deleteStmt = $conn->prepare($deleteQuery);

            if (!$deleteStmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $deleteStmt->bind_param("iisi", $movieId, $theaterId, $row, $seatNum);
            if (!$deleteStmt->execute()) {
                throw new Exception("Execute failed: (" . $deleteStmt->errno . ") " . $deleteStmt->error);
            }
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
