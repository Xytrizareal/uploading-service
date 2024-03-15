<?php
include '../config/config.php';
include '../incl/main.php';

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die('Unable to access the database, please try again later');
}

if (!checkUserSession($conn)) {
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

checkDiscordLink($discord_id);

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Upload a file</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/dashboard/assets/main.css?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/dashboard/assets/main.js?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.js'); ?>"></script>
</head>
<body>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
        <a href="/dashboard/"><i class="fas fa-home sidebar-item"></i></a>
        <a href="/dashboard/gallery.php"><i class="fas fa-file-alt sidebar-item" style="margin-left: 20%;"></i></a>
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

    <div id="upload">
        <div id="dropZone">
            <p>Drag and drop a file here or click to upload</p>
        </div>
        <input type="file" id="fileInput" style="display: none;" />
        <p>Or upload a file from a URL</p>
        <div id="urlUpload">
            <input type="text" id="urlInput" placeholder="Enter Direct File URL" />
            <input type="text" id="fileName" placeholder="File URL Filename" />
            <button onclick="handleUrlUpload()" style="display: block; margin-left: auto; margin-right: auto;">Upload</button>
        </div>
        <div id="progressText" class="progressText" style="display: none; font-weight: bold;">0%</div>
    </div>
</body>

</html>