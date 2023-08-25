<?php

include 'product.php';

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
    $product = new Product();
    $product->name = $productData["name"];
    $product->price = $productData["price"];
    $product->weight = $productData["weight"];
    $product->description = $productData["description"];
    $product->featured = $productData["featured"];
    $product->region = $productData["region"];
    $product->discount = $productData["discount"];
    $product->image = $productData["image"];
    $product->city = $productData["city"];
    $products[] = $product;
}
    $serializedData = serialize($products);
    file_put_contents('data.txt', $serializedData);


header("Location: /program.php");
//echo "file has been reset";

?>