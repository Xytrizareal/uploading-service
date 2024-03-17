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

$apiKey = isset($_POST['key']) ? $_POST['key'] : null;
$session = isset($_COOKIE['session']) ? $_COOKIE['session'] : null;
$password = null;//isset($_POST['password']) ? $_POST['password'] : null;
$spoiler = isset($_POST['spoiler']) ? $_POST['spoiler'] : null;
$filename = isset($_POST['filename']) ? $_POST['filename'] : null;
$fileId = isset($_POST['fileId']) ? $_POST['fileId'] : null;

$query = "UPDATE uploads SET ";
$params = [];
$paramTypes = "";

if ($password !== null) {
    if (strlen($password) > 6) {
        $password = null;
    } else {
        $query .= "password = ?, ";
        array_push($params, $password);
        $paramTypes .= "s";
    }
}

if ($filename !== null) {
    if (strlen($filename) > 255) {
        $filename = null;
    } else {
        $query .= "original_name = ?, ";
        array_push($params, base64_encode($filename));
        $paramTypes .= "s";
    }
}

if ($password !== null || $filename !== null) {
    $query = substr($query, 0, -2);
    if ($apiKey !== null) {
        $query .= " WHERE id = ? AND uid = (SELECT uid FROM users WHERE api_key = ?)";
        array_push($params, $fileId, $apiKey);
        $paramTypes .= "ss";
    } elseif ($session !== null) {
        $query .= " WHERE id = ? AND uid = (SELECT uid FROM users WHERE session = ?)";
        array_push($params, $fileId, $session);
        $paramTypes .= "ss";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($paramTypes, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $response = [
            'success' => 'true',
            'response' => 'File preferences updated',
        ];
        http_response_code(200);
    } else {
        $response = [
            'success' => 'false',
            'response' => 'No update was made',
        ];
        http_response_code(400);
    }

    $stmt->close();
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid parameters',
    ];
    http_response_code(400);
}

header('Content-Type: application/json');
die(json_encode($response));