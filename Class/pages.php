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

        public function __construct()
        {
            require_once 'Class/cars.php';
            $this->title = "Бронювання Автомобіля";
        }
    
        public function ShowContent()
        {
            include "Parts/booking.php";
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

    class AccountPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Особистий кабінет";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/account.php";
        }  
    }

    
    class RegistrationPage extends Page 
{
    public function __construct()
    {
        $this->title = "Регістрація";
        require_once 'Class/cars.php';
        session_start();
    }

    public function ShowContent()
    {
        $contentFile = "Parts/registration.php";
        if (file_exists($contentFile)) {
            include $contentFile;
        } else {
            echo "Файл $contentFile не знайдено.";
        }
    }  
}
    
    class CustomerCRUDPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Управління користувачами";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/customerCRUD.php";
        }  
    }
    class CarCRUDPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Управління автомобілями";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/carCRUD.php";
        }  
    }
    class StatsPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Статистика";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/stats.php";
        }  
    }
    class PaymentPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Оплата";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/payment.php";
        }  
    }

    class SuccessPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Оплата";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/success.php";
        }  
    }

    class ForumPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Оплата";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/forum.php";
        }  
    }

    class ReservationsPage extends Page 
    {
        public function __construct()
        {
            $this->title = "Оплата";
            require_once 'Class/cars.php';
            session_start();
        }
    
        public function ShowContent()
        {
            include "Parts/reservations.php";
        }  
    }
?>


