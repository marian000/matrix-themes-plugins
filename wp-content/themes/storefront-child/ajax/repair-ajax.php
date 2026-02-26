<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

parse_str($_POST['item_repair'], $repair);
//print_r($repair);
//    print_r($repair['order-id-scv']);
//    print_r($repair['order-id-original']);
//    print_r($repair['description-damage-error']);
//    print_r($repair['remedial-action-request']);
//    print_r($repair['warranty']);
//    print_r($repair['item-id']);
//    print_r($repair['myplugin_attachment_id_array']);
global $wpdb;

$current_user_id = get_current_user_id();

// Create post object
$my_post = array(
    'post_type' => 'order_repair',
    'post_title' => 'LFR' . $repair['order-id-scv'] . '-' . get_post_meta($repair['order-id-original'], 'cart_name', true),
    'post_status' => 'publish',
    'post_author' => $current_user_id,
);

// Insert the post into the database
$post_id = wp_insert_post($my_post);
update_post_meta($post_id, 'order-id-scv', $repair['order-id-scv']);
update_post_meta($post_id, 'order-id-original', $repair['order-id-original']);
update_post_meta($post_id, 'description-damage-error', $repair['description-damage-error']);
update_post_meta($post_id, 'remedial-action-request', $repair['remedial-action-request']);
update_post_meta($post_id, 'warranty', $repair['warranty']);
update_post_meta($post_id, 'items-id', $repair['item-id']);
update_post_meta($post_id, 'attachment_id_array', $repair['myplugin_attachment_id_array']);
update_post_meta($post_id, 'order_status', 'processing');


/***************************************************************/
//   Send mail
/***************************************************************/

matrix_send_repair_notification($post_id);



//    MAIL FOR CHINA

//    $to_china = 'july@anyhooshutter.com';
//    $multiple_recipients_china = array(
//        'tudor@fiqs.ro', 'mike@lifetimeshutters.com'
//    );
//    $subject_china = 'Repair Order LFR' . $repair['order-id-scv'] . ' for Original Order LF' . $repair['order-id-scv'] . '';
//    $body_china = 'Dear July, a new repair order has been uploaded to Matrix. Please confirm production and delivery';
//    $headers_china = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <tudor@lifetimeshutters.com>');

//    wp_mail($multiple_recipients_china, $subject_china, $body_china, $headers_china);
