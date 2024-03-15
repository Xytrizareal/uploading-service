<?php
require '../../config/config.php';
require '../../incl/main.php';

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

$stmt = $conn->prepare("SELECT * FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$key = $row['api_key'];

if ($result->num_rows == 0) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid session',
    ];

    http_response_code(403);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$config = '{
    "Name": "Xytriza\'s Uploading Service",
    "DestinationType": "ImageUploader, FileUploader",
    "RequestType": "POST",
    "RequestURL": "'.$serverUrl.'/api/uploadFile.php",
    "FileFormName": "file",
    "Body": "MultipartFormData",
    "Headers": {
        "key": "' . $key . '"
    },
    "URL": "$json:fileUrl$",
    "DeletionURL": "$json:deletionUrl$",
    "ErrorMessage": "$json:error$"
}';
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="xytrizas-uploading-service.sxcu"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($config));
echo $config;

$stmt->close();
$conn->close();