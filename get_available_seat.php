<?php
include('config.php');


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $movieId = $_GET["movie_id"];
    

    // Fetch available seats for the specified movie
    $query = "SELECT * FROM available_seats WHERE movie_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    $availableSeats = [];
    while ($row = $result->fetch_assoc()) {
        $availableSeats[] = [
            'row' => $row['row'],
            'seatNum' => $row['seat_num'],
        ];
    }

    $stmt->close();

    header("Content-Type: application/json");
    echo json_encode($availableSeats);
} else {
    // Invalid request method
    header("HTTP/1.1 400 Bad Request");
    echo "Invalid request method";
}
?>
