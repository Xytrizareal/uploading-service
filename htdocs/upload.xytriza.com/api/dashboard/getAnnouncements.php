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

$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : '';

$stmt = $conn->prepare("SELECT * FROM users WHERE api_key = ?");
$stmt->bind_param("s", $key);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid API Key',
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

$conn->close();
?>