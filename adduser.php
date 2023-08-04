<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');
$_SESSION['table'] = 'users';
$_SESSION['redirect_to'] = 'adduser.php';


$show_table = 'users';
$users = include('database/show.php');

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
                                <form action="database/add.php" method="POST" class="addForm">
                                   
                                    <div class="addFormInputContainer">
                                        <label for="first_name">Keresztnév</label>
                                        <input type="text" class="addFormInput" id="first_name" name="first_name"/>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="last_name">Vezetéknév</label>
                                        <input type="text" class="addFormInput" id="last_name" name="last_name"/>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="email">Email</label>
                                        <input type="email" class="addFormInput" id="email" name="email"/>
                                    </div>
                                    <div class="addFormInputContainer">
                                        <label for="password">Jelszó</label>
                                        <input type="password" class="addFormInput" id="password" name="password" autocomplete="off"/>
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