<?php
session_start();
require_once('tcpdf/tcpdf.php');
$seat = isset($_GET['booked_seat']) ? $_GET['booked_seat'] : '';
include("../user/function.php");

$userId = $_SESSION['user_id'];

$showDetails = printticket($userId, $seat);

class PDF extends TCPDF {
    public function ticket($showDate, $showTime, $movieName, $auditorium, $seat, $token) {
        $barcodeData = "User ID: " . $_SESSION['user_id'] . " Seat: " . $seat . " Token: " . $token;
        $html = "
        <div style='width: 45%; float: left; margin-bottom: 5%; text-align: center; '>
            <h1 style='font-size: 1.5em;'>Bishal cinema City Pvt. Ltd</h1>
            <p style='font-size: 0.8em;'>Gp Road, Chabahil</p>
            <div style='font-size: 0.8em;'>
                <strong>Show Date:</strong> $showDate<br>
                <strong>Show Time:</strong> $showTime<br>
                <strong>Movie Name:</strong> $movieName<br>
                <strong>Auditorium:</strong> $auditorium
                <span style='float: right;'><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Seat:</strong> $seat</span>
            </div>
            <hr style='border-top: 10px dashed #000;border-style: dashed;'>
            <p style='border-style: dashed;'>Thank you for booking with us.</p>
            <hr style='border-top: 10px dashed #000;border-style: dashed;'>
        </div>";
        $this->write2DBarcode($barcodeData, 'QRCODE,M', '100', '', '300', 40);
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
    $token = $booking['token']; // Assuming token is a field in your database

    // Check the value of $seat and decide whether to print one or all seats
    if ($seat == 1) {
        $pdf->ticket($date, $time, $title, 'Auditorium 1', $seat, $token);
    } else {
        // Split the seat string into an array
        $yourSeatArray = explode(',', $seat);

        // Check if $yourSeatArray is an array before using foreach
        if (is_array($yourSeatArray)) {
            foreach ($yourSeatArray as $singleSeat) {
                $pdf->ticket($date, $time, $title, 'Auditorium 1', $singleSeat, $token);
            }
        } else {
            // Handle the case where $yourSeatArray is not an array
            echo "Error: Seat data is not available.";
        }
    }
}

$pdf->Output();
?>
