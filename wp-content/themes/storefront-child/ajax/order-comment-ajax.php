<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.
    'wp-load.php');

print_r($_POST);
echo $order_id = $_POST['id'];
echo $comment = $_POST['comment'];

update_post_meta( $order_id,'order_comment',$comment );

