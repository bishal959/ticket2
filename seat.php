<?php


include("config.php");



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $username = $_SESSION["username"];
    $movieId = $_POST["movie_id"];
    $theaterId = $_POST["theater_id"];
    $selectedSeats = json_decode($_POST["selected_seats"]);

    try {
        foreach ($selectedSeats as $seat) {
            $row = $seat->row;
            $seatNum = $seat->seatNum;

            // Save the booked seat in the database
            $stmt = $conn->prepare("INSERT INTO bookings (username, movie_id, theater_id, row, seat_num) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("siisi", $username, $movieId, $theaterId, $row, $seatNum);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            // Remove the booked seat from available seats
            $stmt = $conn->prepare("DELETE FROM available_seats WHERE movie_id = ? AND theater_id = ? AND row = ? AND seat_num = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: (" . $conn->errno . ") " . $conn->error);
            }

            $stmt->bind_param("iisi", $movieId, $theaterId, $row, $seatNum);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }
        }

        echo "Seats booked successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theater Seats</title>
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 5px;
            background-color: green;
            color:white;
            display: inline-block;
            cursor: pointer;
        }
        .unavailable {
            background-color: red; /* Change to your desired color for unavailable seats */
            cursor: not-allowed;
        }
        .selected {
            background-color: blue; /* Change to your desired color for selected seats */
        }
        #screen {
            width: 500px;
            height: 30px;
            background-color: #333;
            color: #fff;
            text-align: center;
            margin-bottom: 10px;
        }
        #book-button {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Theater Seats</h2>

<div id="screen">Screen</div>

<div id="seats-container">
    <!-- Seats will be loaded dynamically here -->
</div>

<button id="book-button" onclick="bookSelectedSeats()">Book Selected Seats</button>

<script>
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
                    const response = JSON.parse(xhr.responseText);
                    console.log(response.message);
                    // Optionally, you can perform additional actions based on the server response
                } else {
                    console.error("Error booking seats:", xhr.status, xhr.statusText);
                }
            }
        };

        xhr.open("POST", "book_seats.php", true); // Replace with the actual URL of your PHP script
        xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhr.send(JSON.stringify({ selectedSeats }));
    }
    
document.addEventListener("DOMContentLoaded", function() {
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

        xhr.open("GET", "get_available_seats.php", true); // Replace with the actual URL of your PHP script
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

    // Function to handle booking of selected seats
    

    // Load available seats on page load
    loadAvailableSeats();
    
});

</script>

</body>
</html>
