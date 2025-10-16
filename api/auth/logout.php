<?php
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/tokenRepo.php';
require_once __DIR__ . '/../../middlewares/AuthMiddleware.php';


try{
validateRequestMethod('POST');
Is_Authenticated();

$data=json_decode(file_get_contents("php://input"),true);
validateFields($data,['refreshToken']);

 revokeRefreshToken($data['refreshToken']);


}catch(Throwable $error)
{
   jsonResponse("error" ,$error->getMessage(),500);
}


?>