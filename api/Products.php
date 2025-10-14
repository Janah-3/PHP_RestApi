<?php
require_once __DIR__ . '/../repositories/productsRepo.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';


 $userData=Is_Authenticated();
if ($userData->role != "admin" && $userData->role != "editor") {
    jsonResponse('error', 'Access denied', 403);
}
if($_SERVER['REQUEST_METHOD'] =='GET'){
   
    if (isset($_GET['id'])) {
       
        $product_id = (int)$_GET['id'];
       $product =  GetProductById($product_id);
        jsonResponse("success","product retrived successfully",200,[$product]);
    } else {
        
        GetAllProducts();
    }



}else if($_SERVER['REQUEST_METHOD'] =='POST'){

    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $categoryid = $_POST['categoryid'] ?? 1;
    $file = $_FILES['image'] ;

    AddProduct($name, $description, $price, $categoryid, $file);

}else if($_SERVER['REQUEST_METHOD'] =='PUT') {

}
else if($_SERVER['REQUEST_METHOD'] =='PATCH'){
    if (isset($_GET['delete'])) {
    if ($userData->role != "admin") {
    jsonResponse('error', 'Access denied', 403);

    } 
      
    $data = json_decode(file_get_contents('php://input'), true);
   
    
     if ($data === null) {
         jsonResponse('error', 'Invalid JSON data', 400);
    }

    validateFields($data, ['product_id']);
    
    DeleteProduct($data['product_id']);
    }else{

        $data = json_decode(file_get_contents('php://input'), true);
         validateFields($data, ['product_id']);

        updateProduct($data['product_id'],$data);
    }



}else{
    echo "invalid request method ";
}
?>