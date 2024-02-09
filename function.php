<?php
include("session.php");
include("config.php");



function getShowDetailsByUserId($userId) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT movies.title AS movie_title, bookings.show_date, bookings.booked_seats AS quantity, bookings.price AS unit_price, (bookings.price * COUNT(bookings.id)) AS total_price
                  FROM bookings
                  INNER JOIN movies ON bookings.movie_id = movies.id
                  WHERE bookings.user_id = ? and bookings.book_type = 'Reserved'
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
function updatetopaid($book_seat)
{
    global $conn;

    try {
        // Prepare the SQL query
        $query = "UPDATE `bookings` SET `book_type` = 'paid' WHERE booked_seats=?";
        $stmt = $conn->prepare($query);

        // Bind the parameter
        $stmt->bind_param("s", $book_seat);

        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        // Check the affected rows
        $affectedRows = $stmt->affected_rows;

        // Close the statement
        $stmt->close();

        // Return the result (number of affected rows)
        return $affectedRows;
    } catch (Exception $e) {
        // Handle exceptions
        echo "Error: " . $e->getMessage();
        return false;
    }
}

function printticket($userId, $book_seat) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT movies.title AS movie_title, bookings.show_date, bookings.booked_seats AS quantity, bookings.price AS unit_price, (bookings.price * COUNT(bookings.id)) AS total_price
                  FROM bookings
                  INNER JOIN movies ON bookings.movie_id = movies.id
                  WHERE bookings.user_id = ? and bookings.book_type = 'paid' and bookings.booked_seats = ?
                  GROUP BY movies.title, bookings.show_date, bookings.price";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $stmt->bind_param("is", $userId, $book_seat);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result();

        // Fetch the results into an associative array
        $showDetailspaid = [];
        while ($row = $result->fetch_assoc()) {
            $showDetailspaid[] = $row;
        }

        $stmt->close();

        return $showDetailspaid;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}


?>
