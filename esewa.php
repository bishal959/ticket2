<?php
include("session.php");
class Esewa {
    public function initiatePayment($amount, $tamount, $productId, $successUrl, $failedUrl) {
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
            <input value="Pay Now" style="cursor: pointer;"  type="submit">
        </form>
        </body>
        ';
        return $html;
    }

    public function verifyPayment($amount, $oid, $refId) {
        $url = 'https://uat.esewa.com.np/epay/transrec';
        $queryString = http_build_query([
            'amt' => $amount,
            'scd' => 'EPAYTEST',
            'pid' => $oid,
            'rid' => $refId,
        ]);
    
        $url .= '?' . $queryString;
    
        // Perform verification using cURL or any other method you prefer
        $verificationResult = $this->sendCurlRequest($url);
    
        // You can process the verification result as needed
        return $verificationResult;
    }
    
    private function sendCurlRequest($url) {
        $ch = curl_init();
    
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ];
    
        curl_setopt_array($ch, $options);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch) !== 0) {
            // Handle cURL errors here
            throw new Exception(curl_error($ch));
        }
    
        curl_close($ch);
    
        return $response;
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



// $Sucessurl="http://localhost/k/sucess.php";
// Usage example
$esewa = new Esewa();

$verifyPaymentForm = $esewa->verifyPayment(100, "705571", "0006AGQ");

// echo $initiatePaymentForm;

?>
