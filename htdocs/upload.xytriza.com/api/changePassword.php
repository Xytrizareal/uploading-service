<?php
require '../config/config.php';
require '../incl/main.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$old_password = isset($_POST['oldpassword']) ? $_POST['oldpassword'] : '';
$new_password = isset($_POST['newpassword']) ? $_POST['newpassword'] : '';

if (empty($username) || empty($old_password) || empty($new_password)) {
    http_response_code(400);
    die(json_encode(['success' => 'false', 'response' => 'Missing Username, Old Password, or New Password']));
}

if (isset($_POST['confirmpassword']) && $_POST['confirmpassword'] != $new_password) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Passwords do not match']));
}

if (strlen($new_password) < 8 || !preg_match("/[A-Za-z]/", $new_password) || !preg_match("/[0-9]/", $new_password)) {
    http_response_code(400);
    die(json_encode(['success' => 'false', 'response' => 'New password must be at least 8 characters long and contain at least one letter and one number']));
}

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => 'false', 'response' => 'Unable to access database']));
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (!password_verify($old_password, $user['password'])) {
        http_response_code(400);
        die(json_encode(['success' => 'false', 'response' => 'Old password is incorrect']));
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $empty = null;

    $stmt = $conn->prepare("UPDATE users SET password = ?, session = ?, session_expire = ? WHERE username = ?");
    $stmt->bind_param("ssss", $hashed_password, $empty, $empty, $username);
    $stmt->execute();

    http_response_code(200);
    echo json_encode(['success' => 'true', 'response' => 'Password changed successfully']);
} else {
    http_response_code(404);
    die(json_encode(['success' => 'false', 'response' => 'User not found']));
}

$stmt->close();
$conn->close();
?>