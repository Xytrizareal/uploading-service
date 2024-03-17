<?php
require '../../../data/vendor/autoload.php';
require '../config/config.php';

use Google\Cloud\Storage\StorageClient;

if (isset($_GET['deletionkey']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $deleteKey = str_replace('/delete/', '', $_GET['deletionkey']); // used for /delete/ endpoint (nginx stuff blah blah)
} else if (isset($_SERVER['HTTP_DELETIONKEY'])) {
    $deleteKey = $_SERVER['HTTP_DELETIONKEY'];
} else {
    $response = [
        'success' => 'false',
        'response' => 'No deletion key provided',
    ];
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode($response));
}

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

$sql = "SELECT id FROM uploads WHERE delete_key = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $deleteKey);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = $row["id"];

    $storage = new StorageClient([
        'projectId' => $googleProjectId,
        'keyFilePath' => $googleKeyFilePath,
    ]);

    $bucket = $storage->bucket($googleBucketName);
    $object = $bucket->object($id);
    $object->delete();

    $sql = "DELETE FROM uploads WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id);
    $stmt->execute();

    $response = [
        'success' => 'true',
        'response' => 'File deleted successfully',
    ];

    http_response_code(200);
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid deletion key',
    ];
    http_response_code(401);
}

header('Content-Type: application/json');
echo json_encode($response);

?>