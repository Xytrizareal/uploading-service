<?php
include '../../config/config.php';
include '../../incl/main.php';

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

$stmt = $conn->prepare("SELECT uid, username, email, api_key, role, discord_id FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$stmt->bind_result($uid, $username, $email, $api_key, $role, $discord_id);
$stmt->fetch();
$stmt->close();

if ($role !== 1 && $role !== 2) {
    header('Location: /dashboard/');
    die();
}

checkDiscordLink($discord_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['news'])) {
        $news = base64_encode(htmlspecialchars($_POST['news']));
        $sql = "UPDATE settings SET value = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $news);
        $stmt->execute();
        $stmt->close();
    }
    if (isset($_POST['motd'])) {
        $motd = base64_encode(htmlspecialchars($_POST['motd']));
        $sql = "UPDATE settings SET value = ? WHERE id = 2";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $motd);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT value FROM settings WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$news = base64_decode($row['value']);

$sql = "SELECT value FROM settings WHERE id = 2";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$motd = base64_decode($row['value']);

$totalSize = 0;
$uploadCount = 0;

$sql = "SELECT size FROM uploads";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $totalSize += $row['size'];
    $uploadCount++;
}

$sql = "SELECT COUNT(*) FROM users";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$users = $row['COUNT(*)'];

$sql = "SELECT COUNT(*) FROM logins";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$logins = $row['COUNT(*)'];

$sql = "SELECT COUNT(*) FROM uploads";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$uploads = $row['COUNT(*)'];

$conn->close();

$size = formatUnitSize($totalSize);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SH08WXZBBG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-SH08WXZBBG');
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Dashboard</title>
    <link rel="icon" href="https://upload.xytriza.com/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://upload.xytriza.com/dashboard/assets/main.css?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://upload.xytriza.com/dashboard/assets/main.js?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.js'); ?>"></script>
    <style>
        textarea {
            width: 100%;
            height: 200px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="https://upload.xytriza.com/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
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

    <div id="container">
        <div class="info-group">
            <div class="info-box">
                <p class="big"><strong>Total Storage Used</strong></p>
                <p><?php echo $size;?></p>
            </div>
            <div class="info-box">
                <p class="big"><strong>Total Uploads</strong></p>
                <p><?php echo $uploads;?></p>
            </div>
            <div class="info-box">
                <p class="big"><strong>Total Users</strong></p>
                <p><?php echo $users;?></p>
            </div>
            <div class="info-box">
                <p class="big"><strong>Total Logins</strong></p>
                <p><?php echo $logins;?></p>
            </div>
        </div>
        <p>Dynamic placeholders like %discord% (Discord link), %username% (user's name), %uploads% (user's upload count), %role% (user's role), %uid% (user ID), %storage% (user's storage usage), and date details (%year%, %month%, %monthformat%, %day%, %dayformat%, %date%) are automatically replaced with specific user or system information for personalized messages.</p>
        <form method="POST">
            <p>News</p>
            <textarea name="news"><?php echo $news; ?></textarea>
            <p>MOTD</p>
            <textarea name="motd"><?php echo $motd; ?></textarea>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>