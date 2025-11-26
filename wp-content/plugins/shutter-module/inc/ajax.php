<?php
// ajax for item where i verify in cart if name exists in cart items
add_action( 'wp_ajax_cart_uniq_name_item', 'cart_uniq_name_item' );
add_action( 'wp_ajax_nopriv_cart_uniq_name_item', 'cart_uniq_name_item' );
function cart_uniq_name_item() {
    global $wpdb;

    $cart = WC()->cart->get_cart();
    $items_name = array();
    if (count($cart) >= 1) {
        foreach ($cart as $cart_item_key => $cart_item) {
            // get id product
            $product_id = $cart_item['product_id'];
            $property_room_other = get_post_meta($product_id, 'property_room_other', true);
            $items_name[] = $property_room_other;
        }
    }

    echo json_encode($items_name);

    wp_die();
}

