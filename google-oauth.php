<?php
session_start();
require_once 'DBConnection.php';
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('954848789374-k3k5mfbsakvmlcgr5jagi62viou8dic5.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://drivesmart4.great-site.net/google-oauth.php');
$client->addScope('email');
$client->addScope('profile');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // Get profile info from Google
    $oauth2 = new Google_Service_Oauth2($client);
    $profile = $oauth2->userinfo->get();

    if (isset($profile->email)) {
        session_regenerate_id();
        $_SESSION['google_loggedin'] = TRUE;
        $_SESSION['google_email'] = $profile->email;
        $_SESSION['google_name'] = $profile->name;
        $_SESSION['google_picture'] = $profile->picture;

        // Save user in the database
        $db = new DBConnection();
        $pdo = $db->getPdo();
        $email = $profile->email;
        $name = $profile->name;
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $login = $profile->email;
            $role = 'user';
            $stmt = $pdo->prepare('INSERT INTO users (first_name, email, login, role) VALUES (:name, :email, :login, :role)');
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $user_id = $pdo->lastInsertId();
        } else {
            $user_id = $user['user_id'];
        }

        // Update the last_active attribute
        $updateSql = "UPDATE users SET last_active = NOW() WHERE user_id = :user_id";
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->execute();

        $_SESSION['user_id'] = $user_id;

        header('Location: accountOpen.php');
        exit;
    } else {
        exit('Could not retrieve profile information! Please try again later!');
    }
} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}
?>
