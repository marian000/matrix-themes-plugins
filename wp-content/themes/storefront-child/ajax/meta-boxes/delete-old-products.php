<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

$prods_ids = $_POST['prods'];

$nr_prods = count($prods_ids);

$count = 0;

foreach ($prods_ids as $key => $prod_id) {
    $count++;
    wp_trash_post($prod_id);
}
if ($nr_prods == $count || $nr_prods < $count) {
    echo 'Deleted prods ! Nr Prods: ' . $nr_prods . ' - counter Prods: ' . $count;
    //  update_user_meta($user_id, 'orders_user_updated', 'updated');
} else {
    echo 'Not All Prods Deleted! Nr Prods: ' . $nr_prods . ' - counter Prods: ' . $count;
}
echo 'OrdeProdsrs  - Deleted for V2 !!!';

