<?php
session_start();
require_once 'DBConnection.php';

// Перевірка, чи користувач авторизований
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSmart</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <section class="header-section">
        <header class="header">
            <a href="../index.php" class="logo">DriveSmart</a>
            <nav class="main-nav">
                <ul class="main-nav-list">
                    <li class="main-nav-list-item">
                        <a href="./catalogOpen.php" class="main-nav-link">Пропозиції</a>
                    </li>
                    <li class="main-nav-list-item">
                        <a href="./page-3.html" class="main-nav-link">Мапа</a>
                    </li>
                    <li class="main-nav-list-item">
                        <a href="../accountOpen.php" class="main-nav-link">Форум</a>
                    </li>
                    <li>
                        <button type="button" class="header-btn">Завантажити додаток</button>
                    </li>
                    <li class="main-nav-list-item">
                        <?php if ($isLoggedIn): ?>
                            <a href="./accountOpen.php" class="main-nav-link">
                                <img src="../img/user_icon.png" alt="User Icon" class="user-icon">
                            </a>
                        <?php else: ?>
                            <a href="./loginOpen.php" class="main-nav-link">Увійти</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </header>
    </section>
</body>
</html>
