<?php
header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode(['status' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Validate required fields
if (empty($data['name']) || empty($data['phone']) || empty($data['email'])) {
    echo json_encode(['status' => false, 'message' => 'Missing required fields']);
    exit;
}

// Initialize cURL
$ch = curl_init('https://partner-api.posbytz.com/partner-api/v1/leads/signup');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-partner-domain: smartbytz.com',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute request
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
