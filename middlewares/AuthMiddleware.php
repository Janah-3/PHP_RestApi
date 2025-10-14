<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ ."/../Config/config.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function Is_Authenticated() {
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
       
        jsonResponse("error",'Missing Authorization header',401);
    }

    $authHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authHeader); // remove 'Bearer ' prefix

    try {
        $decoded = JWT::decode($token, new Key(JWT_SECRET_KEY, 'HS256'));
        return $decoded; // contains user info, role, etc.
    } catch (Exception $e) {
       
        jsonResponse("error",'Invalid or expired token'.$e,401);
    }
        
}
?>