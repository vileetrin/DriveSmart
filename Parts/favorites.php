<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'DBConnection.php';

$database = new DBConnection();
$database->getPDO()->exec("SET time_zone = '+03:00';");
$user_id = $_SESSION['user_id'];

$stmt = $database->getPDO()->prepare('SELECT cars.* FROM cars JOIN favorites ON cars.car_id = favorites.car_id WHERE favorites.user_id = :user_id');
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

function isCarBooked($car_id, $database) {
    $stmt = $database->getPDO()->prepare('SELECT COUNT(*) FROM reservations WHERE car_id = :car_id AND NOW() BETWEEN start_time AND end_time');
    $stmt->bindParam(':car_id', $car_id);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

if (isset($_POST['delete'])) {
    $car_id = intval($_POST['car_id']);

    $delete_stmt = $database->getPDO()->prepare('DELETE FROM favorites WHERE user_id = :user_id AND car_id = :car_id');
    $delete_stmt->bindParam(':user_id', $user_id);
    $delete_stmt->bindParam(':car_id', $car_id);
    $delete_stmt->execute();

    header('Location: ../favoritiesOpen.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обрані автомобілі</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="selected-cars-container">
        <h2>Обрані автомобілі</h2>
        <div class="selected-cars">
            <?php 
            if ($cars) {
                foreach ($cars as $car) { 
            ?>
            <div class="car-block">
                <div class="car-info">
                    <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
                    <div class="details">
                        <h4>Деталі автомобіля</h4>
                        <div class="info-row">
                            <span class="label"><strong>Назва:</strong></span>
                            <span class="value"><?php echo $car['model']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label"><strong>Категорія:</strong></span>
                            <span class="value"><?php echo $car['category']; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="label"><strong>Вартість за годину:</strong></span>
                            <span class="value"><?php echo $car['price_per_hour']; ?> грн</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><strong>Марка та модель:</strong></span>
                            <span class="value"><?php echo $car['make']; ?> <?php echo $car['model']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <h4>Дії</h4>
                    <form method="POST">
                        <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                        <button class="delete-button" type="submit" name="delete">Видалити</button>
                    </form>

                    <?php if (!isCarBooked($car['car_id'], $database)) { ?>
                            <form method="POST" action="bookingOpen.php">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit" class="book-button">Бронювати</button>
                            </form>
                        <?php } else { 
                            ?>
                            <button class="book-button" disabled>Недоступний</button>
                        <?php } 
                    ?>

                    <form method="GET" action="bookingOpen.php">
                        <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                        <button class="book-button" type="submit">Забронювати</button>
                    </form>

                </div>
            </div>
            <?php 
                }
            } else {
                echo "<div class='no-cars-card'><img src='img/car.png' alt='car icon'><p>Ви не обрали жодного автомобіля.</p><a href='catalogOpen.php'>Повернутися до каталогу</a></div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
