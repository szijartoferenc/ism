<?php
require('fpdf/fpdf.php');

class PDF extends FPDF{
    function __construct() {
        parent:: __construct('L');
    }

          
    // Colored table
    function FancyTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // Header
        $w = array(15, 35, 40, 45, 45, 45);
        for($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach($data as $row)
        {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6,iconv('UTF-8', 'windows-1252', $row[1]), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, $row[4], 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, $row[5], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$type = $_GET['report'];
$report_header = [
   'product'=> 'Product Report'
];

if ($type == 'product') {
    //Pull data from the database
    include('connection.php');

    // Column headings
    $header = array('id', 'product_name', 'stock', 'created_by', 'created_at', 'updated_at');

    //Load product
    $stmt = $conn->prepare("SELECT *, products.id as pid FROM products INNER JOIN users ON products.created_by = users.id ORDER BY products.created_at DESC");
    $stmt->execute();
    $products = $stmt->fetchAll();

    //Headers
    $is_header = true;
    $data = [];
    foreach ($products as $product) {
        $product['created_by'] = $product['first_name'] . ' ' . $product['last_name'];
        unset($product['first_name'], $product['last_name'], $product['password'], $product['email']);

        if ($is_header) {
            $row = array_keys($product);
            $is_header = false;
            
              // $data[] = $row; Add the header row to the data array
        }

        //detect double quotes and escape any value that contains them
        array_walk($product, function (&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r ? \n/", "\\n", $str);
            if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
        });

        $data[] = [
            $product['pid'],
            $product['product_name'],
            // $product['description'],
            // $product['img'],
        number_format($product['stock']),
            $product['created_by'],
            date('Y,M,d, h:i:s A', strtotime($product['created_at'])),
            date('Y,M,d, h:i:s A', strtotime($product['updated_at']))
        ];
    }

}


//Start pdf
$pdf = new PDF();
$pdf->SetFont('Arial','',16);
$pdf->AddPage();

$pdf->Cell(80);
$pdf->Cell(30,10,$report_header[$type],0,0,'C');
$pdf->SetFont('Arial','',10);
$pdf->Ln();
$pdf->Ln();

$pdf->FancyTable($header, $data);
$pdf->Output();




