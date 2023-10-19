<?php

/**
 * @burm
 * нет смысла объявлять эту переменную глобальной,
 * она и так доступна потому что объявлена в файле который ты подключаешь
 */
// global $pdo;
include 'autoload.php';

use Classes\Product;

// Запрос для получения данных
/**
 * @burm
 * у тебя в GET может НЕ быть этого индекса, где проверка?
 */
//$n = $_GET['keyword'];
//$query = "SELECT * FROM product WHERE name = '" . $n . "' ORDER BY price ASC LIMIT 4";
//$statement = $pdo->query($query);

// делаем запрос, который по умолчанию всгде будет, не важно какие условия
$query = "SELECT * FROM product";
if (array_key_exists('key', $_GET)) {
    // если есть в гет поиск, то дописываем в SQL часть отвечающую за условия
    $query .= ' WHERE name = "' . $_GET['key'] . '"';
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price'; // или из ГЕТ или прайс

/**
 * @burm
 * .... допустим тут может быть еще куча условий, которые до-конкатенируют в запрос нужные части
 **/
// поскольку нам надо что-то вроде этого
//$query .= ' ORDER BY price ASC';

// а $sort  уже как раз несет в себе название поля, то можем сделать так
$query .= ' ORDER BY ' . $sort . ' ASC';


/**
 * @burm твоя часть которая была ниже, только чуть перемещенная сюда и с моими правками
 */
$quantity = 6; // Количество элементов на странице
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? max(1, intval($_GET['page'])) : 1;
$startIndex = ($page - 1) * $quantity;
$query .= ' LIMIT ' . $startIndex . ', ' . $quantity;

/**
 * @burm
 * тут шторм может ругнутся красной волнистей линией что он не знает этой переменной, но фактически она есть
 * в autoload.php поэтому переживать не стоит, это проблемы шторма
 */
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

/**
 * @burm это все лишнее
 */
//if (isset($_GET['key']) && strlen($_GET['key']) > 0) {
//    $s = $_GET['key'];
//    $f = [];
//
//    foreach ($productsData as $product) {
//        if (str_contains($product->name, $s)) {
//            $f[] = $product;
//        }
//    }
//
//    $products = $f;
//}

/**
 * @burm это тоже лишнее, потому что пагинацию надо рассчитывать там же где и поиск.. там где ты формируешь
 */
// Пагинация
//$quantity = 6; // Количество элементов на странице
//$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? max(1, intval($_GET['page'])) : 1;

// я поставил просто 10, от балды.. тут надо дописать - как получить кол-во товаров
$totProd = 10;//count($productsData) ?: 0;
$totPages = ceil($totProd / $quantity); // $quantity определена выше

if ($page > $totPages) {
    $page = $totPages;
}



/**
 * @burm я чисто переписал весь массив в другую переменную, потому что ниже по коду таблица хтмл работает в цикле с ней
 */
$productsPag = $productsData;
//$productsPag = array_slice($productsData, $startIndex, $quantity);
//$startIndex = ($page - 1) * $quantity;

// Вывод таблицы
include 'program.html';


// Вычисление общей стоимости товаров
$totalPrice = (new Classes\Product)->getTotalPrice(); // Получение общей стоимости

echo "Total Price: $" . $totalPrice;

?>
