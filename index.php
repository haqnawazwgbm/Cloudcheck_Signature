<?php
// Access data
$access_key = '8h5niUPxfevw6Vmc';
$secret = 'aAmUhFvXI1ROLuKxaHaMbyQhA4nFXnXE';
$nonce = '2';
$timestamp = time().'000';
$data = '{"details":{"address":{"suburb":"Hillsborough","street":"27 Indira Lane","postcode":"8022","city":"Christchurch"},"name":{"given":"Cooper","middle":"John","family":"Down"},"dateofbirth":"1978-01-10"},"consent":"Yes"}';

// The API call path.
$path = '/verify/';

// Set up some dummy parameters. Sort alphabetically.
$parameterMap = array(
    'key' => $access_key,
    'nonce' => $nonce,
    'timestamp' => $timestamp,
    'data' => $data
);

ksort($parameterMap);

// Build the signature string from the parameters.
$signatureString = $path;
foreach ($parameterMap as $key => $value) {
    if ($key === 'signature') {
        continue;
    }

    $signatureString .= "$key=$value;";
}

printf("String: %s\n", $signatureString);

// Create the HMAC SHA-256 Hash from the signature string.
$signatureHex = hash_hmac('sha256', $signatureString, $secret, false);

// Outputs: 53ccc8563d21393bbac5a5a693e0ba7cc408f83dfb04008122510eee9e80aa86
printf("Signature: %s\n", $signatureHex);


// error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

//setting url
$url = "https://mydigitalafterlife.cloudcheck.co.nz/verify/?data=".urlencode($data)."&key=$access_key&nonce=$nonce&timestamp=$timestamp&signature=$signatureHex";
//data
$data = $data;
try {
    $ch = curl_init($url);
    $data_string = $data;
    if (FALSE === $ch)
        throw new Exception('failed to initialize');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/x-www-form-urlencoded', 'Content-Length: ' . strlen($data_string)));
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $output = curl_exec($ch);

    if (FALSE === $output)
        throw new Exception(curl_error($ch), curl_errno($ch));

    // ...process $output now
} catch(Exception $e) {

    trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR);
}
?>