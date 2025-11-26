<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

$container_id = $_POST['container_id'];
$van_number = $_POST['van_number'];

update_post_meta($container_id, 'van_' . $van_number, $_POST['van']);

$van_orders_dealers = get_post_meta($container_id, 'van_' . $van_number, true);

$van_orders = get_post_meta($container_id, 'van' . $van_number . '_orders', true);
if (empty($van_orders)) {
    $van_orders = array();
}

foreach ($van_orders_dealers as $key => $dealer_id) {

    update_user_meta($dealer_id, 'van_nr_' . $container_id, $van_number);

    $container_orders = get_post_meta($container_id, 'container_orders', true);
    foreach ($container_orders as $order_id) {
        $user_id_customer = get_post_meta($order_id, '_customer_user', true);
        if ($user_id_customer == $dealer_id) {
            update_post_meta($order_id, 'van_nr', $van_number);
            $van_orders[] = $order_id;
        }
    }

}
update_post_meta($container_id, 'van_' . $van_number . '_orders', $van_orders);