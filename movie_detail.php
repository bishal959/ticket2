<?php
include('config.php');
// Include your database connection file

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






// Close the connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Detail</title>
    <link rel="stylesheet" href="style.css">
    </head>
    </head>
<body>
    <h2>Theater Seats</h2>

    <div id="screen">Screen</div>

    <div id="seats-container">
        <!-- Seats will be loaded dynamically here -->
    </div>

    <div id="seatContainer">
        <h3>Selected Seats:</h3>
        <ul id="selectedSeats"></ul>
        <button id="book-button" onclick="bookSeats()">Book Selected Seats</button>
    </div>

    <script>
        // Replace the movie ID with your actual movie ID
        var movieId = <?php echo $movieId; ?>;
        function loadAvailableSeats() {
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

            xhr.open("GET", "get_available_seats.php?movie_id=" + movieId, true);
            xhr.send();
        }

        function updateSeats(availableSeats) {
            const seatsContainer = document.getElementById("seats-container");
            seatsContainer.innerHTML = "";

            const seatsPerRow = 10;
            let currentRowContainer;

            availableSeats.forEach(function(seat, index) {
                if (index % seatsPerRow === 0) {
                    currentRowContainer = document.createElement("div");
                    currentRowContainer.className = "row";
                    seatsContainer.appendChild(currentRowContainer);
                }

                const seatElement = document.createElement("div");
                seatElement.className = "seat";
                seatElement.setAttribute("data-seat-number", seat.seat_number);
                seatElement.textContent = seat.seat_number;

                if (seat.availability_status !== "Available") {
                    seatElement.classList.add("unavailable");
                    seatElement.setAttribute("title", "Unavailable");
                    seatElement.removeEventListener("click", toggleSeatSelection);
                } else {
                    seatElement.addEventListener("click", toggleSeatSelection);
                }

                currentRowContainer.appendChild(seatElement);
            });
        }

        function toggleSeatSelection() {
            this.classList.toggle("selected");
            const seatNumber = this.getAttribute("data-seat-number");
            console.log("Selected Seat: Seat Number:", seatNumber);
        }

        function bookSeats() {
            const selectedSeats = document.querySelectorAll(".selected");
            const selectedSeatNumbers = Array.from(selectedSeats).map(function(seat) {
                return seat.getAttribute("data-seat-number");
            });

            // Perform booking logic (you can send selected seat numbers to the server)
            console.log("Selected Seats: ", selectedSeatNumbers);

            // Send selected seat numbers to the PHP file using AJAX
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Booking successful");
                        // Add any additional logic or UI updates here
                    } else {
                        console.error("Error booking seats:", xhr.status, xhr.statusText);
                    }
                }
            };

            xhr.open("POST", "book_seats.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("selectedSeats=" + encodeURIComponent(JSON.stringify(selectedSeatNumbers)) + "&movieId=" + encodeURIComponent(movieId));
            window.href("book_seat.php");
        }

        loadAvailableSeats();
    </script>
</body>
</html>
