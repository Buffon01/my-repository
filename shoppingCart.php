<?php

class ShoppingCart
{
    public array $items = [];
    public $name;


    public function addItem($product, $price, $quantity)
    {
        $this->items[] = ['name' => $product, 'price' => $price, 'quantity' => $quantity];

        foreach ($this->items as $item) {
            if ($item['name'] == $product) {
                $item['quantity']++;
            }
        }
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            //    $total += $item['name']->price * $item['quantity'];
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}