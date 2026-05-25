<?php

$consumerKey = "YOUR_CONSUMER_KEY";
$consumerSecret = "YOUR_CONSUMER_SECRET";

$BusinessShortCode = "174379";
$Passkey = "YOUR_PASSKEY";

$date = date('YmdHis');

$password = base64_encode(
    $BusinessShortCode .
    $Passkey .
    $date
);

$phone = $_POST['phone'];
$amount = $_POST['amount'];

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array(
        'Authorization: Basic '. base64_encode($consumerKey.':'.$consumerSecret)
    ),
));

$response = curl_exec($curl);

$result = json_decode($response);

$access_token = $result->access_token;

curl_close($curl);

$curl = curl_init();

$stkpush = array(
    'BusinessShortCode' => $BusinessShortCode,
    'Password' => $password,
    'Timestamp' => $date,
    'TransactionType' => 'CustomerPayBillOnline',
    'Amount' => $amount,
    'PartyA' => $phone,
    'PartyB' => $BusinessShortCode,
    'PhoneNumber' => $phone,
    'CallBackURL' => 'https://yourdomain.com/callback.php',
    'AccountReference' => 'Event Ticket',
    'TransactionDesc' => 'Ticket Payment'
);

$data_string = json_encode($stkpush);

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data_string,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$access_token
    ),
));

$response = curl_exec($curl);

echo $response;

curl_close($curl);

?>