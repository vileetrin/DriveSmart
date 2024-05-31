<?php
class DBConnection {
    private $pdo;

    public function __construct() {
        $dsn = 'mysql:host=sql104.infinityfree.com;dbname=if0_36638872_drivesmartdb;port=3306';
        $user = 'if0_36638872';
        $password = 'XhxIwJi7YSkZTs3';

        try {
            $this->pdo = new PDO($dsn, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Помилка підключення: " . $e->getMessage() . "<br>";
            die();
        }
        
    }
    public function getPdo() {
        return $this->pdo;
    }
    public function addUser($firstName, $lastName, $login, $email, $password, $role) {
        try {
            $sql = 'INSERT INTO users (first_name, last_name, login, email, password, role) VALUES (:firstName, :lastName, :login, :email, :password, :role)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            echo "Користувача успішно додано.<br>";
        } catch (PDOException $e) {
            echo "Помилка під час додавання користувача: " . $e->getMessage() . "<br>";
        }
    }

    public function addCar($make, $model, $category, $color, $image, $pricePerHour) {
        try {
            $sql = "INSERT INTO cars (make, model, category, color, image, price_per_hour) VALUES (:make, :model, :category, :color, :image, :pricePerHour)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':make', $make);
            $stmt->bindParam(':model', $model);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':color', $color);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':pricePerHour', $pricePerHour);
            $stmt->execute();
            echo "Автомобіль успішно додано.<br>";
        } catch (PDOException $e) {
            echo "Помилка під час додавання автомобіля: " . $e->getMessage() . "<br>";
        }
    }

    public function fetchAllUsers() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        } catch (PDOException $e) {
            echo "Помилка під час отримання користувачів: " . $e->getMessage() . "<br>";
            return [];
        }
    }

    public function fetchAllCars() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM cars");
            $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $cars;
        } catch (PDOException $e) {
            echo "Помилка під час отримання автомобілів: " . $e->getMessage() . "<br>";
            return [];
        }
    }

    public function deleteUser($userId) {
        try {
            $sql = 'DELETE FROM users WHERE user_id = :userId';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            echo "Користувача успішно видалено.<br>";
        } catch (PDOException $e) {
            echo "Помилка під час видалення користувача: " . $e->getMessage() . "<br>";
        }
    }

    public function deleteCar($carId) {
        try {
            $sql = 'DELETE FROM cars WHERE car_id = :carId';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':carId', $carId);
            $stmt->execute();
            echo "Автомобіль успішно видалено.<br>";
        } catch (PDOException $e) {
            echo "Помилка під час видалення автомобіля: " . $e->getMessage() . "<br>";
        }
    }
    public function fetchCarById($car_id) {
        try {
            $sql = 'SELECT * FROM cars WHERE car_id = :car_id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':car_id', $car_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Помилка під час отримання даних автомобіля: " . $e->getMessage() . "<br>";
            return null;
        }
    }
    
    public function fetchUserById($user_id) {
        try {
            $sql = 'SELECT * FROM users WHERE user_id = :user_id';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Помилка під час отримання даних користувача: " . $e->getMessage() . "<br>";
            return null;
        }
    }
    
}

?>
