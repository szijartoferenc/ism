<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');
$_SESSION['table'] = 'suppliers';
$_SESSION['redirect_to'] = 'addsupplier.php';

$user = $_SESSION['user'];


?>



<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Kezelőfelület Beszállítók - Készletnyilvántartó rendszer </title>
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
                            <h1 class="headerSection"><i class="fa fa-plus"></i>Beszállítók rögzítése</h1>
                            <div id="addUserFormContainer">
                                <form action="database/add.php" method="POST" class="addForm" enctype="multipart/form-data">
                                    <div class="addFormInputContainer">
                                        <label for="supplier_name">Beszállító neve</label>
                                        <input type="text" class="addFormInput" placeholder="Írja be a beszállító nevét" id="supplier_name" name="supplier_name"/>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="supplier_location">Leírás</label>
                                        <input class="addFormInput" placeholder="Írja be a beszállító helyszínét" id="supplier_location" name="supplier_location">

                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="email">Email</label>
                                        <input class="addFormInput" placeholder="Írja be a beszállító email címét" id="email" name="email">

                                    </div>
                                                                                                                                          
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