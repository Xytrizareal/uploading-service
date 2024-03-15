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

        a {
            color: #fff;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div id="main-container">
        <h1>Welcome!</h1>
        <p>Please create an account to continue</p>
        <form id="loginForm">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="username" id="username" name="username" placeholder="Username" required>
            <input type="text" id="displayname" name="displayname" placeholder="Display Name (optional)" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <?php Captcha::displayCaptcha(); ?>
            <button type="submit" id="btn" style="margin-bottom: 5px;">Register</button>
        </form>
        <a id="btn" href="/dashboard/login.php">Already have an account?</a>
    </div>
    <div id="check-email" style="display: none; text-align: center;">
        <h1>Check your email</h1>
        <p>We sent an email to confirm your email address.</p>
        <p id="email-sender" style="display: none;"></p>
    </div>

    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (event) {
                event.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: "POST",
                    url: '../../api/registerAccount.php',
                    data: formData,
                    success: function (data, textStatus, xhr) {
                        console.log(data, xhr.status);
                        try {
                            var parts = email.split('@');
                            var domain = parts[1];

                            var emailProviders = {
                                'gmail.com': 'mail.google.com',
                                'yahoo.com': 'mail.yahoo.com',
                                'hotmail.com': 'mail.live.com',
                                'outlook.com': 'outlook.live.com',
                                'aol.com': 'mail.aol.com',
                                'icloud.com': 'icloud.com',
                                'protonmail.com': 'protonmail.com',
                                'mail.com': 'mail.com',
                                'zoho.com': 'zoho.com',
                                'yandex.com': 'mail.yandex.com',
                                'live.com': 'mail.live.com',
                                'inbox.com': 'inbox.com',
                                'fastmail.com': 'fastmail.com',
                                'gmx.com': 'mail.gmx.com',
                                'rocketmail.com': 'mail.yahoo.com',
                                'mail.ru': 'mail.ru',
                                'naver.com': 'mail.naver.com',
                                'rediffmail.com': 'mail.rediff.com',
                                'tutanota.com': 'tutanota.com',
                                'seznam.cz': 'email.seznam.cz',
                                'me.com': 'me.com',
                                'cox.net': 'webmail.cox.net',
                                'sbcglobal.net': 'att.yahoo.com',
                                'att.net': 'att.yahoo.com',
                                'verizon.net': 'mail.aol.com',
                                'earthlink.net': 'webmail.earthlink.net',
                                'optonline.net': 'mail.optimum.net',
                                'roadrunner.com': 'webmail.spectrum.net',
                                'windstream.net': 'windstream.net',
                                'juno.com': 'webmail.juno.com',
                                'aim.com': 'mail.aol.com',
                                'mailinator.com': 'mailinator.com',
                                'comcast.net': 'xfinity.com',
                                'bellsouth.net': 'mail.yahoo.com',
                                'charter.net': 'spectrum.net',
                                'frontier.com': 'frontier.com',
                                'centurylink.net': 'centurylink.net',
                                'shaw.ca': 'webmail.shaw.ca',
                                'telus.net': 'webmail.telus.net',
                                'sympatico.ca': 'bell.net',
                                'rogers.com': 'rogers.com',
                                'videotron.ca': 'webmail.videotron.ca',
                                'hotmail.co.uk': 'mail.live.com',
                                'yahoo.co.uk': 'mail.yahoo.com',
                                'live.co.uk': 'mail.live.com',
                                'outlook.co.uk': 'outlook.live.com',
                                'btinternet.com': 'mail.yahoo.com',
                                'blueyonder.co.uk': 'blueyonder.co.uk',
                                'talktalk.net': 'webmail.talktalk.net',
                                'virginmedia.com': 'virginmedia.com'
                            };

                            if (emailProviders[domain] && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                                var emailSenderTag = document.getElementById("email-sender");
                                emailSenderTag.innerHTML = '<a href="https://' + emailProviders[domain] + '">Click here to view your inbox</a>';
                                emailSenderTag.style.display = "block";
                            }
                        } catch (ignoreError) {
                        }

                        //document.title = "Check your eamil";
                        //document.getElementById("main-container").style.display = "none";
                        //document.getElementById("check-email").style.display = "block";
                        //email shit coming soon!!!!!!
                        window.location.href = "/dashboard/login.php";
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.response + ' (' + xhr.status + ')');
                    }
                });
            });
        });
    </script>
</body>
</html>