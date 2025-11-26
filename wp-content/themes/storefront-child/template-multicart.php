
<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Full width Multi Cart
 *
 */

get_header();



// Cache frequently used values
$user_id = get_current_user_id();
$is_logged_in = is_user_logged_in();
$is_not_outsider = !current_user_can('outsider');
$suspended = get_user_meta($user_id, 'suspended_user', true);
$is_suspended = ($suspended === 'yes');

// Get user data once
$user = wp_get_current_user();
$user_roles = $user->roles;

// Define reusable functions
function get_user_orders_array($user_id, $user_roles)
{
	$favorite = get_user_meta($user_id, 'favorite_user', true);

	$user = get_userdata($user_id);
	$var_view_price = get_user_meta($user_id, 'view_price', true);
	$view_price = ($var_view_price == 'yes' || $var_view_price == '') ? true : false;

	// if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders

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
			// Initialize $users_orders as an array
			$users_orders = [];

			// Fetch the 'employees' user meta
			$employees_meta = get_user_meta($deler_id, 'employees', true);

			// Check if $employees_meta is an array
			if (is_array($employees_meta)) {
				$users_orders = $employees_meta;
			}

			// Append $deler_id to the array
			$users_orders[] = $deler_id;

			// Reverse the array
			$users_orders = array_reverse($users_orders);
		}
	}
	if (in_array('dealer', $user->roles)) {
		// Initialize $users_orders as an array
		$users_orders = [];

		// Fetch the 'employees' user meta
		$employees_meta = get_user_meta($user_id, 'employees', true);

		// Check if $employees_meta is an array
		if (is_array($employees_meta)) {
			$users_orders = $employees_meta;
		}

		// Append $user_id to the array
		$users_orders[] = $user_id;

		// Reverse the array
		$users_orders = array_reverse($users_orders);
	}

	return $users_orders;
}

function render_cart_table($carts_sort, $userialize_data, $total_elements)
{
	$html = '';
	$i = 1;

	foreach ($carts_sort as $key => $carts) {
		$pieces = explode("#", $carts['name']);

		if ($carts['name'] == 'Order #1' || $pieces[0] == 'Order ') {
			if ($pieces[0] == 'Order ') {
				unset($userialize_data['carts'][$key]);
			}
			$i++;
			continue;
		}

		$is_selected = ($userialize_data['customer_id'] == $key);
		$position = $total_elements - $i;
		$date = date("d-m-Y", $carts['time']);
		$status = $is_selected ? "Current Order" : "Not Selected";

		$html .= '<tr class="' . ($is_selected ? 'tr-selected' : '') . '" position="' . $i . '">';
		$html .= '<td>' . $position . '</td>';
		$html .= '<td>';

		if ($is_selected) {
			$html .= '<a href="/checkout/"><strong>' . esc_html($carts['name']) . '</strong></a>';
		} else {
			$html .= esc_html($carts['name']);
		}

		$html .= '</td>';
		$html .= '<td>' . $date . '</td>';
		$html .= '<td>' . $status . '</td>';
		$html .= '<td>';

		if (!$is_selected) {
			$html .= '<a class="btn btn-primary btn-sm" href="#select" target="' . $key . '" onclick="return BMCWcMs.command(\'select\', this);" title="Select Cart">Select</a> ';
			$html .= '<a class="btn btn-primary btn-sm" href="#edit" target="' . $key . '" onclick="return BMCWcMs.command(\'update\', this);">Edit Name</a>';
		} else {
			$html .= '<p class="label label-info" style="color: #fff;font-size: 14px;">Selected</p>';
		}

		$html .= '</td>';
		$html .= '<td></td>';
		$html .= '<td>';
		$html .= '<a class="btn btn-danger btn-sm delete-btn" indice="' . $i . '" href="#delete" target="' . $key . '" onclick="return BMCWcMs.command(\'delete\', this);">Delete Order</a>';
		$html .= '</td>';
		$html .= '</tr>';

		$i++;
	}

	return $html;
}

function render_order_buttons($user_roles, $user_id)
{
	$html = '<div class="btn-group-actions" style="margin-bottom: 15px;">';

	$html .= '<a class="btn btn-primary" href="#new" onclick="return BMCWcMs.command(\'insert\', this);" style="margin-right: 5px;">New Order</a>';
	$html .= '<a class="btn btn-primary" href="#newpos" onclick="return BMCWcMs.command(\'insertpos\', this);" style="margin-right: 5px;">Order Samples</a>';

	$special_users = array(23, 207, 39);
	if (in_array('component_buyer', $user_roles) || in_array('administrator', $user_roles) || in_array($user_id, $special_users)) {
		$html .= '<a class="btn btn-primary" href="#newcomponent" style="background-color: #e91e63; border-color: #e91e63; margin-right: 5px;" onclick="return BMCWcMs.command(\'insertcomponent\', this);">Order Components</a>';
	}

	$html .= '<a href="/order-repairs" class="btn btn-danger" style="margin-right: 5px;">Order Repairs</a>';
	$html .= '<a class="btn btn-primary" href="#new" onclick="return BMCWcMs.command(\'insertAwning\', this);">Order Awning</a>';

	$html .= '</div>';
	return $html;
}

function get_optimized_orders_data($users_orders, $current_page = 1, $orders_per_page = 250)
{
	// Cache key pentru rezultate
	$cache_key = 'optimized_orders_' . md5(serialize($users_orders)) . '_' . $current_page . '_' . $orders_per_page;
	$cached_result = wp_cache_get($cache_key, 'orders_cache');

	if ($cached_result !== false) {
		return $cached_result;
	}

	$args = array(
	  'customer_id' => $users_orders,
	  'limit' => $orders_per_page,
	  'page' => $current_page,
	  'paginate' => true,
	  'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
	  'orderby' => 'date',
	  'order' => 'DESC',
	  'return' => 'objects',
	);

	$customer_orders = wc_get_orders($args);

	// Cache rezultatul pentru 5 minute
	wp_cache_set($cache_key, $customer_orders, 'orders_cache', 300);

	return $customer_orders;
}

function render_optimized_orders_table($orders, $user_id, $view_price = true)
{
	if (empty($orders)) {
		return '<tr><td colspan="11">No orders found.</td></tr>';
	}

	global $wpdb;

	// Preluăm toate datele necesare cu query-uri batch
	$order_ids = array_map(function ($order) {
		return $order->get_id();
	}, $orders);
	$order_ids_str = implode(',', array_map('intval', $order_ids));

	// Batch query pentru custom_orders table
	$custom_orders_data = array();
	if (!empty($order_ids)) {
		$tablename = $wpdb->prefix . 'custom_orders';
		$custom_orders_results = $wpdb->get_results(
		  "SELECT idOrder, sqm, usd_price FROM {$tablename} WHERE idOrder IN ({$order_ids_str})",
		  ARRAY_A
		);

		foreach ($custom_orders_results as $row) {
			$custom_orders_data[$row['idOrder']] = array(
			  'sqm' => $row['sqm'],
			  'usd_price' => $row['usd_price'],
			);
		}
	}

	// Batch query pentru postmeta
	$postmeta_data = array();
	if (!empty($order_ids)) {
		$meta_keys = array('cart_name', 'container_id', 'delivereis_start', 'ticket_id_for_order', 'previous_status');
		$meta_keys_str = "'" . implode("','", $meta_keys) . "'";

		$postmeta_results = $wpdb->get_results(
		  "SELECT post_id, meta_key, meta_value FROM {$wpdb->postmeta} 
             WHERE post_id IN ({$order_ids_str}) AND meta_key IN ({$meta_keys_str})",
		  ARRAY_A
		);

		foreach ($postmeta_results as $row) {
			$postmeta_data[$row['post_id']][$row['meta_key']] = $row['meta_value'];
		}
	}

	// Batch query pentru container titles
	$container_titles = array();
	$container_ids = array();
	foreach ($postmeta_data as $order_id => $meta) {
		if (!empty($meta['container_id'])) {
			$container_ids[] = intval($meta['container_id']);
		}
	}

	if (!empty($container_ids)) {
		$container_ids_str = implode(',', array_unique($container_ids));
		$container_results = $wpdb->get_results(
		  "SELECT ID, post_title FROM {$wpdb->posts} WHERE ID IN ({$container_ids_str})",
		  ARRAY_A
		);

		foreach ($container_results as $row) {
			$container_titles[$row['ID']] = $row['post_title'];
		}
	}

	// Batch query pentru user meta (favorite_user)
	$favorite = get_user_meta($user_id, 'favorite_user', true);

	// Preluăm statusurile o singură dată
	$order_statuses = wc_get_order_statuses();

	// Definim culorile pentru statusuri
	$status_colors = array(
	  'pending' => 'background-color:#ef8181; color: #fff',
	  'on-hold' => 'background-color:#f8dda7; color: #fff',
	  'processing' => 'background-color:#c6e1c6; color: #fff',
	  'completed' => 'background-color:#c8d7e1; color: #000',
	  'cancelled' => 'background-color:#e5e5e5; color: #000; font-weight:bold;',
	  'refound' => 'background-color:#5BC0DE; color: #fff',
	  'inproduction' => 'background-color:#7878e2; color: #fff',
	  'transit' => 'background-color:#85bb65; color: #fff',
	  'waiting' => 'background-color:#3ba000; color: #fff',
	  'account-on-hold' => 'background-color:red; color: #fff',
	  'inrevision' => 'background-color:#8B4513; color: #fff',
	  'revised' => 'background-color:#8B4513; color: #fff',
	);

	// Dacă avem tickets, preluăm statusurile
	$ticket_statuses = array();
	if (function_exists('stgh_get_statuses')) {
		$ticket_statuses = stgh_get_statuses();
	}

	$colors_ticket = array(
	  'stgh_notanswered' => '#ff0000',
	  'stgh_answered' => '#ff0000',
	  'stgh_new' => '#ff0000',
	);

	$html = '';
	$j = 1;

	foreach ($orders as $order) {
		$order_data = $order->get_data();
		$order_id = $order->get_id();
		$order_status = $order_data['status'];
		$status_name = $order_statuses['wc-' . $order_status] ?? $order_status;

		// Preluăm datele din cache-ul nostru
		$sqm = isset($custom_orders_data[$order_id]) ? $custom_orders_data[$order_id]['sqm'] : 0;
		$cart_name = isset($postmeta_data[$order_id]['cart_name']) ? $postmeta_data[$order_id]['cart_name'] : '';
		$container_id = isset($postmeta_data[$order_id]['container_id']) ? $postmeta_data[$order_id]['container_id'] : '';
		$container_name = isset($container_titles[$container_id]) ? $container_titles[$container_id] : '';
		$deliveries_start = isset($postmeta_data[$order_id]['delivereis_start']) ? $postmeta_data[$order_id]['delivereis_start'] : '';
		$ticket_id_for_order = isset($postmeta_data[$order_id]['ticket_id_for_order']) ? $postmeta_data[$order_id]['ticket_id_for_order'] : '';
		$previous_status = isset($postmeta_data[$order_id]['previous_status']) ? $postmeta_data[$order_id]['previous_status'] : '';

		$html .= '<tr>';
		$html .= '<td>' . $j . '.</td>';
		$html .= '<td>LF0' . $order->get_order_number() . ' - <i>' . esc_html($cart_name) . '</i></td>';
		$html .= '<td>' . number_format(floatval($sqm), 2) . '</td>';
		$html .= '<td>' . $order_data['date_created']->date('Y-m-d H:i:s') . '</td>';
		$html .= '<td>' . esc_html($container_name) . '</td>';

		// Delivery status
		$html .= '<td>';
		if (!empty($deliveries_start)) {
			$html .= esc_html($deliveries_start);
		} else {
			if ($ticket_id_for_order) {
				$post_status = get_post_status($ticket_id_for_order);
				$color = isset($colors_ticket[$post_status]) ? $colors_ticket[$post_status] : '#ff0000';
				$status_text = isset($ticket_statuses[$post_status]) ? $ticket_statuses[$post_status] : $post_status;

				$html .= '<span style="color: ' . $color . ' !important">Query ' . $status_text . '</span>';
			} else {
				if ($favorite === "yes") {
					$html .= 'In Production';
				} else {
					if ($order_status == 'inproduction' || $order_status == 'processing') {
						$html .= 'In Production';
					} else {
						$html .= 'On Hold';
					}
				}
			}
		}
		$html .= '</td>';

		// Price
		$html .= '<td>' . ($view_price ? $order->get_total() : '') . '</td>';

		// Status with color
		$style = '';
		if ($previous_status && isset($status_colors[$order_status])) {
			$style = $status_colors[$order_status];
		} elseif (isset($status_colors[$order_status])) {
			$style = $status_colors[$order_status];
		}

		$status_text = $order_status == 'pending' ? 'Pending Payment' :
		  ($order_status == 'waiting' ? 'Waiting Delivery' : $status_name);

		$html .= '<td><p class="statusOrder" style="' . $style . '; text-align:center;">Ordered - ' . $status_text . '</p></td>';

		$html .= '<td>' . esc_html($order->get_billing_first_name() . ' ' . $order->get_billing_last_name()) . '</td>';
		$html .= '<td>' . $order_data['date_modified']->date('Y-m-d H:i:s') . '</td>';
		$html .= '<td><a href="/view-order/?id=' . ($order_id * 1498765 * 33) . '" class="btn btn-primary btn-sm">View</a></td>';
		$html .= '</tr>';

		$j++;
	}

	return $html;
}


function calculate_monthly_data_optimized($users_orders)
{
	if (empty($users_orders)) {
		return array_fill(0, 12, 0);
	}

	$args = array(
	  'customer_id' => $users_orders,
	  'limit' => -1,
	  'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
	  'orderby' => 'date',
	  'order' => 'DESC',
	  'return' => 'objects'
	);

	$orders = wc_get_orders($args);

	if (empty($orders)) {
		return array_fill(0, 12, 0);
	}

	// Preluăm datele din custom_orders table - aceeași logică ca în template-multicart.php
	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';

	$total = array();
	$total2 = array();
	$months_sum = array('01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0);

	// Inițializăm array-urile pentru ultimii ani
	for ($i = 2019; $i <= date('Y'); $i++) {
		foreach ($months_sum as $luna => $sum) {
			$total[$i][$luna] = $sum;
			$total2[$i][$luna] = $sum;
		}
	}



	// Construim array-ul cu order IDs pentru batch query
	$order_ids = array();
	foreach ($orders as $order) {
		$order_ids[] = $order->get_id();
	}

	// Batch query pentru custom_orders - optimizat
	$custom_orders_data = array();
	if (!empty($order_ids)) {
		$order_ids_str = implode(',', array_map('intval', $order_ids));
		$custom_orders_results = $wpdb->get_results(
		  "SELECT idOrder, sqm FROM {$tablename} WHERE idOrder IN ({$order_ids_str})",
		  ARRAY_A
		);

		foreach ($custom_orders_results as $row) {
			$custom_orders_data[$row['idOrder']] = floatval($row['sqm']);
		}
	}

	foreach ($orders as $order) {
		$items = $order->get_items();
		$order_data = $order->get_data();
		$order_status = $order_data['status'];
		$order_id = $order->get_id();

		// Extragem data și luna din order - aceeași logică
		$text = $order_data['date_created']->date('Y-m-d H:i:s');
		preg_match('/-(.*?)-/', $text, $match);
		preg_match('/(.*?)-/', $text, $match_year);
		$month = $match[1];
		$year = $match_year[1];

		if ($order_status != 'cancelled') {
			// Folosim datele din custom_orders table dacă sunt disponibile
			if (isset($custom_orders_data[$order_id])) {
				$property_total = $custom_orders_data[$order_id];
				$total[$year][$month] = $total[$year][$month] + $property_total;
				$total2[$year][$month] = $total2[$year][$month] + $property_total;
			} else {
				// Fallback la property_total din meta dacă nu există în custom_orders
				foreach ($items as $item) {
					$prod_id = $item->get_product_id();
					$quantity = get_post_meta($prod_id, 'quantity', true);
					$quantity = ($quantity == '') ? 1 : $quantity;
					$property_total = get_post_meta($prod_id, 'property_total', true);

					if ($property_total) {
						$total[$year][$month] = $total[$year][$month] + floatval($property_total) * floatval($quantity);
						$total2[$year][$month] = $total2[$year][$month] + floatval($property_total) * floatval($quantity);
					}
				}
			}
		}
	}

	// Folosim aceeași logică de organizare ca în template-multicart.php
	$newlunian = array();
	for ($i = 11; $i >= 0; $i--) {
		$newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
	}

	$new = array();
	foreach ($newlunian as $k => $anluna) {
		$pieces = explode("-", $anluna);
		$an = $pieces[0]; // year
		$luna = $pieces[1]; // month

		$found = false;
		foreach ($total as $key => $value) {
			if ($key == $an) {
				foreach ($total[$key] as $m => $t) {
					if ($luna == $m) {
						$new[$luna] = round($t, 0);
						$found = true;
						break;
					}
				}
				if ($found) break;
			}
		}

		if (!$found) {
			$new[$luna] = 0;
		}
	}

	// Convertim la array cu index-uri consecutive pentru ultimele 12 luni
	$monthly_data = array();
	for ($i = 11; $i >= 0; $i--) {
		$target_month = date('m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
		$monthly_data[11 - $i] = isset($new[$target_month]) ? $new[$target_month] : 0;
	}

	return $monthly_data;
}



function render_editable_orders_table($orders_to_edit_array)
{
	$html = '';
	if ($orders_to_edit_array) {
		$i = 1;
		foreach ($orders_to_edit_array as $order_id => $editable_status) {
			$order = wc_get_order($order_id);
			if ($order) {
				$html .= '<tr>';
				$html .= '<td>' . $i . '</td>';
				$html .= '<td>LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order_id, 'cart_name', true) . '</i></td>';
				$html .= '<td>' . wc_format_datetime($order->get_date_created()) . '</td>';
				$html .= '<td><a href="/editable-order/?id=' . ($order_id * 1498765 * 33) . '" class="btn btn-primary btn-sm">View Order and Edit</a></td>';
				$html .= '<td></td>';
				$html .= '</tr>';
				$i++;
			}
		}
	}
	return $html;
}

function render_pagination($customer_orders, $current_page)
{
	$html = '';
	if ($customer_orders->max_num_pages > 1) {
		$pagination_links = paginate_links(array(
		  'base' => get_pagenum_link(1) . '%_%',
		  'format' => 'page/%#%',
		  'current' => $current_page,
		  'total' => $customer_orders->max_num_pages,
		  'prev_next' => true,
		  'prev_text' => __('&laquo; Previous'),
		  'next_text' => __('Next &raquo;'),
		  'type' => 'array',
		));

		if ($pagination_links) {
			$html .= '<nav aria-label="Pagination" style="margin-top: 20px;">';
			$html .= '<ul class="pagination">';
			foreach ($pagination_links as $link) {
				$active_class = (strpos($link, 'current') !== false) ? 'active' : '';
				$html .= '<li class="' . $active_class . '">' . str_replace('page-numbers', '', $link) . '</li>';
			}
			$html .= '</ul>';
			$html .= '</nav>';
		}
	}
	return $html;
}

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

		<?php if ($is_logged_in && $is_not_outsider): ?>

			<?php
			// Get multi-cart data
			global $wpdb;
			$addresses = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, '_woocom_multisession'));
			?>

            <div class="row">
                <div class="col-xs-12">
                    <h2>My Orders</h2>

                    <div class="alert alert-warning" style="margin-top: 15px;">
						<?php echo wp_kses_post(get_post_meta(1, 'notification_users_message', true)); ?>
                    </div>

					<?php if (!$is_suspended): ?>

                        <div class="show-table2"></div>

						<?php echo render_order_buttons($user_roles, $user_id); ?>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead style="background-color: #f2dede;">
                                <tr>
                                    <th></th>
                                    <th>Unfinished Orders</th>
                                    <th>Date Created</th>
                                    <th>Is selected?</th>
                                    <th>Actions</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								if (!empty($addresses)) {
									$userialize_data = unserialize($addresses[0]->meta_value);
									$carts_sort = $userialize_data['carts'];
									ksort($carts_sort);
									$total_elements = count($carts_sort) + 1;

									echo render_cart_table($carts_sort, $userialize_data, $total_elements);

									// Update multi carts array
									update_user_meta($user_id, '_woocom_multisession', $userialize_data);
								}
								?>
                                </tbody>
                            </table>
                        </div>

					<?php else: ?>
                        <div style="margin-top: 15px;">
                            <a href="/order-repairs" class="btn btn-danger">Order Repairs</a>
                        </div>
					<?php endif; ?>

                    <div style="margin: 50px 0;">
						<?php echo do_shortcode('[display_my_app_orders]'); ?>
                    </div>

					<?php if (!in_array('china_admin', $user_roles) && !in_array('outsider', $user_roles) && !$is_suspended): ?>

                        <h2 style="margin-top: 50px;">My Placed Orders to Edit</h2>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead style="background-color: #f2dede;">
                                <tr>
                                    <th></th>
                                    <th>Editable Orders</th>
                                    <th>Date Created</th>
                                    <th>Actions</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$orders_to_edit_array = get_user_meta($user_id, 'orders_to_edit', true);
								echo render_editable_orders_table($orders_to_edit_array);
								?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Chart content -->
                        <div style="margin: 50px 0;">
                            <h3 class="text-center"><strong>m2 SALES PER MONTH</strong></h3>
                            <hr>
                            <div id="container"></div>
                        </div>

                        <h2>My Transformed Orders</h2>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead style="background-color: #f2dede;">
                                <tr>
                                    <th></th>
                                    <th>Order ID</th>
                                    <th>SQM</th>
                                    <th>Date</th>
                                    <th>Container</th>
                                    <th><strong>Deliveries Status</strong></th>
                                    <th>Total</th>
                                    <th>Order Status</th>
                                    <th>Added by</th>
                                    <th>Last Update</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$users_orders = get_user_orders_array($user_id, $user_roles);
								// Calculate monthly data for chart (optimized)
								$monthly_data = calculate_monthly_data_optimized($users_orders);
								$current_page = max(1, get_query_var('page', 1));
								$customer_orders = get_optimized_orders_data($users_orders, $current_page);
								$orders = $customer_orders->orders;

								$view_price = (get_user_meta($user_id, 'view_price', true) === 'yes' || get_user_meta($user_id, 'view_price', true) === '');

								echo render_optimized_orders_table($orders, $user_id, $view_price);


								?>
                                </tbody>
                            </table>
                        </div>

						<?php echo render_pagination($customer_orders, $current_page); ?>

					<?php endif; ?>

                </div>
            </div>

		<?php endif; ?>

    </main>
</div>

<?php


// Formatează datele pentru 2 zecimale
$formatted_monthly_data = array();
if (isset($monthly_data)) {
	foreach ($monthly_data as $value) {
		$formatted_monthly_data[] = round($value, 2);
	}
} else {
	$formatted_monthly_data = array_fill(0, 12, 0.00);
}
?>

<script>
    jQuery(document).ready(function () {
        var monthlyData = <?php echo json_encode($formatted_monthly_data); ?>;
        console.log('monthlyData', monthlyData);

        // Generate month labels
        var categories = [];
        for (var i = 11; i >= 0; i--) {
            var date = new Date();
            date.setMonth(date.getMonth() - i);
            categories.push(date.toLocaleDateString('en-US', {month: 'short', year: '2-digit'}));
        }

        Highcharts.chart('container', {
            chart: {type: 'column'},
            title: {text: null},
            legend: {
                align: "right",
                verticalAlign: "top",
                y: 75,
                x: -50,
                layout: "vertical"
            },
            xAxis: {categories: categories},
            yAxis: [{
                title: {text: "m2", margin: 70},
                min: 0
            }],
            tooltip: {
                enabled: true,
                valueDecimals: 0,
                valueSuffix: " sqm"
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        valueDecimals: 0
                    },
                    enableMouseTracking: true
                }
            },
            credits: {enabled: true},
            series: [{
                name: "m2",
                yAxis: 0,
                data: monthlyData
            }]
        });
    });
</script>

<?php get_footer(); ?>