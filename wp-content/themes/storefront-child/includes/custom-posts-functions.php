<?php
/*
* Creating a function to create Container Custom POST
*/
function custom_post_type_generator()
{
// Set UI labels for Custom Post Type
	$labels_container = array(
		'name' => _x('Containers', 'Post Type General Name', 'matrix'),
		'singular_name' => _x('Container', 'Post Type Singular Name', 'matrix'),
		'menu_name' => __('Containers', 'matrix'),
		'parent_item_colon' => __('Parent Container', 'matrix'),
		'all_items' => __('All Containers', 'matrix'),
		'view_item' => __('View Container', 'matrix'),
		'add_new_item' => __('Add New Container', 'matrix'),
		'add_new' => __('Add New', 'matrix'),
		'edit_item' => __('Edit Container', 'matrix'),
		'update_item' => __('Update Container', 'matrix'),
		'search_items' => __('Search Container', 'matrix'),
		'not_found' => __('Not Found', 'matrix'),
		'not_found_in_trash' => __('Not found in Trash', 'matrix'),
	);

// Set other options for Custom Post Type

	$args_container = array(
		'label' => __('container', 'matrix'),
		'description' => __('Container nContainers', 'matrix'),
		'labels' => $labels_container,
		// Features this CPT supports in Post Editor
		'supports' => array('title', 'author', 'revisions', 'custom-fields',),
		// You can associate this CPT with a taxonomy or custom taxonomy.
		'taxonomies' => array('genres'),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 30,
		'menu_icon' => 'dashicons-upload',
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'page',
	);
	// Registering your Custom Post Type
	register_post_type('container', $args_container);

// Set UI labels for Custom Post Type
	$labels_repair = array(
		'name' => _x('Order Repair', 'Post Type General Name', 'matrix'),
		'singular_name' => _x('Order Repair', 'Post Type Singular Name', 'matrix'),
		'menu_name' => __('Order Repair', 'matrix'),
		'parent_item_colon' => __('Parent Order Repair', 'matrix'),
		'all_items' => __('All Order Repair', 'matrix'),
		'view_item' => __('View Order Repair', 'matrix'),
		'add_new_item' => __('Add New Order Repair', 'matrix'),
		'add_new' => __('Add New', 'matrix'),
		'edit_item' => __('Edit Order Repair', 'matrix'),
		'update_item' => __('Update Order Repair', 'matrix'),
		'search_items' => __('Search Order Repair', 'matrix'),
		'not_found' => __('Not Found', 'matrix'),
		'not_found_in_trash' => __('Not found in Trash', 'matrix'),
	);

// Set other options for Custom Post Type

	$args_repair = array(
		'label' => __('Order Repairs', 'matrix'),
		'description' => __('Order Repairs', 'matrix'),
		'labels' => $labels_repair,
		// Features this CPT supports in Post Editor
		'supports' => array('title', 'author', 'revisions', 'custom-fields',),
		// You can associate this CPT with a taxonomy or custom taxonomy.
		'taxonomies' => array('repairs'),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/
		'hierarchical' => false,
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-hammer',
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'page',
	);
	// Registering your Custom Post Type
	register_post_type('order_repair', $args_repair);
}


add_action('init', 'custom_post_type_generator', 0);

//Custom column for orders list with order referrence
// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
//add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column_shutter', 20);
function custom_shop_order_column_shutter($columns)
{
	$reordered_columns = array();

	// Inserting columns to a specific location
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'order_number') {
			// Inserting after "Status" column
			$reordered_columns['order_reff'] = __('Order Ref', 'theme_domain');
			$reordered_columns['order_QB'] = __('QB Invoice', 'theme_domain');
		}
		if ($key == 'order_status') {
			// Inserting after "Status" column
			$reordered_columns['eta_col'] = __('ETA', 'theme_domain');
			$reordered_columns['container-id'] = __('Container', 'theme_domain');

			// $reordered_columns['order-shipping'] = __('Delivery', 'theme_domain');
			$reordered_columns['order-sqm'] = __('SQM', 'theme_domain');
			$reordered_columns['order-price-dolar'] = __('Total $', 'theme_domain');
		}
	}
	if (current_user_can('china_admin')) {
		// Hide the columns
		unset($columns['order_total']);
	}
	return $reordered_columns;
}


// Adding custom fields meta data for each new column (example)
//add_action('manage_shop_order_posts_custom_column', 'custom_orders_list_column_content_shutter', 20, 2);
function custom_orders_list_column_content_shutter($column, $post_id)
{
	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';
	$myOrder = $wpdb->get_row("SELECT sqm, usd_price, gbp_price, delivery FROM $tablename WHERE idOrder = $post_id", ARRAY_A);
	$order = wc_get_order($post_id);
	$order_status = $order->get_status();
	$customer_id = get_post_meta($post_id, '_customer_user', true);
	$favorite = get_user_meta($customer_id, 'favorite_user', true);

	switch ($column) {
		case 'order-shipping':
			echo ($myOrder['delivery'] === 'air') ? '<strong>By Air</strong>' : '';
			break;

		case 'order-sqm':
			echo number_format($myOrder['sqm'], 3);
			break;

		case 'order-price-dolar':
			echo '$' . number_format($myOrder['usd_price'], 2);
			break;

		case 'order_reff':
			display_meta_value($post_id, 'cart_name', '<small>(<em>no value</em>)</small>');
			break;

		case 'order_QB':
			display_quickbooks_invoice_button($post_id);
			break;

		case 'container-id':
			display_meta_value($post_id, 'container_id', '<small>(<em>no value</em>)</small>', true);
			break;

		case 'eta_col':
			$delivereis_start = get_post_meta($post_id, 'delivereis_start', true);
			// get status of ticket
			$ticket_id_for_order = get_post_meta($post_id, 'ticket_id_for_order', true);

			$statusArray = stgh_get_statuses();
			$postStatus = get_post_status($ticket_id_for_order);
			$colors_ticket = array('stgh_notanswered' => '#ff000', 'stgh_answered' => '#ff0000', 'stgh_new' => '#ff0000');

			if ($delivereis_start != "") {
				echo $delivereis_start;
			} else {
				if ($ticket_id_for_order) {

					echo '<span style="color: ' . $colors_ticket[$postStatus] . '">';
//					echo 'Query ';
					echo (isset($statusArray[$postStatus])) ? $statusArray[$postStatus] : $postStatus;
					echo '</span>';
				} else {
					if ($favorite === "yes") {
						echo 'In Production';
					} else {
						if ($order_status == 'inproduction') {
							echo 'In Production';
						} else if ($order_status == 'processing') {
							echo 'In Production';
						} else {
							echo 'On Hold';
						}
					}
				}
			}
			break;

		case 'order_status':
			display_order_status($post_id, $order_status);
			break;
	}
}


// This function displays the meta value of a post, with an optional title.
function display_meta_value($post_id, $meta_key, $empty_value, $display_title = false)
{
	// Get the meta value of the post
	$value = get_post_meta($post_id, $meta_key, true);

	// If the value is not empty, display the value (optionally with title), otherwise display the empty value
	echo ($value) ? ($display_title ? get_the_title($value) : $value) : $empty_value;
}


// This function displays a button to create a Quickbooks invoice, or displays 'Invoice Created' if it already exists.
function display_quickbooks_invoice_button($post_id)
{
	// Get the Quickbooks invoice and billing company meta data
	$QB_invoice = get_post_meta($post_id, 'QB_invoice', true);
	$billing_company = get_post_meta($post_id, '_billing_company', true);

	// If the Quickbooks invoice is set or the billing company is 'Lifetime Shutters', display 'Invoice Created'
	if ($QB_invoice || $billing_company == 'Lifetime Shutters') {
		echo 'Invoice Created';
	} else {
		// Otherwise, display a button to create the Quickbooks invoice
		echo '<button class="send_qcuickbooks_invoice btn btn-primary" id-order="' . $post_id . '"  style="box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;">Create QB invoice</button>';
	}
}


// This function displays the order status with the appropriate color styling.
function display_order_status($post_id, $order_status)
{
	// Get the order statuses from WooCommerce
	$order_statuses = wc_get_order_statuses();
	// Get the status name
	$status_name = $order_statuses['wc-' . $order_status];
	// Get the previous status
	$previous_status = get_post_meta($post_id, 'previous_status', true);
	// Get the appropriate color for the current status
	$status_color = get_status_color($order_status, $previous_status);

	// Display the status with the appropriate color styling
	echo "<p class='statusOrder' style='text-align:center;padding: 5px 0;box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;border-radius: 7px;{$status_color}'>";
	echo ($order_status == 'pending') ? 'Pending Payment' : (($order_status == 'waiting') ? 'Waiting Delivery' : $status_name);
	echo "</p>";
}


function get_status_color($order_status, $previous_status)
{
	$culori_status = array(
		'pending' => 'background-color:#ef8181; color: #fff',
		'on-hold' => 'background-color:#f8dda7; color: yellow',
		'processing' => 'background-color:#c6e1c6; color: #fff',
		'completed' => 'background-color:#c8d7e1; color: #000',
		'cancelled' => 'background-color:#e5e5e5; color: #000',
		'refound' => 'background-color:#5BC0DE; color: #fff',
		'inproduction' => 'background-color:#7878e2; color: #fff',
		'transit' => 'background-color:#85bb65; color: #fff',
		'waiting' => 'background-color:#3ba000; color: #fff',
		'account-on-hold' => 'background-color:red; color: #fff',
		'inrevision' => 'background-color:#8B4513; color: yellow',
		'revised' => 'background-color:#8B4513; color: yellow',
	);

	if ($order_status == 'completed') {
		return 'background-color:#c8d7e1; color: #000';
	} elseif ($previous_status) {
		return (array_key_exists($order_status, $culori_status)) ? $culori_status[$previous_status] . ';' : '';
	} else {
		return (array_key_exists($order_status, $culori_status)) ? $culori_status[$order_status] . ';' : '';
	}
}


function new_modify_user_table($column)
{
	unset($column['posts']);
	$column['datecreation'] = 'Date creation';
	$column['group'] = 'Group';
	return $column;
}


add_filter('manage_users_columns', 'new_modify_user_table');

function new_modify_user_table_row($val, $column_name, $user_id)
{
	$current_selected_group = get_user_meta($user_id, 'current_selected_group', true);
	$group_added = get_user_meta($user_id, 'group_added', true);
	$user_info = get_userdata($user_id);
	switch ($column_name) {
		case 'datecreation' :
			return $user_info->user_registered;
		case 'group' :
			return ($group_added == 'yes' && !empty($current_selected_group)) ? $current_selected_group : '';
		default:
	}
	return $val;
}


add_filter('manage_users_custom_column', 'new_modify_user_table_row', 10, 3);

/***************************************************************/
//Custom column for container list with sqm and price
/***************************************************************/

// Add the custom columns to the container post type:
add_filter('manage_container_posts_columns', 'set_custom_edit_container_columns');
function set_custom_edit_container_columns($columns)
{
	$reordered_columns = array();
	// Inserting columns to a specific location
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'title') {
			// Inserting after "Status" column
			$reordered_columns['container-price'] = __('Price', 'theme_domain');
			$reordered_columns['container-sqm'] = __('SQM', 'theme_domain');
			$reordered_columns['packing'] = __('Pack. List', 'theme_domain');
			$reordered_columns['invoice'] = __('Inv.', 'theme_domain');
		}
	}

	return $reordered_columns;
}


// Add the data to the custom columns for the container post type:
add_action('manage_container_posts_custom_column', 'container_column_content', 10, 2);
function container_column_content($column, $post_id)
{
	if ($column == 'container-sqm') {
		echo '<span id="container-sqm-' . $post_id . '" data-id="' . $post_id . '" class="container-sqm"></span>';
	}
	if ($column == 'container-price') {
		echo '<span id="container-price-' . $post_id . '" data-id="' . $post_id . '" class="container-sqm"></span>';
	}
	if ($column == 'packing') {
		// $csv_pack_read = get_post_meta($post_id, 'csv_pack_read', true);
		$csv_orders_array = get_post_meta($post_id, 'csv_orders_array', true);
		$csv_pack_read = (!empty($csv_orders_array)) ? 'yes' : '';
		echo '<span id="container-pack-' . $post_id . '" data-id="' . $post_id . '" class="container-pack">' . $csv_pack_read . '</span>';
	}
	if ($column == 'invoice') {
		// $csv_invoice_read = get_post_meta($post_id, 'csv_invoice_read', true);
		$csv_orders_invoice_array = get_post_meta($post_id, 'csv_orders_invoice_array', true);
		$csv_invoice_read = (!empty($csv_orders_invoice_array)) ? 'yes' : '';
		echo '<span id="container-inv-' . $post_id . '" data-id="' . $post_id . '" class="container-inv">' . $csv_invoice_read . '</span>';
	}
}


add_filter('manage_edit-container_columns', 'container_columns_remove');

function container_columns_remove($columns)
{
	unset($columns['author']);
	return $columns;
}


/***************************************************************/
// END - Custom column for container list with sqm
/***************************************************************/

/***************************************************************/
//Custom column for order_repair list
/***************************************************************/

add_filter('manage_edit-order_repair_columns', 'edit_order_repair_columns', 20);
function edit_order_repair_columns($columns)
{
	unset($columns['author']);
	return $columns;
}


// Add the custom columns to the order_repair post type:
add_filter('manage_order_repair_posts_columns', 'set_custom_edit_order_repair_columns');
function set_custom_edit_order_repair_columns($columns)
{
	$reordered_columns = array();
	// Inserting columns to a specific location
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'title') {
			// Inserting after "Status" column
			$reordered_columns['customer-order'] = __('Customer Order', 'theme_domain');
			$reordered_columns['status-order'] = __('Status', 'theme_domain');
			$reordered_columns['eta-order'] = __('ETA', 'theme_domain');
			$reordered_columns['container-order'] = __('Container', 'theme_domain');
			$reordered_columns['repair-warranty'] = __('Cover Warranty', 'theme_domain');
			$reordered_columns['repair-cost'] = __('Repair Cost', 'theme_domain');
		}
	}

	return $reordered_columns;
}


// Add the data to the custom columns for the order_repair post type:
add_action('manage_order_repair_posts_custom_column', 'custom_order_repair_column', 10, 2);
function custom_order_repair_column($column, $post_id)
{
//	$order_id_scv = get_post_meta($post_id, 'order-id-scv', true);
//	$order_id_original = get_post_meta(get_the_ID(), 'order-id-original', true);
	$order_status = get_post_meta($post_id, 'order_status', true);
	$culori_status = array('pending' => '#ef8181', 'on-hold' => '#f8dda7', 'processing' => '#c6e1c6', 'completed' => '#c8d7e1', 'cancelled' => '#e5e5e5', 'refound' => '#5BC0DE', 'inproduction' => '#7878e2', 'transit' => '#85bb65', 'waiting' => '#3ba000', 'account-on-hold' => 'red');

	if ($column == 'customer-order') {
		echo get_the_author();
	}

	if ($column == 'status-order') { ?>
    <p class="statusOrder"
       style="<?php
			 foreach ($culori_status as $stat => $cul) {
				 if ($stat == $order_status) {
					 echo 'background-color:' . $cul . ';';
				 }
			 }
			 ?> color: #fff; text-align:center;">
      Ordered
      -
			<?php
			if ($order_status == 'pending') {
				echo 'pending payment';
			} //teo>
      elseif ($order_status == 'waiting') {
				echo 'waiting delivery';
			} //teo<
			else {
				echo $order_status;
			}
			?>
    </p>
		<?php
	}

	if ($column == 'eta-order') {
		echo get_post_meta($post_id, 'delivereis_start', true);
	}

	if ($column == 'container-order') {
		$container_id = get_post_meta($post_id, 'container_id', true);
		if ($container_id) {
			echo get_the_title($container_id);
		} else {
			echo 'No container selected';
		}
	}

	if ($column == 'repair-warranty') {
		$no_warranty = false;
		$order_id = get_post_meta($post_id, 'order-id-original', true);
		$warranty = get_post_meta($post_id, 'warranty', true);
		if ($warranty && get_post_type($order_id) == "shop_order") {
			$order = new WC_Order($order_id);
			$items = $order->get_items();
			foreach ($items as $item_id => $item_data) {
				if ($warranty[$item_id] == 'No') {
					$no_warranty = true;
				}
			}
			if ($no_warranty) {
				echo 'No';
			} else {
				echo 'Yes';
			}
		}
	}

	if ($column == 'repair-cost') {
		$cost_field = get_post_meta($post_id, 'cost_repair', true);
		if ($cost_field) {
			echo '£' . number_format($cost_field, 2);
		}
	}
}


/***************************************************************/
// END - Custom column for container list with sqm
/***************************************************************/

//Custom column for orders list with Container
// ADDING 2 NEW COLUMNS WITH THEIR TITLES (keeping "Total" and "Actions" columns at the end)
add_filter('manage_edit-stgh_ticket_columns', 'custom_stgh_ticket_container_shutter', 20);
function custom_stgh_ticket_container_shutter($columns)
{
	$reordered_columns = array();

	// Inserting columns to a specific location
	foreach ($columns as $key => $column) {
		$reordered_columns[$key] = $column;
		if ($key == 'stgh_ticket-id') {
			// Inserting after "Status" column
			$reordered_columns['stgh_ticket-order'] = __('Order', 'theme_domain');
		}
	}
	return $reordered_columns;
}


// Adding custom fields meta data for each new column (example)
add_action('manage_stgh_ticket_posts_custom_column', 'custom_stgh_ticket_list_container_shutter', 20, 2);
function custom_stgh_ticket_list_container_shutter($column, $post_id)
{
	if ($column == 'stgh_ticket-order') {
		// Get custom post meta data
		$order_ref = get_post_meta($post_id, 'order_ref', true);
		if ($order_ref) {
			echo '<a style="font-weight: bold;" href="https://matrix.lifetimeshutters.com/wp-admin/post.php?post_type=stgh_ticket&post=' . $post_id . '&action=edit">' . $order_ref . '</a>';
		} else {
			echo '';
		}
	}
}


function add_component_type_column_content($content, $column_name, $term_id)
{
	$position = get_term_meta($term_id, 'position', true);
	switch ($column_name) {
		case 'position':
			//do your stuff here with $term or $term_id
			$content = $position;
			break;
		default:
			break;
	}
	return $content;
}


add_filter('manage_component_type_custom_column', 'add_component_type_column_content', 10, 3);



function add_no_invoice_filter_to_subsubsub($views) {
	global $wpdb;

	// Count orders where QB_invoice meta key does not exist in post_meta
	$no_invoice_count = $wpdb->get_var("
    SELECT COUNT(*)
    FROM {$wpdb->posts} p
    WHERE p.post_type = 'shop_order'
    AND NOT EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm
        WHERE pm.post_id = p.ID
        AND pm.meta_key = 'QB_invoice'
    )
    AND NOT EXISTS (
        SELECT 1
        FROM {$wpdb->postmeta} pm2
        WHERE pm2.post_id = p.ID
        AND pm2.meta_key = '_billing_company'
        AND pm2.meta_value = 'Lifetime Shutters'
    )
");

	// Generate the URL for the "No Invoice" filter
	$no_invoice_url = admin_url('edit.php?post_type=shop_order&no_invoice=1');

	// Check if the current filter is active
	$current = isset($_GET['no_invoice']) && $_GET['no_invoice'] == '1' ? 'class="current"' : '';

	// Add the "No Invoice" filter as a new `<li>` item
	$views['no_invoice'] = sprintf(
	  '<li><a href="%s" %s>%s <span class="count">(%d)</span></a></li>',
	  esc_url($no_invoice_url),
	  $current,
	  __('No Invoice', 'woocommerce'),
	  $no_invoice_count
	);

	return $views;
}
add_filter('views_edit-shop_order', 'add_no_invoice_filter_to_subsubsub');


function filter_shop_order_query_for_no_invoice($query_vars) {
	global $pagenow;

	// Check if we're on the Orders page and the 'no_invoice' filter is active
	if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'shop_order' && isset($_GET['no_invoice'])) {
		// Add meta queries
		$query_vars['meta_query'] = [
		  'relation' => 'AND', // Combine multiple conditions
		  [
			'key' => 'QB_invoice',
			'compare' => 'NOT EXISTS', // Exclude orders with QB_invoice meta key
		  ],
		  [
			'key' => '_billing_company',
			'value' => 'Lifetime Shutters',
			'compare' => '!=', // Exclude orders with _billing_company set to 'Lifetime Shutters'
		  ],
		];
	}

	return $query_vars;
}
add_filter('request', 'filter_shop_order_query_for_no_invoice');