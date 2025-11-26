<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.
    'wp-load.php');

print_r($_POST);
$link = $_POST['link'];
$order_id = $_POST['order_id'];
update_post_meta($order_id,'csv_url',$link);

//$product_attributes = array();

$attachments = $link;
$headers = 'From: My Name <myname@example.com>' . "\r\n";

wp_mail( 'marian93nes@lifetimeshutters.com', 'Test Matrix attachment', 'Send attachments', $headers, $attachments );