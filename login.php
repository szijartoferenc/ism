<?php

//start the SESSION
session_start();
if(isset($_SESSION['user'])) header('location: dashboard.php');
$error_message='';

 if ($_POST) {
    include('database/connection.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    
    //$query hiba volt - kimaradt a WHERE így nem kapcsolódott az adatbázishoz
    $query = 'SELECT * FROM users WHERE email="'. $username .'" AND password="'. $password .'"';
    $stmt = $conn->prepare($query);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetchAll()[0];

        //Captures data of currently login user
        $_SESSION['user'] = $user;

        header('Location: dashboard.php');

    }else $error_message = 'Kérem győződjön meg a felhasználónév és jelszó helyességéről';
    
 }

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISM bejelentkezés - Készletnyilvántartó rendszer</title>
    <link rel="stylesheet" href="css/fontawesome/css/font-awesome.min.css">   
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body id="bodyLogin">
    <?php if (!empty($error_message)) {
        ?> 
         <div id="errorMessage">
            <b>Hiba:</b> <p class=""> <?= $error_message ?></p>
        </div>
        <?php
    } ?>
    
    <div class="container">
        <div class="headerLogin">
            <h1>ISM</h1>
            <h3>Készletnyilvántartó rendszer</h3>
        </div>
        <div class="bodyLogin">
            <form action="login.php" method="POSt">
                <div class="inputLoginContainer">
                    <label for="username">Felhasználónév</label>
                    <input placeholder="email cím" name="username" id="username" type="text">
                </div>
                <div class="inputLoginContainer">
                    <label for="password">Jelszó</label>
                    <input placeholder="Jelszó" name="password" type="password" id="password" autocomplete="off">
                </div>
                <div class="buttonLoginContainer">
                    <button>Bejelentkezés</button>
                </div>
            </form>

        </div>
    </div>
</body>
</html>