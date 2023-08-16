<?php

// Вызов значений из файла .txt
$fCont = file_get_contents('data.txt');
$products = json_decode($fCont, true);

if (count($_POST) > 0) {
    $product = [
        "name" => $_POST['name'],
        "price" => $_POST['price'],
        "weight" => $_POST['weight'],
        "description" => $_POST['description'],
        "featured" => isset($_POST['featured']),
        "region" => $_POST['region'],
        "discount" => isset($_POST['discount']) ? $_POST['discount'] : 'without_discount'
    ];

    // Обработка загрузки файла
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageFilePath = 'Images/' . $imageFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imageFilePath);
        $product['image'] = 'Images/' . $imageFileName;
    }

    $products[] = $product;
    file_put_contents('data.txt', json_encode($products));
}

// Определение значения по ключу
$s = '';
$f = $products;

if (array_key_exists('key', $_GET) && strlen($_GET['key']) > 0) {
    $s = $_GET['key'];
    $f = [];

    foreach ($products as $product) {
        if (str_contains($product['name'], $s)) {
            $f[] = $product;
        }
    }

    $products = $f;
}

// Сортировка для отображаемых на странице товаров
$sort = array_key_exists('sort', $_GET) ? $_GET['sort'] : "";

$sortedPr = $products;

if (!empty($sortedPr)) { //проверка на пустой массив
    for ($i = 0; $i < count($sortedPr); $i++) {
        for ($j = 0; $j < count($sortedPr) - 1 - $i; $j++) {
            // Sort by price:
            if ($sort == 'up') {
                if ((int)$sortedPr[$j]['price'] > (int)$sortedPr[$j + 1]['price']) {
                    $t = $sortedPr[$j];
                    $sortedPr[$j] = $sortedPr[$j + 1];
                    $sortedPr[$j + 1] = $t;
                }
            } else {
                if ((int)$sortedPr[$j]['price'] < (int)$sortedPr[$j + 1]['price']) {
                    $t = $sortedPr[$j];
                    $sortedPr[$j] = $sortedPr[$j + 1];
                    $sortedPr[$j + 1] = $t;
                }
            }
        }
    }
}

// Пагинация
$quantity = 6; // Количество элементов на странице
$page = 1; // Текущая страница

if (array_key_exists('page', $_GET) && is_numeric($_GET['page'])) {
    $page = max(1, intval($_GET['page']));
}

$totProd = count($sortedPr) ?: 0;
$totPages = ceil($totProd / $quantity);

if ($page > $totPages) {
    $page = $totPages;
}

$startIndex = ($page - 1) * $quantity;
$productsPag = (is_array($sortedPr) && $startIndex >= 0 && $startIndex < $totProd) ? array_slice($sortedPr, $startIndex, $quantity) : [];

// определение максимальной длины массивов
$maxLenName = 0;
$maxLenPrice = strlen("Price");
$maxLenWeight = strlen("Weight");

foreach ($productsPag as $product) {
    $name = $product['name'];
    $price = "$" . $product['price'];
    $weight = $product['weight'];

    $nameLen = strlen($name);
    $priceLen = strlen($price);
    $weightLen = strlen($weight);

    if ($nameLen > $maxLenName) {
        $maxLenName = $nameLen;
    }
    if ($priceLen > $maxLenPrice) {
        $maxLenPrice = $priceLen;
    }
    if ($weightLen > $maxLenWeight) {
        $maxLenWeight = $weightLen;
    }
}

if ($maxLenPrice > $maxLenName) {
    $maxLenName = $maxLenPrice;
}

if ($maxLenWeight > $maxLenName) {
    $maxLenName = $maxLenWeight;
}

// Вывод таблицы
include 'program.html';

// Вычисление общей стоимости товаров
$totalPr = array_sum(array_column($productsPag, 'price'));
echo "Total Price: $" . $totalPr;

?>
