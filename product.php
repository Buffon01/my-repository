<?php

class Product {
    public $name;
    public $price;
    public $weight;
    public $description;
    public $featured;
    public $region;
    public $discount;
    public $image;
    public $city;

    public function getLocation(): string
    {
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
}


