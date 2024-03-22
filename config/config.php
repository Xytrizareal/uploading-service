<?php
$dbservername = "127.0.0.1";
$dbusername = "";
$dbpassword = "";
$dbname = "";

$googleProjectId = "";
$googleBucketName = "";

$discordBotToken = "";
$discordChannelId = "";
$discordGuildId = "";
$discordClientId = "";
$discordClientSecret = "";
$discordRedirectUri = "";

/*
	Captcha settings
	Supports: hCaptcha, reCaptcha, Cloudflare Turnstile
	hCaptcha: https://www.hcaptcha.com/
	reCaptcha: https://www.google.com/recaptcha/
	Cloudflare Turnstile: https://www.cloudflare.com/products/turnstile/
*/

$enableCaptcha = false;
$captchaType = 1; // 1 for hCaptcha, 2 for reCaptcha and 3 for CF-Turnstile
$captchaKey = '';
$captchaSecret = '';

$serverUrl = ''; // the server's url, example: https://upload.xytriza.com
$serverPath = ''; // the server's folder path, example: /home/xytriza-upload/htdocs/upload.xytriza.com

if (empty($dbservername) || empty($dbusername) || empty($dbpassword) || empty($dbname) || empty($googleProjectId) || empty($googleBucketName) || empty($serverUrl) || empty($serverPath)) {
    http_response_code(500);
    die("Please check your config file (are all fields filled in?).");
}

if (!file_exists(__DIR__ . '/../packages/auth.json') || file_get_contents(__DIR__ . '/../packages/auth.json') == "Config for Google Cloud Storage will go here") {
    http_response_code(500);
    die("Please check your Google Cloud Storage IAM Service Account key is valid. (packages/auth.json)");
}

if ($enableCaptcha) {
    if (!in_array($captchaType, [1, 2, 3]) || empty($captchaKey) || empty($captchaSecret)) {
        http_response_code(500);
        die("There are issues with your captcha configuration, please check your captcha configuration in the config file.");
    }
}
?>