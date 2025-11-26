<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

parse_str($_POST['item_repair'], $repair);
//print_r($repair);
//    print_r($repair['order-id-scv']);
//    print_r($repair['order-id-original']);
//    print_r($repair['description-damage-error']);
//    print_r($repair['remedial-action-request']);
//    print_r($repair['warranty']);
//    print_r($repair['item-id']);
//    print_r($repair['myplugin_attachment_id_array']);
global $wpdb;

$current_user_id = get_current_user_id();

// Create post object
$my_post = array(
    'post_type' => 'order_repair',
    'post_title' => 'LFR' . $repair['order-id-scv'] . '-' . get_post_meta($repair['order-id-original'], 'cart_name', true),
    'post_status' => 'publish',
    'post_author' => $current_user_id,
);

// Insert the post into the database
$post_id = wp_insert_post($my_post);
update_post_meta($post_id, 'order-id-scv', $repair['order-id-scv']);
update_post_meta($post_id, 'order-id-original', $repair['order-id-original']);
update_post_meta($post_id, 'description-damage-error', $repair['description-damage-error']);
update_post_meta($post_id, 'remedial-action-request', $repair['remedial-action-request']);
update_post_meta($post_id, 'warranty', $repair['warranty']);
update_post_meta($post_id, 'items-id', $repair['item-id']);
update_post_meta($post_id, 'attachment_id_array', $repair['myplugin_attachment_id_array']);
update_post_meta($post_id, 'order_status', 'processing');


/***************************************************************/
//   Send mail
/***************************************************************/

global $wpdb;
$repair_id = $post_id;
$order_id = get_post_meta($repair_id, 'order-id-original', true);
$order_id_scv = get_post_meta($repair_id, 'order-id-scv', true);
$description_damage = get_post_meta($repair_id, 'description-damage-error', true);
$remedial_action = get_post_meta($repair_id, 'remedial-action-request', true);
$items_repair_id = get_post_meta($repair_id, 'items-id', true);
$attachment_id = get_post_meta($repair_id, 'attachment_id_array', true);
$warranty = get_post_meta($repair_id, 'warranty', true);
$warranty_find_yes = false;
foreach ($warranty as $id => $value) {
    if ($value == 'Yes') {
        $warranty_find_yes = true;
    }
}

$user_id = $current_user_id;
$post = get_post($order_id);

$order = new WC_Order($post->ID);
$order_data = $order->get_data();

$user_id_customer = $user_id;
//echo 'USER ID '.$user_id;

$i = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$items = $order->get_items();

$nr_code_prod = array();
// echo '<pre>';
// print_r(key($order_data['tax_lines']));
// echo '</pre>';
foreach ($items as $item_id => $item_data) {
    $i++;
    // $product = $item_data->get_product();
    $product_id = $item_data['product_id'];


    $nr_t = get_post_meta($product_id, 'counter_t', true);
    $nr_b = get_post_meta($product_id, 'counter_b', true);
    $nr_c = get_post_meta($product_id, 'counter_c', true);

    if (!empty($nr_code_prod)) {
        if ($nr_code_prod['t'] < $nr_t) {
            $nr_code_prod['t'] = $nr_t;
        }
        if ($nr_code_prod['b'] < $nr_b) {
            $nr_code_prod['b'] = $nr_b;
        }
        if ($nr_code_prod['c'] < $nr_c) {
            $nr_code_prod['c'] = $nr_c;
        }
    } else {
        $nr_code_prod['t'] = $nr_t;
        $nr_code_prod['b'] = $nr_b;
        $nr_code_prod['c'] = $nr_c;
    }
}


$email_body = '<br><p>Dear July,<br>A new repair order has been uploaded to Matrix. Please confirm production and delivery. </p><br></hr>
                        <table style="width:100%; display:table; border: 2px solid #333; border-radius: 5px;"
                               class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Customer:</th>
                                    <th>' . $order_data['billing']['company'] . '</th>
                                    <th>Client details:</th>
                                    <th>' . get_post_meta(get_the_id(), 'cart_name', true) . '</th>
                                    <th>Order date:</th>
                                    <th>' . $order->get_date_created()->format('Y-m-d') . '</th>
                                </tr>
                                <tr>
                                    <th>Repair order no:</th>
                                    <th>LFR' . $order_id_scv . '</th>
                                    <th>Original order no:</th>
                                    <th>LF0' . $order->get_order_number() . '</th>
                                    <th>Repair order date:</th>
                                    <th>' . get_the_date('Y-m-d', $post_id) . '</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                        <table id="example2"
                               style="width:100%; display:table;  border: 2px solid #333; border-radius: 5px;"
                               class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    Item
                                </th>
                                <th>
                                    -
                                </th>
                                <th>
                                    -
                                </th>
                                <th>
                                    -
                                </th>
                                <!--th>
                                    Quantity
                                </th-->
                            </tr>
                            </thead>
                            <tbody>';

$array_att = array();
$array_table = array();
foreach ($items

         as $item_id => $item_data) {

    //$product = $item_data->get_product();
    $_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
    //print_r($item_id);
    $product_id = $item_data['product_id'];

    $prod_qnt = get_post_meta($product_id, 'quantity', true);

    $title = get_the_title($product_id);

    $property_room_other = get_post_meta($product_id, 'property_room_other', true);
    $property_style = get_post_meta($product_id, 'property_style', true);
    $property_frametype = get_post_meta($product_id, 'property_frametype', true);

    $attachment = get_post_meta($product_id, 'attachment', true);
    $array_att[] = $attachment;

    // test rename image

    // end - test rename image

    $property_category = get_post_meta($product_id, 'shutter_category', true);
    $property_material = get_post_meta($product_id, 'property_material', true);
    $property_width = get_post_meta($product_id, 'property_width', true);
    $property_height = get_post_meta($product_id, 'property_height', true);
    $property_midrailheight = get_post_meta($product_id, 'property_midrailheight', true);
    $property_midrailheight2 = get_post_meta($product_id, 'property_midrailheight2', true);
    $property_midraildivider1 = get_post_meta($product_id, 'property_midraildivider1', true);
    $property_midraildivider2 = get_post_meta($product_id, 'property_midraildivider2', true);
    $property_midrailpositioncritical = get_post_meta($product_id, 'property_midrailpositioncritical', true);
    $property_totheight = get_post_meta($product_id, 'property_totheight', true);
    $property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);
    $property_bladesize = get_post_meta($product_id, 'property_bladesize', true);
    $property_fit = get_post_meta($product_id, 'property_fit', true);
    $property_frameleft = get_post_meta($product_id, 'property_frameleft', true);
    $property_frameright = get_post_meta($product_id, 'property_frameright', true);
    $property_frametop = get_post_meta($product_id, 'property_frametop', true);
    $property_framebottom = get_post_meta($product_id, 'property_framebottom', true);
    $property_builtout = get_post_meta($product_id, 'property_builtout', true);
    $property_stile = get_post_meta($product_id, 'property_stile', true);
    $property_hingecolour = get_post_meta($product_id, 'property_hingecolour', true);
    $property_shuttercolour = get_post_meta($product_id, 'property_shuttercolour', true);
    $property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
    $property_controltype = get_post_meta($product_id, 'property_controltype', true);
    $property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
    $property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
    $property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
    $property_t1 = get_post_meta($product_id, 'property_t1', true);
    $property_tposttype = get_post_meta($product_id, 'property_tposttype', true);
    $property_total = get_post_meta($product_id, 'property_total', true);
    $property_depth = get_post_meta($product_id, 'property_depth', true);
    $property_volume = get_post_meta($product_id, 'property_volume', true);
    $property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
    $property_solidpanelheight = get_post_meta($product_id, 'property_solidpanelheight', true);
    $property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
    $property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
    $property_locks = get_post_meta($product_id, 'property_locks', true);
    $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
    $property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);
    $price = get_post_meta($product_id, '_price', true);
    $regular_price = get_post_meta($product_id, '_regular_price', true);
    $sale_price = get_post_meta($product_id, '_sale_price', true);
    $svg_product = get_post_meta($product_id, 'svg_product', true);
    $comments_customer = get_post_meta($product_id, 'comments_customer', true);
    $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
    // echo '<pre>';
    // print_r($term_list->slug);
    // echo '</pre>';

    if ($product_id == 337 || $product_id == 72951) {
        $email_body .= '<tr style="';
        if (isset($description_damage[$item_id]) && trim($description_damage[$item_id]) != '' || isset($remedial_action[$item_id]) && trim($remedial_action[$item_id]) != '') {
            $email_body .= 'background: #cceeff';
        }
        $email_body .= '">
                                    <td>
                                        ' . get_the_title($product_id) . '
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <!--td></td-->
                                    <td></td>
                                </tr>';
    } elseif ($term_list[0]->slug == 'pos') {
        $email_body .= '<tr style="';
        if (isset($description_damage[$item_id]) && trim($description_damage[$item_id]) != '' || isset($remedial_action[$item_id]) && trim($remedial_action[$item_id]) != '') {
            $email_body .= 'background: #cceeff';
        }
        $email_body .= '">
                                    <td>
                                        ' . get_the_title($product_id) . '
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <!--td></td-->
                                    <td>' . $prod_qnt . '</td>
                                </tr>';
    } elseif ($property_category === 'Batten') {
        $batten_qnt = get_post_meta($product_id, 'quantity', true);
        $email_body .= '<tr style="';
        if (isset($description_damage[$item_id]) && trim($description_damage[$item_id]) != '' || isset($remedial_action[$item_id]) && trim($remedial_action[$item_id]) != '') {
            $email_body .= 'background: #cceeff';
        }
        $email_body .= '">
                                    <td>
                                        ' . $property_category . '
                                        <br>
                                        Room:
                                        <strong>
                                            ' . $property_room_other . '</strong>
                                        <br>
                                        Material:
                                        <strong>
                                            ' . $atributes[$property_material] . '</strong>
                                    </td>
                                    <td></td>
                                    <td>
                                        Shutter
                                        Colour:<strong>
                                            ' . $atributes[$property_shuttercolour] . '</strong>
                                        <br>
                                        Notes:<strong>
                                            ' . $comments_customer . '</strong>
                                    </td>
                                    <!--td></td-->
                                    <td>
                                        <strong>
                                            ' . number_format((double)$property_volume, 5) . '
                                            q/m </strong>
                                        <br>
                                        ' . 'Qty:  <strong>' . $batten_qnt . '</strong>
                                        <br>
                                        Width:<strong>
                                            ' . $property_width . '</strong>
                                        <br>
                                        Height:<strong>
                                            ' . $property_height . '</strong>
                                        <br>
                                        Depth
                                        :<strong>
                                            ' . $property_depth . '</strong>
                                        <br>
                                    </td>
                                </tr>';
    } else {
        $email_body .= '<tr style="';
        if (isset($description_damage[$item_id]) && trim($description_damage[$item_id]) != '' || isset($remedial_action[$item_id]) && trim($remedial_action[$item_id]) != '') {
            $email_body .= 'background: #cceeff';
        }
        $email_body .= '">
                                <td>';

        $pieces = explode("-", $title);
        if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {

            $email_body .= '' . $atributes[$property_material] . '';
        } elseif ($property_category === 'Shutter & Blackout Blind') {
            $email_body .= '' . $atributes[$property_material] . ' (' . $property_category . ')';
        } elseif ($property_category === 'Batten') {
            $email_body .= '' . $property_category . '';
        }
        $email_body .= '<br>
                                    Room:
                                    <strong>
                                        ' . $property_room_other . '</strong>
                                    <br>
                                    Installation
                                    style:<strong>
                                        ' . $atributes[$property_style] . '
                                           </strong>
                                    <br>
                                    Shape:';
        if (!empty($attachment)) {
            $email_body .= 'yes';
        } else {
            $email_body .= '<strong>no</strong>';
        }
        $email_body .= '<br>
                                    Midrail
                                    Height:<strong>
                                        ' . $property_midrailheight . '</strong>
                                    <br>';

        if (!empty($property_midrailheight2)) {
            $email_body .= 'Midrail Height 2: <strong>' . $property_midrailheight2 . '</strong><br>';
        }
        if (!empty($property_midraildivider1)) {
            $email_body .= 'Hidden Divider 1: <strong>' . $property_midraildivider1 . '</strong><br>';
        }
        if (!empty($property_midraildivider2)) {
            $email_body .= 'Hidden Divider 2: <strong>' . $property_midraildivider2 . '</strong><br>';
        }
        if (!empty($property_solidpanelheight)) {
            $email_body .= 'Solid Panel Height: <strong>' . $property_solidpanelheight . '</strong><br>';
        }
        $email_body .= '
                                    Position
                                    is
                                    Critical:<strong>';

        if (!empty($atributes[$property_midrailpositioncritical])) {
            $email_body .= '' . $atributes[$property_midrailpositioncritical] . '';
        } else {
            $email_body .= 'No';
        }
        $email_body .= '
                                    </strong>
                                    <br>';
        if (!empty($property_totheight)) {
            $email_body .= 'T-o-T Height: <strong>' . $property_totheight . '</strong><br>';
        }
        if (!empty($property_horizontaltpost)) {
            $email_body .= 'Horizontal T Post: <strong>' . $property_horizontaltpost . '</strong><br>';
        }
        $email_body .= '
                                    Louvre
                                    size:<strong>
                                       ' . $atributes[$property_bladesize] . '</strong>
                                    <br>
                                        Fit: <strong>' . $atributes[$property_fit] . '
                                    </strong>
                                </td>
                                <td>
                                    Frame
                                    type:<strong>
                                        ' . $atributes[$property_frametype] . '</strong>
                                    <br>
                                    Stile
                                    type:<strong>
                                        ' . $atributes[$property_stile] . '</strong>
                                    <br>
                                    Frame
                                    Left:<strong>
                                        ' . $atributes[$property_frameleft] . '</strong>
                                    <br>
                                    Frame
                                    Right:<strong>
                                       ' . $atributes[$property_frameright] . '</strong>
                                    <br>
                                    Frame
                                    Top:<strong>
                                        ' . $atributes[$property_frametop] . '</strong>
                                    <br>
                                    Frame
                                    Bottom:<strong>
                                        ' . $atributes[$property_framebottom] . '</strong>
                                    <br>';
        if (!empty($property_builtout)) {
            $email_body .= 'Buildout: <strong>' . $property_builtout . '</strong> (+10%)';
        }
        $email_body .= '
                                </td>
                                <td>
                                    Hinge
                                    Colour:<strong>
                                        ' . $atributes[$property_hingecolour]
            . '</strong>';
        if ($property_hingecolour == 93) {
            $email_body .= ' (+' . get_post_meta(1, 'Stainless_Steel', true) . '%)';
        }
        $email_body .= '<br>
                                    Shutter
                                    Colour:<strong>
                                        ' . $atributes[$property_shuttercolour] . '</strong>
                                    <br>';

        if (!empty($property_shuttercolour_other)) {
            $email_body .= 'Other Colour: <strong>' . $property_shuttercolour_other . '</strong><br>';
        }
        if ($atributes[$property_blackoutblindcolour]) {
            $email_body .= '
                                            Blackout Blind Colour:
                                            <strong>
                                                ' . $atributes[$property_blackoutblindcolour] . '</strong>
                                            <br>';
        }
        $email_body .= '
                                    Control
                                    Type:<strong>
                                        ' . $atributes[$property_controltype] . '</strong>';
        if ($atributes[$property_controltype] == 'Concealed rod') {
            $email_body .= ' (+10%)';
        }

        $email_body .= '</div>';

        if (!empty($atributes[$property_controlsplitheight])) {
            $email_body .= '<br> Control Split Height:
                    <strong>
                    ' . $atributes[$property_controlsplitheight] . '</strong>';
        }
        $email_body .= '
                <br>
                Layout
                code:<strong
                    style="text-transform:uppercase;">
                    ' . $property_layoutcode . '</strong>
                <br>';
        $tbuilout = 0;
        $cbuilout = 0;
        $bbuilout = 0;
        $unghi = 0;
        foreach ($nr_code_prod as $key => $val) {
            for ($i = 1; $i < $val + 1; $i++) {
                if ($key == 'b') {
                    if (!empty(get_post_meta($product_id, 'property_bp' . $i, true))) {
                        $email_body .= 'BPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_bp' . $i, true) . '/' . get_post_meta($product_id, 'property_ba' . $i, true) . '</strong>';
                        if ((get_post_meta($product_id, 'property_ba' . $i, true) == 90) || (get_post_meta($product_id, 'property_ba' . $i, true) == 135)) {
                            $email_body .= '<br>';
                            if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
                                $email_body .= 'BPosts Buildout: <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>(+';
                                if (!empty(get_user_meta($user_id_customer, 'B_Buildout', true)) || (get_user_meta($user_id_customer, 'B_Buildout', true) > 0)) {
                                    $email_body .= '' . get_user_meta($user_id_customer, 'B_Buildout', true) . '';
                                } else {
                                    $email_body .= '' . get_post_meta(1, 'B_Buildout', true) . '';
                                }
                                $email_body .= '%)<br />';
                            }
                        } else {
                            $unghi++;
                            if ($unghi == 1) {
                                $email_body .= ' (+10%)';
                            }
                            $email_body .= '<br>';
                            if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
                                $email_body .= 'BPosts Buildout: <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>(+';
                                if (!empty(get_user_meta($user_id_customer, 'B_Buildout', true)) || (get_user_meta($user_id_customer, 'B_Buildout', true) > 0)) {
                                    $email_body .= '' . get_user_meta($user_id_customer, 'B_Buildout', true) . '';
                                } else {
                                    $email_body .= '' . get_post_meta(1, 'B_Buildout', true) . '';
                                }
                                $email_body .= '%)<br />';
                            }
                        }
                    }
                }
                if ($key == 'c') {
                    if (!empty(get_post_meta($product_id, 'property_c' . $i, true))) {
                        $email_body .= 'CPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_c' . $i, true) . '</strong><br>';
                    }
                    if (!empty(get_post_meta($product_id, 'property_c_buildout' . $i, true))) {
                        $email_body .= 'CPost Buildout: <strong>' . get_post_meta($product_id, 'property_c_buildout' . $i, true) . '</strong>(+';
                        if (!empty(get_user_meta($user_id_customer, 'C_Buildout', true)) || (get_user_meta($user_id_customer, 'C_Buildout', true) > 0)) {
                            $email_body .= '' . get_user_meta($user_id_customer, 'C_Buildout', true) . '';
                        } else {
                            $email_body .= '' . get_post_meta(1, 'C_Buildout', true) . '';
                        }
                        $email_body .= '%)<br />';
                    }
                    //echo 'c'.$i.': '.get_post_meta( $product_id, 'property_c'.$i, true ).'<br> ';
                } elseif ($key == 't') {
                    if (!empty(get_post_meta($product_id, 'property_t' . $i, true))) {
                        $email_body .= 'TPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_t' . $i, true) . '</strong><br>';
                    }
                    if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i, true))) {
                        $email_body .= 'TPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_t_buildout' . $i, true) . '</strong>(+';
                        if (!empty(get_user_meta($user_id_customer, 'T_Buildout', true)) || (get_user_meta($user_id_customer, 'T_Buildout', true) > 0)) {
                            $email_body .= '' . get_user_meta($user_id_customer, 'T_Buildout', true) . '';
                        } else {
                            $email_body .= '' . get_post_meta(1, 'T_Buildout', true) . '';
                        }
                        $email_body .= '%)<br />';
                    }
                }
            }
        }
        if (!empty(get_post_meta($product_id, 'property_t1', true))) {
            if ($property_tposttype) {
                $email_body .= 'T-Post Type:
                            <strong>
                                ' . $atributes[$property_tposttype] . '</strong>
                            <br>';
            }
        }
        if ($property_sparelouvres == 'Yes') {
            $email_body .= 'Include 2 x Spare Louvre:
                    <strong>
                        ' . $property_sparelouvres . '</strong> (+6$)
                    <br>';
        }
        if ($property_ringpull == 'Yes') {
            $email_body .= 'Ring Pull:
                    <strong>
                        ' . $property_ringpull . '</strong> (+35$)
                    <br>
                    How many rings?:
                    <strong>
                        ' . $property_ringpull_volume . '</strong>
                    <br>';
        }
        if ($property_locks == 'Yes') {
            $email_body .= 'Locks:
                    <strong>
                        ' . $property_locks . '</strong> (+35£)
                    <br>
                    How many locks?:
                    <strong>
                       ' . $property_locks_volume . '</strong>
                    <br>';
        }
        $email_body .= 'Notes:<strong>
                    ' . $comments_customer . '</strong>
                </td>
                <!--td></td-->
                <td>
                    <strong>
                        ' . number_format((double)$property_total, 2) . '
                        sq/m </strong>
                    <br>
                    Qty:  <strong>' . $item_data['quantity'] . '</strong>
                    <br>
                    Width:<strong>
                        ' . $property_width . '</strong>
                    <br>
                    Height:<strong>
                        ' . $property_height . '</strong>
                    <br>
                </td>
                </tr>';
    }
    if (isset($description_damage[$item_id]) && trim($description_damage[$item_id]) != '' || isset($remedial_action[$item_id]) && trim($remedial_action[$item_id]) != '' || isset($attachment_id[$item_id]) && trim($attachment_id[$item_id]) != '') {
        $email_body .= '
                        <tr style="background-color: #cceeff">
                            <td colspan="5">
                                    <div id="<?php echo $item_id; ?>"
                                     class="">

                                    <label>
                                        <strong>
                                            Description
                                            of
                                            error/damage
                                        </strong>
                                    </label>
                                    <p>' . $description_damage[$item_id] . '</p>

                                    <label>
                                        <strong>
                                            Remedial
                                            action
                                            requested
                                        </strong>
                                    </label>
                                    <p>' . $remedial_action[$item_id] . '</p>

                                    <label>
                                         <strong>
                                             Covered by Warranty: <strong>' . $warranty[$item_id] . '</strong>
                                         </strong>
                                     </label>

                                    <br>
                                    <label>
                                        <strong>
                                            Images
                                            with
                                            damage
                                        </strong>
                                    </label>
                                    <div id="shape-upload-container">';
        if ($attachment_id[$item_id]) {
            foreach ($attachment_id[$item_id] as $img_url) {
                $email_body .= '
                                                    <div class="col-lg-4 col-md-6" style="max-width: 50%;float: left;">
                                                        <img src="' . $img_url . '"
                                                             alt=""
                                                             style="max-height: 200px; width: auto;">
                                                        <p>
                                                            <a href="' . $img_url . '">IMG LINK</a>
                                                        </p>
                                                    </div>
                                                    <span style="clear: both;"></span>
                                                    ';
            }
        }
        $email_body .= '
                                    </div>
                                </div>
                            </td>
                        </tr>';
    }
}
$email_body .= '
                </tbody>
                </table></hr><br> ';


//    $to = 'tudor@lifetimeshutters.com';
if ($warranty_find_yes === false) {
    $multiple_recipients = array(
        'tudor@lifetimeshutters.com', 'mike@lifetimeshutters.com', 'service@lifetimeshutters.co.uk'
    );
} else {
    $multiple_recipients = array(
        'tudor@lifetimeshutters.com', 'mike@lifetimeshutters.com'
//, 'service@lifetimeshutters.co.uk', 'chris@lifetimeshutters.co.uk'
    );
}
if ($warranty_find_yes === false) {
    $subject_mail_begin = 'Not Under Warranty - ';
} else {
    $subject_mail_begin = '';
}
//$multiple_recipients = 'marian93nes@gmail.com, tudor@fiqs.ro';
$subject = $subject_mail_begin . 'Repair Order LFR' . $repair['order-id-scv'] . ' for Original Order LF0' . $repair['order-id-scv'] . '';
$body = $email_body;
$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Service LifetimeShutters <service@lifetimeshutters.co.uk>');

wp_mail($multiple_recipients, $subject, $body, $headers);



//    MAIL FOR CHINA

//    $to_china = 'july@anyhooshutter.com';
//    $multiple_recipients_china = array(
//        'tudor@fiqs.ro', 'mike@lifetimeshutters.com'
//, 'july@anyhooshutter.com'
//    );
//    $subject_china = 'Repair Order LFR' . $repair['order-id-scv'] . ' for Original Order LF' . $repair['order-id-scv'] . '';
//    $body_china = 'Dear July, a new repair order has been uploaded to Matrix. Please confirm production and delivery';
//    $headers_china = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <tudor@lifetimeshutters.com>');

//    wp_mail($multiple_recipients_china, $subject_china, $body_china, $headers_china);
