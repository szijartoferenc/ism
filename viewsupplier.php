<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');

$show_table = 'suppliers';
$suppliers = include('database/show.php');

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Beszállítók megtekintése - Készletnyilvántartó rendszer </title>
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
                            <h1 class="headerSection"><i class="fa fa-list"></i>Beszállító lista</h1>
                            <div class="sectionContent">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Beszállító neve</th>
                                                <th>Beszállító helyszíne</th>
                                                <th>Kapcsolat részletek</th>
                                                <th>Termékek</th>
                                                <th>Készítette</th>
                                                <th>Készült</th>
                                                <th>Feltöltve</th>
                                                <th>Művelet</th>                                             
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($suppliers as $index=> $supplier) { ?>
                                            <tr>
                                                <td><?=$index + 1 ?></td>
                                                <td class="supplierName"><?=$supplier['supplier_name'] ?></td>
                                                <td class="supplierLocation"><?=$supplier['supplier_location'] ?> </td>
                                                <td class="contactDetails"><?=$supplier['email'] ?></td>
                                                <td>
                                                    <?php 

                                                            $product_list = '-';

                                                            $sid= $supplier['id'];
                                                            $stmt = $conn->prepare("SELECT product_name FROM products, productsuppliers WHERE productsuppliers.supplier=$sid AND productsuppliers.product = products.id");
                                                            $stmt->execute();
                                                            $row = $stmt->fetchAll(PDO::FETCH_ASSOC); 

                                                            if ($row) {
                                                                $product_arr = array_column($row, 'product_name');
                                                                $product_list = '<li>' . implode("</li><li>", $product_arr);
                                                            }

                                                            echo $product_list;

                                                                                                            
                                                        ?>                                              
                                                </td>
                                                <td>
                                                <?php 
                                                    $uid=$supplier['created_by'];
                                                    $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);  
                                                    
                                                    $created_by_name = $row['first_name']. ' '. $row['last_name'];
                                                    echo $created_by_name;
                                                 
                                                 ?>   
                                                </td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($supplier['created_at'])) ?></td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($supplier['updated_at'])) ?></td>
                                                <td>
                                                    <a href="" class="updateSupplier" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-pencil"></i>Szerkesztés</a>|
                                                    <a href="" class="deleteSupplier"  data-sname="<?= $supplier['supplier_name'] ?>" data-sid="<?= $supplier['id'] ?>"><i class="fa fa-trash"></i>Törlés</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($suppliers) ?> Beszállító</p>
                                </div>
                            </div>
                           
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php 
    include('./partials/scripts.php'); 
     
    $show_table = 'products';
    $products = include('database/show.php');

        $products_arr = [];

        foreach ($products as $product) {

            $products_arr[$product['id']] = $product['product_name'];
        }

        $products_arr = json_encode($products_arr);
         
    ?>

<script>
    var productsList = <?php print $products_arr ?>;
        
    function ScriptP() {
    vm = this;
    
    this.initialize = function() {
        this.registerEvents();
    };

    this.registerEvents = function() {
        document.addEventListener('click', function(e) {
        targetElement = e.target;
        classList = targetElement.classList;

        if (classList.contains('deleteSupplier')) {
            e.preventDefault();

            sId = targetElement.dataset.sid;
            supplierName = targetElement.dataset.sname;

            BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_DANGER,
            title: 'Beszállító törlése',
            message: 'Biztosan törli <b>' + supplierName + '</b>?',
            callback: function(isDelete) {
                if (isDelete) {
                $.ajax({
                    method: 'POST',
                    data: {
                    id: sId,
                    table: 'suppliers'
                    },
                    url: 'database/delete.php',
                    dataType: 'json',
                    success: function(data) {
                    message = data.success ? supplierName + ' sikeresen törölve' : 'hiba a feldolgozás során';

                    if (data.success) {
                        BootstrapDialog.alert({
                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_SUCCESS,
                        message: message,
                        callback: function() {
                            if (data.success) location.reload();
                        }
                        });

                    } else {
                        BootstrapDialog.alert({
                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                        message: message,
                        callback: function() {
                            if (data.success) location.reload();
                        }
                        });
                    }
                    }
                });
                } else {
                alert('törölve');
                }
            }
            });
        }

        if (classList.contains('updateSupplier')) {
            e.preventDefault(); // this prevents the default notification

            sId = targetElement.dataset.sid;
            vm.showEditDialog(sId);

            document.addEventListener('submit', function(e) {
            e.preventDefault();
            targetElement = e.target;

            if (targetElement.id === 'editSupplierForm') {
                vm.saveUpdateData(targetElement);
            }
            });
        }
        });
    };

    this.saveUpdateData = function(form) {
        $.ajax({
        method: 'POST',
        data: {
            supplier_name:document.getElementById('supplier_name').value,
            supplier_location:document.getElementById('supplier_location').value,
            email:document.getElementById('email').value,
            products: $('#products').val(),
            sid:document.getElementById('sid').value,
        },
        url: 'database/update-supplier.php',
        dataType: 'json',
        success: function(data) {
            BootstrapDialog.alert({
            type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
            message: data.message,
            callback: function() {
                if (data.success) location.reload();
            }
            });
        }
        });
    };

    this.showEditDialog = function(id) {
        $.get('database/get-supplier.php', { id: id }, function(supplierDetails) {
        let curProducts = supplierDetails['products'];
        let productOptions = '';

        for (const [pId, pName] of Object.entries(productsList)) {
            selected = curProducts.indexOf(pId) > -1 ? 'selected' : '';
            productOptions += "<option "+ selected +" value='"+ pId +"'>"+ pName +"</option>";
        }

        BootstrapDialog.confirm({
            title: 'Feltöltés <strong> ' + supplierDetails.supplier_name + '</strong> ',
            message:'<form action="database/add.php" method="POST" enctype="multipart/form-data" id="editSupplierForm">\
                <div class="addFormInputContainer">\
                <label for="supplier_name">Beszállító neve</label>\
                <input type="text" class="addFormInput" placeholder="Írja be a beszállító nevét" id="supplier_name" value="' + supplierDetails.supplier_name + '" name="supplier_name"/>\
                </div>\
                <div class="addFormInputContainer">\
                <label for="supplier_location">Beszállító helyszíne</label>\
                <input type="text" class="addFormInput" placeholder="Írja be a beszállító helyét" id="supplier_location" value="' + supplierDetails.supplier_location + '" name="supplier_location"/>\
                </div>\
                <div class="addFormInputContainer">\
                <label for="email">Beszállító email</label>\
                <input type="text" class="addFormInput" placeholder="Írja be a beszállító email címét..." id="email" value="' + supplierDetails.email + '" name="email"/>\
                </div>\
                <div class="addFormInputContainer">\
                <label for="products">Termékek</label>\
                <select name="products[]" id="products" multiple="">\
                    <option value="">Válassza ki a terméket!</option>\
                    ' +     productOptions + '\
                </select>\
            </div>\
                <input type="hidden" name="sid" id="sid" value="' + supplierDetails.id + '"/>\
                <input type="submit" value="submit" id="editSupplierSubmitBtn" class="hidden" />\
            </form>\
            ',
            callback: function(isUpdate) {
            if (isUpdate) {
                document.getElementById('editSupplierSubmitBtn').click();
            }
            }
        });
        }, 'json');
    };

    this.initialize();
    }

    let myscript = new ScriptP();

</script>
 
</body>
</html>