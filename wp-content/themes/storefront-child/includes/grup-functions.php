<?php

/**
 * Calculate the total square meters for each material in an order.
 *
 * @param int $order_id The ID of the order.
 * @return array An associative array of materials and their total square meters.
 */
function order_items_materials_sqm($order_id)
{
	// Define the material mapping by material ID
	$materials = array(
		187 => 'Earth',
		137 => 'Green',
		138 => 'BiowoodPlus',
		6 => 'Biowood',
		139 => 'Supreme',
		188 => 'Ecowood',
		5 => 'EcowoodPlus',
	);
	$order_material_sqm = array();
	$order = wc_get_order($order_id);
	$items = $order->get_items();

	$attributes_array = get_post_meta(1, 'attributes_array', true);

	foreach ($items as $item_id => $item) {
		$product_id = $item['product_id'];
		$material_id = get_post_meta($product_id, 'property_material', true);
		$property_frametype = get_post_meta($product_id, 'property_frametype', true);
		$material = $materials[$material_id];
		$sqm = get_post_meta($product_id, 'property_total', true);
		// do something with the material value
		if (array_key_exists($material, $order_material_sqm)) {

//			if ($material_id == 138 && starts_with($attributes_array[$property_frametype], "P")) {
//				$prev_value = $order_material_sqm['BiowoodP'];
//				$order_material_sqm['BiowoodP'] = $sqm + $prev_value;
//			} else if ($material_id == 138 && !starts_with($attributes_array[$property_frametype], "P")) {
//				$prev_value = $order_material_sqm['BiowoodNonP'];
//				$order_material_sqm['BiowoodNonP'] = $sqm + $prev_value;
//			}

			// Asigură-te că valoarea anterioară este numerică; dacă nu, initializează cu 0
			if (!isset($order_material_sqm[$material]) || !is_numeric($order_material_sqm[$material])) {
				$order_material_sqm[$material] = 0;
			}

// Convertim și adunăm valorile în mod explicit la float
			$prev_value = floatval($order_material_sqm[$material]);
			$order_material_sqm[$material] = floatval($sqm) + $prev_value;
		} else {
			$order_material_sqm[$material] = $sqm;
//			if ($material_id == 138 && starts_with($attributes_array[$property_frametype], "P")) {
//				$order_material_sqm['BiowoodP'] = $sqm;
//			} else if ($material_id == 138 && !starts_with($attributes_array[$property_frametype], "P")) {
//				$order_material_sqm['BiowoodNonP'] = $sqm;
//			}
		}
	}
	return $order_material_sqm;
}


/**
 * Helper function to check if a string starts with a given substring.
 *
 * @param string $string The string to check.
 * @param string $startString The substring to check for.
 * @return bool True if the string starts with the substring, false otherwise.
 */
function starts_with($string, $startString)
{
	return (substr($string, 0, strlen($startString)) === $startString);
}


/**
 * Fetch users based on the selected group.
 *
 * @return array List of user IDs.
 */
function get_user_group()
{
	if ($_POST['group_name']) {
		if ($_POST['group_name'] === 'all_users') {
			$users = get_users(array('fields' => array('ID')));
			$group = array();
			foreach ($users as $user) {
				$group[] = $user->ID;
			}
		} else {
			$group = get_post_meta(1, $_POST['group_name'], true);
		}
		return $group;
	} else {
//		$groups_created = get_post_meta(1, 'groups_created', true);
//		$group = array();
//		for ($i = 0; $i < count($groups_created); $i++) {
//			$group_single = get_post_meta(1, $groups_created[$i], true);
//			$group = array_merge($group, $group_single);
//		}
		$users = get_users(array('fields' => array('ID')));
		$group = array();
		foreach ($users as $user) {
			$group[] = $user->ID;
		}
		return $group;
	}
}


/**
 * Fetch orders based on user group and selected year.
 *
 * @param array $user_group List of user IDs.
 * @param int $selected_year Selected year for the orders.
 * @return array List of order IDs.
 */
function fetch_orders($user_group, $selected_year,  $page = 1, $per_page = -1, $batch = false)
{
	if ($batch) {
		$per_page = 200;
	}
	$args = array(
		'customer_id' => $user_group,
		'limit' => $per_page,
		'page' => $page,
		'type' => 'shop_order',
		'status' => array(
			'wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing',
			'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'
		),
		'orderby' => 'date',
		'order' => 'DESC',
		'date_query' => array(
			'after' => date('Y-m-d', mktime(0, 0, 0, 1, 0, $selected_year)),
		),
		'return' => 'ids',
	);
	return wc_get_orders($args);
}


/**
 * Fetch custom order data from the database.
 *
 * @param array $order_ids List of order IDs.
 * @return array Associative array of order data.
 */
function fetch_custom_order_data($order_ids)
{
	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';
	$order_data = array();

	if (empty($order_ids)) {
		return $order_data;
	}

	$results = $wpdb->get_results(
		"SELECT idOrder, sqm, usd_price FROM $tablename WHERE idOrder IN (" . implode(',', array_map('intval', $order_ids)) . ")",
		ARRAY_A
	);

	foreach ($results as $line) {
		$order_data[$line['idOrder']] = array(
			'sqm' => (float)$line['sqm'],
			'usd_price' => (float)$line['usd_price'],
		);
	}

	return $order_data;
}


/**
 * Initialize data arrays for a given year.
 *
 * @param int $year Year to initialize data for.
 */
function initialize_yearly_data(&$data_arrays, $year)
{
	$months_sum = array_fill_keys(range(1, 12), 0);

	foreach ($data_arrays as &$array) {
		$array[$year] = $months_sum;
	}
}


function process_order_batch($orders) {
	// Initialize variables
	$total = $sum_total = $sum_total_train = $total_order_shipping = array();
	$total_dolar = $total_gbp = $total_gbp_train = $total_train_calc = $total_truck_calc = $total_materials_sqm = array();
	$months = array('01' => 'Jan.', '02' => 'Feb.', '03' => 'Mar.', '04' => 'Apr.', '05' => 'May', '06' => 'Jun.', '07' => 'Jul.', '08' => 'Aug.', '09' => 'Sep.', '10' => 'Oct.', '11' => 'Nov.', '12' => 'Dec.');
	$months_sum = array_fill_keys(array_keys($months), 0);

	// Data arrays to initialize
	$data_arrays = array(
		&$sum_total, &$sum_total_train, &$total_dolar,
		&$total, &$total_gbp, &$total_gbp_train,
		&$total_train_calc, &$total_truck_calc, &$total_order_shipping
	);

	// Process each order
	foreach ($orders as $order_id) {
		$order = wc_get_order($order_id);
		$order_data = $order->get_data();
		$custom_data = fetch_custom_order_data(array($order_id))[$order_id];
		$user_id_customer = get_post_meta($order_id, '_customer_user', true);

		// Extract year and month
		$order_date = $order_data['date_created']->date('Y-m');
		list($year, $month) = explode('-', $order_date);

		// Initialize arrays for the year if not already initialized
		if (!array_key_exists($year, $sum_total)) {
			initialize_yearly_data($data_arrays, $year);
		}

		// Calculate train price if applicable
		$property_total = $custom_data['sqm'];
		$train_price = 0;
		if ($property_total > 0 && $year >= 2021) {
			$train_price = get_post_meta($order_id, 'order_train', true);
			if (empty($train_price) || $train_price < 10) {
				$train_price = 10 * $property_total;
			}
		}

		// Update arrays with order data
		$sum_total_train[$year][$month] += $train_price;
		$sum_total[$year][$month] += floatval($order->get_total());

		// Update materials
		$items_material = order_items_materials_sqm($order_id);
		foreach ($items_material as $material => $material_sqm) {
			if (!isset($total_materials_sqm[$year][$month][$material])) {
				$total_materials_sqm[$year][$month][$material] = 0;
			}
			$total_materials_sqm[$year][$month][$material] += floatval($material_sqm);
		}

		// Update totals
		$total_dolar[$year][$month] += $custom_data['usd_price'];
		$total_gbp[$year][$month] += $order->get_subtotal() - $train_price;
		$total_gbp_train[$year][$month] += $order->get_subtotal();
		$total[$year][$month] += $property_total;

		// Calculate and update transport costs
		$average_train = (float)get_post_meta($order_id, 'average_train', true) ?: 0;
		$average_truck = (float)get_post_meta($order_id, 'average_truck', true) ?: 0;
		$total_train_calc[$year][$month] += $average_train * $property_total;
		$total_truck_calc[$year][$month] += $average_truck * $property_total;

		// Update shipping totals
		$order_shipping_total = floatval($order_data['shipping_total']) ?: 0;
		$total_order_shipping[$year][$month] += $order_shipping_total;
	}

	// Prepare response data
	$response_data = [];
	$response_data['count'] = count($orders);
	foreach ($sum_total as $year => $sum_ty) {
		foreach ($months as $month_num => $month_name) {
			if (isset($sum_ty[$month_num]) && $sum_ty[$month_num]) {
				$response_data[$month_name] = [
					'year' => $year,
					'month' => $month_name,
					'sqm' => number_format($total[$year][$month_num], 2),
					'dolar' => number_format($total_dolar[$year][$month_num], 2),
					'gbp' => number_format($total_gbp[$year][$month_num], 2),
					'shipping' => number_format($total_order_shipping[$year][$month_num], 2),
					'train' => number_format($sum_total_train[$year][$month_num], 2),
					'prices' => number_format($sum_ty[$month_num], 2),
					'earth' => number_format($total_materials_sqm[$year][$month_num]['Earth'], 2),
					'green' => number_format($total_materials_sqm[$year][$month_num]['Green'], 2),
					'ecowood' => number_format($total_materials_sqm[$year][$month_num]['Ecowood'], 2),
					'ecowoodPlus' => number_format($total_materials_sqm[$year][$month_num]['EcowoodPlus'], 2),
					'biowood' => number_format($total_materials_sqm[$year][$month_num]['Biowood'], 2),
					'biowoodPlus' => number_format($total_materials_sqm[$year][$month_num]['BiowoodPlus'], 2),
					'supreme' => number_format($total_materials_sqm[$year][$month_num]['Supreme'], 2)

				];
			}
		}
	}

	wp_send_json_success($response_data);
}



function my_process_order_batch()
{
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 200;

	// Fetch orders for the current batch
	$user_group = get_user_group();
	$selected_year = isset($_POST['an_select']) ? $_POST['an_select'] : date("Y");
	$orders = fetch_orders($user_group, $selected_year, $page, $per_page);

	// Process the orders
	$batch_result = process_order_batch($orders);

	// Store intermediate results in a transient or session
	if (session_id()) {
		if (!isset($_SESSION['batch_results'])) {
			$_SESSION['batch_results'] = array();
		}
		$_SESSION['batch_results'][] = $batch_result;
	} else {
		$existing_results = get_transient('batch_results') ?: array();
		$existing_results[] = $batch_result;
		set_transient('batch_results', $existing_results, 12 * HOUR_IN_SECONDS);
	}

	wp_send_json_success($batch_result);
}


add_action('wp_ajax_process_order_batch', 'my_process_order_batch');

function compile_final_results()
{
	// Retrieve accumulated results from session or transient
	if (session_id()) {
		$batch_results = isset($_SESSION['batch_results']) ? $_SESSION['batch_results'] : array();
		unset($_SESSION['batch_results']); // Clear session data
	} else {
		$batch_results = get_transient('batch_results') ?: array();
		delete_transient('batch_results'); // Clear transient data
	}

	// Compile final results from batch results
	$final_results = array();
	foreach ($batch_results as $batch_result) {
		// Merge batch results into final results
		// Your logic here to compile final results
	}

	wp_send_json_success($final_results);
}


add_action('wp_ajax_compile_final_results', 'compile_final_results');
