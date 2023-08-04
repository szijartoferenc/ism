<?php
$servername = 'localhost';
$username= 'root';
$password= '';

//connection to the database
try {
    $conn= new PDO("mysql:host=$servername;dbname=ism", $username, $password);

    //set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'Connected successfully';
} catch (\Exception $e) {
   $error_message = $e->getMessage();
}

?>