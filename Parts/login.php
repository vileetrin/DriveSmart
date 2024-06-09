<?php
session_start();
require_once 'DBConnection.php';

$db = new DBConnection();
$pdo = $db->getPdo();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = $_POST['login'];
    $mypassword = $_POST['password'];

    try {
        $sql = "SELECT * FROM users WHERE login = :login AND password = :password";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $myusername);
        $stmt->bindParam(':password', $mypassword);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['blocked']) {
                $error = "Your account is blocked. Please contact support.";
            } else {
                // Update the last_active attribute
                $updateSql = "UPDATE users SET last_active = NOW() WHERE user_id = :user_id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->bindParam(':user_id', $user['user_id']);
                $updateStmt->execute();

                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_role'] = $user['role']; 
                header("Location: ../accountOpen.php");
                exit();
            }
        } else {
            $error = "Your Login Name or Password is invalid";
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація</title>
    <link rel="stylesheet" href="./css/styles.css">
    <script>
        function validateForm() {
            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;
            let error = '';

            if (login.trim() === '') {
                error = 'Логін не може бути порожнім';
            } else if (password.trim() === '') {
                error = 'Пароль не може бути порожнім';
            } else if (password.length < 6) {
                error = 'Пароль повинен містити щонайменше 6 символів';
            }

            if (error) {
                document.getElementById('error').textContent = error;
                return false;
            }
            return true;
        }
        
        window.fbAsyncInit = function() {
            FB.init({
            appId      : '1372081633568837',
            cookie     : true,
            xfbml      : true,
            version    : 'v18.0'
            });
            
            FB.AppEvents.logPageView();   
            
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
</head>
<body>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/uk_UA/sdk.js#xfbml=1&version=v20.0&appId=1372081633568837" nonce="wZPFmw0m"></script>
    <div class="auth-container">
        <div class="form-container">
            <h1>Авторизація</h1>
            <?php if (isset($error) && !empty($error)): ?>
                <div id="error" class="error"><?php echo $error; ?></div>
            <?php else: ?>
                <div id="error" class="error"></div>
            <?php endif; ?>
            <form action="" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="login">Введіть логін</label>
                    <input type="text" name="login" id="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Введіть пароль</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button class="login-button" type="submit">Увійти</button>
            </form>
            <a class="registration" href="/registrationOpen.php">Реєстрація</a>
            <div class="social-login">
                <div class="social-divider">
                    <div class="line"></div>
                    <span>Або увійти через</span>
                    <div class="line"></div>
                </div>
                <button class="social-btn facebook" onclick="window.location.href='../facebook-oauth.php'">
                    <img src="../img/entypo-social_facebook.png" alt="Facebook Icon" class="social-icon"> Увійти за допомогою Facebook
                </button>
                <button class="social-btn google">
                    <img src="../img/flat-color-icons_google.png" alt="Google Icon" class="social-icon"> Увійти за допомогою Google
                </button>
            </div>
        </div>
    </div>
</body>
</html>
