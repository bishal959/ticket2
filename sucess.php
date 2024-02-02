<?php
include("esewa.php");
include("config.php");
include("user.php");

$oid = isset($_GET['oid']) ? $_GET['oid'] : '';
$amt = isset($_GET['amt']) ? $_GET['amt'] : '';
$refId = isset($_GET['refId']) ? $_GET['refId'] : '';
$esewa = new Esewa();
$verificationResult = $esewa->verifyPayment($amt, $oid, $refId);

header("Location: http://localhost/k/ticket.php");

?>
