<?php
require '../../config/config.php';

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

$session = isset($_COOKIE['session']) ? $_COOKIE['session'] : '';

if (isset($session) && empty($session)) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid session',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt = $conn->prepare("SELECT api_key FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $api_key = $user['api_key'];

    $response = [
        'success' => 'true',
        'response' => 'Success',
        'api_key' => $api_key,
    ];
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid session',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt->close();
$conn->close();
?>