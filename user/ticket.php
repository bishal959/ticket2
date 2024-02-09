<?php
session_start();
require_once('tcpdf/tcpdf.php');
$seat = isset($_GET['booked_seat']) ? $_GET['booked_seat'] : '';
include("../function.php");

$userId = $_SESSION['user_id'];

$showDetails = printticket($userId, $seat);

class PDF extends TCPDF {
    public function ticket($showDate, $showTime, $movieName, $auditorium, $seat) {
        $html = "
        <div style='width: 45%; float: left; margin-right: 5%; text-align: center; margin-bottom: 20px;'>
            <h1 style='font-size: 1.5em; margin-bottom: 10px;'>Bishal cinema City Pvt. Ltd</h1>
            <p style='font-size: 0.8em; margin-bottom: 10px;'>Gp Road, Chabahil</p>
            <div style='font-size: 0.8em; margin-bottom: 10px;'>
                <strong>Show Date:</strong> $showDate<br><br>
                <strong>Show Time:</strong> $showTime<br><br>
                <strong>Movie Name:</strong> $movieName<br><br>
                <strong>Auditorium:</strong> $auditorium
                <span style='float: right;'><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seat:</strong> $seat</span>
            </div>
            <hr style='border-top: 1px solid #000;'>
            <p style='font-size: 0.8em; '>Thank you for booking with us.</p>
            <hr style='border-top: 1px solid #000;'>
        </div><br><br><br><br><br><br>";
        $this->writeHTML($html, true, false, true, false, '');
    }
}

$pdf = new PDF();
$pdf->AddPage();

foreach ($showDetails as $booking) {
    $title = $booking['movie_title'];
    $date = $booking['show_date'];
    $time = "11:00";
    $quantity = $booking['quantity'];

    // Check the value of $seat and decide whether to print one or all seats
    if ($seat == 1) {
        $pdf->ticket($date, $time, $title, 'Auditorium 1', $seat);
    } else {
        // Split the seat string into an array
        $yourSeatArray = explode(',', $seat);

        // Check if $yourSeatArray is an array before using foreach
        if (is_array($yourSeatArray)) {
            foreach ($yourSeatArray as $singleSeat) {
                $pdf->ticket($date, $time, $title, 'Auditorium 1', $singleSeat);
            }
        } else {
            // Handle the case where $yourSeatArray is not an array
            echo "Error: Seat data is not available.";
        }
    }
}

$pdf->Output();
?>
