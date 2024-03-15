<?php
require '../../../data/vendor/autoload.php';
require '../config/config.php';
require '../incl/main.php';

use Google\Cloud\Storage\StorageClient;

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    $response = [
        'success' => false,
        'response' => 'Unable to access database',
    ];

    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$key = isset($_SERVER['HTTP_KEY']) ? $_SERVER['HTTP_KEY'] : '';

if ($_COOKIE['session']) {
    $sql = "SELECT uid FROM users WHERE session = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_COOKIE['session']);
} else {
    $sql = "SELECT uid, role FROM users WHERE api_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $key);
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $uid = $row["uid"];
} else {
    $response = [
        'success' => false,
        'response' => 'Invalid API Key',
    ];

    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$sql = "SELECT SUM(size) as totalSize FROM uploads WHERE uid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $uid);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();
$totalSize = $row['totalSize'];

if ($row['role'] === 1 && $totalSize > 161061273600) {
    $response = [
        'success' => false,
        'response' => 'You cannot use more than 150GB of storage',
    ];

    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode($response));
} elseif ($row['role'] === 2 && $totalSize > 26843545600) {
    $response = [
        'success' => false,
        'response' => 'You cannot use more than 25GB of storage',
    ];

    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode($response));
} else if ($totalSize > 5368709120) {
    $response = [
        'success' => false,
        'response' => 'You cannot use more than 5GB of storage',
    ];

    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode($response));
}

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    do {
        $randomString = generateRandomString(8);
        $sql = "SELECT id FROM uploads WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $randomString);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    do {
        $deleteKey = generateRandomString(24);
        $sql = "SELECT id FROM uploads WHERE delete_key = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $deleteKey);
        $stmt->execute();
        $result = $stmt->get_result();
    } while ($result->num_rows > 0);

    $storage = new StorageClient([
        'projectId' => $googleProjectId,
        'keyFilePath' => $googleKeyFilePath,
    ]);

    $bucket = $storage->bucket($googleBucketName);

    $filePath = $_FILES['file']['tmp_name'];
    $fileName = base64_encode(htmlspecialchars($_FILES['file']['name']));
    $fileType = (new finfo(FILEINFO_MIME_TYPE))->buffer(file_get_contents($filePath));

    $options = [
        'name' => $randomString,
        'metadata' => [
            'cacheControl' => 'no-cache, no-store, must-revalidate',
            'contentType' => $fileType,
        ],
    ];

    $bucket->upload(
        fopen($filePath, 'r'),
        $options
    );

    $size = filesize($filePath);

    $sql = "INSERT INTO uploads (id, uid, uploaded, delete_key, size, original_name, filetype) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $currentTime = time();
    $stmt->bind_param("siisiss", $randomString, $uid, $currentTime, $deleteKey, $size, $fileName, $fileType);
    $stmt->execute();

    $response = [
        'success' => true,
        'response' => 'File uploaded successfully',
        'fileUrl' => "$serverUrl/files/$randomString",
        'deletionUrl' => "$serverUrl/delete/$deleteKey",
        'deletionKey' => $deleteKey,
    ];

    http_response_code(200);
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => false,
        'response' => 'Invalid file upload'
    ];

    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode($response));
}
?>
