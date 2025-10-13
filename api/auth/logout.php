<?php
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/tokenRepo.php';


try{
validateRequestMethod('POST');
$data=json_decode(file_get_contents("php://input"),true);
validateFields($data,['refreshToken']);



 $is_revoked=revokeRefreshToken($data['refreshToken']);

    if ($is_revoked > 0) {
        jsonResponse("success", "Logged out successfully", 200);
    } else {
        jsonResponse("error", "Invalid or already revoked token", 400);
    }

}catch(Throwable $error)
{
   jsonResponse("error" ,$error->getMessage(),500);
}


?>