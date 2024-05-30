<?php
session_start();
require_once 'DBConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $database = new DBConnection();
    $pdo = $database->getPDO();

    $stmt = $pdo->prepare('SELECT * FROM users WHERE login = :login');
    $stmt->bindParam(':login', $login);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Невірний логін або пароль';
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
    

</head>

<body>

    <div class="auth-container">
        <div class="form-container">
            <h1>Авторизація</h1>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="login">Введіть логін</label>
                    <input type="text" name="login" id="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Введіть пароль</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button class= "login-button" type="submit">Увійти</button>
            </form>
            <button class="registration" href="registration.php">Реєстрація</button>

            <div class="social-login">
                <div class="social-divider">
                    <div class="line"></div>
                    <span>Або увійти через</span>
                    <div class="line"></div>
                </div>
                <button class="social-btn facebook">
                    <img src="../img/entypo-social_facebook.png" class="social-icon"> Увійти за допомогою Facebook
                </button>
                <button class="social-btn google">
                    <img src="../img/flat-color-icons_google.png" class="social-icon"> Увійти за допомогою Google
                </button>
            </div>
        </div>
    </div>
</body>

</html>
