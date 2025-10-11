<?php
header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'Invalid request method']);
    exit;
}

// Get form data safely
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$business_type = $_POST['business_type'] ?? 'Others';

// Map business type to ID
$businessTypeMap = [
    "Restaurant" => 1,
    "Bar & Restaurant" => 2,
    "Cloud Kitchen" => 3,
    "Resto Bar & Pub" => 4,
    "Others" => 7
];
$business_type_id = $businessTypeMap[$business_type] ?? 7;

// Prepare payload for API
$data = [
    "name" => $name,
    "phone" => $phone,
    "calling_code" => "91",
    "email" => $email,
    "country_code" => "IN",
    "business_name" => $business_type,
    "location_name" => "India",
    "business_type_id" => (string)$business_type_id,
    "source" => "Website Form"
];

// Initialize cURL
$ch = curl_init('https://partner-api.posbytz.com/partner-api/v1/leads/signup');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'x-partner-domain: posbytz.com'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Return JSON response to JS
if ($response === false) {
    echo json_encode(['status' => false, 'message' => $curl_error]);
} else {
    $decoded = json_decode($response, true) ?: $response;
    echo json_encode(['status' => $httpcode === 200, 'response' => $decoded]);
}
?>
