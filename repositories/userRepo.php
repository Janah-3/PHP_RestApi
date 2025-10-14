<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




function findUserByEmail($email) {
    global $connect;
    $query = "SELECT * FROM users WHERE email = ? LIMIT 1";
    $stmt = $connect->prepare($query);
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function createUser($name,$email,$password,$confirmPassword,$role="user"){
    global $connect;

     $allowedRoles = ['admin', 'editor', 'user'];
    if (!in_array($role, $allowedRoles)) {
        jsonResponse('error', 'Invalid role. Allowed roles: ' . implode(', ', $allowedRoles), 400);
    }

    $user=findUserByEmail($email);
    if($password != $confirmPassword){
       jsonResponse("failed","password doesn't match confirm password",400);
    }

    if($user)
    {
       jsonResponse("failed" , "Email already exists",409);
    }

    $hashPassword = password_hash($password, PASSWORD_BCRYPT);

    $insertQ="insert into users  (username, email, password,role) 
               VALUES (:name, :email, :password ,:role)"; 
    $stmt = $connect-> prepare($insertQ) ;
    $stmt ->execute(['name' => $name , 'email' => $email , 'password' => $hashPassword ,':role'=>$role]);  

    if($role=="user"){
   $mailBody="Thank you for registering with us! Your account has been created successfully. We’re excited to have you on board.<br> <p>Best regards,<br>The AuthoStore Team</p>";
   MailMessage($email,'Welcome to AuthoStore',$mailBody);
    }

    jsonResponse("success" ,"User registered successfully",201);

}

 function verifyPassword($user, $password) {
    return password_verify($password, $user['password']);

}

function createResetCode($user){
  global $connect;
  $reset_code = mt_rand(100000, 999999);
  $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));


  $insertCode = "INSERT INTO password_resets (user_id, user_email, reset_code, expires_at)
               VALUES (:user_id, :email, :code, :expires)";
  $stmt = $connect->prepare($insertCode);
  $stmt->execute([
    'user_id' => $user['user_id'],
    'email' => $user['email'],
    'code' => $reset_code,
    'expires' => $expires_at
  ]);

  return $reset_code;

}

function MailMessage($email,$subject,$body){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'jojojana233@gmail.com';
    $mail->Password   = 'vyav igzh kbfa dnbe'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('jojojana233@gmail.com', 'AuthoStore');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->send();
    
    jsonResponse("success" ,"email sent successfully",200);
}

function checkCodeValidation($code){
global $connect;
$StoredCode = "SELECT id, user_email , user_id, reset_code, expires_at FROM password_resets WHERE reset_code = :code ORDER BY expires_at DESC LIMIT 1";
$stmt = $connect->prepare($StoredCode);
$stmt->execute([':code' => $code]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);



if (!$result ) {

    jsonResponse("failed" , "Invalid code ",400);
}

$nowDateTime=new DateTime();
$expiryDateTime = new DateTime($result['expires_at']);


if($nowDateTime > $expiryDateTime) {
    jsonResponse("failed" , "the code is expired ",400);
}
return $result['user_id'];

}


function updatePassword($newPassword , $user_id){
   global $connect;

  $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

  $updatePass = "UPDATE users SET password = :password WHERE id = :id";
  $stmt = $connect->prepare($updatePass);
  $stmt->execute([':password' => $hashedPassword,':id' => $user_id]);


if ($stmt->rowCount() > 0) {
   
    jsonResponse("success" , "Password updated successfully",200);

    $deleteReset = "DELETE FROM password_resets WHERE reset_code = :code";
    $stmt = $connect->prepare($deleteReset);
    $stmt->execute([':code' => $code]);

} else {
    jsonResponse("failed" , "password unchanged",402);
}
}





?>