<?php
session_start();
require_once 'config.php';
require_once 'DBConnection.php';
require_once 'stripe-php-14.9.0/init.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../loginOpen.php');
    exit();
}

if (!isset($_GET['session_id'])) {
    echo "Session ID not provided!";
    exit();
}

$session_id = $_GET['session_id'];
try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status == 'paid') {
        $user_id = $_SESSION['user_id'];
        $amount = $_SESSION['amount'];
        $car_id = $_SESSION['car_id'];
        $start_date = $_SESSION['start_date'];
        $end_date = $_SESSION['end_date'];

        // Save payment details in the database
        $database = new DBConnection();
        $database->addPayment($user_id, $amount, 'completed');

        // Update reservation status to 'paid'
        $database->updateReservationStatus($user_id, $car_id, $start_date, $end_date, 'paid');

        // Clear session variables
        unset($_SESSION['amount']);
        unset($_SESSION['car_id']);
        unset($_SESSION['start_date']);
        unset($_SESSION['end_date']);

    } else {
        echo "Оплата не вдалася!";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успішна</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="selected-cars-container">
        <main>
            <div class='no-cars-card'>
            <div class="success-section">
                <h1>Оплата успішна</h1>
                <p>Дякуємо за вашу оплату! Ваша транзакція була успішною.</p>
                    <a href="../accountOpen.php">Повернутися до акаунта</a>
            </div>
        </main>
    </div>
</body>
</html>
