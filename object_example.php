<?php

// В новой программе опиши класс любой структуры (продукт, пользователь, животное, геометрическая фигура, ...).
// Создай три экземпляра этого класса, и выведи информацию каждого из них.

class Product {
    public $name;
    public $weight;
    public $price;
}

$egg = new Product;
$egg->name = 'Egg';
$egg->price = 23;
$egg->weight = 1;

echo 'Name:' . $egg->name . ', Price:' . $egg->price . '$' . ', Weight:' . $egg->price . ' kilos' . "\n";