<?php
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../repositories/tokenRepo.php';

try{
    validateRequestMethod('POST');
$data=json_decode(file_get_contents("php://input"),true);
validateFields($data,['refreshToken']);

$TokenData=checkRefreshTokenValidation($data['refreshToken']);

$accessToken=createAccessToken($TokenData);

jsonResponse("success" ,"Token refreshed successfully",200,["access token" =>$accessToken , "refresh_token" =>$data['refreshToken']]);

}catch(Throwable $error)
{
      jsonResponse("error" ,$error->getMessage(),500);
}


?>