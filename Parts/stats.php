<?php
require_once 'DBConnection.php';
$db = new DBConnection();
$pdo = $db->getPdo();

try {
    // Fetch list of car models for the dropdown
    $stmt = $pdo->query("SELECT DISTINCT CONCAT(make, ' ', model) AS car_model FROM cars");
    $car_models = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get selected model from the form, or use a default value
    $selected_model = isset($_GET['car_model']) ? $_GET['car_model'] : $car_models[0]['car_model'];

    // Default values for the date range
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

    // Total number of registered users
    $stmt = $pdo->query("SELECT COUNT(*) AS total_users FROM users");
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

    // Number of active users in the custom period
    $stmt = $pdo->prepare("SELECT COUNT(*) AS active_users FROM users WHERE last_active BETWEEN :start_date AND :end_date");
    $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
    $active_users = $stmt->fetch(PDO::FETCH_ASSOC)['active_users'];

    // Total number of rented cars
    $stmt = $pdo->query("SELECT COUNT(DISTINCT car_id) AS total_rented_cars FROM reservations");
    $total_rented_cars = $stmt->fetch(PDO::FETCH_ASSOC)['total_rented_cars'];

    // Total number of cars in the system
    $stmt = $pdo->query("SELECT COUNT(*) AS total_cars FROM cars");
    $total_cars = $stmt->fetch(PDO::FETCH_ASSOC)['total_cars'];

    // Average usage time of the selected car model
    $stmt = $pdo->prepare("SELECT AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) AS avg_usage_time FROM reservations 
                           JOIN cars ON reservations.car_id = cars.car_id 
                           WHERE CONCAT(cars.make, ' ', cars.model) = :model");
    $stmt->execute(['model' => $selected_model]);
    $avg_usage_time = $stmt->fetch(PDO::FETCH_ASSOC)['avg_usage_time'];

    // Most popular car make and model
    $stmt = $pdo->query("SELECT CONCAT(cars.make, ' ', cars.model) AS popular_car, COUNT(*) AS count 
                         FROM reservations 
                         JOIN cars ON reservations.car_id = cars.car_id 
                         GROUP BY popular_car 
                         ORDER BY count DESC 
                         LIMIT 1");
    $popular_car = $stmt->fetch(PDO::FETCH_ASSOC)['popular_car'];

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика - DriveSmart</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="statistics-container">
        <h1>Статистика</h1>
        <div class="section">
            <h2>Користувачі</h2>
            <div class="stat-item">
                <span class="label">Кількість зареєстрованих користувачів:</span>
                <span class="value"><?= $total_users ?></span>
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-item">
                <span class="label">Кількість активних користувачів за період:</span>
                <form class = "forma" method="get" action="">
                    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($startDate) ?>">
                    <label for="end_date">-</label>
                    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($endDate) ?>">
                    <button type="submit">Показати</button>
                </form>
                <span class="value"><?= $active_users ?></span>
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-item">
                <span class="label">Загальна кількість орендованих автомобілів користувачами:</span>
                <span class="value"><?= $total_rented_cars ?></span>
                <i class="fas fa-car"></i>
            </div>
        </div>
        <div class="section">
            <h2>Автомобілів</h2>
            <div class="stat-item">
                <span class="label">Кількість автомобілів у системі:</span>
                <span class="value"><?= $total_cars ?></span>
                <i class="fas fa-car-side"></i>
            </div>
            <div class="stat-item">
                <span class="label">Середній час використання автомобіля:</span>
                <form class = "forma" method="get" action="">
                    <select id="car_model" name="car_model">
                        <?php foreach ($car_models as $car_model): ?>
                            <option value="<?= htmlspecialchars($car_model['car_model']) ?>" <?= $car_model['car_model'] == $selected_model ? 'selected' : '' ?>>
                                <?= htmlspecialchars($car_model['car_model']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Показати</button>
                </form>
                <span class="value"><?= round($avg_usage_time, 2) ?> хв</span>
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-item">
                <span class="label">Найпопулярніша марка та модель:</span>
                <span class="value"><?= $popular_car ?></span>
            </div>
        </div>
    </div>
</body>
</html>
