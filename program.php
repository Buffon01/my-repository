<?php

global $pdo;
include 'autoload.php';

use Classes\Product;

// Запрос для получения данных
$n = $_GET['keyword'];
$query = "SELECT * FROM product WHERE name = '" . $n . "' ORDER BY price ASC LIMIT 4";
$statement = $pdo->query($query);

$productsData = [];

foreach ($statement as $row) {
    $product = new Product($row['id'], $row['name'], $row['price'], $row['weight'], $row['description'], $row['featured'], $row['location'], $row['discount'], $row['image'], $row['addtocart']);
    $productsData[] = $product;
}

//Вставка данных
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['price']) && isset($_POST['weight']) && isset($_POST['description']) && isset($_POST['region']) && isset($_POST['city'])) {
    $insertQuery = "INSERT INTO product (`id`,`name`, `price`, `weight`, `description`, `featured`, `location`, `discount`, `image`, `addtocart`) 
                    VALUES (null, :name, :price, :weight, :description, :featured, :location, :discount, :image, :addtocart)";

    $stmt = $pdo->prepare($insertQuery);

    $stmt->bindValue(':id', null);
    $stmt->bindValue(':name', $_POST['name']);
    $stmt->bindValue(':price', $_POST['price']);
    $stmt->bindValue(':weight', $_POST['weight']);
    $stmt->bindValue(':description', $_POST['description']);
    $stmt->bindValue(':featured', isset($_POST['featured']));
    $stmt->bindValue(':location', $_POST['region']);
    $stmt->bindValue(':discount', isset($_POST['discount']) ? $_POST['discount'] : 'without_discount');
    $stmt->bindValue(':image', isset($_FILES['image']['name']) ? 'Images/' . $_FILES['image']['name'] : null);
    $stmt->bindValue(':addtocart', $_POST['city']);

    $stmt->execute();

    header('Location: program.php');
    exit();
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
