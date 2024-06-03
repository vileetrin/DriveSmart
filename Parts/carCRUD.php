<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'DBConnection.php';

$database = new DBConnection();
$cars = $database->fetchAllCars();

$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'car_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$cars = $database->sortAllCars($sort_by, $order);

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

    if (isset($_POST['delete'])) {
        $database->deleteCar($_POST['car_id']);
    } elseif (isset($_POST['update'])) {
        $imagePath = !empty($imagePath) ? $imagePath : $_POST['existing_image'];
        $database->updateCar($_POST['car_id'], $_POST['make'], $_POST['model'], $_POST['category'], $_POST['color'], $imagePath, $_POST['price_per_hour']);
    } elseif (isset($_POST['add'])) {
        $database->addCar($_POST['make'], $_POST['model'], $_POST['category'], $_POST['color'], $imagePath, $_POST['price_per_hour']);
    }
    header('Location: ../carCRUDOpen.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління автомобілями</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="selected-cars-container">
        <div class="search-container">
        <h2>Управління <span style="color: #E68C3A;">автомобілями</span></h2>
            <div class="search"> <input type="text" placeholder="Пошук..." id="searchInput" onkeyup="searchCar()" />
            <button onclick="searchCar()"><i class="fas fa-search"></i></button></div>
            <div class="dropdown">
                <button class="dropbtn" onclick="toggleDropdown()">Сортувати</button>
                <div id="dropdown-content" class="dropdown-content">
                    <a href="?sort_by=make&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Бренд</a>
                    <a href="?sort_by=model&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Модель</a>
                    <a href="?sort_by=category&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Категорія</a>
                    <a href="?sort_by=color&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Колір</a>
                    <a href="?sort_by=price_per_hour&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Ціна на годину</a>
                </div>
            </div>
            <button class="addbtn" onclick="toggleAddCar()">+</button>
        </div>
        <div class="car-block car-card hidden" id="add-car-card">
                <form method="post" enctype="multipart/form-data" class="car-info">
                    <img src="../img/default-car-image.jpg" alt="Car Avatar">
                    <div class="details">
                        <h4>Додати автомобіль</h4>
                        <div class="info-row">
                            <span class="label">Бренд:</span>
                            <input type="text" name="make" class="value" value="">
                        </div>
                        <div class="info-row">
                            <span class="label">Модель:</span>
                            <input type="text" name="model" class="value" value="">
                        </div>
                        <div class="info-row">
                            <span class="label">Категорія:</span>
                            <input type="text" name="category" class="value" value="">
                        </div>
                        <div class="info-row">
                            <span class="label">Колір:</span>
                            <input type="text" name="color" class="value" value="">
                        </div>
                        <div class="info-row">
                            <span class="label">Ціна на годину:</span>
                            <input type="text" name="price_per_hour" class="value" value="">
                        </div>
                        <div class="info-row">
                            <span class="label">Зображення:</span>
                            <input type="file" name="image" class="value">
                        </div>
                    </div>
                    <div class="actions">
                        <h4>Дії</h4>
                        <button type="submit" name="add" class="book-button">Додати</button>
                        <button type="reset" class="delete-button" onclick="toggleAddCar()">Скасувати</button></div></form></div>
        <div class="selected-cars" id="car-list">
            <?php foreach ($cars as $car): ?>
                <div class="car-block car-card">
                <form method="post" enctype="multipart/form-data" class="car-info">
                    <img src="<?php echo htmlspecialchars($car['image'] ?? '../img/default-car-image.jpg'); ?>" alt="Car Avatar">
                    <div class="details">
                        <h4>Деталі автомобіля</h4>
                        <div class="info-row">
                            <span class="label">Бренд:</span>
                            <input type="text" name="make" class="value" value="<?php echo htmlspecialchars($car['make']); ?>">
                        </div>
                        <div class="info-row">
                            <span class="label">Модель:</span>
                            <input type="text" name="model" class="value" value="<?php echo htmlspecialchars($car['model']); ?>">
                        </div>
                        <div class="info-row">
                            <span class="label">Категорія:</span>
                            <input type="text" name="category" class="value" value="<?php echo htmlspecialchars($car['category']); ?>">
                        </div>
                        <div class="info-row">
                            <span class="label">Колір:</span>
                            <input type="text" name="color" class="value" value="<?php echo htmlspecialchars($car['color']); ?>">
                        </div>
                        <div class="info-row">
                            <span class="label">Ціна на годину:</span>
                            <input type="text" name="price_per_hour" class="value" value="<?php echo htmlspecialchars($car['price_per_hour']); ?>">
                        </div>
                        <div class="info-row">
                            <span class="label">Зображення:</span>
                            <input type="file" name="image" class="value">
                            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($car['image']); ?>">
                        </div>
                        <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                    </div>
                    <div class="actions">
                        <h4>Дії</h4>
                        <button type="submit" name="update" class="book-button">Редагувати</button>
                        <button type="submit" name="delete" class="delete-button">Видалити</button>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function searchCar() {
            var input, filter, cards, cardContainer, i, j, details, matched;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("car-list");
            cards = cardContainer.getElementsByClassName("car-card");
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

        function toggleAddCar() {
            var addCarCard = document.getElementById("add-car-card");
            addCarCard.classList.toggle("hidden");
            addCarCard.classList.toggle("visible");
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
