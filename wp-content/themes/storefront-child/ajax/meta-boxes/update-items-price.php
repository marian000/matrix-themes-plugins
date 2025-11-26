<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

//$user_id = $_POST['id_user'];
//$order_id = $_POST['id_order'];
//

$orders_ids = $_POST['orders'];

//print_r($orders_ids);

$nr_orders = count($orders_ids);

$count = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$edit_placed_order_param = '';

foreach ($orders_ids as $key => $order_id) {
    $count++;

    $order = wc_get_order($order_id);
    $items = $order->get_items();

    $myNonFormatedFloat = get_post_meta($order_id, '_order_total', true);
    $myGermanNumber = number_format($myNonFormatedFloat, 2, '.', '');
    update_post_meta($order_id, '_order_total', $myGermanNumber);

    foreach ($items as $item_id => $item_data) {

        $product_id = $item_data['product_id'];

        if (!empty(wc_get_order_item_meta($item_id, '_price', true))) {
            $myNonFormatedFloat = wc_get_order_item_meta($item_id, '_price', true);
            $myGermanNumber = number_format($myNonFormatedFloat, 2, '.', '');
            wc_update_order_item_meta($item_id, 'total_price', $myGermanNumber);
            wc_update_order_item_meta($item_id, '_price', $myGermanNumber);
        }

    }

}
if ($nr_orders == $count || $nr_orders < $count) {
    echo 'Updated Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
    //  update_user_meta($user_id, 'orders_user_updated', 'updated');
} else {
    echo 'Updated Not All Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
}
echo 'Orders  - UPDATED ITEMS to V2 !!!';
