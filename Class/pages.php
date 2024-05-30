<?php 
    class Page 
    {
        public $title;

        public function __construct($title) 
        {
            $this->title = $title;
        }

        public function ShowHeader()
        {
            include "Parts/header.php";
        }

        public function ShowContent()
        {
            include "Parts/mainPage.php";
        }

        public function ShowFooter()
        {
            include "Parts/footer.php";
        }
    }

    class MainPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Головна сторінка";
            require_once 'Class/cars.php';
            session_start();
        }
    }

    class BookingPage extends Page 
    {
        public $carId;
        public $car;

        public function __construct($car)
        {
            require_once 'Class/cars.php';
            $this->car = $car;
            $this->title = "Бронювання Автомобіля";
        }
    
        public function ShowContent()
        {
            include "Parts/booking.php";
            session_start();
        }  
    }

    class CatalogPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Каталог";
            require_once 'Class/cars.php';
        }
    
        public function ShowContent()
        {
            include "Parts/catalog.php";
        }   
    }

    class FavoritesPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Обрані автомобілі";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/favorites.php";
        }  
    }

    class LoginPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Обрані автомобілі";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/login.php";
        }  
    }
?>