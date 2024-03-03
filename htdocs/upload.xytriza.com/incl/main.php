<?php

chdir(dirname(__FILE__));

use PHPMailer\PHPMailer\PHPMailer;

function checkUserSession($conn) {
    if (isset($_COOKIE['session'])) {
        $session = $_COOKIE['session'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE session = ?");
        $stmt->bind_param("s", $session);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function formatUnitSize($unformattedsize) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
    $i = 0;
    while ($unformattedsize >= 1024 && $i < count($units) - 1) {
        $unformattedsize /= 1024;
        $i++;
    }

    $size = round($unformattedsize, 1) . ' ' . $units[$i];
    return $size;
}
function sendEmail($email, $password, $sender, $target, $target_user, $subject, $body, $ishtml, $mailhost) {
    require '../../../data/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../../../data/vendor/vendor/phpmailer/phpmailer/src/Exception.php';
    require '../../../data/vendor/vendor/phpmailer/phpmailer/src/SMTP.php';

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();

        $mail->Host = $mailhost;

        $mail->SMTPAuth = true;

        $mail->Username = $email;

        $mail->Password = $password;

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom($email, $sender);
        $mail->addAddress($target, $target_user);

        $mail->Subject = $subject;

        $mail->isHTML($ishtml);
        $mail->Body = $body;

        $mail->send();

        return true;
    } catch (Exception $e) {
        return false;
    }
}
function checkDiscordLink($id) {
    if ($id == null || $id == "") {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Discord link required</title>
    <link rel="icon" href="https://upload.xytriza.com/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://upload.xytriza.com/dashboard/assets/main.css?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://upload.xytriza.com/dashboard/assets/main.js?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.js'); ?>"></script>
</head>
<body>
    <div id="notification-container"></div>

    <div id="container">
        <div id="discord-link">
            <h1>Discord link required</h1>
            <p>You need to link your Discord account to use this service.</p>
            <button onclick="setCookieAndRedirect()">Link Discord</button>
        </div>
    </div>
</body>
</html>
<?php
        exit();
    }
}