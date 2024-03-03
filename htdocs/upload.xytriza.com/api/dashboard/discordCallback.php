<?php
include '../../config/config.php';

if (isset($_GET['code']) && !empty($_GET['code']) && isset($_COOKIE['session']) && !empty($_COOKIE['session'])){
    $code = $_GET['code'];
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/v8/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $discordClientId,
        'client_secret' => $discordClientSecret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $discordRedirectUri,
        'scope' => 'identify guilds.join, email, identify'
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200) {
        $response = [
            'success' => 'false',
            'message' => 'Invalid code provided.',
        ];
        header('Content-Type: application/json');
        die(json_encode($response));
    }
    $data = json_decode($response, true);

    curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/v8/users/@me');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $data['access_token']
    ]);
    curl_setopt($ch, CURLOPT_POST, 0);
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($status_code != 200) {
        $response = [
            'success' => 'false',
            'message' => 'Unable to fetch user data.',
        ];
        header('Content-Type: application/json');
        die(json_encode($response));
    }
    $userData = json_decode($response, true);

    curl_setopt($ch, CURLOPT_URL, "https://discord.com/api/v8/guilds/{$discordGuildId}/members/{$userData['id']}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bot ' . $discordBotToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'access_token' => $data['access_token'],
        'roles' => [],
        'mute' => false,
        'deaf' => false
    ]));
    curl_exec($ch);
    curl_close($ch);

    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        $response = [
            'success' => 'false',
            'message' => 'Unable to connect to the database.',
        ];
        header('Content-Type: application/json');
        die(json_encode($response));
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE session = ?");
    $stmt->bind_param("s", $_COOKIE['session']);
    $stmt->execute();
    $result = $stmt->get_result();
    $sqlData = $result->fetch_assoc();

    if ($result->num_rows == 0) {
        $response = [
            'success' => 'false',
            'message' => 'Invalid session.',
        ];
        header('Content-Type: application/json');
        die(json_encode($response));
    }

    $avatar = "https://cdn.discordapp.com/avatars/" . $userData['id'] . "/" . $userData['avatar'];

    $stmt = $conn->prepare("UPDATE users SET discord_id = ?, discord_access_token = ?, discord_refresh_token = ?, discord_expires_in = ?, discord_avatar = ?, discord_email = ? WHERE email = ?");
    $stmt->bind_param("sssssss", $userData['id'], $data['access_token'], $data['refresh_token'], $data['expires_in'], $avatar, $userData['email'], $sqlData['email']);
    $stmt->execute();

    if (isset($_COOKIE['redirect_link']) && !empty($_COOKIE['redirect_link']) && strpos($_COOKIE['redirect_link'], 'https://upload.xytriza.com/dashboard') === 0) {
        setcookie('redirect_link', '', time() - 3600, '/', '', true, true);
        // deepcode ignore OR: already validated in the if condition
        header('Location: ' . $_COOKIE['redirect_link']);
        die();
    }

    $response = [
        'success' => 'true',
        'message' => 'Successfully saved to database'
    ];
    header('Content-Type: application/json');
    die(json_encode($response));
} else {
    $response = [
        'success' => 'false',
        'message' => 'Invalid request.',
    ];
    header('Content-Type: application/json');
    die(json_encode($response));
}
?>