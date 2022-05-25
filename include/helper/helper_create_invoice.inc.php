<?php
//Load Header
require_once "../site_php_head.inc.php";

//Load tcpdf
require_once INCLUDE_TCPDF_DIR . DS . "tcpdf.php";


$invoice_nr = "743";
$order_date = date("d.m.Y");
$delivery_date = date("d.m.Y");
$pdfAuthor = PAGE_NAME;

$invoice_header = '
<img src="' . IMAGE_LOGO_DIR . DS . "logo_long.svg" . '" height="32">
' . PAGE_NAME . '
' . COMPANY_STREET . " " . COMPANY_STREET_NR . '
' . COMPANY_ZIP_CODE . " " . COMPANY_CITY . '
' . COMPANY_COUNTRY;

//TODO
$invoice_recipient = '
Max Musterman
Musterstraße 17
12345 Musterstadt';

$invoice_footer = INVOICE_FOOTER;

//TODO
$order_items = array(
    array("Produkt 1", 1, 42.50),
    array("Produkt 2", 5, 5.20),
    array("Produkt 3", 3, 10.00));

//value added tax (0.19 = 19%)
$tax = 0.0; //TODO constant

$pdfName = "invoice_" . $invoice_nr . ".pdf";

//Invoice body (The use of css is limited in tcpdf)
$html = '
<table style="width: 100%; ">
 <tr>
 <td>' . nl2br(trim($invoice_header)) . '</td>
    <td style="text-align: right">
Bill Number ' . $invoice_nr . '<br>
Invoice Date: ' . $order_date . '<br>
Delivery Date: ' . $delivery_date . '<br>
 </td>
 </tr>
 
 <tr>
 <td style="font-size:1.3em; font-weight: bold;">
<br><br>
Invoice
<br>
 </td>
 </tr>
 
 
 <tr>
 <td colspan="2">' . nl2br(trim($invoice_recipient)) . '</td>
 </tr>
</table>
<br><br><br>
 
<table style="width: 100%; border: none;">
 <tr style="background-color: #cccccc; padding:5px;">
 <td style="padding:5px;"><b>Name</b></td>
 <td style="text-align: center;"><b>Amount</b></td>
 <td style="text-align: center;"><b>Unit Price</b></td>
 <td style="text-align: center;"><b>Price</b></td>
 </tr>';

//TODO
$sum = 0;

foreach ($order_items as $item) {
    $amount = $item[1];
    $unitPrice = $item[2];
    $price = $amount * $unitPrice;
    $sum += $price;
    $html .= '<tr>
                <td>' . $item[0] . '</td>
 <td style="text-align: center;">' . $item[1] . '</td> 
 <td style="text-align: center;">' . number_format($item[2], 2, ',', '') . ' Euro</td>	
                <td style="text-align: center;">' . number_format($price, 2, ',', '') . ' Euro</td>
              </tr>';
}
$html .= "</table>";


$html .= '
<hr>
<table cellpadding="5" cellspacing="0" style="width: 100%;" border="0">';
if ($tax > 0) {
    $netto = $sum / (1 + $tax);
    $tax_amount = $sum - $netto;

    $html .= '
 <tr>
 <td colspan="3">Zwischensumme (Netto)</td>
 <td style="text-align: center;">' . number_format($netto, 2, ',', '') . ' Euro</td>
 </tr>
 <tr>
 <td colspan="3">Umsatzsteuer (' . intval($tax * 100) . '%)</td>
 <td style="text-align: center;">' . number_format($tax_amount, 2, ',', '') . ' Euro</td>
 </tr>';
}

$html .= '
            <tr>
                <td colspan="3"><b>Gesamtsumme: </b></td>
                <td style="text-align: center;"><b>' . number_format($sum, 2, ',', '') . ' Euro</b></td>
            </tr> 
        </table>
<br><br><br>';

if ($tax == 0) {
    $html .= 'According to § 19 paragraph 1 UStG no sales tax will be charged.<br><br>';
}

$html .= nl2br($invoice_footer);

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Add PDF Information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($pdfAuthor);
$pdf->SetTitle('Invoice ' . $invoice_nr);
$pdf->SetSubject('Invoice ' . $invoice_nr);


// Add header and footer
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Select font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// select margin
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// New page, if needed
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// scale the images
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Select text font
$pdf->SetFont('dejavusans', '', 10);

// Add a new page
$pdf->AddPage();

// Add the html content to the pdf.
$pdf->writeHTML($html, true, false, true, false, '');

//Create output dir, if it does not exist.
if (!file_exists(INVOICES_DIR)) {
    mkdir(INVOICES_DIR, 0777, true);
}
//Create the pdf in the filesystem
$pdf->Output(realpath(INVOICES_DIR) . DS . $pdfName, 'F');