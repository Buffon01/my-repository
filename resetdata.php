<?php

//spl_autoload_register(function ($class){
//    include 'Classes/' . $class . '.php';
//});
//include 'Classes/Product.php';

$file = 'data.txt';
file_put_contents($file, '');


// Преобразование данных в объекты

$productsData = [
    [
        "name" => 'Dick',
        "price" => 100,
        "weight" => 33,
        "description" => "Black Dick",
        "featured" => true,
        "region" => "Europe",
        "city" => "Paris",
        "discount" => "with_discount",
        "image" => null,
    ],
];

$products = [];
foreach ($productsData as $productData) {
    $product = new Classes\Product(
        $productData["name"],
        $productData["price"],
        $productData["weight"],
        $productData["description"],
        $productData["featured"],
        $productData["region"],
        $productData["discount"],
        $productData["image"],
        $productData["city"]
    );
    $products[] = $product;
}
    $serializedData = serialize($products);
    file_put_contents('data.txt', $serializedData);


header("Location: /program.php");
//echo "file has been reset";

?>
