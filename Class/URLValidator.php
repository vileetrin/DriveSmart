<?php
class URLValidator {

    // Функція для перевірки URL адреси
    public function isValidURL($url) {
        // Регулярний вираз для перевірки URL
        $pattern = "/^(https?:\/\/)?([\da-z.-]+)\.([a-z.]{2,6})([\/\w .-]*)*\/?$/";

        // Використання preg_match для перевірки відповідності рядка регулярному виразу
        return preg_match($pattern, $url);
    }
}

// Приклад використання
$urlValidator = new URLValidator();

$urls = [
    "http://example.com",
    "https://example.com",
    "example.com",
    "http://example.com/path/to/resource",
    "https://example.com/path/to/resource?query=string",
    "ftp://example.com", // Invalid
    "http://example.cars"
];

foreach ($urls as $url) {
    if ($urlValidator->isValidURL($url)) {
        echo "URL '$url' є дійсною.\n";
    } else {
        echo "URL '$url' є недійсною.\n";
    }
}
?>
