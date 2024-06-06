<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'DBConnection.php';

$database = new DBConnection();
$pdo = $database->getPdo();

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'user_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$users = $database->fetchAllUsers($sort_by, $order);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imagePath = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imagePath = $uploadFile;
        } else {
            echo "Failed to upload image.";
        }
    }

    if (isset($_POST['block'])) {
        $user_id = $_POST['user_id'];
        $user = $pdo->prepare("SELECT blocked FROM users WHERE user_id = :user_id");
        $user->execute(['user_id' => $user_id]);
        $result = $user->fetch(PDO::FETCH_ASSOC);

        if ($result['blocked']) {
            $database->unblockUser($user_id);
        } else {
            $database->blockUser($user_id);
        }
    } elseif (isset($_POST['delete'])) {
        $database->deleteUser($_POST['user_id']);
    } elseif (isset($_POST['update'])) {
        $imagePath = !empty($imagePath) ? $imagePath : $_POST['existing_image'];
        $database->updateUser($_POST['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['login'], $_POST['role'], $imagePath);
    } elseif (isset($_POST['add'])) {
        $database->addUser($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['login'], $_POST['password'], $_POST['role'], $imagePath);
    }
    header('Location: ../customerCRUDOpen.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління користувачами</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="selected-cars-container">
        <div class="search-container">
            <h2>Управління <span style="color: #E68C3A;">користувачами</span></h2>
            <div class="search">
                <input type="text" placeholder="Пошук..." id="searchInput" onkeyup="searchUser()" />
                <button onclick="searchUser()"><i class="fas fa-search"></i></button>
            </div>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Сортувати</button>
                <div id="dropdown-content" class="dropdown-content">
                    <a href="?sort_by=first_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Ім'я</a>
                    <a href="?sort_by=last_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Прізвище</a>
                    <a href="?sort_by=email&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Електронна пошта</a>
                    <a href="?sort_by=login&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Логін</a>
                    <a href="?sort_by=role&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Роль</a>
                </div>
            </div>
            <button class="addbtn" onclick="toggleAddUser()">+</button>
        </div>
        <div class="car-block user-card hidden" id="add-user-card">
            <form method="post" enctype="multipart/form-data" class="car-info">
                <img src="../img/default-user-profile.jpg" alt="User Avatar">
                <div class="details">
                    <h4>Додати користувача</h4>
                    <div class="info-row">
                        <span class="label">Ім'я:</span>
                        <input type="text" name="first_name" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Прізвище:</span>
                        <input type="text" name="last_name" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Електронна пошта:</span>
                        <input type="text" name="email" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Логін:</span>
                        <input type="text" name="login" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Пароль:</span>
                        <input type="password" name="password" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Роль:</span>
                        <input type="text" name="role" class="value" value="">
                    </div>
                    <div class="info-row">
                        <span class="label">Зображення:</span>
                        <input type="file" name="image" class="value">
                    </div>
                </div>
                <div class="actions">
                    <h4>Дії</h4>
                    <button type="submit" name="add" class="book-button">Додати</button>
                    <button type="reset" class="delete-button" onclick="toggleAddUser()">Скасувати</button>
                </div>
            </form>
        </div>
        <div class="selected-cars" id="user-list">
            <?php foreach ($users as $user): ?>
                <div class="car-block user-card">
                    <form method="post" enctype="multipart/form-data" class="car-info">
                        <img src="<?php echo htmlspecialchars($user['image'] ?? '../img/default-user-profile.jpg'); ?>" alt="User Avatar">
                        <div class="details">
                            <h4>Особисті дані</h4>
                            <div class="info-row">
                                <span class="label">Ім'я:</span>
                                <input type="text" name="first_name" class="value" value="<?php echo htmlspecialchars($user['first_name']); ?>">
                            </div>
                            <div class="info-row">
                                <span class="label">Прізвище:</span>
                                <input type="text" name="last_name" class="value" value="<?php echo htmlspecialchars($user['last_name']); ?>">
                            </div>
                            <div class="info-row">
                                <span class="label">Електронна пошта:</span>
                                <input type="text" name="email" class="value" value="<?php echo htmlspecialchars($user['email']); ?>">
                            </div>
                            <div class="info-row">
                                <span class="label">Логін:</span>
                                <input type="text" name="login" class="value" value="<?php echo htmlspecialchars($user['login']); ?>">
                            </div>
                            <div class="info-row">
                                <span class="label">Роль:</span>
                                <input type="text" name="role" class="value" value="<?php echo htmlspecialchars($user['role']); ?>">
                            </div>
                            <div class="info-row">
                                <span class="label">Зображення:</span>
                                <input type="file" name="image" class="value">
                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($user['image']); ?>">
                            </div>
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        </div>
                        <div class="actions">
                            <h4>Дії</h4>
                            <button type="submit" name="update" class="book-button">Редагувати</button>
                            <button type="submit" name="block" class="book-button"><?php echo $user['blocked'] ? 'Розблокувати' : 'Заблокувати'; ?></button>
                            <button type="submit" name="delete" class="delete-button">Видалити</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function searchUser() {
            var input, filter, cards, cardContainer, i, j, details, matched;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("user-list");
            cards = cardContainer.getElementsByClassName("user-card");
            for (i = 0; i < cards.length; i++) {
                details = cards[i].getElementsByClassName("value");
                matched = false;
                for (j = 0; j < details.length; j++) {
                    if (details[j].value.toUpperCase().indexOf(filter) > -1) {
                        matched = true;
                        break;
                    }
                }
                if (matched) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }

        function toggleDropdown() {
            document.getElementById("dropdown-content").classList.toggle("show");
        }

        function toggleAddUser() {
            var addUserCard = document.getElementById("add-user-card");
            addUserCard.classList.toggle("hidden");
            addUserCard.classList.toggle("visible");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
