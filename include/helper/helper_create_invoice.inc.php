<?php
// Helper page to create an invoice after the order complete.

// redirect, if this page got called directly and not via "request"
if (str_contains($_SERVER["REQUEST_URI"], basename(__FILE__))) {
    header("Location: " . ROOT_DIR);
    die();
}

// load Header
require_once "../site_php_head.inc.php";

UserController::redirectIfNotLoggedIn();

// Redirect, if information to create the invoice are missing or the information do not have the correct datatype.
// The most information could be retrieved by the order object, but we only want to use this page after order creation
// in this case this information should already be set.
if (!isset($order) || !$order instanceof Order || empty($productOrders) ||
    !$productOrders[0] instanceof ProductOrder || !isset($deliveryAddress) || !$deliveryAddress instanceof Address) {
    header("Location: " . USER_PAGES_DIR . "page_shopping_cart.php");
    die();
}

// load tcpdf
require_once INCLUDE_TCPDF_DIR . "tcpdf.php";

// region load data
// it's safe to use this here, because we check the user information in the function redirectIfNotLoggedIn
$user = UserController::getById($_SESSION["uid"]);

$orderId = $order->getId();
$userId = $user->getId();
$orderDate = $order->getFormattedOrderDate();
$deliveryDate = $order->getFormattedDeliveryDate();
$pdfAuthor = PAGE_NAME;

// recipient of invoice
$invoice_recipient =
    $user->getFormattedName() . "\n" .
    $deliveryAddress->getStreet() . " " . $deliveryAddress->getNumber() . "\n" .
    $deliveryAddress->getZip() . " " . $deliveryAddress->getCity();

$invoice_footer = INVOICE_FOOTER;

// value added tax (0.19 = 19%)
$tax = SHOP_TAX;
// endregion

// load file information
$targetDir = INVOICES_DIR . $userId;
$pdfName = "invoice_" . $userId . "_" . $orderId . ".pdf";


// region invoice body (use of css is limited in tcpdf)
$html = '
<table style="width: 100%; ">
    <tr>
        <td style="width: 60%">
            <img src="' . IMAGE_LOGO_DIR . 'logo_long.svg" height="34" alt="Logo"> <br><br>
        </td>
        
        <td style="width: 40%">
            <!-- invoice information -->
            Bill Number ' . $orderId . '<br>
            Invoice Date: ' . $orderDate . '<br>
            Delivery Date: ' . $deliveryDate . '<br><br>
        </td>
    </tr>

    <tr>
        <td>' . nl2br(trim($invoice_recipient)) . '</td>
        
        <td>  
            <!-- sender of invoice -->
            '. PAGE_NAME . '<br>
            ' . COMPANY_STREET . ' ' . COMPANY_STREET_NR  . '<br>
            ' . COMPANY_ZIP_CODE . ' ' . COMPANY_CITY . '<br>
            ' . COMPANY_COUNTRY . '
        </td>
    </tr>
 
    <tr>
        <td>
            <p style="font-size:1.4em; font-weight: bold; margin-top: 15px">Invoice<br><br></p>
        </td>
    </tr>
</table>
 
<table style="width: 100%; border: none;">
    <thead>
        <tr style="background-color: #bbbbbb;">
            <th scope="col" style="width: 61%">Name</th>
            <th scope="col" style="width: 13%; text-align: center;">Amount</th>
            <th scope="col" style="width: 13%; text-align: center;">Unit Price</th>
            <th scope="col" style="width: 13%; text-align: center;">Price</th>
        </tr>
    </thead>
    
    <tbody>';
// endregion

// TODO shipping?
// total sum of all products for this invoice
$sum = 0;

foreach ($productOrders as $item) {
    $product = ProductController::getByID($item->getProductId());

    $sum += $item->getFullPrice();

    $html .= '<tr>
                <td data-th="Name" style="width: 61%; margin-bottom: 10px">' . $product->getTitle() . '<br></td>
                <td data-th="Amount" style="width: 13%; text-align: center;">' . $item->getAmount() . " pcs." . '</td> 
                <td data-th="Unit Price" style="width: 13%; text-align: center;">' . $item->getFormattedUnitPrice() . '</td>	
                <td data-th="Price" style="width: 13%; text-align: center;">' . $item->getFormattedFullPrice() . '</td>
              </tr>';
}

$html .= '
    </tbody>
</table>
<hr>

<table style="width: 100%; border: none">';

if ($tax > 0) {
    $net = $sum / (1 + $tax);
    $tax_amount = $sum - $net;

    $html .= '
 <tr>
    <td style="width: 87%;">Subtotal (net)</td>
    <td style="width: 13%; text-align: center;">' . number_format($net, 2, ',', '') . CURRENCY_SYMBOL . '</td>
 </tr>
 
 <tr>
    <td style="width: 87%;">Tax (' . intval($tax * 100) . '%)</td>
    <td style="width: 13%; text-align: center;">' . number_format($tax_amount, 2, ',', '') . CURRENCY_SYMBOL . '</td>
 </tr>';
}

$html .= '
    <tr>
        <td style="width: 87%;"><b>Total: </b></td>
        <td style="width: 13%; text-align: center;"><b>' . number_format($sum, 2, ',', '') . CURRENCY_SYMBOL . '</b></td>
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

// new page, if needed
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// scale the images
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Select text font
$pdf->SetFont("dejavusans", "", 10);

// Add a new page
$pdf->AddPage();

// Add the html content to the pdf.
$pdf->writeHTML($html, true, false, true, false, '');

// Create output dir, if it does not exist.
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Create the pdf in the filesystem (tcpdf only works with absolut paths)
$pdf->Output(realpath($targetDir) . DS . $pdfName, "F");