<?php
session_start();

include("config.php");
include("function.php");


// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$userId = $_SESSION['user_id'];

$oid = isset($_GET['oid']) ? $_GET['oid'] : '';
$amount = isset($_GET['amt']) ? $_GET['amt'] : '';
$refId = isset($_GET['refId']) ? $_GET['refId'] : '';
// $verifyPaymentForm = $esewa->verifyPayment($amount, $refId, $oid);
$bookedseat = $_GET['booked_seat']; 
$result = updatetopaid($bookedseat);
if ($result !== false && $result > 0) {
    echo "Booking payment status updated successfully. Affected rows: " . $result;
    header("Location: ../ticket/send.php?booked_seat=" . $bookedseat);
} else {
    echo "Error updating booking payment status.";
}


?>

