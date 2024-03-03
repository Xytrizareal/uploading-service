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
    <title>Xytriza's Uploading Service - Terms of Service</title>
    <link rel="icon" href="https://upload.xytriza.com/assets/logo.png" type="image/png">
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
            <img src="https://upload.xytriza.com/assets/logo.png" alt="Xytriza's Uploading Service">
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
        <h1>Terms of Service for Xytriza's Uploading Service</h1>

        <p><strong>Effective Date:</strong> 1/21/2024</p>

        <h2>1. No Creating Alts or Ban Evading:</h2>
        <p>- Users are prohibited from creating alternative accounts (alts) or attempting to evade bans imposed by Xytriza's Uploading Service.</p>

        <h2>2. No Illegal Content:</h2>
        <p>- Users must not upload, share, or engage in any activity involving illegal content of any kind, including but not limited to, copyrighted material, explicit adult content, or any content that violates applicable laws.</p>

        <h2>3. No Account Sharing:</h2>
        <p>- Sharing accounts is not allowed. Each user is responsible for maintaining the confidentiality of their account information.</p>

        <h2>4. No IP Logging, Phishing, or Malware:</h2>
        <p>- Users are strictly prohibited from engaging in IP logging, phishing, or using the service to spread malware.</p>

        <h2>5. No Spamming our Services:</h2>
        <p>- Spamming, including but not limited to, excessive promotional messages, is not allowed on Xytriza's Uploading Service.</p>

        <h2>6. No Abusing Bugs on the Site:</h2>
        <p>- Users must not exploit or abuse any bugs or vulnerabilities on the site. Any identified issues should be reported to cryfxreal@gmail.com.</p>

        <h2>7. No Selling Premium Invites or Accounts:</h2>
        <p>- Users are not allowed to sell premium invites or accounts for Xytriza's Uploading Service.</p>

        <h2>8. Changes to Terms:</h2>
        <p>- These terms may change with a 30-day notice on the dashboard of Xytriza's Uploading Service. It is the user's responsibility to stay informed about any updates.</p>

        <p>By using Xytriza's Uploading Service, you acknowledge and agree to abide by these Terms of Service. Violation of these terms may result in account suspension or termination.</p>

        <p>If you have any questions or concerns regarding these Terms of Service, please contact us at cryfxreal@gmail.com.</p>

        <p>Thank you for choosing Xytriza's Uploading Service.</p>
    </div>

    <footer>
        <p>&copy; 2024 Xytriza | <a href="mailto:contact@yourcompany.com">Contacts</a> | <a href="mailto:abuse@yourcompany.com">Abuse</a> | <a href="/privacy">Privacy</a> | <a href="/terms">Terms</a> | <a href="mailto:support@yourcompany.com">Support</a> | <a href="/discord">Discord</a></p>
    </footer>
</body>
</html>