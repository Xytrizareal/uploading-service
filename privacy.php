<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Privacy Policy</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="/assets/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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