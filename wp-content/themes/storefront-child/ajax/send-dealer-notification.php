<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

$id_ticket = $_POST['idTicket'];

$ticket_id = $id_ticket;

$post = get_post($ticket_id);

$title = $post->post_title;

$user_id_ticket = get_post_meta($ticket_id, '_stgh_contact', true);

$user_info = get_userdata($user_id_ticket);
$email = $user_info->user_email;

$first_name = get_user_meta($user_id_ticket, 'billing_first_name', true);
$last_name = get_user_meta($user_id_ticket, 'billing_last_name', true);

$permalink = get_site_url() . '/factory-queries/';

$multiple_recipients = array(
    $email, 'tudor@lifetimeshutters.com'
);

$bodyth = 'Dear ' . $first_name . ' ' . $last_name . ',
                <br><br>
                The above order is still on hold at the factory as they have questions they need you to answer,
                <br><br>
                Please log on to the Matrix Ordering System at Factory Querries Page  to address the question.
                <br><br>
                In case of any queries please contact Customer Support on 0777 246 0159
                <br><br>
                Best Regards<br>
                Matrix Ordering System';

$subth = 'Kind reminder: Not-Answered Querry for Order ' . $title;
//                wp_mail( $multiple_recipients, 'Cron Ticket notanswered mail', 'Automatic email from WordPress to test cron for ticket id: '.$ticket_id.' with title: '.get_the_title($ticket_id));
$headersth = array('Content-Type: text/html; charset=UTF-8');
wp_mail($multiple_recipients, $subth, $bodyth, $headersth);