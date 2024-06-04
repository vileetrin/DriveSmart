<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('UTC');

require_once 'DBConnection.php';

$database = new DBConnection();
$cars = $database->fetchAllCars();
$database->getPDO()->exec("SET time_zone = '+03:00';");

function isCarBooked($car_id, $database) {
    $stmt = $database->getPDO()->prepare('SELECT COUNT(*) FROM reservations WHERE car_id = :car_id AND NOW() BETWEEN start_time AND end_time');
    $stmt->bindParam(':car_id', $car_id);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['favorite']) && ((isset($_SESSION['user_id']))) )
     {
        $car_id = intval($_POST['car_id']);
        $user_id = $_SESSION['user_id'];

        // Check if the car is already in the favorites list
        $stmt = $database->getPDO()->prepare('SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND car_id = :car_id');
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':car_id', $car_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count == 0) {
            // Add the car to the favorites list
            $stmt = $database->getPDO()->prepare('INSERT INTO favorites (user_id, car_id) VALUES (:user_id, :car_id)');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();
        } else {
            // Remove the car from the favorites list
            $stmt = $database->getPDO()->prepare('DELETE FROM favorites WHERE user_id = :user_id AND car_id = :car_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();
        }

        // Redirect to the same page to refresh the content
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

function filterCars($cars, $minPrice, $maxPrice, $brand, $category, $database) {
    return array_filter($cars, function ($car) use ($minPrice, $maxPrice, $brand, $category, $database) {
        return ($car['price_per_hour'] >= $minPrice && $car['price_per_hour'] <= $maxPrice) &&
               ($brand === "" || $car['make'] === $brand) &&
               ($category === "" || $car['category'] === $category) &&
               !isCarBooked($car['car_id'], $database);
    });
}

$minPrice = isset($_POST['min_price']) ? intval($_POST['min_price']) : 0;
$maxPrice = isset($_POST['max_price']) ? intval($_POST['max_price']) : 440000;
$brand = isset($_POST['brand']) ? $_POST['brand'] : "";
$category = isset($_POST['category']) ? $_POST['category'] : "";

if (isset($_POST['filter'])) {
    $cars = filterCars($cars, $minPrice, $maxPrice, $brand, $category, $database);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог автомобілів</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="body">
        <div class="mainheading">Наші автомобілі</div>
        <form method="POST">
            <div class="filters">
                <div class="filters__elem">
                    <div class="h2">Ціна</div>
                    <div class="filter__price">
                        <input class="filter__price__input" type="number" name="min_price" value="<?php echo htmlspecialchars($minPrice); ?>"> –
                        <input class="filter__price__input" type="number" name="max_price" value="<?php echo htmlspecialchars($maxPrice); ?>">
                    </div>
                </div>
                <div class="filters__elem">
                    <div class="h2">Марка</div>
                    <select class="dropdown" name="brand">
                        <option value="">Виберіть марку</option>
                        <option value="BMW" <?php if ($brand === 'BMW') echo 'selected'; ?>>BMW</option>
                        <option value="Toyota" <?php if ($brand === 'Toyota') echo 'selected'; ?>>Toyota</option>
                        <option value="Tesla" <?php if ($brand === 'Tesla') echo 'selected'; ?>>Tesla</option>
                        <option value="Mercedes" <?php if ($brand === 'Mercedes') echo 'selected'; ?>>Mercedes</option>
                        <option value="Honda" <?php if ($brand === 'Honda') echo 'selected'; ?>>Honda</option>
                    </select>
                </div>
                <div class="filters__elem">
                    <div class="h2">Категорія</div>
                    <select class="dropdown" name="category">
                        <option value="">Виберіть категорію</option>
                        <option value="SUV" <?php if ($category === 'SUV') echo 'selected'; ?>>SUV</option>
                        <option value="Sedan" <?php if ($category === 'Sedan') echo 'selected'; ?>>Sedan</option>
                        <option value="Coupe" <?php if ($category === 'Coupe') echo 'selected'; ?>>Coupe</option>
                        <option value="Van" <?php if ($category === 'Van') echo 'selected'; ?>>Van</option>
                        <option value="E-class" <?php if ($category === 'E-class') echo 'selected'; ?>>E-class</option>
                    </select>
                </div>
                <div class="filters__elem">
                    <button type="submit" name="filter">Застосувати фільтри</button>
                </div>
            </div>
        </form>
    </div>
    <div class="blue-line"></div>
    <div class="body__content">
        <div class="content__body__elem">
            <?php
            foreach ($cars as $car) {
                // Check if the car is in the favorites list
                $stmt = $database->getPDO()->prepare('SELECT COUNT(*) FROM favorites WHERE user_id = :user_id AND car_id = :car_id');
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                $stmt->bindParam(':car_id', $car['car_id']);
                $stmt->execute();
                $isFavorite = $stmt->fetchColumn() > 0;
            ?>
                <div class="body__elem">
                    <div class="elem__img">
                        <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
                    </div>
                    <div class="elem__text">
                        <h3><?php echo htmlspecialchars($car['model']); ?></h3>
                        <div class="car-details-container">
                            <div class="car-details">
                                <div class="car-detail-item">
                                    <span>Категорія:</span>
                                    <span><?php echo htmlspecialchars($car['category']); ?></span>
                                </div>
                                <div class="car-detail-item">
                                    <span>Колір:</span>
                                    <span><?php echo htmlspecialchars($car['color']); ?></span>
                                </div>
                                <div class="car-detail-item">
                                    <span>Ціна за годину:</span>
                                    <span><?php echo htmlspecialchars($car['price_per_hour']); ?> грн</span>
                                </div>
                                <div class="car-detail-item">
                                    <span>Тип:</span>
                                    <span><?php echo htmlspecialchars($car['make']); ?></span>
                                </div>
                                <div class="car-detail-item">
                                    <span>Марка і модель:</span>
                                    <span><?php echo htmlspecialchars($car['make']); ?> <?php echo htmlspecialchars($car['model']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="elem__button">
                        <?php if (!isCarBooked($car['car_id'], $database)) { ?>
                            <form method="POST" action="bookingOpen.php">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" class="elem__button__elements">Бронювати</button>
                            </form>
                        <?php } else { ?>
                            <button class="elem__button__elements" disabled>Недоступний</button>
                        <?php } ?>
                        <form method="POST">
                            <button type="submit" class="heart-button" name="favorite">
                                <i class="fa-<?php echo $isFavorite ? 'solid' : 'regular'; ?> fa-heart"></i>
                            </button>
                            <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</body>
</html>
