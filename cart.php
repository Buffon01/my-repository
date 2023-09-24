<?php

include 'Classes/product.php';

// Загрузка данных товаров из data.txt
$dataFileContents = file_get_contents('data.txt');
$productsData = unserialize($dataFileContents);
if ($productsData === false) {
    $productsData = []; // если данных нет
}

$cart = []; // Инициализация пустой корзины, если она не существует

// Загрузка корзины из cart.txt
$cartFileContents = file_get_contents('cart.txt');
if ($cartFileContents !== false) {
    $cart = unserialize($cartFileContents);
    if ($cart === false) {
        $cart = []; // Если не удается десериализовать данные, создать пустую корзину
    }
}

// Вычисление общей стоимости

$totalPr = 0;
foreach ($cart as $product) {
    $totalPr += $product->getPrice();
}

include 'cartTable.html';
