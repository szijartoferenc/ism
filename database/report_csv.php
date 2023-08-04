<?php
$type = $_GET['report'];
$file_name = '.xls';

$mapping_filenames = [
    'supplier'=> 'Supplier Report',
    'product'=> 'Product Report'
];

$file_name = $mapping_filenames[$type] . '.xls';
header("Content-Disposition: attachment; filename=\"$file_name\"");
header("Content-Type: application/vnd.ms-excel");

//Pull data from the database
include('connection.php');

if ($type === 'product') {
    $stmt = $conn->prepare("SELECT * FROM products INNER JOIN users ON products.created_by = users.id ORDER BY products.created_at DESC");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $products = $stmt->fetchAll();

    //Headers
    $is_header = true;
    foreach ($products as $product) {
        $product['created_by'] = $product['first_name'] . ' ' . $product['last_name'];
        unset($product['first_name'], $product['last_name'], $product['password'], $product['email']);

        if ($is_header) {
            $row = array_keys($product);
            $is_header = false;
            echo implode("\t", $row) . "\n";
        }

        //detect double quotes and escape any value that contains them
        array_walk($product, function (&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r ?\n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        });

        echo implode("\t", $product) . "\n";
    }
    exit(); 
}
?>

