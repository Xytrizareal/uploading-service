<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service</title>
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
