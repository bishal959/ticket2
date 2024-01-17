<?php
include('config.php'); // Include your database connection file

// Check if the movie_id parameter is set in the URL
if (isset($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];
    echo "Movie ID : " . $movieId;

    // Fetch movie details
    $query = "SELECT * FROM movies WHERE id = $movieId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
        echo '<h2>' . $movie['title'] . '</h2>';
        echo '<img src="' . $movie['image_url'] . '" alt="' . $movie['title'] . ' Poster" class="movie-poster">';
        echo '<p>Release Date: ' . $movie['release_date'] . '</p>';
        echo '<p>Genre: ' . $movie['genre'] . '</p>';
        echo '<p>Run Time: ' . $movie['runTime'] . '</p>';
        echo '<p>Director: ' . $movie['director'] . '</p>';
        echo '<p>Cast: ' . implode(', ', explode(", ", $movie['cast'])) . '</p>';
        

        
    }
}
$conn->close();
$theaterId=1;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Theater Seats</h2>

<div id="screen">Screen</div>

<div id="seats-container">
    <!-- Seats will be loaded dynamically here -->
</div>

<button id="book-button" onclick="bookSelectedSeats()">Book Selected Seats</button>
<script>

        const movieId = <?php echo $movieId; ?>;
        const theaterId = <?php echo $theaterId; ?>;
        const seatsContainer = document.getElementById("seats-container");

        // Function to load available seats from the server
        function loadAvailableSeats() {
            // Make an AJAX request to your PHP script
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const availableSeats = JSON.parse(xhr.responseText);
                        updateSeats(availableSeats);
                    } else {
                        console.error("Error loading available seats:", xhr.status, xhr.statusText);
                    }
                }
            };

            xhr.open("GET", "movie_detail.php?movie_id=" + movieId, true); // Pass movie_id in the URL
            xhr.send();
        }

        // Function to update seats based on available seats data
        function updateSeats(availableSeats) {
            const rows = 5; // Replace with the actual number of rows
            const seatsPerRow = 10; // Replace with the actual number of seats per row

            seatsContainer.innerHTML = ""; // Clear existing seats

            for (let row = 1; row <= rows; row++) {
                for (let seatNum = 1; seatNum <= seatsPerRow; seatNum++) {
                    const seat = document.createElement("div");
                    seat.className = "seat";
                    seat.setAttribute("data-row", row);
                    seat.setAttribute("data-seat-num", seatNum);
                    seat.textContent = seatNum;

                    // Check if the seat is available
                    const isAvailable = availableSeats.some(s => s.row == row && s.seatNum == seatNum);
                    if (!isAvailable) {
                        seat.classList.add("unavailable");
                        seat.setAttribute("title", "Unavailable");
                        seat.removeEventListener("click", toggleSeatSelection);
                    } else {
                        seat.addEventListener("click", toggleSeatSelection);
                    }

                    seatsContainer.appendChild(seat);
                }
                seatsContainer.appendChild(document.createElement("br"));
            }
        }

        // Function to toggle seat selection
        function toggleSeatSelection() {
            this.classList.toggle("selected");
            const row = this.getAttribute("data-row");
            const seatNum = this.getAttribute("data-seat-num");
            console.log("Selected Seat:", "Row:", row, "Seat Number:", seatNum);
        }

        // Load available seats on page load
        document.addEventListener("DOMContentLoaded", function() {
            loadAvailableSeats();
        });

        function bookSelectedSeats() {
            const selectedSeats = document.querySelectorAll('.selected');
            const seatInfo = Array.from(selectedSeats).map(seat => {
                return {
                    row: seat.getAttribute("data-row"),
                    seatNum: seat.getAttribute("data-seat-num")
                };
            });

            // Send the selected seat information to the server for booking
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                    } else {
                        console.error("Error booking seats:", xhr.status, xhr.statusText);
                    }
                }
            };

            xhr.open("POST", "book_seats.php", true); // Replace with the actual URL of your PHP script
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            const data = "movie_id=" + encodeURIComponent(movieId) + "&theater_id=" + encodeURIComponent(theaterId) + "&selected_seats=" + encodeURIComponent(JSON.stringify(seatInfo));

            xhr.send(data);
        }
    </script>


</body>
</html>