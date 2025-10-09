<?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = [
        "name" => $_POST['name'],
        "phone" => $_POST['phone'],
        "calling_code" => $_POST['calling_code'], // "91" or "971"
        "email" => $_POST['email'],
        "country_code" => $_POST['country_code'], // "IN" or "AE"
        "business_name" => $_POST['business_type'],
        "location_name" => $_POST['location_name'], // "India" or "UAE"
        "business_type_id" => $_POST['business_type_id'],
        "source" => "Website Form"
    ];

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
    curl_close($ch);

    header('Content-Type: application/json');
    echo json_encode(['status' => $httpcode, 'response' => json_decode($response)]);
}
?>
