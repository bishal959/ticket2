<?php
include("config.php");
function getShowDetailsByUserId($userId) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT
        movies.title AS movie_title,
        bookings.show_date,
        bookings.booked_seats AS quantity,
        bookings.price AS unit_price,
        (bookings.price * COUNT(bookings.id)) AS total_price
    FROM
        bookings
    INNER JOIN
        movies ON bookings.movie_id = movies.id
    WHERE
        bookings.user_id = ? and bookings.book_type = 'reserved'
    GROUP BY
        movies.title, bookings.show_date, bookings.booked_seats, bookings.price
    LIMIT 0, 25";

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
function updatetopaid($book_seat)
{
    global $conn;

    try {
        // Generate a random token
        $token = md5(uniqid(rand(), true));

        // Prepare the SQL query
        $query = "UPDATE `bookings` SET `book_type` = 'paid', `token` = ? WHERE booked_seats=?";
        $stmt = $conn->prepare($query);

        // Bind the parameters
        $stmt->bind_param("ss", $token, $book_seat);

        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        // Check the affected rows
        $affectedRows = $stmt->affected_rows;

        // Close the statement
        $stmt->close();
        if ($affectedRows > 0) {
            $updateAvailabilityQuery = "UPDATE `seats` SET `availability_status` = 'Unavailable' WHERE seat_number=?";
            $updateAvailabilityStmt = $conn->prepare($updateAvailabilityQuery);

            // Bind the parameter for updating availability
            $updateAvailabilityStmt->bind_param("s", $book_seat);

            // Execute the query for updating availability
            if (!$updateAvailabilityStmt->execute()) {
                throw new Exception("Execute failed: (" . $updateAvailabilityStmt->errno . ") " . $updateAvailabilityStmt->error);
            }

            // Close the statement for updating availability
            $updateAvailabilityStmt->close();
        }
        // Return true for success or the number of affected rows
        return ($affectedRows > 0) ? $token : true;
    } catch (Exception $e) {
        // Log or display the error
        error_log("Error in updatetopaid function: " . $e->getMessage());
        return false;
    }
}

function getpaidShowDetailsByUserId($userId) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT
        movies.title AS movie_title,
        bookings.show_date,
        bookings.booked_seats AS quantity,
        bookings.price AS unit_price,
        (bookings.price * COUNT(bookings.id)) AS total_price
    FROM
        bookings
    INNER JOIN
        movies ON bookings.movie_id = movies.id
    WHERE
        bookings.user_id = ? and bookings.book_type = 'paid'
    GROUP BY
        movies.title, bookings.show_date, bookings.booked_seats, bookings.price
    LIMIT 0, 25";

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
function printticket($userId, $book_seat) {
    global $conn;

    try {
        // Select relevant columns from the bookings table and join with the movies table
        $query = "SELECT 
        movies.title AS movie_title, 
        bookings.show_date, 
        bookings.booked_seats AS quantity, 
        MAX(bookings.price) AS unit_price,  -- Aggregate function for price
        (MAX(bookings.price) * COUNT(bookings.id)) AS total_price,
        bookings.token
    FROM 
        bookings
    INNER JOIN 
        movies ON bookings.movie_id = movies.id
    WHERE 
        bookings.user_id = ? 
        AND bookings.book_type = 'paid' 
        AND bookings.booked_seats = ?
    GROUP BY 
        movies.title, 
        bookings.show_date, 
        bookings.token;
    ";

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
        // Log or handle the exception appropriately (e.g., log to a file, send an email, etc.)
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function insertMovieData($title, $releaseDate, $genre, $runTime, $director, $cast, $imageFileName) {
    global $conn;

    $stmt = $conn->prepare("INSERT INTO movies (title, release_date, genre, runTime, director, cast, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sssssss", $title, $releaseDate, $genre, $runTime, $director, $cast, $imageFileName);

    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }

    $stmt->close();
}


?>
