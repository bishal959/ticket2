<?php
session_start();
include('config.php');
include("session.php");
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

<body>
    
    <nav class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">

<div class="flex flex-wrap items-center">
    <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
        <a href="#">
            <span class="text-xl pl-2"><i class="em em-grinning"></i></span>
        </a>
    </div>

    <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-white px-2">
        <span class="relative w-full">
            <input type="search" placeholder="Search" class="w-full bg-gray-900 text-white transition border border-transparent focus:outline-none focus:border-gray-400 rounded py-3 px-2 pl-10 appearance-none leading-normal">
            <div class="absolute search-icon" style="top: 1rem; left: .8rem;">
                <svg class="fill-current pointer-events-none text-white w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                </svg>
            </div>
        </span>
    </div>

    <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
        <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
            <li class="flex-1 md:flex-none md:mr-3">
                <a class="inline-block py-2 px-4 text-white no-underline" href="index.php"> <i class="fa fa-home pr-0 md:pr-3 text-white fa-lg"></i>Home</a>
            </li>
            <li class="flex-1 md:flex-none md:mr-3">
                <a class="inline-block text-gray-600 no-underline hover:text-gray-200 hover:text-underline py-2 px-4" href="#">link</a>
            </li>
            <li class="flex-1 md:flex-none md:mr-3">
                <div class="relative inline-block">
                    <button onclick="toggleDD('myDropdown')" class="drop-button text-white focus:outline-none"> <span class="pr-2"><i class="em em-robot_face"></i></span> Hi, <?php print_r($_SESSION['username']); ?> <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg></button>
                    <div id="myDropdown" class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30 invisible">
                        <input type="text" class="drop-search p-2 text-gray-600" placeholder="Search.." id="myInput" onkeyup="filterDD('myDropdown','myInput')">
                        <a href="#" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fa fa-user fa-fw"></i> Profile</a>
                        <a href="#" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fa fa-cog fa-fw"></i> Settings</a>
                        <div class="border border-gray-800"></div>
                        <a href="logout.php" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

</nav>


<div class="flex flex-col md:flex-row">

<div class="bg-gray-800 shadow-xl h-16 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48">

    <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
        <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">
            
            <li class="mr-3 flex-1">
                <a href="user.php" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-purple-600">
                    <i class="fa fa-home pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">My Reservation</span>
                </a>
            </li>
            <li class="mr-3 flex-1">
                <a href="ticket.php" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 border-pink-500">
                    <i class="fa fa-ticket-alt pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Tickets</span>
                </a>
            </li>
            <li class="mr-3 flex-1">
                <a href="#" class="block py-1 md:py-3 pl-0 md:pl-0 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-red-500">
                    <i class="fa fa-wallet pr-0 md:pr-3"></i><span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">Payments</span>
                </a>
            </li>
        </ul>
    </div>


</div>

<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

    <div class="bg-gray-800 pt-3">
        <div class="rounded-tl-3xl bg-gradient-to-r from-purple-900 to-gray-800 p-4 shadow text-2xl text-white">
            <h3 class="font-bold pl-2">My ticket</h3>
            <i class="fa-solid fa-ticket"></i>
        </div>
    </div>
<div>
<h2>Theater Seats</h2>

<div id="screen">Screen</div>

<div id="seats-container">
    <!-- Seats will be loaded dynamically here -->
</div>

<div id="seatContainer">
    <h3>Selected Seats:</h3>
    <ul id="selectedSeats"></ul>
    <div id="totalPrice"></div>
    <button id="book-button" onclick="bookSeats()">Book Selected Seats</button>
</div>
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
