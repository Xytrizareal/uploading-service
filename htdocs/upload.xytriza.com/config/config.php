<?php
$dbservername = "127.0.0.1";
$dbusername = "";
$dbpassword = "";
$dbname = "";

$googleProjectId = "";
$googleBucketName = "";
$googleKeyFilePath = "";

$discordBotToken = "";
$discordChannelId = "";
$discordGuildId = "";
$discordClientId = "";
$discordClientSecret = "";
$discordRedirectUri = "";

if (empty($dbservername) || empty($dbusername) || empty($dbpassword) || empty($dbname) || empty($googleProjectId) || empty($googleBucketName) || empty($googleKeyFilePath)) {
    http_response_code(500);
    die("Please check your config file (are all fields filled in?)");
}
?>