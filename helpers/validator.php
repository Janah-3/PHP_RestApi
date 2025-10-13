
<?php
function validateFields ($data, $requiredFields){
   $missing=[];

   foreach($requiredFields as $field){
    if(empty($data[$field] )){
        $missing[] = $field;
    }
   }

   if(!empty($missing)){
    http_response_code(400);
    echo json_encode(["success" => false, "message" =>  "Missing required field " . implode(", ", $missing)]);
    exit;
    jsonResponse('failed',"Missing required field". implode(", ", $missing),400,);
   }

}



function validateEmail ($email){
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){

       jsonResponse("failed" , "Invalid email format",400);

    }
}


function validateRequestMethod($RequestMethod) {
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($RequestMethod)) {

        jsonResponse("failed" ,"Only $RequestMethod requests are allowed",405);
        
    }
}

?>