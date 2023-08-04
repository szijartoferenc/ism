<?php

//start the SESSION
session_start();
if(!isset($_SESSION['user'])) header('location:index.php');
$user = $_SESSION['user'];

//Get graph data - purchase order by status
include('database/po_status_pie_graph.php');

//Get graph data - supplier product count
include('database/supplier_product_bar_graph.php');

//Get graph data - delivery history per day
include('database/delivery-history.php');

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
            <div class="dashboardContent">
                <div class="dashboardContentMain">

                    <div class="col50">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                            <p class="highcharts-description">
                                Az alábbiakban a beszerzési rendelések felbontása látható státusz szerint: <br>
                                <b>Függőben lévő rendelések(pending):</b> Ezek a rendelések még nem teljesültek, és várják a feldolgozást.<br>
                                <b>Hiányos rendelések(incomplete):</b> Ezek a rendelések részben teljesültek, de még nem érkezett meg minden termék.<br>
                                <b>Teljesített rendelések(complete):</b> Ezek a rendelések már teljes mértékben teljesültek, és minden termék megérkezett.
                            </p>
                        </figure>
                    </div>
                    <div class="col50">
                        <figure class="highcharts-figure">
                            <div id="containerBarChart"></div>
                            <p class="highcharts-description">
                                <b>Az alábbiakban a beszerzési rendelések felbontása látható beszállítók szerint </b>                             
                            </p>
                        </figure>
                  </div>                  
                   
                </div>
                    <div>
                        <div id="deliveryHistory">
                           
                        </div>
                    </div>                            
            </div>
        </div>
    </div>
     <!-- JavaScript -->
     <?php include('partials/scripts.php'); ?>
     <?php include('partials/scriptchart.php'); ?>
     
     <script>
        //Kördiagram a megrendelések feldolgozása szerint
        let graphData = <?php echo json_encode($results) ?>;
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Megrendelések állapota',
                align: 'left'
            },
            tooltip: {
                    pointFormatter:function(){
                        let point = this;
                        series =point.series;

                        return `${series.name}: <b>${point.y}</b>${point.y}<b></b>`
                    }
            },
        
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.y}'
                    }
                }
            },
            series: [{
                name: 'Állapot',
                colorByPoint: true,
                data: graphData
            }]
        });
        
        let barGraphData = <?php echo json_encode($bar_chart_data) ?>; 
        let barGraphCategories = <?php echo json_encode($categories) ?>; 

        Highcharts.chart('containerBarChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'A beszállítóhoz rendelt termékszám',
                align: 'left'
            },
          
            xAxis: {
                categories: barGraphCategories,
                crosshair: true,
            },
            yAxis: {
                title: {
                    text: 'Termékszám'
                }
            },
            tooltip: {
                pointFormatter:function(){
                        let point = this;
                        series =point.series;

                        return `<b>${series.category}</b>: ${point.y}`
                    }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: 'Beszállítók',
                    data: barGraphData
                }
            
            ]
        });

        let lineCategories = <?php echo json_encode($line_categories) ?>; 
        let lineData = <?php echo json_encode($line_data) ?>; 

        console.log(lineCategories);
        console.log(lineData);

        Highcharts.chart('deliveryHistory', {
            chart: {
                type: 'spline'
            },
            title: {
                text: 'Szállítmányozási folyamatok / nap'
            },
           
            xAxis: {
                categories: lineCategories,
                accessibility: {
                    description: 'Months of the year'
                }
            },
            yAxis: {
                title: {
                    text: 'Szállított termékek'
                },
                
            },
            tooltip: {
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                name: 'Kiszállított termékek',
                data: lineData
            }]
        });


     </script>
        

       
</body>
</html>