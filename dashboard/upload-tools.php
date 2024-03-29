<?php
include '../config/config.php';
require '../incl/mainLib.php';

$main = new mainLib();

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die('Unable to access the database, please try again later');
}

if (!$main->checkUserSession($conn)) {
    setcookie('session', '', time(), "/", "", true, true);
    header('Location: /dashboard/login.php');
    die();
}

$session = htmlspecialchars($_COOKIE['session']);

$stmt = $conn->prepare("SELECT api_key, role, discord_id FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$stmt->bind_result($api_key, $role, $discord_id);
$stmt->fetch();
$stmt->close();

$conn->close();

$main->checkDiscordLink($discord_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Upload Tools</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/dashboard/assets/main.css?v=<?php echo filemtime(__DIR__.'/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/dashboard/assets/main.js?v=<?php echo filemtime(__DIR__.'/assets/main.js'); ?>"></script>
</head>
<body>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
        <a href="/dashboard/"><i class="fas fa-home sidebar-item"></i></a>
        <a href="/dashboard/files.php"><i class="fas fa-file-alt sidebar-item" style="margin-left: 20%;"></i></a>
        <a href="/dashboard/upload.php"><i class="fas fa-upload sidebar-item"></i></a>
        <a href="/dashboard/settings.php"><i class="fas fa-cog sidebar-item"></i></a>
        <a href="/dashboard/account.php" style="margin-top: auto;"><i class="fas fa-user-cog sidebar-item"></i></a>
        <?php
        if ($role === 1 || $role === 2) {
            echo '<a href="/dashboard/admin" class="sidebar-item"><i class="fas fa-user-shield"></i></a>
            <a href="/dashboard/admin/users.php" class="sidebar-item"><i class="fas fa-user-edit"></i></a>';
        }
        ?>
        <a href="javascript:logout()"><i class="fas fa-sign-out-alt sidebar-item"></i></a>
    </div>

    <div id="notification-container"></div>

    <div id="container">
        <h1>Upload Tools</h1>
        <?php
        if (isset($_GET['type'])) {
            if ($_GET['type'] === 'windows') {
                echo '<h3>Windows - Upload Configuration</h3>
                <p>Download ShareX here: <a href="https://getsharex.com/" style="text-decoration: underline;" target="_blank">https://getsharex.com/</a></p>
                <p>Download the ShareX upload config here: <a href="javascript:downloadConfig()" style="text-decoration: underline;">Download</a></p>
                <p>Run the ShareX upload config and click "Yes" when prompted</p>';
            } elseif ($_GET['type'] === 'ios') {
                echo '<h3>iOS - Upload Configuration</h3>
                <p>Download the Shortcuts app <a href="https://apps.apple.com/us/app/shortcuts/id915249334" style="text-decoration: underline;" target="_blank">here</a></p>
                <p>Download the iOS shortcut <a href="https://www.icloud.com/shortcuts/335833f37e244a54914fbc5c65dcd6f4" style="text-decoration: underline;">here</a></p>
                <p>Copy your api key from <a href="/dashboard/settings.php" style="text-decoration: underline;">your account settings</a></p>
                <p>Click "Set Up Shortcut"</p>
                <p>Paste your API Key then click "Add Shortcut"
                <p>Optionally add the shortcut to a wiget on your home screen to easily upload files and run the shortcut</p>';
            } else {
                echo '<h3>Other Platform support coming soon!</h3>';
            }
        } else {
            header('Location: /dashboard/settings.php');
        }
        echo '<button onclick="window.location.href=\'/dashboard/settings.php\'">Back</button>';
        ?>
</body>

</html>