<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['discord_access_token'])) {
    header('Location: index.php'); // Redirect to the login page
    exit;
}

// Fetch user details from Discord API
$accessToken = $_SESSION['discord_access_token'];
$discordApiUrl = 'https://discord.com/api/';
$apiUrl = $discordApiUrl . 'users/@me';
$headers = ['Authorization: Bearer ' . $accessToken];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userData = json_decode(curl_exec($ch), true);
curl_close($ch);

// Display username and profile picture
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello, <?php echo $userData['username']; ?>!</h1>
    <img src="https://cdn.discordapp.com/avatars/<?php echo $userData['id']; ?>/<?php echo $userData['avatar']; ?>.png" alt="Profile Picture">
</body>
</html>
