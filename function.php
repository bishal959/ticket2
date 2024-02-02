<?php
include("config.php");
include("esewa.php");

function getShowDetailsByUserId($userId) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT movies.title AS movie_title, bookings.show_date, bookings.booked_seats AS quantity, bookings.price AS unit_price, (bookings.price * COUNT(bookings.id)) AS total_price
                  FROM bookings
                  INNER JOIN movies ON bookings.movie_id = movies.id
                  WHERE bookings.user_id = ?
                  GROUP BY movies.title, bookings.show_date, bookings.price";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt->bind_param("i", $userId);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result();

        // Fetch the results into an associative array
        $showDetails = [];
        while ($row = $result->fetch_assoc()) {
            $showDetails[] = $row;
        }

        $stmt->close();

        return $showDetails;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
$conn->close();

?>
