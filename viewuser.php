<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');
$_SESSION['table'] = 'users';
$user = $_SESSION['user'];

$show_table = 'users';
$users = include('database/show.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Kezelőfelület - Készletnyilvántartó rendszer </title>
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
                            <h1 class="headerSection"><i class="fa fa-list"></i>Felhasználó lista</h1>
                            <div class="sectionContent">
                                <div class="users">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Keresztnév</th>
                                                <th>Vezetéknév</th>
                                                <th>Email</th>
                                                <th>Készült</th>
                                                <th>Feltöltve</th>
                                                <th>Művelet</th>                                             
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($users as $index=> $user) { ?>
                                            <tr>
                                                <td><?=$index + 1 ?></td>
                                                <td class="firstName"><?=$user['first_name'] ?></td>
                                                <td class="lastName"><?=$user['last_name'] ?> </td>
                                                <td class="email"><?=$user['email'] ?></td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($user['created_at'])) ?></td>
                                                <td><?= date('Y M d @ h:i:s A', strtotime ($user['updated_at'])) ?></td>
                                                <td>
                                                    <a href="" class="updateUser" data-userid="<?= $user['id'] ?>"><i class="fa fa-pencil"></i>Szerkesztés</a>
                                                    <a href="" class="deleteUser" data-userid="<?= $user['id'] ?>", data-fname="<?= $user['first_name'] ?>", data-lname="<?= $user['last_name'] ?>"><i class="fa fa-trash"></i>Törlés</a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <p class="userCount"><?= count($users) ?> Felhasználó</p>
                                </div>
                            </div>
                           
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php include('partials/scripts.php'); ?>
    <?php include('partials/scriptuser.php'); ?>                                        
     
</body>
</html>