<?php

$product_name = $_POST['product_name'];
$description = $_POST['description'];
$pid = $_POST['pid'];

 //upload or move the file to our directory
 $target_dir = "../uploads/products/";

 $file_name_value = NULL;
 $file_data = $_FILES['img'];

 if ($file_data['tmp_name'] !== '') {
    $file_name = $file_data['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name = 'product-' . time() . '.' . $file_ext;

    $check = getimagesize($file_data['tmp_name']);
    $file_name_value = NULL;

    //move the file
    if ($check) {
        if (move_uploaded_file($file_data['tmp_name'], $target_dir .$file_name)) {
            //Save the file_name to the database
            $file_name_value = $file_name;
        }         
        
    }
 }
 
 //Save the upload
 try {
    $sql = "UPDATE products 
            SET 
            product_name=?, description=?, img=? 
            WHERE id=?";

            include('connection.php');  
            $stmt = $conn->prepare($sql);
            $stmt->execute( [$product_name, $description, $file_name_value, $pid]);

            //Delete the old values 
            $sql= "DELETE FROM productsuppliers WHERE product=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid]);

             // Loop through the suppliers and add the records
             //Get suppliers
             $suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
             foreach ($suppliers as $supplier) {
                $supplier_data = [
                    'supplier_id' => $supplier,
                    'product_id' => $pid,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO productsuppliers (supplier, product, created_at, updated_at) VALUES (:supplier_id, :product_id, :created_at, :updated_at)";

                $stmt = $conn->prepare($sql);
                $stmt->execute($supplier_data);      
            }


        $response = [
            'success'=> true,
            'message'=> "<strong>$product_name</strong> sikeresen módosítva a rendszerben"
        ];
 } catch (Exception $e) {
    $response = [
        'success'=> false,
        'message'=> "Hiba a feldolgozás során"
        ];
 }
echo json_encode($response);

