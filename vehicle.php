<?php

class Vehicle {
    public $brand;
    public $model;
    public $year;

    public function getDetails()
    {
        echo '(Vehicle) brand = ' . $this->brand . ', model = ' . $this->model . ', year = ' . $this->year . "\n";
    }
}


class Car extends Vehicle {
    public $doors;
    public $engineType;

    public function getDetails()
    {
        echo '(Car) brand = ' . $this->brand . ', model = ' . $this->model . ', year = ' . $this->year . ', doors = ' . $this->doors . ', engineType = ' . $this->engineType . "\n";
    }

}

class Bicycle extends Vehicle {
    public $type;
    public $speeds;

    public function getDetails()
    {
        echo '(Bicycle) brand = ' . $this->brand . ', model = ' . $this->model . ', year = ' . $this->year . ', type = ' . $this->type . ', speeds = ' . $this->speeds . "\n";
    }
}

$car = new Car();
$car->year = 2013;
$car->brand = 'Skoda';
$car->model = 'Fabia';
$car->doors = 5;
$car->engineType = 'Hybrid';

$bicycle = new Bicycle();
$bicycle->year = 1999;
$bicycle->model = 'R55';
$bicycle->brand = 'Trek';
$bicycle->type = 'FX2';
$bicycle->speeds = 24;

$car->getDetails();
$bicycle->getDetails();





