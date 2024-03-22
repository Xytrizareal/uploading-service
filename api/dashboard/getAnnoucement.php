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

$sql = "SELECT value FROM settings WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$news = base64_decode($row['value']);

$sql = "SELECT value FROM settings WHERE id = 2";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$motd = base64_decode($row['value']);

$response = [
    'success' => 'true',
    'response' => 'Here is the announcements',
    'news' => $news,
    'motd' => $motd,
];

http_response_code(200);
header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>