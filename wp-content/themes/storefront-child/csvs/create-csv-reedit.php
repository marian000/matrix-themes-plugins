<?php

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
include('simple_html_domv2.php');

require_once WP_CONTENT_DIR . '/themes/storefront-child/csvs/mpdf/vendor/autoload.php';
//include( '/wp-content/themes/storefront-child/html2fpdf/html2fpdf.php' );

$order_id = $_POST['id_ord_original'];

$mail_send = get_post_meta($order_id, 'revised_mail_send', true);
$customer_id = get_post_meta($order_id, '_customer_user', true);
$favorite = get_user_meta($customer_id, 'favorite_user', true);

if (empty($mail_send)) {

    update_post_meta($order_id, 'mail_send', 1);

    $billing_company = get_user_meta($customer_id, 'shipping_company', true);

    $pos = $_POST['pos'];
    $component = $_POST['component'];
    $name = $_POST['name'];
    //$vowels = array(" ", ".", "#");
    $vowels = array(" ", ".", "#", "'", "\"", "`", "/", "\\", ":", ";", "|", "?", "*", "<", ">");

    $rename = str_replace($vowels, "", $name);
    $rebilling_company = str_replace($vowels, "", $billing_company);

    $html = str_get_html($_POST['table']);

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
                        <td width="50%" style="text-align: right;"><img class="logo-img site-logo-img" style="text-align: center; margin: 0 auto;" src="https://matrix.lifetimeshutters.com/wp-content/uploads/2019/11/lifetime-shutters-logo-e1575042323515.png" alt="Plantation Shutters &amp; Windows" title=""></td>
                    </tr>
                </table>
                <h3 style="text-align: center;"><strong>Order Summary - Proforma Invoice</strong></h3>
                <br>' . $items_table . '</body>';
    $pdf_content .= '</html>';

    // MAKE PDF ORDER

    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-content/themes/storefront-child/html2fpdf/html2fpdf.php');
    // exit($content);

//
    $fileName = 'REVISED-Order-id-LF0' . $_POST['id'] . '-' . $rename . '-' . $rebilling_company . '.csv';
    $pdfNmae = 'REVISED-LF0' . $_POST['id'] . '.pdf';

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

    //  ----------------- GRABZIT -----------------

//        $grabzIt = new \GrabzIt\GrabzItClient("ZGZiNjI2NzZlOGU3NGU3NmEzNjdmYzc3MDE1N2ZiY2U=", "IjpCPz99WD8/PT9HPxZwBX1xP0M/Pz93dGo/Cj8/Uz8=");
//
//        $options = new \GrabzIt\GrabzItPDFOptions();
//        $options->setMarginTop(20);
//        $options->setMarginBottom(20);
//
//        $grabzIt->HTMLToPDF($pdf_content, $options);
//        $grabzIt->SaveTo(WP_CONTENT_DIR . '/uploads/csv-pdf/' .'grabzit-LF0' . $_POST['id'] . '.pdf');

    // ----------------- MDPF -----------------

    $mpdf = new \Mpdf\Mpdf();

//        $mpdf->SetHTMLHeader('
//                <table width="100%">
//                    <tr>
//                        <td width="33%">Lifetime Shutters & Windows Ltd
//                                        7 Lichfield Terrace Sheen Road
//                                        Richmond
//                                        SR
//                                        TW9 1AS
//                                        02089401418
//                                        office@lifetimeshutters.co.uk
//                                        VAT Registration No.: 986 2325
//                                        89
//                                        Company Registration No.
//                                        07113670
//                        </td>
//                        <td width="33%" align="center">
//                           <img class="logo-img site-logo-img" src="https://dematrix.richmond-shutters.co.uk/wp-content/uploads/2019/11/lifetime-shutters-logo.png" alt="Plantation Shutters &amp; Windows" title="">
//                        </td>
//                        <td width="33%" style="text-align: right;">Tax Invoice</td>
//                    </tr>
//                </table>');
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
    $mpdf->Output(WP_CONTENT_DIR . '/uploads/csv-pdf/' . 'REVISED-LF0' . $_POST['id'] . '.pdf');

    if ($_POST['pos'] == 0 || $_POST['component'] == 0) {
        $attachment1 = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $fileName;
    }
    // $attachment2 = WP_CONTENT_DIR . '/uploads/csv-pdf/' . $pdfNmae;
    $attachment2 = WP_CONTENT_DIR . '/uploads/csv-pdf/' . 'REVISED-LF0' . $_POST['id'] . '.pdf';

    $attachments = array();
    $attachments_summary = array();
    $attachments[0] = $attachment1;
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

    // $to = 'marian93nes@gmail.com';
    // $multiple_recipients = array(
    //     'order@lifetimeshutters.com'
    // );

    $multiple_recipients = array(
        'order@lifetimeshutters.com', 'accounts@lifetimeshutters.com'
    );
    $subject = 'REVISED ORDER: LF0' . $_POST['id'] . ' - ' . $name . ' - Matrix Order Attached';
    $body = 'See REVISED Order Attached <br>';
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


    if ($customer_id == 1) {
        wp_mail('marian93nes@gmail.com', $subject, $body, $headers, $attachments);
        wp_mail('marian93nes@gmail.com', $subject, $body, $headers, $attachment2);
    } else {
        wp_mail($multiple_recipients, $subject, $body, $headers, $attachments);
        wp_mail('order@lifetimeshutters.com', $subject, $body, $headers, $attachment2);
    }


    $order_id = wc_sequential_order_numbers()->find_order_by_order_number($_POST['id']);

    $mess = '
        Dear ' . get_user_meta(get_current_user_id(), 'billing_company', true) . ', <br />
        <br />
        Thank you for your recent order with Matrix.<br />
        <br />
        Please quote the following details should you have any questions regarding this order at any stage.<br />
        <br />
        Lifetime Number: LF0' . $_POST['id'] . '<br />
        Customer: ' . $name . ' <br />
        <br />
        You can find the Order Summary - Proforma Invoice for order reference ' . get_post_meta($order_id, 'cart_name', true) . ' in the attachment or by following this link:<br />
        <br />
        <a href="https://matrix.lifetimeshutters.com/view-order/?id=' . $order_id * 1498765 * 33 . '">
        https://matrix.lifetimeshutters.com/view-order/?id=' . $order_id * 1498765 * 33 . '</a><br />
        <br />
        Your order has been sent to our factory ready for manufacture. Once payment has been received manufacturing will commence.<br />
        <br />
        Payments can be made via bank transfer to the following account: <br />
        <br />
        Account Name: Lifetime Shutters<br />
        Sort Code: 20-46-73<br />
        Account Number: 13074145<br />
        <br />
        <strong style="color:green; font-size:1.2em;">Amount due: $' . wc_format_decimal($order->get_total(), 2) . '</strong><br />
        <br />
        We will contact you to advise of delivery arrangements in due course.<br />
        <br />
        Should you have any questions please contact us at order@lifetimeshutters.com <br />
        <br />
        Kind regards, <br />
        <br />
        Accounts Department <br />
        <br />
        ';

    $user_id = get_current_user_id();

    $user_info = get_userdata($user_id);
    $user_mail = $user_info->user_email;
    $billing_email = get_user_meta($user_id, 'billing_email', true);
    $multiple_recipients = array(
        $user_mail, $billing_email
    );

    //print_r($user_mail);

    $order_id = wc_sequential_order_numbers()->find_order_by_order_number($_POST['id']);
    $name = get_post_meta($order_id, 'cart_name', true);

    $single_email = array(
        $user_mail
    );
    //$single_email = 'marian93nes@gmail.com';

    $subject2 = 'REVISED ORDER: LF0' . $_POST['id'] . ' - ' . $name . ' - Order Summary - Proforma Invoice!!';
    $headers2 = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

    // wp_mail( $single_email, $subject2, $mess, $headers2, $attachments ); //temporary disabled attachments by Teo

    if ($customer_id == 1) {
        wp_mail('marian93nes@gmail.com', $subject2, $mess, $headers2, $attachments_summary);
    } else {
        if ($user_mail == $billing_email) {
            wp_mail($single_email, $subject2, $mess, $headers2, $attachments_summary);
        } else {
            wp_mail($multiple_recipients, $subject2, $mess, $headers2, $attachments_summary);
        }
    }
}
