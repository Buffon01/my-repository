<?php

class Counter {
    private $value;

    public function __construct() {
        $this->value = 0;
    }

    public function increment() {
        $this->value++;
    }

    public function decrement() {
        if ($this->value > 0) {
            $this->value--;
        }
    }

    public function getValue() {
        return $this->value;
    }
}


$counter = new Counter();
echo $counter->getValue() . "\n"; // Вывод: 0

$counter->increment();
echo $counter->getValue() . "\n"; // Вывод: 1

$counter->decrement();
echo $counter->getValue() . "\n"; // Вывод: 0

