<?php
require_once __DIR__ . '/../repositories/productsRepo.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';



if($_SERVER['REQUEST_METHOD'] =='GET'){
   
    if (isset($_GET['id'])) {
       
        $product_id = (int)$_GET['id'];
       $product =  GetProductById($product_id);
        jsonResponse("success","product retrived successfully",200,[$product]);
    } else {
        
        GetAllProducts();
    }



}else if($_SERVER['REQUEST_METHOD'] =='POST'){
    $userData = requireRole(['admin', 'editor']);

    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $description = $_POST['description'] ?? '';
    $categoryid = $_POST['categoryid'] ?? 1;
    $file = $_FILES['image'] ;

    AddProduct($name, $description, $price, $categoryid, $file);

}
else if($_SERVER['REQUEST_METHOD'] =='PATCH'){

    if (isset($_GET['action']) && $_GET['action'] === 'delete') {

        requireRole(['admin']);

        if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
            jsonResponse('error', 'Valid product ID is required', 400);
        }
   
         deleteProduct($_GET['product_id']) ;

   
    }else if (isset($_GET['action']) && $_GET['action'] === 'restore') {
        requireRole(['admin']);

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
           jsonResponse('error', 'Valid product ID is required', 400);
        }

        RestoreProduct($_GET['id']);

    } else{
         $userData = requireRole(['admin', 'editor']);
         $data = json_decode(file_get_contents('php://input'), true);
         validateFields($data, ['product_id']);

         updateProduct($data['product_id'],$data);
    }



}else{
    jsonResponse("failed","invalid request method ",400);
}
?>