<?php
class EmailValidator {

    public function isValidEmail($email) {
        $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

        return preg_match($pattern, $email);
    }
}

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
