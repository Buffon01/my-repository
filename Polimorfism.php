<?php

interface Shape {
    public function area();
    public function type();
}

// класс Circle
class Circle implements Shape {
    private $radius;

    public function __construct($radius) {
        $this->radius = $radius;
    }

    public function area() {
        return M_PI * pow($this->radius, 2); // Площадь круга
    }

    public function type() {
        return "Круг";
    }
}

// класс Rectangle
class Rectangle implements Shape {
    private $length;
    private $width;

    public function __construct($length, $width) {
        $this->length = $length;
        $this->width = $width;
    }

    public function area() {
        return $this->length * $this->width; // Площадь прямоугольника
    }

    public function type() {
        return "Прямоугольник";
    }
}

// класс Triangle
class Triangle implements Shape {
    private $side1;
    private $side2;
    private $side3;

    public function __construct($side1, $side2, $side3) {
        $this->side1 = $side1;
        $this->side2 = $side2;
        $this->side3 = $side3;
    }

    public function area() {
        $s = ($this->side1 + $this->side2 + $this->side3) / 2;
        return sqrt($s * ($s - $this->side1) * ($s - $this->side2) * ($s - $this->side3));
    }

    public function type() {
        return "Треугольник";
    }
}

// массив объектов фигур
$shapes = [
    new Circle(5),
    new Rectangle(4, 6),
    new Triangle(3, 4, 5),
    new Circle(3.5),
    new Rectangle(7, 2),
    new Triangle(6, 8, 10),
];

// вывод типа и площади фигур
foreach ($shapes as $shape) {
    echo "Тип: " . $shape->type() . ", Площадь: " . $shape->area() . PHP_EOL;
}

?>
