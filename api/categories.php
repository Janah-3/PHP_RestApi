<?php
require_once __DIR__ . '/../repositories/categoriesRepo.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';




if($_SERVER['REQUEST_METHOD'] =='GET')
{
   

    if (isset($_GET['category_id'])) {
       
       $category_id = (int)$_GET['category_id'];
       $category =  GetCategoryById($category_id);
       jsonResponse("success","category retrived successfully",200,[$category]);
       
    } else {
        
       GetAllCategories();
    }


}else if($_SERVER['REQUEST_METHOD'] =='POST'){

    $data=json_decode(file_get_contents("php://input"),true);
    validateFields($data, ['name']);
    AddCategory($data);

}else if($_SERVER['REQUEST_METHOD'] =='PATCH'){

    
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
       $userData = requireRole(['admin']);
      

      if (!isset($_GET['cat_id']) || !is_numeric($_GET['cat_id'])) {
            jsonResponse('error', 'Valid category ID is required', 400);
        }
      
      deleteCategory($_GET['cat_id']);
       

    }else{
         $data=json_decode(file_get_contents("php://input"),true);
         validateFields($data,['category_id']);

          updateCategory($data['category_id'],$data);

    }
}
else{
 jsonResponse("failed","invalid request method ",400);
}
?>