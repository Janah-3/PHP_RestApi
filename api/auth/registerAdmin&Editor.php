<?php
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/userRepo.php';
require_once __DIR__ . '/../../middlewares/AuthMiddleware.php';

try{

validateRequestMethod('POST');
 requireRole(['admin']);

$data=json_decode(file_get_contents("php://input"),true);
validateFields($data, ['name', 'email', 'password','confirmPassword' ,'role']);
validateEmail($data['email']);

createUser($data['name'],$data['email'],$data['password'],$data['confirmPassword'],$data['role']);

}catch(Throwable $error)
{
    jsonResponse("error" ,"unexpected error".$error->getMessage(),500);
}


?>