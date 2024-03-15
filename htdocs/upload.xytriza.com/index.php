<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        }

        #top-bar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        #top-bar .logo {
            margin-left: 5%;
            margin-top: 20px;
        }

        #top-bar .logo img {
            width: 48px;
            height: 48px;
        }

        #top-bar .nav-links {
            margin-right: 5%;
            margin-top: 20px;
        }

        #top-bar .nav-links a {
            color: #fff;
            text-decoration: none;
            transition: 0.25s ease-in-out;
            border-radius: 5px;
            padding: 5px 8px;
        }

        #top-bar .nav-links a:hover {
            background-color: #3f3f3f;
        }

        #main-container {
            text-align: center;
            margin-top: 10vh;
            max-width: 90%;
        }

        h1, h2 {
            color: #3c076e;
            text-shadow: 2px 2px 4px #000;
        }

        p {
            margin-top: 20px;
            font-size: 16px;
            text-shadow: 1px 1px 2px #000;
        }

        #btn {
            display: inline-block;
            margin: 4px;
            padding: 10px 20px;
            background-color: #3c076e;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            text-shadow: 1px 1px 2px #000;
        }

        #btn:hover {
            background-color: #370664;
            transform: scale(1.08);
        }

        .topic-box {
            flex: 0 0 calc(100% - 20px);
            width: 100%;
            max-width: 270px;
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

        .topic-description {
            color: #fff;
            font-size: 18px;
            text-shadow: 1px 1px 2px #000;
            transition: transform 0.3s;
        }

        .topic-wrapper {
            display: flex;
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

        .topic-container {
            margin-top: 15vh;
        }

        footer p {
            text-align: center;
        }

        footer a {
            color: #fff;
        }

        @media only screen and (max-width: 600px) {
            .topic-box {
                width: 70%;
                max-width: none;
                margin: 10px auto;
            }

            .topic-wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div id="top-bar">
        <a href="/" class="logo">
            <img src="/assets/logo.png" alt="Xytriza's Uploading Service">
        </a>
        <div class="nav-links">
            <?php
            if (isset($_COOKIE['session'])) {
                echo '<a href="/dashboard">Dashboard</a>';
            } else {
                echo '<a href="/dashboard/login.php">Login</a>
                <a href="/dashboard/register.php">Register</a>';
            }
            ?>
            <a href="/discord">Discord</a>
        </div>
    </div>

    <div id="main-container">
        <h1>Xytriza's Uploading Service</h1>
        <p>Secure & Fast File-Uploader</p>
        <p><strong>Currently in beta</p>
        <?php
        if (isset($_COOKIE['session'])) {
            echo '<a id="btn" href="/dashboard">Dashboard</a>';
        } else {
            echo '<a id="btn" href="/dashboard/register.php">Get started</a>';
        }
        ?>
    </div>

    <div class="topic-container">
        <h2 style="text-align: center;">Features</h2>

        <div class="topic-wrapper">
            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-bolt" style="color: #fff;"></i></div>
                <p class="topic-text">Efficient</p>
                <p class="topic-description">High-speed servers ensure a swift and efficient upload experience.</p>
            </div>

            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-check-circle" style="color: #fff;"></i></div>
                <p class="topic-text">Dependable</p>
                <p class="topic-description">Maintaining a 99.99% uptime record for a reliable and consistent service.</p>
            </div>

            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-cogs" style="color: #fff;"></i></div>
                <p class="topic-text">Customizable</p>
                <p class="topic-description">Modern and customizable features cater to personalized image/video sharing preferences.</p>
            </div>
        </div>

        <div class="topic-wrapper">
            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-hands-helping" style="color: #fff;"></i></div>
                <p class="topic-text">Assistive</p>
                <p class="topic-description">Community and staff ready to help with any issues/questions.</p>
            </div>

            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-user-shield" style="color: #fff;"></i></div>
                <p class="topic-text">Privacy Friendly</p>
                <p class="topic-description">Respecting user privacy with clear data usage policies.</p>
            </div>

            <div class="topic-box">
                <div class="topic-icon"><i class="fas fa-share-alt" style="color: #fff;"></i></div>
                <p class="topic-text">Simple Setup</p>
                <p class="topic-description">Integration support for apps like ShareX for seamless uploading.</p>
            </div>
        </div>
    </div>
    <footer>
        <p>&copy; 2024 Xytriza | <a href="mailto:cryfxreal@gmail.com">Contact</a> | <a href="/privacy">Privacy</a> | <a href="/terms">Terms</a> | <a href="/discord">Discord</a></p>
    </footer>
</body>
</html>
