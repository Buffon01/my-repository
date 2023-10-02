<?php

spl_autoload_register(function ($class) {
    include $class . '.php';
});

use Classes\ShoppingCart;

// Загрузка данных товаров из data.txt
$dataFileContents = file_get_contents('data.txt');
$productsData = unserialize($dataFileContents);
if ($productsData === false) {
    $productsData = []; // если данных нет
}

$cart = new ShoppingCart();

// Загрузка корзины из cart.txt
$cartFileContents = file_get_contents('cart.txt');
if ($cartFileContents !== false) {
    $cart->items = unserialize($cartFileContents);
    if ($cart->items === false) {
        $cart->items = []; // Если не удается десериализовать данные, создать пустую корзину
    }
}

// Если пользователь отправил форму для обновления корзины
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $newCart = [];

    foreach ($_POST['quantity'] as $productName => $quantity) {
        $product = new Product($productName, $productsData[$productName]);
        $cart->addItem($product, $quantity);
    }

    // Сохранить обновленную корзину в файл cart.txt
    $cartFileContents = serialize($cart->items);
    file_put_contents('cart.txt', $cartFileContents);


    // Перенаправить обратно на страницу корзины
    header("Location: cart.php");
    exit;
}

include 'cartTable.html';
