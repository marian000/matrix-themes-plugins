<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Order History
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

				$customer_order = '';
				if (isset($_GET['deliveries'])) {
					$string = $_GET['customer_order'];
					$pre = 'LF0';

					if (strpos($string, $pre) !== false) {
						$cust_order = $string;
						$customer_order = substr($cust_order, 3);
					} else {
						$customer_order = $_GET['customer_order'];
					}
				}
				$user_id = get_current_user_id();
				$meta_key = 'wc_multiple_shipping_addresses';

				global $wpdb;
				if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
					$addresses = maybe_unserialize($addresses);
				}

				$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

				//print_r($_GET);

				?>
        <h2>Orders History</h2>

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

        <form action="" method="GET">
          <div class="row">
            <div class="col-sm-3 col-lg-3 form-group">
              <label for="q_Deliveries start">Deliveries start</label>
              <br>
              <input id="q_container_contents_container_delivery_week_at_casted_eq" name="deliveries"
                     type="text" class="form-control">
            </div>
            <div class="col-sm-3 col-lg-3 form-group">
              <label for="q_Status">Status</label>
              <br>
              <select id="q_status_order_id_eq" name="status_order" class="form-control">
                <option value="">Please select...</option>
                <option value="processing">Ordered - Pending</option>
                <option value="on-hold">on-hold</option>
                <option value="completed">completed</option>
                <option value="refunded">refunded</option>
                <option value="failed">failed</option>
              </select>
            </div>
            <div class="col-sm-3 col-lg-3 form-group">
              <label for="q_Order reference">Order reference</label>
              <br>
              <input data-autocomplete="/orders/autocomplete_order_customer_order_reference" id="search"
                     name="customer_order" placeholder="SEARCH"
                     size="15" type="text" value="" autocomplete="off" class="form-control">
            </div>
            <div class="col-sm-3 col-lg-3 form-group">
              <label for=""></label>
              <br>
              <input type="submit" name="submit" value="Search" class="btn btn-info">

            </div>
          </div>

        </form>
        <a href="/order-history" class="btn btn-info">Reset Search</a>
        <br>
        <br>

        <table class="table">
        <thead style="background-color: #f2dede;">
        <th></th>
        <th>Order ID</th>
        <th>Date</th>
        <th>Deliveries Start</th>
        <th>Total</th>
        <th>Status</th>
        <th>Last Update</th>
        <th></th>
        </thead>
        <tbody>
				<?php

				if (!empty($_GET['status_order'])) {
					$status = $_GET['status_order'];
				} else {
					$status = array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision');
				}

				if (isset($_GET['customer_order'])) {

					$string = $_GET['customer_order'];
					$pre = 'LF0';

					if (strpos($string, $pre) !== false) {
						$cust_order = $string;
						$customer_order = substr($cust_order, 3);
					} else {
						$customer_order = $_GET['customer_order'];
					}

					if (is_numeric($_GET['customer_order'])) {
						$articles = get_posts(
							array(
								'numberposts' => -1,
								'post_status' => $status,
								'post_type' => 'shop_order',
								'meta_query' => array(
									array(
										'key' => '_order_number',
										'value' => $customer_order,
										'compare' => 'LIKE',
									),
								),
							)
						);
					} elseif (strpos($string, $pre) !== false) {
						$articles = get_posts(
							array(
								'numberposts' => -1,
								'post_status' => $status,
								'post_type' => 'shop_order',
								'meta_query' => array(
									array(
										'key' => '_order_number',
										'value' => $customer_order,
										'compare' => 'LIKE',
									),
								),
							)
						);
					} else {
						$articles = get_posts(
							array(
								'numberposts' => -1,
								'post_status' => $status,
								'post_type' => 'shop_order',
								'meta_query' => array(
									array(
										'key' => 'cart_name',
										'value' => $_GET['customer_order'],
										'compare' => 'LIKE',
									),
								),
							)
						);
					}

					foreach ($articles as $article) {

						$user = get_userdata($user_id);
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

						$article_id = $article->ID;
						$order = new WC_Order($article_id);
						if (in_array($order->customer_id, $users_orders)) {
							$total = array();
							$i = 1;
							$order_statuses = wc_get_order_statuses();
							$items = $order->get_items();
							$order_data = $order->get_data();
							$order_status = $order_data['status'];
							$status_name = $order_statuses['wc-' . $order_status];
							$culori_status = array('pending' => 'background-color:#ef8181; color: #fff', 'on-hold' => 'background-color:#f8dda7; color: #fff', 'processing' => 'background-color:#c6e1c6; color: #fff', 'completed' => 'background-color:#c8d7e1; color: #000', 'cancelled' => 'background-color:#e5e5e5; color: #000', 'refound' => 'background-color:#5BC0DE; color: #fff', 'inproduction' => 'background-color:#7878e2; color: #fff', 'transit' => 'background-color:#85bb65; color: #fff', 'waiting' => 'background-color:#3ba000; color: #fff', 'account-on-hold' => 'background-color:red; color: #fff', 'inrevision' => 'background-color:#8B4513; color: #fff', 'revised' => 'background-color:#8B4513; color: #fff');
							$previous_status = get_post_meta($order->ID, 'previous_status', true);
							?>
              <tr class=" ">
                <td>
									<?php echo $i; ?>.
                </td>
                <td>
									<?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->ID, 'cart_name', true) . '</i>'; ?>
                </td>
                <td>
									<?php
									echo $order_data['date_created']->date('Y-m-d H:i:s');

									$text = $order_data['date_created']->date('Y-m-d H:i:s');
									preg_match('/-(.*?)-/', $text, $match);
									$month = $match[1];
									?>
                </td>
                <td>
									<?php echo get_post_meta($order->ID, 'delivereis_start', true); ?>
                </td>
                <td>
									<?php

									echo $order->get_total();

									foreach ($items as $item) {

										$prod_id = $item->get_product_id();
										$name = $item->get_name();
										//echo '<a href="/product-edit/?id='.($prod_id*1498765*33).'" target="_blank">'.$name.'</a>';
										//echo '<br>';

										$property_total = get_post_meta($prod_id, 'property_total', true);
										$total[$month] += (float)$property_total;
									}
									?>
                </td>
                <td>
                  <p class="statusOrder" style="<?php
									// foreach ($culori_status as $stat => $cul) {
									if ($previous_status) {
										if (array_key_exists($order_status, $culori_status)) {
											echo $culori_status[$previous_status] . ';';
										}
									} else {
										if (array_key_exists($order_status, $culori_status)) {
											echo $culori_status[$order_status] . ';';
										}
									}
									// }
									?> text-align:center;">Ordered -
										<?php
										if ($order_status == 'pending') {
											echo 'Pending Payment';
										} //teo>
                    elseif ($order_status == 'waiting') {
											echo 'Waiting Delivery';
										} //teo<
										else {
											echo $status_name;
										}
										?>
                  </p>
                </td>
                <td>
									<?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                </td>
                <td>
                  <a href="/view-order/?id=<?php echo $order->ID * 1498765 * 33 ?>"
                     class="btn btn-primary">View</a>
                </td>
              </tr>

							<?php
							$i++;
						}
					}

					?>

          </tbody>
          </table>
					<?php
				} else {

					$user = get_userdata($user_id);
					if (in_array('dealer', $user->roles)) {
						$users_orders = get_user_meta($user_id, 'employees', true);
					}
					// if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders
					if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
						// get user company_parent id from meta
						$deler_id = get_user_meta($user_id, 'company_parent', true);
						// initiate users_orders array with logged user id
						$users_orders = array($user_id);

						if (!empty($deler_id)) {
							$users_orders[] = $deler_id;
						}
					}

					if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles)) {
						$users_orders = array($user_id);
					}
					foreach ($users_orders as $user_id) {
						$args = array(
							'customer_id' => $user_id,
							'status' => $status,
							'return' => $customer_order,

						);
						//print_r($user_id);
						$orders = wc_get_orders($args);

						$total = array();
						$i = 1;
						// echo '<pre>';
						// 	print_r($orders);
						// echo '</pre>';
						foreach ($orders as $order) {
							$items = $order->get_items();
							$order_data = $order->get_data();
							$order_status = $order_data['status'];
							$order_statuses = wc_get_order_statuses();
							$order_data = $order->get_data();
							$order_status = $order_data['status'];
							$status_name = $order_statuses['wc-' . $order_status];
							$culori_status = array('pending' => 'background-color:#ef8181; color: #fff', 'on-hold' => 'background-color:#f8dda7; color: #fff', 'processing' => 'background-color:#c6e1c6; color: #fff', 'completed' => 'background-color:#c8d7e1; color: #000', 'cancelled' => 'background-color:#e5e5e5; color: #000', 'refound' => 'background-color:#5BC0DE; color: #fff', 'inproduction' => 'background-color:#7878e2; color: #fff', 'transit' => 'background-color:#85bb65; color: #fff', 'waiting' => 'background-color:#3ba000; color: #fff', 'account-on-hold' => 'background-color:red; color: #fff', 'inrevision' => 'background-color:#8B4513; color: #fff', 'revised' => 'background-color:#8B4513; color: #fff');
							$previous_status = get_post_meta($order->ID, 'previous_status', true);
							?>
              <tr class=" ">
                <td>
									<?php echo $i; ?>.
                </td>
                <td>
									<?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->ID, 'cart_name', true) . '</i>'; ?>
                </td>
                <td>
									<?php
									echo $order_data['date_created']->date('Y-m-d H:i:s');

									$text = $order_data['date_created']->date('Y-m-d H:i:s');
									preg_match('/-(.*?)-/', $text, $match);
									$month = $match[1];
									?>
                </td>
                <td>
									<?php echo get_post_meta($order->ID, 'delivereis_start', true); ?>
                </td>
                <td>
									<?php

									echo $order->get_total();

									foreach ($items as $item) {

										$prod_id = $item->get_product_id();
										$name = $item->get_name();
										//echo '<a href="/product-edit/?id='.($prod_id*1498765*33).'" target="_blank">'.$name.'</a>';
										//echo '<br>';

										$property_total = get_post_meta($prod_id, 'property_total', true);
										$total[$month] += (float)$property_total;
									}

									?>
                </td>
                <td>
                  <p class="statusOrder" style="<?php
									// foreach ($culori_status as $stat => $cul) {
									if ($previous_status) {
										if (array_key_exists($order_status, $culori_status)) {
											echo $culori_status[$previous_status] . ';';
										}
									} else {
										if (array_key_exists($order_status, $culori_status)) {
											echo $culori_status[$order_status] . ';';
										}
									}
									// }
									?> text-align:center;">Ordered -
										<?php
										if ($order_status == 'pending') {
											echo 'Pending Payment';
										} //teo>
                    elseif ($order_status == 'waiting') {
											echo 'Waiting Delivery';
										} //teo<
										else {
											echo $status_name;
										}
										?>
                  </p>
                </td>
                <td>
									<?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                </td>
                <td>
                  <a href="/view-order/?id=<?php echo $order->ID * 1498765 * 33 ?>"
                     class="btn btn-primary">View</a>
                </td>
              </tr>

							<?php
							$i++;
						}
					}
					?>

          </tbody>
          </table>
          <input type="hidden" name="totalcart" value="<?php print_r($total); ?>">

					<?php
				}
			}
			?>

      <!-- #primary -->

      <script>
          // jQuery(document).ready(function(){
          //     jQuery( ".hasDatepicker" ).datepicker();
          // });
      </script>

    </main>
  </div>

<?php
get_footer();
