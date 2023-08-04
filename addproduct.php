<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');
$_SESSION['table'] = 'products';
$_SESSION['redirect_to'] = 'addproduct.php';

$user = $_SESSION['user'];


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
                            <h1 class="headerSection"><i class="fa fa-plus"></i>Felhasználó rögzítése</h1>
                            <div id="addUserFormContainer">
                                <form action="database/add.php" method="POST" class="addForm" enctype="multipart/form-data">
                                    <div class="addFormInputContainer">
                                        <label for="product_name">Termék neve</label>
                                        <input type="text" class="addFormInput" placeholder="Írja be a termék nevét" id="product_name" name="product_name"/>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="description">Leírás</label>
                                        <textarea class="addFormInput productTextAreaInput" placeholder="Írja be a termék leírását" id="description" name="description"></textarea>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="suppliersSelect">Beszállítók</label>
                                        <select name="suppliers[]" id="suppliersSelect"  multiple="">
                                            <option value="">Válassza ki a beszállítót!</option>
                                                    <?php 
                                                     $show_table = 'suppliers';
                                                     $suppliers = include('database/show.php');
                                                     
                                                     foreach ($suppliers as $supplier) {
                                                        echo "<option value='". $supplier['id'] . "'>".$supplier['supplier_name']."</option>";
                                                     }
                                                    ?>
                                                                                       
                                        </select>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="img">Termék képe</label>
                                        <input type="file"  name="img" id="img"/>
                                    </div>
                                                                      
                                    <input type="hidden" name="table" value="users">
                                    <button type="submit" class="addUserFormBtn"><i class="fa fa-plus"></i>Hozzáadás</button>
                                </form>
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
    </div>
    <!-- JavaScript -->
    <?php include('./partials/scripts.php'); ?>
   

         
</body>
</html>