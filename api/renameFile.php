<?php
require '../config/config.php';

header('Content-Type: application/json');

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'response' => 'Unable to access database']);
    exit;
}

$apiKey = isset($_POST['key']) ? $_POST['key'] : null;
$session = isset($_COOKIE['session']) ? $_COOKIE['session'] : null;
$fileId = isset($_POST['fileId']) ? $_POST['fileId'] : null;
$fileName = isset($_POST['fileName']) ? $_POST['fileName'] : null;

if (!$apiKey && !$session) {
    http_response_code(401);
    echo json_encode(['success' => false, 'response' => 'Invalid API key or session']);
    exit;
}

if ($apiKey) {
    $stmt = $conn->prepare("SELECT uid FROM users WHERE api_key = ?");
    $stmt->bind_param("s", $apiKey);
} else {
    $stmt = $conn->prepare("SELECT uid FROM users WHERE session = ?");
    $stmt->bind_param("s", $session);
}

$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    http_response_code(401);
    echo json_encode(['success' => false, 'response' => 'Authentication failed']);
    exit;
}

$user = $result->fetch_assoc();
$uid = $user['uid'];

if (!$fileId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'response' => 'File ID not provided']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM uploads WHERE id = ? AND uid = ?");
$stmt->bind_param("si", $fileId, $uid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows == 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'response' => 'File not found or access denied']);
    exit;
}

if (!isset($fileName) || trim($fileName) === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'response' => 'Filename cannot be empty']);
    exit;
}

$stmt = $conn->prepare("UPDATE uploads SET original_name = ? WHERE id = ?");
$stmt->bind_param("ss", base64_encode(trim($fileName)), $fileId);
$stmt->execute();
$stmt->close();

echo json_encode(['success' => true, 'response' => 'File name changed successfully', 'filename' => trim($fileName)]);

$conn->close();
