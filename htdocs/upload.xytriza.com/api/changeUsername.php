<?php
require '../config/config.php';
require '../incl/main.php';

$old_username = isset($_POST['oldusername']) ? $_POST['oldusername'] : '';
$new_username = isset($_POST['newusername']) ? $_POST['newusername'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($old_username) || empty($new_username) || empty($password)) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Missing Old Username, New Username, or Password']));
}

if (strlen($new_username) < 3 || strlen($new_username) > 32) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'New Username must be between 3 and 32 characters']));
}

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Unable to access database']));
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $old_username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        die(json_encode(['success' => 'false', 'response' => 'Password is incorrect']));
    }

    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_username, $old_username);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => 'true', 'response' => 'Username changed successfully']);
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'User not found']));
}

$stmt->close();
$conn->close();
?>