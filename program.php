<?php

include 'product.php';

//Вызов значений из файла .txt
$fCont = file_get_contents('data.txt');
$productsData = unserialize($fCont);
if ($productsData === false) {
    $productsData = []; // если данных нет
}

//Запись данных в файл
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProduct = new Product();
    $newProduct->name = $_POST['name'];
    $newProduct->price = $_POST['price'];
    $newProduct->weight = $_POST['weight'];
    $newProduct->description = $_POST['description'];
    $newProduct->featured = isset($_POST['featured']);
    $newProduct->region = $_POST['region'];
    $newProduct->city = $_POST['city'];
    $newProduct->discount = isset($_POST['discount']) ? $_POST['discount'] : 'without_discount';

    // Загрузка файла
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['image']['name'];
        $imageFilePath = 'Images/' . $imageFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imageFilePath);
        $newProduct->image = 'Images/' . $imageFileName;
    }

    $productsData[] = $newProduct;
    $serializedData = serialize($productsData);
    file_put_contents('data.txt', $serializedData);
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
    if (isset($product->price) && is_numeric($product->price)) {
        $totalPr += $product->price;
    }
}
echo "Total Price: $" . $totalPr;

?>
