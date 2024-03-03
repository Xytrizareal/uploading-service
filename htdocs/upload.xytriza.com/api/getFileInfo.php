<?php
require '../config/config.php';
include '../incl/main.php';

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
$fileid = isset($_SERVER['HTTP_FILEID']) ? $_SERVER['HTTP_FILEID'] : '';

if (empty($apikey) || empty($fileid)) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid request',
    ];
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt = $conn->prepare("SELECT uid FROM users WHERE api_key = ?");
$stmt->bind_param("s", $apikey);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM uploads WHERE id = ?");
    $stmt->bind_param("s", $fileid);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $response = [
            'success' => 'false',
            'response' => 'File not found',
        ];
        http_response_code(404);
        header('Content-Type: application/json');
        die(json_encode($response));
    }

    $fileInfo = $result->fetch_assoc();

    $info = [
        'id' => $fileid,
        'uploaderUid' => $fileInfo['uid'],
        'size' => $fileInfo['size'],
        'sizeFormatted' => formatUnitSize($fileInfo['size']),
        'uploadedAt' => $fileInfo['uploaded'],
        'filename' => base64_decode($fileInfo['original_name']),
        'filetype' => $fileInfo['filetype'],
    ];

    $response = [
        'success' => true,
        'response' => 'Information for file "' . $fileid . '"',
        'fileinfo' => $info,
    ];

    http_response_code(200);
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid API key',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt->close();
$conn->close();
?>