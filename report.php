<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:login.php');

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ISM Kezelőfelület - Készletnyilvántartó rendszer </title>
    <?php include('partials/header.php'); ?>
</head>   
</head>
<body>
    <div id="dashboardMainContainer">
        <?php include('partials/sidebar.php') ?>
        <div class="dashboardContentContainer" id="dashboardContentContainer">
            <?php include('partials/topnav.php') ?>
            <div class="reportsContainer">
                <div class="reportTypeContainer">
                    <div class="reportType">
                        <p>Termékek exportálása</p>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=product" class="reportExportBtn">EXCEL</a>
                            <a href="database/report_pdf.php?report=product" target="_blank" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                    <div class="reportType">
                        <p>Beszállítók exportálása</p>
                        <div class="alignRight">
                            <a href="database/report_csv.php?report=supplier" class="reportExportBtn">EXCEL</a>
                            <a href="" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                            
                </div>
                <div class="reportTypeContainer">
                    <div class="reportType">
                        <p>Szállítmányok exportálása</p>
                        <div class="alignRight">
                            <a href="database/report_csv.php" class="reportExportBtn">EXCEL</a>
                            <a href="" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                    <div class="reportType">
                        <p>Termék megrendelések exportálása</p>
                        <div class="alignRight">
                            <a href="database/report_csv.php" class="reportExportBtn">EXCEL</a>
                            <a href="" class="reportExportBtn">PDF</a>
                        </div>
                    </div>
                            
                </div>

            </div>           
        </div>
    </div>
     <!-- JavaScript -->
     <?php include('partials/scripts.php'); ?>
       
</body>
</html>