<?php
include '../config/config.php';
require '../incl/mainLib.php';

$main = new mainLib();

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die('Unable to access the database, please try again later');
}

if (!$main->checkUserSession($conn)) {
    setcookie('session', '', time(), "/", "", true, true);
    header('Location: /dashboard/login.php');
    die();
}

$session = htmlspecialchars($_COOKIE['session']);

$stmt = $conn->prepare("SELECT uid, username, email, api_key, country, timezone, dateformat, timeformat, role, discord_id FROM users WHERE session = ?");
$stmt->bind_param("s", $session);
$stmt->execute();
$stmt->bind_result($uid, $username, $email, $api_key, $country, $timezone, $dateFormat, $timeFormat, $role, $discord_id);
$stmt->fetch();
$stmt->close();

$main->checkDiscordLink($discord_id);

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Account Management</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/dashboard/assets/main.css?v=<?php echo filemtime(__DIR__.'/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/dashboard/assets/main.js?v=<?php echo filemtime(__DIR__.'/assets/main.js'); ?>"></script>
</head>
<body>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
        <a href="/dashboard/"><i class="fas fa-home sidebar-item"></i></a>
        <a href="/dashboard/files.php"><i class="fas fa-file-alt sidebar-item" style="margin-left: 20%;"></i></a>
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

    <div id="container">
        <h1>Upload Tools</h1>
        <div class="upload-tools">
                <div class="tool-box">
                    <a href="/dashboard/upload-tools.php?type=windows">
                        <div class="tool-title">Windows</div>
                        <div class="tool-icon"><i class="fab fa-windows"></i></div>
                    </a>
                </div>
                <div class="tool-box">
                    <a href="/dashboard/upload-tools.php?type=macos">
                        <div class="tool-title">MacOS</div>
                        <div class="tool-icon"><i class="fab fa-apple"></i></div>
                    </a>
                </div>
                <div class="tool-box">
                    <a href="/dashboard/upload-tools.php?type=ios">
                        <div class="tool-title">iOS</div>
                        <div class="tool-icon"><i class="fab fa-app-store-ios"></i></div>
                    </a>
                </div>
                <div class="tool-box">
                    <a href="/dashboard/upload-tools.php?type=linux">
                        <div class="tool-title">Linux</div>
                        <div class="tool-icon"><i class="fab fa-linux"></i></div>
                    </a>
                </div>
                <div class="tool-box">
                    <a href="/dashboard/upload-tools.php?type=android">
                        <div class="tool-title">Android</div>
                        <div class="tool-icon"><i class="fab fa-android"></i></div>
                    </a>
                </div>
        </div>
        <button onclick="downloadConfig()" style="margin-top: 8px;">Download ShareX Config</button>
    </div>

    <div id="container">
            <h1>Time Settings</h1>
            <div class="settings-group">
                <label for="country" style="color: #fff;">Country:</label>
                <select id="country" name="country">
                    <?php
                    $countries = ["Andorra", "French Southern and Antarctic Lands", "Laos", "Canada", "Nigeria", "Vanuatu", "Czechia", "Malawi", "Mali", "Iceland", "Norway", "Saint Vincent and the Grenadines", "Guadeloupe", "Chile", "Bermuda", "Kuwait", "Dominica", "Montenegro", "United States Virgin Islands", "Cameroon", "Sri Lanka", "China", "Bangladesh", "Sweden", "Grenada", "Turkey", "Guinea", "Tanzania", "Rwanda", "Singapore", "Morocco", "Saint Barthélemy", "Iraq", "Brunei", "Isle of Man", "North Korea", "Iran", "Curaçao", "Paraguay", "Albania", "Tajikistan", "Bolivia", "Austria", "Saint Kitts and Nevis", "United States Minor Outlying Islands", "Colombia", "Kosovo", "Belize", "Guinea-Bissau", "Marshall Islands", "Myanmar", "French Polynesia", "Brazil", "Croatia", "Somalia", "Afghanistan", "Anguilla", "Cook Islands", "Western Sahara", "New Zealand", "Eritrea", "Cambodia", "Bahamas", "Belarus", "Norfolk Island", "Tuvalu", "South Georgia", "Mauritania", "New Caledonia", "Bulgaria", "Mozambique", "Niue", "Estonia", "Italy", "Malta", "Slovenia", "India", "Peru", "Burundi", "Lithuania", "United States", "Honduras", "Tonga", "Saudi Arabia", "Suriname", "Qatar", "Saint Helena, Ascension and Tristan da Cunha", "Gibraltar", "Northern Mariana Islands", "Mauritius", "Barbados", "Réunion", "British Indian Ocean Territory", "Syria", "Egypt", "São Tomé and Príncipe", "Kiribati", "Timor-Leste", "Lesotho", "Solomon Islands", "Libya", "South Korea", "Liechtenstein", "Nicaragua", "Ecuador", "Maldives", "Algeria", "Kyrgyzstan", "Finland", "Antarctica", "Kenya", "Cuba", "Montserrat", "Poland", "Åland Islands", "Ethiopia", "Togo", "Bosnia and Herzegovina", "Uruguay", "Guam", "Cape Verde", "Chad", "Vatican City", "Palau", "Haiti", "Yemen", "Eswatini", "Zimbabwe", "Greece", "Israel", "Saint Martin", "Antigua and Barbuda", "Cyprus", "Sint Maarten", "Monaco", "Fiji", "Ukraine", "Martinique", "Hong Kong", "Portugal", "Bhutan", "Nepal", "France", "Ireland", "United Arab Emirates", "Guernsey", "Saint Lucia", "Dominican Republic", "Serbia", "Botswana", "Ivory Coast", "Ghana", "Comoros", "Azerbaijan", "United Kingdom", "Central African Republic", "Palestine", "Caribbean Netherlands", "Taiwan", "Pitcairn Islands", "San Marino", "Svalbard and Jan Mayen", "Djibouti", "Wallis and Futuna", "Denmark", "Papua New Guinea", "Madagascar", "Bouvet Island", "Hungary", "Tokelau", "Trinidad and Tobago", "Gambia", "Luxembourg", "Cocos (Keeling) Islands", "Republic of the Congo", "Argentina", "DR Congo", "Greenland", "Jordan", "Belgium", "Switzerland", "Indonesia", "Lebanon", "Malaysia", "Cayman Islands", "Slovakia", "Armenia", "Christmas Island", "Mongolia", "Saint Pierre and Miquelon", "Japan", "South Africa", "Philippines", "Micronesia", "Germany", "Latvia", "Jamaica", "Macau", "Nauru", "Faroe Islands", "Guyana", "Burkina Faso", "Sudan", "Russia", "Mayotte", "Australia", "Liberia", "Mexico", "Tunisia", "Aruba", "Kazakhstan", "Oman", "French Guiana", "Niger", "Turkmenistan", "Sierra Leone", "Samoa", "Senegal", "Georgia", "Namibia", "South Sudan", "Thailand", "Bahrain", "Heard Island and McDonald Islands", "Falkland Islands", "Jersey", "Vietnam", "Guatemala", "Moldova", "North Macedonia", "Uzbekistan", "Romania", "Uganda", "El Salvador", "Zambia", "Gabon", "Equatorial Guinea", "Spain", "Netherlands", "British Virgin Islands", "Benin", "Pakistan", "Panama", "Turks and Caicos Islands", "Angola", "American Samoa", "Venezuela", "Costa Rica", "Puerto Rico", "Seychelles"];

                    sort($countries);

                    foreach ($countries as $option) {
                        echo '<option class="settings-value" value="' . htmlspecialchars($option) . '"';
                        if ($option == $country) {
                            echo ' selected';
                        }
                        echo '>' . htmlspecialchars($option) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="settings-group">
                <label for="timezone" style="color: #fff;">Timezone:</label>
                <select id="timezone" name="timezone">
                    <?php
                    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

                    foreach ($timezones as $option) {
                        echo '<option class="settings-value" value="' . htmlspecialchars($option) . '"';
                        if ($option == $timezone) {
                            echo ' selected';
                        }
                        echo '>' . htmlspecialchars($option) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="settings-group settings-radio-group">
                <div class="settings-label">Date Format:</div>
                <label class="radio-container">
                    <input type="radio" name="dateFormat" value="mmddyyyy" <?php echo $dateFormat == "mmddyyyy" ? 'checked' : ''; ?>>
                    <i class="far fa-circle"></i>
                    <i class="fas fa-check-circle"></i>
                    mm/dd/yyyy
                </label>
                <label class="radio-container">
                    <input type="radio" name="dateFormat" value="ddmmyyyy" <?php echo $dateFormat == "ddmmyyyy" ? 'checked' : ''; ?>>
                    <i class="far fa-circle"></i>
                    <i class="fas fa-check-circle"></i>
                    dd/mm/yyyy
                </label>
            </div>

            <div class="settings-group settings-radio-group">
                <div class="settings-label">Time Format:</div>
                <label class="radio-container">
                    <input type="radio" name="timeFormat" value="12hour" <?php echo $timeFormat == "12" ? 'checked' : ''; ?>>
                    <i class="far fa-circle"></i>
                    <i class="fas fa-check-circle"></i>
                    12-hour
                </label>
                <label class="radio-container">
                    <input type="radio" name="timeFormat" value="24hour" <?php echo $timeFormat == "24" ? 'checked' : ''; ?>>
                    <i class="far fa-circle"></i>
                    <i class="fas fa-check-circle"></i>
                    24-hour
                </label>
            </div>

            <button onclick="saveTimeSettings()" style="margin-top: 8px;">Save Settings</button>
        </div>
    </div>
</body>

</html>