<?php

include('connection.php');
$statuses = ['pending', 'complete', 'incomplete'];

$results = [];

//Loop trough statuses and query
foreach ($statuses as $status) {
    $stmt = $conn->prepare("SELECT COUNT(*) as status_count FROM product_order WHERE product_order.status='" . $status . "'");
    $stmt->execute();
    $row = $stmt->fetch();

    $count = $row['status_count'];

    $results[] = [
        'name' => strtoupper($status),
        'y' => (int) $count
    ];
    
    
}

