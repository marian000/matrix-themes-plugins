<?php
$csv_matrix_orders = array();

$framesType = array(323 => 46, 321 => 51, 330 => 72, 318 => 60, 322 => 87, 319 => 100, 331 => 51, 320 => 46, 325 => 46, 326 => 46, 327 => 46, 328 => 46, 324 => 46, 329 => 46, 307 => 64, 310 => 64, 313 => 77, 333 => 60, 306 => 51, 309 => 64, 312 => 77, 305 => 46, 308 => 64, 311 => 77, 332 => 60, 142 => 46, 314 => 46, 315 => 46, 316 => 46, 317 => 46, 300 => 50, 302 => 50, 301 => 50);

$email_body = '
<table id="example" style="width:100%; display:table;" class="table table-striped">
        <thead>
            <tr>
                <th>Order number</th>
                <th>NO</th>
                <th>material</th>
                <th>width</th>
                <th>height</th>
                <th>depth</th>
                <th>qty</th>
                <th>unit</th>
                <th>sqm</th>
                <th>unit price</th>
                <th>amount</th>
                <th>csv amount</th>
                <th>diff csv</th>
                <th>Frame</th>
                <th>total</th>
                <th>csv total</th>
                <th>diff csv total</th>
            </tr>
        </thead>
        <tbody>';
$current_container_id = get_the_id();
$container_orders = get_post_meta(get_the_id(), 'container_orders', true);
$csv_orders_array = get_post_meta(get_the_id(), 'csv_orders_invoice_array', true);

arsort($container_orders);
$payment_order = 0;
$payment_order_csv = 0;
if ($container_orders) {
  //print_r($container_orders);
  $lifetime_orders = array('total_dolar' => 0, 'csv_total_dolar' => 0);
  $customers_orders = array('total_dolar' => 0, 'csv_total_dolar' => 0);
  foreach ($container_orders as $order) {
    $container_order = $order;
    $company = get_post_meta($order, '_billing_company', true);

    if (get_post_type($order) == 'order_repair') {
      $order_original = get_post_meta($order, 'order-id-original', true);
      $order = wc_get_order($order_original);
      $order_data = $order->get_data();

      if (!empty($csv_orders_array) && !array_key_exists($order, $csv_orders_array)) {
        $csv_matrix_orders[$order] = '0';
      }
    } else {
      $order = wc_get_order($order);
      if ($order) {
        $order_data = $order->get_data();

        if (!empty($csv_orders_array) && !array_key_exists($order, $csv_orders_array)) {
          $csv_matrix_orders[$order] = '0';
        }
      }
    }

    // print_r($index_items_orders);

    // $order = wc_get_order($order);
    // $order_data = $order->get_data();
    $full_payment = 0;

    //$order = new WC_Order( $order_id );
    if ($order) {
      $items = $order->get_items();
      $order_data = $order->get_data();
      // echo '<pre>';
      // print_r($order_data);
      // echo '</pre>';
      $i = 0;
      $atributes = get_post_meta(1, 'attributes_array', true);

      $user_id = $order->get_customer_id();

      $nr_code_prod = array();
      foreach ($order->get_items() as $prod => $item_data) {
        $i++;
        $product = $item_data->get_product();
        $product_id = $product->id;

        $nr_g = get_post_meta($product_id, 'counter_g', true);
        $nr_t = get_post_meta($product_id, 'counter_t', true);
        $nr_b = get_post_meta($product_id, 'counter_b', true);
        $nr_c = get_post_meta($product_id, 'counter_c', true);

        if (!empty($nr_code_prod)) {
          if ($nr_code_prod['g'] < $nr_g) {
            $nr_code_prod['g'] = $nr_g;
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
          $nr_code_prod['g'] = $nr_g;
          $nr_code_prod['t'] = $nr_t;
          $nr_code_prod['b'] = $nr_b;
          $nr_code_prod['c'] = $nr_c;
        }
      }

      $array_att = array();
      $a = 0;
      $k = 0;
      $b = 0;
      $total_order = 0;
      $size = count($order->get_items());
      $item_index = 0;
      $index_items_orders = array();
      // get index of items from csv to show
      foreach ($csv_orders_array[$order->get_id()] as $csv_index => $csv_item) {
        $index_items_orders[] = $csv_index;
      }

      foreach ($order->get_items() as $prod => $item_data) {
        $i++;
        $item_index++;

        $product = $item_data->get_product();
        $product_id = $product->id;
        // Get an instance of corresponding the WC_Product object
        $product_name = $product->get_name(); // Get the product name

        $item_quantity = $item_data->get_quantity(); // Get the item quantity

        $item_total = $item_data->get_total(); // Get the item line total
        $property_category = get_post_meta($product_id, 'shutter_category', true);

        if (in_array($item_index, $index_items_orders) || $property_category === 'Batten') {

          $property_room_other = get_post_meta($product_id, 'property_room_other', true);
          $property_style = get_post_meta($product_id, 'property_style', true);
          $property_frametype = get_post_meta($product_id, 'property_frametype', true);
          $property_tposttype = get_post_meta($product_id, 'property_tposttype', true);

          $attachment = get_post_meta($product_id, 'attachment', true);
          $array_att[] = $attachment;

          $property_material = get_post_meta($product_id, 'property_material', true);
          $property_width = get_post_meta($product_id, 'property_width', true);
          $property_height = get_post_meta($product_id, 'property_height', true);
          $property_depth = get_post_meta($product_id, 'property_depth', true);
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
          $property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
          $property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
          $property_controltype = get_post_meta($product_id, 'property_controltype', true);
          $property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
          $property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
          $property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
          $property_t1 = get_post_meta($product_id, 'property_t1', true);
          $property_total = get_post_meta($product_id, 'property_total', true);

          $property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
          $property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
          $property_locks = get_post_meta($product_id, 'property_locks', true);
          $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
          $property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);

          $property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
          $sections_price = get_post_meta($product_id, 'sections_price', true);

          $price = get_post_meta($product_id, '_price', true);
          $regular_price = get_post_meta($product_id, '_regular_price', true);
          $sale_price = get_post_meta($product_id, '_sale_price', true);

          $price_earth = get_post_meta(1, 'Earth', true);
          $price_green = get_post_meta(1, 'Green', true);
          $price_biowood = get_post_meta(1, 'Biowood', true);
          $price_supreme = get_post_meta(1, 'Supreme', true);
          $dolar_price = get_post_meta($product_id, 'dolar_price', true);

          $materials = array('Earth' => $price_earth, 'Green' => $price_green, 'Biowood' => $price_biowood, 'Supreme' => $price_supreme);

          $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
          // echo '<pre>';
          // print_r($term_list->slug);
          // echo '</pre>';
          if ($product_id == 337 || $product_id == 72951) {
            $product = wc_get_product($product_id);
            $dolar_price = 100;
          }
          // elseif( $term_list[0]->slug == 'pos' ) {
          // }
          elseif ($property_category === 'Batten') {
            $b++;
            $a++;

            if (get_post_type($container_order) == 'order_repair') {
              $dolar_price = 0;

              $total_order = $total_order + 0;
              $order_sum = $total_order;

              $total_order_csv = 0;
              foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                $total_order_csv = 0 + $total_order_csv;
              }
            } else {
              if (empty($dolar_price)) {
                $battenCustomDolar = get_post_meta(1, 'BattenCustom-dolar', true);
                $dolar_price = number_format($property_total, 2, ',', '') * $battenCustomDolar;
              }

              $total_order = number_format($total_order, 2, ',', '') + number_format($dolar_price, 2, ',', '') * $item_data['quantity'];
              $order_sum = $total_order;

              $total_order_csv = 0;
              foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                $total_order_csv = number_format($csv_item, 2, ',', '') + number_format($total_order_csv, 2, ',', '');
              }
            }

            $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');

            if ($diff_sum < 0) {
              $diff_class_sum = 'negative';
            } elseif ($diff_sum > 0) {
              $diff_class_sum = 'positive';
            }
            if ($a != $size) {
              $diff_class_sum = 'neutral';
            }

            $diff_amount = (floatval($dolar_price) * $item_quantity) - floatval($csv_orders_array[$order->get_id()]['batten-' . $b]);

//                echo $diff_amount. ' = ' . floatval($dolar_price) * $item_quantity . ' - ' . floatval($csv_orders_array[$order->get_id()]['batten-' . $b]) . '<br>';
            $diff_class = '';
            if ($diff_amount < 0) {
              $diff_class = 'negative';
            } elseif ($diff_amount > 0) {
              $diff_class = 'positive';
            } else {
              $diff_class = 'neutral';
            }
// if($a == $size ){  $order_sum = $total_order; }

            $email_body .= '<tr id="' . $container_order . '-1">';
            if (get_post_type($container_order) == 'order_repair') {
              $email_body .= '<td>LFR' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
            } else {
              $email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
            }
            //$email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>;
            $email_body .= '<td>' . $a . '</td>
                            <td>';
            if ($term_list[0]->slug == 'pos') {
              $email_body .= '' . get_the_title($product_id) . '';
            } else {
              $email_body .= '' . $atributes[$property_material] . '';
            }
            $email_body .= '</td>
                            <td>' . $property_width . '</td>
                            <td>' . $property_height . '</td>
                            <td>' . $property_depth . '</td>
                            <td>' . $item_quantity . '</td>
                            <td>m2</td>
                            <td>' . round($property_total, 3) * $item_quantity . '</td>
                            <td>$' . get_post_meta(1, $atributes[$property_material] . '-dolar', true) . '</td>';

            if (get_post_type($container_order) == 'order_repair') {
              $email_body .= '<td>$ 0</td>
                            <td class="csv-amount">$ 0</td>
                            <td class="csv-diff ' . $diff_class . '">$ 0</td>
                            <td>';
            } else {
              $email_body .= '<td>$' . round($dolar_price, 2) * $item_quantity . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()]['batten-' . $b] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
            }

//                foreach ($frames as $frame) {
//                    $email_body .= '' . $frame . ' - ' . get_post_meta(1, $frame . '-dolar', true) . '<br>';
//                }
            $email_body .= '</td>
                            <td>';
            if ($a == $size) {
              $email_body .= '$ ' . $order_sum . '';
            }
            $email_body .= '</td>
                            <td class="csv-total">';
            if ($a == $size) {
              $email_body .= '$ ' . number_format($total_order_csv, 2, ',', '') . '';
            }
            $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
            if ($a == $size) {
              $email_body .= '$ ' . number_format($diff_sum, 2, ',', '') . '';
            }
            $email_body .= '</td>
                        </tr>';
          } else {

            if (!empty($property_nr_sections)) {
              $a++;
              $indiv_amount_csv = 0;

              if (get_post_type($container_order) == 'order_repair') {
                if (empty($dolar_price)) {
                  $dolar_price = $sum;
                }
                $dolar_price = 0;

                $total_order = $total_order + 0;
                $order_sum = number_format($total_order, 2, ',', '');

                $total_order_csv = 0;
                foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                  $total_order_csv = 0 + $total_order_csv;
                }

                global $wpdb;
                $key = 'order-id-original';
                $value = $order->get_id();
                $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_value = $value AND meta_key = 'order-id-original'", ARRAY_A);
                $repair_id = $results[0]['post_id'];

                $warranty = get_post_meta($repair_id, 'warranty', true);
              } else {
                if (empty($dolar_price)) {
                  $dolar_price = $sum;
                }

                $total_order = $total_order + $dolar_price * $item_data['quantity'];
                $order_sum = number_format($total_order, 2, ',', '');

                $total_order_csv = 0;
                foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                  $total_order_csv = number_format($csv_item, 2, ',', '') + number_format($total_order_csv, 2, ',', '');
                }
              }

              for ($sec = 1; $sec <= $property_nr_sections; $sec++) {
                $k++;
                /*  **********************************************************************
								--------------------------  Calcul Suma Dolari --------------------------
								**********************************************************************  */

                $sum = 0;

                //$discount_custom = get_user_meta($user_id, 'discount_custom',true);
                $frames = array();

                $sum = get_post_meta(1, $atributes[$property_material] . '-dolar', true);
                $basic = get_post_meta(1, $atributes[$property_material] . '-dolar', true);

                $sum = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);
                $basic = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);

                $frames[] = $atributes[$property_frametype];

                if (($property_style == 221) || ($property_style == 227) || ($property_style == 226) || ($property_style == 222) || ($property_style == 228)) {
                  $dolar_sum = dolarSum('Solid-dolar', $user_id);

                  $sum = $sum + ($dolar_sum * $basic) / 100;
                  $frames[] = $atributes[$property_style];
                }
                if ($property_style == 33) {
                  $dolar_sum = dolarSum('Shaped-dolar', $user_id);

                  $sum = $dolar_sum / 1;
                  $frames[] = $atributes[$property_style];
                }
                if ($property_style == 34) {
                  $dolar_sum = dolarSum('French_Door-dolar', $user_id);

                  $sum = $dolar_sum / 1;
                  $frames[] = $atributes[$property_style];
                }
                if ($property_style == 35) {
                  $dolar_sum = dolarSum('Tracked-dolar', $user_id);

                  $sum = $dolar_sum / 1;
                  $frames[] = $atributes[$property_style];
                }
                if (strlen($property_builtout) > 0 && !empty($property_builtout)) {
                  $frdepth = $framesType[$property_frametype];
                  $sum_build_frame = $frdepth + $property_frametype;
                  if ($sum_build_frame < 100) {
                    if (!empty(get_user_meta($user_id, 'Buildout-dolar', true)) || (get_user_meta($user_id, 'Buildout-dolar', true) > 0)) {
                      $sum = $sum + (get_user_meta($user_id, 'Buildout-dolar', true) * $basic) / 100;
                    } else {
                      $sum = $sum + (get_post_meta(1, 'Buildout-dolar', true) * $basic) / 100;
                    }
                  } elseif ($sum_build_frame >= 100) {
                    get_post_meta($product_id, 'sum_build_frame', true);
                    $sum = $sum + (20 * $basic) / 100;
                  }
                  $frames[] = 'Buildout';
                }
                if (($property_controltype == 403)) {
                  if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) || (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
                    $result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
                    $sum = $sum + ($result * $basic) / 100;
                  } else {
                    $result = get_post_meta(1, 'Concealed_Rod-dolar', true);
                    $sum = $sum + $result * $property_total;
                  }

                  $frames[] = 'Concealed_Rod';
                }
                if (($property_hingecolour == 93)) {
                  $dolar_sum = dolarSum('Stainless_Steel-dolar', $user_id);

                  $sum = $sum + ($dolar_sum * $basic) / 100;
                  $frames[] = 'Stainless_Steel';
                }

// If tpost have buidout add 7%
                $t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
                foreach ($t_buildout as $property_tb) {
                  if (!empty(get_post_meta($product_id, $property_tb . '_' . $sec, true))) {
                    $dolar_sum = dolarSum('T_Buildout-dolar', $user_id);

                    $sum = $sum + ($dolar_sum * $basic) / 100;
                    $frames[] = 'T_Buildout';
                    break;
                  }
                }
// If Cpost have buidout add 7%
                $c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
                foreach ($c_buildout as $property_cb) {
                  if (!empty(get_post_meta($product_id, $property_cb . '_' . $sec, true))) {
                    $dolar_sum = dolarSum('C_Buildout-dolar', $user_id);

                    $sum = $sum + ($dolar_sum * $basic) / 100;
                    $frames[] = 'C_Buildout';
                    break;
                  }
                }
                if (($property_shuttercolour == 264) || ($property_shuttercolour == 265) || ($property_shuttercolour == 266) || ($property_shuttercolour == 267) || ($property_shuttercolour == 268) || ($property_shuttercolour == 269) || ($property_shuttercolour == 270) || ($property_shuttercolour == 271) || ($property_shuttercolour == 271) || ($property_shuttercolour == 273)) {
                  $dolar_sum = dolarSum('Colors-dolar', $user_id);

                  $sum = $sum + ($dolar_sum * $basic) / 100;
                  $frames[] = $atributes[$property_shuttercolour];
                }
                if (!empty($property_ringpull && $property_ringpull == 'Yes')) {
                  $dolar_sum = dolarSum('Ringpull-dolar', $user_id);

                  $sum = $sum + ($dolar_sum * $property_ringpull_volume);
                  $frames[] = 'Ringpull';
                }
                if (!empty($property_locks && $property_locks == 'Yes')) {
                  $dolar_sum = dolarSum('Lock-dolar', $user_id);

                  $sum = $sum + ($dolar_sum * $property_locks_volume);
                  $frames[] = 'Lock';
                }
                if (!empty($property_sparelouvres && $property_sparelouvres == 'Yes')) {
                  $dolar_sum = dolarSum('Spare_Louvres-dolar', $user_id);

                  $sum = $sum + $dolar_sum;
                  $frames[] = 'Spare_Louvres';
                }

                /*  **********************************************************************
								--------------------------   END - Calcul Suma Dolari --------------------------
								**********************************************************************  */

                $indiv_amount_csv = $indiv_amount_csv + $csv_orders_array[$order->get_id()][$k];

                $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');
                if ($diff_sum < 0) {
                  $diff_class_sum = 'negative';
                } elseif ($diff_sum > 0) {
                  $diff_class_sum = 'positive';
                }
                if ($a != $size) {
                  $diff_class_sum = 'neutral';
                }
                if ($property_nr_sections == $sec) {
                  $diff_amount = (number_format($dolar_price, 2, ',', '') * $item_quantity) - number_format($indiv_amount_csv, 2, ',', '');
                  $diff_class = '';
                  if ($diff_amount < 0) {
                    $diff_class = 'negative';
                  } elseif ($diff_amount > 0) {
                    $diff_class = 'positive';
                  } else {
                    $diff_class = 'neutral';
                  }
                } else {
                  $diff_amount = 0;
                  $diff_class = 'neutral';
                }
// if($a == $size ){  $order_sum = $total_order; }
                $email_body .= '<tr id="' . $container_order . '-2">';
                if (get_post_type($container_order) == 'order_repair') {
                  $email_body .= '<td>LFR' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
                } else {
                  $email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
                }

                $email_body .= '<td>' . $a . '</td>
                            <td>';
                if ($term_list[0]->slug == 'pos') {
                  $email_body .= '' . get_the_title($product_id) . '';
                } else {
                  $email_body .= '' . $atributes[$property_material] . ' Idv' . $sec;
                }
                $email_body .= '</td>
                            <td>' . get_post_meta($product_id, 'property_width' . $sec, true) . '</td>
                            <td>' . $property_height . '</td>
                            <td>' . $property_depth . '</td>
                            <td>' . $item_quantity . '</td>
                            <td>m2</td>';
                if (get_post_type($container_order) == 'order_repair') {
                  $email_body .= '<td>0</td>';
                } else {
                  $property_total = get_post_meta($product_id, 'property_total_section' . $sec, true);
                  $email_body .= '<td>' . round($property_total, 3) * $item_quantity . '</td>';
                }
                $email_body .= '<td>$' . get_post_meta(1, $atributes[$property_material] . '-dolar', true) . '</td>';

                if (get_post_type($container_order) == 'order_repair') {
                  if ($warranty[$prod] == 'No') {
                    $repair_price = round($dolar_price, 2) * $item_quantity;
                  } else {
                    $repair_price = 0;
                  }
                  $email_body .= '<td> $' . $repair_price . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()][$k] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
                } else {
                  if ($property_nr_sections == $sec) {
                    $email_body .= '<td>$' . round($dolar_price, 2) * $item_quantity . '</td>
                            <td class="csv-amount">$' . $indiv_amount_csv . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
                  } else {
                    $email_body .= '<td>$0</td>
                            <td class="csv-amount">$0</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
                  }
                }
                foreach ($frames as $frame) {
                  $email_body .= '' . $frame . ' - ' . get_post_meta(1, $frame . '-dolar', true) . '<br>';
                }
                $email_body .= '</td>
                            <td>';

                if ($a == $size && $property_nr_sections == $sec) {
                  $email_body .= '$ ' . number_format($order_sum, 2, ',', '') . '';
                }
                $email_body .= '</td>
                            <td class="csv-total">';
                if ($a == $size && $property_nr_sections == $sec) {
                  $email_body .= '$ ' . number_format($total_order_csv, 2, ',', '') . '';
                }
                $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
                if ($a == $size && $property_nr_sections == $sec) {

                  $email_body .= '$ ' . number_format($diff_sum, 2, ',', '') . '';
                }
                $email_body .= '</td>
                        </tr>';
              }
            } else {
              $k++;
              $a++;
              /*  **********************************************************************
							--------------------------  Calcul Suma Dolari --------------------------
							**********************************************************************  */

              $sum = 0;

              //$discount_custom = get_user_meta($user_id, 'discount_custom',true);
              $frames = array();

              $sum = get_post_meta(1, $atributes[$property_material] . '-dolar', true);
              $basic = get_post_meta(1, $atributes[$property_material] . '-dolar', true);

              $sum = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);
              $basic = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);
              //echo 'SUM 1: '.$sum.'<br>';
              //echo 'BASIC 1: '.$basic.'<br>';

              // $sum = $property_total*100;
              // echo 'SUM 1: '.$sum.'<br>';
              // $basic = $property_total*100;
              // echo 'BASIC 1: '.$basic.'<br>';
              //style
              $frames[] = $atributes[$property_frametype];
              if ($property_frametype == 171) {
                //$sum = get_post_meta(1, 'P4028X-dolar', true) / 1;
                //echo 'SUM 10: '.$sum.'<br>';
                //echo 'BASIC 10: '.$basic.'<br>';
                //$frames[] = $atributes[$property_frametype];
              }
              if ($property_frametype == 322) {
                //$sum = get_post_meta(1, 'P4008T-dolar', true) / 1;
                //echo 'SUM 10: '.$sum.'<br>';
                //echo 'BASIC 10: '.$basic.'<br>';
                //$frames[] = $atributes[$property_frametype];
              }
              if ($property_frametype == 319) {
                //$sum = get_post_meta(1, 'P4008W-dolar', true) / 1;
                //echo 'SUM 10: '.$sum.'<br>';
                //echo 'BASIC 10: '.$basic.'<br>';
                //$frames[] = $atributes[$property_frametype];
              }
              if (($property_style == 221) || ($property_style == 227) || ($property_style == 226) || ($property_style == 222) || ($property_style == 228)) {
                $dolar_sum = dolarSum('Solid-dolar', $user_id);

                $sum = $sum + ($dolar_sum * $basic) / 100;
                //echo 'SUM 2: '.$sum.'<br>';
                //echo 'BASIC 2: '.$basic.'<br>';
                $frames[] = $atributes[$property_style];
              }
              if ($property_style == 33) {
                $dolar_sum = dolarSum('Shaped-dolar', $user_id);

                $sum = $dolar_sum / 1;
                //echo 'SUM 3: '.$sum.'<br>';
                //echo 'BASIC 3: '.$basic.'<br>';
                $frames[] = $atributes[$property_style];
              }
              if ($property_style == 34) {
                $dolar_sum = dolarSum('French_Door-dolar', $user_id);

                $sum = $dolar_sum / 1;
                //echo 'SUM 3: '.$sum.'<br>';
                //echo 'BASIC 3: '.$basic.'<br>';
                $frames[] = $atributes[$property_style];
              }
              if ($property_style == 35) {
                $dolar_sum = dolarSum('Tracked-dolar', $user_id);

                $sum = $dolar_sum / 1;
                //echo 'SUM 4: '.$sum.'<br>';
                //echo 'BASIC 4: '.$basic.'<br>';
                $frames[] = $atributes[$property_style];
              }
              if (strlen($property_builtout) > 0 && !empty($property_builtout)) {
                $frdepth = $framesType[$property_frametype];
                $sum_build_frame = $frdepth + $property_frametype;
                if ($sum_build_frame < 100) {
                  if (!empty(get_user_meta($user_id, 'Buildout-dolar', true)) || (get_user_meta($user_id, 'Buildout-dolar', true) > 0)) {
                    $sum = $sum + (get_user_meta($user_id, 'Buildout-dolar', true) * $basic) / 100;
                  } else {
                    $sum = $sum + (get_post_meta(1, 'Buildout-dolar', true) * $basic) / 100;
                  }
                } elseif ($sum_build_frame >= 100) {
                  get_post_meta($product_id, 'sum_build_frame', true);
                  $sum = $sum + (20 * $basic) / 100;
                }
                $frames[] = 'Buildout';
              }
              if ($property_frametype == 171) {

                $sum = $sum + (get_post_meta(1, 'P4028X-dolar', true) * $basic) / 100;
                if (!empty(get_post_meta(1, 'blackoutblind-dolar', true)) || (get_post_meta(1, 'blackoutblind-dolar', true) > 0)) {
                  $sum = $sum + get_post_meta(1, 'blackoutblind-dolar', true) * round($property_total, 2);
                }

                if (!empty($property_tposttype)) {
                  if (!empty(get_post_meta(1, 'tposttype_blackout-dolar', true)) || (get_post_meta(1, 'tposttype_blackout-dolar', true) > 0)) {
                    $sum = $sum + (get_post_meta(1, 'tposttype_blackout-dolar', true) * $basic) / 100;
                  }
                }
              }
//                    if (strlen($property_builtout) > 0 && !empty($property_builtout)) {
//                        $sum = $sum + (get_post_meta(1, 'Buildout-dolar', true) * $basic) / 100;
//                        //echo 'SUM 5: '.$sum.'<br>';
//                        //echo 'BASIC 5: '.$basic.'<br>';
//                        $frames[] = 'Buildout';
//                    }
              if (($property_controltype == 403)) {

                if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) || (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
                  $result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
                  $sum = $sum + ($result * $basic) / 100;
                } else {
                  $result = get_post_meta(1, 'Concealed_Rod-dolar', true);
                  $sum = $sum + $result * $property_total;
                }

                // echo 'SUM 6: '.$sum.'<br>';
                // echo 'BASIC 6: '.$basic.'<br>';
                $frames[] = 'Concealed_Rod';
              }
              if (($property_hingecolour == 93)) {
                $dolar_sum = dolarSum('Stainless_Steel-dolar', $user_id);

                $sum = $sum + ($dolar_sum * $basic) / 100;
                //echo 'SUM 6: '.$sum.'<br>';
                //echo 'BASIC 6: '.$basic.'<br>';
                $frames[] = 'Stainless_Steel';
              }
              $bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
              $bpost_unghi = 0;
              foreach ($bayangle as $property_ba) {
                if (!empty($property_ba)) {
                  if (!empty(get_post_meta($product_id, $property_ba, true))) {
                    if ($property_ba == 90) {
                      // echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
                    } elseif ($property_ba == 135) {
                      // echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
                    } else {
                      $dolar_sum = dolarSum('Bay_Angle-dolar', $user_id);

                      $sum = $sum + ($dolar_sum * $basic) / 100;
                      // echo 'SUM 7: '.$sum.'<br>';
                      // echo 'BASIC 7: '.$basic.'<br>';
                      // echo '----- Unghi diferit de  90: '.$property_ba.' ADAUGARE 10%------';
                      $bpost_unghi++;
                      $frames[] = 'Bay_Angle';
                      break;
                    }
                  }
                }
              }
              if ($bpost_unghi == 0) {
                // If Bpost have buidout add 7%
                $b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
                foreach ($b_buildout as $property_bb) {
                  if (!empty(get_post_meta($product_id, $property_bb, true))) {
                    if (!empty($property_bb)) {
                      $dolar_sum = dolarSum('B_Buildout-dolar', $user_id);

                      $sum = $sum + ($dolar_sum * $basic) / 100;
                      //echo 'SUM 7: '.$sum.'<br>';
                      // echo 'BASIC 7: '.$basic.'<br>';
                      $frames[] = 'B_Buildout';
                      break;
                    }
                  }
                }
              }
// If tpost have buidout add 7%
              $t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
              foreach ($t_buildout as $property_tb) {
                if (!empty(get_post_meta($product_id, $property_tb, true))) {
                  if (!empty($property_tb)) {
                    $dolar_sum = dolarSum('T_Buildout-dolar', $user_id);

                    $sum = $sum + ($dolar_sum * $basic) / 100;
                    //echo 'SUM 7: '.$sum.'<br>';
                    //echo 'BASIC 7: '.$basic.'<br>';
                    $frames[] = 'T_Buildout';
                    break;
                  }
                }
              }
// If Cpost have buidout add 7%
              $c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
              foreach ($c_buildout as $property_cb) {
                if (!empty(get_post_meta($product_id, $property_cb, true))) {
                  if (!empty($property_cb)) {
                    $dolar_sum = dolarSum('C_Buildout-dolar', $user_id);

                    $sum = $sum + ($dolar_sum * $basic) / 100;
                    //echo 'SUM 7: '.$sum.'<br>';
                    //echo 'BASIC 7: '.$basic.'<br>';
                    $frames[] = 'C_Buildout';
                    break;
                  }
                }
              }
              if (($property_shuttercolour == 264) || ($property_shuttercolour == 265) || ($property_shuttercolour == 266) || ($property_shuttercolour == 267) || ($property_shuttercolour == 268) || ($property_shuttercolour == 269) || ($property_shuttercolour == 270) || ($property_shuttercolour == 271) || ($property_shuttercolour == 271) || ($property_shuttercolour == 273)) {
                $dolar_sum = dolarSum('Colors-dolar', $user_id);

                $sum = $sum + ($dolar_sum * $basic) / 100;
                //echo 'SUM 11: '.$sum.'<br>';
                //echo 'BASIC 11: '.$basic.'<br>';
                $frames[] = $atributes[$property_shuttercolour];
              }
              if (!empty($property_ringpull && $property_ringpull == 'Yes')) {
                $dolar_sum = dolarSum('Ringpull-dolar', $user_id);

                $sum = $sum + ($dolar_sum * $property_ringpull_volume);
                //echo 'SUM 8: '.$sum.'<br>';
                //echo 'BASIC 8: '.$basic.'<br>';
                $frames[] = 'Ringpull';
              }
              if (!empty($property_locks && $property_locks == 'Yes')) {
                $dolar_sum = dolarSum('Lock-dolar', $user_id);

                $sum = $sum + ($dolar_sum * $property_locks_volume);
                //echo 'SUM 8: '.$sum.'<br>';
                //echo 'BASIC 8: '.$basic.'<br>';
                $frames[] = 'Lock';
              }
              if (!empty($property_sparelouvres && $property_sparelouvres == 'Yes')) {
                $dolar_sum = dolarSum('Spare_Louvres-dolar', $user_id);

                $sum = $sum + $dolar_sum;
                //echo 'SUM 9: '.$sum.'<br>';
                //echo 'BASIC 9: '.$basic.'<br>';
                $frames[] = 'Spare_Louvres';
              }
//print_r($frames);
//echo "Suma_total DOLARS: ".$sum.'<br><br><br>';

              /*  **********************************************************************
							--------------------------   END - Calcul Suma Dolari --------------------------
							**********************************************************************  */

              if (get_post_type($container_order) == 'order_repair') {
                if (empty($dolar_price)) {
                  $dolar_price = $sum;
                }
                $dolar_price = 0;

                $total_order = $total_order + 0;
                $order_sum = number_format($total_order, 2, ',', '');

                $total_order_csv = 0;
                foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                  $total_order_csv = 0 + $total_order_csv;
                }

                global $wpdb;
                $key = 'order-id-original';
                $value = $order->get_id();
                $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_value = $value AND meta_key = 'order-id-original'", ARRAY_A);
                $repair_id = $results[0]['post_id'];

                $warranty = get_post_meta($repair_id, 'warranty', true);
              } else {
                if (empty($dolar_price)) {
                  $dolar_price = $sum;
                }

                $total_order = $total_order + $dolar_price * $item_data['quantity'];
                $order_sum = number_format($total_order, 2, ',', '');

                $total_order_csv = 0;
                foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
                  $total_order_csv = number_format($csv_item, 2, ',', '') + number_format($total_order_csv, 2, ',', '');
                }
              }

              $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');
              if ($diff_sum < 0) {
                $diff_class_sum = 'negative';
              } elseif ($diff_sum > 0) {
                $diff_class_sum = 'positive';
              }
              if ($a != $size) {
                $diff_class_sum = 'neutral';
              }

              $diff_amount = (number_format($dolar_price, 2, ',', '') * $item_quantity) - number_format($csv_orders_array[$order->get_id()][$k], 2, ',', '');
              $diff_class = '';
              if ($diff_amount < 0) {
                $diff_class = 'negative';
              } elseif ($diff_amount > 0) {
                $diff_class = 'positive';
              } else {
                $diff_class = 'neutral';
              }
// if($a == $size ){  $order_sum = $total_order; }
              $email_body .= '<tr id="' . $container_order . '-3">';
              if (get_post_type($container_order) == 'order_repair') {
                $email_body .= '<td>LFR' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
              } else {
                $email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
              }

              $email_body .= '<td>' . $a . '</td>
                            <td>';
              if ($term_list[0]->slug == 'pos') {
                $email_body .= '' . get_the_title($product_id) . '';
              } else {
                $email_body .= '' . $atributes[$property_material] . '';
              }
              $email_body .= '</td>
                            <td>' . $property_width . '</td>
                            <td>' . $property_height . '</td>
                            <td>' . $property_depth . '</td>
                            <td>' . $item_quantity . '</td>
                            <td>m2</td>';
              if (get_post_type($container_order) == 'order_repair') {
                $email_body .= '<td>0</td>';
              } else {
                $email_body .= '<td>' . round($property_total, 3) * $item_quantity . '</td>';
              }
              $email_body .= '<td>$' . get_post_meta(1, $atributes[$property_material] . '-dolar', true) . '</td>';

              if (get_post_type($container_order) == 'order_repair') {
                if ($warranty[$prod] == 'No') {
                  $repair_price = round($dolar_price, 2) * $item_quantity;
                } else {
                  $repair_price = 0;
                }
                $email_body .= '<td> $' . $repair_price . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()][$k] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
              } else {
                $email_body .= '<td>$' . round($dolar_price, 2) * $item_quantity . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()][$k] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
              }
              foreach ($frames as $frame) {
                $email_body .= '' . $frame . ' - ' . get_post_meta(1, $frame . '-dolar', true) . '<br>';
              }
              $email_body .= '</td>
                            <td>';
              if ($a == $size) {
                $email_body .= '$ ' . number_format($order_sum, 2, ',', '') . '';
              }
              $email_body .= '</td>
                            <td class="csv-total">';
              if ($a == $size) {
                $email_body .= '$ ' . number_format($total_order_csv, 2, ',', '') . '';
                if ($company === 'Lifetime Shutters') {
                }
              }
              $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
              if ($a == $size) {

                $email_body .= '$ ' . number_format($diff_sum, 2, ',', '') . '';
              }
              $email_body .= '</td>
                        </tr>';

              if ($company === 'Lifetime Shutters') {
                $lifetime_orders['total_dolar'] += $order_sum;
                $lifetime_orders['csv_total_dolar'] += $total_order_csv;
              } else {
                $customers_orders['total_dolar'] += $order_sum;
                $customers_orders['csv_total_dolar'] += $total_order_csv;
              }
            }
          }
        }
      }
      if (get_post_type($container_order) != 'order_repair') {
        $payment_order = $payment_order + $total_order;
        $payment_order_csv = $payment_order_csv + $total_order_csv;
      }
      //$payment_order = $payment_order + $total_order;
    }
  }
  /*
	 * Show orders extra from csv
	 */

  $csv_matrix_orders_plus = array();
  //  print_r($csv_orders_array);
  foreach ($csv_orders_array as $order => $val) {

    global $wpdb;
    $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_key = 'order-id-original' AND meta_value = $order", ARRAY_A);
    $repairs_array = array();
    if ($results) {
      foreach ($results as $repair) {
        $order_id = $repair['post_id'];
        $repairs_array[] = $order_id;
      }
      // parcurg fiecare order din csv si vad daca exista un repair si il adaug in array
      // daca arrayul de repairs si cel de container au id-uri in comune inseamna ca exista in container, altfel se adauga in order_plus
      // din cauza ca id-ul din container exista si el are si repair, id-ul de repair nu e gasit in container si il adauga in orders_plus
      $same_ids = array_intersect($repairs_array, $container_orders);
      $repair_in_container = get_post_meta($order_id, 'container_id', true);

      if (empty($same_ids) && !empty($repair_in_container) && $current_container_id == $repair_in_container) {
        $order_number = get_post_meta($order, '_order_number', true);
        if (!empty($csv_orders_array) && !in_array($order_id, $container_orders)) {
          $csv_matrix_orders_plus[$order] = '0';
          // echo '<br> CSV order R not found in container ' . $order . ' - LFR' . $order_number . ' ';
        }
      }
      //print_r($results);
    } else {
      $order_number = get_post_meta($order, '_order_number', true);
      if (!empty($csv_orders_array) && !in_array($order, $container_orders)) {
        if ($order != 0 || !empty($order)) {
          $csv_matrix_orders_plus[$order] = '0';
          // echo '<br> CSV order not found in container ' . $order . ' - LF0' . $order_number;
        }
      }
    }
  }

  foreach ($csv_matrix_orders_plus as $order => $val) {
    global $wpdb;
    $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_key = 'order-id-original' AND meta_value = $order", ARRAY_A);

    //$order = new WC_Order( $order_id );
    $order = wc_get_order($order);
    $items = $order->get_items();
    $order_data = $order->get_data();
    // echo '<pre>';
    // print_r($order_data);
    // echo '</pre>';
    $i = 0;
    $atributes = get_post_meta(1, 'attributes_array', true);

    $user_id = $order->get_customer_id();

    $nr_code_prod = array();
    foreach ($order->get_items() as $prod => $item_data) {
      $i++;
      $product = $item_data->get_product();
      $product_id = $product->id;

      $nr_g = get_post_meta($product_id, 'counter_g', true);
      $nr_t = get_post_meta($product_id, 'counter_t', true);
      $nr_b = get_post_meta($product_id, 'counter_b', true);
      $nr_c = get_post_meta($product_id, 'counter_c', true);

      if (!empty($nr_code_prod)) {
        if ($nr_code_prod['g'] < $nr_g) {
          $nr_code_prod['g'] = $nr_g;
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
        $nr_code_prod['g'] = $nr_g;
        $nr_code_prod['t'] = $nr_t;
        $nr_code_prod['b'] = $nr_b;
        $nr_code_prod['c'] = $nr_c;
      }
    }

    $array_att = array();
    $a = 0;
    $k = 0;
    $b = 0;
    $total_order = 0;
    $size = count($order->get_items());
    foreach ($order->get_items() as $prod => $item_data) {
      $i++;

      $product = $item_data->get_product();
      $product_id = $product->id;
      // Get an instance of corresponding the WC_Product object
      $product_name = $product->get_name(); // Get the product name

      $item_quantity = $item_data->get_quantity(); // Get the item quantity

      $item_total = $item_data->get_total(); // Get the item line total

      $property_room_other = get_post_meta($product_id, 'property_room_other', true);
      $property_style = get_post_meta($product_id, 'property_style', true);
      $property_frametype = get_post_meta($product_id, 'property_frametype', true);
      $property_tposttype = get_post_meta($product_id, 'property_tposttype', true);

      $attachment = get_post_meta($product_id, 'attachment', true);
      $array_att[] = $attachment;

      $property_category = get_post_meta($product_id, 'shutter_category', true);
      $property_material = get_post_meta($product_id, 'property_material', true);
      $property_width = get_post_meta($product_id, 'property_width', true);
      $property_height = get_post_meta($product_id, 'property_height', true);
      $property_depth = get_post_meta($product_id, 'property_depth', true);
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
      $property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
      $property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
      $property_controltype = get_post_meta($product_id, 'property_controltype', true);
      $property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
      $property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
      $property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
      $property_t1 = get_post_meta($product_id, 'property_t1', true);
      $property_total = get_post_meta($product_id, 'property_total', true);

      $property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
      $property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
      $property_locks = get_post_meta($product_id, 'property_locks', true);
      $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
      $property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);
      $price = get_post_meta($product_id, '_price', true);
      $regular_price = get_post_meta($product_id, '_regular_price', true);
      $sale_price = get_post_meta($product_id, '_sale_price', true);

      $sections_price = get_post_meta($product_id, 'sections_price', true);

      $price_earth = get_post_meta(1, 'Earth', true);
      $price_green = get_post_meta(1, 'Green', true);
      $price_biowood = get_post_meta(1, 'Biowood', true);
      $price_supreme = get_post_meta(1, 'Supreme', true);
      $dolar_price = get_post_meta($product_id, 'dolar_price', true);

      $materials = array('Earth' => $price_earth, 'Green' => $price_green, 'Biowood' => $price_biowood, 'Supreme' => $price_supreme);

      $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
      // echo '<pre>';
      // print_r($term_list->slug);
      // echo '</pre>';
      if ($product_id == 337 || $product_id == 72951) {
        $product = wc_get_product($product_id);
        $dolar_price = 100;
      }
      // elseif( $term_list[0]->slug == 'pos' ) {
      // }
      elseif ($property_category === 'Batten') {
        $b++;
        $a++;

        if (!empty($results)) {
          if (empty($dolar_price)) {
            $dolar_price = 0;
          }

          $total_order = $total_order + 0;
          $order_sum = $total_order;

          $total_order_csv = 0;
          foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
            $total_order_csv = 0 + $total_order_csv;
          }

          $diff_sum = 0;
        } else {
          if (empty($dolar_price)) {
            $battenCustomDolar = get_post_meta(1, 'BattenCustom-dolar', true);
            $dolar_price = number_format($property_total, 2, ',', '') * $battenCustomDolar;
          }

          $total_order = $total_order + 0 * $item_data['quantity'];
          $order_sum = number_format($total_order, 2, ',', '');

          $total_order_csv = 0;
          foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
            $total_order_csv = number_format($csv_item, 2, ',', '') + number_format($total_order_csv, 2, ',', '');
          }

          $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');
        }

        if ($diff_sum < 0) {
          $diff_class_sum = 'negative';
        } elseif ($diff_sum > 0) {
          $diff_class_sum = 'positive';
        }
        if ($a != $size) {
          $diff_class_sum = 'neutral';
        }
        $diff_amount = (floatval($dolar_price) * $item_quantity) - floatval($csv_orders_array[$order->get_id()]['batten-' . $b]);
        $diff_class = '';
        if ($diff_amount < 0) {
          $diff_class = 'negative';
        } elseif ($diff_amount > 0) {
          $diff_class = 'positive';
        } else {
          $diff_class = 'neutral';
        }
// if($a == $size ){  $order_sum = $total_order; }

        $email_body .= '<tr id="' . $container_order . '-4" style="background-color: #7bdcb5">';
        if (!empty($results)) {
          $email_body .= '<td>LFR' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
        } else {
          $email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
        }
        //$email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>;
        $email_body .= '<td>' . $a . '</td>
                            <td>';
        if ($term_list[0]->slug == 'pos') {
          $email_body .= '' . get_the_title($product_id) . '';
        } else {
          $email_body .= '' . $atributes[$property_material] . '';
        }
        $email_body .= '</td>
                            <td>' . $property_width . '</td>
                            <td>' . $property_height . '</td>
                            <td>' . $property_depth . '</td>
                            <td>' . $item_quantity . '</td>
                            <td>m2</td>
                            <td>' . round($property_total, 3) * $item_quantity . '</td>
                            <td>$' . get_post_meta(1, $atributes[$property_material] . '-dolar', true) . '</td>';
        if (!empty($results)) {
          $email_body .= '<td>$ 0</td>
                            <td class="csv-amount">$ 0</td>
                            <td class="csv-diff ' . $diff_class . '">$ 0</td>
                            <td>';
        } else {
          $email_body .= '<td>$' . round($dolar_price, 2) * $item_quantity . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()]['batten-' . $b] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
        }

//                foreach ($frames as $frame) {
//                    $email_body .= '' . $frame . ' - ' . get_post_meta(1, $frame . '-dolar', true) . '<br>';
//                }
        $email_body .= '</td>
                            <td>';
        if (!empty($results)) {
          if ($a == $size) {
            $email_body .= '$ 0';
          }
          $email_body .= '</td>
                            <td class="csv-total">';
          if ($a == $size) {
            $email_body .= '$ 0';
          }
          $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
          if ($a == $size) {
            $email_body .= '$ 0';
          }
        } else {
          if ($a == $size) {
            $email_body .= '$ ' . $order_sum . '';
          }
          $email_body .= '</td>
                            <td class="csv-total">';
          if ($a == $size) {
            $email_body .= '$ ' . number_format($total_order_csv, 2, ',', '') . '';
          }
          $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
          if ($a == $size) {
            $email_body .= '$ ' . number_format($diff_sum, 2, ',', '') . '';
          }
        }

        $email_body .= '</td>
                        </tr>';
      } else {
        $k++;
        $a++;
        /*  **********************************************************************
				--------------------------  Calcul Suma Dolari --------------------------
				**********************************************************************  */

        $sum = 0;

        //$discount_custom = get_user_meta($user_id, 'discount_custom',true);
        $frames = array();

        $sum = get_post_meta(1, $atributes[$property_material] . '-dolar', true);
        $basic = get_post_meta(1, $atributes[$property_material] . '-dolar', true);

        $sum = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);
        $basic = $property_total * get_post_meta(1, $atributes[$property_material] . '-dolar', true);
        //echo 'SUM 1: '.$sum.'<br>';
        //echo 'BASIC 1: '.$basic.'<br>';

        // $sum = $property_total*100;
        // echo 'SUM 1: '.$sum.'<br>';
        // $basic = $property_total*100;
        // echo 'BASIC 1: '.$basic.'<br>';
        //style
        $frames[] = $atributes[$property_frametype];
        if ($property_frametype == 171) {
          //$sum = get_post_meta(1, 'P4028X-dolar', true) / 1;
          //echo 'SUM 10: '.$sum.'<br>';
          //echo 'BASIC 10: '.$basic.'<br>';
          // $frames[] = $atributes[$property_frametype];
        }
        if ($property_frametype == 322) {
          //$sum = get_post_meta(1, 'P4008T-dolar', true) / 1;
          //echo 'SUM 10: '.$sum.'<br>';
          //echo 'BASIC 10: '.$basic.'<br>';
          // $frames[] = $atributes[$property_frametype];
        }
        if ($property_frametype == 319) {
          //$sum = get_post_meta(1, 'P4008W-dolar', true) / 1;
          //echo 'SUM 10: '.$sum.'<br>';
          //echo 'BASIC 10: '.$basic.'<br>';
          // $frames[] = $atributes[$property_frametype];
        }
        if (($property_style == 221) || ($property_style == 227) || ($property_style == 226) || ($property_style == 222) || ($property_style == 228)) {
          $sum = $sum + (get_post_meta(1, 'Solid-dolar', true) * $basic) / 100;
          //echo 'SUM 2: '.$sum.'<br>';
          //echo 'BASIC 2: '.$basic.'<br>';
          $frames[] = $atributes[$property_style];
        }
        if ($property_style == 33) {
          $sum = get_post_meta(1, 'Shaped-dolar', true) / 1;
          //echo 'SUM 3: '.$sum.'<br>';
          //echo 'BASIC 3: '.$basic.'<br>';
          $frames[] = $atributes[$property_style];
        }
        if ($property_style == 34) {
          $sum = get_post_meta(1, 'French_Door-dolar', true) / 1;
          //echo 'SUM 3: '.$sum.'<br>';
          //echo 'BASIC 3: '.$basic.'<br>';
          $frames[] = $atributes[$property_style];
        }
        if ($property_style == 35) {
          $sum = get_post_meta(1, 'Tracked-dolar', true) / 1;
          //echo 'SUM 4: '.$sum.'<br>';
          //echo 'BASIC 4: '.$basic.'<br>';
          $frames[] = $atributes[$property_style];
        }
        if (strlen($property_builtout) > 0 && !empty($property_builtout)) {
          $frdepth = $framesType[$property_frametype];
          $sum_build_frame = $frdepth + $property_frametype;
          if ($sum_build_frame < 100) {
            if (!empty(get_user_meta($user_id, 'Buildout-dolar', true)) || (get_user_meta($user_id, 'Buildout-dolar', true) > 0)) {
              $sum = $sum + (get_user_meta($user_id, 'Buildout-dolar', true) * $basic) / 100;
            } else {
              $sum = $sum + (get_post_meta(1, 'Buildout-dolar', true) * $basic) / 100;
            }
          } elseif ($sum_build_frame >= 100) {
            get_post_meta($product_id, 'sum_build_frame', true);
            $sum = $sum + (20 * $basic) / 100;
          }
          $frames[] = 'Buildout';
          //  $frames[] = $atributes[$property_frametype];
        }

        if ($property_frametype == 171) {

          $sum = $sum + (get_post_meta(1, 'P4028X-dolar', true) * $basic) / 100;
          if (!empty(get_post_meta(1, 'blackoutblind-dolar', true)) || (get_post_meta(1, 'blackoutblind-dolar', true) > 0)) {
            $sum = $sum + get_post_meta(1, 'blackoutblind-dolar', true) * round($property_total, 2);
          }

          if (!empty($property_tposttype)) {
            if (!empty(get_post_meta(1, 'tposttype_blackout-dolar', true)) || (get_post_meta(1, 'tposttype_blackout-dolar', true) > 0)) {
              $sum = $sum + (get_post_meta(1, 'tposttype_blackout-dolar', true) * $basic) / 100;
            }
          }
        }

        if (($property_controltype == 403)) {
          if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) || (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
            $result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
            $sum = $sum + ($result * $basic) / 100;
          } else {
            $result = get_post_meta(1, 'Concealed_Rod-dolar', true);
            $sum = $sum + $result * $property_total;
          }
          // echo 'SUM 6: '.$sum.'<br>';
          // echo 'BASIC 6: '.$basic.'<br>';
          $frames[] = 'Concealed_Rod';
        }
        if (($property_hingecolour == 93)) {
          $sum = $sum + (get_post_meta(1, 'Stainless_Steel-dolar', true) * $basic) / 100;
          //echo 'SUM 6: '.$sum.'<br>';
          //echo 'BASIC 6: '.$basic.'<br>';
          $frames[] = 'Stainless_Steel';
        }
        $bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
        $bpost_unghi = 0;
        foreach ($bayangle as $property_ba) {
          if (!empty($property_ba)) {
            if (!empty(get_post_meta($product_id, $property_ba, true))) {
              if ($property_ba == 90) {
                // echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
              } elseif ($property_ba == 135) {
                // echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
              } else {
                $sum = $sum + (dolarSum('Bay_Angle-dolar', $user_id) * $basic) / 100;
                // echo 'SUM 7: '.$sum.'<br>';
                // echo 'BASIC 7: '.$basic.'<br>';
                // echo '----- Unghi diferit de  90: '.$property_ba.' ADAUGARE 10%------';
                $bpost_unghi++;
                $frames[] = 'Bay_Angle';
                break;
              }
            }
          }
        }
        if ($bpost_unghi == 0) {
          // If Bpost have buidout add 7%
          $b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
          foreach ($b_buildout as $property_bb) {
            if (!empty(get_post_meta($product_id, $property_bb, true))) {
              if (!empty($property_bb)) {
                $dolar_sum = dolarSum('B_Buildout-dolar', $user_id);
                $sum = $sum + ($dolar_sum * $basic) / 100;
                //echo 'SUM 7: '.$sum.'<br>';
                // echo 'BASIC 7: '.$basic.'<br>';
                $frames[] = 'B_Buildout';
                break;
              }
            }
          }
        }
// If tpost have buidout add 7%
        $t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
        foreach ($t_buildout as $property_tb) {
          if (!empty(get_post_meta($product_id, $property_tb, true))) {
            if (!empty($property_tb)) {
              $dolar_sum = dolarSum('T_Buildout-dolar', $user_id);

              $sum = $sum + ($dolar_sum * $basic) / 100;
              //echo 'SUM 7: '.$sum.'<br>';
              //echo 'BASIC 7: '.$basic.'<br>';
              $frames[] = 'T_Buildout';
              break;
            }
          }
        }
// If Cpost have buidout add 7%
        $c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
        foreach ($c_buildout as $property_cb) {
          if (!empty(get_post_meta($product_id, $property_cb, true))) {
            if (!empty($property_cb)) {
              $dolar_sum = dolarSum('C_Buildout-dolar', $user_id);

              $sum = $sum + ($dolar_sum * $basic) / 100;
              //echo 'SUM 7: '.$sum.'<br>';
              //echo 'BASIC 7: '.$basic.'<br>';
              $frames[] = 'C_Buildout';
              break;
            }
          }
        }
        if (($property_shuttercolour == 264) || ($property_shuttercolour == 265) || ($property_shuttercolour == 266) || ($property_shuttercolour == 267) || ($property_shuttercolour == 268) || ($property_shuttercolour == 269) || ($property_shuttercolour == 270) || ($property_shuttercolour == 271) || ($property_shuttercolour == 271) || ($property_shuttercolour == 273)) {
          $dolar_sum = dolarSum('Colors-dolar', $user_id);

          $sum = $sum + ($dolar_sum * $basic) / 100;
          //echo 'SUM 11: '.$sum.'<br>';
          //echo 'BASIC 11: '.$basic.'<br>';
          $frames[] = $atributes[$property_shuttercolour];
        }
        if (!empty($property_ringpull && $property_ringpull == 'Yes')) {
          $dolar_sum = dolarSum('Ringpull-dolar', $user_id);

          $sum = $sum + ($dolar_sum * $property_ringpull_volume);
          //echo 'SUM 8: '.$sum.'<br>';
          //echo 'BASIC 8: '.$basic.'<br>';
          $frames[] = 'Ringpull';
        }
        if (!empty($property_locks && $property_locks == 'Yes')) {
          $dolar_sum = dolarSum('Lock-dolar', $user_id);

          $sum = $sum + ($dolar_sum * $property_locks_volume);
          //echo 'SUM 8: '.$sum.'<br>';
          //echo 'BASIC 8: '.$basic.'<br>';
          $frames[] = 'Lock';
        }
        if (!empty($property_sparelouvres && $property_sparelouvres == 'Yes')) {
          $dolar_sum = dolarSum('Spare_Louvres-dolar', $user_id);

          $sum = $sum + $dolar_sum;
          //echo 'SUM 9: '.$sum.'<br>';
          //echo 'BASIC 9: '.$basic.'<br>';
          $frames[] = 'Spare_Louvres';
        }
//print_r($frames);
//echo "Suma_total DOLARS: ".$sum.'<br><br><br>';

        /*  **********************************************************************
				--------------------------   END - Calcul Suma Dolari --------------------------
				**********************************************************************  */

        if (!empty($results)) {
          if (empty($dolar_price)) {
            $dolar_price = 0;
          }

          $total_order = $total_order + 0;
          $order_sum = $total_order;

          $total_order_csv = 0;
          foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
            $total_order_csv = 0 + $total_order_csv;
          }

          $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');
        } else {
          if (empty($dolar_price)) {
            $dolar_price = $sum;
          }

          $total_order = $total_order + 0 * $item_data['quantity'];
          $order_sum = number_format($total_order, 2, ',', '');

          $total_order_csv = 0;
          foreach ($csv_orders_array[$order->get_id()] as $csv_item) {
            $total_order_csv = number_format($csv_item, 2, ',', '') + number_format($total_order_csv, 2, ',', '');
          }

          //$diff_sum = $order_sum - $total_order_csv;
          $diff_sum = number_format($order_sum, 2, ',', '') - number_format($total_order_csv, 2, ',', '');
        }

        if ($diff_sum < 0) {
          $diff_class_sum = 'negative';
        } elseif ($diff_sum > 0) {
          $diff_class_sum = 'positive';
        }
        if ($a != $size) {
          $diff_class_sum = 'neutral';
        }

        $diff_amount = (number_format($dolar_price, 2, ',', '') * $item_quantity) - number_format($csv_orders_array[$order->get_id()][$k], 2, ',', '');

        $diff_class = '';
        if ($diff_amount < 0) {
          $diff_class = 'negative';
        } elseif ($diff_amount > 0) {
          $diff_class = 'positive';
        } else {
          $diff_class = 'neutral';
        }
// if($a == $size ){  $order_sum = $total_order; }
        $email_body .= '<tr id="' . $order->get_id() . '-5" style="background-color: #7bdcb5">';
        if (!empty($results)) {
          $email_body .= '<td>LFR' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
        } else {
          $email_body .= '<td>LF0' . $order->get_order_number() . ' - ' . get_post_meta($order->get_id(), 'cart_name', true) . '</td>';
        }

        $email_body .= '<td>' . $a . '</td>
                            <td>';
        if ($term_list[0]->slug == 'pos') {
          $email_body .= '' . get_the_title($product_id) . '';
        } else {
          $email_body .= '' . $atributes[$property_material] . '';
        }
        $email_body .= '</td>
                            <td>' . $property_width . '</td>
                            <td>' . $property_height . '</td>
                            <td>' . $property_depth . '</td>
                            <td>' . $item_quantity . '</td>
                            <td>m2</td>
                            <td>' . round($property_total, 3) * $item_quantity . '</td>
                            <td>$' . get_post_meta(1, $atributes[$property_material] . '-dolar', true) . '</td>';
        if (!empty($results)) {
          $email_body .= '<td>$ 0</td>
                            <td class="csv-amount">$ 0</td>
                            <td class="csv-diff ' . $diff_class . '">$ 0</td>
                            <td>';
        } else {
          $email_body .= '<td>$' . round($dolar_price, 2) * $item_quantity . '</td>
                            <td class="csv-amount">$' . $csv_orders_array[$order->get_id()][$k] . '</td>
                            <td class="csv-diff ' . $diff_class . '">$' . number_format($diff_amount, 2, ',', '') . '</td>
                            <td>';
        }

        foreach ($frames as $frame) {
          $email_body .= '' . $frame . ' - ' . get_post_meta(1, $frame . '-dolar', true) . '<br>';
        }
        $email_body .= '</td>
                            <td>';

        if (!empty($results)) {
          if ($a == $size) {
            $email_body .= '$ 0';
          }
          $email_body .= '</td>
                            <td class="csv-total">';
          if ($a == $size) {
            $email_body .= '$ 0';
          }
          $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
          if ($a == $size) {

            $email_body .= '$0';
          }
        } else {
          if ($a == $size) {
            $email_body .= '$ ' . number_format($order_sum, 2, '.', ',') . '';
            if ($company === 'Lifetime Shutters') {
            }
          }
          $email_body .= '</td>
                            <td class="csv-total">';
          if ($a == $size) {
            $email_body .= '$ ' . number_format($total_order_csv, 2, ',', '') . '';
            if ($company === 'Lifetime Shutters') {
            }
          }
          $email_body .= '</td>
                        <td class="csv-diff-sum ' . $diff_class_sum . '">';
          if ($a == $size) {

            $email_body .= '$ ' . number_format($diff_sum, 2, ',', '') . '';
          }
        }

        if ($company === 'Lifetime Shutters') {
          $lifetime_orders['total_dolar'] += $order_sum;
          $lifetime_orders['csv_total_dolar'] += $total_order_csv;
        } else {
          $customers_orders['total_dolar'] += $order_sum;
          $customers_orders['csv_total_dolar'] += $total_order_csv;
        }

        $email_body .= '</td>
                        </tr>';
      }
    }
    if (empty($results)) {
      $payment_order = $payment_order + $total_order;
      $payment_order_csv = $payment_order_csv + $total_order_csv;
    }
    //$payment_order = $payment_order + $total_order;
  }
}

update_post_meta(get_the_id(), 'lifetime_orders_invoice', $lifetime_orders);
update_post_meta(get_the_id(), 'customers_orders_invoice', $customers_orders);

$email_body .= '
    <tr style="background-color:yellow;">
        <td>
        <strong>Full Payment</strong>
        </td>
        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        <td><strong>$' . round($payment_order, 2) . '</strong></td><td><strong>$' . round($payment_order_csv, 2) . ' - CSV</strong></td><td></td>
    </tr>
    </tbody>
</table>';
//  print_r($csv_orders_array);
?>

<div class="show_tabble">
  <?php echo $email_body; ?>
</div>

<style>
  #example {
    overflow: auto;
    width: auto;
    display: block;
  }

  #woocommerce-order-items,
  #postcustom {
    display: none;
  }

  #items-order td {
    min-width: 15%;
  }

  td.csv-diff.negative, td.csv-diff-sum.negative {
    background-color: #ffb3b3;
  }

  td.csv-diff.positive, td.csv-diff-sum.positive {
    background-color: #c6ffb3;
  }

  td.csv-diff.neutral, td.csv-diff-sum.neutral {
    background-color: transparent;
  }
</style>

<?php

function dolarSum($name_prop, $user_id)
{
  $result = 0;
  if (!empty(get_user_meta($user_id, $name_prop, true)) || (get_user_meta($user_id, $name_prop, true) > 0)) {
    $result = get_user_meta($user_id, $name_prop, true);
  } else {
    $result = get_post_meta(1, $name_prop, true);
  }
  return $result;
}


?>
