<?php
session_start();
require('fpdf186/fpdf.php');

if (!isset($_SESSION['id']) && !isset($_SESSION['employee'])){
	header('location:open.php');
  }

try{
	$pdo = new PDO('mysql:host=localhost;dbname=pos', "root", "");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
	echo 'Connection Failed' .$e->getMessage();
	}

	date_default_timezone_set("Asia/Taipei");

	$id = $_SESSION['unique_id'];
	$stmt4 = $pdo->prepare("SELECT * from invoice where unique_id = :id");
	$stmt4->bindParam(':id', $id);
    $stmt4->execute();
	$result4 = $stmt4->fetchAll();
	foreach($result4 as $row4){
	}

	

	$datetime = date("Y-m-d H:i:s");

$pdf = new FPDF('P','mm',array(65, 300));


$pdf->AddPage();


$pdf->SetFont('Helvetica','B',7);
$pdf->Cell(20	,5,'BANANA IS YELLOW!',0,1);

$pdf->SetFont('Arial','',7);
$pdf->Cell(20	,5,'[DHVSU]',0,1);
$pdf->Cell(20	,5,'[Bacolor, Pampanga, 2001]',0,1);
$pdf->Cell(20	,5,'Phone [0999-999-9999]',0,1);
$pdf->Cell(20	,5,'************************************************************',0,1);

$pdf->SetFont('Helvetica','B',7);
$pdf->Cell(20	,5,'INVOICE',0,1,);

$pdf->SetFont('Arial','',7);
$pdf->Cell(20	,5,'Date',0,0);
$pdf->Cell(20	,5,$row4['date'],0,1);
$pdf->Cell(20	,5,'Time',0,0);
$pdf->Cell(20	,5,$row4['time'],0,1);
$pdf->Cell(20	,5,'Receipt Generated',0,1);
$pdf->Cell(20	,5,$datetime,0,1);
$pdf->Cell(20	,5,'Invoice ID',0,0);
$pdf->Cell(20	,5,$row4['invoice_id'],0,1);
$pdf->Cell(20	,5,'Bill To:',0,0);
$pdf->Cell(20	,5,$row4['customer'],0,1);
$pdf->Cell(20	,5,'************************************************************',0,1);


$pdf->SetFont('Arial','',6.5);


	$stmt5 = $pdo->prepare("SELECT * from ticket where unique_id = :id");
	$stmt5->bindParam(':id', $id);
    $stmt5->execute();
	$result5 = $stmt5->fetchAll();
	foreach($result5 as $row5){
		$pdf->Cell(10, 5, 'x'.$row5['quantity'].' '  .$row5['product']. ' ' .$row5['price']. ' ('. $row5['discount']. ')', 0, 1);
		
	}

	$pdf->Cell(15	,3,'',0,1);


//summary
$pdf->Cell(15	,3,'Subtotal',0,0);
$pdf->Cell(3	,3,'P',1,0);
$pdf->Cell(10	,3,$row4['subtotal'],1,1,'R');//end of line

$pdf->Cell(15	,5,'Discount',0,0);
$pdf->Cell(3	,5,'P',1,0);
$pdf->Cell(10	,5,$row4['discount'],1,1,'R');//end of line

$pdf->Cell(15	,5,'VAT',0,0);
$pdf->Cell(3	,5,'%',1,0);
$pdf->Cell(10	,5,$row4['vat'],1,1,'R');//end of line

$pdf->Cell(15	,5,'VAT Amt.',0,0);
$pdf->Cell(3	,5,'P',1,0);
$pdf->Cell(10	,5,$row4['vat_amount'],1,1,'R');//end of line

$pdf->Cell(15	,5,'Total',0,0);
$pdf->Cell(3	,5,'P',1,0);
$pdf->Cell(10	,5,$row4['totalamount'],1,1,'R');//end of line

$pdf->Cell(15	,5,'Received',0,0);
$pdf->Cell(3	,5,'P',1,0);
$pdf->Cell(10	,5,$row4['amountpaid'],1,1,'R');//end of line

$pdf->Cell(15	,5,'Change',0,0);
$pdf->Cell(3	,5,'P',1,0);
$pdf->Cell(10	,5,$row4['cash_change'],1,1,'R');//end of line

$pdf->Cell(15	,5,'MOP',0,0);
$pdf->Cell(13	,5,$row4['mop'],1,1,'C');//end of line

$pdf->Output();
?>