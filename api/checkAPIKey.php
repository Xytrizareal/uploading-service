<?php
require '../config/config.php';

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    $response = [
        'success' => 'false',
        'response' => 'Unable to access database',
    ];

    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$apikey = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : '';

if (isset($apikey) && empty($apikey)) {
    $response = [
        'success' => 'false',
        'response' => 'API Key not provided',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt = $conn->prepare("SELECT api_key FROM users WHERE api_key = ?");
$stmt->bind_param("s", $apikey);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = [
        'success' => 'true',
        'response' => 'API Key is valid',
    ];
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => 'false',
        'response' => 'API Key is invalid',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt->close();
$conn->close();
?>