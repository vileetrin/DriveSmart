<?php
require_once 'DBConnection.php';

// Включення всіх помилок і попереджень для відладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function isPasswordStrong($password) {
    // Пароль має бути не менше 8 символів, містити великі та малі літери, цифри та спеціальні символи
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

$error = "";
$firstName = "";
$lastName = "";
$email = "";
$login = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $role = 'user'; // Роль за замовчуванням

    // Перевірка надійності паролю
    if (!isPasswordStrong($password)) {
        $error = "Пароль має бути не менше 8 символів, містити великі та малі літери, цифри та спеціальні символи.";
    } else {
        $db = new DBConnection();
        $pdo = $db->getPdo();

        try {
            $sql = "INSERT INTO users (first_name, last_name, email, login, password, role) VALUES (:first_name, :last_name, :email, :login, :password, :role)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();

            echo "Реєстрація успішна!";
            header('Location: ../loginOpen.php');
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }

    // Обробка видалення акаунта
    if (isset($_POST['delete_account'])) {
        $db->deleteUser($user_id);
        session_destroy();
        header('Location: loginOpen.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="./css/styles.css">
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="form-container">
            <h1>Зареєструйтесь зараз!</h1>
            <form action="" method="post">
                <div class="form-group">
                    <label for="first_name">Ім'я</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Прізвище</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Пошта</label>
                    <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="login">Логін</label>
                    <input type="text" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                    <?php if ($error): ?>
                        <p class="error-message"><?php echo $error; ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="login-button">Зареєструватись</button>
            </form>
            <div class="social-login">
                <div class="social-divider">
                    <div class="line"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
