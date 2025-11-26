<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Reports template
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <!-- Multi Cart Template -->
			<?php if (is_active_sidebar('multicart_widgett')) : ?>
                <div id="bmc-woocom-multisession-2" class="widget bmc-woocom-multisession-widget">
					<?php //dynamic_sidebar( 'multicart_widgett' ); ?>
                </div>
                <!-- #primary-sidebar -->
			<?php endif; ?>

			<?php
			if (is_user_logged_in()) {

				$user_id = get_current_user_id();
				$meta_key = 'wc_multiple_shipping_addresses';

				global $wpdb;
				if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
					$addresses = maybe_unserialize($addresses);
				}

				$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

				?>
                <h2>Reports</h2>

                <br>

                <div class="alert alert-warning">
					<?php
					// Get the saved notification message
					$notification_message = get_post_meta(1, 'notification_users_message', true);
					echo wp_kses_post($notification_message); // Safely display the HTML content
					?>
                </div>


                <br>
                <br>

                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div id="container"></div>
                    </div>
                    <div class="col-md-6">
                        <div id="container2"></div>
                    </div>
                </div>

				<?php

				$user_info = get_userdata(get_current_user_id());
				//$users = get_users( array( 'fields' => array( 'ID' ) ) );
				$total = array();
				$total_gbp = array();
				$totaly = array();
				$sum_total = array();
				$sum_totaly = array();
				$order_shipping_total = array();
				$total_order_shipping = array();
				$total2_order_shipping = array();
				$totaly_order_shipping = array();
				$years_orders = array();

				$i = 1;

				//print_r($users);
				//foreach( $users as $user ){

				//print_r('User foreach id: '.$user->ID);
				$user = $user_info;

				if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles) || in_array('emplimited', $user->roles) && !in_array('dealer', $user->roles)) {
					// echo 'dealer simplu';
					$users_orders = array($user_id);
				}
				if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
					// get user company_parent id from meta
					$deler_id = get_user_meta($user_id, 'company_parent', true);
					// initiate users_orders array with logged user id
					//$users_orders = array($user_id);

					if (!empty($deler_id)) {
						$users_orders = array();
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

				$args = array(
				  'customer_id' => $users_orders,
				  'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
				  'date_query' => array(
					'after' => date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y') - 1)),
				  ),
				);

				$orders = wc_get_orders($args);

				$months_sum = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

				global $wpdb;
				$tablename = $wpdb->prefix . 'custom_orders';
//$sum_total[date('Y')] = $months_sum;
				$myOrders = array();
				if (!empty($orders)) {
					$ids = implode(',', array_map('intval', $orders));
					$myResult = $wpdb->get_results(
					  "SELECT idOrder, sqm, usd_price FROM {$wpdb->prefix}custom_orders WHERE idOrder IN ($ids)",
					  ARRAY_A
					);
					foreach ($myResult as $line) {
						$myOrders[$line['idOrder']] = [
						  'sqm' => $line['sqm'],
						  'usd_price' => $line['usd_price'],
						];
					}
				} else {
					// Handle the case where there are no orders
					$myResult = [];
				}

				foreach ($orders as $order) {

					$id_order = $order->get_id();

					global $wpdb;
					$tablename = $wpdb->prefix . 'custom_orders';
					$myOrder = $wpdb->get_row("SELECT sqm, usd_price, gbp_price, subtotal_price, shipping_cost, createTime FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

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

					// $suma = $myOrder['gbp_price'];
					$suma = floatval($order->get_total());
					$sum_total[$year][$month] = $sum_total[$year][$month] + $suma;
					$sum_totaly[$year2][$month] = $sum_totaly[$year2][$month] + $suma;

					// GBP price
					$property_total_gbp = floatval($myOrder['subtotal_price']);
					$total_gbp[$year][$month] = $total_gbp[$year][$month] + $property_total_gbp;

					// SQM
					$property_total = floatval($myOrder['sqm']);
					$total[$year][$month] = $total[$year][$month] + $property_total;

					?>

					<?php
					$i++;
				}

				//$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
				$luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
				$newlunian = array();
				$new2 = array();
				$news2 = array();

				for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
					$newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
				}

				//print_r($newlunian);
				$current_year = date("y");

				foreach ($newlunian as $k => $anluna) {
					$pieces = explode("-", $anluna);
					$an = $pieces[0]; // piece1
					$luna = $pieces[1]; // piece2
					foreach ($total as $key => $value) {
						if ($key == $an) {
							foreach ($total[$key] as $m => $t) {
								if ($luna == $m) {
									$new2[$luna] = round($t, 0);
									//print_r($t);
								} else {
									if ($new2[$luna] != 0) {

									} else {
										$new2[$luna] = 0;
										//print_r($new[$luna]);
									}
								}
							}
						} else {
							if (empty($new2[$luna])) {
								$new2[$luna] = 0;
							} else {
							}
						}
					}

					foreach ($total_gbp as $key => $value) {
						if ($key == $an) {

							foreach ($total_gbp[$key] as $m => $t) {
								if ($luna == $m) {
									$news2[$luna] = round($t, 0);
									//print_r($t);
								} else {
									if ($news2[$luna] != 0) {

									} else {
										$news2[$luna] = 0;
										//print_r($new[$luna]);
									}
								}
							}
						} else {
							if (empty($news2[$luna])) {
								$news2[$luna] = 0;
							} else {
							}
						}
					}
				}
			}
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
                            <th style="text-align:right">Amount</th>
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
						foreach ($sum_total as $year => $sum_ty) {
							foreach ($luni as $luna) {
								if ($sum_ty[$luna]) {
									echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right">' . number_format($total[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</th>
                        </tr>';
									$sum_tot_sqm = $sum_tot_sqm + $total[$year][$luna];
									$sum_tot_gbp = $sum_tot_gbp + $total_gbp[$year][$luna];
									$sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
								} else {
									echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                        </tr>';
								}
							}
						}
						?>

                        <tr>
                            <td colspan="2" style="text-align:right"><strong>Totals</strong></td>
                            <td style="text-align:right"><?php echo number_format($sum_tot_sqm, 2); ?></td>
                            <td style="text-align: right">£<?php echo number_format($sum_tot_prices, 2); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </main>
        <!-- #main -->
    </div>
    <!-- #primary -->

    <script>
        jQuery(document).ready(function () {

            var sqm = jQuery('input[name="totalcart"]').val();
            var js_array = [<?php echo '"' . implode('","', $new2) . '"' ?>];
            var js_array_sum = [<?php echo '"' . implode('","', $news2) . '"' ?>];
            // console.log(sqm);
            console.log(js_array);
            var ints = js_array.map(parseFloat);
            var ints_sum = js_array_sum.map(parseFloat);

            Highcharts.chart('container', {
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
                    "categories": ["<?php echo date('M y', mktime(0, 0, 0, date('m') - 11, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 10, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 9, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 8, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 7, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 6, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 5, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 4, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 3, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 2, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 1, 1, date('Y'))); ?>", "<?php echo date('M y'); ?>"]
                },
                "yAxis": [{
                    "title": {
                        "text": "m2",
                        "margin": 70
                    },
                    "min": 0
                }],
                "tooltip": {
                    "enabled": true
                },
                "credits": {
                    "enabled": false
                },

                "subtitle": {},
                "series": [{
                    "name": "m2",
                    "yAxis": 0,
                    "data": ints
                }]

            });


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
                    "categories": ["<?php echo date('M y', mktime(0, 0, 0, date('m') - 11, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 10, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 9, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 8, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 7, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 6, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 5, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 4, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 3, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 2, 1, date('Y'))); ?>", "<?php echo date('M y', mktime(0, 0, 0, date('m') - 1, 1, date('Y'))); ?>", "<?php echo date('M y'); ?>"]
                },
                "yAxis": [{
                    "title": {
                        "text": "Total",
                        "margin": 70
                    },
                    "min": 0
                }],
                "tooltip": {
                    "enabled": true
                },
                "credits": {
                    "enabled": false
                },

                "subtitle": {},
                "series": [{
                    "name": "Total",
                    "yAxis": 0,
                    "data": ints_sum
                }]

            });

        });
    </script>


<?php
get_footer();