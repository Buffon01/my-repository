<?php

global $pdo;
include 'autoload.php';

use Classes\Product;

// Запрос для получения данных
$query = "SELECT * FROM product ORDER BY price ASC";
$statement = $pdo->query($query);

$productsData = [];

foreach ($statement as $row) {
    $product = new Product($row['id'], $row['name'], $row['price'], $row['weight'], $row['description'], $row['featured'], $row['location'], $row['discount'], $row['image'], $row['add to cart']);
    $productsData[] = $product;
}

//Запись данных в файл
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProduct = Product::create(
        $_POST['name'],
        $_POST['price'],
        $_POST['weight'],
        $_POST['description'],
        isset($_POST['featured']),
        $_POST['region'],
        isset($_POST['discount']) ? $_POST['discount'] : 'without_discount',
        isset($_FILES['image']['name']) ? 'Images/' . $_FILES['image']['name'] : null,
        $_POST['city']
    );

    $productsData[] = $newProduct;
    $serializedData = serialize($productsData);
    file_put_contents('data.txt', $serializedData);
}

// Добавляем товар в корзину
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['add_to_cart'])) {
    $productId = $_GET['add_to_cart'];

    // Находим товар по его имени
    foreach ($productsData as $product) {
        if ($product->getName() === $productId) {
            $cart[] = $product; // Добавляем товар в корзину
            break;
        }
    }

    // Сохраняем обновленную корзину в cart.txt
    $cartFileContents = serialize($cart);
    file_put_contents('cart.txt', $cartFileContents);
}

// Определение значения по ключу
$s = '';
$f = $productsData;

if (isset($_GET['key']) && strlen($_GET['key']) > 0) {
    $s = $_GET['key'];
    $f = [];

    foreach ($productsData as $product) {
        if (str_contains($product->name, $s)) {
            $f[] = $product;
        }
    }

    $products = $f;
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Пагинация
$quantity = 6; // Количество элементов на странице
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? max(1, intval($_GET['page'])) : 1;
$totProd = count($productsData) ?: 0;
$totPages = ceil($totProd / $quantity);

if ($page > $totPages) {
    $page = $totPages;
}

$startIndex = ($page - 1) * $quantity;
$productsPag = array_slice($productsData, $startIndex, $quantity);

// Вывод таблицы
include 'program.html';


// Вычисление общей стоимости товаров
$totalPrice = (new Classes\Product)->getTotalPrice(); // Получение общей стоимости

echo "Total Price: $" . $totalPrice;

?>
