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

$stmt = $conn->prepare("SELECT uid, display_name, username, email, api_key, discord_id, role, discord_avatar FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$stmt->bind_result($uid, $displayname, $username, $email, $api_key, $discord_id, $role, $avatar);
$stmt->fetch();
$stmt->close();

$displayname = htmlspecialchars($displayname, ENT_QUOTES, 'UTF-8');

$conn->close();

checkDiscordLink($discord_id);

$role_format = $role == 1 ? "Owner" : ($role == 2 ? "Admin" : ($role == 0 ? "User" : "Unknown"));
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
    <title>Xytriza's Uploading Service - Account Management</title>
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

    <div id="container" style="max-width: 48%;">
        <div id="account-information" class="section">
            <h2>Account Information</h2>
            <p id="username"><strong>Username:</strong> <?php echo $username;?></p>
            <p id="displayname"><strong>Display Name:</strong> <?php echo $displayname;?></p>
            <p><strong>UID:</strong> <?php echo $uid;?></p>
            <p><strong>Discord ID:</strong> <?php echo $discord_id;?></p>
            <p><strong>Email:</strong> <em class="blur-on-hover"><?php echo $email;?></em></p>
            <p><strong>Role:</strong> <?php echo $role_format;?></p>
        </div>

        <div class="row">
            <div id="file-settings" class="section half-width">
                <h2>File Settings</h2>
                <?php 'PGRpdiBjbGFzcz0ic2xpZGVyLWNvbnRhaW5lciI+CiAgICAgICAgICAgICAgICAgICAgPHAgc3R5bGU9ImRpc3BsYXk6IG5vbmU7IiBpZD0ic2V0dGluZ05hbWUiPmxvY2FsaXplZC10aW1lem9uZTwvcD4KICAgICAgICAgICAgICAgICAgICA8bGFiZWwgZm9yPSJib29sZWFuU2V0dGluZyIgY2xhc3M9InNsaWRlci1sYWJlbCI+VXNlIGxvY2FsaXplZCB0aW1lem9uZSBmb3IgZmlsZSBwYWdlPC9sYWJlbD4KICAgICAgICAgICAgICAgICAgICA8cD5EaXNhYmxlIHRvIHVzZSB5b3VyIHVzZXIgc2V0dGluZyB0aW1lem9uZSBmb3Igd2hlbiBhIHVzZXIgdmlld3MgYSBmaWxlIHlvdSBwb3N0ZWQ8cD4KICAgICAgICAgICAgICAgICAgICA8cD5FbmFibGUgdG8gdXNlIHRoZSB1c2VyJ3MgdGltZXpvbmUgZm9yIHdoZW4gYSB1c2VyIHZpZXdzIGEgZmlsZSB5b3UgcG9zdGVkPHA+CiAgICAgICAgICAgICAgICAgICAgPGxhYmVsIGNsYXNzPSJzd2l0Y2giPgogICAgICAgICAgICAgICAgICAgICAgICA8aW5wdXQgdHlwZT0iY2hlY2tib3giIGlkPSJib29sZWFuU2V0dGluZyI8P3BocCBlY2hvICRsb2NhbGl6c2VkVGltZXpvbmUgPT09ICd0cnVlJyA/ICIgY2hlY2tlZCIgOiAiIjs/Pj4KICAgICAgICAgICAgICAgICAgICAgICAgPHNwYW4gY2xhc3M9InNsaWRlciByb3VuZCI+PC9zcGFuPgogICAgICAgICAgICAgICAgICAgIDwvbGFiZWw+CiAgICAgICAgICAgICAgICA8L2Rpdj4=';
                ?>
                <button onclick="deleteAllFiles();">Delete all files</button>
            </div>

            <div id="change-password" class="section half-width">
                <h2>Change Password</h2>
                <form id="passwordChangeForm">
                    <input type="password" id="oldPassword" placeholder="Old Password">
                    <input type="password" id="newPassword" placeholder="New Password">
                    <input type="password" id="confirmPassword" placeholder="Confirm New Password">
                    <input type="hidden" id="username" value="<?php echo $displayname;?>">
                    <button type="submit">Change Password</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div id="change-username" class="section half-width">
                <h2>Change Username</h2>
                <form id="usernameChangeForm">
                    <input type="text" id="newUsername" placeholder="New Username">
                    <input type="hidden" id="oldUsername" value="<?php echo $username;?>">
                    <input type="password" id="usernamepassword" placeholder="Password">
                    <button type="submit">Change Username</button>
                </form>
                <h2>Change Display Name</h2>
                <form id="displaynameChangeForm">
                    <input type="text" id="newDisplayname" placeholder="New Display Name">
                    <input type="hidden" id="oldDisplayname" value="<?php echo $displayname;?>">
                    <input type="hidden" id="username" value="<?php echo $username;?>">
                    <input type="password" id="displayusernamepassword" placeholder="Password">
                    <button type="submit">Change Display Name</button>
                </form>
            </div>

            <div id="user-settings" class="section half-width">
                <h2>User Settings</h2>
                <button onclick="setCookieAndRedirect()">Re-Link Discord</button>
                <button onclick="copyAPIKey();">Copy API Key</button>
                <button onclick="generateAPIKey();">Generate new API Key</button>
            </div>
        </div>
    </div>
</body>

</html>