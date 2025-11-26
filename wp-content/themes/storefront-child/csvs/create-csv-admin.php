<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
include('simple_html_domv2.php');

require_once WP_CONTENT_DIR . '/themes/storefront-child/csvs/mpdf/vendor/autoload.php';
//include( '/wp-content/themes/storefront-child/html2fpdf/html2fpdf.php' );

//    $_POST['table'];

$order_id = $_POST['id_ord_original'];

$mail_send = get_post_meta($order_id, 'mail_send', true);
$customer_id = get_post_meta($order_id, '_customer_user', true);
$favorite = get_user_meta($customer_id, 'favorite_user', true);

$multiple_test_mails = array(
	'teopro@gmail.com', 'marian93nes@gmail.com',
);

update_post_meta($order_id, 'mail_send', 1);

$billing_company = get_user_meta($customer_id, 'shipping_company', true);

$pos = $_POST['pos'];
$name = $_POST['name'];
//$vowels = array(" ", ".", "#");
$vowels = array(" ", ".", "#", "'", "\"", "`", "/", "\\", ":", ";", "|", "?", "*", "<", ">");
$rename = str_replace($vowels, "", $name);
$rebilling_company = str_replace($vowels, "", $billing_company);

$html = str_get_html($_POST['table']);

// Check if HTML is successfully parsed
if (!$html) {
    die('Failed to load HTML content');
}

$items_table = stripslashes($_POST['items_table']);

$pdf_content = '<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8"/>
        <title></title>
        <style>

        table th, table td {
            font-size: 8pt !important;
        }
        table tbody td {
            padding: 5px 10px !important;
            height: 38px;
        }
        table
        {
            font-size: 8pt;
        }
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 0 !important;
        }
        </style>
        </head>
        ';
$pdf_content .= '<body>
               <table width="100%">
                    <tr>
                        <td width="50%"><p><strong>Lifetime Shutters</strong></p>
                                        <p>7 Lichfield Terrace Sheen Road Richmond SR TW9 1AS</p>
                                        <p>Phone: 02089401418</p>
                                        <p>Email: accounts@lifetimeshutters.com</p>
                                        <p>VAT Registration No.: 986 2325 89</p>
                                        <p>Company Registration No. 07113670</p>
                        </td>
                        <td width="50%" style="text-align: right;"><img class="logo-img site-logo-img" style="text-align: center; margin: 0 auto;" src="https://matrix.lifetimeshutters.com/wp-content/uploads/Logos/lifetime-shutters-logo.png" alt="Plantation Shutters &amp; Windows" title=""></td>
                    </tr>
                </table>
                 <h3 style="text-align: center;"><strong>Order Summary - Proforma Invoice</strong></h3>
                 <br>' . $items_table . '</body>';
$pdf_content .= '</html>';

//MAKE PDF ORDER

//OLD VERSION
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/storefront-child/html2fpdf/html2fpdf.php');
//        exit($content);
//        $pdf = new HTML2FPDF('P', 'mm', 'Letter');
//        $pdf->AddPage();
//        $pdf->WriteHTML($html);
//        $pdf->Output('LF0' .$_POST['id']. '.pdf', 'D');
//
$pdfName = 'LF0' . $_POST['id'] . '.pdf';
$fileName = 'Order-id-LF0' . $_POST['id'] . '-' . $rename . '-' . $rebilling_company . '.csv';

// END - MAKE PDF ORDER

// header('Content-type: application/ms-excel');
// header("Content-Disposition: attachment; filename=$fileName");

$fp = fopen(WP_CONTENT_DIR . '/uploads/csv-pdf/' . $fileName, "w");

foreach ($html->find('tr') as $element) {
	$td = array();
	foreach ($element->find('th') as $row) {
		$td [] = $row->plaintext;
	}

	foreach ($element->find('td') as $row) {
		$td [] = $row->plaintext;
	}
	fputcsv($fp, $td);
}

fclose($fp);

$user_id = get_current_user_id();

$user_info = get_userdata($user_id);
$user_mail = $user_info->user_email;

// ----------------- MDPF -----------------

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetHTMLFooter('
                <table width="100%" style="vertical-align: bottom; font-family: serif;
                    font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;">
                    <tr>
                        <td width="33%">{DATE j-m-Y}</td>
                        <td width="33%" align="center">www.lifetimeshutters.com</td>
                        <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
                    </tr>
                </table>');
$mpdf->WriteHTML($pdf_content);
$mpdf->Output(WP_CONTENT_DIR . '/uploads/csv-pdf/' . 'LF0' . $_POST['id'] . '.pdf');

if ($_POST['pos'] == 0) {
	$attachment1 = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $fileName;
}
$attachment2 = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $pdfName;
//teo   $attachment2china = '';

$attachments = array();
$attachments_summary = array();
$attachments[0] = '';
$attachments[1] = $attachment2;
$attachments_summary[] = $attachment2;

$attachments_china = array();
$attachments_china[0] = $attachment1;
$attachments_china[1] = '';

//get an instance of the WC_Order object
$order = wc_get_order($order_id);

$items = $order->get_items();
$at = 2;
foreach ($items as $item_id => $item_data) {

	$product_id = $item_data['product_id'];
	$attachment_img = get_post_meta($product_id, 'attachment', true);
	$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);

	$img = $attachment_img;
	$pieces = explode(get_home_url() . '/wp-content', $img);
	$pieces[0];
	$pieces[1];

	$attachments[$at] = WP_CONTENT_DIR . $pieces[1];
	$attachments_china[$at] = WP_CONTENT_DIR . $pieces[1];
	if (!empty($attachmentDraw)) {
		$piecesDraw = explode(get_home_url() . '/wp-content', $attachmentDraw);
		$at++;
		$attachments[$at] = WP_CONTENT_DIR . $piecesDraw[1];
		$attachments_china[$at] = WP_CONTENT_DIR . $piecesDraw[1];
	}
	$at++;
}

if (!empty($_POST['china']) && $_POST['table']) {

	// $to = 'marian93nes@gmail.com';
	$multiple_recipients = array(
		'tudor@fiqs.ro', 'tudor@lifetimeshutters.com',
	);
	$subject = 'LF0' . $_POST['id'] . ' - ' . $name . ' - Matrix Order Attached - Resent';
	$body = 'Hi July, Kevin<br><br>New order. See Order Attached <br>';
	foreach ($items as $item_id => $item_data) {

		$product_id = $item_data['product_id'];
		$attachment_img = get_post_meta($product_id, 'attachment', true);
		if (!empty($attachment_img)) {
			$body .= get_the_title($product_id) . ': <a href="' . $attachment_img . '">' . $attachment_img . '</a> <br>';
		}
		$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
		if (!empty($attachmentDraw)) {
			$body .= get_the_title($product_id) . ': <a href="' . $attachmentDraw . '">' . $attachmentDraw . '</a> <br>';
		}
	}
	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

		wp_mail($multiple_recipients, $subject, $body, $headers, $attachments_china);
//	wp_mail($multiple_test_mails, $subject, $body, $headers, $attachments_china);
} else {

	// $to = 'marian93nes@gmail.com';
	// $multiple_recipients = array(
	//     'order@lifetimeshutters.com'
	// );
	if ($favorite == 'yes') {
		$multiple_recipients = array(
			'order@lifetimeshutters.com', 'accounts@lifetimeshutters.com', 'caroline@anyhooshutter.com', 'july@anyhooshutter.com',
		);
	} else {
		$multiple_recipients = array(
			'order@lifetimeshutters.com', 'accounts@lifetimeshutters.com',
		);
	}

	$subject = 'LF0' . $_POST['id'] . ' - ' . $name . ' - Matrix Order Attached - Resent';
	$body = 'Hi July, Kevin<br><br>New order. See Order Attached <br>';
	foreach ($items as $item_id => $item_data) {

		$product_id = $item_data['product_id'];
		$attachment_img = get_post_meta($product_id, 'attachment', true);
		if (!empty($attachment_img)) {
			$body .= get_the_title($product_id) . ': <a href="' . $attachment_img . '">' . $attachment_img . '</a> <br>';
		}
		$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
		if (!empty($attachmentDraw)) {
			$body .= get_the_title($product_id) . ': <a href="' . $attachmentDraw . '">' . $attachmentDraw . '</a> <br>';
		}
	}
	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

		wp_mail($multiple_recipients, $subject, $body, $headers, $attachments_china);
//	wp_mail($multiple_test_mails, $subject, $body, $headers, $attachments_china);
//		wp_mail('mike@lifetimeshutters.com', $subject, $body, $headers, $attachments);
}

