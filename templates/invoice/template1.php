<?php

foreach ($invoices as $invoice) {
  $id_invoice              = $invoice['id_invoice'];
  $invoice_original_amount = $invoice['invoice_original_amount'];
  $invoice_amount          = $invoice['invoice_amount'];
  $invoice_date            = $invoice['invoice_date'];
  $email                   = $invoice['net_user_email'];
  $phone                   = $invoice['net_user_phone'];
  $net_user_address        = $invoice['net_user_address'];
  $payable                 = $invoice['payable'];
  $due                     = $invoice['due'];
  $rec_flag                = $invoice['rec_flag'];
  $description             = $invoice['description'];
}


$this->fpdf->Cell(120,7,'',0,0);//headder logo
$this->fpdf->Image('assets/img/'.$isp_image,10,7,90,30);


$this->fpdf->setY(40);
$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','B',11);
$this->fpdf->Cell(128,7,'Bill To',0,0);
$this->fpdf->SetFont('Arial','B',14);
$this->fpdf->Cell(30,7,$isp_name,0,1);
$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(128,7,$email,0,0);
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(30,7,$isp_address,0,1);




$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(120,7,$net_user_address,0,0);
$this->fpdf->SetFont('Arial','',13);
$this->fpdf->Cell(28,7,'Invoice :',0,0,'R');
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(30,7,'#'.$id_invoice,0,1);



$this->fpdf->setX(15);

$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(120,7,'Cell : '.$phone,0,0);
$this->fpdf->SetFont('Arial','',13);
$this->fpdf->Cell(28,7,'Date     :',0,0,'R');
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(30,7,$invoice_date,0,1);





$this->fpdf->setY(75);
$this->fpdf->setX(15);
$this->fpdf->setFillColor(230,230,230);
$this->fpdf->SetFont('Arial','B',11);
$this->fpdf->Cell(90,7,'Description',1,0,'C',1);
$this->fpdf->Cell(20,7,'Qty/Mon',1,0,'C',1);
$this->fpdf->Cell(20,7,'Price',1,0,'C',1);
$this->fpdf->Cell(50,7,'Amount',1,1,'C',1);

if($rec_flag==0)
{
  foreach ($invoices as $invoice) {  //rakib
    $this->fpdf->setX(15);
    $this->fpdf->SetFont('Arial','',11);
    $this->fpdf->Cell(90,7,$invoice['package_name'].' - '.$invoice['package_speed'],0,0,'C');
    $this->fpdf->Cell(20,7,'',0,0,'C');
    $this->fpdf->Cell(20,7,$invoice['invoice_original_amount'],0,0,'C');
    $this->fpdf->Cell(50,7,$invoice['invoice_original_amount'],0,1,'C');
  }

}else if($rec_flag==1)
{
  $this->fpdf->setX(15);
  $this->fpdf->SetFont('Arial','',11);
  $this->fpdf->Cell(90,7,$description,0,0,'C');
  $this->fpdf->Cell(20,7,'',0,0,'C');
  $this->fpdf->Cell(20,7,$invoice_original_amount,0,0,'C');
  $this->fpdf->Cell(50,7,$invoice_original_amount,0,1,'C');
}



$this->fpdf->Line(105,80,105,221);
$this->fpdf->Line(125,80,125,200);
$this->fpdf->Line(145,80,145,221);


// for ($x = 0; $x <= 5; $x++) {
//   $this->fpdf->setX(15);
//   $this->fpdf->SetFont('Arial','',11);
//   $this->fpdf->Cell(90,7,$x,1,0,'C');
//   $this->fpdf->Cell(20,7,$x,1,0,'C');
//   $this->fpdf->Cell(20,7,$x,1,0,'C');
//   $this->fpdf->Cell(50,7,$x,1,1,'C');
//
// }

//$this->fpdf->setX(15);
//$this->fpdf->SetFont('Arial','B',11);
//$this->fpdf->Cell(110,7,'',0,0);
// $this->fpdf->Cell(20,7,'Subtotal',1,0,'R');
// $this->fpdf->SetFont('Arial','',11);
// $this->fpdf->Cell(50,7,$subtotal,1,1,'C');
//
// $this->fpdf->setX(15);
// $this->fpdf->SetFont('Arial','B',11);
// $this->fpdf->Cell(110,7,'',0,0);
// $this->fpdf->Cell(20,7,'Tax',1,0,'R');
// $this->fpdf->SetFont('Arial','',11);
// $this->fpdf->Cell(50,7,$calculated_tax,1,1,'C');
//
$this->fpdf->setY(200);
$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','B',11);
$this->fpdf->Cell(90,7,'',0,0);
$this->fpdf->Cell(40,7,'Current Total',0,0,'C');
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(50,7,$invoice_original_amount.' Taka',0,1,'C');
$this->fpdf->Line(105,200,190,200);


$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','B',11);
$this->fpdf->Cell(90,7,'',0,0);
$this->fpdf->Cell(40,7,'Current Due',0,0,'C');
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(50,7,$due.' Taka',0,1,'C');
$this->fpdf->Line(105,207,190,207);


$this->fpdf->setX(15);
$this->fpdf->SetFont('Arial','B',11);
$this->fpdf->Cell(90,7,'',0,0);
$this->fpdf->Cell(40,7,'Payable',0,0,'C');
$this->fpdf->SetFont('Arial','',11);
$this->fpdf->Cell(50,7,$payable.' Taka',0,1,'C');
$this->fpdf->Line(105,214,190,214);


$this->fpdf->Line(15,221,190,221);

$this->fpdf->setY(269);
$this->fpdf->setX(30);
$this->fpdf->Cell(140,7,$isp_description,0,0,'C');

//Put the watermark
if($invoice_amount<=0){
  $this->fpdf->SetFont('Arial','B',70);
  $this->fpdf->SetTextColor(255,192,203);
  $this->fpdf->Text(74,153,'Paid');
}

 ?>
