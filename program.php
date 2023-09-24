<?php

use Classes\Product;
include 'Classes/product.php';

//Вызов значений из файла .txt
$fCont = file_get_contents('data.txt');
$productsData = unserialize($fCont);
if ($productsData === false) {
    $productsData = []; // если данных нет
}

//Запись данных в файл
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProduct = new Classes\Product(
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
            break; // Нет необходимости продолжать поиск
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

// Сортировка для отображаемых на странице товаров
$sort = isset($_GET['sort']) ? $_GET['sort'] : "";

$sortedPr = $productsData;

if (!empty($sortedPr) && is_array($sortedPr)) {
    for ($i = 0; $i < count($sortedPr); $i++) {
        for ($j = 0; $j < count($sortedPr) - 1 - $i; $j++) {
            if (isset($sortedPr[$j]->price) && isset($sortedPr[$j + 1]->price)) {
                $price1 = (int)$sortedPr[$j]->price;
                $price2 = (int)$sortedPr[$j + 1]->price;

                if ($sort == 'up') {
                    if ($price1 > $price2) {
                        $t = $sortedPr[$j];
                        $sortedPr[$j] = $sortedPr[$j + 1];
                        $sortedPr[$j + 1] = $t;
                    }
                } else {
                    if ($price1 < $price2) {
                        $t = $sortedPr[$j];
                        $sortedPr[$j] = $sortedPr[$j + 1];
                        $sortedPr[$j + 1] = $t;
                    }
                }
            }
        }
    }
}

// Пагинация
$quantity = 6; // Количество элементов на странице
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$totProd = count($sortedPr) ?: 0;
$totPages = ceil($totProd / $quantity);

if ($page > $totPages) {
    $page = $totPages;
}

$startIndex = ($page - 1) * $quantity;
$productsPag = (is_array($sortedPr) && $startIndex >= 0 && $startIndex < $totProd) ? array_slice($sortedPr, $startIndex, $quantity) : [];

// Вывод таблицы
include 'program.html';

// Вычисление общей стоимости товаров
$totalPr = 0;
foreach ($productsPag as $product) {
        $totalPr += $product->price;
}
echo "Total Price: $" . Product::$totalPrice;

?>
