<?php

header("Content-Type: application/json");

// Get JSON from JS
$data = file_get_contents("php://input");

// Init CURL
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://partner-api.posbytz.com/partner-api/v1/leads/signup");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Headers for API
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-partner-domain: posbytz.com"
]);

$response = curl_exec($ch);

// Error check
if (curl_errno($ch)) {
    echo json_encode([
        "success" => false,
        "message" => curl_error($ch)
    ]);
    exit;
}

curl_close($ch);

// Send API response back
echo $response;
