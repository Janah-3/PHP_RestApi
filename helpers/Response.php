<?php
function jsonResponse($status,$message,$code,$data = null){

    http_response_code($code);
     
    $response = [
        "status" => $status,
        "message" => $message
    ];

    if (!is_null($data)) {
        $response["data"] = $data;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


?>