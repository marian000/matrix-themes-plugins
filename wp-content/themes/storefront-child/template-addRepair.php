<?php
/**
 * Template Name: Add Repairs Template
 *
 */

get_header();

global $wpdb;
$repair_id = $_GET['order_number'];
?>
    <div id="primary"
         class="content-area">
        <main id="main"
              class="site-main"
              role="main">

            <!-- Multi Cart Template -->
            <?php if (is_active_sidebar('multicart_widgett')) : ?>
                <div id="bmc-woocom-multisession-2"
                     class="widget bmc-woocom-multisession-widget">
                </div>
                <!-- #primary-sidebar -->
            <?php endif; ?>

            <?php
            if (is_user_logged_in()) {
                $user_id = get_current_user_id();
                ?>
                <h2>Order Repairs - LFR<?php echo $repair_id; ?></h2>
                <?php
            }
            ?>

            <div>

                <?php
                $order_id = wc_sequential_order_numbers()->find_order_by_order_number($_GET['order_number']);
                $post = get_post($order_id);

                $order = new WC_Order($post->ID);
                $order_data = $order->get_data();

                $user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
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

                    $nr_g = get_post_meta($product_id, 'counter_g', true);
                    $nr_t = get_post_meta($product_id, 'counter_t', true);
                    $nr_b = get_post_meta($product_id, 'counter_b', true);
                    $nr_c = get_post_meta($product_id, 'counter_c', true);

                    if (!empty($nr_code_prod)) {
                        if ($nr_code_prod['g'] < $nr_t) {
                            $nr_code_prod['g'] = $nr_t;
                        }
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

                ?>


                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12"
                         id="items-info">
                        <form class="repair-form">
                            <table id="example2"
                                   style="width:100%; display:table;"
                                   class="table table-striped"
                                   count-items="<?php echo $i; ?>"
                            >
                                <thead>
                                <tr>
                                    <!-- <th>item</th> -->

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
                                    <th>
                                        Quantity
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $array_att = array();
                                $array_table = array();
                                foreach ($items

                                as $item_id => $item_data) {
                                $j++;
                                //$product = $item_data->get_product();
                                $_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
                                //print_r($item_id);
                                $product_id = $item_data['product_id'];

                                $prod_qnt = get_post_meta($product_id, 'quantity', true);

                                $title = get_the_title($product_id);

                                $property_room_other = get_post_meta($product_id, 'property_room_other', true);
                                $property_style = get_post_meta($product_id, 'property_style', true);
                                $property_frametype = get_post_meta($product_id, 'property_frametype', true);

                                echo $attachment = get_post_meta($product_id, 'attachment', true);
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

                                if ($product_id == 337 || $product_id == 72951){ ?>
                                    <tr>
                                        <td>
                                            <?php echo get_the_title($product_id); ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                } elseif ($term_list[0]->slug == 'pos') {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo get_the_title($product_id); ?>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><?php echo $prod_qnt; ?></td>
                                    </tr>
                                    <?php
                                } elseif ($property_category === 'Batten') {
                                    $batten_qnt = get_post_meta($product_id, 'quantity', true);
                                    ?>
                                    <tr>
                                        <td>
                                            <a class="nameshutter"
                                               href="/product5-edit/?id=<?php echo $product_id * 1498765 * 33; ?>&cust_id=<?php echo $user_id_customer; ?>&item_id=<?php echo $item_id; ?>"><?php echo $property_category; ?></a><?php
                                            ?>
                                            <br>
                                            Room:
                                            <strong>
                                                <?php echo $property_room_other; ?></strong>
                                            <br>
                                            Material:
                                            <strong>
                                                <?php echo $atributes[$property_material]; ?></strong>

                                        </td>
                                        <td></td>
                                        <td>
                                            Shutter
                                            Colour:<strong>
                                                <?php echo $atributes[$property_shuttercolour]; ?></strong>
                                            <br>
                                            Notes:<strong>
                                                <?php echo $comments_customer; ?></strong>
                                        </td>
                                        <td></td>
                                        <td>
                                            <strong>
                                                <?php echo number_format((double)$property_volume, 5); ?>
                                                q/m </strong>
                                            <br>
                                            <?php echo 'Qty:  <strong>' . $batten_qnt; ?></strong>
                                            <br>
                                            Width:<strong>
                                                <?php echo $property_width; ?></strong>
                                            <br>
                                            Height:<strong>
                                                <?php echo $property_height; ?></strong>
                                            <br>
                                            Depth
                                            :<strong>
                                                <?php echo $property_depth; ?></strong>
                                            <br>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                else{
                                ?>
                                <tr>
                                    <!-- <td><?php // echo $j;
                                    ?></td> -->
                                    <td>
                                        <?php
                                        $pieces = explode("-", $title);
                                        $property_category;
                                        if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
                                            ?>
                                            <a
                                            class="nameshutter"
                                            href="/product-admin/?id=<?php echo $product_id * 1498765 * 33; ?>&cust_id=<?php echo $user_id_customer; ?>&item_id=<?php echo $item_id; ?>"><?php echo $atributes[$property_material]; ?></a><?php
                                        } elseif ($property_category === 'Shutter & Blackout Blind') {
                                            ?>
                                            <a
                                            class="nameshutter"
                                            href="/product3-admin/?id=<?php echo $product_id * 1498765 * 33; ?>&cust_id=<?php echo $user_id_customer; ?>&item_id=<?php echo $item_id; ?>"><?php echo $atributes[$property_material] . ' (' . $property_category . ')'; ?></a><?php
                                        } elseif ($property_category === 'Batten') {
                                            ?>
                                            <a
                                            class="nameshutter"
                                            href="/product5-admin/?id=<?php echo $product_id * 1498765 * 33; ?>&cust_id=<?php echo $user_id_customer; ?>&item_id=<?php echo $item_id; ?>"><?php echo $property_category; ?></a><?php
                                        } ?>
                                        <br>
                                        Room:
                                        <strong>
                                            <?php echo $property_room_other; ?></strong>
                                        <br>
                                        Installation
                                        style:<strong>
                                            <?php echo $atributes[$property_style];
                                            if ($property_style == 34) {
                                                echo ' (+£125)';
                                            }
                                            if ($property_style == 33) {
                                                echo ' (+%' . get_post_meta(1, 'Shaped', true) . ')';
                                            }
                                            ?></strong>
                                        <br>
                                        Shape:
                                        <?php
                                        if (!empty($attachment)) {
                                            echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
                                        } else {
                                            echo '<strong>no</strong>';
                                        }
                                        ?>
                                        <br>
                                        Midrail
                                        Height:<strong>
                                            <?php echo $property_midrailheight; ?></strong>
                                        <br>

                                        <?php
                                        if (!empty($property_midrailheight2)) {
                                            echo 'Midrail Height 2: <strong>' . $property_midrailheight2 . '</strong><br>';
                                        }
                                        ?>
                                        <?php
                                        if (!empty($property_midraildivider1)) {
                                            echo 'Hidden Divider 1: <strong>' . $property_midraildivider1 . '</strong><br>';
                                        }
                                        ?>
                                        <?php
                                        if (!empty($property_midraildivider2)) {
                                            echo 'Hidden Divider 2: <strong>' . $property_midraildivider2 . '</strong><br>';
                                        }
                                        if (!empty($property_solidpanelheight)) {
                                            echo 'Solid Panel Height: <strong>' . $property_solidpanelheight . '</strong><br>';
                                        }
                                        ?>
                                        Position
                                        is
                                        Critical:<strong>
                                            <?php

                                            if (!empty($atributes[$property_midrailpositioncritical])) {
                                                echo $atributes[$property_midrailpositioncritical];
                                            } else {
                                                echo 'No';
                                            }

                                            ?>
                                        </strong>
                                        <br>
                                        <?php
                                        if (!empty($property_totheight)) {
                                            echo 'T-o-T Height: <strong>' . $property_totheight . '</strong><br>';
                                        }
                                        ?>
                                        <?php
                                        if (!empty($property_horizontaltpost)) {
                                            echo 'Horizontal T Post: <strong>' . $property_horizontaltpost . '</strong><br>';
                                        }
                                        ?>
                                        Louvre
                                        size:<strong>
                                            <?php echo $atributes[$property_bladesize]; ?></strong>
                                        <br>
                                        <?php
                                        echo 'Fit: <strong>' . $atributes[$property_fit];
                                        ?></strong>
                                    </td>
                                    <td>
                                        Frame
                                        type:<strong>
                                            <?php echo $atributes[$property_frametype]; ?></strong>
                                        <br>
                                        Stile
                                        type:<strong>
                                            <?php echo $atributes[$property_stile]; ?></strong>
                                        <br>
                                        Frame
                                        Left:<strong>
                                            <?php echo $atributes[$property_frameleft]; ?></strong>
                                        <br>
                                        Frame
                                        Right:<strong>
                                            <?php echo $atributes[$property_frameright]; ?></strong>
                                        <br>
                                        Frame
                                        Top:<strong>
                                            <?php echo $atributes[$property_frametop]; ?></strong>
                                        <br>
                                        Frame
                                        Bottom:<strong>
                                            <?php echo $atributes[$property_framebottom]; ?></strong>
                                        <br>
                                        <?php
                                        if (!empty($property_builtout)) {
                                            echo 'Buildout: <strong>' . $property_builtout . '</strong> (+10%)';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        Hinge
                                        Colour:<strong>
                                            <?php
                                            echo $atributes[$property_hingecolour];
                                            ?></strong>
                                        <?php if ($property_hingecolour == 93) {
                                            echo ' (+' . get_post_meta(1, 'Stainless_Steel', true) . '%)';
                                        } ?>
                                        <br>
                                        Shutter
                                        Colour:<strong>
                                            <?php echo $atributes[$property_shuttercolour]; ?></strong>
                                        <br>
                                        <?php
                                        if (!empty($property_shuttercolour_other)) {
                                            echo 'Other Colour: <strong>' . $property_shuttercolour_other . '</strong><br>';
                                        }


                                        if ($atributes[$property_blackoutblindcolour]) {
                                            ?>
                                            Blackout Blind Colour:
                                            <strong>
                                                <?php echo $atributes[$property_blackoutblindcolour]; ?></strong>
                                            <br>
                                            <?php
                                        }
                                        ?>

                                        Control
                                        Type:<strong>
                                            <?php echo $atributes[$property_controltype]; ?></strong> <?php
                                        if ($atributes[$property_controltype] == 'Concealed rod') {
                                            echo ' (+10%)';
                                        }
                                        ?>
                    </div>

                    <?php if (!empty($atributes[$property_controlsplitheight])) { ?>
                        <br> Control Split Height:
                        <strong>
                        <?php echo $atributes[$property_controlsplitheight]; ?></strong><?php } ?>
                    <br>
                    Layout
                    code:<strong
                            style="text-transform:uppercase;">
                        <?php echo $property_layoutcode; ?></strong>
                    <br>
                    <?php
                    $tbuilout = 0;
                    $cbuilout = 0;
                    $bbuilout = 0;
                    $unghi = 0;
                    foreach ($nr_code_prod as $key => $val) {
                        for ($i = 1; $i < $val + 1; $i++) {
                            if ($key == 'b') {
                                if (!empty(get_post_meta($product_id, 'property_bp' . $i, true))) {
                                    echo 'BPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_bp' . $i, true) . '/' . get_post_meta($product_id, 'property_ba' . $i, true) . '</strong>';
                                    if ((get_post_meta($product_id, 'property_ba' . $i, true) == 90) || (get_post_meta($product_id, 'property_ba' . $i, true) == 135)) {
                                        echo '<br>';
                                        if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
                                            echo 'BPosts Buildout: <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>(+';
                                            if (!empty(get_user_meta($user_id_customer, 'B_Buildout', true)) || (get_user_meta($user_id_customer, 'B_Buildout', true) > 0)) {
                                                echo get_user_meta($user_id_customer, 'B_Buildout', true);
                                            } else {
                                                echo get_post_meta(1, 'B_Buildout', true);
                                            }
                                            echo '%)<br />';
                                        }
                                    } else {
                                        $unghi++;
                                        if ($unghi == 1) {
                                            echo ' (+10%)';
                                        }
                                        echo '<br>';
                                        if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
                                            echo 'BPosts Buildout: <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>(+';
                                            if (!empty(get_user_meta($user_id_customer, 'B_Buildout', true)) || (get_user_meta($user_id_customer, 'B_Buildout', true) > 0)) {
                                                echo get_user_meta($user_id_customer, 'B_Buildout', true);
                                            } else {
                                                echo get_post_meta(1, 'B_Buildout', true);
                                            }
                                            echo '%)<br />';
                                        }
                                    }

//                                      echo '<br>';
                                }
                            }
                            if ($key == 'c') {
                                if (!empty(get_post_meta($product_id, 'property_c' . $i, true))) {
                                    echo 'CPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_c' . $i, true) . '</strong><br>';
                                }
                                if (!empty(get_post_meta($product_id, 'property_c_buildout' . $i, true))) {
                                    echo 'CPost Buildout: <strong>' . get_post_meta($product_id, 'property_c_buildout' . $i, true) . '</strong>(+';
                                    if (!empty(get_user_meta($user_id_customer, 'C_Buildout', true)) || (get_user_meta($user_id_customer, 'C_Buildout', true) > 0)) {
                                        echo get_user_meta($user_id_customer, 'C_Buildout', true);
                                    } else {
                                        echo get_post_meta(1, 'C_Buildout', true);
                                    }
                                    echo '%)<br />';
                                }
                                //echo 'c'.$i.': '.get_post_meta( $product_id, 'property_c'.$i, true ).'<br> ';
                            } elseif ($key == 't') {
                                if (!empty(get_post_meta($product_id, 'property_t' . $i, true))) {
                                    echo 'TPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_t' . $i, true) . '</strong><br>';
                                }
                                if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i, true))) {
                                    echo 'TPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_t_buildout' . $i, true) . '</strong>(+';
                                    if (!empty(get_user_meta($user_id_customer, 'T_Buildout', true)) || (get_user_meta($user_id_customer, 'T_Buildout', true) > 0)) {
                                        echo get_user_meta($user_id_customer, 'T_Buildout', true);
                                    } else {
                                        echo get_post_meta(1, 'T_Buildout', true);
                                    }
                                    echo '%)<br />';
                                }

                                //echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
                            } elseif ($key == 'g') {
                                if (!empty(get_post_meta($product_id, 'property_g' . $i, true))) {
                                    echo 'GPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_g' . $i, true) . '</strong><br>';
                                }
                                if (!empty(get_post_meta($product_id, 'property_g_buildout' . $i, true))) {
                                    echo 'GPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_g_buildout' . $i, true) . '</strong>(+';
                                    if (!empty(get_user_meta($user_id_customer, 'G_Buildout', true)) || (get_user_meta($user_id_customer, 'G_Buildout', true) > 0)) {
                                        echo get_user_meta($user_id_customer, 'G_Buildout', true);
                                    } else {
                                        echo get_post_meta(1, 'G_Buildout', true);
                                    }
                                    echo '%)<br />';
                                }

                                //echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
                            }
                        }
                    } ?>
                    <?php
                    if (!empty(get_post_meta($product_id, 'property_t1', true))) {
                        if ($property_tposttype) { ?> T-Post Type:
                            <strong>
                                <?php echo $atributes[$property_tposttype]; ?></strong>
                            <br>
                        <?php }
                    } ?>
                    <?php if ($property_sparelouvres == 'Yes') { ?> Include 2 x Spare Louvre:
                        <strong>
                            <?php echo $property_sparelouvres; ?></strong> (+6£)
                        <br>
                    <?php } ?>
                    <?php if ($property_ringpull == 'Yes') { ?> Ring Pull:
                        <strong>
                            <?php echo $property_ringpull; ?></strong> (+35£)
                        <br>
                        How many rings?:
                        <strong>
                            <?php echo $property_ringpull_volume; ?></strong>
                        <br>
                    <?php } ?>
                    <?php if ($property_locks == 'Yes') { ?> Locks:
                        <strong>
                            <?php echo $property_locks; ?></strong> (+35£)
                        <br>
                        How many locks?:
                        <strong>
                            <?php echo $property_locks_volume; ?></strong>
                        <br>
                    <?php } ?>
                    Notes:<strong>
                        <?php echo $comments_customer; ?></strong>
                    </td>
                    <td></td>
                    <td>
                        <strong>
                            <?php echo number_format((double)$property_total, 2); ?>
                            sq/m </strong>
                        <br>
                        <?php echo 'Qty:  <strong>' . $prod_qnt . '</strong>'; ?>
                        <br>
                        Width:<strong>
                            <?php echo $property_width; ?></strong>
                        <br>
                        Height:<strong>
                            <?php echo $property_height; ?></strong>
                        <br>
                    </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="5">
                            <button type="button"
                                    class="btn btn-info"
                                    data-toggle="collapse"
                                    data-target="#<?php echo $item_id; ?>">Open Repair Form
                            </button>
                            <div id="<?php echo $item_id; ?>"
                                 class="collapse">

                                <label for="txtarea1">
                                    Description
                                    of
                                    error/damage
                                </label>
                                <textarea
                                        name="description-damage-error[<?php echo $item_id; ?>]"
                                        id="txtarea1"
                                        cols="30"
                                        rows="5"></textarea>
                                <label for="txtarea2">
                                    Remedial
                                    action
                                    requested
                                </label>
                                <textarea
                                        name="remedial-action-request[<?php echo $item_id; ?>]"
                                        id="txtarea2"
                                        cols="30"
                                        rows="5"> </textarea>
                                <label for="Warranty">
                                    Covered by Warranty
                                </label>
                                <label class="checkbox-inline">
                                    <input name="warranty[<?php echo $item_id; ?>]" type="radio" value="Yes"> Yes
                                </label>
                                <label class="checkbox-inline">
                                    <input name="warranty[<?php echo $item_id; ?>]" type="radio" value="No"> No
                                </label>
                                <!--                                <select name="warranty[-->
                                <?php //echo $item_id; ?><!--]"-->
                                <!--                                        id="Warranty">-->
                                <!--                                    <option value="No">No</option>-->
                                <!--                                    <option value="Yes">Yes</option>-->
                                <!--                                </select>-->
                                <br>
                                <label>
                                    Images
                                    with
                                    damage
                                </label>
                                <div id="shape-upload-container">
                                    <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                    <input id="frontend-button-<?php echo $item_id; ?>"
                                           id-item="<?php echo $item_id; ?>"
                                           type="button"
                                           value="Select Images to Upload"
                                           class="button upload-images"
                                           style="position: relative; z-index: 1;">

                                    <input type="hidden"
                                           name="item-id[]"
                                           value="<?php echo $item_id; ?>">
                                    <div id="myplugin-placeholder-<?php echo $item_id; ?>"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>

                    </tbody>
                    </table>
                    <input type="hidden"
                           name="order-id-scv"
                           value="<?php echo $repair_id; ?>">
                    <input type="hidden"
                           name="order-id-original"
                           value="<?php echo $order_id; ?>">
                    <button type="submit"
                            class="btn btn-primary">Send Repair Info
                    </button>
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="show-info"></div>

        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->

    <script>
        jQuery(document).ready(function () {
            jQuery(".repair-form").on("submit", function (event) {
                event.preventDefault();
                var repair_data = jQuery(this).serialize();
                var repair_data_array = jQuery(this).serializeArray();
                var items_count = jQuery('table#example2').attr('count-items');

                // after
                var warranty_count = 0;
                var description_damage_error = 0;
                var myplugin_attachment_id_array = 0;
                var warranty_count_yes = 0;
                var warranty_count_no = 0;
                repair_data_array.forEach(function (key, index) {
                    console.log(repair_data_array);
                    var name_inputs = repair_data_array[index].name;
                    var value_inputs = repair_data_array[index].value;

                    if (name_inputs.indexOf('warranty') !== -1) {
                        warranty_count++;
                        if (value_inputs === 'Yes') {
                            warranty_count_yes++
                        }
                        if (value_inputs === 'No') {
                            warranty_count_no++
                        }
                    }
                    if (name_inputs.indexOf('myplugin_attachment_id_array') !== -1) {
                        myplugin_attachment_id_array++
                    }
                    console.log(repair_data_array[index]);
                    if (name_inputs.indexOf('description-damage-error') !== -1) {
                        if (value_inputs.length > 1) {
                            console.log('desc have value');
                            description_damage_error++;
                        }
                    }
                });
                // check if found warranty
                if (warranty_count > 0 && description_damage_error > 0 && warranty_count === description_damage_error) {
                    if (warranty_count_yes <= myplugin_attachment_id_array) {
                        console.log('warranty_count_yes ' + warranty_count_yes);
                        console.log('myplugin_attachment_id_array ' + myplugin_attachment_id_array);
                        if (warranty_count >= warranty_count_yes) {
                            console.log('found warranty ' + warranty_count);

                            jQuery('.btn.btn-primary[type="submit"]').hide();

                            jQuery.ajax({
                                method: "POST",
                                url: "/wp-content/themes/storefront-child/ajax/repair-ajax.php",
                                data: {item_repair: repair_data}
                            })
                                .done(function (msg) {
                                    // jQuery('.show-info').html(msg);

                                    setTimeout(function () {
                                        //window.location.reload();
                                        window.location.replace("/order-repairs/");
                                    }, 500);
                                });
                        } else if (warranty_count === warranty_count_no) {
                            console.log('found warranty ' + warranty_count);

                            jQuery('.btn.btn-primary[type="submit"]').hide();

                            jQuery.ajax({
                                method: "POST",
                                url: "/wp-content/themes/storefront-child/ajax/repair-ajax.php",
                                data: {item_repair: repair_data}
                            })
                                .done(function (msg) {
                                    // jQuery('.show-info').html(msg);

                                    setTimeout(function () {
                                        //window.location.reload();
                                        window.location.replace("/order-repairs/");
                                    }, 500);
                                });
                        }
                    } else {
                        console.log('no iamge to yes warranty ' + myplugin_attachment_id_array);
                        // alert('Please select Images for items with Yes warranty!');
                        swal({
                            title: "Please provide images for items covered by warranty!",
                            text: "",
                            icon: "warning",
                            button: "OK",
                        });
                    }
                } else {
                    console.log('no warranty match ' + warranty_count);
                    // alert('Please select "Covered by Warranty" option on each item!');
                    swal({
                        title: "Please provide images for items covered by warranty!",
                        text: "",
                        icon: "warning",
                        button: "OK",
                    });
                }


            });
        });
    </script>

<?php
get_footer();
