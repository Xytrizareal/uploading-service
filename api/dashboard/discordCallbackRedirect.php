<?php
include '../../config/config.php';

if (isset($_GET['path']) && !empty($_GET['path']))
setcookie('redirectPath', $_GET['path'], time() + (15 * 60), '/', '', true, true);
header("Location: https://discord.com/api/oauth2/authorize?client_id=$discordClientId&redirect_uri=".urlencode($serverUrl . "/api/dashboard/discordCallback.php")."&response_type=code&scope=identify+guilds.join+email")
?>