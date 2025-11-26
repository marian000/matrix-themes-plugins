<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.
    'wp-load.php');

    
global $wpdb;
if ( $addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key)) ) {
    $addresses = maybe_unserialize($addresses);
}

$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

//$order_ref = $_POST['order_ref'];


    $string = $_POST['order_ref'];
    $pre = 'LF0';

    
    if (strpos($string,$pre) !== false) {
        
        $cust_order = $string;    
        $customer_order = substr($cust_order, 3);

        $pieces = explode("LF0", $_POST['order_ref']);
        $pieces[0]; // piece1
        $customer_order = $pieces[1]; // piece2
        //echo $customer_order;
    }else {
        $customer_order = $_POST['order_ref'];
    }
    
    if(is_numeric($_POST['order_ref'])){
       // echo '</br> 1 </br>';

        $articles = get_posts(
            array(
                'numberposts' => -1,
                'post_status' => 'any',
                'post_type' => 'shop_order',
            'meta_query' => array(
                array(
                    'key'     => '_order_number',
                    'value'   => $customer_order,
                    'compare' => 'LIKE',
                    ),
                ),
            )
        );

    }
    elseif(strpos($string,$pre) !== false){
        //echo '</br> 2 </br>';
        $articles = get_posts(
            array(
                'numberposts' => -1,
                'post_status' => 'any',
                'post_type' => 'shop_order',
            'meta_query' => array(
                array(
                    'key'     => '_order_number',
                    'value'   => $customer_order,
                    'compare' => 'LIKE',
                    ),
                ),
            )
        );
    }


    //print_r($articles);

    foreach($articles as $article ){
        //print_r($article);
            $article_id = $article->ID;
            $order = new WC_Order($article_id); 
            $i = 1;

                $items = $order->get_items(); 
                $order_data = $order->get_data();

            //echo 'LF0'.$order->get_order_number().' - '.get_post_meta($article_id,'cart_name',true).'';

            $detalii = 'Action required for On Hold Order - LF0'.$order->get_order_number().' - '.get_post_meta($article_id,'cart_name',true).'';

            $order_detail['customer_id'] = get_post_meta( $article_id, '_customer_user', true );
            $order_detail['customer_first_name'] = get_post_meta( $article_id, '_billing_first_name', true );
            $order_detail['customer_last_name'] = get_post_meta( $article_id, '_billing_last_name', true );
            $order_detail['customer_name'] = get_post_meta( $article_id, '_billing_first_name', true ).' '.get_post_meta( $article_id, '_billing_last_name', true );
            $order_detail['customer_email'] = get_post_meta( $article_id, '_billing_email', true );
            $order_detail['order_name'] = $detalii;
            
            $myJSON = json_encode($order_detail);

            print_r( $myJSON );
        
    
    }








// $args = array(
//     'customer_id' => $user_id,
// );

// //print_r($user_id);
// $orders = wc_get_orders();
// $total = array();
// $i = 0;
// // echo '<pre>';
// // 	print_r($orders);
// // echo '</pre>';
// foreach($orders as $order ){ 
//     $items = $order->get_items(); 
//     $order_data = $order->get_data();
//     $order_status = $order_data['status'];
//     $order = new WC_Order( $order->id );
//     $order_detail[$i]['customer_first_name'] = get_post_meta( $order->id, '_billing_first_name', true );
//     $order_detail[$i]['customer_last_name']  = get_post_meta( $order->id, '_billing_last_name', true );
//     $order_detail[$i]['customer_email']     = get_post_meta( $order->id, '_billing_email', true );
                            
//     $i++;
// }

// $myJSON = json_encode($order_detail);

// print_r( $myJSON );