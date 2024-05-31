<?php
session_start();
require_once 'DBConnection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../loginOpen.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$db = new DBConnection();
$pdo = $db->getPdo();

// Включення всіх помилок і попереджень для відладки
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Обробка виходу з акаунта
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: loginOpen.php');
    exit();
}

// Обробка видалення акаунта
if (isset($_POST['delete_account'])) {
    $db->deleteUser($user_id);
    session_destroy();
    header('Location: loginOpen.php');
    exit();
}

try {
    $sql = "SELECT first_name, last_name, email, login, image FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// Перевірка існування директорії 'uploads'
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true); // Створення директорії з правами на запис
}

// Завантаження нового зображення
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image'])) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            try {
                $sql = "UPDATE users SET image = :image WHERE user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':image', $uploadFile);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();
                // Оновлення профілю користувача
                $user['image'] = $uploadFile;
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                exit();
            }
        } else {
            echo "Failed to upload file.";
        }
    }

    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['login'])) {
        try {
            $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, login = :login WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':first_name', $_POST['first_name']);
            $stmt->bindParam(':last_name', $_POST['last_name']);
            $stmt->bindParam(':email', $_POST['email']);
            $stmt->bindParam(':login', $_POST['login']);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            // Оновлення профілю користувача
            $user['first_name'] = $_POST['first_name'];
            $user['last_name'] = $_POST['last_name'];
            $user['email'] = $_POST['email'];
            $user['login'] = $_POST['login'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}

// Шлях до зображення профілю
$profileImage = (!empty($user['image']) && $user['image'] !== NULL) ? $user['image'] : '../img/default-profile-picture.jpg';
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кабінет користувача</title>
    <link rel="stylesheet" href="./css/styles.css">
    <script>
        function toggleEditMode() {
            const editMode = document.getElementById('edit-mode');
            const displayMode = document.getElementById('display-mode');
            const isEditMode = editMode.style.display === 'block';
            editMode.style.display = isEditMode ? 'none' : 'block';
            displayMode.style.display = isEditMode ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <div class="cabinet-container">
        <h2>Особиста інформація</h2>
        <div class="fav-cars-container">
            <form action="../favoritiesOpen.php">
                <button class="fav-cars-btn">Обрані машини</button>
            </form>
        </div>
        <div class="profile-info-container">
            <div class="profile-card">
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Picture">
                <div id="display-mode">
                    <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                    <p><?php echo htmlspecialchars($user['first_name']); ?></p>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><?php echo htmlspecialchars($user['login']); ?></p>
                    <button class="profile-card-button" onclick="toggleEditMode()">Змінити</button>
                       <div class="social-divider">
                    <div class="line"></div></div>
                    <div class="logout">
                        <form action="" method="post">
                            <button class="logout-button" type="submit" name="logout">Вийти</button>
                        </form>
                        <form action="" method="post">
                            <button class="logout-button" type="submit" name="delete_account" onclick="return confirm('Ви впевнені, що хочете видалити акаунт?')">Видалити акаунт</button>
                        </form>
                    </div>
                </div>
                <div id="edit-mode" style="display: none;">
                    <form action="accountOpen.php" method="post" enctype="multipart/form-data">
                        <label for="image">Змінити зображення:</label>
                        <input type="file" name="image" id="image" class="file-input">
                        <button class="profile-card-button" type="submit">Завантажити</button>
                    </form>
                    <form action="accountOpen.php" method="post">
                        <label for="first_name">Ім'я:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                        <label for="last_name">Прізвище:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                        <label for="login">Login:</label>
                        <input type="text" name="login" id="login" value="<?php echo htmlspecialchars($user['login']); ?>">
                        <button class="profile-card-button" type="submit">Зберегти</button>
                    </form>
                    <button class="profile-card-button" onclick="toggleEditMode()">Скасувати</button>
                </div>
            </div>
            <div class="info-section">
                <div class="info-section-details">
                    <p>Копія водійського посвідчення:</p>
                    <button>Завантажити</button>
                </div>
                <div class="info-section-details">
                    <p>Копія паспорту:</p>
                    <button>Завантажити</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>