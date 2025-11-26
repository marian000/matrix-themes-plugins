<?php
    $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
    include($path . 'wp-load.php');
    
    //parse_str($_POST['prod_ids'], $prod_ids);
    $products_ids = $_POST['prod_ids'];
    $shipping_class = $_POST['shipping_class'];
    print_r( $products_ids );
    
    print_r( $shipping_class );
    
    foreach ( $products_ids as $product_id ){
        $product = wc_get_product($product_id);
        $int_shipping = get_term_by('slug', $shipping_class, 'product_shipping_class');
        //$product->set_shipping_class_id($int_shipping->term_id);
        // Set the shipping class for the product
        wp_set_post_terms( $product_id, array($int_shipping->term_id), 'product_shipping_class' );
    }