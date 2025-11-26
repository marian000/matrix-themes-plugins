<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

$order_id = $_POST['order_id'];
$quickbooks_invoice = $_POST['quickbooks_invoice'];
if ($quickbooks_invoice == 1) {
    update_post_meta($order_id, 'QB_invoice', true);
}
else {
    update_post_meta($order_id, 'QB_invoice', false);
}
