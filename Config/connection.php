<?php
require_once __DIR__ . '/../helpers/Response.php';

try {
$connect = new PDO("mysql:host=localhost;dbname=php_api", "root", "");
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch (PDOException $e) {

    jsonResponse( 'error','Database connection failed'.$e->getMessage(),500);
}
?>