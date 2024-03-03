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

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();

function getRole($role) {
    return $role == 1 ? "Owner" : ($role == 2 ? "Admin" : ($role == 0 ? "User" : "Unknown"));
}
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
        .hidden {
            filter: blur(8px);
            transition: filter 0.25s ease;
        }

        .hidden:hover {
            filter: blur(0);
        }

        td {
            padding: 10px;
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
            <h1>Admin Panel</h1>
            <p>Manage users and settings</p>
        </div>
        
        <table>
            <tr>
                <th>UID</th>
                <th>Username</th>
                <th>Display Name</th>
                <th>Email</th>
                <th>Discord ID</th>
                <th>IP</th>
                <th>Role</th>
            </tr>
            <?php
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . (!empty($row['uid']) ? htmlspecialchars($row['uid']) : "N/A") . "</td>";
                echo "<td>" . (!empty($row['username']) ? htmlspecialchars($row['username']) : "N/A") . "</td>";
                echo "<td>" . (!empty($row['display_name']) ? htmlspecialchars($row['display_name']) : "N/A") . "</td>";
                if ($row['role'] != 0 || $role != 1) {
                    echo "<td>Hidden</td>";
                } else {
                    echo "<td class='hidden'>" . (!empty($row['email']) ? htmlspecialchars($row['email']) : "N/A") . "</td>";
                }
                echo "<td>" . (!empty($row['discord_id']) ? htmlspecialchars($row['discord_id']) : "Not Synced") . "</td>";
                if ($row['role'] != 0 || $role != 1) {
                    echo "<td>Hidden</td>";
                } else {
                    echo "<td class='hidden'>" . (!empty($row['latest_ip']) ? htmlspecialchars($row['latest_ip']) : "N/A") . "</td>";
                }
                echo "<td>" . (!empty(getRole($row['role'])) ? htmlspecialchars(getRole($row['role'])) : "N/A") . "</td>";
                echo "<td>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>