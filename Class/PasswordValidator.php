<?php
class PasswordValidator {

    // Функція для перевірки надійності паролю
    public function isStrongPassword($password) {
        // Регулярний вираз для перевірки надійності паролю
        $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

        // Використання preg_match для перевірки відповідності рядка регулярному виразу
        return preg_match($pattern, $password);
    }
}

// Приклад використання
$passwordValidator = new PasswordValidator();

$passwords = [
    "Password123!",
    "weakpassword",
    "P@ssw0rd",
    "P@ss123",
    "StrongPass!1",
    "Short1!",
    "N0specialchar"
];

foreach ($passwords as $password) {
    if ($passwordValidator->isStrongPassword($password)) {
        echo "Пароль '$password' є надійним.\n";
    } else {
        echo "Пароль '$password' є ненадійним.\n";
    }
}
?>
