<?php
class Esewa {
    public function initiatePayment($amount,$tamount, $productId, $successUrl, $failedUrl) {
        $html = '
        <body>
        <form action="https://uat.esewa.com.np/epay/main" method="POST">
            <input value="' . $amount . '" name="tAmt" type="hidden">
            <input value="' . $tamount . '" name="amt" type="hidden">
            <input value="0" name="txAmt" type="hidden">
            <input value="0" name="psc" type="hidden">
            <input value="0" name="pdc" type="hidden">
            <input value="EPAYTEST" name="scd" type="hidden">
            <input value="' . $productId . '" name="pid" type="hidden">
            <input value="' . $successUrl . '" type="hidden" name="su">
            <input value="' . $failedUrl . '" type="hidden" name="fu">
            <input value="Submit" type="submit">
        </form>
        </body>
        ';
        return $html;
    }

    public function verifyPayment($amount, $productId, $receiptId) {
        $html = '
        <body>
        <form action="https://uat.esewa.com.np/epay/transrec" method="GET">
            <input value="' . $amount . '" name="amt" type="hidden">
            <input value="EPAYTEST" name="scd" type="hidden">
            <input value="' . $productId . '" name="pid" type="hidden">
            <input value="' . $receiptId . '" name="rid" type="hidden">
            <input value="Submit" type="submit">
        </form>
        </body>
        ';
        return $html;
    }
}
function generateRandomProductCode() {
    $characters = '0123456789';
    $code = '';
    
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $code;
}

// Generate a random product code
$productId = generateRandomProductCode();
$receiptId="000AE01";

echo $productId;
$Sucessurl="https://localhost:3000/index.php";
$tamount="100";
$amount="100";


// Usage example
$esewa = new Esewa();
$initiatePaymentForm = $esewa->initiatePayment("$amount","$tamount", "$productId", "$Sucessurl", "http://localhost/failed.php");
$verifyPaymentForm = $esewa->verifyPayment(100, "705571","0006AGQ" );

echo $initiatePaymentForm;
echo $verifyPaymentForm;

?>