<?php
// Enable CORS for all origins (adjust to your domain in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate input
if (!$data) {
    echo json_encode(['status' => false, 'message' => 'Invalid JSON']);
    exit;
}

if (empty($data['name']) || empty($data['phone']) || empty($data['email'])) {
    echo json_encode(['status' => false, 'message' => 'Missing required fields']);
    exit;
}

// Send lead to PosBytz Production API
$ch = curl_init('https://partner-api.posbytz.com/partner-api/v1/leads/signup');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-partner-domain: posbytz.com', // Production partner domain
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // verify SSL

$response = curl_exec($ch);

// Handle cURL errors
if (curl_errno($ch)) {
    echo json_encode(['status' => false, 'message' => 'cURL Error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Return API response
echo $response;
?>
