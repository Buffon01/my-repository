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
    private $totalPrice = 0;
    private $quantity = 0;
    private $id;

    public function __construct($id = null, $name = null, $price = null, $weight = null, $description = null, $featured = null, $region = null, $discount = null, $image = null, $city = null) {
        $this->id = $id;
        $this->name = $name;
        $this->setPrice($price);;
        $this->weight = $weight;
        $this->description = $description;
        $this->featured = $featured;
        $this->region = $region;
        $this->discount = $discount;
        $this->image = $image;
        $this->city = $city;
        $this->totalPrice = $this->price;

    }

    public static function create($name = null, $price = null, $weight = null, $description = null, $featured = null, $region = null, $discount = null, $image = null, $city = null) {
        return new self($name, $price, $weight, $description, $featured, $region, $discount, $image, $city);
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function addQuantity(): void {
        $this->quantity = ++$this->quantity;
    }

    public function removeQuantity(): void {
        $this->quantity = --$this->quantity;
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
        return floatval($this->price);
    }

    public function getWeight(): float {
        return $this->weight;
    }

    /**
     * @burm тут дескрипшен возвращается NULL из базы, поэтому аннтация string не верная, меняем на ?string
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    public function isFeatured() {
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
        return $this->name;
    }

    public function __wakeup() {
        if (!isset($this->totalPrice)) {
            $this->totalPrice = 0;
        }

        $this->totalPrice += $this->price;
    }

    public function setTotalPrice($totalPrice): void
    {
        $this->totalPrice = $this->totalPrice + $totalPrice;
    }

    public function getTotalPrice()
    {
        global $totalPrice;
        if (empty($this->cart)) {
            return 0;
        }

        return $totalPrice;
    }
}
