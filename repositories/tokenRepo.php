<?php
require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../vendor/autoload.php';


use Firebase\JWT\JWT;

     
function createAccessToken($user){

return JWT::encode(
    ['user_id' => $user['user_id'] ,"role"=>$user['role'], 'exp' => time() + 900 ,"iss" => "localhost","iat"=>time()], 
    JWT_SECRET_KEY,
    'HS256'
    );
}   

function createRefreshToken($user) {
    global $connect;

    $refreshToken = bin2hex(random_bytes(64)); 
    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 days'));
    $createdAt = date('Y-m-d H:i:s');

    try {
        $insertQuery = "INSERT INTO refresh_tokens (user_id, token, expires_at, created_at, is_revoked)
                        VALUES (:uid, :token, :exp, :created_at, :revoked)";

        $insertStmt = $connect->prepare($insertQuery);
        $insertStmt->execute([
            ':uid' => $user['user_id'],
            ':token' => $refreshToken,
            ':exp' => $expiresAt,
            ':created_at' => $createdAt,
            ':revoked' => 0
        ]);

      
        return $refreshToken;

    } catch (PDOException $e) {
        jsonResponse("failed", $e->getMessage(), 500);
    }
}


function revokeRefreshToken($refreshToken) {
    global $connect;
    $updateToken = "UPDATE refresh_tokens SET is_revoked = 1 WHERE token = :Token";
    $update=$connect->prepare($updateToken);
    $update->execute([':Token' => $refreshToken]);

    $is_revoked= $update->rowCount();

      if ($is_revoked > 0) {
        jsonResponse("success", "Logged out successfully", 200);
    } else {
        jsonResponse("error", "Invalid or already revoked token", 400);
    }

}

function checkRefreshTokenValidation($refreshToken){
 global $connect;
$select="SELECT rt.expires_at , u.role ,rt.user_id 
              FROM refresh_tokens rt 
              JOIN users u ON rt.user_id = u.user_id
              WHERE token = :token";

$stmt = $connect ->prepare($select);
$stmt -> execute([':token' => $refreshToken]);
$tokenData = $stmt->fetch(PDO::FETCH_ASSOC);


if(!$tokenData){
    jsonResponse("failed" , "Invalid refresh token" ,400);
}

 $tokenExpiryT = new DateTime($tokenData['expires_at']);
 $nowDateTime=new DateTime();



if($nowDateTime > $tokenExpiryT){

    revokeRefreshToken($refreshToken);
    jsonResponse("failed","Refresh token expired",401);
   
}

return $tokenData;
}

?>