<?php

$cartFileContents = file_get_contents('cart.txt');
$cart = unserialize($cartFileContents);
if ($cart === false) {
    $cart = [];
}

// Подключаем определение класса Product
//include 'Classes/Product.php';

$productId = $_GET['name'] ?? '';
$productPrice = $_GET['price'] ?? '';

// Создаем объект Product
$product = new Classes\Product($productId, $productPrice);

$cart[] = $product;

$cartFileContents = serialize($cart);
file_put_contents('cart.txt', $cartFileContents);

header("Location: /program.php");
?>
