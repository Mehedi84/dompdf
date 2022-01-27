<?php
include 'function.php';
date_default_timezone_set('Asia/Dhaka');
$html='<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>INVOICE</title>
</head>
<body>
<style type="text/css">
@page {
	margin: 0cm 0cm;
}
	    #footer{
position: absolute;
bottom: -60px; 
left: 0px; 
right: 0px;
height: 240px; 
text-align: center;
line-height: 35px;
}body{
	background-image: url("body.jpg");
	background-size: 100%;
	background-repeat: no-repeat;
}
</style>
<table style=" width: 100%;margin-top:80px;margin-bottom: 10px;">
<tr>
<td style="text-align: center;color:#C0504D;font-size:18px;"><strong>INVOICE</strong></td>
</tr>
</table>
<table width="100%" height="auto" style="border-collapse: collapse;margin:0px 40px; padding:0px;">
<tr>
<td style="border:1px solid #0070C0;">';
include 'db.php';
$invoice = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `invoice` WHERE `id`='".$_GET['id']."'"));
$date=$invoice["created_at"];
$data_str=strtotime($date);			

$date_bill=$invoice["bill_month"];
$bill_month=strtotime($date_bill);
$html.='<table style="width: 100%;">
<!--<tr>
<td style="border: 0px;padding-left:10px;">To</td>
</tr>-->
<tr>
<td style="border: 0px;padding-left:10px;"><strong>'.$invoice["organization"].'</strong></td>
</tr>
<tr>
<td style="border: 0px;padding-left:10px;"><strong>'.$invoice["customer_name"].'</strong></td>
</tr>
<tr>
<td style="border: 0px;padding-left:10px;">'.$invoice["address"].'</td>
</tr>
</table>
</td>
<td style="border: 1px solid #0070C0; padding: 0px;">
<table style="width: 100%;margin:0px; padding:0px;">
<tr>
<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Date</td>
<td style="border: 1px solid #0070C0;padding-left:10px;">'.date('F-d-Y',$data_str).'</td>
</tr>
<tr>
<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Invoice No.</td>
<td style="border: 1px solid #0070C0;padding-left:10px;">'.$invoice["subject"].'</td>
</tr>';
if($invoice["type"]!='OTC') {
	$html.='<tr>
	<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Bill Period</td>
	<td style="border: 1px solid #0070C0;padding-left:10px;">'.date("F Y",$bill_month).'</td>
	</tr>';
}
$html.='<tr>
<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">BIN</td>

<td style="border: 1px solid #0070C0;padding-left:10px;">002036982-0202</td>
</tr>
<tr>
<td  style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Ref. No.</td>
<td style="border: 1px solid #0070C0;padding-left:10px;">'.$invoice["ref_no"].'</td>
</tr>
</table>
</td>
</tr>
</table>
<table style="width: 84%;margin-top:30px;border-collapse: collapse;" align="center">
<tr style="background:#B6DDE8;">
<th style="border: 1px solid black">Sl. No.</th>
<th style="border: 1px solid black">Description</th>
<th style="border: 1px solid black">Qnty/Min</th>
<th style="border: 1px solid black">Unit Price (BDT)</th>
<th style="border: 1px solid black">Vat (%)</th>
<th style="border: 1px solid black">Total Amount (BDT)</th>
</tr>';
$sl=1;
$total=0;
$vat=0;
$sql = mysqli_query($conn,"SELECT * FROM `order_invoice` WHERE `invoice_id`='".$_GET['id']."'");
while ($row = mysqli_fetch_assoc($sql)) {
	$total+=$row['total'];
	$vat += ($row['total']*$row['vat'] / 100);
	$rowTotal =$row['total']+($row['total']*$row['vat'] / 100);
	$grandTotal = $total+$vat;
	if ($row['vat']!='') {
		$vatt =$row['vat'].'%';
	}else{
		$vatt ='-';
	}

	$html.='<tr>
	<td style="border: 1px solid black;text-align: center;">'.$sl++.'</td>
	<td style="border: 1px solid black">'.$row['productname'].'</td>
	<td style="border: 1px solid black;text-align: center;">'.$row['quantity'].'</td>
	<td style="border: 1px solid black;text-align: center;">'.$row['unit_price'].'</td>
	<td style="border: 1px solid black;text-align: center;">'.$vatt.'</td>
	<td style="border: 1px solid black;text-align: right;">'.number_format($rowTotal,2).'</td>
	</tr>';
}
$html.='<tr>
<td style="border: 1px solid black"></td>
<td style="border: 1px solid black"><strong>Total-</strong></td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: right;"><strong>'.number_format($grandTotal,2).'</strong></td>
</tr>';

$timestamp = strtotime($invoice['bill_month']);
$now_munth = date('Y-m-d', $timestamp);
$sql = mysqli_fetch_assoc(mysqli_query($conn ,"SELECT SUM(`grandTotal`) as grandTotal,SUM(`payment_Tk`) as paymentTk FROM `invoice` WHERE SUBSTRING_INDEX(bill_month, '-', 3) < '".$now_munth."' AND `lead_Id`='".$invoice['lead_Id']."'"));

$previous = $sql['grandTotal']-$sql['paymentTk'];
$totalGrandTotal = $grandTotal+$previous;
if ($previous>0) {
	$html.='<tr>
	<td style="border: 1px solid black"></td>
	<td style="border: 1px solid black"><strong>Previous Due</strong></td>
	<td style="border: 1px solid black;text-align: center;">-</td>
	<td style="border: 1px solid black;text-align: center;">-</td>
	<td style="border: 1px solid black;text-align: center;"></td>
	<td style="border: 1px solid black;text-align: right;"><strong>'.number_format($previous,2).'</strong></td>
	</tr>';
}
$html.='<tr>
<td style="border: 1px solid black"></td>
<td style="border: 1px solid black"><strong>Grand Total</strong></td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: center;">-</td>
<td style="border: 1px solid black;text-align: right;"><strong>'.number_format($totalGrandTotal,2).'</strong></td>
</tr>
</table>

<table style=" width: 84%;margin-top:30px;margin-bottom:0px;border-collapse: collapse;" align="center">
<tr>
<td style="border: 1px solid black;"><strong>Amount In Words: BDT. '.numberTowords($totalGrandTotal).' </strong></td>
</tr>
</table>';
if ($invoice["vat_text"]!='') {
	$html.='<table style=" width: 84%;margin-top:30px;margin-bottom:0px;border-collapse: collapse;" align="center">
	<tr>
	<td style="border: 1px solid black;"><strong>'.$invoice["vat_text"].'</strong></td>
	</tr>
	</table>';
}
$html.='<table style="height: 150px; width: 84%;margin-top:40px;border-style: dashed;" align="center">
<tr>
<td style="border: 0px;text-decoration: underline;padding-left:10px"><strong>Terms-</strong></td>
</tr>
<tr>
<td style="border: 0px;padding-left:10px">Please make Account Payee Cheque to iHelpBD, at-</td>
</tr>		
<tr>
<td style="border: 0px;padding-left:10px">Bank Name: <strong>Dutch Bangla Bank Limited (Panthapath Branch)</strong></td>
</tr>				
<tr>
<td style="border: 0px;padding-left:10px"><strong>Account Name: I HELP BD </strong></td>
</tr>		
<tr>
<td style="border: 0px;padding-left:10px"><strong>Account Number: 255 110 3033</strong></td>
</tr>		
<tr>
<td style="border: 0px;padding-left:10px">Routing Number: 090270424</td>
</tr>
</table>
<span id="footer">
<table style=" width: 84%;margin-bottom:20px;" align="center">
<tr>
<td style="width: 30%">
<!--<img src="sine.png"width="100%" height="40px" alt="Image Here"/>-->
<br>Authorized Signature</td>
<!--<td><img src="sill.png" alt="Logo"></td>-->
</tr>
</table>
</span>
</body>
</html>';
require_once 'dompdf/autoload.inc.php';

		// reference the Dompdf namespace
use Dompdf\Dompdf;

define("DOMPDF_ENABLE_REMOTE", false);

		// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

		// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

		// Render the HTML as PDF
$dompdf->render();

ob_end_clean();
$dompdf->stream(rand().".pdf", array('Attachment'=>0));
?>
</body>
</html>