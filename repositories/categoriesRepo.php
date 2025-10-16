<?php
require_once __DIR__ ."/../Config/connection.php";
require_once __DIR__ . '/../helpers/Validator.php';

function GetAllCategories(){

    try{
    global $connect;
    $GetCategories=$connect->prepare("select * from categories");
    $GetCategories->execute();
    $categories= $GetCategories->fetchAll(pdo::FETCH_ASSOC);
  
    jsonResponse("success", "categories retrived successfully",200,$categories);

    }catch(PDOException $error){
        jsonResponse("error","database error occured ".$error,500);
    }
}

function GetCategoryById($category_id){
    global $connect;

 if (!is_numeric($category_id) || $category_id <= 0) {
            jsonResponse("failed", "Invalid category ID", 400);
           
    }
       try {
    $GetCategory=$connect->prepare("select * from categories where id = :category_id AND is_deleted=0");
    $GetCategory->execute([':category_id'=> $category_id]);
    $category = $GetCategory->fetch(PDO::FETCH_ASSOC);
    
    if($category==null){
        jsonResponse("failed","category is not found",404);
    }else{
        
        return $category;
    }

}catch(PDOException $E){
 jsonResponse("error", "Unable to retrieve category".$E, 500);
}
}


function AddCategory($data){
    global $connect;

    try{
    $insert=$connect->prepare("insert into categories (name, description, is_deleted) values (:name, :description, 0)");
    $insert->execute([':name' => $data['name'] , ':description' => $data['description']]);

    jsonResponse("success","category added successfully",201);
    }catch(PDOException $error){
        jsonResponse("error", $error,500);
    }

}

function deleteCategory($category_id){
    global $connect;
    GetCategoryById($category_id);

    $SoftDelete=$connect->prepare("update categories set is_deleted = 1 where id = :category_id");
    $SoftDelete->execute([':category_id'=> $category_id]);

     $is_deleted = $SoftDelete->rowCount();

    if($is_deleted>0){
        jsonResponse("success","category deleted successfully",200);
    }else{
        jsonResponse("failed","couldn't delete the category",400);
    }
}


function updateCategory($category_id, $data) {
    global $connect;
    
    try {
        $allowedFields = ['name', 'description'];
        $modifiedCoulmns = [];
        $params = [':category_id' => $category_id];

        
       GetCategoryById($category_id);
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $modifiedCoulmns[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
      
        if (empty($modifiedCoulmns)) {
            jsonResponse("error", "No fields to update", 400);
        }
        
       
        
       
        $setClause = implode(', ', $modifiedCoulmns);
        $update =$connect->prepare( "UPDATE categories SET $setClause WHERE id = :category_id AND is_deleted = 0");
        $update->execute($params);
        
        if ($update->rowCount() > 0) {
            jsonResponse("success", "category updated successfully", 200);
        } else {
            jsonResponse("error", "category not found or no changes made", 404);
        }
        
    } catch (PDOException $e) {
        jsonResponse("error", "Database error occurred", 500);
    }
}





?>