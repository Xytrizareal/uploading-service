<?php
use PHPMailer\PHPMailer\PHPMailer;
class mainLib {
    public function checkUserSession($conn) {
        if (isset($_COOKIE['session'])) {
            $session = $_COOKIE['session'];

            $stmt = $conn->prepare("SELECT * FROM users WHERE session = ?");
            $stmt->bind_param("s", $session);
            $stmt->execute();
            
            if ($stmt->get_result()->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    public function formatUnitSize($unformattedsize) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB');
        $i = 0;
        while ($unformattedsize >= 1024 && $i < count($units) - 1) {
            $unformattedsize /= 1024;
            $i++;
        }

        $size = round($unformattedsize, 1) . ' ' . $units[$i];
        return $size;
    }
    public function sendEmail($email, $password, $sender, $target, $target_user, $subject, $body, $ishtml, $mailhost) {
        //didnt test this as it isnt used yet, will soon 👍
        require dirname(__FILE__) . '../../../data/vendor/autoload.php';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();

            $mail->Host = $mailhost;

            $mail->SMTPAuth = true;

            $mail->Username = $email;

            $mail->Password = $password;

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->setFrom($email, $sender);
            $mail->addAddress($target, $target_user);

            $mail->Subject = $subject;

            $mail->isHTML($ishtml);
            $mail->Body = $body;

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    public function checkDiscordLink($id) {
        if ($id == null || $id == "") {
            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xytriza's Uploading Service - Discord link required</title>
    <link rel="icon" href="https://upload.xytriza.com/assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://upload.xytriza.com/dashboard/assets/main.css?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://upload.xytriza.com/dashboard/assets/main.js?v=<?php echo filemtime('/home/xytriza-upload/htdocs/upload.xytriza.com/dashboard/assets/main.js'); ?>"></script>
</head>
<body>
    <div id="notification-container"></div>
    <div id="container">
        <div id="discord-link">
            <h1>Discord link required</h1>
            <p>You need to link your Discord account to use this service.</p>
            <button onclick="setCookieAndRedirect()">Link Discord</button>
    </div>
    </div>
</body>
</html>
<?php
            exit();
        }
    }
    public function getIPAddress() {
        //from cvolton
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $this->isCloudFlareIP($_SERVER['REMOTE_ADDR']))
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && ipInRange::ipv4_in_range($_SERVER['REMOTE_ADDR'], '127.0.0.0/8'))
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        return $_SERVER['REMOTE_ADDR'];
    }
	public function isCloudFlareIP($ip) {
        //from cvolton
        include_once __DIR__ . "/ip_in_range.php";
    	$cf_ips = array(
	        '173.245.48.0/20',
			'103.21.244.0/22',
			'103.22.200.0/22',
			'103.31.4.0/22',
			'141.101.64.0/18',
			'108.162.192.0/18',
			'190.93.240.0/20',
			'188.114.96.0/20',
			'197.234.240.0/22',
			'198.41.128.0/17',
			'162.158.0.0/15',
			'104.16.0.0/13',
			'104.24.0.0/14',
			'172.64.0.0/13',
			'131.0.72.0/22'
	    );
	    foreach ($cf_ips as $cf_ip) {
	        if (ipInRange::ipv4_in_range($ip, $cf_ip)) {
	            return true;
	        }
	    }
	    return false;
	}
}