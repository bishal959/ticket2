<?php


include("config.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $username = $_SESSION["username"];
    $movieId = $_POST["movie_id"];
    $theaterId = $_POST["theater_id"];
    $selectedSeats = json_decode($_POST["selected_seats"]);

    try {
        foreach ($selectedSeats as $seat) {
            $row = $seat->row;
            $seatNum = $seat->seatNum;

            // Save the booked seat in the database
            $stmt = $conn->prepare("INSERT INTO bookings (username, movie_id, theater_id, row, seat_num) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("siisi", $username, $movieId, $theaterId, $row, $seatNum);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            // Remove the booked seat from available seats
            $stmt = $conn->prepare("DELETE FROM available_seats WHERE movie_id = ? AND theater_id = ? AND row = ? AND seat_num = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("iisi", $movieId, $theaterId, $row, $seatNum);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
        }

        echo "Seats booked successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn->close();
?>