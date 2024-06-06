<?php
session_start();
require_once 'DBConnection.php';
require_once 'config.php';
require_once 'stripe-php-14.9.0/init.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

if (!isset($_SESSION['user_id'])) {
    header('Location: ../loginOpen.php');
    exit();
}

$amount = isset($_SESSION['amount']) ? $_SESSION['amount'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $car_id = $_SESSION['car_id'];
    $start_date = $_SESSION['start_date'];
    $end_date = $_SESSION['end_date'];

    // Create a new Stripe Checkout Session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'uah',
                'product_data' => [
                    'name' => 'Car Rental Payment',
                ],
                'unit_amount' => $amount * 100, // Сума в копійках
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://drivesmart4.great-site.net/successOpen.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://drivesmart4.great-site.net/payment.php',
    ]);

    header('Location: ' . $session->url);
    exit();
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <div class="container">
        <main>
            <div class="payment-section">
                <h1>Оплата</h1>
                <div class="line-container"></div>
                <div class="payment-details">
                    <span class="label">Сума:</span>
                    <span class="value"><?php echo number_format($amount, 2); ?> грн</span>
                </div>
                <div class="payment-details">
                    <span class="label">Комісія:</span>
                    <span class="value">0 грн</span>
                </div>
                <div class="line-container"></div>
                <div class="payment-details">
                    <span class="label">Сума до сплати:</span>
                    <span class="value" style="color: #E68C3A;"><?php echo number_format($amount, 2); ?> грн</span>
                </div>
                <div class="line-container"><h3>Оплата через Stripe</h3></div>
                <form class = "payment-form" action="" method="post">
                    <button type="submit" class="stripe-button">Оплатити</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
