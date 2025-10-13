<?php 
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/connection.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/userRepo.php';


try{
validateRequestMethod('POST');
$data=json_decode(file_get_contents("php://input"),true);
validateFields($data, ['name', 'email', 'password','confirmPassword']);
validateEmail($data['email']);

createUser($data['name'],$data['email'],$data['password'],$data['confirmPassword']);

}catch(Throwable $error)
{
    jsonResponse("error" ,"unexpected error".$error->getMessage(),500);
}


?>