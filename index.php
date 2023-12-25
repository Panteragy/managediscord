<?php
session_start();

// Discord OAuth details
$clientId = '1188175462451646504';
$clientSecret = 'G2fL5rDhLuYcQ8hCiBWShd5q_Q4Gz1M8';
$redirectUri = 'https://manage.trinow.zya.me/';
$discordApiUrl = 'https://discord.com/api/';

// Check if the user is already logged in
if (isset($_SESSION['discord_access_token'])) {
    // Fetch user details from Discord API
    $accessToken = $_SESSION['discord_access_token'];
    $apiUrl = $discordApiUrl . 'users/@me';
    $headers = ['Authorization: Bearer ' . $accessToken];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $userData = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // Redirect to the dashboard
    header('Location: dashboard.php');
    exit;
}

// Check if the user is redirected from Discord OAuth
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Exchange code for access token
    $tokenUrl = $discordApiUrl . 'oauth2/token';
    $postFields = [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri,
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $tokenData = json_decode(curl_exec($ch), true);
    curl_close($ch);

    // Store access token in session
    $_SESSION['discord_access_token'] = $tokenData['access_token'];

    // Redirect to the dashboard
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Discord OAuth Login</title>
</head>
<body>
    <a href="https://discord.com/api/oauth2/authorize?client_id=YOUR_CLIENT_ID&response_type=code&redirect_uri=https%3A%2F%2Fmanage.trinow.zya.me%2F&scope=identify+email+connections+guilds+guilds.join+messages.read+activities.write+activities.read+guilds.members.read+applications.commands+role_connections.write+webhook.incoming+applications.commands.permissions.update">Login with Discord</a>
</body>
</html>
