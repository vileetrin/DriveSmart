<?php
session_start();
require_once 'DBConnection.php';

// Enable all errors and warnings for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in through Facebook
$facebook_loggedin = isset($_SESSION['facebook_loggedin']) ? $_SESSION['facebook_loggedin'] : false;
$facebook_email = isset($_SESSION['facebook_email']) ? $_SESSION['facebook_email'] : '';
$facebook_name = isset($_SESSION['facebook_name']) ? $_SESSION['facebook_name'] : '';
$facebook_picture = isset($_SESSION['facebook_picture']) ? $_SESSION['facebook_picture'] : '';

// Check if the user is logged in through Google
$google_loggedin = isset($_SESSION['google_loggedin']) ? $_SESSION['google_loggedin'] : false;
$google_email = isset($_SESSION['google_email']) ? $_SESSION['google_email'] : '';
$google_name = isset($_SESSION['google_name']) ? $_SESSION['google_name'] : '';
$google_picture = isset($_SESSION['google_picture']) ? $_SESSION['google_picture'] : '';

// Check if the user is logged in internally
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$db = new DBConnection();
$pdo = $db->getPdo();

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: loginOpen.php');
    exit();
}

// Handle account deletion
if (isset($_POST['delete_account'])) {
    $db->deleteUser($user_id);
    session_destroy();
    header('Location: loginOpen.php');
    exit();
}

$user = null;
if ($user_id) {
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
} elseif ($facebook_loggedin || $google_loggedin) {
    $user = [
        'first_name' => $facebook_loggedin ? $facebook_name : $google_name,
        'last_name' => '',
        'email' => $facebook_loggedin ? $facebook_email : $google_email,
        'login' => '',
        'image' => $facebook_loggedin ? $facebook_picture : $google_picture,
    ];
}

// Check for 'uploads' directory
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true); // Create directory with write permissions
}

// Handle new image upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_id) {
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
                // Update user profile
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

            // Update user profile
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

// Profile image path
$profileImage = (!empty($user['image']) && $user['image'] !== NULL) ? $user['image'] : '../img/default-user-profile.jpg';
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
                        <div class="line"></div>
                    </div>
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
