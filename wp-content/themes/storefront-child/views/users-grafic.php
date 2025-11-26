<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Use Font Awesome Free CDN modified-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script type='text/javascript' src='/wp-content/themes/storefront-child/js/highcharts.js'></script>
<style>#container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }</style>
<div id="primary" class="content-area container">
    <main id="main" class="site-main" role="main">

        <!-- Multi Cart Template -->
        <?php if (is_active_sidebar('multicart_widgett')) : ?>
            <div id="bmc-woocom-multisession-2" class="widget bmc-woocom-multisession-widget">
                <?php //dynamic_sidebar( 'multicart_widgett' ); ?>
            </div>
            <!-- #primary-sidebar -->
        <?php endif;

        $user_id = get_current_user_id();
        $meta_key = 'wc_multiple_shipping_addresses';

        global $wpdb;
        //        if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
        //            $addresses = maybe_unserialize($addresses);
        //        }

        $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

        ?>
        <h2>Reports</h2>

        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" href="#menu1">Single Users SQM</a>
            </li>
            <li>
                <a data-toggle="tab" href="#home">Total User SQM</a>
            </li>
            <li>
                <a data-toggle="tab" href="#battens">Total battens user</a>
            </li>
        </ul>

        <div class="tab-content">

            <div id="menu1" class="tab-pane fade in active">
                <!-- *********************************************************************************************
                    Start single user sqm*********************************************************************************************
                    -->
                <br>
                <form action="" method="POST" style="display:block;">
                    <div class="row">

                        <div class="col-sm-3 col-lg-3 form-grup">
                            <label for="q_Status">Select user</label>
                            <br>

                            <select id="q_status_order_id_eq" name="id_user" class="form-control">
                                <option value="">Please select...</option>
                                <?php
                                $customers = get_users(['role__in' => ['administrator', 'subscriber']]);
                                // Array of WP_User objects.
                                foreach ($customers as $user) {
                                    echo '<option value="' . $user->id . '">' . $user->display_name . '</option>';
                                } ?>
                            </select>

                        </div>


                        <!--                                                <div class="col-sm-3 col-lg-3 form-group">-->
                        <!--                                                    <label for="q_Order reference">Insert User id or email</label>-->
                        <!--                                                    <br>-->
                        <!--                                                    <input id="q_Order" name="customer_search" placeholder="Id or Email" type="text" value="" class="form-control">-->
                        <!--                                                </div>-->
                        <div class="col-sm-3 col-lg-1 form-group">
                            <br>
                            <input type="submit" name="search" value="Search" class="btn btn-info">
                        </div>
                        <div class="col-sm-3 col-lg-2 form-group">
                            <br>
                            <input type="submit" name="all_dates" value="All Dates" class="btn btn-info">
                        </div>

                        <div class="col-sm-3 col-lg-3 form-group">
                            <br>
                            <?php
                            $from = (isset($_POST['mishaDateFrom'])) ? $_POST['mishaDateFrom'] : '';
                            $to = (isset($_POST['mishaDateTo'])) ? $_POST['mishaDateTo'] : '';
                            ?>
                            <input type="text" name="mishaDateFrom" placeholder="Date From"
                                   value="<?php echo $from; ?>"/>
                            <input type="text" name="mishaDateTo" placeholder="Date To"
                                   value="<?php echo $to; ?>"/>
                            <script>
                                jQuery(function ($) {
                                    var from = $('input[name="mishaDateFrom"]'),
                                        to = $('input[name="mishaDateTo"]');

                                    $('input[name="mishaDateFrom"], input[name="mishaDateTo"]').datepicker({
                                        dateFormat: "yy-mm-dd",
                                        changeYear: true
                                    });
                                    // by default, the dates look like this "April 3, 2017"
                                    // I decided to make it 2017-04-03 with this parameter datepicker({dateFormat : "yy-mm-dd"});


                                    // the rest part of the script prevents from choosing incorrect date interval
                                    from.on('change', function () {
                                        to.datepicker('option', 'minDate', from.val());
                                    });

                                    to.on('change', function () {
                                        from.datepicker('option', 'maxDate', to.val());
                                    });

                                });
                            </script>
                            <style>
                                input[name="mishaDateFrom"], input[name="mishaDateTo"] {
                                    line-height: 28px;
                                    height: 28px;
                                    margin: 0;
                                    width: 125px;
                                }
                            </style>
                        </div>
                        <div class="col-sm-3 col-lg-3 form-group">
                            <br>
                            <input type="submit" name="interval" value="Interval Selection" class="btn btn-info">
                        </div>
                    </div>

                </form>

                <br>

                <hr>
                <h3>Single Users SQM</h3>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div id="container2"></div>
                    </div>
                    <!--div class="col-md-6">
                        <div id="container2"></div>
                    </div-->
                </div>

                <?php

                if (!empty($_POST['customer_search'])) {

                    if (is_numeric($_POST['customer_search'])) {

                        echo 'Customer ID: ' . $user_id = $_POST['customer_search'];

                    } else {

                        $user = get_user_by('email', $_POST['customer_search']);
                        echo 'Customer ID: ' . $user_id = $user->id;

                    }

                } elseif (!empty($_POST['id_user'])) {
                    echo 'Customer ID: ' . $user_id = $_POST['id_user'];
                }

                $user_info = get_userdata($user_id);
                echo ' - ';
                echo $user_name = $user_info->first_name . ' ' . $user_info->last_name;
                echo ' - ';
                echo $billing_company = $user_info->billing_company;

                //$users = get_users( array( 'fields' => array( 'ID' ) ) );
                $total = array();
                $totaly = array();
                $sum_total = array();
                $sum_totaly = array();
                $totaly_gbp = array();
                $totaly_dolar = array();
                $order_shipping_total = array();
                $total_order_shipping = array();
                $total2_order_shipping = array();
                $totaly_order_shipping = array();
                $years_orders = array();
                $total_train_calc = array();
                $total_truck_calc = array();

                $i = 1;

                //print_r($users);
                //foreach( $users as $user ){

                //print_r('User foreach id: '.$user->ID);
                $user = $user_info;

                if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles) || (in_array('emplimited', $user->roles) && !in_array('dealer', $user->roles))) {
                    // echo 'dealer simplu';
                    $users_orders = array($user_id);
                }
                if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
                    // get user company_parent id from meta
                    $deler_id = get_user_meta($user_id, 'company_parent', true);
                    // initiate users_orders array with logged user id
                    //$users_orders = array($user_id);

                    if (!empty($deler_id)) {
                        $users_orders = get_user_meta($deler_id, 'employees', true);
                        $users_orders[] = $deler_id;
                        $users_orders = array_reverse($users_orders);
                    }
                }
                if (in_array('dealer', $user->roles)) {
                    // echo 'dealer master';
                    $users_orders = get_user_meta($user_id, 'employees', true);
                    $users_orders[] = $user_id;
                    $users_orders = array_reverse($users_orders);

                }

                if (isset($_POST['interval'])) {
                    $args = array(
                        'customer_id' => $users_orders,
                        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
                        'date_query' => array(
                            'after' => date('Y-m-d', strtotime($from)),
                            'before' => date('Y-m-d', strtotime($to))
                        )
                    );
                } elseif (isset($_POST['all_dates'])) {
                    $args = array(
                        'customer_id' => $users_orders,
                        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision')
                    );
                } else {
                    $args = array(
                        'customer_id' => $users_orders,
                        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
                        'date_query' => array(
                            'after' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y') - 1)),
                        )
                    );
                }

                //                echo '<pre>';
                //                print_r($args);
                //                echo '</pre>';

                //echo 'User id: '.$user_id;
                $orders = wc_get_orders($args);

                //                echo '<pre>';
                //                print_r(count($orders));
                //                echo '</pre>';

                foreach ($orders as $order) {

                    $id_order = $order->get_id();

                    global $wpdb;
                    $tablename = $wpdb->prefix . 'custom_orders';
                    $myOrder = $wpdb->get_row("SELECT sqm, usd_price, gbp_price, subtotal_price, shipping_cost, createTime FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

                    $average_train = get_post_meta($id_order, 'average_train', true);
                    $average_truck = get_post_meta($id_order, 'average_truck', true);
                    if (empty($average_train)) $average_train = 0;
                    if (empty($average_truck)) $average_truck = 0;
                    $suma = $myOrder['gbp_price'];

                    $text = $myOrder['createTime'];
                    $texty = $myOrder['createTime'];
                    preg_match('/-(.*?)-/', $text, $match);
                    preg_match('/(.*?)-/', $text, $match_year);
                    $month = $match[1];
                    $year = $match_year[1];

                    preg_match('/(.*?)-/', $texty, $match2);
                    $pieces = explode("-", $match2[0]);
                    $pieces[0]; // piece1
                    $year2 = $pieces[0];

                    if (empty($years_orders)) {
                        $years_orders[] = $year;
                    } elseif (!in_array($year, $years_orders)) {
                        $years_orders[] = $year;
                    }


                    $order_transport_train = (float)$average_train * $property_total;
                    $total_train_calc[$year][$month] = $total_train_calc[$year][$month] + $order_transport_train;
                    $order_transport_truck = (float)$average_truck * $property_total;
                    $total_truck_calc[$year][$month] = $total_truck_calc[$year][$month] + $order_transport_truck;

                    $sum_totaly[$year2][$month] = $sum_totaly[$year2][$month] + $suma;

                    // USD price
                    $property_total_dolar = (float)$myOrder['usd_price'];
                    $totaly_dolar[$year][$month] = $totaly_dolar[$year][$month] + $property_total_dolar;

                    // GBP price
                    $property_total_gbp = (float)$myOrder['subtotal_price'];
                    $totaly_gbp[$year][$month] = $totaly_gbp[$year][$month] + $property_total_gbp;

                    // SQM
                    $property_total = (float)$myOrder['sqm'];
                    $totaly[$year][$month] = $totaly[$year][$month] + $property_total;

                    $order_shipping_total = $myOrder['shipping_cost'];

                    if (is_array($order_shipping_total) || empty($order_shipping_total) || $order_shipping_total == '') {
                        $order_shipping_total = 0;
                        $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
                    } elseif (!empty($order_shipping_total) || !is_array($order_shipping_total) || $order_shipping_total != '') {
                        $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
                    }
                    $property_total_gbp = $myOrder['subtotal_price'];
                    ?>

                    <?php
                    $i++;
                }

                //}
                // print_r($sum_totaly);

                $all_time_sqm = array();
                foreach ($totaly as $key => $value) {
                    foreach ($value as $month => $t) {
                        $all_time_sqm[$key . '-' . $month] = round($t, 0);
                    }
                }

                //                echo '<pre>';
                //                print_r(array_reverse($all_time_sqm));
                //                echo '</pre>';

                ?>
                <input type="hidden" name="totalcart2" value="<?php print_r($all_time_sqm); ?>">

                <?php
                //$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                $luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                $newlunian = array();
                $new2 = array();

                for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
                    $newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
                }

                //print_r($newlunian);
                $current_year = date("y");

                //print_r($new2);

                //echo '<pre>';
                //print_r($addresses[0]->meta_value);
                $userialize_data = unserialize($addresses[0]->meta_value);
                //print_r($userialize_data);
                //print_r($userialize_session['cart']);
                $unserialize_cart = $userialize_session['cart'];
                $cart_uns = unserialize($unserialize_cart);
                //print_r($cart_uns);
                $products_ids = array();
                // foreach($cart_uns as $key => $data){
                // 	$products_ids[] = $data['product_id'];

                // }
                //print_r($products_ids);
                //echo '</pre>';
                ?>

                <br>
                <div class="row">
                    <div class="col-sm-8">
                        <table class="table table-bordered table-striped">
                            <tbody>

                            <tr>
                                <th>Year</th>
                                <th>Month</th>
                                <th style="text-align:right">m2</th>
                                <th style="text-align:right">USD</th>
                                <th style="text-align:right">Total.Train $/m2</th>
                                <th style="text-align:right">Total.Truck €/m2</th>
                                <th></th>
                                <th style="text-align:right">GBP</th>
                                <th style="text-align:right">Delivery</th>
                            </tr>
                            <?php
                            $current_year = date("Y");
                            $yearss = array($current_year, ($current_year - 1));
                            $months = array('01' => 'Jan.', '02' => 'Feb.', '03' => 'Mar.', '04' => 'Apr.', '05' => 'May', '06' => 'Jun.', '07' => 'Jul.', '08' => 'Aug.', '09' => 'Sep.', '10' => 'Oct.', '11' => 'Nov.', '12' => 'Dec.');
                            //print_r($yearss);
                            $sum_tot_sqm = 0;
                            $sum_tot_dolar = 0;
                            $sum_tot_gbp = 0;
                            $sum_tot_prices = 0;
                            $sum_shipping = 0;
                            // foreach ($yearss as $year) {
                            foreach (array_reverse($years_orders) as $year) {
                                foreach ($sum_totaly as $key => $sum_ty) {

                                    foreach ($luni as $luna) {
                                        if ($key == $year) {
                                            if ($sum_ty[$luna]) {
                                                echo '<tr>
                                                        <th>' . $year . '</th>
                                                        <th>' . $months[$luna] . '</th>
                                                        <th style="text-align:right">' . number_format($totaly[$year][$luna], 2) . '</th>
                                                        <th style="text-align:right">' . number_format($totaly_dolar[$year][$luna], 2) . '</th>
                                                        <th style="text-align:right">' . number_format($total_train_calc[$year][$luna], 2) . '</th>
                                                        <th style="text-align:right">' . number_format($total_truck_calc[$year][$luna], 2) . '</th>
                                                        <th></th>
                                                        <th style="text-align:right">' . number_format($totaly_gbp[$year][$luna], 2) . '</th>
                                                        <th style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</th>
                                                    </tr>';
                                                $sum_tot_sqm = $sum_tot_sqm + $totaly[$year][$luna];
                                                $sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
                                                $sum_tot_dolar = $sum_tot_dolar + $totaly_dolar[$year][$luna];
                                                $sum_tot_gbp = $sum_tot_gbp + $totaly_gbp[$year][$luna];
                                                $sum_shipping = $sum_shipping + $total_order_shipping[$year][$luna];
                                            } else {
                                                echo '<tr>
                                                        <th>' . $year . '</th>
                                                        <th>' . $months[$luna] . '</th>
                                                        <th style="text-align:right"></th>
                                                        <th style="text-align:right"></th>
                                                        <th style="text-align:right"></th>
                                                        <th></th>
                                                        <th style="text-align:right"></th>
                                                        <th style="text-align:right"></th>
                                                        <th style="text-align:right"></th>
                                                    </tr>';
                                            }
                                        }
                                    }
                                }
                            }
                            ?>

                            <tr>
                                <td colspan="2" style="text-align:right">
                                    <strong>Totals</strong>
                                </td>
                                <td style="text-align:right">
                                    <?php echo number_format($sum_tot_sqm, 2); ?>
                                </td>
                                <td style="text-align:right">
                                    <?php echo number_format($sum_tot_dolar, 2); ?>
                                </td>
                                <td style="text-align:right"></td>
                                <td style="text-align:right"></td>
                                <td></td>
                                <td style="text-align:right">
                                    <?php echo number_format($sum_tot_gbp, 2); ?>
                                </td>
                                <td style="text-align:right">
                                    <?php echo number_format($sum_shipping, 2); ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>

                    jQuery(document).ready(function () {
                        var sqm2 = jQuery('input[name="totalcart2"]').val();
                        var js_array2 = [<?php echo '"' . implode('","', array_reverse($all_time_sqm)) . '"' ?>];
                        console.log(sqm2);
                        console.log('js_array2 : ' + js_array2);
                        var ints2 = js_array2.map(parseFloat);

                        Highcharts.chart('container2', {
                            "chart": {
                                "type": 'column'
                            },
                            "title": {
                                "text": null
                            },
                            "legend": {
                                "align": "right",
                                "verticalAlign": "top",
                                "y": 75,
                                "x": -50,
                                "layout": "vertical"
                            },
                            "xAxis": {
                                "categories": <?php
                                foreach ($all_time_sqm as $year_month => $sqm) {
                                    $month_year[] = date('M y', strtotime($year_month));
                                }
                                echo $js_array = json_encode(array_reverse($month_year));
                                ?>
                            },
                            "yAxis": [{
                                "title": {
                                    "text": "m2",
                                    "margin": 70
                                },
                                "min": 0
                            }],
                            "tooltip": {
                                "enabled": true,
                                "valueDecimals": 0,
                                "valueSuffix": " sqm"
                            },

                            "plotOptions": {
                                "column": {
                                    "dataLabels": {
                                        "enabled": true,
                                        "valueDecimals": 0
                                    },
                                    enableMouseTracking: true
                                }
                            },

                            "credits": {
                                "enabled": true
                            },

                            "subtitle": {},
                            "series": [{
                                "name": "m2",
                                "yAxis": 0,
                                "data": ints2
                            }]

                        });
                    });
                </script>

                <!-- *********************************************************************************************
                    End single user sqm
 ******************************************************************************************
 -->

            </div>
            <div id="home" class="tab-pane fade">
                <?php
                include_once __DIR__ . '/users-grafic-includes/total-user-sqm.php';
                ?>
            </div>
            <div id="battens" class="tab-pane fade">
                <?php
                include_once __DIR__ . '/users-grafic-includes/total-prod-user-period.php';
                ?>
            </div>
        </div>
    </main>
    <!-- #main -->
</div>
<!-- #primary -->

<?php
//
//    global $wpdb;
//    $tablename = $wpdb->prefix . 'custom_orders';
//    $myOrder = $wpdb->get_results("SELECT sqm, usd_price, gbp_price, subtotal_price, shipping_cost, createTime FROM $tablename WHERE status <> 'cancelled'");
//
//
//    $total = array();
//    $totaly = array();
//    $sum_total = array();
//    $sum_totaly = array();
//    $total_order_shipping = array();
//    $total2_order_shipping = array();
//    $totaly_order_shipping = array();
//    $total_dolar = array();
//    $total_gbp = array();
//
//    foreach ($myOrder as $ord) {
//
//        $text = $ord->createTime;
//        $texty = $ord->createTime;
//        preg_match('/-(.*?)-/', $text, $match);
//        preg_match('/(.*?)-/', $text, $match_year);
//        $month = $match[1];
//        $year = $match_year[1];
//
//
//        $suma = $ord->gbp_price;
//        $sum_total[$year][$month] = $sum_total[$year][$month] + $suma;
//        $sum_totaly[$year][$month] = $sum_totaly[$year][$month] + $suma;
//
//        // USD price
//        $property_total_dolar = floatval($ord->usd_price);
//        $total_dolar[$year][$month] = $total_dolar[$year][$month] + $property_total_dolar;
//        $total2_dolar[$year][$month] = $total2_dolar[$year][$month] + $property_total_dolar;
//        $totaly_dolar[$year][$month] = $totaly_dolar[$year][$month] + $property_total_dolar;
//
//        // GBP price
//        $property_total_gbp = $ord->subtotal_price;
//        $total_gbp[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp;
//
//        // SQM
//        $property_total = floatval($ord->sqm);
//        $total[$year][$month] = $total[$year][$month] + $property_total;
//        $total2[$year][$month] = $total2[$year][$month] + $property_total;
//        $totaly[$year][$month] = $totaly[$year][$month] + $property_total;
//
//        $order_shipping_total = $ord->shipping_cost;
//
//        if (is_array($order_shipping_total) || empty($order_shipping_total) || $order_shipping_total == '') {
//            $order_shipping_total = 0;
//            $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
//            $total2_order_shipping[$year][$month] = $total2_order_shipping[$year][$month] + $order_shipping_total;
//            $totaly_order_shipping[$year][$month] = $totaly_order_shipping[$year][$month] + $order_shipping_total;
//        } elseif (!empty($order_shipping_total) || !is_array($order_shipping_total) || $order_shipping_total != '') {
//            $total_order_shipping[$year][$month] = $total_order_shipping[$year][$month] + $order_shipping_total;
//            $total2_order_shipping[$year][$month] = $total2_order_shipping[$year][$month] + $order_shipping_total;
//            $totaly_order_shipping[$year][$month] = $totaly_order_shipping[$year][$month] + $order_shipping_total;
//        }
//
//    }


if (1 == get_current_user_id()) {
//
//    $args = array(
//        'customer_id' => 18,
//        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
//        'date_query' => array(
//            'after' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y') - 1)),
//        )
//    );
//
//    $orders = wc_get_orders($args);
//
//    echo '<pre>';
//    print_r(count($orders));
//    echo '</pre>';
//
//    foreach ($orders as $order) {
//        $order_id = $order->get_id();
////    $order = wc_get_order($order_id);
//        $items = $order->get_items();
//        $totaly_sqm = 0;
//        foreach ($items as $item_id => $item_data) {
//            $quantity = get_post_meta($item_data['product_id'], 'quantity', true);
//            $sqm = get_post_meta($item_data['product_id'], 'property_total', true);
//
//            if ($sqm > 0) {
//                //$prod_qty = $item_data['quantity'];
//                $product_id = $item_data['product_id'];
//                $prod_qty = get_post_meta($product_id, 'quantity', true);
//
//                $sqm = get_post_meta($product_id, 'property_total', true);
//                $property_total_sqm = floatval($sqm) * floatval($prod_qty);
//                $totaly_sqm = $totaly_sqm + $property_total_sqm;
//            }
//        }
//
//
//        global $wpdb;
//
//        $idOrder = $order_id;
//        $orderExist = $wpdb->get_var("SELECT COUNT(*) FROM `wp_custom_orders` WHERE `idOrder` = $idOrder");
//
//        if ($orderExist != 0) {
//            /*
//             * Update table
//             */
//            $tablename = $wpdb->prefix . 'custom_orders';
//
//            $data = array(
//                'sqm' => $totaly_sqm,
//            );
//            $where = array(
//                "idOrder" => $order_id
//            );
//            $wpdb->update($tablename, $data, $where);
//        }
//    }
}



/**
 * Fetch orders within the last year and get their IDs.
 *
 * @return array List of order IDs.
 */
function fetch_order_ids() {
	return get_posts(array(
		'post_type' => 'shop_order',
		'posts_per_page' => -1,
		'post_status' => array(
			'wc-on-hold', 'wc-completed', 'wc-pending',
			'wc-processing', 'wc-inproduction', 'wc-paid',
			'wc-waiting', 'wc-revised', 'wc-inrevision'
		),
		'date_query' => array(
			'after' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y') - 1)),
		),
		'fields' => 'ids'
	));
}

/**
 * Fetch custom order data from the database.
 *
 * @param array $order_ids List of order IDs.
 * @return array Associative array of order data.
 */
function fetch_custom_order_data_gr($order_ids) {
	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';
	$order_data = array();

	$results = $wpdb->get_results(
		"SELECT idOrder, sqm, usd_price FROM $tablename WHERE idOrder IN (" . implode(',', array_map('intval', $order_ids)) . ")",
		ARRAY_A
	);

	foreach ($results as $line) {
		$order_data[$line['idOrder']] = array(
			'sqm' => (float)$line['sqm'],
			'usd_price' => (float)$line['usd_price']
		);
	}

	return $order_data;
}

/**
 * Initialize data arrays for a given year.
 *
 * @param int $year Year to initialize data for.
 */
function initialize_yearly_data_gr(&$data_arrays, $year) {
	$months_sum = array_fill_keys(range(1, 12), 0);

	foreach ($data_arrays as &$array) {
		$array[$year] = $months_sum;
	}
}
