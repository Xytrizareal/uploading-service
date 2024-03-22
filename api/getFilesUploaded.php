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

$stmt = $conn->prepare("SELECT uid FROM users WHERE api_key = ?");
$stmt->bind_param("s", $apikey);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM uploads WHERE uid = ?");
    $stmt->bind_param("s", $user['uid']);
    $stmt->execute();

    $result = $stmt->get_result();
    $uploads = [];

    while ($row = $result->fetch_assoc()) {
        $uploads[] = [
            'id' => $row['id'],
            'size' => $row['size'],
            'uploaded' => $row['uploaded'],
            'filename' => base64_decode($row['original_name']),
            'filetype' => $row['filetype'],
            'deletionkey' => $row['delete_key'],
        ];
    }

    $response = [
        'success' => 'true',
        'response' => 'Here are your uploads',
        'uploads' => $uploads,
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