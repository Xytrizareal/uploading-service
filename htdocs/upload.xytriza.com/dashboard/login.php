<?php
require '../config/config.php';
include '../incl/main.php';
include '../incl/captcha.php';

if (isset($_COOKIE['session'])) {
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

    if (!$conn->connect_error && checkUserSession($conn)) {
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
    <link rel="icon" href="https://upload.xytriza.com/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            background-color: #1f1f1f;
            color: #fff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow-x: hidden;
            height: 100vh;
        }

        #main-container {
            text-align: center;
        }

        h1 {
            color: #3c076e;
            text-shadow: 2px 2px 4px #000;
        }

        p {
            margin-top: 20px;
            font-size: 18px;
            text-shadow: 1px 1px 2px #000;
        }

        #btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3c076e;
            color: #fff;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
        }

        #btn:hover {
            background-color: #370664;
            transform: scale(1.08);
        }

        #topic-container {
            text-align: left;
            max-width: 600px;
            margin-top: 10vh;
            transition: margin-top 0.5s;
        }

        .topic-wrapper {
            display: flex;
            justify-content: space-around;
            flex-wrap: nowrap;
        }

        .topic-box {
            flex: 0 0 30%;
            width: 320px;
            border: 2px solid #3c076e;
            margin: 10px;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease;
            background: #1B1B1B;
            text-align: center;
        }

        .topic-icon {
            background-color: #3c076e;
            padding: 10px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .topic-text {
            color: #fff;
            font-size: 18px;
            text-shadow: 1px 1px 2px #000;
            cursor: pointer;
        }

        .topic-text:hover {
            text-decoration: underline;
        }

        .topic-box:hover .topic-text {
            text-decoration: none;
        }

        .topic-box:hover {
            transform: scale(1.02);
        }

        .topic-description {
            color: #fff;
            font-size: 18px;
            text-shadow: 1px 1px 2px #000;
            transition: transform 0.3s;
        }

        input[type="text"], input[type="password"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 18px;
            border: 2px solid #3c076e;
            border-radius: 5px;
            background-color: #2f2f2f;
            color: #fff;
        }

        input[type="text"]::placeholder, input[type="password"]::placeholder {
            color: #aaa;
        }
    </style>
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