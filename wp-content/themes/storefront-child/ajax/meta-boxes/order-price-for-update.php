<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

echo $order_id = $_POST['order_id'];
echo '<br>';
echo $price_for_update = $_POST['price_for_update'];
echo '<br>';

update_post_meta($order_id, 'price_for_update', $price_for_update);

print_r(price_for_update);
