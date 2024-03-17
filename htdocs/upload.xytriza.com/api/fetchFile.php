<?php
require "../config/config.php";
require '../../../data/vendor/autoload.php';
require '../incl/mainLib.php';

$main = new mainLib();

use Google\Cloud\Storage\StorageClient;

if (extension_loaded("zlib")) {
    ob_start("ob_gzhandler");
}

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    $response = [
        "success" => "false",
        "response" => "Unable to access the database",
    ];

    http_response_code(500);
    header("Content-Type: application/json");
    die(json_encode($response));
}

$file = rtrim(str_replace("/files/", "", htmlspecialchars($_GET["url"])), "/");

if ($_GET['raw'] == 'true') {
    $file = str_replace("?raw=true", "", $file);
    header("Location: https://storage.googleapis.com/$googleBucketName/$file");
}

$storage = new StorageClient([
    'projectId' => $googleProjectId,
    'keyFilePath' => $googleKeyFilePath,
]);
$bucket = $storage->bucket($googleBucketName);

$stmt = $conn->prepare("SELECT id, uid, uploaded, original_name, filetype FROM uploads WHERE id = ?");
$stmt->bind_param("s", $file);
$stmt->execute();
$stmt->bind_result($id, $uid, $uploaded, $original_filename, $fileType);

if (!$stmt->fetch()) {
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - File not found</title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/dashboard/assets/main.css?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/dashboard/assets/main.js?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.js'); ?>"></script>
</head>
<body>
    <?php
    if ($main->checkUserSession($conn)) {
        $stmt = $conn->prepare("SELECT role FROM users WHERE session = ?");
        $stmt->bind_param("s", $_COOKIE["session"]);
        $stmt->execute();

        $role = $stmt->get_result()->fetch_assoc()["role"];
        ?>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
        <a href="/dashboard/"><i class="fas fa-home sidebar-item"></i></a>
        <a href="/dashboard/gallery.php"><i class="fas fa-file-alt sidebar-item" style="margin-left: 20%;"></i></a>
        <a href="/dashboard/upload.php"><i class="fas fa-upload sidebar-item"></i></a>
        <a href="/dashboard/settings.php"><i class="fas fa-cog sidebar-item"></i></a>
        <?php
        if ($role === 1 || $role === 2) {
            echo '<a href="/dashboard/admin" class="sidebar-item"><i class="fas fa-user-shield"></i></a>
            <a href="/dashboard/admin/users.php" class="sidebar-item"><i class="fas fa-user-edit"></i></a>';
        }
        ?>
        <a href="/dashboard/account.php" style="margin-top: auto;"><i class="fas fa-user-cog sidebar-item"></i></a>
        <a href="javascript:logout()"><i class="fas fa-sign-out-alt sidebar-item"></i></a>
    </div>
    <?php
    }
    ?>

    <div id="notification-container"></div>

    <div id="container">
        <div id="discord-link">
            <h1>File not found</h1>
            <p>We searched everywhere, but we couldn't find that file.</p>
            <button onclick="window.history.back()">Back</button>
        </div>
    </div>
</body>

</html>
<?php
    $stmt->close();
    $conn->close();
    http_response_code(404);
    die();
}

$stmt->close();

$fileUrl = "https://storage.googleapis.com/$googleBucketName/$file";

$stmt = $conn->prepare("SELECT display_name, timezone FROM users WHERE uid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$stmt->bind_result($username, $timezone);
$stmt->fetch();
$stmt->close();

$username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

if (!isset($timezone) || empty($timezone) || !in_array($timezone, DateTimeZone::listIdentifiers(DateTimeZone::ALL))) {
    $timezone = "America/Los_Angeles";
}

$date = new DateTime("@{$uploaded}");
$date->setTimezone(new DateTimeZone($timezone));

$utcOffset = $date->getOffset() / 3600;
$utcOffsetFormatted = $utcOffset > 0 ? "+{$utcOffset}" : $utcOffset;

$formattedDate = $date->format("m/d/Y, g:i:s A") . " UTC{$utcOffsetFormatted}";

$now = new DateTime();
$uploadedDate = DateTime::createFromFormat("U", $uploaded);
$interval = $now->diff($uploadedDate);

$timeDiff = "Uploaded by {$username} ";

$timeUnits = ['year', 'month', 'day', 'hour', 'minute', 'second'];
$timeValues = [$interval->y, $interval->m, $interval->d, $interval->h, $interval->i, $interval->s];

for ($i = 0; $i < count($timeUnits); $i++) {
    if ($timeValues[$i] > 0) {
        $timeDiff .= $timeValues[$i] . " " . $timeUnits[$i] . ($timeValues[$i] > 1 ? 's' : '') . ' ago';
        break;
    }
}

try {
    $object = $bucket->object($id);
    $objectData = $object->downloadAsString();
} catch (Exception $e) {
    die("Error searching for file.");
}

if (in_array($fileType, ["image/png", "image/jpeg", "image/gif", "image/webp", "image/svg+xml"])) {
    $objectInfo = getimagesizefromstring($objectData);

    $width = $objectInfo[0];
    $height = $objectInfo[1];
}

$original_filename = base64_decode($original_filename);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="robots" content="noindex">
    <title><?php echo $original_filename . ' - ' . $username;?></title>
    <link rel="icon" href="/assets/logo.png" type="image/png">
    <meta charset="UTF-8">
    <meta name="description" content="<?php echo 'Uploaded ' . $formattedDate . ' by ' . $username;?>">
    <meta property="og:title" content="<?php echo $original_filename?>">
    <meta property="og:description" content="<?php echo 'Uploaded ' . $formattedDate . ' by ' . $username;?>">
    <meta property="og:site_name" content="Xytriza's Uploading Service">
    <?
    if (in_array($fileType, ["image/png", "image/jpeg", "image/gif", "image/webp", "image/svg+xml"])) {
        echo '<meta property="og:image" content="' . $fileUrl . '">
        <meta property="og:image:alt" content="' . $original_filename . '">
        <meta property="og:image:width" content="' . $width . '">
        <meta property="og:image:height" content="' . $height . '">';
    }
    ?>
    <meta property="theme-color" content="#3c076e">
    <meta property="twitter:card" content="summary_large_image">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="/dashboard/assets/main.css?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.css'); ?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="/dashboard/assets/main.js?v=<?php echo filemtime($serverPath.'/dashboard/assets/main.js'); ?>"></script>
    <style>
        img {
            max-width: 50vw;
            max-height: 50vh;
        }
    </style>
</head>
<body>
    <?php
    if ($main->checkUserSession($conn)) {
        $stmt = $conn->prepare("SELECT role FROM users WHERE session = ?");
        $stmt->bind_param("s", $_COOKIE["session"]);
        $stmt->execute();

        $role = $stmt->get_result()->fetch_assoc()["role"];
        ?>
    <div id="sidebar">
        <a href="/" class="logo"><img class="sidebar-item" src="/assets/logo.png" alt="Xytriza's Uploading Service" height="40vw" width="40vw"></a>
        <a href="/dashboard/"><i class="fas fa-home sidebar-item"></i></a>
        <a href="/dashboard/gallery.php"><i class="fas fa-file-alt sidebar-item" style="margin-left: 20%;"></i></a>
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
    <?php
    }
    ?>

    <div id="notification-container"></div>

    <div id="container">
        <div id="content">
            <div id="file-container">
                <h1><? echo "{$original_filename} • {$timeDiff}"; ?></h1>
                <?php
                if (in_array($fileType, ["image/png", "image/jpeg", "image/gif", "image/webp", "image/svg+xml"])) {
                    echo '<img src="' . $fileUrl . '" alt="' . htmlspecialchars($file, ENT_QUOTES, 'UTF-8') . '">';
                } elseif (in_array($fileType, ["video/mp4", "video/webm", "video/ogg"])) {
                    echo '<video width="100%" height="auto" controls><source src="' . $fileUrl . '" type="' . htmlspecialchars($fileType, ENT_QUOTES, 'UTF-8') . '">Your browser does not support the video tag.</video>';
                } elseif (in_array($fileType, ["audio/mpeg", "audio/wav", "audio/ogg"])) {
                    echo '<audio controls><source src="' . $fileUrl . '" type="' . $fileType . '">Your browser does not support the audio element.</audio>';
                } elseif (in_array($fileType, ["text/plain"])) {
                    $fileContent = $object['Body']->getContents();
                    if (strlen($fileContent) > 100000) {
                        $fileContent = substr($fileContent, 0, 100000) . "... Preview too long. Download the file to view the full content.";
                    }
                    echo '<textarea readonly class="file-textbox">' . htmlspecialchars($fileContent, ENT_QUOTES, 'UTF-8') . '</textarea>';
                } else {
                    echo '<p>Unable to display file "' . $fileType . '". Suggest support for this type in our Discord.</p>';
                }
                ?>
            </div>
            <button class="button" onclick="downloadFile('<?php echo $fileUrl; ?>', '<?php echo $original_filename; ?>', '<?php echo $fileType; ?>')">Download</button>
            <button class="button" onclick="copyToClipboard('<?php echo $fileUrl; ?>', 'File URL copied to clipboard', 0)">Copy link</button>
            <?php
            if (in_array($fileType, ["text/plain"])) {
                echo '<button class="button" onclick="copyToClipboard(\'' . $fileContent . '\', \'File content copied to clipboard\', 0)">Copy</button>';
            }
            if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], "$serverUrl/dashboard/gallery.php") !== false) {
                echo '<button class="button" onclick="window.history.back()">Back</button>';
            }
            ?>
            </div>
        </div>
    </div>
    <script>
        if (window.location.search.includes("download=1")) {
            downloadFile("<?php echo $fileUrl; ?>", "<?php echo $original_filename; ?>", "<?php echo $fileType; ?>");
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>