<?php

include("config.php");

// Check if movie_id is set
if (isset($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];

    // Fetch available seats for the specific movie from the database
    $sql = "SELECT seat_number, availability_status, price FROM seats WHERE movie_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $movieId);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result) {
            $availableSeats = array();

            // Fetching results as an associative array
            while ($row = $result->fetch_assoc()) {
                $availableSeats[] = $row;
            }

            // Close the statement
            $stmt->close();

            // Close the database connection
            $conn->close();

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($availableSeats);
        } else {
            // Handle the error if the query fails
            echo "Error: " . $stmt->error;
        }
    } else {
        // Handle the error if the statement preparation fails
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    // Handle the case where movie_id is not set
    echo "Error: Movie ID is not set.";
}
?>
