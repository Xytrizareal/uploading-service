<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Terms of Service</title>
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
        <p>&copy; 2024 Xytriza | <a href="mailto:cryfxreal@gmail.com">Contact</a> | <a href="/privacy">Privacy</a> | <a href="/terms">Terms</a> | <a href="/discord">Discord</a></p>
    </footer>
</body>
</html>