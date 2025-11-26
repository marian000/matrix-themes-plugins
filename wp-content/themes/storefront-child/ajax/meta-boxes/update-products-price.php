<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

//$user_id = $_POST['id_user'];
//$order_id = $_POST['id_order'];
//

$prods_ids = $_POST['orders'];

//print_r($orders_ids);

$nr_prods = count($prods_ids);

$count = 0;
$edit_placed_order_param = '';


foreach ($prods_ids as $key => $product_id) {
    $count++;

        if (!empty(get_post_meta($product_id, '_price', true))) {
            $myNonFormatedFloat = get_post_meta($product_id, '_price', true);
            $myGermanNumber = number_format($myNonFormatedFloat, 2, '.', '');
            update_post_meta($product_id, '_price', $myGermanNumber);
        }
        if (!empty(get_post_meta($product_id, '_regular_price', true))) {
            $myNonFormatedFloat = get_post_meta($product_id, '_regular_price', true);
            $myGermanNumber = number_format($myNonFormatedFloat, 2, '.', '');
            update_post_meta($product_id, '_regular_price', $myGermanNumber);
        }
        if (!empty(get_post_meta($product_id, '_sale_price', true))) {
            $myNonFormatedFloat = get_post_meta($product_id, '_sale_price', true);
            $myGermanNumber = number_format($myNonFormatedFloat, 2, '.', '');
            update_post_meta($product_id, '_sale_price', $myGermanNumber);
        }


}
if ($nr_prods == $count || $nr_prods < $count) {
    echo 'UPDATED ITEMS! Nr Items: ' . $nr_prods . ' - counter Items: ' . $count;
    //  update_user_meta($user_id, 'orders_user_updated', 'updated');
} else {
    echo 'Updated Not All Orders! Nr Orders: ' . $nr_prods . ' - counter orders: ' . $count;
}
echo 'Price Items - UPDATED ITEMS !!!';
