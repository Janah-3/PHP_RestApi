
<?php


function validateFields ($data, $requiredFields){
   $missing=[];
try{
   foreach($requiredFields as $field){
    if(empty($data[$field] )){
        $missing[] = $field;
    }
   }

   if(!empty($missing)){
   
    jsonResponse('failed',"Missing required field". implode(", ", $missing),400,);
   }
}catch(Throwable $e){
 jsonResponse('failed',$e,500,);
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



function validateAndUploadImage($file, $uploadDir = __DIR__ .'/../uploads/products/', $maxSizeMB = 2)
{
   
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        jsonResponse("failed","No image uploaded or upload error occurred",400);
    }

    
    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        jsonResponse("failed","Invalid image type. Only JPG, PNG, and WEBP are allowed",400);
    }

    
    $maxSizeBytes = $maxSizeMB * 1024 * 1024;
    if ($file['size'] > $maxSizeBytes) {
        jsonResponse("failed","Image size exceeds {$maxSizeMB}MB limit",400);
        
    }

   
    $targetPath = $uploadDir . basename($file['name']);

    
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
         jsonResponse("failed","Failed to upload image",400);
    }

    
    $baseUrl = (isset($_SERVER['HTTPS']) ) . $_SERVER['HTTP_HOST'] . '/';
    return $baseUrl . $targetPath;
}





?>