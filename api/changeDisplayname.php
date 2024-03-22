<?php
require '../config/config.php';

$old_displayname = isset($_POST['olddisplayname']) ? $_POST['olddisplayname'] : '';
$new_displayname = isset($_POST['newdisplayname']) ? $_POST['newdisplayname'] : '';
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($old_displayname) || empty($new_displayname) || empty($password)) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Missing Username, Old Display Name, New Display Name, or Password']));
}

if (strlen($new_displayname) < 3 || strlen($new_displayname) > 32) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'responsae' => 'New Display Name must be between 3 and 32 characters']));
    exit;
}

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Unable to access database']));
}

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        die(json_encode(['success' => 'false', 'response' => 'Password is incorrect']));
    }

    $stmt = $conn->prepare("UPDATE users SET display_name = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_displayname, $username);
    $stmt->execute();

    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => 'true', 'response' => 'Display Name changed successfully']);
} else {
    http_response_code(404);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'User not found']));
}

$stmt->close();
$conn->close();
?>