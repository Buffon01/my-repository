<?php

include 'autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['remove_from_cart'])) {
    // Загрузка корзины из cart.txt
    $cartFileContents = file_get_contents('cart.txt');
    $cart = unserialize($cartFileContents);
    if ($cart === false) {
        $cart = [];
    }

    $productId = $_GET['remove_from_cart'];

    foreach ($cart as $key => $product) {
        if ($product->getName() === $productId) {
            $cart[$productId]->removeQuantity();
            break;
        }
    }

    // Сохранить корзину в cart.txt после удаления товара
    $cartFileContents = serialize($cart);
    file_put_contents('cart.txt', $cartFileContents);

    // Перенаправить обратно на страницу корзины
    header("Location: cart.php");
    exit;
}
?>
