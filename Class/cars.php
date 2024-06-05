<?php
    class Car 
    {
        public $id;
        public $name;
        public $brand;
        public $model;
        public $year;
        public $pricePerHour;
        public $category;
        public $color;
        public function __construct($id, $name, $brand, $model, $year, $pricePerHour, $category, $picture, $color)
        {
            $this->id = $id;
            $this->name = $name;
            $this->brand = $brand;
            $this->model = $model;
            $this->year = $year;
            $this->pricePerHour = $pricePerHour;
            $this->category = $category;
            $this->picture = $picture;
            $this->color = $color;
        }
            public function getCarDetails()
        {
            return "ID: " . $this->id . ", Назва: " . $this->name . ", Бренд: " . $this->brand . ", Модель: " . $this->model . ", Рік: " . $this->year . ", Ціна за годину: " . $this->pricePerHour;
        }
            public function calculateRentalCost($hours)
        {
            return $this->pricePerHour * $hours;
        }
            public function checkAvailabilityForRent($hours)
        {
            if ($this->isAvailable) {
                return "Автомобіль доступний для оренди на $hours годин";
            } else {
                return "Автомобіль вже орендований і не доступний для оренди";
            }
        }
    }
            class Card
        {
            public $id;
            public $car;
            public $isAvailable;

            public function __construct($id, $car) {
                $this->id = $id;
                $this->car = $car;           
                $this->isAvailable = $car->count > 0;
            }
        }
           
    class favoritesItem
    {
        public $id;
        public $car;

        public function __construct($id, $car) {
            $this->id = $id;
            $this->car = $car;           
        }
    }
?>