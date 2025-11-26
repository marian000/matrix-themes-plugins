<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

$orders_ids = $_POST['orders'];

$nr_orders = count($orders_ids);

$count = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$edit_placed_order_param = '';

foreach ($orders_ids as $key => $order_id) {
    $count++;

    global $wpdb;
    $tablename = $wpdb->prefix . 'custom_orders';
    $myOrder = $wpdb->get_row("SELECT sqm, usd_price, delivery, shipping_cost FROM $tablename WHERE idOrder = $order_id", ARRAY_A);

    if (!empty($myOrder)) {
        if ($myOrder['sqm']) {
            update_post_meta($order_id, 'order_sqm', $myOrder['sqm']);
        }
        if ($myOrder['usd_price']) {
            update_post_meta($order_id, 'dolar_price', $myOrder['usd_price']);
        }
        if ($myOrder['delivery']) {
            update_post_meta($order_id, 'shipping_type', $myOrder['delivery']);
        }
        if ($myOrder['shipping_cost']) {
            update_post_meta($order_id, 'shipping_cost', $myOrder['shipping_cost']);
        }
    } else {
        echo 'Order - '. $order_id .' - NOT FINDED !!!';
    }

}
if ($nr_orders == $count || $nr_orders < $count) {
    echo 'Updated Orders METAS ! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
    //  update_user_meta($user_id, 'orders_user_updated', 'updated');
} else {
    echo 'Updated Not All Orders METAS! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
}
echo 'Orders  - UPDATED ITEMS to V2 !!!';

