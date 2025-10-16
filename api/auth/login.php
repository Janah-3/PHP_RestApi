<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/tokenRepo.php';
require_once __DIR__ . '/../../repositories/userRepo.php';



try{

validateRequestMethod('POST');
$data=json_decode(file_get_contents('php://input'),true) ;

validateFields($data, [ 'email', 'password']);
validateEmail($data['email']);

  $user=findUserByEmail($data['email']);
  
  $is_passCorrect = password_verify($data['password'], $user['password']);
   
  if(!$user || !$is_passCorrect){
    jsonResponse("failed","invaild credentials",401);
  }

  $accessToken= createAccessToken($user);
  $refreshToken = createRefreshToken($user);

 jsonResponse("success" ,"Login successful",200,["access token" =>$accessToken , "refresh_token" => $refreshToken]);

}catch(Throwable $error)
{
  jsonResponse("error" ,$error->getMessage(),500);
}



?>