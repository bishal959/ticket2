<?php

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
}
function generateRandomProductCode() {
    $characters = '0123456789';
    $code = '';

    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}
class khalti {
    public function initiatePayment($productId, $tamount) {
        $apiUrl = 'https://khalti.com/api/v2/epayment/initiate/';
        $apiKey = 'live_secret_key_5e65c902d19a4d6c9b74763a669b83e1';
    
        $payload = array(
            "return_url" => "https://example.com",
            "website_url" => "https://example.com",
            "amount" => $tamount,
            "purchase_order_id" => $productId,
            "purchase_order_name" => "ticket",
            "customer_info" => array(
                "name" => "test",
                "email" => "test@khalti.com",
                "phone" => "9800000001"
            )
        );
    
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Authorization: key ' . $apiKey,
                'Content-Type: application/json',
            ),
        ));
    
        $response = curl_exec($curl);
    
        if ($response === false) {
            // Handle cURL error
            echo "cURL Error: " . curl_error($curl);
        } else {
            // Decode JSON response
            $responseData = json_decode($response, true);
    
            // Check if payment_url is present in the response
            if (isset($responseData['payment_url'])) {
                // Redirect user to the payment URL
                header("Location: " . $responseData['payment_url']);
                exit();
            } else {
                // Handle other cases or display an error message
                echo "Payment URL not found in the response.";
            }
        }
    
        curl_close($curl);
    }
}    

$productId = generateRandomProductCode();



?>
