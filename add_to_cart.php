<?php

$cartFileContents = file_get_contents('cart.txt');
$cart = unserialize($cartFileContents);
if ($cart === false) {
    $cart = [];
}

//
$productId = $_GET['name'] ?? '';
$productPrice = $_GET['price'] ?? '';

// объект Product
include 'product.php';
$product = new Product($productId, $productPrice, );


$cart[] = $product;

$cartFileContents = serialize($cart);
file_put_contents('cart.txt', $cartFileContents);

header("Location: /program.php");
?>
