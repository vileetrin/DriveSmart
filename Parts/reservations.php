<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'DBConnection.php';

$database = new DBConnection();
$pdo = $database->getPdo();
$user_id = $_SESSION['user_id'];

$reservations = $database->fetchUserReservations($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['car_id'])) {
        $car_id = intval($_POST['car_id']);
        $user_id = $_SESSION['user_id'];

        $car = $database->fetchCarById($car_id);
        $user = $database->fetchUserById($user_id);

        if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];

            // Calculate the total amount
            $price_per_hour = $car['price_per_hour'];
            $start_time = strtotime($start_date);
            $end_time = strtotime($end_date);
            $duration_in_hours = ($end_time - $start_time) / 3600;
            $amount = $price_per_hour * $duration_in_hours;

            $database->getReservationById($user_id, $car_id, $start_date, $end_date, $amount);

            // Redirect to payment page with booking details
            $_SESSION['amount'] = $amount;
            $_SESSION['car_id'] = $car_id;
            $_SESSION['start_date'] = $start_date;
            $_SESSION['end_date'] = $end_date;

            header('Location: ../paymentPage.php');
            exit();
        }
    }

    if (isset($_POST['cancel'])) {
        $reservation_id = intval($_POST['reservation_id']);
        $stmt = $pdo->prepare('DELETE FROM reservations WHERE reservation_id = :reservation_id');
        $stmt->bindParam(':reservation_id', $reservation_id);
        $stmt->execute();
        header('Location: ../reservationsOpen.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мої бронювання</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="selected-cars-container">
        <h2>Мої бронювання</h2>
        <div class="selected-cars">
            <?php if ($reservations): ?>
                <?php foreach ($reservations as $reservation): ?>
                    <div class="car-block">
                        <div class="car-info">
                            <img src="<?php echo $reservation['image']; ?>" alt="<?php echo $reservation['model']; ?>">
                            <div class="details">
                                <h4>Деталі автомобіля</h4>
                                <div class="info-row">
                                    <span class="label"><strong>Назва:</strong></span>
                                    <span class="value"><?php echo $reservation['make'] . ' ' . $reservation['model']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><strong>Час початку:</strong></span>
                                    <span class="value"><?php echo $reservation['start_time']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><strong>Час закінчення:</strong></span>
                                    <span class="value"><?php echo $reservation['end_time']; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><strong>Ціна:</strong></span>
                                    <span class="value"><?php echo $reservation['amount']; ?> грн</span>
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <h4>Дії</h4>
                            <?php 
                                $start_time = new DateTime($reservation['start_time']);
                                $now = new DateTime();
                                $interval = $now->diff($start_time);
                                $days_to_start = $interval->format('%r%a');
                            ?>
                            <?php if ($days_to_start >= 1): ?>
                                <form method="POST">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                    <button class="delete-button" type="submit" name="cancel">Скасувати оренду</button>
                                </form>
                            <?php else: ?>
                                <button class="delete-button" disabled>Скасування недоступне</button>
                            <?php endif; ?>

                            <?php if ($reservation['status'] !== 'paid'): ?>
                                <form method="POST" action="../paymentOpen.php">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['reservation_id']; ?>">
                                    <input type="hidden" name="car_id" value="<?php echo $reservation['car_id']; ?>">
                                    <input type="hidden" name="start_date" value="<?php echo $reservation['start_time']; ?>">
                                    <input type="hidden" name="end_date" value="<?php echo $reservation['end_time']; ?>">
                                    <button type="submit" class="book-button">Оплатити</button>
                                </form>
                            <?php else: ?>
                                <button class="book-button" disabled>Сплачено</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class='no-cars-card'><img src='img/car.png' alt='car icon'><p>У вас немає жодного бронювання.</p><a href='catalogOpen.php'>Повернутися до каталогу</a></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
