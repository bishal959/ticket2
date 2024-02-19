<?php
session_start();
include('config.php');

// Include your database connection file

$username = $_SESSION["username"];

// Check if the movie_id parameter is set in the URL
if (isset($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];

    // Fetch movie details
    $query = "SELECT * FROM movies WHERE id = $movieId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $movie = $result->fetch_assoc();
        echo '<div class="hero-section"><img src="' . $movie['image_url'] . '" alt="' . $movie['title'] . ' Poster" class="movie-poster">';
        echo '<h2 class="movie-title">' . $movie['title'] . '</h2>';

        echo '<div class="movie-details">';

        echo '<p>Release Date: ' . $movie['release_date'] . '</p>';
        echo '<p>Genre: ' . $movie['genre'] . '</p>';
        echo '<p>Run Time: ' . $movie['runTime'] . '</p>';
        echo '<p>Director: ' . $movie['director'] . '</p>';
        echo '<p>Cast: ' . implode(', ', explode(", ", $movie['cast'])) . '</p>';
        echo '</div></div>';
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
    <link rel="stylesheet" href="./styles/movie-detail.css">
    <link rel="stylesheet" href="./styles/reset.css">
</head>

<body>

    <main class="container">

        <h2>Theater Seats</h2>

        <div class="seats-wrapper">
            <div id="screen">Screen</div>
            <div id="seats-container">
                <!-- Seats will be loaded dynamically here -->
            </div>
        </div>

        <ul class="seat-details">
            <li>
                <span class="detail-available"></span> Available
            </li>

            <li>
                <span class="detail-booked"></span> Booked
            </li>
            <li>
                <span class="detail-notavailable"></span> Not Available
            </li>
            <li>
                <span class="detail-selected"></span>Selected
            </li>
        </ul>

        <div id="seatContainer">
            <h3>Selected Seats:</h3>
            <ul id="selectedSeats"></ul>
            <div id="totalPrice"></div>
            <button id="book-button" onclick="bookSeats()">Book Selected Seats</button>
        </div>

    </main>
    <script>
        // Replace the movie ID with your actual movie ID
        var movieId = <?php echo $movieId; ?>;
        var username = "<?php echo $username; ?>";
        var selectedSeatsInfo = [];
        var totalPriceElement = document.getElementById("totalPrice"); // Declare totalPriceElement here

        function loadAvailableSeats() {
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
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

            availableSeats.forEach(function (seat, index) {
                if (index % seatsPerRow === 0) {
                    currentRowContainer = document.createElement("div");
                    currentRowContainer.className = "row";
                    seatsContainer.appendChild(currentRowContainer);
                }

                const seatElement = document.createElement("div");
                seatElement.className = "seat";
                seatElement.setAttribute("data-seat-number", seat.seat_number);
                seatElement.setAttribute("data-price", seat.price);
                seatElement.textContent = seat.seat_number;

                if (seat.availability_status === "Unavailable") {
                    seatElement.classList.add("unavailable");
                    seatElement.setAttribute("title", "Unavailable");
                    seatElement.removeEventListener("click", toggleSeatSelection);
                } else if (seat.availability_status === "Booked") {
                    seatElement.classList.add("booked");
                    seatElement.setAttribute("title", "Booked");
                    seatElement.removeEventListener("click", toggleSeatSelection);
                } else if (seat.availability_status === "Available") {
                    seatElement.classList.add("available");
                    seatElement.setAttribute("title", "Available");
                    seatElement.addEventListener("click", toggleSeatSelection);
                }

                currentRowContainer.appendChild(seatElement);
            });
        }

        function toggleSeatSelection() {
            this.classList.toggle("selected");
            const seatNumber = this.getAttribute("data-seat-number");
            const seatPrice = parseFloat(this.getAttribute("data-price"));

            const index = selectedSeatsInfo.findIndex(item => item.seatNumber === seatNumber);

            if (this.classList.contains("selected")) {
                if (index === -1) {
                    selectedSeatsInfo.push({ seatNumber, seatPrice });
                }
            } else {
                if (index !== -1) {
                    selectedSeatsInfo.splice(index, 1);
                }
            }

            updateSelectedSeats();
        }

        function updateSelectedSeats() {
            const selectedSeatsList = document.getElementById("selectedSeats");

            selectedSeatsList.innerHTML = "";
            let totalSeats = 0;
            let totalPrice = 0;

            selectedSeatsInfo.forEach(item => {
                const listItem = document.createElement("li");
                listItem.textContent = `Seat ${item.seatNumber} - Rs.${item.seatPrice.toFixed(2)}`;
                selectedSeatsList.appendChild(listItem);

                totalSeats++;
                totalPrice += item.seatPrice;
            });

            totalPriceElement.textContent = `Total cost: Rs.${totalPrice.toFixed(2)}`;

            console.log("Selected Seats: ", selectedSeatsInfo);
        }

        function bookSeats() {
            const selectedSeatNumbers = selectedSeatsInfo.map(item => item.seatNumber);
            const selectedSeatPrices = selectedSeatsInfo.map(item => item.seatPrice);

            // Perform booking logic (you can send selected seat numbers and prices to the server)
            console.log("Selected Seats: ", selectedSeatNumbers);
            console.log("Selected Prices: ", selectedSeatPrices);

            // Send selected seat numbers and prices to the PHP file using AJAX
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("Booking successful");
                        window.location.href = "user.php";
                        // Add any additional logic or UI updates here
                    } else {
                        console.error("Error booking seats:", xhr.status, xhr.statusText);
                    }
                }
            };

            xhr.open("POST", "book_seats.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("selectedSeats=" + encodeURIComponent(JSON.stringify(selectedSeatNumbers)) + "&movieId=" + encodeURIComponent(movieId) + "&prices=" + encodeURIComponent(JSON.stringify(selectedSeatPrices)));
        }


        loadAvailableSeats();
    </script>
</body>

</html>