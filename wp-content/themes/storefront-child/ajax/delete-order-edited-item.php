<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

echo $order_id = $_POST['order_id'];
echo $item_id = $_POST['item_id'];

if( isset($_POST["item_id"]) ){
    $order = new WC_Order($order_id);
    wc_delete_order_item( $item_id );
    $order->calculate_totals();
}
