<?php

include('connection.php');

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM product_order_history ORDER BY date_received DESC");
$stmt->execute();
$stmt->setFetchMode(PDO::FETCH_ASSOC);

echo json_encode($stmt->fetchAll());