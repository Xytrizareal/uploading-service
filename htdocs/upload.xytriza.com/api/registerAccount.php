<?php
require '../config/config.php';
require '../incl/main.php';
require '../incl/captcha.php';

if(!Captcha::validateCaptcha()) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid captcha',
    ];

    http_response_code(403);
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

$request_username = isset($_POST['username']) ? $_POST['username'] : '';
$request_displayname = (isset($_POST['displayname']) && !empty($_POST['displayname'])) ? $_POST['displayname'] : $request_username;
$request_password = isset($_POST['password']) ? $_POST['password'] : '';
$request_email = isset($_POST['email']) ? $_POST['email'] : '';

if (empty($request_username) || empty($request_password) || empty($request_email)) {
    $response = [
        'success' => 'false',
        'response' => 'Missing username, password or email',
    ];

    http_response_code(400);
    die(json_encode($response));
}

if (!ctype_alnum($request_username)) {
    $response = [
        'success' => 'false',
        'response' => 'Username must be alphanumeric',
    ];

    http_response_code(400);
    die(json_encode($response));
}

if (!filter_var($request_email, FILTER_VALIDATE_EMAIL)) {
    $response = [
        'success' => 'false',
        'response' => 'Invalid email',
    ];

    http_response_code(400);
    die(json_encode($response));
}

if (strlen($request_password) < 8 || !preg_match("/[A-Za-z]/", $request_password) || !preg_match("/[0-9]/", $request_password)) {
    $response = [
        'success' => 'false',
        'response' => 'Password must be at least 8 characters long and contain at least one letter and one number',
    ];

    http_response_code(400);
    die(json_encode($response));
}

if (strlen($request_username) < 3 || strlen($request_username) > 32) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Userame must be between 3 and 32 characters']));
    exit;
}

if (strlen($request_displayname) < 3 || strlen($request_displayname) > 32) {
    http_response_code(400);
    header('Content-Type: application/json');
    die(json_encode(['success' => 'false', 'response' => 'Display Name must be between 3 and 32 characters']));
    exit;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$hashed_password = password_hash($request_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$stmt->bind_param("ss", $request_username, $request_email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = [
        'success' => 'false',
        'response' => 'Username or email already exists',
    ];

    http_response_code(400);
    die(json_encode($response));
} else {
    $api_key = generateRandomString(32);
    $emailconfirm = generateRandomString(255);
    $emailcontent = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Email Confirmation</title>
        <style>
            body {
                font-family: "Arial", sans-serif;
                background-color: #1f1f1f;
                margin: 0;
                padding: 0;
                text-align: center;
            }

            .container {
                background-color: #222;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin: 20px auto;
                padding: 20px;
                max-width: 600px;
            }

            h1 {
                color: #fff;
            }

            p {
                color: #fff;
                font-size: 16px;
                line-height: 1.6;
            }

            a {
                display: inline-block;
                padding: 12px 24px;
                font-size: 16px;
                text-align: center;
                text-decoration: none;
                color: #fff !important;
                background-color: #3c076e;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Email Confirmation</h1>
            <p>Thank you for signing up with Xytriza\'s Uploading Service! We\'re excited to have you on board.</p>
            <p>To complete the registration process, please click on the link below to confirm your email address:</p>
    
            <a href="https://upload.xytriza.com/confirm-email/' . $emailconfirm . '">Confirm Your Email</a>
    
            <p>If you did not sign up for Xytriza\'s Uploading Service, please disregard this email and the account will be unregistered within 2 hours.</p>
    
            <p>Thank you,<br>Xytriza\'s Uploading Service Team</br></p>
        </div>
    </body>
    </html>';
    $emailresult = true;//sendEmail($noreply_email_address, $noreply_email_password, $noreply_email_address, $request_email, $request_username, "Action Required: Confirm Your Email Address with Xytriza\'s Uploading Service", $emailcontent, true, 'smtp.ionos.com');
    // will do soon (real)

    if (!$emailresult) {
        $response = [
            'success' => 'false',
            'response' => 'Error sending conformation email, please try again later'
        ];
    
        http_response_code(500);
        echo json_encode($response);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, display_name, password, email, api_key, emailconfirm, register_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $request_username, $request_displayname, $hashed_password, $request_email, $api_key, $emailconfirm, time());
    $stmt->execute();

    $response = [
        'success' => 'true',
        'response' => 'Account created successfully'
    ];

    http_response_code(200);
    echo json_encode($response);
}

$stmt->close();
$conn->close();

?>