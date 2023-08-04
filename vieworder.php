<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');

$show_table = 'suppliers';
$suppliers = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Rendelések megtekintése - Készletnyilvántartó rendszer </title>
    <?php include('./partials/header.php'); ?>
</head>
<body>
    <div id="dashboardMainContainer">
       <?php include('partials/sidebar.php');  ?>
        <div class="dashboardContentContainer" id="dashboardContentContainer">
        <?php include('partials/topnav.php');  ?>
            <div class="dashboardContent">
                <div class="dashboardContentMain">
                    <div class="row">
                      
                        <div class="column column-12">
                            <h1 class="headerSection"><i class="fa fa-list"></i>Rendelések listája</h1>
                            <div class="sectionContent">
                                <div class="poListContainer">
                                    <?php 
                                        $stmt = $conn->prepare("SELECT product_order.id,product_order.product, products.product_name, product_order.quantity_ordered, users.first_name, 
                                                                    product_order.batch, product_order.quantity_received,users.last_name,
                                                                    suppliers.supplier_name,product_order.status, product_order.created_at
                                                                    FROM product_order, suppliers, products, users
                                                                    WHERE 
                                                                        product_order.supplier = suppliers.id
                                                                            AND 
                                                                        product_order.product = products.id
                                                                                AND 
                                                                        product_order.created_by = users.id
                                                                    ORDER BY product_order.created_at DESC
                                                                ");
                                    $stmt->execute();
                                    $purchase_orders = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                    
                                    $data = [];
                                    foreach ($purchase_orders as $purchase_order) {
                                        $data[$purchase_order['batch']][] = $purchase_order;
                                     }                                                                    
                                    ?>

                                    <?php 
                                        foreach ($data as $batch_id => $batch_pos) {
                                      
                                    ?>
                                        <div class="poList" id="container-<?php echo $batch_id ?>">
                                            <p>Tétel #:<?php echo $batch_id ?></p>
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Termék</th>
                                                        <th>Megrendelt mennyiség</th>
                                                        <th>Megérkezett mennyiség</th>
                                                        <th>Beszállító</th>
                                                        <th>Státusz</th>
                                                        <th>Megrendelő</td>
                                                        <th>Megrendelés dátuma</th>
                                                        <th>Szállítási előzmények</th>
                                                 </tr>
                                            </thead>
                                            <tbody>
                                                    <?php 
                                                        // $row_number = 1;
                                                        foreach ($batch_pos as $index => $batch_po) {
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $index + 1 ?></td>
                                                            <td class="po_product"><?php echo $batch_po['product_name']; ?></td>
                                                            <td class="po_qty_ordered"><?php echo $batch_po['quantity_ordered']; ?></td>
                                                            <td class="po_qty_received"><?php echo $batch_po['quantity_received']; ?></td>
                                                            <td class="po_qty_supplier"><?php echo $batch_po['supplier_name']; ?></td>
                                                            <td class="po_qty_status"><span class="po-badge  po-badge-<?php echo $batch_po['status']?>"><?php echo $batch_po['status']?></span></td>
                                                            <td><?php echo $batch_po['first_name'] . ' ' . $batch_po['last_name']; ?></td>

                                                            <td>
                                                                <?php echo $batch_po['created_at']; ?>
                                                                <input type="hidden" class="po_qty_row_id" value="<?php echo $batch_po['id'] ?>">
                                                                <input type="hidden" class="po_qty_productid" value="<?php echo $batch_po['product'] ?>">
                                                           </td>
                                                           <td>
                                                                <button class="addbtn addDeliveryHistory" data-id="<?php echo $batch_po['id'] ?>">Szállítások</button>
                                                           </td>
                                                        </tr>
                                                        <?php } ?>                                   
                                                                                                                
                                                    </tbody>
                                                </table>
                                                <div class="poOrderUpdateBtnContainer alignRight">
                                                    <button class="addbtn updatePoBtn" data-id="<?php echo $batch_id?>">Feltöltés</button>

                                                </div>
                                    </div>                                   
                                   <?php } ?>                                     
                                </div>                
                                
                            </div>
                           
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include('./partials/scripts.php');  ?>
    <?php include('./partials/scriptpo.php');  ?>
    
      
    
   
</body>
</html>