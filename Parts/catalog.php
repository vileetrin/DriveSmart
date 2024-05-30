<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Каталог автомобілів</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once 'DBConnection.php';

    $database = new DBConnection();
    
    $cars = $database->fetchAllCars();

    if (!isset($_SESSION['car_items'])) {
        $_SESSION['car_items'] = array();
    }
    if (!isset($_SESSION['counter'])) {
        $_SESSION['counter'] = 0;
    }
    ?>
   <div class="body">
        <div class="mainheading">Наші автомобілі</div>
            <form method="POST">
                <div class="filters">
                    <div class="filters__elem">
                        <div class="h2">Ціна</div>
                        <div class="filter__price">
                            <input class="filter__price__input" type="number" name="min_price" value="0"> –
                            <input class="filter__price__input" type="number" name="max_price" value="440000">
                        </div>
                    </div>
                    <div class="filters__elem">
                        <div class="h2">Марка</div>
                        <select class="dropdown" name="brand">
                            <option value="">Виберіть марку</option>
                            <option value="BMW">BMW</option>
                            <option value="Toyota">Toyota</option>
                            <option value="Tesla">Tesla</option>
                            <option value="Mercedes">Mercedes</option>
                            <option value="Honda">Honda</option>
                        </select>
                    </div>
                    <div class="filters__elem">
                        <div class="h2">Категорія</div>
                        <select class="dropdown" name="category">
                            <option value="">Виберіть категорію</option>
                            <option value="SUV">SUV</option>
                            <option value="Sedan">Sedan</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Van">Van</option>
                            <option value="E-class">E-class</option>
                        </select>
                    </div>
                    <div class="filters__elem">
                        <button type="submit" name="filter">Застосувати фільтри</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="blue-line"></div>
        <div class="body__content">
            <div class="content__body__elem">
                <?php
                function filterCars($cars, $minPrice, $maxPrice, $brand, $category) {
                    return array_filter($cars, function ($car) use ($minPrice, $maxPrice, $brand, $category) {
                        return ($car['price_per_hour'] >= $minPrice && $car['price_per_hour'] <= $maxPrice) &&
                               ($brand === "" || $car['make'] === $brand) &&
                               ($category === "" || $car['category'] === $category);
                    });
                }

                $minPrice = isset($_POST['min_price']) ? intval($_POST['min_price']) : 0;
                $maxPrice = isset($_POST['max_price']) ? intval($_POST['max_price']) : 440000;
                $brand = isset($_POST['brand']) ? $_POST['brand'] : "";
                $category = isset($_POST['category']) ? $_POST['category'] : "";

                if (isset($_POST['filter'])) {
                    $cars = filterCars($cars, $minPrice, $maxPrice, $brand, $category);
                }

                foreach ($cars as $car) {
                    if (isset($_POST[strval($car['car_id']) . "add"])) {
                        if (isset($_SESSION['car_items']) === true) {
                            $exists = false;
                            foreach ($_SESSION['car_items'] as $item) {
                                if ($item['car_id'] == $car['car_id']) {
                                    $exists = true;
                                    break;
                                }
                            }
                            if (!$exists) {
                                $_SESSION['car_items'][] = $car;
                                $_SESSION['counter'] = $_SESSION['counter'] + 1;
                            }
                        }
                    }
                ?>
                     <div class="body__elem">
                        <div class="elem__img">
                            <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['model']; ?>">
                        </div>
                        <div class="elem__text">
                            <h3><?php echo $car['model']; ?></h3>
                            <div class="car-details-container">
                                <div class="car-details">
                                    <div class="car-detail-item">
                                        <span>Категорія:</span>
                                        <span><?php echo $car['category']; ?></span>
                                    </div>
                                    <div class="car-detail-item">
                                        <span>Колір:</span>
                                        <span><?php echo $car['color']; ?></span>
                                    </div>
                                    <div class="car-detail-item">
                                        <span>Ціна за годину:</span>
                                        <span><?php echo $car['price_per_hour']; ?> грн</span>
                                    </div>
                                    <div class="car-detail-item">
                                        <span>Тип:</span>
                                        <span><?php echo $car['make']; ?></span>
                                    </div>
                                    <div class="car-detail-item">
                                        <span>Марка і модель:</span>
                                        <span><?php echo $car['make']; ?> <?php echo $car['model']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="elem__button">
                            <form method="POST">
                                <button type="submit" class="elem__button__elements" name=<?php echo ($car['price_per_hour'] > 0) ? strval($car['car_id']) . "add" : "nothing" ?>>
                                    <?php echo ($car['price_per_hour'] > 0) ? "Орендувати" : "Недоступно для оренди" ?>
                                </button>
                            </form>
                            <form method="POST">
                                <button type="submit" class="heart-button" name=<?php echo ($car['price_per_hour'] > 0) ? strval($car['car_id']) . "add" : "nothing" ?>>
                                <i class="fa-regular fa-heart"></i>
                                </button>
                            </form>
                        </div>
                        
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const heartButtons = document.querySelectorAll('.heart-button');

            heartButtons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    this.classList.toggle('active');
                    this.querySelector('i').classList.toggle('fas');
                    this.querySelector('i').classList.toggle('far');

                    // Add your form submission logic here if needed
                    this.closest('form').submit();
                });
            });
        });
    </script> -->
</body>

</html>