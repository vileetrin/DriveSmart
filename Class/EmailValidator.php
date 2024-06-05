<?php
class EmailValidator {

    // Функція для перевірки e-mail адреси
    public function isValidEmail($email) {
        // Регулярний вираз для перевірки e-mail
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        // Використання preg_match для перевірки відповідності рядка регулярному виразу
        return preg_match($pattern, $email);
    }
}

// Приклад використання
$emailValidator = new EmailValidator();

$emails = [
    "valid.email@example.com",
    "invalid-email",
    "another.valid.email@domain.org",
    "wrong@domain,com",
    "valid.email+alias@example.co.uk"
];

foreach ($emails as $email) {
    if ($emailValidator->isValidEmail($email)) {
        echo "E-mail адреса '$email' є дійсною.\n";
    } else {
        echo "E-mail адреса '$email' є недійсною.\n";
    }
}
?>
