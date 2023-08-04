<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');
$_SESSION['table'] = 'products';
$_SESSION['redirect_to'] = 'addproduct.php';


//Get all products
$show_table = 'products';
$products = include('database/show.php');
$products = json_encode($products);

?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> ISM Kezelőfelület - Készletnyilvántartó rendszer </title>
        <?php include('./partials/header.php'); ?>
    </head>
    <body>
        <div id="dashboardMainContainer">
        <?php include('partials/sidebar.php')  ?>
            <div class="dashboardContentContainer" id="dashboardContentContainer">
            <?php include('partials/topnav.php')  ?>
                <div class="dashboardContent">
                    <div class="dashboardContentMain">
                        <div class="row">
                            <div class="column column-10">
                                <h1 class="headerSection"><i class="fa fa-plus"></i>Rendelés rögzítése</h1>

                                <div>
                                    <form action="database/saveorder.php" method="POST">
                                        
                                        <div class="alignRight">
                                        <button type="button" class="orderBtn orderProductBtn" id="orderProductBtn">Új termékrendelés hozzáadása</button>
                                        </div>
                                        
                                        <div id="orderProductLists">
                                            <p id="noData" style="#9f9f9f;">Nincs kiválasztva termék</p>
                                        </div>
                                        
                                        <div  class="alignRight marginTop20">
                                        <button type="submit" class="orderBtn submitOrderProductBtn"> Rendelés küldése</button>
                                        </div>

                                    </form>
                                </div>
                                    <?php
                                        if (isset($_SESSION['response'])) {
                                            $response_message = $_SESSION['response']['message'];
                                            $is_success = $_SESSION['response']['success'];
                                
                                    ?>
                                    <div class="responseMessage">
                                        <p class="responseMessage <=? $is_success ? 'responseMessage_success' : 'responseMessage_error' ?>">
                                            <?= $response_message ?>
                                        </p>
                                    </div>
                                    <?php unset($_SESSION['response']); } ?>
                                
                            </div>
                        
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- JavaScript -->
        <?php include('./partials/scripts.php'); ?>
    
        <script>
            let products = <?php print $products ?>;

            function scriptO(){
                let vm = this;
                let counter = 0;
                
                let productOptions = '\
                    <div>\
                        <label for="product_name">Termék neve</label>\
                        <select name="products[]"  class="productNameSelect" id="product_name">\
                            <option value="">Termék kiválasztása</option>\
                            INSERTPRODUCTHERE\
                        </select>\
                        <button class="btn removeOrderBtn">Törlés</button>\
                    </div>';

                
                    
                    this.initialize = function() {
                    this.registerEvents();
                    this.renderProductOptions();
                },

                this.renderProductOptions =function(){
                let optionHtml = '';
                products.forEach((product)=> {
                    optionHtml += '<option value="'+ product.id +'">'+ product.product_name +'</options>'
                });

                productOptions = productOptions.replace('INSERTPRODUCTHERE',  optionHtml);
                },

                this.registerEvents = function() {
                    document.addEventListener('click', function(e) {
                        targetElement = e.target;
                        classList = targetElement.classList;

                        //add new product order event
                        if (targetElement.id === 'orderProductBtn') {
                            document.getElementById('noData').style.display = 'none';
                            let orderProductListsContainer =document.getElementById('orderProductLists');
                            
                            orderProductLists.innerHTML +='\
                                <div class="orderProductRow">\
                                    '+ productOptions +'\
                                    <div class="supplierRows" id="supplierRows_'+ counter +'" data-counter="'+ counter +'"></div>\
                                </div>';                 
                            
                                counter++;
                        }

                        //REMOVE  button is clicked
                        if (targetElement.classList.contains('removeOrderBtn')) {
                            let orderRow = targetElement
                            .closest('.orderProductRow');
                            
                            //Remove element
                            orderRow.remove();
                            console.log(orderRow);
                        }
                        
                    });

                    document.addEventListener('change', function(e) {
                        targetElement = e.target;
                        classList = targetElement.classList;

                        //add supplier row on product option change
                        if (classList.contains('productNameSelect')) {
                            let pid = targetElement.value; 

                            let counterId = targetElement
                            .closest('.orderProductRow')
                            .querySelector('.supplierRows')
                            .dataset.counter;
                            
                            $.get('database/get-product-suppliers.php', {id: pid}, function(suppliers){
                            vm.renderSupplierRows(suppliers, counterId); // Pass counterId to the function
                            }, 'json');  
                        }
                    });
                },
                this.renderSupplierRows = function(suppliers, counterId) { // Accept counterId as a parameter
                let supplierRows = '';

                    suppliers.forEach((supplier) => {
                        supplierRows += '\
                        <div class="row">\
                            <div style="width:50%;">\
                                <p class="supplierName">'+ supplier.supplier_name +'</p>\
                            </div>\
                            <div style="width:50%;">\
                                <label for="quantity">Mennyiség</label>\
                                <input type="number" name="quantity['+ counterId +']['+ supplier.id +']" placeholder="Írja be a mennyiséget..." class="orderProductQty" id="quantity"/>\
                            </div>\
                        </div>';
                });

                //Append to container
                let supplierRowContainer = document.getElementById('supplierRows_' + counterId);
                supplierRowContainer.innerHTML = supplierRows;
                }
            }    
            (new scriptO()).initialize();
        </script>
            
    </body>
</html>