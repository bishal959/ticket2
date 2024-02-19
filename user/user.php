<?php
session_start();
include("function.php");
include("esewa.php");
include("../env+session.php");
$userId = $_SESSION['user_id'];
$showDetails = getShowDetailsByUserId($userId);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to the Student Dashboard</title>

    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/user.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
</head>

<body>
    <div class="page-layout">

        <div class="menu">
            <ul>
                <h2>Browse</h2>
                <li>
                    <a href=" user.php">My Reservation</a>
                </li>
                <li>
                    <a href=" ticket.php">Tickets</a>
                </li>
                <li>
                    <a href="#">Payments</a>
                </li>
            </ul>
        </div>

        <section class="table">
            <table>
                <thead>
                    <tr>
                        <td>Show Details</td>
                        <td>Date</td>
                        <td>Seats</td>
                        <td>Price</td>
                        <td>Click to Pay</td>
                    </tr>
                </thead>
                <tbody>
                    <?php

                   


                    $esewa = new Esewa(); // Assuming Esewa class is defined somewhere
                    $showDetails = getShowDetailsByUserId($userId);
                    

                    foreach ($showDetails as $booking) {
                        $amount = $booking['unit_price'];
                        $tamount = $booking['unit_price'];
                        $productId = generateRandomProductCode();
                        $Sucessurl = "$siteurl\sucess.php?booked_seat=" . urlencode($booking['quantity']);
                        $initiatePaymentForm = $esewa->initiatePayment("$amount", "$tamount", "$productId", "$Sucessurl", "http://localhost/failed.php");
                        // $initiatePaymentkhalti = $khalti->initiatePayment("$tamount", "$productId" );
                    
                        echo '<tr>';
                        echo '<td>' . $booking['movie_title'] . '</td>';
                        echo '<td>' . $booking['show_date'] . '</td>';
                        echo '<td>' . $booking['quantity'] . '</td>';
                        echo '<td>Rs.' . number_format($booking['unit_price'], 2) . '</td>';
                        echo '<td>' . $initiatePaymentForm . '</td>';
                        echo '</tr>';
                    }
                    
                    ?>
                </tbody>
            </table>

        </section>

    </div>

</body>

</html>