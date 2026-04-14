<?php

/**
 * create-csv-admin.php
 *
 * Versiunea ADMIN a scriptului de generare CSV/PDF si trimitere emailuri.
 * Diferente fata de createcsv.php (versiunea dealer):
 *   - Nu are protectie anti-spam (mail_send) — adminul poate retrimite oricand
 *   - Subiectul emailului contine sufixul "Resent" pentru a distinge retrimiterea
 *   - Internul (order@, accounts@) primeste CSV + PDF + imagini (nu doar CSV)
 *   - Emailul de confirmare catre dealer/client NU se trimite din aceasta versiune
 *   - Trimiterea catre Anyhoo este comentata (dezactivata intentionat)
 *
 * Apelat manual de admin din interfata de administrare a comenzilor.
 */

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
include('simple_html_domv2.php');

require_once WP_CONTENT_DIR . '/themes/storefront-child/csvs/mpdf/vendor/autoload.php';

$order_id = $_POST['id_ord_original'];

// mail_send este citit dar NU blocheaza trimiterea — adminul poate retrimite oricand
$mail_send = get_post_meta($order_id, 'mail_send', true);
$customer_id = get_post_meta($order_id, '_customer_user', true);

// favorite_user = 'yes' inseamna client Anyhoo (kevin@, july@)
// In aceasta versiune admin, trimiterea catre Anyhoo este dezactivata (vezi mai jos)
$favorite = get_user_meta($customer_id, 'favorite_user', true);

// Adrese de test — folosite in dezvoltare in loc de destinatarii reali
$multiple_test_mails = array(
	'teopro@gmail.com', 'marian93nes@gmail.com',
);

// Marcheaza comanda ca avand emailul trimis (chiar daca era deja marcat)
update_post_meta($order_id, 'mail_send', 1);

$billing_company = get_user_meta($customer_id, 'shipping_company', true);

$pos = isset($_POST['pos']) ? intval($_POST['pos']) : 0;
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
//require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/storefront-child/html2fpdf/html2fpdf.php');
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

// CSV-ul se genereaza doar daca pos == 0 (nu e comanda POS)
$csv_file = '';
if ($pos == 0) {
	$csv_file = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $fileName;
}
$pdf_file = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $pdfName;

$order = wc_get_order($order_id);
$items = $order->get_items();

// Colecteaza caile locale ale imaginilor produselor din comanda
// (attachment = imagine produs, attachmentDraw = desen tehnic)
$product_images = array();
foreach ($items as $item_id => $item_data) {
	$product_id = $item_data['product_id'];
	$attachment_img = get_post_meta($product_id, 'attachment', true);
	$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);

	if (!empty($attachment_img)) {
		$pieces = explode(get_home_url() . '/wp-content', $attachment_img);
		if (!empty($pieces[1])) {
			$product_images[] = WP_CONTENT_DIR . $pieces[1];
		}
	}
	if (!empty($attachmentDraw)) {
		$piecesDraw = explode(get_home_url() . '/wp-content', $attachmentDraw);
		if (!empty($piecesDraw[1])) {
			$product_images[] = WP_CONTENT_DIR . $piecesDraw[1];
		}
	}
}

// Tipuri de atasamente folosite in emailuri:
//   $attach_csv_pdf_images — CSV + PDF + imagini (pentru intern: order@, accounts@)
//   $attach_pdf_images     — PDF + imagini (pentru Mike)
//   $attach_csv_only       — doar CSV (pentru Anyhoo — dezactivat momentan)
//   $attach_csv_images     — CSV + imagini fara PDF (pentru ramura China)
$attach_csv_pdf_images = array();
if (!empty($csv_file)) {
	$attach_csv_pdf_images[] = $csv_file;
}
$attach_csv_pdf_images[] = $pdf_file;
$attach_csv_pdf_images = array_merge($attach_csv_pdf_images, $product_images);

$attach_pdf_images = array();
$attach_pdf_images[] = $pdf_file;
$attach_pdf_images = array_merge($attach_pdf_images, $product_images);

$attach_csv_only = array();
if (!empty($csv_file)) {
	$attach_csv_only[] = $csv_file;
}

$attach_csv_images = array();
if (!empty($csv_file)) {
	$attach_csv_images[] = $csv_file;
}
$attach_csv_images = array_merge($attach_csv_images, $product_images);

// -------------------------------------------------------------------------
// RAMURA CHINA: comanda marcata ca "china" → trimite CSV+imagini la Tudor
// Subiectul contine "Resent" pentru a distinge retrimiterea admin de trimiterea initiala
// -------------------------------------------------------------------------
if (!empty($_POST['china']) && $_POST['table']) {

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

	wp_mail($multiple_recipients, $subject, $body, $headers, $attach_csv_images);
	// wp_mail($multiple_test_mails, $subject, $body, $headers, $attach_csv_images); // linie de test

} else {

	// -------------------------------------------------------------------------
	// RAMURA NORMALA (admin resend)
	//
	// Email 1: Intern (order@, accounts@) → CSV + PDF + imagini
	//   Diferenta fata de versiunea dealer: internul primeste si PDF-ul, nu doar CSV
	//
	// Email 2: Mike (mike@) → PDF + imagini
	//
	// Email 3: Anyhoo → DEZACTIVAT intentionat in versiunea admin
	//   Motivul: adminul retrimite doar intern, nu vrea sa spam-uiasca Anyhoo
	//   Daca trebuie reactivat, decomentati blocul de mai jos
	// -------------------------------------------------------------------------

	$subject = 'LF0' . $_POST['id'] . ' - ' . $name . ' - Matrix Order Attached - Resent';
	$body = 'Hi,<br><br>New order. See Order Attached <br>';
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

	// Email 1: Intern primeste CSV + PDF + imagini (in versiunea dealer primeste doar CSV)
	$internal_recipients = array('order@lifetimeshutters.com', 'accounts@lifetimeshutters.com');
	wp_mail($internal_recipients, $subject, $body, $headers, $attach_csv_pdf_images);

	// Email 2: Mike primeste PDF + imagini
	wp_mail('mike@lifetimeshutters.com', $subject, $body, $headers, $attach_pdf_images);

	// Email 3: Anyhoo — dezactivat in versiunea admin (decomentati daca e necesar)
	// $anyhoo_recipients = array('kevin@anyhooshutter.com', 'july@anyhooshutter.com');
	// wp_mail($anyhoo_recipients, $subject, $body, $headers, $attach_csv_only);
}

