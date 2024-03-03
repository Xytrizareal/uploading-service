<?php
require '../config/config.php';
require '../incl/main.php';

$ip = $_SERVER['REMOTE_ADDR'];

$token = isset($_POST['captcha_token']) ? $_POST['captcha_token'] : '';

$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

$data = [
    'secret'    => '0x4AAAAAAARIYYLl4IlaAeS9Q40g-Y6QZ9M',
    'response'  => $token,
    'remoteip'  => $ip,
];

$options = [
    'http' => [
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result !== false) {
    $outcome = json_decode($result, true);

    if (!isset($outcome['success']) || !$outcome['success']) {
        $response = [
            'success' => 'false',
            'response' => 'Invalid captcha',
        ];
    
        http_response_code(403);
        header('Content-Type: application/json');
        die(json_encode($response));
    }
} else {
    $response = [
        'success' => 'false',
        'response' => 'Unable to validate captcha',
    ];

    http_response_code(500);
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

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$request_username = $_POST['username'];
$request_password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $request_username);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if (password_verify($request_password, $row["password"])) {
            $login_ip = $_SERVER['REMOTE_ADDR'];
            $login_time = time();
            $session_token = generateRandomString(255);
            $session_expires = $login_time + (7 * 24 * 60 * 60);
            $uid = $row['uid'];

            $stmt = $conn->prepare("UPDATE users SET session = ?, session_expire = ?, latest_ip = ? WHERE uid = ?");
            $stmt->bind_param("sssi", $session_token, $session_expires, $login_ip, $uid);
            $stmt->execute();

            $stmt = $conn->prepare("INSERT INTO logins (uid, session_token, session_expire, login_ip, login_time) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $uid, $session_token, $session_expires, $login_ip, $login_time);
            $stmt->execute();

            $row['session_token'] = $session_token;
            $row['session_expires'] = $session_expires;

            $response = [
                'success' => 'true',
                'session' => $session_token,
                'session_expires' => $session_expires,
            ];

            header('Content-Type: application/json');
            die(json_encode($response));
        } else {
            $response = [
                'success' => 'false',
                'response' => 'Incorrect username or password',
            ];
            
            http_response_code(401);
            header('Content-Type: application/json');
            die(json_encode($response));
        }
    }
} else {
    $response = [
        'success' => 'false',
        'response' => 'Incorrect username or password',
    ];
    
    http_response_code(401);
    header('Content-Type: application/json');
    die(json_encode($response));
}

$stmt->close();
$conn->close();

?>
