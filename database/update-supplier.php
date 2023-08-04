<?php
$supplier_name = isset($_POST['supplier_name']) ? $_POST['supplier_name'] : '';
$supplier_location = isset($_POST['supplier_location']) ? $_POST['supplier_location'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$products = isset($_POST['products']) ? $_POST['products'] : '';

$supplier_id = $_POST['sid'];

 
 try {
    $sql = "UPDATE suppliers 
            SET 
            supplier_name=?, supplier_location=?, email=? 
            WHERE id=?";

            include('connection.php');  
            $stmt = $conn->prepare($sql);
            $stmt->execute( [$supplier_name, $supplier_location, $email, $supplier_id]);

            //Delete the old values 
            $sql= "DELETE FROM productsuppliers WHERE supplier=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$supplier_id]);

             // Loop through the suppliers and add the records
             //Get suppliers
             $products = isset($_POST['products']) ? $_POST['products'] : [];
             foreach ($products as $product) {
                $supplier_data = [
                    'supplier_id' => $supplier_id,
                    'product_id' => $product,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $sql = "INSERT INTO productsuppliers (supplier, product, created_at, updated_at) VALUES (:supplier_id, :product_id, :created_at, :updated_at)";

                $stmt = $conn->prepare($sql);
                $stmt->execute($supplier_data);      
            }


        $response = [
            'success'=> true,
            'message'=> "<strong>$supplier_name</strong> sikeresen módosítva a rendszerben"
        ];
 } catch (Exception $e) {
    $response = [
        'success'=> false,
        'message'=> "Hiba a feldolgozás során"
        ];
 }
echo json_encode($response);

