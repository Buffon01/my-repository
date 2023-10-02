<?php

namespace Classes;
class ShoppingCart
{
    public array $items = [];
    public $name;

    public function addItem(Product $product, $quantity)
    {
        $productPrice = $product->getPrice();
        $productName = $product->getName();

        $found = false;
        foreach ($this->items as &$item) {
            if ($item['name'] == $productName) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $this->items[] = ['name' => $productName, 'price' => $productPrice, 'quantity' => $quantity];
        }
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
