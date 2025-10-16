<?php
require_once __DIR__ . '/../../helpers/Validator.php';
require_once __DIR__ . '/../../helpers/Response.php';
require_once __DIR__ . '/../../repositories/userRepo.php';

try {
validateRequestMethod('POST');
$data = json_decode(file_get_contents("php://input"), true);

validateFields($data, ['email']);
validateEmail($data['email']);

$user = findUserByEmail($data['email']);

if (!$user)
{
  jsonResponse("failed" , "Invalid email ",401);
}

$reset_code = createResetCode($user);


} catch (Throwable $error) {
   jsonResponse("error" ,$error->getMessage(),500);
}

?>
