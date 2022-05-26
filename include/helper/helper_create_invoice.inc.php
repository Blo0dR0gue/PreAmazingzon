<?php
//Helper page to create an invoice after the order complete.

//Redirect, if this page got called directly and not via "request"
if (str_contains($_SERVER["REQUEST_URI"], basename(__FILE__))) {
    header("Location: " . ROOT_DIR);
    die();
}

//Load Header
require_once "../site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

//Redirect, if information to create the invoice are missing or the information do not have the correct datatype
//The most information could be got by the order object, but we only want to use this page after order creation and there this information should already be set.
if (!isset($order) || !$order instanceof Order || !isset($productOrders) || count($productOrders) <= 0 ||
    !$productOrders[0] instanceof ProductOrder || !isset($deliveryAddress) || !$deliveryAddress instanceof Address) {
    header("Location: " . USER_PAGES_DIR . DS . "page_shopping_cart.php");
    die();
}

//Load tcpdf
require_once INCLUDE_TCPDF_DIR . DS . "tcpdf.php";

//It's safe to user this here, because we check the user information in the function redirectIfNotLoggedIn
$user = UserController::getById($_SESSION["uid"]);

$orderId = $order->getId();
$userId = $user->getId();
$order_date = $order->getFormattedOrderDate();
$delivery_date = $order->getFormattedDeliveryDate();
$pdfAuthor = PAGE_NAME;

$targetDir = INVOICES_DIR . DS . $userId;

//The sender of this invoice
$invoice_header =
    "<img src='" . IMAGE_LOGO_DIR . DS . "logo_long.svg" . "' height='32'> \n " .
    PAGE_NAME . "\n" .
    COMPANY_STREET . " " . COMPANY_STREET_NR . "\n" .
    COMPANY_ZIP_CODE . " " . COMPANY_CITY . "\n" .
    COMPANY_COUNTRY;

//Recipient information for invoice
$invoice_recipient =
    $user->getFormattedName() . "\n" .
    $deliveryAddress->getStreet() . " " . $deliveryAddress->getNumber() . "\n" .
    $deliveryAddress->getZip() . " " . $deliveryAddress->getCity();

$invoice_footer = INVOICE_FOOTER;

//value added tax (0.19 = 19%)
$tax = 0.0; //TODO constant

$pdfName = "invoice_" . $userId . "_" . $orderId . ".pdf";

//Invoice body (The use of css is limited in tcpdf)
$html = ' 
<table style="width: 100%; ">
 <tr>
 <td>' . nl2br(trim($invoice_header)) . '</td>
    <td style="text-align: right">
Bill Number ' . $orderId . '<br>
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

//Total sum of all products for this invoice
$sum = 0;

foreach ($productOrders as $item) {
    $product = ProductController::getByID($item->getProductId());

    $sum += $item->getFullPrice();

    $html .= '<tr>
                <td>' . $product->getTitle() . '</td>
                <td style="text-align: center;">' . $item->getAmount() . " pcs." . '</td> 
                <td style="text-align: center;">' . $item->getFormattedUnitPrice() . '</td>	
                <td style="text-align: center;">' . $item->getFormattedFullPrice() . '</td>
              </tr>';
}
$html .= "</table>";


$html .= '
<hr>
<table style="width: 100%; border: none">';
if ($tax > 0) {
    $net = $sum / (1 + $tax);
    $tax_amount = $sum - $net;

    $html .= '
 <tr>
 <td colspan="3">Subtotal (net)</td>
 <td style="text-align: center;">' . number_format($net, 2, ',', '') . CURRENCY_SYMBOL . '</td>
 </tr>
 <tr>
 <td colspan="3">Tax (' . intval($tax * 100) . '%)</td>
 <td style="text-align: center;">' . number_format($tax_amount, 2, ',', '') . CURRENCY_SYMBOL . '</td>
 </tr>';
}

$html .= '
            <tr>
                <td colspan="3"><b>Total: </b></td>
                <td style="text-align: center;"><b>' . number_format($sum, 2, ',', '') . CURRENCY_SYMBOL . '</b></td>
            </tr> 
        </table>
<br><br><br>';

if ($tax == 0) {
    $html .= "According to § 19 paragraph 1 UStG no sales tax will be charged.<br><br>";
}

$html .= nl2br($invoice_footer);

// Create PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Add PDF Information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($pdfAuthor);
$pdf->SetTitle("Invoice " . $orderId);
$pdf->SetSubject("Invoice " . $orderId);


// Add header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, "", PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, "", PDF_FONT_SIZE_DATA));

// Select monospace font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// select margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// New page, if needed
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// scale the images
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Select text font
$pdf->SetFont("dejavusans", "", 10);

// Add a new page
$pdf->AddPage();

// Add the html content to the pdf.
$pdf->writeHTML($html, true, false, true, false, '');

//Create output dir, if it does not exist.
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

//Create the pdf in the filesystem (tcpdf only works with absolut paths)
$pdf->Output(realpath($targetDir) . DS . $pdfName, "F");