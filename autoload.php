<?php
spl_autoload_register(function($class){
    include $class . '.php';

});

$login = 'root';
$password = '';
$pdo = new PDO('mysql:host=localhost;dbname=learnProject', $login, $password);
