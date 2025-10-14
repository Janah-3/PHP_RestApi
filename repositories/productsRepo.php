<?php
require_once __DIR__ ."/../Config/connection.php";
require_once __DIR__ . '/../helpers/Validator.php';

function GetAllProducts() {


    try{
    global $connect;

    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = max(1, (int)($_GET['limit'] ?? 10));
    $search = $_GET['search'] ?? "";
    $cat_id = $_GET['cat_id'] ?? "";
    $orderby = $_GET['order_by'] ?? "product_id";
    
    $offset = ($page - 1) * $limit;

   
    $where = "is_deleted = 0";
    $params = [];

    if (!empty($search)) {
        $where .= " AND (name LIKE :search OR description LIKE :search)";
        $params[':search'] = "%$search%";
        $params[':search'] = "%$search%";
    }

    if (!empty($cat_id) && is_numeric($cat_id)) {
        $where .= " AND category_id = :cat_id";
        $params[':cat_id'] = $cat_id;
    }

    
    $allowed_order = ['product_id', 'name', 'price', 'created_at'];
    if (!in_array($orderby, $allowed_order)) {
        $orderby = "product_id";
    }

   
    $countStmt = $connect->prepare("SELECT COUNT(*) FROM products WHERE $where");
    $countStmt->execute($params);
    $totalRecords = $countStmt->fetchColumn();
    $totalPages = ceil($totalRecords / $limit);

    
    $sql = "SELECT * FROM products WHERE $where ORDER BY $orderby LIMIT $limit OFFSET $offset";

    $stmt = $connect->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    jsonResponse("success", "Products retrieved successfully", 200, [
        "page" => $page,
        "limit" => $limit,
        "total_records" => $totalRecords,
        "total_pages" => $totalPages,
        "data" => $products
    ]);
 }catch(PDOException $error){
    jsonResponse("error","Database error occurred".$error,500);
 }
}

function GetProductById($product_id){
    global $connect;

 if (!is_numeric($product_id) || $product_id <= 0) {
            jsonResponse("failed", "Invalid product ID", 400);
           
    }
       try {
    $GetProduct=$connect->prepare("select * from products where product_id = :product_id AND is_deleted=0");
    $GetProduct->execute([':product_id'=> $product_id]);
    $product = $GetProduct->fetch(PDO::FETCH_ASSOC);
    
    if($product==null){
        jsonResponse("failed","product is not found",404);
    }else{
        
        return $product;
    }

}catch(PDOException $E){
 jsonResponse("error", "Unable to retrieve product".$E, 500);
}
}

function AddProduct($name, $description, $price, $categoryid, $image)
{
    global $connect;

    $imageurl = validateAndUploadImage($image);

    $tokendata = Is_Authenticated();
    $created_by = $tokendata->user_id;

    $nowDateTime = date('Y-m-d H:i:s');

    try {

        $insert = $connect->prepare("
            INSERT INTO products 
            (name, description, price, category_id, image_url, is_deleted, created_by, created_at, updated_at)
            VALUES 
            (:name, :description, :price, :category_id, :image_url, 0, :created_by, :created_at, :updated_at)
        ");

        
        $insert->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':category_id' => $categoryid,
            ':image_url' => $imageurl,
            ':created_by' => $created_by,
            ':created_at' => $nowDateTime,
            ':updated_at' => $nowDateTime
        ]);

      
        jsonResponse("success", "Product added successfully", 201, [
            "product_name" => $name,
            "image_url" => $imageurl
        ]);

    } catch (PDOException $e) {
        jsonResponse("error", $e->getMessage(), 500);
    }
}

function updateProduct($product_id, $data) {
    global $connect;
    
    try {
        $allowedFields = ['name', 'description', 'price','category_id'];
        $modifiedCoulmns = [];
        $params = [':product_id' => $product_id];

        if(isset($data['category_id'])){
        $checkIfCatExists=$connect->prepare("select count(*) from categories where id = :category_id");
        $checkIfCatExists->execute([':category_id' => $data['category_id']]);
       $num = $checkIfCatExists->fetchColumn();

        if($num==0){
            jsonResponse('failed','category does not exist',404);
        }
    }

        
        GetProductById($product_id);
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $modifiedCoulmns[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        
      
        if (empty($modifiedCoulmns)) {
            jsonResponse("error", "No fields to update", 400);
        }
        
       
        $modifiedCoulmns[] = "updated_at = NOW()";
        
       
        $setClause = implode(', ', $modifiedCoulmns);
        $update =$connect->prepare( "UPDATE products SET $setClause WHERE product_id = :product_id AND is_deleted = 0");
        $update->execute($params);
        
        if ($update->rowCount() > 0) {
            jsonResponse("success", "Product updated successfully", 200);
        } else {
            jsonResponse("error", "Product not found or no changes made", 404);
        }
        
    } catch (PDOException $e) {
        error_log("Update error: " . $e->getMessage());
        jsonResponse("error", "Database error occurred", 500);
    }
}



function DeleteProduct($product_id){
    global $connect;

    try{

    $product = GetProductById($product_id);

    

    $update=$connect->prepare("update products set is_deleted = 1 where product_id = :product_id");
    $update->execute([':product_id'=> $product_id]);
    $is_deleted = $update->rowCount();

    if($is_deleted>0){
        jsonResponse("success","product deleted successfully",200);
    }else{
        jsonResponse("failed","couldn't delete the product",400);
    }

   
 } catch (PDOException $e) {
        
        jsonResponse("error", "Database error occurred".$e, 500);
    }
}
?>