<?php
include 'product.php';

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

// добавление товара в корзину
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['add_to_cart'])) {
        $productId = $_GET['add_to_cart'];

        // Если товар уже есть в корзине
        foreach ($productsData as $product) {
            if ($product->getName() === $productId) {
                $cart[] = $product; // Добавил товар в корзину
                break;
            }
        }

        // Сохранить обновленную корзину в cart.txt
        $cartFileContents = serialize($cart);
        file_put_contents('cart.txt', $cartFileContents);

        // Перенаправить обратно на страницу корзины
        header("Location: cart.php");
        exit; // Добавьте это, чтобы завершить выполнение скрипта после перенаправления
    }
}

// удаление товара из корзины
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['remove_from_cart'])) {
    $productId = $_GET['remove_from_cart'];

    foreach ($cart as $key => $product) {
        if ($product->getName() === $productId) {
            unset($cart[$key]);
            break;
        }
    }

    // Сохранить корзину в cart.txt после удаления товара
    $cartFileContents = serialize($cart);
    file_put_contents('cart.txt', $cartFileContents);

    // Перенаправить обратно на страницу корзины
    header("Location: cart.php");
    exit; // Добавьте это, чтобы завершить выполнение скрипта после перенаправления
}

// Вычисление общей стоимости
$totalPr = 0;
foreach ($cart as $product) {
    $totalPr += $product->getPrice();
}

include 'cartTable.html';
