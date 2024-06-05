<?php
session_start();
require_once 'DBConnection.php';

if (! isset($_SESSION['user_id'])) {
    header('Location: ../loginOpen.php');
    exit();
}

$database = new DBConnection();
$car = null;
$user = null;
$amount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['car_id'])) {
        $car_id = intval($_POST['car_id']);
        $user_id = $_SESSION['user_id'];

        // Fetch car and user details
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

            // Save the booking in the database
            $database->addReservation($user_id, $car_id, $start_date, $end_date, $amount);

            // Redirect to payment page with booking details
            $_SESSION['amount'] = $amount;
            $_SESSION['car_id'] = $car_id;
            $_SESSION['start_date'] = $start_date;
            $_SESSION['end_date'] = $end_date;

            header('Location:../paymentOpen.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бронювання Автомобіля</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="booking-container">
        <h1>Бронювання Автомобіля</h1>
        <div class="booking-content">
            <div class="car-image">
                <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['model']); ?>">
            </div>
            <div class="booking-details">
                <div class="car-inform">
                    <h2>Деталі Автомобіля</h2>
                    <div class="car-info-row">
                        <span class="label">Марка та модель :</span>
                        <span class="value"><?php echo htmlspecialchars($car['make'] . ' ' . $car['model']); ?></span>
                    </div>
                    <div class="car-info-row">
                        <span class="label">Категорія:</span>
                        <span class="value"><?php echo htmlspecialchars($car['category']); ?></span>
                    </div>
                    <div class="car-info-row">
                        <span class="label">Вартість за годину:</span>
                        <span class="value"><?php echo htmlspecialchars($car['price_per_hour']); ?> грн</span>
                    </div>
                    <div class="car-info-row">
                        <span class="label">Колір:</span>
                        <span class="value"><?php echo htmlspecialchars($car['color']); ?></span>
                    </div>
                </div>
                <hr>
                <div class="booking-form">
                    <form action="" method="post">
                        <h2>Обeріть Дату та Час</h2>
                        <label for="start_date">Дата бронювання з:</label>
                        <input type="datetime-local" id="start_date" name="start_date" required>
                        <label for="end_date">Дата бронювання по:</label>
                        <input type="datetime-local" id="end_date" name="end_date" required>
                        <hr>
                        <h2>Особиста Інформація</h2>
                        <label for="name">ПІБ:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" readonly>
                        <label for="email">Електронна пошта:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                        <button type="submit">Орендувати</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
