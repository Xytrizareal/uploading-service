<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Privacy Policy</title>
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

        .dropdown {
            cursor: pointer;
            border: none;
            text-align: left;
            outline: none;
            color: #fff;
            padding: 14px 16px;
            transition: 0.4s;
            font-size: 17px;
            background-color: #3c076e;
            border-radius: 5px;
        }

        .dropdown:hover {
            background-color: #370664;
        }

        .dropdown-content {
            display: none;
            position: relative;
            background-color: #1B1B1B;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
        }

        .dropdown-content p {
            color: #fff;
            text-decoration: none;
            display: block;
        }

        .dropdown-content.show {
            display: block;
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
        <h1>Privacy Policy for Xytriza's Uploading Service</h1>

        <p><strong>Effective Date:</strong> 1/21/2024</p>
        
        <h2>1. Definitions</h2>
        <p>- This Privacy Policy ("Policy") is designed to inform you about how Xytriza's Uploading Service ("we", "our", or "us") collects and uses your personal information. When we refer to "we", "our", or "us" in this Policy, we are referring to Xytriza's Uploading Service. When we refer to "you" or "your" in this Policy, we are referring to the individual using our services.</p>

        <h2>2. Location of Servers:</h2>
        <p>- Our servers are hosted in Santa Clara, California, United States.</p>

        <h2>3. Storage Provider:</h2>
        <p>- We use Digital Ocean Spaces to store files. It is important to note that no personal information is sent to Digital Ocean Spaces.</p>

        <h2>4. Logged Information:</h2>
        <p>- For security reasons, we log your most recent login IP and registration IP. This information is utilized to enhance the security of your account.</p>

        <h2>5. Policy Changes:</h2>
        <p>- We reserve the right to change this privacy policy with a 30-day notice. Any updates will be announced on the dashboard of Xytriza's Uploading Service.</p>

        <h2>6. Age Restriction:</h2>
        <p>- You must be 13 years or older to use our service. If you believe an underage user is using our service, please report it to us at cryfxreal@gmail.com.</p>

        <h2>7. Contact Information:</h2>
        <p>- You can contact us through our Discord server or via email at cryfxreal@gmail.com.</p>

        <h2>8. Compliance with Law:</h2>
        <p>- We may provide your data if required by law, subpoena, or other legal processes.</p>

        <h2>9. Registration Data:</h2>
        <p>- When you register, your data is stored on our servers to facilitate your use of Xytriza's Uploading Service.</p>

        <h2>10. Account Deletion:</h2>
        <p>- You have the right to delete your account at any time. Deleting your account will remove your personal information from our servers.</p>

        <h2>11. File Uploads:</h2>
        <p>- When you upload files, they are stored in Google Cloud Storage. We do not access or use the content of your uploaded files for any purpose other than providing the intended service.</p>

        <h2>12. Server Host:</h2>
        <p>- Our server host is Digital Ocean, ensuring a secure and reliable hosting environment.</p>

        <p>By using Xytriza's Uploading Service, you acknowledge and agree to the practices described in this Privacy Policy. We are committed to protecting your privacy and providing a secure and reliable service.</p>

        <p>If you have any questions or concerns regarding this Privacy Policy, please contact us at cryfxreal@gmail.com.</p>

        <p>Thank you for choosing Xytriza's Uploading Service.</p>
    </div>
    <footer>
        <p>&copy; 2024 Xytriza | <a href="mailto:cryfxreal@gmail.com">Contact</a> | <a href="/privacy">Privacy</a> | <a href="/terms">Terms</a> | <a href="/discord">Discord</a></p>
    </footer>
</body>
</html>