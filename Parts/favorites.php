<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['delete'])) {
    $carIdToRemove = intval($_POST['car_id']);
    if (isset($_SESSION['car_items'])) {
        foreach ($_SESSION['car_items'] as $key => $item) {
            if ($item['car_id'] === $carIdToRemove) {
                unset($_SESSION['car_items'][$key]);
                break;
            }
        }
        // Перезаписать массив, чтобы устранить пробелы в ключах
        $_SESSION['car_items'] = array_values($_SESSION['car_items']);
    }
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
            if (isset($_SESSION['car_items']) && count($_SESSION['car_items']) > 0) {
                foreach ($_SESSION['car_items'] as $car) { 
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
                    <button class="book-button">Забронювати</button>
                </div>
            </div>
            <?php 
                }
            } else {
                echo "<div class='no-cars-card'><img src='img/car.png' alt='car icon'><p>Ви не обрали жодного автомобіля.</p><a href='index.php'>Повернутися до каталогу</a></div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
