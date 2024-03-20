<?php
require '../config/config.php';
include '../incl/captcha.php';
require '../incl/mainLib.php';

$main = new mainLib();

if (isset($_COOKIE['session'])) {
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

    if (!$conn->connect_error && $main->checkUserSession($conn)) {
        header('Location: /dashboard');
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="/assets/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="main-container">
        <h1>Welcome Back!</h1>
        <p>Please login to your account to continue</p>
        <form id="loginForm">
            <input type="text" id="username" name="username" placeholder="Username" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <?php Captcha::displayCaptcha(); ?>
            <button type="submit" id="btn" style="margin-bottom: 5px;">Login</button>
        </form>
        <a id="btn" href="/dashboard/register.php">Don't have an account?</a>
    </div>
    <script>
        $('#loginForm').on('submit', function(event) {
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: "POST",
                url: '../../api/loginAccount.php',
                data: formData,
                complete: function(xhr, textStatus) {
                    if(xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        window.location.href = '/dashboard/';
                        var date = new Date();
                        date.setTime(date.getTime() + (3*24*60*60*1000));
                        var expires = "; expires=" + date.toGMTString();
                        document.cookie = "session=" + response.session + expires + "; path=/";
                    } else {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.response + ' (' + xhr.status + ')');
                        location.reload();
                    }
                }
            });
        });
    </script>
</body>
</html>