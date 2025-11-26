<?php

// Hook into the 'init' action to register the custom post type
add_action('init', 'create_appcart_post_type');

function create_appcart_post_type() {
	// Define the labels for the custom post type
	$labels = array(
	  'name'                  => _x('App Carts', 'Post type general name', 'textdomain'),
	  'singular_name'         => _x('App Cart', 'Post type singular name', 'textdomain'),
	  'menu_name'             => _x('App Carts', 'Admin Menu text', 'textdomain'),
	  'name_admin_bar'        => _x('App Cart', 'Add New on Toolbar', 'textdomain'),
	  'add_new'               => __('Add New', 'textdomain'),
	  'add_new_item'          => __('Add New App Cart', 'textdomain'),
	  'new_item'              => __('New App Cart', 'textdomain'),
	  'edit_item'             => __('Edit App Cart', 'textdomain'),
	  'view_item'             => __('View App Cart', 'textdomain'),
	  'all_items'             => __('All App Carts', 'textdomain'),
	  'search_items'          => __('Search App Carts', 'textdomain'),
	  'parent_item_colon'     => __('Parent App Carts:', 'textdomain'),
	  'not_found'             => __('No App Carts found.', 'textdomain'),
	  'not_found_in_trash'    => __('No App Carts found in Trash.', 'textdomain'),
	  'featured_image'        => _x('App Cart Cover Image', 'Overrides the “Featured Image” phrase for this post type.', 'textdomain'),
	  'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase.', 'textdomain'),
	  'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase.', 'textdomain'),
	  'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase.', 'textdomain'),
	  'archives'              => _x('App Cart Archives', 'The post type archive label.', 'textdomain'),
	  'insert_into_item'      => _x('Insert into App Cart', 'Overrides the “Insert into post” phrase.', 'textdomain'),
	  'uploaded_to_this_item' => _x('Uploaded to this App Cart', 'Overrides the “Uploaded to this post” phrase.', 'textdomain'),
	  'filter_items_list'     => _x('Filter App Carts list', 'Screen reader text for the filter links.', 'textdomain'),
	  'items_list_navigation' => _x('App Carts list navigation', 'Screen reader text for the pagination.', 'textdomain'),
	  'items_list'            => _x('App Carts list', 'Screen reader text for the item list.', 'textdomain'),
	);

	// Define the arguments for the custom post type
	$args = array(
	  'labels'             => $labels,
	  'public'             => true, // Determines visibility in the front-end
	  'publicly_queryable' => true, // Allows querying the post type
	  'show_ui'            => true, // Enables default UI in the admin
	  'show_in_menu'       => true, // Displays the post type in the admin menu
	  'query_var'          => true, // Allows querying using `?appcart=value`
	  'rewrite'            => array('slug' => 'appcart'), // URL slug for the post type
	  'capability_type'    => 'post', // Use standard post capabilities
	  'has_archive'        => true, // Enable archive page for the post type
	  'hierarchical'       => false, // Set to `false` for non-hierarchical post types
	  'menu_position'      => null, // Position in the admin menu
	  'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'), // Features enabled
	  'show_in_rest'       => true, // Enable Gutenberg editor
	);

	// Register the post type with the specified arguments
	register_post_type('appcart', $args);
}

// Handle the AJAX request to delete an appCart post
add_action('wp_ajax_delete_appcart_post', 'delete_appcart_post');

function delete_appcart_post() {
	// Check for the nonce
	check_ajax_referer('delete_appcart_post_nonce', 'security');

	// Ensure the user has the correct capability to delete posts
	if (!current_user_can('delete_posts')) {
		wp_send_json_error('You do not have permission to delete this post.');
		return;
	}

	// Get the post ID from the request
	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

	// Ensure the post ID is valid and exists
	if ($post_id <= 0 || get_post_type($post_id) !== 'appcart') {
		wp_send_json_error('Invalid post ID.');
		return;
	}

	// Attempt to delete the post
	$deleted = wp_delete_post($post_id, true);

	if ($deleted) {
		wp_send_json_success('App Cart deleted successfully.');
	} else {
		wp_send_json_error('Failed to delete the App Cart.');
	}
}


function table_items_appcart($atts)
{
	$attributes = shortcode_atts(array(
	  'id' => 'default_id',
	  'table_class' => 'table table-striped',
	  'table_id' => 'example',
	  'appcart_id' => '',
	  'editable' => false,
	  'admin' => false,
	  'view_price' => false,
	), $atts);
	require_once plugin_dir_path(__FILE__) . 'appCart-table.php';
//	include_once(get_stylesheet_directory() . '/views/table/items.php');
}

add_shortcode('table_items_appcart', 'table_items_appcart');


/**
 * Shortcode to display product IDs attached to an AppCart post
 *
 * Usage: [appcart_products id="123"]
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function display_appcart_products($atts) {
	// Extract the attributes from the shortcode
	$atts = shortcode_atts(array(
	  'id' => 0 // Default ID to 0 if not provided
	), $atts, 'appcart_products');

	// Get the AppCart post ID from the shortcode attribute
	$appcart_id = intval($atts['id']);

	// Validate the AppCart ID
	if ($appcart_id <= 0 || get_post_type($appcart_id) !== 'appcart') {
		return '<p>Invalid AppCart ID provided.</p>';
	}

	// Get the product IDs attached to this AppCart (assuming stored as custom field)
	// Replace 'attached_product_ids' with your actual custom field key or meta key.
	$product_ids = get_post_meta($appcart_id, 'attached_product_ids', true);

	// Check if there are products attached
	if (empty($product_ids) || !is_array($product_ids)) {
		return '<p>No products attached to this AppCart.</p>';
	}

	// Build the HTML table with product IDs
	$output = '<table class="appcart-products-table">';
	$output .= '<thead><tr><th>Product ID</th><th>Product Name</th><th>View Product</th></tr></thead>';
	$output .= '<tbody>';

	foreach ($product_ids as $product_id) {
		$product = wc_get_product($product_id); // Get the WooCommerce product object

		if ($product) {
			$product_name = $product->get_name();
			$product_link = get_permalink($product_id);

			$output .= '<tr>';
			$output .= '<td>' . esc_html($product_id) . '</td>';
			$output .= '<td>' . esc_html($product_name) . '</td>';
			$output .= '<td><a href="' . esc_url($product_link) . '" target="_blank">View</a></td>';
			$output .= '</tr>';
		} else {
			$output .= '<tr><td colspan="3">Product not found: ' . esc_html($product_id) . '</td></tr>';
		}
	}

	$output .= '</tbody></table>';

	return $output;
}
add_shortcode('appcart_products', 'display_appcart_products');

// Register REST API route for creating an appCart and adding products
add_action('rest_api_init', function () {
	register_rest_route('custom/v1', '/create-appcart/', array(
	  'methods'  => 'POST',
	  'callback' => 'create_appcart_and_add_products',
	  'permission_callback' => function () {
		  return current_user_can('edit_posts'); // Ensure the user has permission to create posts
	  },
	));
});

/**
 * Callback function to handle creating an appCart and attaching products.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return WP_REST_Response The response object.
 */
function create_appcart_and_add_products(WP_REST_Request $request) {
	// Get parameters from the request body
	$title = sanitize_text_field($request->get_param('title'));
	$product_ids = $request->get_param('product_ids');

	// Validate the title
	if (empty($title)) {
		return new WP_REST_Response(array(
		  'message' => 'Title is required.',
		), 400);
	}

	// Validate and sanitize the product IDs
	if (empty($product_ids) || !is_array($product_ids)) {
		return new WP_REST_Response(array(
		  'message' => 'Product IDs must be provided as an array.',
		), 400);
	}

		$sanitized_product_ids = array_filter($product_ids, function ($product_id) {
			return is_numeric($product_id) && $product_id > 0;
		});

	// Create a new appCart post
	$appcart_id = wp_insert_post(array(
	  'post_title'   => $title,
	  'post_type'    => 'appcart',
	  'post_status'  => 'publish',
	  'post_content' => '', // Optional, leave empty or add content if needed
	  'post_author'  => get_current_user_id() // Set the current user as the author
	));

	if (is_wp_error($appcart_id) || $appcart_id === 0) {
		return new WP_REST_Response(array(
		  'message' => 'Failed to create appCart.',
		), 500);
	}

	// Update the attached product IDs in the newly created appCart post meta
	update_post_meta($appcart_id, 'attached_product_ids', $sanitized_product_ids);

	// Return success response with the newly created appCart ID
	return new WP_REST_Response(array(
	  'message' => 'AppCart created successfully.',
	  'appcart_id' => $appcart_id,
	  'attached_product_ids' => $sanitized_product_ids,
	), 201);
}