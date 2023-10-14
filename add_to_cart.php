<?php

include 'autoload.php';

use Classes\ShoppingCart;
use Classes\Product;

$cart = new ShoppingCart();

$productId = $_GET['name'] ?? '';
$productPrice = $_GET['price'] ?? 0;

// Проверяем, есть ли товар с таким именем уже в корзине
$itemExists = false;
foreach ($cart->items as &$item) {
    if ($item['name'] == $productId) {
        $item['quantity']++; // Увеличиваем количество
        $itemExists = true;
        break;
    }
}

if (!$itemExists) {
    // Если товара с таким именем нет в корзине, добавляем его
    $cart->addItem(new Product($productId, $productPrice), 1);
}

// После обновления корзины, сохраняем ее в файл
$cartFileContents = serialize($cart->items);
file_put_contents('cart.txt', $cartFileContents);

header("Location: /program.php");
?>
