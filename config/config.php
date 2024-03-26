<?php
$dbservername = "127.0.0.1";
$dbusername = "";
$dbpassword = "";
$dbname = "";

$googleProjectId = "";
$googleBucketName = "";

$discordBotToken = "";
$discordGuildId = "";
$discordClientId = "";
$discordClientSecret = "";

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

if (empty($dbservername) || empty($dbusername) || empty($dbpassword) || empty($dbname) || empty($googleProjectId) || empty($googleBucketName) || empty($discordBotToken) || empty($discordGuildId) || empty($discordClientId) || empty($discordClientSecret) || empty($serverUrl)) {
	die("The configuration is incomplete. Please fill in all fields in the configuration file.");
}
?>