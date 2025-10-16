<?php
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../repositories/tokenRepo.php';
require_once __DIR__ . '/../../middlewares/AuthMiddleware.php';

try{
    validateRequestMethod('POST');
$data=json_decode(file_get_contents("php://input"),true);
validateFields($data,['refreshToken']);

$TokenData=checkRefreshTokenValidation($data['refreshToken']);

$accessToken=createAccessToken($TokenData);

jsonResponse("success" ,"Token refreshed successfully",200,["access token" =>$accessToken ]);

}catch(Throwable $error)
{
      jsonResponse("error" ,$error->getMessage(),500);
}


?>