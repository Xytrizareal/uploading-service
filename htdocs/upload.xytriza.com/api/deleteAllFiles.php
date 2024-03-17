<?php
require '../../../data/vendor/autoload.php';
require '../config/config.php';

use Google\Cloud\Storage\StorageClient;

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
$session = isset($_COOKIE['session']) ? $_COOKIE['session'] : '';

if (!empty($key)) {
    $sql = "SELECT uid FROM users WHERE api_key = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
} else if (!empty($session)) {
    $sql = "SELECT uid FROM users WHERE session = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $session);
    $stmt->execute();
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid API key',
    ];
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $uid = $row["uid"];

    $storage = new StorageClient([
        'projectId' => $googleProjectId,
        'keyFilePath' => $googleKeyFilePath,
    ]);

    $bucket = $storage->bucket($googleBucketName);

    $sql = "SELECT id FROM uploads WHERE uid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uid);
    $stmt->execute();

    $result = $stmt->get_result();

    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $object = $bucket->object($id);
        $object->delete();

        $sql = "DELETE FROM uploads WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
    }

    $response = [
        'success' => 'true',
        'response' => 'All files deleted successfully',
    ];

    http_response_code(200);
} else {
    $response = [
        'success' => 'false',
        'response' => 'Invalid API key',
    ];
    http_response_code(401);
}

header('Content-Type: application/json');
echo json_encode($response);

?>