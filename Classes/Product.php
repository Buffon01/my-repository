<?php

namespace Classes;
class Product {
    public $price;
    public $name;
    public $weight;
    public $description;
    public $featured;
    public $region;
    public $discount;
    public $image;
    public $city;

    public static $totalPrice = 0;

    public function __construct($name = null, $price = null, $weight = null, $description = null, $featured = null, $region = null, $discount = null, $image = null, $city = null) {
        $this->name = $name;
        $this->setPrice($price);;
        $this->weight = $weight;
        $this->description = $description;
        $this->featured = $featured;
        $this->region = $region;
        $this->discount = $discount;
        $this->image = $image;
        $this->city = $city;

    }

    public static function create($name = null, $price = null, $weight = null, $description = null, $featured = null, $region = null, $discount = null, $image = null, $city = null) {
        return new self($name, $price, $weight, $description, $featured, $region, $discount, $image, $city);
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getLocation(): string {
        return $this->region . ', ' . $this->city;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getWeight(): float {
        return $this->weight;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function isFeatured(): bool {
        return $this->featured;
    }

    public function getRegion(): string {
        return $this->region;
    }

    public function getCity(): string {
        return $this->city;
    }

    public function getDiscount(): string {
        return $this->discount === 'with_discount' ? 'With Discount' : 'Without Discount';
    }

    public function getImagePath(): ?string {
        return $this->image;
    }

    public function __toString(): string {
        return $this->name . ' FORZA JUVE !!!';
    }

    public function __wakeup() {
        if (!isset(self::$totalPrice)) {
            self::$totalPrice = 0;
        }

        self::$totalPrice += $this->price;
    }
}



