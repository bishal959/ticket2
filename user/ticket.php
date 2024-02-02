<?php
require('fpdf/fpdf.php');
require('../function.php');
session_start();
$book_seat = isset($_GET['booked_seat']) ? $_GET['booked_seat'] : '';

// Output for debugging
echo $book_seat;
function generatePDF($title, $data) {
    // Create instance of FPDF class
    $pdf = new FPDF();

    // Add a page to the PDF
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 13);

    // Add title to the PDF with background color
    $pdf->SetFillColor(150, 200, 205);
    $pdf->Cell(0, 6, $title, 0, 1, 'C', true);

    // Set font for the table header
    $pdf->SetFont('Arial', 'B', 11);

    // Add table headers to the PDF with background color
    $pdf->SetFillColor(100, 180, 200);
    $pdf->Cell(40, 10, 'Movie Title', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Show Date', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'seats', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Price', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Total Price', 1, 1, 'C', true);

    // Set font for the table content
    $pdf->SetFont('Arial', '', 12);

    // Add data to the PDF
    foreach ($data as $row) {
        $pdf->Cell(40, 10, $row['movie_title'], 1);
        $pdf->Cell(30, 10, $row['show_date'], 1);
        $pdf->Cell(30, 10, $row['quantity'], 1);
        $pdf->Cell(30, 10, $row['unit_price'], 1);
        $pdf->Cell(40, 10, $row['total_price'], 1);
        $pdf->Ln();
    }

    // Output the PDF to the browser or save it to a file
    $pdf->Output();
}


require_once('../config.php'); // Include your database connection file
$userId = $_SESSION['user_id']; // Replace with the actual user ID

// Call the function to get data from the database
$showDetailsPaid = getprintticket($userId, $book_seat);

// Output for debugging
print_r($showDetailsPaid);

// Check if there are results before generating the PDF
if (!empty($showDetailsPaid)) {
    $title = " Show Details  ";
    generatePDF($title, $showDetailsPaid);
} else {
    echo "No paid show details found for the user.";
}
?>
