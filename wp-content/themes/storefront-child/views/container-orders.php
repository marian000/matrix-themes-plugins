<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<?php
$container_orders = get_post_meta(get_the_id(), 'container_orders', true);
//print_r($container_orders);
arsort($container_orders);
//print_r($container_orders);
$csv_orders_array = get_post_meta(get_the_id(), 'csv_orders_array', true);
$csv_orders_array_cartons = get_post_meta(get_the_id(), 'csv_orders_array_cartons', true);
$csv_orders_array_repair = get_post_meta(get_the_id(), 'csv_orders_array_repair', true);
$csv_orders_array_cartons_repair = get_post_meta(get_the_id(), 'csv_orders_array_cartons_repair', true);

$csv_orders_array_frames_repair = get_post_meta(get_the_id(), 'csv_orders_array_frames_repair', true);
$csv_orders_array_panels_repair = get_post_meta(get_the_id(), 'csv_orders_array_panels_repair', true);

//echo '<pre>';
//print_r($csv_orders_array_frames_repair);
//echo '</pre>';
//echo '<pre>';
//print_r($csv_orders_array_panels_repair);
//echo '</pre>';
?>
<div class="clearfix"></div>
<div class="row container-order">
    <div class="col-md-12">
        <table id="example"
               style="width:100%; display:table;"
               class="table table-striped">
            <thead>
            <tr>
                <th>
                    Order
                </th>
                <th>
                    Order
                    Ref
                </th>
                <th>
                    Date
                </th>
                <th>
                    Status
                </th>
                <th>
                    SQM
                </th>
                <th>
                    CSV SQM
                </th>
                <th>
                    CSV CARTONS
                </th>
                <th>
                    Diff SQM
                </th>
                <th>
                    Total
                    $
                </th>
                <?php if (!current_user_can('china_admin')) { ?>
                    <th>
                        Total
                    </th>
                <?php } ?>

            </tr>
            </thead>
            <tbody>

            <?php
            $sum_dolar_conatainer = 0;
            $sum_conatainer = 0;
            $csv_matrix_orders = array();
            $repair_matrix_orders = array();
            if ($container_orders) {
                // print_r($container_orders);
                foreach ($container_orders as $order_id) {
                    // if csv order is not in matrix then create an array with orders outside matrix
//                    print_r($order_id);
//                    print_r(get_post_type($order_id));
                    if (get_post_type($order_id) == 'order_repair') {
                        $order_original = get_post_meta($order_id, 'order-id-original', true);
                        $order = wc_get_order($order_original);
                        $repair_matrix_orders[] = $order_id;
                        // $order_data = $order->get_data();
                        if (!empty($csv_orders_array) && !array_key_exists($order_original, $csv_orders_array)) {
                            $csv_matrix_orders[$order_original] = '0';
                        }
                    } else {
                        $order = wc_get_order($order_id);
//                        if(empty($order)){
//                            echo 'id order negasit: '.$order_id. ' <br>';
//                        }
                        // $order_data = $order->get_data();
                        if (!empty($csv_orders_array) && !array_key_exists($order_id, $csv_orders_array)) {
                            $csv_matrix_orders[$order_id] = '0';
                        }

                        // $order = wc_get_order($order);
                        // $order_data = $order->get_data();
                        $full_payment = 0;
                        $user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
                        //echo 'USER ID '.$user_id;

                        $i = 0;
                        $atributes = get_post_meta(1, 'attributes_array', true);
                        if ($order) {
                            $items = $order->get_items();

                            $nr_code_prod = array();

                            foreach ($items as $item_id => $item_data) {
                                $i++;
                                // $product = $item_data->get_product();
                                $product_id = $item_data['product_id'];

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
                            ?>
                            <tr>
                                <td>
                                    <a href="/wp-admin/post.php?post=<?php echo $order_id; ?>&action=edit">
                                        <?php if (get_post_type($order_id) == 'order_repair') { ?>
                                        LFR<?php echo $order->get_order_number(); ?></a>
                                    <?php }
                                    else { ?>
                                        LF0<?php echo $order->get_order_number(); ?></a>
                                    <?php } ?>
                                    <!-- LF0<?php echo $order->get_order_number(); ?></a> -->
                                </td>
                                <td><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>
                                <td><?php echo $order->get_date_modified()->date('Y-m-d H:i:s'); ?></td>
                                <td><?php echo $order->get_status(); ?></td>
                                <td><?php $sqm_total = 0;
                                    foreach ($items as $item_id => $item_data) {
                                        $product_id = $item_data['product_id'];
                                        $property_total = get_post_meta($product_id, 'property_total', true);
                                        $sqm_total = $sqm_total + $property_total * $item_data['quantity'];
                                    }
                                    echo number_format($sqm_total, 2);

                                    $diff_sqm = number_format($sqm_total, 2) - $csv_orders_array[$order->get_id()];

                                    $diff_class = '';
                                    if ($diff_sqm < 0) {
                                        $diff_class = 'negative';
                                    } elseif ($diff_sqm > 0) {
                                        $diff_class = 'positive';
                                    } else {
                                        $diff_class = 'neutral';
                                    } ?>
                                </td>
                                <td><?php echo $csv_orders_array[$order->get_id()]; ?></td>
                                <td>
                                    <?php echo $csv_orders_array_cartons[$order->get_id()]; ?>
                                </td>
                                <td class="csv-diff-sum <?php echo $diff_class; ?>"><?php
                                    echo number_format($diff_sqm, 2);
                                    ?>
                                </td>
                                <td>
                                    $<?php $sum_total = 0;
                                    foreach ($items as $item_id => $item_data) {
                                        $product_id = $item_data['product_id'];
                                        $qty = $item_data['quantity'];
                                        $dolar_price = get_post_meta($product_id, 'dolar_price', true);

                                        $sum_total = $sum_total + $dolar_price * $qty;
                                    }
                                    $sum_dolar_conatainer = $sum_dolar_conatainer + $sum_total;
                                    echo number_format($sum_total, 2); ?>
                                </td>
                                <?php if (!current_user_can('china_admin')) { ?>
                                    <td>
                                        £<?php echo number_format($order->get_subtotal(), 2); ?></td>
                                    <?php
                                }
                                $sum_conatainer = $sum_conatainer + $order->get_subtotal();
                                ?>
                            </tr>

                            <?php
                        }
                    }

                }

                foreach ($repair_matrix_orders as $index => $order_id) {

                    // if csv order is not in matrix then create an array with orders outside matrix

                    if (get_post_type($order_id) == 'order_repair') {
                        $order_original = get_post_meta($order_id, 'order-id-original', true);
                        $order = wc_get_order($order_original);
                        $repair_matrix_orders[] = $order_id;
                        // $order_data = $order->get_data();
                        if (!empty($csv_orders_array) && !array_key_exists($order_original, $csv_orders_array_repair)) {
                            $csv_matrix_orders[$order_original] = '0';

                        }

                        // $order = wc_get_order($order);
                        // $order_data = $order->get_data();
                        $full_payment = 0;
                        $user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
                        //echo 'USER ID '.$user_id;

                        $i = 0;
                        $atributes = get_post_meta(1, 'attributes_array', true);
                        $items = $order->get_items();

                        $nr_code_prod = array();
                        foreach ($items as $item_id => $item_data) {
                            $i++;
                            // $product = $item_data->get_product();
                            $product_id = $item_data['product_id'];

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
                        ?>
                        <tr>
                            <td>
                                <a href="/wp-admin/post.php?post=<?php echo $order_id; ?>&action=edit">
                                    <?php if (get_post_type($order_id) == 'order_repair') { ?>
                                    LFR<?php echo $order->get_order_number(); ?></a>
                                <?php }
                                else { ?>
                                    LF0<?php echo $order->get_order_number(); ?></a>
                                <?php } ?>
                                <!-- LF0<?php echo $order->get_order_number(); ?></a> -->
                            </td>
                            <td><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>
                            <td><?php echo $order->get_date_modified()->date('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $order->get_status(); ?></td>
                            <td><?php $sqm_total = 0;
                                foreach ($items as $item_id => $item_data) {
                                    $product_id = $item_data['product_id'];
                                    $property_total = get_post_meta($product_id, 'property_total', true);
                                    $sqm_total = $sqm_total + $property_total * $item_data['quantity'];
                                }
                                echo number_format($sqm_total, 2);

                                $diff_sqm = number_format($sqm_total, 2) - $csv_orders_array_repair[$order->get_id()];

                                $diff_class = '';
                                if ($diff_sqm < 0) {
                                    $diff_class = 'negative';
                                } elseif ($diff_sqm > 0) {
                                    $diff_class = 'positive';
                                } else {
                                    $diff_class = 'neutral';
                                } ?>
                            </td>
                            <td><?php echo $csv_orders_array_repair[$order->get_id()]; ?></td>
                            <td>
                                <?php echo $csv_orders_array_cartons_repair[$order->get_id()]; ?>
                            </td>
                            <td class="csv-diff-sum <?php echo $diff_class; ?>"><?php
                                echo number_format($diff_sqm, 2);
                                ?>
                            </td>
                            <td>
                                $<?php $sum_total = 0;
                                foreach ($items as $item_id => $item_data) {
                                    $product_id = $item_data['product_id'];
                                    $qty = $item_data['quantity'];
                                    $dolar_price = get_post_meta($product_id, 'dolar_price', true);

                                    $sum_total = $sum_total + $dolar_price * $qty;
                                }
                                $sum_dolar_conatainer = $sum_dolar_conatainer + $sum_total;
                                echo number_format($sum_total, 2); ?>
                            </td>
                            <?php if (!current_user_can('china_admin')) { ?>
                                <td>
                                    £<?php echo number_format($order->get_subtotal(), 2); ?></td>
                                <?php
                            }
                            $sum_conatainer = $sum_conatainer + $order->get_subtotal();
                            ?>
                        </tr>

                        <?php

                    } else {
                        $order = wc_get_order($order_id);
                        // $order_data = $order->get_data();
                        if (!empty($csv_orders_array) && !array_key_exists($order_id, $csv_orders_array)) {
                            $csv_matrix_orders[$order_id] = '0';
                        }

                    }

                }

                /*
                  * Show orders extra from csv
                  */

                $csv_matrix_orders_plus = array();
                //  print_r($csv_orders_array);
                foreach ($csv_orders_array as $order => $val) {

                    $order_number = get_post_meta($order, '_order_number', true);
                    if (!empty($csv_orders_array) && !in_array($order, $container_orders)) {
                        if ($order != 0 || !empty($order)) {
                            $csv_matrix_orders_plus[$order] = '0';
                            // ordere adaugate de chinezi in plus fata de cele selectate in matrix
                            // echo '<br> CSV order not found in container ' . $order . ' - LF0' . $order_number;
                        }
                    }
                }

                foreach ($csv_orders_array_repair as $order => $val) {

                    global $wpdb;
                    $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_key = 'order-id-original' AND meta_value = $order", ARRAY_A);
                    $repairs_array = array();
                    if ($results) {
                        foreach ($results as $repair) {
                            $order_id = $repair['post_id'];
                            $repairs_array[] = $order_id;
                        }
                        $same_ids = array_intersect($repairs_array, $container_orders);
                        if (empty($same_ids)) {
                            $order_number = get_post_meta($order, '_order_number', true);
                            if (!empty($csv_orders_array) && !in_array($order_id, $container_orders)) {
                                $csv_matrix_orders_plus[$order] = '0';
                                echo '<br> CSV order R not found in container ' . $order . ' - LFR' . $order_number . ' ';
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

                // print_r($csv_matrix_orders_plus);
                foreach ($csv_matrix_orders_plus as $order => $val) {

                    global $wpdb;
                    $results = $wpdb->get_results("select post_id from $wpdb->postmeta where meta_key = 'order-id-original' AND meta_value = $order", ARRAY_A);

                    $order = wc_get_order($order);
                    // $order_data = $order->get_data();
                    $full_payment = 0;
                    //echo 'USER ID '.$user_id;

                    $i = 0;
                    $atributes = get_post_meta(1, 'attributes_array', true);
                    $items = $order->get_items();

                    $nr_code_prod = array();
                    foreach ($items as $item_id => $item_data) {
                        $i++;
                        // $product = $item_data->get_product();
                        $product_id = $item_data['product_id'];

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
                    ?>
                    <tr style="background-color: #7bdcb5">
                        <td>
                            <a href="/wp-admin/post.php?post=<?php echo $order_id; ?>&action=edit">
                                <?php if (!empty($results)) { ?>
                                LFR<?php echo $order->get_order_number(); ?></a>
                            <?php }
                            else { ?>
                                LF0<?php echo $order->get_order_number(); ?></a>
                            <?php } ?>
                            <!-- LF0<?php echo $order->get_order_number(); ?></a> -->
                        </td>
                        <td><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>
                        <td><?php echo $order->get_date_modified()->date('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $order->get_status(); ?></td>
                        <td><?php $sqm_total = 0;
                            foreach ($items as $item_id => $item_data) {
                                $product_id = $item_data['product_id'];
                                $property_total = get_post_meta($product_id, 'property_total', true);
                                $sqm_total = $sqm_total + $property_total * $item_data['quantity'];
                            }
                            echo number_format($sqm_total, 2);

                            $diff_sqm = number_format($sqm_total, 2) - $csv_orders_array[$order->get_id()];

                            $diff_class = '';
                            if ($diff_sqm < 0) {
                                $diff_class = 'negative';
                            } elseif ($diff_sqm > 0) {
                                $diff_class = 'positive';
                            } else {
                                $diff_class = 'neutral';
                            } ?>
                        </td>
                        <td><?php echo $csv_orders_array[$order->get_id()]; ?></td>
                        <td><?php echo $csv_orders_array_cartons[$order->get_id()]; ?></td>
                        <td class="csv-diff-sum <?php echo $diff_class; ?>"><?php
                            echo number_format($diff_sqm, 2);
                            ?>
                        </td>
                        <td>
                            $<?php $sum_total = 0;
                            foreach ($items as $item_id => $item_data) {
                                $product_id = $item_data['product_id'];
                                $qty = $item_data['quantity'];
                                $dolar_price = get_post_meta($product_id, 'dolar_price', true);

                                $sum_total = $sum_total + $dolar_price * $qty;
                            }
                            $sum_dolar_conatainer = $sum_dolar_conatainer + $sum_total;
                            echo number_format($sum_total, 2); ?>
                        </td>
                        <?php if (!current_user_can('china_admin')) { ?>
                            <td>
                                £<?php echo number_format($order->get_subtotal(), 2); ?></td>
                            <?php
                        }
                        $sum_conatainer = $sum_conatainer + $order->get_subtotal();
                        ?>
                    </tr>

                    <?php

                }

            } ?>

            </tbody>
            <tfoot style="background-color:yellow;">
            <th>
                <!--                    Order-->
            </th>
            <th>
                <!--                    Order-->
                <!--                    Ref-->
            </th>
            <th>
                <!--                    Date-->
            </th>
            <th>
                <!--                    Status-->
            </th>
            <th>
                <!--                    SQM-->
            </th>
            <th>
                <!--                    CSV SQM-->
            </th>
            <th>
                <!--                    CSV CARTONS-->
            </th>
            <th>
                <!--                    Diff SQM-->
            </th>
            <th>
                <?php echo '$' . number_format($sum_dolar_conatainer, 2); ?>
            </th>
            <?php if (!current_user_can('china_admin')) { ?>
                <th>
                    <?php echo '£' . number_format($sum_conatainer, 2); ?>
                </th>
            <?php } ?>

            </tfoot>
        </table>
        <hr>
    </div>
</div>

<div class="clearfix"></div>

<style>
    .row.container-order {
        margin-bottom: 50px;
    }
</style>
