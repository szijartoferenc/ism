<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');

$show_table = 'products';
$products = include('database/show.php');

?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Termék megtekintése - Készletnyilvántartó rendszer </title>
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
                            <h1 class="headerSection"><i class="fa fa-list"></i>Terméklista</h1>
                            <div class="sectionContent">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Kép</th>
                                                <th>Terméknév</th>
                                                <th>Készlet</th>
                                                <th width="20%">Leírás</th>
                                                <th width="15%">Beszállítók</th>
                                                <th>Készítette</th>
                                                <th>Készült</th>
                                                <th>Feltöltve</th>
                                                <th>Művelet</th>                                             
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($products as $index=> $product) { ?>
                                            <tr>
                                                <td><?=$index + 1 ?></td>
                                                <td class="productImages">
                                                    <img class="productImages" src="uploads/products/<?=$product['img'] ?>" alt=""/> </td>
                                                <td class="productName"><?=$product['product_name'] ?> </td>
                                                <td class="productStock"><?= number_format($product['stock']) ?> </td>
                                                <td class="productDescription"><?=$product['description'] ?></td>
                                                <td class="productDescription">
                                                <?php 

                                                    $supplier_list = '-';

                                                    $pId=$product['id'];
                                                    $stmt = $conn->prepare("SELECT supplier_name FROM suppliers, productsuppliers WHERE productsuppliers.product =$pId AND productsuppliers.supplier = suppliers.id");
                                                    $stmt->execute();
                                                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC); 
                                                    
                                                    if ($row) {
                                                        $supplier_arr = array_column($row, 'supplier_name');
                                                        $supplier_list = '<li>' . implode("</li><li>", $supplier_arr);
                                                    }

                                                    echo $supplier_list;
                                                    
                                                                                                    
                                                 ?>   
                                            
                                                </td>
                                                <td>
                                                <?php 
                                                    $uid=$product['created_by'];
                                                    $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                                    $stmt->execute();
                                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);  
                                                    
                                                    $created_by_name = $row['first_name']. ' '. $row['last_name'];
                                                    echo $created_by_name;
                                                 
                                                 ?>   
                                                </td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($product['created_at'])) ?></td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($product['updated_at'])) ?></td>
                                                <td>
                                                    <a href="" class="updateProduct" data-pid="<?= $product['id'] ?>"><i class="fa fa-pencil"></i>Szerkesztés</a>|
                                                    <a href="" class="deleteProduct"  data-pname="<?= $product['product_name'] ?>" data-pid="<?= $product['id'] ?>"><i class="fa fa-trash"></i>Törlés</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($products) ?> Termék</p>
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
     
    $show_table = 'suppliers';
    $suppliers = include('database/show.php');

        $supplier_arr = [];

        foreach ($suppliers as $supplier) {

            $supplier_arr[$supplier['id']] = $supplier['supplier_name'];
        }

        $supplier_arr = json_encode($supplier_arr);
         
    ?>

<script>
var suppliersList = <?php print $supplier_arr ?>;
    
function ScriptP() {
  vm = this;
  
  this.initialize = function() {
    this.registerEvents();
  };

  this.registerEvents = function() {
    document.addEventListener('click', function(e) {
      targetElement = e.target;
      classList = targetElement.classList;

      if (classList.contains('deleteProduct')) {
        e.preventDefault();

        pId = targetElement.dataset.pid;
        pName = targetElement.dataset.pname;

        BootstrapDialog.confirm({
          type: BootstrapDialog.TYPE_DANGER,
          title: 'Termék törlése',
          message: 'Biztosan törli <b>' + pName + '</b>?',
          callback: function(isDelete) {
            if (isDelete) {
              $.ajax({
                method: 'POST',
                data: {
                  id: pId,
                  table: 'products'
                },
                url: 'database/delete.php',
                dataType: 'json',
                success: function(data) {
                  message = data.success ? pName + ' sikeresen törölve' : 'hiba a feldolgozás során';

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

      if (classList.contains('updateProduct')) {
        e.preventDefault(); // this prevents the default notification

        pId = targetElement.dataset.pid;
        vm.showEditDialog(pId);

        document.addEventListener('submit', function(e) {
          e.preventDefault();
          targetElement = e.target;

          if (targetElement.id === 'editProductForm') {
            vm.saveUpdateData(targetElement);
          }
        });
      }
    });
  };

  this.saveUpdateData = function(form) {
    let formData = new FormData(form);
    $.ajax({
      method: 'POST',
      data: formData,
      url: 'database/update-product.php',
      processData: false,
      contentType: false,
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
    $.get('database/get-product.php', { id: id }, function(productDetails) {
      let curSuppliers = productDetails['suppliers'];
      let supplierOption = '';

      for (const [supId, supName] of Object.entries(suppliersList)) {
        selected = curSuppliers.indexOf(supId) > -1 ? 'selected' : '';
        supplierOption += "<option "+ selected +" value='"+ supId +"'>"+ supName +"</option>";
      }

      BootstrapDialog.confirm({
        title: 'Feltöltés <strong> ' + productDetails.product_name + '</strong> ',
        message:'<form action="database/add.php" method="POST" enctype="multipart/form-data" id="editProductForm">\
            <div class="addFormInputContainer">\
              <label for="product_name">Termék neve</label>\
              <input type="text" class="addFormInput" placeholder="Írja be a termék nevét" id="product_name" value="' + productDetails.product_name + '" name="product_name"/>\
            </div>\
            <div class="addFormInputContainer">\
              <label for="suppliersSelect">Beszállítók</label>\
              <select name="suppliers[]" id="suppliersSelect" multiple="">\
                <option value="">Válassza ki a beszállítót!</option>\
                ' +     supplierOption+ '\
              </select>\
          </div>\
          <div class="addFormInputContainer">\
              <label for="description">Leírás</label>\
              <textarea class="addFormInput productTextAreaInput" placeholder="Írja be a termék leírását" id="description" name="description">' + productDetails.description + '</textarea>\
          </div>\
          <div class="addFormInputContainer">\
              <label for="product_name">Termék képe</label>\
              <input type="file" name="img"/>\
          </div>\
            <input type="hidden" name="pid" value="' + productDetails.id + '"/>\
            <input type="submit" value="submit" id="editProductSubmitBtn" class="hidden" />\
          </form>\
        ',
        callback: function(isUpdate) {
          if (isUpdate) {
            document.getElementById('editProductSubmitBtn').click();
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