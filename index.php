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
            }
	</style>
    <table style=" width: 100%;margin:10px;">
        <tr>
         <td style="width: 20%"><img src="logo.png"width="100%" height="70px" alt="Image Here"/></td>
         <td style="width: 78%;padding-top:15px;"><img src="shet.png"width="100%" height="25px" alt="Image Here"/></td>
       </tr>
	</table>
	    <table style=" width: 100%;margin-top:10px;margin-bottom: 10px;">
        <tr>
         <td style="text-align: center;color:#C0504D;font-size:18px;"><strong>INVOICE</strong></td>
       </tr>
	</table>
	<table width="100%" height="450px" style="border-collapse: collapse;margin:0px 40px;">
		<tr>
			<td>';
			include 'db.php';
			$invoice = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `invoice` WHERE `id`='".$_GET['id']."'"));
			$date=$invoice["created_at"];
            $data_str=strtotime($date);			

            $date_bill=$invoice["bill_month"];
            $bill_month=strtotime($date_bill);
			$html.='<table style="height: 150px; width: 100%;border:1px solid #0070C0;">
					<tr>
						<td style="border: 0px;padding-left:10px;">To</td>
					</tr>
					<tr>
						<td style="border: 0px;padding-left:10px;"><strong>'.$invoice["customer_name"].'</strong></td>
					</tr>
					<tr>
						<td style="border: 0px;padding-left:10px;"><strong>'.$invoice["organization"].'</strong></td>
					</tr>
					<tr>
						<td style="border: 0px;padding-left:10px;">'.$invoice["address"].'</td>
					</tr>
				</table>
			</td>
			<td>
			<table style="height: 150px; width: 100%;margin-bottom:6px;">
					<tr>
						<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Date</td>
						<td style="border: 1px solid #0070C0;padding-left:10px;">'.gmdate('F-d-Y',$data_str).'</td>
					</tr>
					<tr>
						<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Invoice No.</td>
						<td style="border: 1px solid #0070C0;padding-left:10px;">'.$invoice["subject"].'</td>
					</tr>
					<tr>
						<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">Bill Month</td>
						<td style="border: 1px solid #0070C0;padding-left:10px;">'.gmdate('F-Y',$bill_month).'</td>
					</tr>
					<tr>
						<td style="border: 1px solid #0070C0;font-weight: bold;padding-left:10px;background:#B6DDE8;">BIN</td>

						<td style="border: 1px solid #0070C0;padding-left:10px;">'.$invoice["bin"].'</td>
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
        <th style="border: 1px solid black">Quantity</th>
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
         	$grandTotal = $total+$vat;
       $html.='<tr>
         <td style="border: 1px solid black;text-align: center;">'.$sl++.'</td>
         <td style="border: 1px solid black">'.$row['productname'].'</td>
         <td style="border: 1px solid black;text-align: center;">'.$row['quantity'].'</td>
         <td style="border: 1px solid black;text-align: center;">'.$row['unit_price'].'</td>
         <td style="border: 1px solid black;text-align: center;">'.$row['vat'].'</td>
         <td style="border: 1px solid black;text-align: right;">'.$row['total'].'</td>
       </tr>';
          }
        $html.='<tr>
         <td style="border: 1px solid black"></td>
         <td style="border: 1px solid black"><strong>Total-</strong></td>
         <td style="border: 1px solid black;text-align: center;">-</td>
         <td style="border: 1px solid black;text-align: center;">-</td>
         <td style="border: 1px solid black;text-align: center;"><strong>'.$vat.'</strong></td>
         <td style="border: 1px solid black;text-align: right;"><strong>'.$total.'</strong></td>
       </tr>
       <tr>
         <td style="border: 1px solid black"></td>
         <td style="border: 1px solid black"><strong>Grand Total-</strong></td>
         <td style="border: 1px solid black;text-align: center;">-</td>
         <td style="border: 1px solid black;text-align: center;">-</td>
         <td style="border: 1px solid black;text-align: center;">-</td>
         <td style="border: 1px solid black;text-align: right;"><strong>'.$grandTotal.'</strong></td>
       </tr>
	</table>
	<table style=" width: 84%;margin-top:30px;margin-bottom:0px;border-collapse: collapse;" align="center">
        <tr>
         <td style="border: 1px solid black;"><strong>Amount In Words: BDT. '.numberTowords($grandTotal).' </strong></td>
       </tr>
	</table>
		<table style="height: 150px; width: 84%;margin-top:40px;border-style: dashed;" align="center">
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
         <td style="width: 30%"><img src="sine.png"width="100%" height="40px" alt="Image Here"/><br>Authorized Signature</td>
         <td><img src="sill.png" alt="Logo"></td>
       </tr>
	</table>
    <table style="width: 100%;margin-top:20px;">
        <tr>
         <td style="width: 30%;padding-left:15px;"><img src="shet2.png" width="100%" height="10px" alt="Image Here"/></td>
         <td style="width: 30%;"><img src="shet2.png" width="100%" height="10px" alt="Image Here"/></td>
         <td style="width: 30%;"><img src="shet2.png" width="100%" height="10px" alt="Image Here"/></td>
       </tr>
	</table>
	    <table style="width: 100%;margin-top:0px;">
        <tr>
         <td style="font-size:13px; font-weight:bold;padding-left:25px;color:gray;">H# 01, R# 15 (New), 28 (Old), Dhanmondi, Dhaka-1205 Phone: +88 01672-063705 Email: info@ihelpbd.com Web: www.ihelpbd.com </td>
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