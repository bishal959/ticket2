<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input

    $seats = $_POST["seats"];
    $screening_id = $_POST["screening_id"];

    // Check if seats are available
    $stmt = $conn->prepare("SELECT available_seats FROM screenings WHERE id = ? AND available_seats >= ?");
    $stmt->bind_param("ii", $screening_id, $seats);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Seats are available, proceed with booking
        $user_id = $_SESSION["user_id"];

        // Update available seats
        $stmt = $conn->prepare("UPDATE screenings SET available_seats = available_seats - ? WHERE id = ?");
        $stmt->bind_param("ii", $seats, $screening_id);
        $stmt->execute();

        // Record the booking
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, screening_id, booked_seats, booking_time) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iii", $user_id, $screening_id, $seats);

        if ($stmt->execute()) {
            echo "Booking successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Not enough seats available.";
    }

    $stmt->close();
}

$conn->close();
?>
