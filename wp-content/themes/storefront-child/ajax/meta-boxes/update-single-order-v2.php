<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

$user_id = $_POST['id_user'];
$order_id = $_POST['id_order'];

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';


$count = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$edit_placed_order_param = '';

$order = wc_get_order($order_id);
$items = $order->get_items();
$user_id_customer = get_post_meta($order_id, '_customer_user', true);
$user_id = $user_id_customer;

$array_att = array();

$order_items_array = array();

$propertys_array = array('property_total' => 'sqm', 'property_room_other' => 'room', 'property_material' => 'material', 'property_style' => 'installation', 'attachment' => 'attachment', 'attachmentDraw' => 'attachmentDraw', 'property_width' => 'width', 'property_height' => 'height', 'property_midrailheight' => 'midrail_height', 'property_midrailheight2' => 'midrail_height2', 'property_midraildivider1' => 'midrail_divider', 'property_midraildivider2' => 'midrail_divider2', 'property_solidtype' => 'solid_type', 'property_midrailpositioncritical' => 'position_is_critical', 'property_totheight' => 't-o-t_height', 'property_horizontaltpost' => 'horizontal_t_post', 'property_bladesize' => 'louvre_size', 'property_trackedtype' => 'track_installation_type', 'property_bypasstype' => 'by-pass_type', 'property_lightblocks' => 'light_blocks', 'property_tracksnumber' => 'tracks_number', 'property_fit' => 'fit', 'property_frametype' => 'frame', 'property_frameleft' => 'frame_left', 'property_frameright' => 'frame_right', 'property_frametop' => 'frame_top', 'property_framebottom' => 'frame_bottom', 'bay-post-type' => 'bay-post-type', 't-post-type' => 't-post-type', 'property_builtout' => 'builtout', 'property_stile' => 'style', 'property_hingecolour' => 'hinge_colour', 'property_shuttercolour' => 'shutter_colour', 'property_shuttercolour_other' => 'shutter_colour_other', 'property_controltype' => 'control_type', 'property_controlsplitheight' => 'control_split_height', 'property_controlsplitheight2' => 'control_split_height2', 'property_layoutcode' => 'layout_code', 'counter_t' => 'counter_t', 'counter_b' => 'counter_b', 'counter_c' => 'counter_c', 'counter_g' => 'counter_g', 'property_layoutcode_tracked' => 'property_layoutcode', 'property_t1' => 't1', 'property_g1' => 'g1', 'property_tposttype' => 't-post_type', 'property_depth' => 'depth', 'property_volume' => 'volume', 'property_blackoutblindcolour' => 'blackout_blind_colour', 'property_solidpanelheight' => 'solid_panel_height', 'property_sparelouvres' => 'spare_louvres', 'property_ringpull' => 'ringpull', 'property_locks' => 'locks', 'property_ringpull_volume' => 'ringpull_volume', 'property_locks_volume' => 'locks_volume', '_price' => '_price', 'property_nowarranty' => 'no_warranty', 'comments_customer' => 'comments_customer', 'shutter_category' => 'shutter_category');

foreach ($items as $item_id => $item_data) {


    // if (empty($item_data['material'])) {
    //$product = $item_data->get_product();
    //$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
    //print_r($item_id);
    $product_id = $item_data['product_id'];
    $title = get_the_title($product_id);
//    $property_room_other = get_post_meta($product_id, 'property_room_other', true);
//    $attachment = get_post_meta($product_id, 'attachment', true);
//    $attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
//    $array_att[] = $attachment;

    //Returns All Term Items for "product_cat"
    $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
    $item_propertyes = array();

//    $product = wc_get_product($post_id);
//    wc_get_order($order_id)->add_product($product, $item_data['quantity']);

    foreach ($propertys_array as $property => $new_property_meta) {
        if (!empty($atributes[get_post_meta($product_id, $property, true)]) && $atributes[get_post_meta($product_id, $property, true)] != '') {
            $item_propertyes[$property] = $atributes[get_post_meta($product_id, $property, true)];
            wc_update_order_item_meta($item_id, $new_property_meta, $atributes[get_post_meta($product_id, $property, true)]);
            $item_propertyes_new[$new_property_meta] = $atributes[get_post_meta($product_id, $property, true)];
        } else {
            $item_propertyes[$property] = get_post_meta($product_id, $property, true);
            wc_update_order_item_meta($item_id, $new_property_meta, get_post_meta($product_id, $property, true));
            $item_propertyes_new[$new_property_meta] = get_post_meta($product_id, $property, true);
        }
        if ($property == 'property_width' || $property == 'property_height' || $property == 'property_height' || $property == 'property_depth' || $property == 'property_midraildivider1' || $property == 'property_midrailheight' || $property == 'property_midrailheight2' || $property == 'property_midraildivider2' || $property == 'property_tposttype') {
            $item_propertyes[$property] = get_post_meta($product_id, $property, true);
            wc_update_order_item_meta($item_id, $new_property_meta, get_post_meta($product_id, $property, true));
            $item_propertyes_new[$new_property_meta] = get_post_meta($product_id, $property, true);
        }
    }
    if (!empty(get_post_meta($product_id, '_price', true))) {
        wc_update_order_item_meta($item_id, 'total_price', get_post_meta($product_id, '_price', true));
    }
    if (get_post_meta($product_id, 'shutter_category', true) == 'Shutter') {
        wc_update_order_item_meta($item_id, 'product_id', 34433);
    }
    if (get_post_meta($product_id, 'shutter_category', true) == 'Batten') {
        wc_update_order_item_meta($item_id, 'product_id', 34434);
    }
    if (get_post_meta($product_id, 'shutter_category', true) == 'Shutter & Blackout Blind') {
        wc_update_order_item_meta($item_id, 'product_id', 34435);
    }

    // Set counter posts
    $nr_t = get_post_meta($product_id, 'counter_t', true);
    $nr_b = get_post_meta($product_id, 'counter_b', true);
    $nr_c = get_post_meta($product_id, 'counter_c', true);
    $nr_g = get_post_meta($product_id, 'counter_g', true);

    if ($nr_b) {
        wc_update_order_item_meta($item_id, 'counter_b', $nr_b);
    }
    if ($nr_t) {
        wc_update_order_item_meta($item_id, 'counter_t', $nr_t);
    }
    if ($nr_g) {
        wc_update_order_item_meta($item_id, 'counter_g', $nr_g);
    }
    if ($nr_c) {
        wc_update_order_item_meta($item_id, 'counter_c', $nr_c);
    }

    $str = strtoupper(get_post_meta($product_id, 'property_layoutcode', true));
    $layout_code = str_split($str);
    $i = 1;
    $order_b = 1;
    $order_t = 1;
    $order_c = 1;
    $order_g = 1;
    foreach ($layout_code as $key => $l) {

        if ($l == 't' || $l == 'T') {
            $property_t = get_post_meta($product_id, 'property_t' . $i, true);
            wc_update_order_item_meta($item_id, 'property_t' . $key, $property_t);
            $item_propertyes['property_t' . $key] = $property_t;
            $order_t++;
            $i++;
        }
        if ($l == 'b' || $l == 'B') {
            $property_bp = get_post_meta($product_id, 'property_bp' . $i, true);
            wc_update_order_item_meta($item_id, 'property_bp' . $key, $property_bp);
            $item_propertyes['property_bp' . $key] = $property_bp;

            $property_ba = get_post_meta($product_id, 'property_ba' . $i, true);
            wc_update_order_item_meta($item_id, 'property_ba' . $key, $property_ba);
            $item_propertyes['property_ba' . $key] = $property_ba;
            $order_b++;
            $i++;
        }
        if ($l == 'c' || $l == 'C') {
            $property_c = get_post_meta($product_id, 'property_c' . $i, true);
            wc_update_order_item_meta($item_id, 'property_c' . $key, $property_c);
            $item_propertyes['property_c' . $key] = $property_c;
            $order_c++;
            $i++;
        }
        if ($l == 'g' || $l == 'G') {
            $property_g = get_post_meta($product_id, 'property_g' . $i, true);
            wc_update_order_item_meta($item_id, 'property_g' . $key, $property_g);
            $item_propertyes['property_g' . $key] = $property_g;
            $order_g++;
            $i++;
        }

    }

    $item_propertyes['quantity'] = $item_data['quantity'];
    wc_update_order_item_meta($item_id, 'quantity', $item_data['quantity']);
    $order_items_array[$item_id] = $item_propertyes;

    // }

}
//if ($nr_orders == $count || $nr_orders < $count) {
//    echo 'User Updated Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
//    //  update_user_meta($user_id, 'orders_user_updated', 'updated');
//} else {
//    echo 'User Updated Not All Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
//}
echo '<pre>';
echo 'Items transformed : ';
print_r($order_items_array);
echo '</pre>';
echo 'Order ID: ' . $order_id . ' - UPDATED ITEMS to V2 !!!';
