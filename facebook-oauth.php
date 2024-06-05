<?php
session_start();
require_once 'DBConnection.php';

// Facebook OAuth Configuration
$facebook_oauth_app_id = '1372081633568837';
$facebook_oauth_app_secret = '0e2b6405af8951cccf2312f5b71055b2';
$facebook_oauth_redirect_uri = 'https://drivesmart4.great-site.net/facebook-oauth.php';
$facebook_oauth_version = 'v18.0';

if (isset($_GET['code']) && !empty($_GET['code'])) {
    $params = [
        'client_id' => $facebook_oauth_app_id,
        'client_secret' => $facebook_oauth_app_secret,
        'redirect_uri' => $facebook_oauth_redirect_uri,
        'code' => $_GET['code']
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    if (isset($response['access_token']) && !empty($response['access_token'])) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $facebook_oauth_version . '/me?fields=name,email,picture');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);

        if (isset($profile['email'])) {
            session_regenerate_id();
            $_SESSION['facebook_loggedin'] = TRUE;
            $_SESSION['facebook_email'] = $profile['email'];
            $_SESSION['facebook_name'] = $profile['name'];
            $_SESSION['facebook_picture'] = $profile['picture']['data']['url'];

            // Save user in the database
            $db = new DBConnection();
            $pdo = $db->getPdo();
            $email = $profile['email'];
            $name = $profile['name'];
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $login = $profile['email'];
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
        exit('Invalid access token! Please try again later!');
    }
} else {
    $params = [
        'client_id' => $facebook_oauth_app_id,
        'redirect_uri' => $facebook_oauth_redirect_uri,
        'response_type' => 'code',
        'scope' => 'email'
    ];
    header('Location: https://www.facebook.com/dialog/oauth?' . http_build_query($params));
    exit;
}

?>
