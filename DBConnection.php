<?php
class DBConnection {
    private $pdo;

    public function __construct() {
        $dsn = 'mysql:host=sql210.infinityfree.com;dbname=if0_36672875_drivesmart;port=3306';
        $user = 'if0_36672875';
        $password = 'TqffyiPfBbVtpS';

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
    public function addUser($firstName, $lastName, $login, $email, $password, $role, $image) {
        try {
            $sql = 'INSERT INTO users (first_name, last_name, email, login, password, role, image) VALUES (:first_name, :last_name, :email, :login, :password, :role, :image)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':image', $image);
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
    public function blockUser($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET blocked = 1 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    }

    public function unblockUser($user_id) {
        $stmt = $this->pdo->prepare("UPDATE users SET blocked = 0 WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
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

    public function updateUser($user_id, $first_name, $last_name, $email, $login, $role, $image) {
        $stmt = $this->pdo->prepare('UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, login = :login, role = :role, image = :image WHERE user_id = :user_id');
        $stmt->execute([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'login' => $login,
            'role' => $role,
            'image' => $image,
            'user_id' => $user_id,
        ]);
    }    
    public function updateCar($car_id, $make, $model, $category, $color, $image, $price_per_hour) {
        $stmt = $this->pdo->prepare('UPDATE cars SET make = :make, model = :model, category = :category, color = :color, image = :image, price_per_hour = :price_per_hour  WHERE car_id = :car_id');
        $stmt->execute([
            'make' => $make,
            'model' => $model,
            'category' => $category,
            'color' => $color,
            'image' => $image,
            'price_per_hour' => $price_per_hour,
            'car_id' => $car_id,
        ]);
    }
    public function sortAllUsers($sort_by = 'user_id', $order = 'ASC') {
        $valid_columns = ['user_id', 'first_name', 'last_name', 'email', 'login', 'role'];
        if (!in_array($sort_by, $valid_columns)) {
            $sort_by = 'user_id';
        }
        $sql = "SELECT * FROM users ORDER BY $sort_by $order";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function sortAllCars($sort_by = 'car_id', $order = 'ASC') {
        $valid_columns = ['car_id', 'make', 'model', 'category', 'color', 'price_per_hour'];
        if (!in_array($sort_by, $valid_columns)) {
            $sort_by = 'car_id';
        }
        $sql = "SELECT * FROM cars ORDER BY $sort_by $order";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }
    public function getTotalUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total_users FROM users");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    }

    public function getActiveUsers($startDate, $endDate) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS active_users FROM users WHERE last_active BETWEEN :start_date AND :end_date");
        $stmt->execute(['start_date' => $startDate, 'end_date' => $endDate]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['active_users'];
    }

    public function getTotalRentedCars() {
        $stmt = $this->pdo->query("SELECT COUNT(DISTINCT car_id) AS total_rented_cars FROM rentals");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_rented_cars'];
    }

    public function getTotalCars() {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total_cars FROM cars");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_cars'];
    }

    public function getAvgUsageTime($model) {
        $stmt = $this->pdo->prepare("SELECT AVG(TIMESTAMPDIFF(MINUTE, rental_start, rental_end)) AS avg_usage_time FROM rentals 
                                     JOIN cars ON rentals.car_id = cars.id 
                                     WHERE CONCAT(cars.make, ' ', cars.model) = :model");
        $stmt->execute(['model' => $model]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['avg_usage_time'];
    }

    public function getPopularCar() {
        $stmt = $this->pdo->query("SELECT CONCAT(cars.make, ' ', cars.model) AS popular_car, COUNT(*) AS count 
                                   FROM rentals 
                                   JOIN cars ON rentals.car_id = cars.id 
                                   GROUP BY popular_car 
                                   ORDER BY count DESC 
                                   LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC)['popular_car'];
    }

    public function getCarModels() {
        $stmt = $this->pdo->query("SELECT DISTINCT CONCAT(make, ' ', model) AS car_model FROM cars");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
public function addReservation($user_id, $car_id, $start_time, $end_time, $amount) {
    $stmt = $this->pdo->prepare("INSERT INTO reservations (user_id, car_id, start_time, end_time, status, amount) VALUES (:user_id, :car_id, :start_time, :end_time, 'booked', :amount)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':car_id', $car_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();
}

public function addPayment($user_id, $amount, $status) {
    $stmt = $this->pdo->prepare("INSERT INTO payments (user_id, amount, payment_datetime, status) VALUES (:user_id, :amount, NOW(), :status)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':amount', $amount);
    $stmt->bindParam(':status', $status);
    $stmt->execute();
}
public function updateReservationStatus($user_id, $car_id, $start_time, $end_time, $status) {
    $stmt = $this->pdo->prepare("UPDATE reservations SET status = :status WHERE user_id = :user_id AND car_id = :car_id AND start_time = :start_time AND end_time = :end_time");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':car_id', $car_id);
    $stmt->bindParam(':start_time', $start_time);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->execute();
}
public function fetchUserReservations($user_id) {
    $stmt = $this->pdo->prepare('
        SELECT reservations.*, cars.model, cars.make, cars.image 
        FROM reservations 
        JOIN cars ON reservations.car_id = cars.car_id 
        WHERE reservations.user_id = :user_id
    ');
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getReservationById($user_id, $car_id, $start_date, $end_date, $amount) {
    $sql = "INSERT INTO reservations (user_id, car_id, start_time, end_time, amount, status) 
            VALUES (:user_id, :car_id, :start_date, :end_date, :amount, 'unpaid')";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':car_id', $car_id);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->bindParam(':amount', $amount);
    $stmt->execute();
}

}

?>
