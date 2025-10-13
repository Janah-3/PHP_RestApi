<?php
// $host ="localhost";
// $user="root";
// $pass="";
// $DBName="php_api";

$connect = new PDO("mysql:host=localhost;dbname=php_api", "root", "");
$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>