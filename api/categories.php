<?php
require_once __DIR__ . '/../repositories/categoriesRepo.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

$userData =Is_Authenticated();
if ($userData->role != "admin" && $userData->role != "editor") jsonResponse('error', 'Access denied', 403);

if($_SERVER['REQUEST_METHOD'] =='GET')
{
   GetAllCategories();

}else if($_SERVER['REQUEST_METHOD'] =='POST'){

    $data=json_decode(file_get_contents("php://input"),true);
    validateFields($data, ['name']);
    AddCategory($data);

}else if($_SERVER['REQUEST_METHOD'] =='PATCH'){

    if(isset($_GET['delete'])){
      if ($userData->role != "admin") jsonResponse('error', 'Access denied', 403);
      $data=json_decode(file_get_contents("php://input"),true);
      validateFields($data,['category_id']);

      deleteCategory($data['category_id']);

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