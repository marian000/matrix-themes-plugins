<?php

$container_id = $_POST['containerId'];
$info = $_POST;
//print_r($_POST);

//$post_id = 'LF08500, LF08499';

// Given variable
$orders_ref = $_POST['list'];

// Explode orders_ref and process each order
$order_refs = explode(',', $orders_ref);

// Initialize an array to store order ids
$order_ids = array();

foreach ($order_refs as $order_ref) {
	// Check if the order reference starts with 'LFR'
	if (substr($order_ref, 0, 3) === 'LFR') {
		// Remove 'LFR' and leading zeros
		$order_ref = ltrim(substr($order_ref, 3), '0');

		// Set the meta key and meta value you want to search for
		$meta_key = 'order-id-scv';
		$meta_value = $order_ref;

// Use get_posts() to search for posts with the specified meta_key and meta_value
		$args = array(
			'post_type' => 'order_repair', // or use the specific post type you want to search for
			'posts_per_page' => 1, // we just need one result
			'meta_key' => $meta_key,
			'meta_value' => $meta_value,
		);
		$posts = get_posts($args);
		$order_id = $posts[0]->ID;
	} else {
		// Remove 'LF' and leading zeros
		$order_ref = ltrim(substr($order_ref, 2), '0');
		$customer_order = strtok($order_ref, '- ');

		// Get order_id by _order_number meta_key
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_order_number' AND meta_value = $customer_order ", ARRAY_A);
		$order_id = $results[0]['post_id'];
	}
//	print_r($order_ref);

	// Extract customer_order from order_ref

	// Add order_id to the array
	if (!empty($order_id)) {
		$order_ids[] = $order_id;
	}
}

//echo ' ++++ orders ids: ++++ ';
//print_r($order_ids);

// Update container_orders meta
foreach ($order_ids as $post_id) {
	// Remove order_id from old_container_orders
	$old_container_id = get_post_meta($post_id, 'container_id', true);
	$old_container_orders = get_post_meta($old_container_id, 'container_orders', true);
	$key = array_search($post_id, $old_container_orders);
	if (false !== $key) {
		unset($old_container_orders[$key]);
		update_post_meta($old_container_id, 'container_orders', $old_container_orders);
	}

	// Add order_id to new container_orders
	$container_orders = get_post_meta($container_id, 'container_orders', true);
	if (empty($container_orders)) {
		update_post_meta($container_id, 'container_orders', array($post_id));
	} elseif (!in_array($post_id, $container_orders)) {
		$container_orders[] = $post_id;
//		print_r($container_orders);

		update_post_meta($container_id, 'container_orders', $container_orders);
	}

	// Update current order with a meta container for reallocating container
	update_post_meta($post_id, 'container_id', $container_id);
}

wp_die();