<?php
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/userRepo.php';


try{
validateRequestMethod('POST');
$data= json_decode(file_get_contents("php://input"),true);
validateFields($data,['code','newPassword']);

$user_id = checkCodeValidation($data['code']);
updatePassword($data['newPassword'],$user_id);

}catch(Throwable $error)
{
    jsonResponse("error" ,"An unexpected error occurred".$error->getMessage(),500);
}

?>