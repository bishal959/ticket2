function bookSelectedSeats() {
    const selectedSeats = document.querySelectorAll('.selected');
    const seatInfo = Array.from(selectedSeats).map(seat => {
        return {
            row: seat.getAttribute("data-row"),
            seatNum: seat.getAttribute("data-seat-num")
        };
    });

    // Send the selected seat information to the server for booking
    const movieId = 1; // Replace with the actual movie ID
    // Replace with the actual theater ID

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

        xhr.open("GET", "get_available_seats.php?movie_id=2", true); // Replace with the actual URL of your PHP script
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
    loadAvailableSeats();
});