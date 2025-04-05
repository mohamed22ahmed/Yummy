<?php
$secretKey = '1ab534db708f4a56a35e650b0fe23796b90c8a4bc26d48869d2a9632343a67979b609d9327fd411188f2d8c1b9640366bb442cba994d4546b3fa47b95d29ce5cc624f372a4ce4eeb83c614f4149087b396daa5456041468d9da1e52b7fe87fc0e9235304cf4840eea7a3cfcd82aa367e34ad33ae025b4a78863f492c22389896';
$accessKey = '8d3e1ea6d00c3efebc18e22b1d9c91ae';
$profileId = '3BFF2F9C-5F6A-477A-A0EF-788EA432A200';
$secureAcceptanceURL = 'https://testsecureacceptance.cybersource.com/pay';
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Pragma: no-cache'); // For HTTP/1.0
header('Expires: 0');       // For proxies

// Payment Details
$amount = '10.00'; // Example amount
$currency = 'USD'; // Example currency
$paymentReference = uniqid(); // Unique reference for the payment

// Data to be sent to Cybersource
$data = [
    'access_key' => $accessKey,
    'profile_id' => $profileId,
    'transaction_uuid' => uniqid(),
    "signed_field_names" => "access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency",
    "unsigned_field_names" => "",
    'signed_date_time' => gmdate('Y-m-d\TH:i:s\Z'),
    'locale' => 'en',
    'transaction_type' => 'sale,create_payment_token',
    'reference_number' => time(),
    'amount' => $amount,
    'currency' => $currency
];

// Generate the signature
 function generateSignature($data, $secretKey): string
{
    $signedFieldNames = explode(",", $data["signed_field_names"]);
    $dataToSign = [];
    foreach ($signedFieldNames as $field) {
        if (isset($data[$field])) {
            $dataToSign[] = $field . "=" . $data[$field];
        }
    }
    $dataString = implode(",", $dataToSign);

    return base64_encode(hash_hmac('sha256', $dataString, $secretKey, true));
}

$data['signature'] = generateSignature($data, $secretKey);

// Generate the payment URL
$paymentURL = $secureAcceptanceURL . '?' . http_build_query($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Page</title>
</head>
<body>
<form id="payment_confirmation" action="https://testsecureacceptance.cybersource.com/pay" method="post">
    <?php
        foreach($data as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }
    ?>
    <input type="submit" id="submit" value="Confirm"/>
</form>
</body>
</html>