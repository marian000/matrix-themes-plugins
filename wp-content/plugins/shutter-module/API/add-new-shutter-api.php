<?php

// Hook into the REST API initialization to register our custom route.
add_action('rest_api_init', function () {
	// Register a new route for the namespace 'custom/v1' and route 'add-product'
	register_rest_route('custom/v1', 'add-matrix-shutter', array(
	  'methods' => 'POST', // Only allow POST method
	  'callback' => 'matrix_handle_product_insert', // Callback function to handle the request
	  'permission_callback' => function () {
		  return is_user_logged_in(); // Check if the user is logged in
	  },
	));
});

/**
 * Callback function for the REST API to handle product insertion.
 *
 * @param WP_REST_Request $request The REST API request object.
 * @return WP_REST_Response The response object.
 */
function matrix_handle_product_insert(WP_REST_Request $request)
{

	// Ensure WooCommerce is loaded
	global $woocommerce;

	// Get the JSON data from the request body and decode it
	$rawData = $request->get_body();
	my_custom_log('$rawData API', $rawData);

	$prodConfigs = json_decode($rawData, true);

	// Convert $prodConfigs to a JSON string
	$prodConfigsString = json_encode($prodConfigs);
	my_custom_log('DATA REST API', $prodConfigsString);

	// Check if the data was decoded properly
	if (empty($prodConfigs)) {
		my_custom_log('STATUS 400 REST API', $prodConfigsString);

		return new WP_REST_Response(array('message' => 'Invalid or empty JSON data'), 400);
	} else {
		my_custom_log('STATUS 200 REST API', $prodConfigsString);
//		print_r(transform_new_to_old($prodConfigs));

//		my_custom_log('$reverse_matrix data ', json_encode(transform_new_to_old($prodConfigs)));

		my_custom_log('Counter Shutters ', count($prodConfigs));
//		return new WP_REST_Response(array('message' => 'Valid JSON data ' . json_encode(transform_new_to_old($prodConfigs)), 200));
	}

	/**
	 * Start - Create New App Cart
	 */

	$title = !empty($prodConfigs['customer_name']) ? $prodConfigs['customer_name'] : "Test App Order";
//	$title = $prodConfigs['name'];

// Check if a post with the given title exists
	$existing_post = get_posts(array(
	  'post_type' => 'appcart',
	  'post_status' => 'publish',
	  'title' => $title, // Query by title
	  'numberposts' => 1, // Limit to one post
	  'fields' => 'ids', // Only return the ID
	));

	$product_ids = array();
	$products_images = array();
	$app_cart_id = 0;

	$user = get_user_by('email', $prodConfigs['user_email']);
	$user_id = $user->ID;

// If a post exists, use its ID; otherwise, create a new post
	if (!empty($existing_post)) {
		$app_cart_id = $existing_post[0]; // Get the existing post ID
		$product_ids = get_post_meta($app_cart_id, 'attached_product_ids', true);
		my_custom_log('product_ids ', json_encode($product_ids));
	} else {
		// Create a new appCart post
		$app_cart_id = wp_insert_post(array(
		  'post_title' => $title,
		  'post_type' => 'appcart',
		  'post_status' => 'publish',
		  'post_content' => '', // Optional, leave empty or add content if needed
		  'post_author' => $user_id, // Set the current user as the author
		));

		$orderData = $prodConfigs['orderData'];
		foreach ($orderData as $key => $value) {
			update_post_meta($app_cart_id, $key, $value);
		}
	}

	my_custom_log('$app_cart_id api: ', $app_cart_id);

	/**
	 * End - Create New App Cart
	 */

	if ($prodConfigs['shutters']) {
		foreach ($prodConfigs['shutters'] as $product_shutter) {

			my_custom_log('$product_shutter api: ', json_encode($product_shutter));

			$products = transform_new_to_old($product_shutter);

			my_custom_log('transformed products api: ', json_encode($products));
			my_custom_log('$products[name] api: ', $products['property_room_other']);

			$email_user = $prodConfigs['user_email'];

//			print_r('email: ', $email_user);

			$atribute = get_post_meta(1, 'attributes_array', true);
//update_post_meta( 1,'attributes_array',$atributezzz );

			/*
			 * Frame types ID => Depth calculater
			 *
			 * 	Calcul suprafata de facturare
				acum:		$width * $height / 1e6
				in plus:	daca se foloseste Z-frame
							daca una din perechile left|right($sillw) sau top|bottom($sillh) au valoarea "sill" sau "no"


			Calcul buildout
			acum:		daca $buildout > 0 se adauga 10%
			in plus:	daca si $buildout + $frdepth =< 100 raman +10%
					daca $buildout + $frdepth > 100 se adauga 20%
			 */
			$framesType = array(303 => 36, 323 => 46, 321 => 51, 330 => 72, 318 => 60, 322 => 87, 352 => 60, 331 => 51, 320 => 46, 325 => 46, 326 => 46, 327 => 46, 328 => 46, 324 => 46, 304 => 46, 329 => 49, 307 => 46, 310 => 64, 313 => 77, 333 => 60, 306 => 51, 309 => 64, 312 => 77, 305 => 46, 308 => 64, 311 => 77, 332 => 60, 142 => 36, 314 => 46, 315 => 46, 316 => 60, 317 => 60, 300 => 50, 302 => 50, 301 => 50, 351 => 60);

			$user_id = get_current_user_id();
			$dealer_id = get_user_meta($user_id, 'company_parent', true);

			my_custom_log('$user_id ', $user_id);

			// Prepare the product post data
			$post = array(
			  'post_author' => $user_id,
			  'post_content' => '', // No content for now
			  'post_status' => "publish", // Publish immediately
			  'post_title' => sanitize_text_field($products['property_room_other']),
			  'post_parent' => 0, // No parent post
			  'post_type' => "product", // Set post type as 'product'
			);

			// Insert the post and get the newly created post ID
			$post_id = wp_insert_post($post);

			my_custom_log('$post_id ', $post_id);

			// Set the product type to 'simple'
			wp_set_object_terms($post_id, 'simple', 'product_type');

			// Add custom meta data for the product
			$pieces = explode("-", sanitize_text_field($products['page_title']));
			update_post_meta($post_id, 'shutter_category', 'Shutter');

			// Check if the product matches certain categories

			if ($products['property_frametype'] == 171) {
				update_post_meta($post_id, 'shutter_category', 'Shutter & Blackout Blind');
			}

			// Update other product metadata
			update_post_meta($post_id, 'attachmentDraw', sanitize_text_field($products['attachmentDraw']));
			update_post_meta($post_id, '_visibility', 'visible');
			update_post_meta($post_id, '_stock_status', 'instock');
			update_post_meta($post_id, '_purchase_note', "");
			update_post_meta($post_id, '_featured', "no");
			update_post_meta($post_id, '_sku', "");
			update_post_meta($post_id, '_sale_price_dates_from', "");
			update_post_meta($post_id, '_sale_price_dates_to', "");
			update_post_meta($post_id, '_sold_individually', "");
			update_post_meta($post_id, '_manage_stock', "no");
			update_post_meta($post_id, '_backorders', "no");
			update_post_meta($post_id, '_stock', "50");

			$sum = 0;
			$i = 0;

			$discount_custom = get_user_meta($user_id, 'discount_custom', true);

			// Retrieve and round the property total value
			$sqm_value = round($products['property_total'], 2);

// Check if sqm_value is 0 or null
			if (empty($sqm_value) || $sqm_value == 0) {
				// Calculate sqm using property_width and property_height
				if (!empty($products['property_width']) && !empty($products['property_height'])) {
					$sqm_value = round(($products['property_width'] / 1000) * ($products['property_height'] / 1000), 2);
					$products['property_frametype'] = $sqm_value;
				} else {
					// Set sqm_value to 0 if width or height are not provided
					$sqm_value = 0;
				}
			}

			if ($products['property_frametype'] == 171) {
				$sqm_value_blackout = 0;
				$sqm_parts = blackoutSqmsApi($products);
				foreach ($sqm_parts as $k => $sqm) {
					if ($sqm < 1) {
						$sqm_value_blackout = $sqm_value_blackout + 1;
					} else {
						$sqm_value_blackout = $sqm_value_blackout + number_format($sqm, 2);
					}
				}
				update_post_meta($post_id, 'sqm_parts', $sqm_parts);
				update_post_meta($post_id, 'sqm_value_parts', $sqm_value_blackout);
			} elseif ($sqm_value < 0.5) {
				$sqm_value = 0.5;
			}

// Calculate price
			if ($products['property_width']) {
				$width_track = $products['property_width'];
				// to_document echo '-(Width track: ' . $width_track . ')-';
			}

			if ($products['property_material'] == 187) {
				if (!empty(get_user_meta($user_id, 'Earth', true)) || (get_user_meta($user_id, 'Earth', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'Earth', true)) + (($sqm_value * get_user_meta($user_id, 'Earth', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
						// to_document echo 'SUM Earth: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Earth', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'Earth', true);
						// to_document echo 'SUM Earth: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Earth', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_Earth', get_user_meta($user_id, 'Earth', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'Earth', true);
					// to_document echo 'SUM Earth: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'Earth', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_Earth', get_post_meta(1, 'Earth', true));
				}

				if ($products['property_style'] == 27) {
					$sum = $sum - ($sum * 5.45) / 100;
					$basic = $basic - ($basic * 5.45) / 100;
				}
				if ($products['property_style'] == 28) {
					$sum = $sum - ($sum * 3.45) / 100;
					$basic = $basic - ($basic * 3.45) / 100;
				}
				update_post_meta($post_id, 'basic_earth_price', floatval($basic));
			}
			if ($products['property_material'] == 188) {
				if (!empty(get_user_meta($user_id, 'Ecowood', true)) || (get_user_meta($user_id, 'Ecowood', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'Ecowood', true)) + (($sqm_value * get_user_meta($user_id, 'Ecowood', true)) * get_user_meta($user_id, 'Ecowood_tax', true)) / 100;
						// to_document echo 'SUM Ecowood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Ecowood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'Ecowood', true);
						// to_document echo 'SUM Ecowood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Ecowood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_Ecowood', get_user_meta($user_id, 'Ecowood', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'Ecowood', true);
					// to_document echo 'SUM Ecowood: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'Ecowood', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_Ecowood', get_post_meta(1, 'Ecowood', true));
				}
			}
			if ($products['property_material'] == 137) {
				if (!empty(get_user_meta($user_id, 'Green', true)) || (get_user_meta($user_id, 'Green', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'Green', true)) + (($sqm_value * get_user_meta($user_id, 'Green', true)) * get_user_meta($user_id, 'Green_tax', true)) / 100;
						// to_document echo 'SUM Green: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Green', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'Green', true);
						// to_document echo 'SUM Green: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Green', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_Green', get_user_meta($user_id, 'Green', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'Green', true);
					// to_document echo 'SUM Green: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'Green', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_Green', get_post_meta(1, 'Green', true));
				}
			}
			if ($products['property_material'] == 5) {
				if (!empty(get_user_meta($user_id, 'EcowoodPlus', true)) || (get_user_meta($user_id, 'EcowoodPlus', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'EcowoodPlus', true)) + (($sqm_value * get_user_meta($user_id, 'EcowoodPlus', true)) * get_user_meta($user_id, 'EcowoodPlus_tax', true)) / 100;
						// to_document echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'EcowoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'EcowoodPlus', true);
						// to_document echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'EcowoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_EcowoodPlus', get_user_meta($user_id, 'EcowoodPlus', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'EcowoodPlus', true);
					// to_document echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'EcowoodPlus', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_EcowoodPlus', get_post_meta(1, 'EcowoodPlus', true));
				}
			}
			if ($products['property_material'] == 138) {
				if (!empty(get_user_meta($user_id, 'BiowoodPlus', true)) || (get_user_meta($user_id, 'BiowoodPlus', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'BiowoodPlus', true)) + (($sqm_value * get_user_meta($user_id, 'BiowoodPlus', true)) * get_user_meta($user_id, 'BiowoodPlus_tax', true)) / 100;
						// to_document echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
						// to_document echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_BiowoodPlus', get_user_meta($user_id, 'BiowoodPlus', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'BiowoodPlus', true);
					// to_document echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'BiowoodPlus', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_BiowoodPlus', get_post_meta(1, 'BiowoodPlus', true));
				}
			}
			if ($products['property_material'] == 6) {
				if (!empty(get_user_meta($user_id, 'Biowood', true)) || (get_user_meta($user_id, 'Biowood', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'Biowood', true)) + (($sqm_value * get_user_meta($user_id, 'Biowood', true)) * get_user_meta($user_id, 'Biowood_tax', true)) / 100;
						// to_document echo 'SUM Biowood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Biowood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'Biowood', true);
						// to_document echo 'SUM Biowood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Biowood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_Biowood', get_user_meta($user_id, 'Biowood', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'Biowood', true);
					// to_document echo 'SUM Biowood: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'Biowood', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_Biowood', get_post_meta(1, 'Biowood', true));
				}
			}
			if ($products['property_material'] == 147) {
				if (!empty(get_user_meta($user_id, 'Basswood', true)) || (get_user_meta($user_id, 'Basswood', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'Basswood', true)) + (($sqm_value * get_user_meta($user_id, 'Basswood', true)) * get_user_meta($user_id, 'Basswood_tax', true)) / 100;
						// to_document echo 'SUM Basswood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Basswood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'Basswood', true);
						// to_document echo 'SUM Basswood: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'Basswood', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_Basswood', get_user_meta($user_id, 'Basswood', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'Basswood', true);
					// to_document echo 'SUM Basswood: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'Basswood', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_Basswood', get_post_meta(1, 'Basswood', true));
				}
			}

			if ($products['property_material'] == 139) {
				if (!empty(get_user_meta($user_id, 'BasswoodPlus', true)) || (get_user_meta($user_id, 'BasswoodPlus', true) > 0)) {
					if ($user_id == 18) {
						$sum = ($sqm_value * get_user_meta($user_id, 'BasswoodPlus', true)) + (($sqm_value * get_user_meta($user_id, 'BasswoodPlus', true)) * get_user_meta($user_id, 'BasswoodPlus_tax', true)) / 100;
						// to_document echo 'SUM BasswoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'BasswoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					} else {
						$sum = $sqm_value * get_user_meta($user_id, 'BasswoodPlus', true);
						// to_document echo 'SUM BasswoodPlus: ' . $sum . ' ..... ';
						$basic = $sqm_value * get_user_meta($user_id, 'BasswoodPlus', true);
						// to_document echo 'BASIC 1: ' . $basic . '<br>';
					}
					update_post_meta($post_id, 'price_item_BasswoodPlus', get_user_meta($user_id, 'BasswoodPlus', true));
				} else {
					$sum = $sqm_value * get_post_meta(1, 'BasswoodPlus', true);
					// to_document echo 'SUM BasswoodPlus: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_post_meta(1, 'BasswoodPlus', true);
					// to_document echo 'BASIC 1: ' . $basic . '<br>';
					update_post_meta($post_id, 'price_item_BasswoodPlus', get_post_meta(1, 'BasswoodPlus', true));
				}
			}

			if (!empty($discount_custom) || $discount_custom > 0) {
				// to_document echo ' Discount: ' . (($discount_custom * $basic) / 100) . ' - ' . $discount_custom . ' -';
				$sum = $sum - number_format((($discount_custom * $basic) / 100), 2, '.', '');
				// to_document echo 'SUM Discount: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 0: ' . $basic . '<br>';
				$basic = $sum;
			}

// $sum = $sqm_value*100;
// // to_document echo 'SUM 1: '.$sum.'<br>';
// $basic = $sqm_value*100;
// // to_document echo 'BASIC 1: '.$basic.'<br>';
//style

			if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232) || ($products['property_style'] == 38) || ($products['property_style'] == 39) || $products['property_style'] == 42 || $products['property_style'] == 43) {
				if (!empty(get_user_meta($user_id, 'Solid', true)) || (get_user_meta($user_id, 'Solid', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Solid', true) * $basic) / 100;
					// to_document echo 'SUM Solid: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'Solid', true) * $basic) / 100;
					// to_document echo 'SUM Solid: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_style'] == 33 || $products['property_style'] == 43) {
				if (!empty(get_user_meta($user_id, 'Shaped', true)) || (get_user_meta($user_id, 'Shaped', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Shaped', true) * $basic) / 100;
					// to_document echo 'SUM Shaped: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Shaped',true)/1;
					$sum = $sum + (get_post_meta(1, 'Shaped', true) * $basic) / 100;
					// to_document echo 'SUM Shaped: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_style'] == 34) {
				if (!empty(get_user_meta($user_id, 'French_Door', true)) || (get_user_meta($user_id, 'French_Door', true) > 0)) {
					$sum = $sum + get_user_meta($user_id, 'French_Door', true);
					// to_document echo 'SUM French_Door: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'French_Door',true)/1;
					$sum = $sum + get_post_meta(1, 'French_Door', true);
					// to_document echo 'SUM French_Door: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_style'] == 35 || $products['property_style'] == 39 || $products['property_style'] == 41) {
				if (!empty(get_user_meta($user_id, 'Tracked', true)) || (get_user_meta($user_id, 'Tracked', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Tracked', true) * ($width_track / 1000));
					//        $sum = $sum + (get_user_meta($user_id,'Tracked',true)*$basic)/100;
					// to_document echo 'SUM Tracked: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Tracked',true)/1;
					$sum = $sum + (get_post_meta(1, 'Tracked', true) * ($width_track / 1000));
					// to_document echo 'SUM Tracked: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_style'] == 37 || $products['property_style'] == 38 || $products['property_style'] == 40) {
				if (!empty(get_user_meta($user_id, 'TrackedByPass', true)) || (get_user_meta($user_id, 'TrackedByPass', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'TrackedByPass', true) * ($width_track / 1000));
					// to_document echo 'SUM TrackedByPass: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'TrackedByPass', true) * ($width_track / 1000));
					// to_document echo 'SUM TrackedByPass: ' . $sum . ' ..... ';
				}
				if ($products['property_tracksnumber'] >= 3) {
					if (!empty(get_user_meta($user_id, 'Tracked', true)) || (get_user_meta($user_id, 'Tracked', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'Tracked', true) *
							($products['property_tracksnumber'] - 2) * ($width_track / 1000));
						// to_document echo 'SUM Tracked: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + 71 *
						  ($products['property_tracksnumber'] - 2) * ($width_track / 1000);
						// to_document echo 'SUM Tracked: ' . $sum . ' ..... ';
					}
				}
			}

			if ($products['property_lightblocks'] == 'Yes') {
				if (!empty(get_user_meta($user_id, 'Light_block', true)) || (get_user_meta($user_id, 'Light_block', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Light_block', true) * $basic) / 100;
					// to_document echo 'SUM Light_block: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Shaped',true)/1;
					$sum = $sum + (get_post_meta(1, 'Light_block', true) * $basic) / 100;
					// to_document echo 'SUM Light_block: ' . $sum . ' ..... ';
				}
			}

			if ($products['property_style'] == 36 || $products['property_style'] == 42) {
				if ($products['property_material'] == 138) {
					//   $arched_price_user = get_user_meta($products['customer_id'], 'Arched', true) + 30;
					$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true);
//        $arched_price = get_post_meta(1, 'Arched', true) + 30;
					$arched_price = get_post_meta(1, 'Arched', true);
				} else {
					$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true);
					$arched_price = get_post_meta(1, 'Arched', true);
				}
				if (!empty(get_user_meta($user_id, 'Arched', true)) || (get_user_meta($user_id, 'Arched', true) > 0)) {
					$sum = $sum + ($arched_price_user * $basic) / 100;
					// to_document echo 'SUM Arched: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Shaped',true)/1;
					$sum = $sum + ($arched_price * $basic) / 100;
					// to_document echo 'SUM Arched: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_style'] == 229 || $products['property_style'] == 233 || $products['property_style'] == 40 || $products['property_style'] == 41) {
				if (!empty(get_user_meta($user_id, 'Combi', true)) || (get_user_meta($user_id, 'Combi', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Combi', true) * $basic) / 100;
					// to_document echo 'SUM Combi: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Combi',true)/1;
					$sum = $sum + (get_post_meta(1, 'Combi', true) * $basic) / 100;
					// to_document echo 'SUM Combi: ' . $sum . ' ..... ';
				}
			}

// measurement type
			if ($products['property_fit'] == 56) {
				if (!empty(get_user_meta($user_id, 'Inside', true)) || (get_user_meta($user_id, 'Inside', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Inside', true) * $basic) / 100;
					// to_document echo 'SUM inside: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Combi',true)/1;
					$sum = $sum + (get_post_meta(1, 'Inside', true) * $basic) / 100;
					// to_document echo 'SUM inside: ' . $sum . ' ..... ';
				}
			}

// Frame Type
			if ($products['property_frametype'] == 171) {
				if (!empty(get_user_meta($user_id, 'P4028X', true)) || (get_user_meta($user_id, 'P4028X', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'P4028X', true) * $basic) / 100;
					// to_document echo 'SUM P4028X: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Combi',true)/1;
					$sum = $sum + (get_post_meta(1, 'P4028X', true) * $basic) / 100;
					// to_document echo 'SUM P4028X: ' . $sum . ' ..... ';
				}

				if (!empty($products['property_blackoutblindcolour']) && $products['property_blackoutblindcolour'] != 390) {
					if (!empty(get_user_meta($user_id, 'blackoutblind', true)) || (get_user_meta($user_id, 'blackoutblind', true) > 0)) {
						$sum = $sum + get_user_meta($user_id, 'blackoutblind', true) * $sqm_value_blackout;
						// to_document echo 'SUM blackoutblind: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + get_post_meta(1, 'blackoutblind', true) * $sqm_value_blackout;
						// to_document echo 'SUM blackoutblind: ' . $sum . ' ..... ';
					}
				}

				if ($products['property_tposttype']) {
					if (!empty(get_user_meta($user_id, 'tposttype_blackout', true)) || (get_user_meta($user_id, 'tposttype_blackout', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'tposttype_blackout', true) * $basic) / 100;
						// to_document echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'tposttype_blackout', true) * $basic) / 100;
						// to_document echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
					}
				}

				if (!empty($products['bay-post-type'])) {
					if (!empty(get_user_meta($user_id, 'bposttype_blackout', true)) || (get_user_meta($user_id, 'bposttype_blackout', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'bposttype_blackout', true) * $basic) / 100;
						// to_document echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'bposttype_blackout', true) * $basic) / 100;
						// to_document echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
					}
				}
			}
			if ($products['property_frametype'] == 322) {
				if (!empty(get_user_meta($user_id, 'P4008T', true)) || (get_user_meta($user_id, 'P4008T', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'P4008T', true) * $basic) / 100;
					// to_document echo 'SUM P4008T: ' . $sum . ' ..... ';
				} else {

					$sum = $sum + (get_post_meta(1, 'P4008T', true) * $basic) / 100;
					// to_document echo 'SUM P4008T: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_frametype'] == 353) {
				if (!empty(get_user_meta($user_id, '4008T', true)) || (get_user_meta($user_id, '4008T', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, '4008T', true) * $basic) / 100;
					// to_document echo 'SUM 4008T: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, '4008T', true) * $basic) / 100;
					// to_document echo 'SUM 4008T: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_frametype'] == 319) {
				if (!empty(get_user_meta($user_id, 'P4008W', true)) || (get_user_meta($user_id, 'P4008W', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'P4008W', true) * $basic) / 100;
					// to_document echo 'SUM P4008W: ' . $sum . ' ..... ';
				} else {
					//$sum = get_post_meta(1,'Combi',true)/1;
					$sum = $sum + (get_post_meta(1, 'P4008W', true) * $basic) / 100;
					// to_document echo 'SUM P4008W: ' . $sum . ' ..... ';
				}
			}

//$tracked = $sum + $sum/1;

			if ($products['property_material'] == 139 || $products['property_material'] == 147) {
				if ($products['property_bladesize'] == 52) {
					$sum = $sum + (get_post_meta(1, 'Flat_Louver', true) * $basic) / 100;
					// to_document echo 'SUM bladesize: ' . $sum . ' ..... ';
				}
			}

			if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
				$frdepth = $framesType[$products['property_frametype']];
				$sum_build_frame = $frdepth + $products['property_builtout'];
				if ($sum_build_frame <= 100) {
					if (!empty(get_user_meta($user_id, 'Buildout', true)) || (get_user_meta($user_id, 'Buildout', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'Buildout', true) * $basic) / 100;
						// to_document echo 'SUM Buildout: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'Buildout', true) * $basic) / 100;
						// to_document echo 'SUM Buildout: ' . $sum . ' ..... ';
					}
				} elseif ($sum_build_frame > 100) {
					update_post_meta($post_id, 'sum_build_frame', true);
					$sum = $sum + (20 * $basic) / 100;
					// to_document echo 'SUM Buildout: ' . $sum . ' ..... ';
				}
			}
			if (($products['property_controltype'] == 403)) {
				if (!empty(get_user_meta($user_id, 'Concealed_Rod', true)) || (get_user_meta($user_id, 'Concealed_Rod', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Concealed_Rod', true) * $basic) / 100;
					// to_document echo 'SUM Concealed_Rod: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'Concealed_Rod', true) * $basic) / 100;
					// to_document echo 'SUM Concealed_Rod: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_controltype'] == 387) {
				$result = 0;
				if (!empty(get_user_meta($user_id, 'Hidden_Rod_with_Locking_System', true)) || (get_user_meta($user_id, 'Hidden_Rod_with_Locking_System', true) > 0)) {
					$result = get_user_meta($user_id, 'Hidden_Rod_with_Locking_System', true);
					$sum = $sum + ($basic * $result) / 100;
				} else {
					$result = get_post_meta(1, 'Hidden_Rod_with_Locking_System', true);
					$sum = $sum + $result * $sqm_value;
				}
				// to_document echo 'SUM Hidden_Rod_with_Locking_System: ' . $sum . ' ..... ';
			}
			if ($products['property_hingecolour'] == 93) {
				if (!empty(get_user_meta($user_id, 'Stainless_Steel', true)) || (get_user_meta($user_id, 'Stainless_Steel', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Stainless_Steel', true) * $basic) / 100;
					// to_document echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'Stainless_Steel', true) * $basic) / 100;
					// to_document echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
				}
			}
			if ($products['property_hingecolour'] == 186) {
				if (!empty(get_user_meta($user_id, 'Hidden', true)) || (get_user_meta($user_id, 'Hidden', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Hidden', true) * $basic) / 100;
					// to_document echo 'SUM Hidden: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'Hidden', true) * $basic) / 100;
					// to_document echo 'SUM Hidden: ' . $sum . ' ..... ';
				}
			}
			$bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
			$bpost_unghi = 0;
			foreach ($bayangle as $property_ba) {
				if (!empty($property_ba)) {
					if (!empty($products[$property_ba]) && $products['bay-post-type'] == 'normal') {
						if ($products[$property_ba] == 90 || $products[$property_ba] == 135) {
							// to_document echo '----- Unghi egal cu 135 sau 90: ' . $products['property_ba1'] . ' ------';
						} else {
							if (!empty(get_user_meta($user_id, 'Bay_Angle', true)) || (get_user_meta($user_id, 'Bay_Angle', true) > 0)) {
								$sum = $sum + (get_user_meta($user_id, 'Bay_Angle', true) * $basic) / 100;
								// to_document echo 'SUM Bay_Angle: ' . $sum . ' ..... ';
								// to_document echo '----- Unghi diferit de  90: ' . $products['property_ba1'] . ' ADAUGARE 10%------';
								$bpost_unghi++;
								break;
							} else {
								$sum = $sum + (get_post_meta(1, 'Bay_Angle', true) * $basic) / 100;
								// to_document echo 'SUM Bay_Angle: ' . $sum . ' ..... ';
								// to_document echo '----- Unghi diferit de  90: ' . $products['property_ba1'] . ' ADAUGARE 10%------';
								$bpost_unghi++;
								break;
							}
						}
					}
				}
			}

			if (!empty($products['bay-post-type'])) {
				update_post_meta($post_id, 'bay-post-type', $products['bay-post-type']);

				if ($products['bay-post-type'] == 'flexible') {
					if (!empty(get_user_meta($user_id, 'B_typeFlexible', true)) || (get_user_meta($user_id, 'B_typeFlexible', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'B_typeFlexible', true) * $basic) / 100;
						// to_document echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'B_typeFlexible', true) * $basic) / 100;
						// to_document echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
					}
				}
			}

// If Bpost have buidout add 7%
			$b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
			update_post_meta($post_id, 'property_b_buildout1', $products['property_b_buildout1']);
			foreach ($b_buildout as $property_bb) {
				if (!empty($property_bb)) {
					if (!empty($products[$property_bb])) {
						if (!empty(get_user_meta($user_id, 'B_Buildout', true)) || (get_user_meta($user_id, 'B_Buildout', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'B_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM B_Buildout: ' . $sum . ' ..... ';
							break;
						} else {
							$sum = $sum + (get_post_meta(1, 'B_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM B_Buildout: ' . $sum . ' ..... ';
							break;
						}
					}
				}
			}

// If G-post exist add 3%
//$g_post = get_post_meta( $post_id , 'property_g1' , true );
			if ($products['property_g1']) {
				if (!empty(get_user_meta($user_id, 'G_post', true)) || (get_user_meta($user_id, 'G_post', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'G_post', true) * $basic) / 100;
					// to_document echo 'SUM G-Post: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (3 * $basic) / 100;
					// to_document echo 'SUM G-Post: ' . $sum . ' ..... ';
				}
			}

// If tpost have buidout add 7%
			$t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
			update_post_meta($post_id, 'property_t_buildout1', $products['property_t_buildout1']);
			foreach ($t_buildout as $property_tb) {
				if (!empty($property_tb)) {
					if (!empty($products[$property_tb])) {
						if (!empty(get_user_meta($user_id, 'T_Buildout', true)) || (get_user_meta($user_id, 'T_Buildout', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'T_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM T_Buildout: ' . $sum . ' ..... ';
							break;
						} else {
							$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM T_Buildout: ' . $sum . ' ..... ';
							break;
						}
					}
				}
			}

			if (!empty($products['t-post-type'])) {
				update_post_meta($post_id, 't-post-type', $products['t-post-type']);

				if ($products['t-post-type'] == 'adjustable') {
					if (!empty(get_user_meta($user_id, 'T_typeAdjustable', true)) || (get_user_meta($user_id, 'T_typeFlexible', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
						// to_document echo 'SUM t-post-type: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
						// to_document echo 'SUM t-post-type: ' . $sum . ' ..... ';
					}
				}
			}

// If Cpost have buidout add 7%
			$c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
			update_post_meta($post_id, 'property_c_buildout1', $products['property_c_buildout1']);
			foreach ($c_buildout as $property_cb) {
				if (!empty($property_cb)) {
					if (!empty($products[$property_cb])) {
						if (!empty(get_user_meta($user_id, 'C_Buildout', true)) || (get_user_meta($user_id, 'C_Buildout', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'C_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM C_Buildout: ' . $sum . ' ..... ';
							break;
						} else {
							$sum = $sum + (get_post_meta(1, 'C_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM C_Buildout: ' . $sum . ' ..... ';
							break;
						}
					}
				}
			}

// ======== Start - special price for gren colors for user Perfect Shutters =========

// Colors 20%
//if ($user_id == 274 || $dealer_id == 274) {
//	// green colors
//	if ($products['property_material'] == 137) {
//		if (($products['property_shuttercolour'] == 101) || ($products['property_shuttercolour'] == 103) || ($products['property_shuttercolour'] == 104) || ($products['property_shuttercolour'] == 105) || ($products['property_shuttercolour'] == 106) || ($products['property_shuttercolour'] == 107) || ($products['property_shuttercolour'] == 108) || ($products['property_shuttercolour'] == 109) || ($products['property_shuttercolour'] == 110) || ($products['property_shuttercolour'] == 111) || ($products['property_shuttercolour'] == 112) || ($products['property_shuttercolour'] == 113) || ($products['property_shuttercolour'] == 114) || ($products['property_shuttercolour'] == 115) || ($products['property_shuttercolour'] == 116) || ($products['property_shuttercolour'] == 117) || ($products['property_shuttercolour'] == 118) || ($products['property_shuttercolour'] == 119) || ($products['property_shuttercolour'] == 120) || ($products['property_shuttercolour'] == 121)) {
//			if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
//				$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
//				// to_document echo 'SUM Colors green20%: ' . $sum . ' ..... ';
//			} else {
//				$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
//				// to_document echo 'SUM Colors green20%: ' . $sum . ' ..... ';
//			}
//		}
//	}
//}
// ======== END - special price for green colors for user Perfect Shutters =========

// Colors 20%
			if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
				if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
					// to_document echo 'SUM Colors: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
					// to_document echo 'SUM Colors: ' . $sum . ' ..... ';
				}
			}
// Colors 10%
			if (($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263) || ($products['property_shuttercolour'] == 274)) {
				$sum = $sum + (10 * $basic) / 100;
				// to_document echo 'SUM Colors: ' . $sum . ' ..... ';
			}

			if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232)) {
				if (!empty($products['property_ringpull'] && $products['property_ringpull'] == 'Yes')) {
					if (!empty(get_user_meta($user_id, 'Ringpull', true)) || (get_user_meta($user_id, 'Ringpull', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'Ringpull', true) * $products['property_ringpull_volume']);
						// to_document echo 'SUM Ringpull: ' . $sum . ' ..... ';
					} else {
						$sum = $sum + (get_post_meta(1, 'Ringpull', true) * $products['property_ringpull_volume']);
						// to_document echo 'SUM Ringpull: ' . $sum . ' ..... ';
					}
				}
			} else {
				// to_document echo '-(Delete ringpull: ' . $post_id, ')-';
				delete_post_meta($post_id, 'property_ringpull_volume', '');
				delete_post_meta($post_id, 'property_ringpull', 'YES');
			}

			$horizontal_tpost_add = 1;
			if (!empty($products['property_horizontaltpost']) && $products['property_horizontaltpost'] == 'Yes') {
				$horizontal_tpost_add = 2;
			}

// Count the number of 'T' or 't' characters
			$t_count = countTApi($products['property_layoutcode']);
			if (!empty($products['property_central_lock']) && $products['property_central_lock'] == 'Yes') {
				$t_count_add = ($t_count > 0) ? $t_count : 1;
				$sum = $sum + (get_post_meta(1, 'Central_Lock', true) * $t_count_add * $horizontal_tpost_add);
				// to_document echo 'SUM Central_Lock: ' . $sum . ' ..... ';
			}

			if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {

				// if material biowood and biowood plus add 10% for lock
				if ($products['property_material'] == 6 || $products['property_material'] == 138) {

					$sum = $sum + (58 * $products['property_locks_volume'] * $horizontal_tpost_add);
					// to_document echo 'SUM Lock: ' . $sum . ' ..... ';
				} else {
					if (!empty($products['property_lock_position'])) {
						$val_lock_position = $products['property_lock_position'];
						if ($val_lock_position == 'Central Lock') {
							$sum = $sum + (get_post_meta(1, 'Central_Lock', true) * $products['property_locks_volume'] * $horizontal_tpost_add);
						}
						if ($val_lock_position == 'Top & Bottom Lock') {
							$sum = $sum + (get_post_meta(1, 'Top_Bottom_Lock', true) * $products['property_locks_volume'] * $horizontal_tpost_add);
						}
						// to_document echo 'SUM lock_position: ' . $sum . ' ..... ';
					} else {
						if (!empty(get_user_meta($user_id, 'Lock', true)) || (get_user_meta($user_id, 'Lock', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'Lock', true) * $products['property_locks_volume'] * 2 * $horizontal_tpost_add);
							// to_document echo 'SUM Lock: ' . $sum . ' ..... ';
						} else {
							$sum = $sum + (get_post_meta(1, 'Lock', true) * $products['property_locks_volume'] * 2 * $horizontal_tpost_add);
							// to_document echo 'SUM Lock: ' . $sum . ' ..... ';
						}
					}
				}
			}

			$nrPanels = nrPanelsCountApi($products['property_layoutcode']);

// if layout have b t c, calculatePanelsWidthApi have panels with width < 200 then add to price 30,
			if ($products['property_layoutcode'] == 'B' || $products['property_layoutcode'] == 'T' || $products['property_layoutcode'] == 'C') {
				$panels_width = calculatePanelsWidthApi($products['property_layoutcode'], $products['property_width'], $products);
				// if panels_width have width < 200 then add 30 for each panel
				foreach ($panels_width as $panel) {
					if ($panel < 200) {
						$sum = $sum + get_post_meta(1, 'panel_width_price', true);
						// to_document echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
					}
				}
			} else if ($width_track / nrPanelsCountApi($products['property_layoutcode']) < 200) {
				$sum = $sum + $nrPanels * get_post_meta(1, 'panel_width_price', true);
				// to_document echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
			}

			if ($products['property_controltype'] == 387) {

				$midCount = midrailDividerCounterApi($products['property_midrailheight'], $products['property_midrailheight2'], $products['property_midraildivider1'], $products['property_midraildivider2']);
				if (!empty(get_user_meta($user_id, 'Louver_lock', true)) || (get_user_meta($user_id, 'Louver_lock', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Louver_lock', true) * $nrPanels * $midCount);
				} else {
					$sum = $sum + (get_post_meta(1, 'Louver_lock', true) * $nrPanels * $midCount);
				}
				// to_document echo 'SUM louver_lock: ' . $sum . ' ..... ';
			}

			if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
				if (!empty(get_user_meta($user_id, 'Spare_Louvres', true)) || (get_user_meta($user_id, 'Spare_Louvres', true) > 0)) {
					$sum = $sum + ($nrPanels * get_user_meta($user_id, 'Spare_Louvres', true));
					// to_document echo ' Panels: ' . $nrPanels . ' SUM Spare_Louvres: ' . $sum . ' ..... ';
				} else {
					$sum = $sum + ($nrPanels * get_post_meta(1, 'Spare_Louvres', true));
					// to_document echo 'SUM Spare_Louvres: ' . $sum . ' ..... ';
				}
			}

// if( !empty($discount_custom) || $discount_custom > 0 ){
//     $sum = $sum - ($discount_custom*$basic)/100;
//     // to_document echo 'SUM 0: '.$sum.'<br>';
// }

// ADD Tax for material for user lifetimeshutter (MikeR) id 18
			if (!empty(get_user_meta($user_id, 'SeaDelivery', true))) {
				$sum = $sum + (get_user_meta($user_id, 'SeaDelivery', true));
				// to_document echo 'SUM SeaDelivery: ' . $sum . ' ..... ';
			}

			// to_document echo "Suma_total: " . $sum;

			$g = 0;
			$t = 0;
			$b = 0;
			$c = 0;
//			my_custom_log('$products error ', json_encode($products));
			$product_attributes = array();
			foreach ($products as $name_attr => $id) {
				update_post_meta($post_id, $name_attr, $id);

				if ($name_attr == "property_layoutcode") {
					update_post_meta($post_id, $name_attr, strtoupper($id));
				}

				if (!is_array($id)) {
					//// to_document echo $product.'<br>';
					//$sum = $sum + $id;

					if ($name_attr == "property_width") {
						$width = $id;
						// to_document echo "gasit width";
					}
					if ($name_attr == "property_height") {
						$height = $id;
						// to_document echo "gasit height";
					}
					// to_document echo '<br>' . $width . '<br>';
					// to_document echo '<br>' . $height . '<br>';

					//    $sum = ($width*$height)/1000/4;
					//    // to_document echo $sum;

					if ($name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15") {
						$t++;
					} elseif ($name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15") {
						$g++;
					} elseif ($name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15") {
						$b++;
					} elseif ($name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15") {
						$c++;
					}

					// Verificăm dacă $id este scalar (string sau int) și dacă acesta există ca cheie în array-ul $atribute
//					if (!empty($id) && array_key_exists($id, $atribute)) {
//
//						if ($name_attr == "product_id" || $name_attr == "property_width" || $name_attr == "property_height" || $name_attr == "property_solidpanelheight" || $name_attr == "property_midrailheight" || $name_attr == "property_builtout" || $name_attr == "property_layoutcode" || $name_attr == "property_opendoor" || $name_attr == "quantity_split" || $name_attr == "property_ba1" || $name_attr == "property_ba2" || $name_attr == "property_ba3" || $name_attr == "property_ba4" || $name_attr == "property_ba5" || $name_attr == "property_ba6" || $name_attr == "property_ba7" || $name_attr == "property_ba8" || $name_attr == "property_ba9" || $name_attr == "property_ba10" || $name_attr == "property_ba11" || $name_attr == "property_ba12" || $name_attr == "property_ba13" || $name_attr == "property_ba14" || $name_attr == "property_ba15" || $name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15" || $name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15" || $name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15" || $name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15") {
//							$name_attr = explode("_", $name_attr);
//							$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
//							  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
//							  'value' => $id, // set attribute value
//							  'position' => $i,
//							  'is_visible' => 1,
//							  'is_variation' => 0,
//							  'is_taxonomy' => 0,
//							);
//							$i++;
//						} else {
//							$name_attr = explode("_", $name_attr);
//							$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
//							  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
//							  'value' => $atribute[$id], // set attribute value
//							  'position' => $i,
//							  'is_visible' => 1,
//							  'is_variation' => 0,
//							  'is_taxonomy' => 0,
//							);
//							$i++;
//						}
//					} else {
//
//						$name_attr = explode("_", $name_attr);
//						$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
//						  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
//						  'value' => $id, // set attribute value
//						  'position' => $i,
//						  'is_visible' => 1,
//						  'is_variation' => 0,
//						  'is_taxonomy' => 0,
//						);
//						$i++;
//					}
				}
			}

			$sections_price = array();
			if ($products['property_nr_sections']) {
				for ($sec = 1; $sec <= $products['property_nr_sections']; $sec++) {
					$sections_price[$sec] = 0;
				}
			}

			if ($products['property_nr_sections']) {

				for ($i = 1; $i <= $products['property_nr_sections']; $i++) {
					$property_tb = 'property_t_buildout1_' . $i;
					$tposttype = 't-post-type' . $i;
					$property_g1 = 'property_g1';

					if (!empty($products[$property_tb])) {
						update_post_meta($post_id, $property_tb, $products[$property_tb]);
						if (!empty(get_user_meta($user_id, 'T_Buildout', true)) || (get_user_meta($user_id, 'T_Buildout', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'T_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM T_Buildout: ' . $sum . ' ..... ';
							// to_document echo 'BASIC 7: ' . $basic . '<br>';
							break;
						} else {
							$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
							// to_document echo 'SUM T_Buildout: ' . $sum . ' ..... ';
							// to_document echo 'BASIC 7: ' . $basic . '<br>';
							break;
						}
					}

					if (!empty($products[$tposttype])) {
						update_post_meta($post_id, $tposttype, $products[$tposttype]);

						if ($products[$tposttype] == 'adjustable') {
							if (!empty(get_user_meta($user_id, 'T_typeAdjustable', true)) || (get_user_meta($user_id, 'T_typeFlexible', true) > 0)) {
								$sum = $sum + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
								// to_document echo 'SUM t-post-type: ' . $sum . ' ..... ';
								// to_document echo 'BASIC 7: ' . $basic . '<br>';
							} else {
								$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
								// to_document echo 'SUM t-post-type: ' . $sum . ' ..... ';
								// to_document echo 'BASIC 7: ' . $basic . '<br>';
							}
						}
					}

					if ($products[$property_g1]) {
						if (!empty(get_user_meta($user_id, 'G_post', true)) || (get_user_meta($user_id, 'G_post', true) > 0)) {
							$sum = $sum + (get_user_meta($user_id, 'G_post', true) * $basic) / 100;
							// to_document echo 'SUM G-Post: ' . $sum . ' ..... ';
							// to_document echo 'BASIC 7: ' . $basic . '<br>';
						} else {
							$sum = $sum + (3 * $basic) / 100;
							// to_document echo 'SUM G-Post: ' . $sum . ' ..... ';
							// to_document echo 'BASIC 7: ' . $basic . '<br>';
						}
					}
					$sections_price[$i] = $sum;
				}
			}

			if ($products['property_motorized'] == 'Yes') {
				$property_nrMotors = get_post_meta($post_id, 'property_nrMotors', true);
				$price_notor = get_post_meta(1, 'motor', true);
				$property_nrRemotes = get_post_meta($post_id, 'property_nrRemotes', true);
				$price_remote = get_post_meta(1, 'remote', true);
				$sum = $sum + $property_nrMotors * $price_notor;
				$sum = $sum + $property_nrRemotes * $price_remote;
			}

			$individual_counter = array();
			if ($products['property_nr_sections']) {
				$nr_sections = $products['property_nr_sections'];
				for ($sec = 1; $sec <= $nr_sections; $sec++) {
					$t = 0;
					$g = 0;
					$individual_counter[$sec]['counter_b'] = 0;
					$individual_counter[$sec]['counter_c'] = 0;
					$individual_counter[$sec]['counter_t'] = 0;
					$individual_counter[$sec]['counter_g'] = 0;
					for ($i = 1; $i <= 10; $i++) {
						$property_t = "property_t" . $i . "_" . $sec;
						$property_g = "property_g" . $i . "_" . $sec;
						if ($products[$property_t]) {
							$t++;
						} elseif ($products[$property_g]) {
							$g++;
						}
					}
					$individual_counter[$sec]['counter_t'] = $t;
					$individual_counter[$sec]['counter_g'] = $g;
				}
			}

			update_post_meta($post_id, 'sections_price', $sections_price);
			update_post_meta($post_id, 'individual_counter', $individual_counter);
			unset($product_attributes['shutter_svg']);
			update_post_meta($post_id, '_product_attributes', $product_attributes);
			// to_document echo "<pre>";
			// print_r($product_attributes);
			// to_document echo "</pre>";
			// to_document echo "<br>Total product price: " . $sum . " Euro";
			update_post_meta($post_id, '_price', floatval($sum));
			update_post_meta($post_id, 'counter_t', $t);
			update_post_meta($post_id, 'counter_b', $b);
			update_post_meta($post_id, 'counter_c', $c);
			update_post_meta($post_id, 'counter_g', $g);
			update_post_meta($post_id, 'counter_g', $g);
			update_post_meta($post_id, '_regular_price', floatval($sum));
			update_post_meta($post_id, '_sale_price', floatval($sum));

			$quantity = 1;
			update_post_meta($post_id, 'quantity', $quantity);

			$sqm = $sqm_value;
			if ($sqm > 0) {
				$train_price = get_user_meta($user_id, 'train_price', true);
				if ($train_price === null || $train_price === '') {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				$new_price = floatval($sqm * $quantity) * floatval($train_price);
				update_post_meta($post_id, 'train_delivery', $new_price);
				// $new_price = 0;
			}

			saveCurrentPriceItemApi($post_id, $user_id);

// update_post_meta($post_id, 'svg_product', $_POST['svg']);

			/*  **********************************************************************
			--------------------------  Calcul Suma Dolari --------------------------
			**********************************************************************  */

			$sum = 0;
			$basic = 0;
// Calculate price
			if ($products['property_material'] == 187) {
				$sum = $sqm_value * dolarSumApi('Earth-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 188) {
				$sum = $sqm_value * dolarSumApi('Ecowood-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 137) {
				$sum = $sqm_value * dolarSumApi('Green-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 5) {
				$sum = $sqm_value * dolarSumApi('EcowoodPlus-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 138) {
				$sum = $sqm_value * dolarSumApi('BiowoodPlus-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 6) {
				$sum = $sqm_value * dolarSumApi('Biowood-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 147) {
				$sum = $sqm_value * dolarSumApi('Basswood-dolar', $user_id);
				// to_document echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// to_document echo 'BASIC 1 dolar: ' . $basic . '<br>';
			}
			if ($products['property_material'] == 139) {
				$sum = $sqm_value * dolarSum('BasswoodPlus-dolar', $user_id);
				echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
			}
			if ($products['property_material'] == 147) {
				$sum = $sqm_value * dolarSum('Basswood-dolar', $user_id);
				echo 'SUM 1 dolar: ' . $sum . ' ..... ';
				$basic = $sum;
				// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
			}
			

//style

			if ($products['property_material'] == 139 || $products['property_material'] == 147) {
				if ($products['property_bladesize'] == 52) {
					$sum = $sum + (dolarSumApi('Flat_Louver-dolar', $user_id) * $basic) / 100;
					// to_document echo 'SUM bladesize: ' . $sum . ' ..... ';
					// to_document echo 'BASIC bladesize: ' . $basic . '<br>';
				}
			}

			if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232) || ($products['property_style'] == 38) || ($products['property_style'] == 39) || $products['property_style'] == 42 || $products['property_style'] == 43) {
				$val = dolarSumApi('Solid-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 2: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 2: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 33 || $products['property_style'] == 43) {
				$val = dolarSumApi('Shaped-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 3: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 3: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 34) {
				$val = dolarSumApi('French_Door-dolar', $user_id);
				$sum = $sum + $val;
				// to_document echo 'SUM 3: ' . $sum . ' ..... ';
				// to_document echo '<br>BASIC 3: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 35) {
				$val = dolarSumApi('Tracked-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 4: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 4: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 37) {
				$val = dolarSumApi('TrackedByPass-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 4 dolar: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 4: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 36 || $products['property_style'] == 42) {
				$val = dolarSumApi('Arched-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM Arched 3: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 3: ' . $basic . '<br>';
			}
			if ($products['property_style'] == 229 || $products['property_style'] == 233) {
				$val = dolarSumApi('Combi-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 10: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 10: ' . $basic . '<br>';
			}

// Frame Type
			if ($products['property_frametype'] == 171) {

				//$sum = get_post_meta(1,'Combi',true)/1;
				$val = dolarSumApi('P4028X-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM P4028X: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 10: ' . $basic . '<br>';

				$blackoutblind = dolarSumApi('blackoutblind-dolar', $user_id);
				if (!empty($blackoutblind) || ($blackoutblind > 0)) {

					$sum = $sum + $blackoutblind * $sqm_value_blackout;
					// to_document echo 'SUM blackoutblind: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 10: ' . $basic . '<br>';
				}

				if ($products['property_tposttype']) {
					$tposttype_blackout = dolarSumApi('tposttype_blackout-dolar', $user_id);
					if (!empty($tposttype_blackout) || ($tposttype_blackout > 0)) {
						$sum = $sum + ($tposttype_blackout * $basic) / 100;
						// to_document echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 10: ' . $basic . '<br>';
					}
				}

				if (!empty($products['bay-post-type'])) {
					$bposttype_blackout = dolarSumApi('bposttype_blackout-dolar', $user_id);
					if (!empty($bposttype_blackout) || ($bposttype_blackout > 0)) {
						$sum = $sum + ($bposttype_blackout * $basic) / 100;
						// to_document echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 10: ' . $basic . '<br>';
					}
				}
			}
			if ($products['property_frametype'] == 322) {
				$val = dolarSumApi('P4008T-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM P4008T: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 10: ' . $basic . '<br>';
			}
			if ($products['property_frametype'] == 353) {
				$val = dolarSumApi('4008T-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 4008T: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 10: ' . $basic . '<br>';
			}
			if ($products['property_frametype'] == 319) {
				$val = dolarSumApi('P4008W-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM P4008W: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 10: ' . $basic . '<br>';
			}

			if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
				$frdepth = $framesType[$products['property_frametype']];
				$sum_build_frame = $frdepth + $products['property_builtout'];
				if ($sum_build_frame <= 100) {
					$val = dolarSumApi('Buildout-dolar', $user_id);
					if (!empty($val) || ($val > 0)) {
						$sum = $sum + ($val * $basic) / 100;
						// to_document echo 'SUM Buildout: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 2: ' . $basic . '<br>';
					}
				} elseif ($sum_build_frame > 100) {
					update_post_meta($post_id, 'sum_build_frame', true);
					$sum = $sum + (20 * $basic) / 100;
					// to_document echo 'SUM Buildout: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 5: ' . $basic . '<br>';
				}
			}
			if ($products['property_controltype'] == 403) {
				$result = 0;
				if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) || (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
					$result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
					$sum = $sum + ($basic * $result) / 100;
				} else {
					$result = get_post_meta(1, 'Concealed_Rod-dolar', true);
					$sum = $sum + ($basic * $result) / 100;
//					$sum = $sum + $result * $sqm_value;
				}
				// to_document echo 'SUM 6: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 6: ' . $basic . '<br>';
			}
			if ($products['property_controltype'] == 387) {
				$result = 0;
				if (!empty(get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true)) || (get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true) > 0)) {
					$result = get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true);
					$sum = $sum + ($basic * $result) / 100;
				} else {
					$result = get_post_meta(1, 'Hidden_Rod_with_Locking_System-dolar', true);
					$sum = $sum + $result * $sqm_value;
				}
				// to_document echo 'SUM 6: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 6: ' . $basic . '<br>';
			}
			if ($products['property_hingecolour'] == 93) {
				$val = dolarSumApi('Stainless_Steel-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 6: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 6: ' . $basic . '<br>';
			}
			if ($products['property_hingecolour'] == 186) {
				$val = dolarSumApi('Hidden-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				// to_document echo 'SUM 6: ' . $sum . ' ..... ';
				// to_document echo 'BASIC 6: ' . $basic . '<br>';
			}
			$bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
			$bpost_unghi = 0;
			foreach ($bayangle as $property_ba) {
				if (!empty($property_ba)) {
					if (!empty($products[$property_ba]) && $products['bay-post-type'] == 'normal') {
						if ($products[$property_ba] == 90 || $products[$property_ba] == 135) {
							// // to_document echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
						} else {
							$val = dolarSumApi('Bay_Angle-dolar', $user_id);
							$sum = $sum + ($val * $basic) / 100;
							// to_document echo 'SUM 7: ' . $sum . ' ..... ';
							// to_document echo 'BASIC 7: ' . $basic . '<br>';
							// // to_document echo '----- Unghi diferit de  90: '.$property_ba.' ADAUGARE 10%------';
							$bpost_unghi++;
							break;
						}
					}
				}
			}

			if (!empty($products['bay-post-type'])) {

				if ($products['bay-post-type'] == 'flexible') {
					$val = dolarSumApi('B_typeFlexible-dolar', $user_id);
					$sum = $sum + ($val * $basic) / 100;
					// to_document echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 7: ' . $basic . '<br>';
				}
			}

// If Bpost have buidout add 7%
			$b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
			foreach ($b_buildout as $property_bb) {
				if (!empty($property_bb)) {
					if (!empty($products[$property_bb])) {
						$val = dolarSumApi('B_Buildout-dolar', $user_id);
						$sum = $sum + ($val * $basic) / 100;
						// to_document echo 'SUM 7: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}

// If tpost have buidout add 7%
			$t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
			foreach ($t_buildout as $property_tb) {
				if (!empty($property_tb)) {
					if (!empty($products[$property_tb])) {
						$val = dolarSumApi('T_Buildout-dolar', $user_id);
						$sum = $sum + ($val * $basic) / 100;
						// to_document echo 'SUM 7: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}

			if ($products['property_nr_sections']) {
				for ($i = 1; $i <= 10; $i++) {
					$property_tb = 'property_t_buildout1_' . $i;
					if (!empty($products[$property_tb])) {
						$val = dolarSumApi('T_Buildout-dolar', $user_id);
						$sum = $sum + ($val * $basic) / 100;
						// to_document echo 'SUM 7: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}
// If Cpost have buidout add 7%
			$c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
			foreach ($c_buildout as $property_cb) {
				if (!empty($property_cb)) {
					if (!empty($products[$property_cb])) {
						$val = dolarSumApi('C_Buildout-dolar', $user_id);
						$sum = $sum + ($val * $basic) / 100;
						// to_document echo 'SUM 7: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}

// Stained BASSWOOD 14.57%
			if ($products['property_material'] == 139 || $products['property_material'] == 147) {
				if (($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125)) {
					$sum = $sum + (14.57 * $basic) / 100;
					// to_document echo 'SUM 11: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 11: ' . $basic . '<br>';
				}
			}

// Brushed BIOWOOD 35.64%
			if ($products['property_material'] == 138) {
				if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
					$sum = $sum + (35.64 * $basic) / 100;
					// to_document echo 'SUM 11: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 11: ' . $basic . '<br>';
				}
			}

// Painted EARTH shutters  6.87%
			if ($products['property_material'] == 187) {
				if (($products['property_shuttercolour'] == 258) || ($products['property_shuttercolour'] == 259) || ($products['property_shuttercolour'] == 260) || ($products['property_shuttercolour'] == 261) || ($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263)) {
					$sum = $sum + (6.87 * $basic) / 100;
					// to_document echo 'SUM 11: ' . $sum . ' ..... ';
					// to_document echo 'BASIC 11: ' . $basic . '<br>';
				}
			}

			if (!empty($products['property_ringpull'] && $products['property_ringpull'] == 'Yes')) {
				$val = dolarSumApi('Ringpull-dolar', $user_id);
				$sum = $sum + ($val * $products['property_ringpull_volume']);
				// to_document echo 'SUM 8: ' . $sum . ' ..... ';
			}

			if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {
				$val = dolarSumApi('Lock-dolar', $user_id);
				$sum = $sum + ($val * $products['property_locks_volume'] * 2);
				// to_document echo 'SUM 8: ' . $sum . ' ..... ';
			}
			$nrPanels = nrPanelsCountApi($products['property_layoutcode']);
			
			if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
				$val = dolarSumApi('Spare_Louvres-dolar', $user_id);
				$sum = $sum + ($nrPanels * $val);
				// to_document echo 'SUM 9: ' . $sum . ' ..... ';
			}

// if layout have b t c, calculatePanelsWidthApi have panels with width < 200 then add to price 30,
			if ($products['property_layoutcode'] == 'B' || $products['property_layoutcode'] == 'T' || $products['property_layoutcode'] == 'C') {
				$panels_width = calculatePanelsWidthApi($products['property_layoutcode'], $products['property_width'], $products);
				// if panels_width have width < 200 then add 30 for each panel
				foreach ($panels_width as $panel) {
					if ($panel < 200) {
						$sum = $sum + get_post_meta(1, 'dolar_price-panel_width_price', true);
						// to_document echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
						// to_document echo 'BASIC 8: ' . $basic . '<br>';
					}
				}
			} else if ($width_track / nrPanelsCountApi($products['property_layoutcode']) < 200) {
				$nrPanels = nrPanelsCountApi($products['property_layoutcode']);
				$sum = $sum + $nrPanels * get_post_meta(1, 'dolar_price-panel_width_price', true);
				// to_document echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
			}

			if ($products['property_motorized'] == 'Yes') {
				$property_nrMotors = get_post_meta($post_id, 'property_nrMotors', true);
				$price_notor = get_post_meta(1, 'motor-dolar', true);
				$property_nrRemotes = get_post_meta($post_id, 'property_nrRemotes', true);
				$price_remote = get_post_meta(1, 'remote-dolar', true);
				$sum = $sum + $property_nrMotors * $price_notor;
				$sum = $sum + $property_nrRemotes * $price_remote;
			}

//// print_r($frames);
			// to_document echo "Suma_total DOLARS: " . $sum . '<br><br><br>';

			update_post_meta($post_id, 'dolar_price', floatval($sum));
			/*  **********************************************************************
			--------------------------   END - Calcul Suma Dolari --------------------------
			**********************************************************************  */

			// set shipping class default for product
			$product = wc_get_product($post_id);
			$int_shipping = get_term_by('slug', 'ship', 'product_shipping_class');
			$product->set_shipping_class_id($int_shipping->term_id);
			$product->save();

			$product_ids[] = $post_id;
			$products_images[] = get_post_meta($post_id, 'pictures', true);
			update_post_meta($post_id, 'attached_cart_id', $app_cart_id);
		}
	}

	$sanitized_product_ids = array_filter($product_ids, function ($product_id) {
		return is_numeric($product_id) && $product_id > 0;
	});

	// Update the attached product IDs in the newly created appCart post meta
	update_post_meta($app_cart_id, 'attached_product_ids', $sanitized_product_ids);
	update_post_meta($app_cart_id, 'products_images', $products_images);

	// Set response message for the created product
	$response[] = array(
	  'post_id' => $post_id,
	  'message' => 'Product inserted successfully',
	);

	// Return the response
	return new WP_REST_Response($response, 200);
}


if (!function_exists('transform_new_to_old')) {
	function transform_new_to_old($object)
	{

		$fields = array(
		  "shutterCategory" => array("old_key" => "shutter_category", "has_attribute" => false),
		  "name" => array("old_key" => 'property_room_other', "has_attribute" => false),
			// Exemplu de câmp vechi, nou: "material" va deveni "property_material" (tratată ca atribut)
//		  "material"      => array("old_key" => "property_material", "has_attribute" => true),
			// Noile câmpuri care se termină cu "Id" vor avea proprietatea 'has_id' => true
		  "materialId" => array("old_key" => "property_material", "has_id" => true),
			// Restul câmpurilor din exemplul original...
//		  "shutterType" => array("old_key" => 'property_style', "has_attribute" => true),
		  "shutterTypeId" => array("old_key" => 'property_style', "has_id" => true),
		  "height" => array("old_key" => "property_height", "has_attribute" => false),
		  "width" => array("old_key" => "property_width", "has_attribute" => false),
		  "midrailHeight" => array("old_key" => "property_midrailheight", "has_attribute" => false),
		  "midrailHeight2" => array("old_key" => "property_midrailheight2", "has_attribute" => false),
		  "hiddenDivider" => array("old_key" => "property_midraildivider1", "has_attribute" => false),
		  "hiddenDivider2" => array("old_key" => "property_midraildivider2", "has_attribute" => false),
		  "tierOnTierHeight" => array("old_key" => "property_totheight", "has_attribute" => false),
		  "horizontalTPost" => array("old_key" => "property_horizontaltpost", "has_attribute" => false),
		  "solidType" => array("old_key" => "property_solidtype", "has_attribute" => false),
		  "solidPanelHeight" => array("old_key" => "property_solidpanelheight", "has_attribute" => false),
		  "trackInstallationType" => array("old_key" => "property_trackedtype", "has_attribute" => false),
		  "by-passType" => array("old_key" => "property_bypasstype", "has_attribute" => false),
		  "lightBlocks" => array("old_key" => "property_lightblocks", "has_attribute" => false),
		  "freeFolding" => array("old_key" => "property_freefolding", "has_attribute" => false),
		  "doubleClosingLouvres" => array("old_key" => "property_double_closing_louvres", "has_attribute" => false),
		  "positionIsCritical" => array("old_key" => "property_midrailpositioncritical", "has_attribute" => true),
//		  "louvreSize" => array("old_key" => "property_bladesize", "has_attribute" => false),
		  "louvreSizeId" => array("old_key" => "property_bladesize", "has_id" => false),
//		  "measureType" => array("old_key" => "property_fit", "has_attribute" => false),
		  "measurementTypeId" => array("old_key" => "property_fit", "has_id" => true),
		  "openDrawingPad" => array("old_key" => "openDrawingPad", "has_attribute" => false),
		  "uploadDrawing" => array("old_key" => "attachmentDraw", "has_attribute" => false),

			// Additional fields
//		  "frameCode" => array("old_key" => "property_frametype", "has_attribute" => true),
		  "frameId" => array("old_key" => "property_frametype", "has_id" => true),
//		  "frameLeft" => array("old_key" => "property_frameleft", "has_attribute" => true),
		  "frameLeftId" => array("old_key" => "property_frameleft", "has_id" => true),
//		  "frameRight" => array("old_key" => "property_frameright", "has_attribute" => true),
		  "frameRightId" => array("old_key" => "property_frameright", "has_id" => true),
//		  "frameTop" => array("old_key" => "property_frametop", "has_attribute" => true),
		  "frameTopId" => array("old_key" => "property_frametop", "has_id" => true),
//		  "frameBottom" => array("old_key" => "property_framebottom", "has_attribute" => true),
		  "frameBottomId" => array("old_key" => "property_framebottom", "has_id" => true),
		  "buildoutSize" => array("old_key" => "property_builtout", "has_attribute" => false),
		  "buildout" => array("old_key" => "addBuildout", "has_attribute" => false),
//		  "stileSize" => array("old_key" => "property_stile", "has_attribute" => true),
		  "stileId" => array("old_key" => "property_stile", "has_id" => true),

			// More fields
//		  "hingeColour" => array("old_key" => "property_hingecolour", "has_attribute" => true),
		  "hingeColourId" => array("old_key" => "property_hingecolour", "has_id" => true),
//		  "shutterColour" => array("old_key" => "property_shuttercolour", "has_attribute" => true),
		  "shutterColourId" => array("old_key" => "property_shuttercolour", "has_id" => true),
		  "customColor" => array("old_key" => "property_shuttercolour_other", "has_attribute" => false),
//		  "controlType" => array("old_key" => "property_controltype", "has_attribute" => true),
		  "controlTypeId" => array("old_key" => "property_controltype", "has_id" => true),
		  "individualCounter" => array("old_key" => "individual_counter", "has_attribute" => false),
		  "layout" => array("old_key" => "property_layoutcode", "has_attribute" => false),
		  "lr" => array("old_key" => "property_opendoor", "has_attribute" => false),
		  "motorised" => array("old_key" => "property_motorized", "has_attribute" => false),

			// B-Post fields
		  'bPost1' => array("old_key" => 'property_bp1', "has_attribute" => false),
		  'bayAngle1' => array("old_key" => 'property_ba1', "has_attribute" => false),
		  'bPost2' => array("old_key" => 'property_bp2', "has_attribute" => false),
		  'bayAngle2' => array("old_key" => 'property_ba2', "has_attribute" => false),
		  'bPost3' => array("old_key" => 'property_bp3', "has_attribute" => false),
		  'bayAngle3' => array("old_key" => 'property_ba3', "has_attribute" => false),
		  'bPost4' => array("old_key" => 'property_bp4', "has_attribute" => false),
		  'bayAngle4' => array("old_key" => 'property_ba4', "has_attribute" => false),
		  'bPost5' => array("old_key" => 'property_bp5', "has_attribute" => false),
		  'bayAngle5' => array("old_key" => 'property_ba5', "has_attribute" => false),
		  'bPostType' => array("old_key" => 'bay-post-type', "has_attribute" => false),
		  'bPostBuildout' => array("old_key" => 'property_b_buildout1', "has_attribute" => false),

			// C-Post fields
		  'cPost1' => array("old_key" => 'property_c1', "has_attribute" => false),
		  'cPost2' => array("old_key" => 'property_c2', "has_attribute" => false),
		  'cPost3' => array("old_key" => 'property_c3', "has_attribute" => false),
		  'cPost4' => array("old_key" => 'property_c4', "has_attribute" => false),
		  'cPost5' => array("old_key" => 'property_c5', "has_attribute" => false),
		  'cPostType' => array("old_key" => 'c-post-type', "has_attribute" => false),
		  'cPostBuildout' => array("old_key" => 'property_c_buildout1', "has_attribute" => false),

			// T-Post fields
		  'tPostBuildoutWidth' => array("old_key" => 'property_tp1', "has_attribute" => false),
		  'tPost1' => array("old_key" => 'property_t1', "has_attribute" => false),
		  'tPost2' => array("old_key" => 'property_t2', "has_attribute" => false),
		  'tPost3' => array("old_key" => 'property_t3', "has_attribute" => false),
		  'tPost4' => array("old_key" => 'property_t4', "has_attribute" => false),
		  'tPost5' => array("old_key" => 'property_t5', "has_attribute" => false),
		  'tPostType' => array("old_key" => 't-post-type', "has_attribute" => false),
		  'tPostCode' => array("old_key" => 'property_tposttype', "has_attribute" => true),
		  'tPostBuildout' => array("old_key" => 'property_t_buildout1', "has_attribute" => false),

			// G-Post fields
		  'gPost1' => array("old_key" => 'property_g1', "has_attribute" => false),
		  'gPost2' => array("old_key" => 'property_g2', "has_attribute" => false),
		  'gPost3' => array("old_key" => 'property_g3', "has_attribute" => false),
		  'gPost4' => array("old_key" => 'property_g4', "has_attribute" => false),
		  'gPost5' => array("old_key" => 'property_g5', "has_attribute" => false),
		  'gPostType' => array("old_key" => 'g-post-type', "has_attribute" => false),
		  'gPostBuildout' => array("old_key" => 'property_g_buildout1', "has_attribute" => false),

			// Additional fields
		  "spareLouvres" => array("old_key" => "property_sparelouvres", "has_attribute" => false),
		  "topBottomLocks" => array("old_key" => "property_locks", "has_attribute" => false),
		  "notes" => array("old_key" => "comments_customer", "has_attribute" => false),
		  "nrSections" => array("old_key" => "property_nr_sections", "has_attribute" => false),
		  "outOfWarranty" => array("old_key" => "property_nowarranty", "has_attribute" => false),
		);

		$fields_old = array(
		  'post_type',
		  'customer_order',
		  'property_total',
		  'product_id_updated',
		  'customer_id',
		  'dealer_id',
		  'edit_customer',
		  'order_edit',
		  'cart_items_name',
		  'property_frametype',
		  'property_room_other',
		  'property_material',
		  'product_id',
		  'property_style',
		  'attachmentDraw',
		  'attachment',
		  'property_width',
		  'property_height',
		  'property_midrailheight',
		  'property_midrailheight2',
		  'property_midraildivider1',
		  'property_midraildivider2',
		  'property_solidpanelheight',
		  'property_midrailpositioncritical',
		  'property_totheight',
		  'property_bladesize',
		  'property_louver_lock',
		  'property_freefolding',
		  'property_lightblocks',
		  'property_double_closing_louvres',
		  'property_fit',
		  'property_frameleft',
		  'property_frameright',
		  'property_frametop',
		  'property_framebottom',
		  'property_builtout',
		  'property_stile',
		  'property_hingecolour',
		  'property_shuttercolour',
		  'property_shuttercolour_other',
		  'property_controltype',
		  'property_layoutcode',
		  'property_layoutcode_tracked',
		  'property_tracksnumber',
		  'property_opendoor',
		  'property_motorized',
		  'property_sparelouvres',
		  'property_ringpull',
		  'property_ringpull_volume',
		  'property_locks',
		  'property_central_lock',
		  'quantity_split',
		  'shutter_svg',
		  'comments_customer',
		  'quantity',
		  'page_title',
		  'panels_left_right',
		  'property_extra_column',
		);

		$atributes_old_matrix = array(1 => '', 2 => '', 3 => '', 4 => '', 5 => 'Ecowood Plus', 6 => 'Biowood', 7 => '', 8 => '', 9 => '', 10 => '', 11 => '', 12 => '', 13 => ' ', 14 => '', 15 => '', 16 => '', 17 => '', 18 => '', 19 => '', 20 => '', 21 => '', 22 => '', 23 => '', 24 => '', 25 => '', 26 => '', 27 => 'ALU Panel Only', 28 => 'ALU Fixed Shutter', 29 => 'Full Height', 30 => 'Café Style', 31 => 'Tier-on-Tier', 32 => 'Bay Window', 33 => 'Special Shaped', 34 => 'French Door Cut', 35 => 'Tracked By-Fold', 36 => 'Arched Shape', 37 => 'Tracked By-Pass', 38 => 'Solid Tracked By-Pass', 39 => 'Solid Tracked By-Fold', 40 => 'Combi Tracked By-Pass', 41 => 'Combi Tracked By-Fold', 42 => 'Solid Arched Shaped', 43 => 'Solid Special Shaped', 44 => '', 45 => '', 46 => '', 47 => '', 48 => '', 49 => '', 50 => '', 51 => '', 52 => 'Flat Louver', 53 => '63.5mm', 54 => '76.2mm', 55 => '88.9mm', 56 => 'inside', 57 => 'outside', 58 => '', 59 => 'F', 60 => 'L70', 61 => 'C', 62 => 'D', 63 => 'K', 64 => 'O', 65 => 'Q', 66 => 'N', 67 => 'M', 68 => '', 69 => '', 70 => 'Yes', 71 => 'No', 72 => 'Sill', 73 => 'lightblock', 74 => 'none', 75 => 'Yes', 76 => 'No', 77 => 'Sill', 78 => 'lightblock', 79 => 'none', 80 => 'Yes', 81 => 'No', 82 => 'Sill', 83 => 'lightblock', 84 => 'none', 85 => 'Yes', 86 => 'No', 87 => 'Sill', 88 => 'lightblock', 89 => 'none', 90 => 'White', 91 => 'Brass', 92 => 'Antique Brass', 93 => 'Stainless Steel (+10%)', 94 => 'Other', 95 => '', 96 => 'Clearview', 97 => 'Centre Rod', 98 => 'Off Centre Rod', 99 => 'Centre Rod Split', 100 => 'Off Centre Rod Split', 101 => 'LS 601 PURE WHITE', 102 => '', 103 => 'LS 003 SILK WHITE', 104 => 'LS 630 MOST WHITE', 105 => 'LS 637 HOG BRISTLE', 106 => 'LS 609 CHAMPAGNE', 107 => 'LS 105 PEARL', 108 => 'LS 618 ALABASTER', 109 => 'LS 619 CREAMY', 110 => 'LS 632 MISTRA', 111 => 'LS 910 JET BLACK (+20%)', 112 => 'LS 615 CLASSICAL WHITE', 113 => 'LS 617 New EGGSHELL', 114 => 'LS 620 LIME WHITE', 115 => 'LS 621 SAND', 116 => 'LS 622 STONE', 117 => 'LS 032 SEA MIST', 118 => 'LS 049 STONE GREY', 119 => 'LS 051 BROWN GREY', 120 => 'LS 053 CLAY', 121 => 'LS 072 MATTINGLEY 267', 122 => 'LS 108 RUSTIC GREY', 123 => 'LS 109 WEATHERED TEAK', 124 => 'LS 110 CHIQUE WHITE', 125 => 'LS 114 TAUPE', 126 => 'LS 202 GOLDEN OAK', 127 => 'LS 204 OAK MANTEL', 128 => 'LS 205 GOLDENROD', 129 => 'LS 211 CHERRY', 130 => 'LS 212 DARK TEAK', 131 => 'LS 214 COCOA', 132 => 'LS 215 CORDOVAN', 133 => 'LS 219 MAHOGANY', 134 => 'LS 220 NEW EBONY', 135 => 'Top track', 136 => 'Track in Board', 137 => 'Green', 138 => 'Biowood Plus', 139 => 'BasswoodPlus', 140 => 'P', 141 => 'H', 142 => '4009 Camber deco frame', 143 => 'Track in Board', 144 => 'Bottom M Track', 145 => 'Other', 146 => 'Bay Window Tier-on-Tier', 147 => 'Basswood', 148 => '', 149 => '', 150 => 'L90', 151 => 'M Track', 152 => '50.8mm Butt Flat', 153 => '50.8mm Rebated Flat', 154 => '50.8mm Butt Beaded', 155 => '50.8mm Rebated Beaded', 156 => '50.8mm Astragal Flat', 157 => '50.8mm Astragal Beaded', 158 => '38.1mm Butt Flat', 159 => '38.1mm Rebated Flat', 160 => '38.1mm Butt Beaded', 161 => '38.1mm Rebated Beaded', 162 => '38.1mm Astragal Flat', 163 => '38.1mm Astragal Beaded', 164 => '50.8mm', 165 => '114.3mm', 166 => 'LS 221 BLACK WALNUT', 167 => 'Pearl', 168 => 'Brushed Nickel', 169 => 'Yes', 170 => 'No', 171 => 'P4028X F frame/Honeycomb BO Blind', 172 => 'Sea Mist', 173 => 'L50MF', 174 => 'Bisque', 175 => 'N201 - Champagne', 176 => '532 - Dusk', 177 => '', 178 => '', 179 => '', 180 => 'Security S/S Hinge (+10%)', 181 => '', 182 => '', 183 => '', 184 => '', 185 => '', 186 => 'Hidden', 187 => 'Earth', 188 => 'Ecowood', 189 => '', 190 => '', 191 => '', 192 => '', 193 => '', 194 => '', 195 => '', 196 => '', 197 => '', 198 => '', 199 => '', 200 => '', 201 => '', 202 => '', 203 => '', 204 => '', 205 => '', 206 => '', 207 => '', 208 => '', 209 => '', 210 => '', 211 => '', 212 => '', 213 => '', 214 => '', 215 => '', 216 => '', 217 => '', 218 => '', 219 => '', 220 => 'LS 227 RED OAK', 221 => 'Solid Flat Panel', 222 => 'Solid Raised Panel', 223 => 'Hidden Tilt', 224 => 'Hidden Tilt Split', 225 => 'Café Style Bay Window', 226 => 'Solid Raised Café Style', 227 => 'Solid Flat Tier-on-Tier', 228 => 'Solid Raised Tier-on-Tier', 229 => 'Solid Combi Panel', 230 => 'Solid Panel Bay Window Full Height', 231 => 'Solid Panel Bay Window Tier-on-Tier', 232 => 'Solid Panel Bay Window Cafe Style', 233 => 'Solid Combi Panel Bay Window', 234 => '', 235 => '', 236 => '', 237 => '', 238 => '', 239 => '', 240 => '', 241 => '', 242 => '', 243 => '', 244 => '', 245 => '', 246 => '', 247 => '', 248 => '', 249 => '', 250 => '', 251 => '', 252 => '', 253 => 'LS 229 RICH WALNUT', 254 => 'LS 230 OLD TEAK', 255 => 'LS 232 RED MAHOGANY', 256 => 'LS 237 WENGE', 257 => 'LS 862 FRENCH OAK', 258 => 'A600 (WHITE)', 259 => 'A103 (PEARL)', 260 => 'A700 (BLACK)', 261 => 'A800 (SILVER)', 262 => 'A202 (LIGHT CEDAR)', 263 => 'A203 (GOLDEN OAK )', 264 => 'P601 WHITE BRUSHED (+20%)', 265 => 'P603 VANILLA BRUSHED (+20%)', 266 => 'P630 WINTER WHITE BRUSHED (+20%)', 267 => 'P631 STONE BRUSHED (+20%)', 268 => 'P632 MISTRAL BRUSHED (+20%)', 269 => 'P615 CLASSICAL WHITE BRUSHED (+20%)', 270 => 'P910 JET BLACK BRUSHED (+20%)', 271 => 'P817 OLD TEAK BRUSHED (+20%)', 272 => 'P819 COFFEE BEAN BRUSHED (+20%)', 273 => 'PS-1 HONEY BRUSHED (+20%)', 274 => 'A200 (BLACK WALNUT) (+10%)', 275 => '', 276 => '', 277 => '', 278 => '', 279 => '', 280 => '', 281 => '', 282 => '', 283 => '', 284 => '', 285 => '', 286 => '', 287 => '', 288 => '', 289 => 'P4023B', 290 => '4011B', 291 => 'U-Channel', 300 => 'A4001 Frame A4001', 301 => 'A4002 Z Frame', 302 => 'A4027', 303 => 'P4009 Camber deco frame', 304 => 'P4083', 305 => '4001A 46mm single beaded L frame', 306 => '4007A 50.8mm plain L frame', 307 => '4008A 46mm insert L frame', 308 => '4001B 63.5mm single beaded L frame', 309 => '4007B 63.5mm plain L frame', 310 => '4008B 63.5mm insert L frame', 311 => '4001C 76.2mm single beaded L frame', 312 => '4007C 76.2mm plain L frame', 313 => '4008C 76.2mm insert L frame', 314 => '4003 Bullnose Z frame', 315 => '4004 Crown Z frame', 316 => '4013 Beaded Z frame with insert', 317 => '4014 Crown Z frame with insert', 318 => 'P4028B 60mm double insert L frame', 319 => 'P4008W 100mm L insert frame', 320 => 'P4001N 46mm single beaded L frame', 321 => 'P4008H 50.8mm insert L frame', 322 => 'P4008T 87mm insert L frame', 323 => 'P4008K', 324 => 'P4073 Plain Z frame', 325 => 'P4013 Beaded Z frame', 326 => 'P4023', 327 => 'P4033 Bull nose Z frame', 328 => 'P4043 Tiara Z frame', 329 => 'P4014 Crown Z frame with insert', 330 => 'P4008S 72mm insert L frame', 331 => 'P4007A 50.8mm plain L frame', 332 => '4022B 60mm side insert L frame', 333 => '4028B 60mm double insert L frame', 350 => 'A1002D (Std.beaded stile)', 351 => 'P4022B 60mm side insert L frame', 352 => '4024 Large Z frame with insert', 353 => '4008T 100mm L insert frame', 354 => '60mm A1006D (beaded D-mould)', 355 => '1001B(51mm plain butt)', 356 => '1005B(51mm plain D-mould)', 357 => '1002B(51mm beaded butt)', 358 => '1006B(51mm beaded D-mould)', 359 => '1004B(51mm beaded rebate)', 360 => '1003B(51mm plain rebate)', 361 => '1001A(35mm plain butt)', 362 => '1005A(35mm plain D-mould)', 363 => '1002A(35mm beaded butt)', 364 => '1006A(35mm beaded D-mould)', 365 => '1004A(35mm beaded rebate)', 366 => '1003A(35mm plain rebate)', 367 => '', 368 => '', 369 => '', 370 => 'T1001K(51mm plain butt)', 371 => 'T1005K(51mm plain D-mould)', 372 => 'T1002K(51mm beaded butt)', 373 => 'T1006K(51mm beaded D-mould)', 374 => 'T1004K(51mm beaded rebate)', 375 => 'T1003K(51mm plain rebate)', 376 => '41mm 1001M(plain butt)', 377 => '41mm 1005M(plain D-mould)', 378 => '41mm 1003M(plain rebate)', 379 => '', 380 => 'PVC-P1001B(51mm plain butt)', 381 => 'PVC-P1005B(51mm plain D-mould)', 382 => 'PVC-P1002B(51mm beaded butt)', 383 => 'PVC-P1006B(51mm beaded D-mould)', 384 => 'PVC-P1004E(51mm beaded rebate)', 385 => 'PVC-P1003E(51mm  plain rebate)', 386 => '', 387 => 'Hidden Tilt with Louver Lock', 388 => 'Fixed Louvers', 389 => '', 390 => 'Frame Only', 400 => 'Centre rod', 401 => 'Hidden tilt', 402 => 'Offset rod', 403 => 'Concealed tilt', 405 => '101 - Snow White', 406 => 'K077 - Creamy', 407 => '616 - Sand', 408 => '231 - Gold', 409 => '841 - Terracotta', 410 => '310 - Gun Metal', 411 => 'Frosted White', 412 => 'Neutral White',  414 => 'Vanilla', 415 => 'Shell White', 416 => '610 - BLACK', 417 => '', 418 => '', 419 => '', 420 => '4108C 76.2mm insert L frame', 421 => 'P4009 Camber deco frame', 422 => '', 423 => '', 424 => '', 425 => '', 426 => '', 427 => '', 428 => '', 429 => '', 430 => '', 431 => '', 432 => '', 433 => '', 434 => '', 435 => '', 436 => '', 437 => 'P7032', 438 => '7001', 439 => 'P7201', 440 => 'Black', 441 => '7011', 442 => 'A7001', 443 => '7032', 444 => 'P7030', 445 => '41mm T1002M(beaded butt)', 446 => '41mm T1004M(beaded rebate)', 447 => '41mm T1006M(beaded D-mould)', 457 => 'A400 (Surfmist)', 456 => 'A500 (Antracit)', 458 => 'Earth hinge');

		// Create a reverse matrix with lowercase keys for case-insensitive mapping
		$reverse_matrix = [];
		foreach ($atributes_old_matrix as $key => $value) {
			if (is_string($value)) {
				$reverse_matrix[strtolower($value)] = $key;
			}
		}

		// Initialize an empty array to hold the transformed data
		$transformed_data = [];

		// Ensure the provided $object is an array
		if (!is_array($object)) {
			return $transformed_data;
		}

		// Separați cheile cu sufixul "Id" (sau cu proprietatea 'has_id' true) și celelalte
		$keys_with_id = array();
		$keys_without_id = array();

		foreach ($object as $new_key => $value) {
			if ((substr($new_key, -2) === 'Id') || (isset($fields[$new_key]) && isset($fields[$new_key]['has_id']) && $fields[$new_key]['has_id'] === true)) {
				$keys_with_id[$new_key] = $value;
			} else {
				$keys_without_id[$new_key] = $value;
			}
		}

		// --- Faza 1: Procesăm câmpurile cu "Id" (au prioritate) ---
		foreach ($keys_with_id as $new_key => $value) {
			if (isset($fields[$new_key])) {
				$field_data = $fields[$new_key];
				$old_key = $field_data['old_key'];
				if ($new_key == 'frameId' && $value == 0) {
					$value = 171;
				}
				// Pentru câmpurile cu "has_id", presupunem că valoarea este deja un id (index)
				// Dacă e nevoie se poate verifica dacă există în $atributes_old_matrix
				if (isset($atributes_old_matrix[$value])) {
					$transformed_data[$old_key] = $value;
				} else {
					// Dacă nu se găsește, puteți decide fie să lăsați valoarea așa cum este,
					// fie să o transformați (aici o lăsăm așa)
					$transformed_data[$old_key] = $value;
				}
			}
		}

		// Iterate through the provided object
		foreach ($keys_without_id as $new_key => $value) {
			if($new_key == 'pictures'){
				$transformed_data['pictures'] = $keys_without_id['pictures'];
			}

			// Check if the key is one of the nested fields that needs another foreach (recursive call)
			if (in_array($new_key, ['design', 'frameStile', 'layout', 'cch', 'price']) && is_array($value)) {
				// Recursively transform the nested object
//			$transformed_data[$new_key] = transform_new_to_old($value);
				foreach ($value as $key => $val) {
					if (isset($fields[$key])) {

						$field_data = $fields[$key];
						$old_key = $field_data['old_key'];

						if($value['tPost1'] == '' && $key == 'tPostType') {
							$value['tPostType'] = '';
							continue;
						}
						//bPostType
						if($value['bPost1'] == '' && $key == 'bPostType') {
							$value['bPostType'] = '';
							continue;
						}

						// Check if the field has an attribute and needs to be reverse transformed
						if ($field_data['has_attribute']) {
							if ($key == 'tPostBuildout' && $val == false) {
								continue;
							}
							if ($key == 'bPostBuildout' && $val == false) {
								continue;
							}
//							$materials_array = array(187 => 'm-earth', 137 => 'm-green', 5 => 'm-ecowood-plus', 138 => 'm-biowood-plus', 6 => 'm-biowood', 139 => 'm-basswood', 188 => 'm-ecowood');
//							// reverse the material
//							$materials_array_reverse = array_flip($materials_array);
//
//							// if value is in $materials_array_reverse, use the mapped value
//							if (isset($materials_array_reverse[$val])) {
//								$val = $materials_array_reverse[$val];
//							}

							// If the attribute value is present in $atributes_old_matrix, use the mapped value
//						if (isset($reverse_matrix[strtolower($val)]) && $key != 'material' && $key != 'louvreSize' && $key != 'hingeColour' && $key != 'controlType' && $key != 'frameType' && $key != 'frameCode' && $key != 'stileSize'  && $key != 'shutterColour') {
							if (isset($reverse_matrix[strtolower($val)]) && $key != 'material' && $key != 'louvreSize') {
								$val = $reverse_matrix[strtolower($val)];
							}
						}

						// Add to the transformed data array using the old key
						$transformed_data[$old_key] = $val;
					}
				}

				if ($new_key == 'design') {
					$width = $value["width"];
					$height = $value["height"];

					$sqm_value = round(($width / 1000) * ($height / 1000), 2);

					$transformed_data['property_total'] = $sqm_value;
				}

				if ($new_key == 'price') {
					$priceInfo = $value["priceInfo"];
					foreach ($priceInfo as $key => $value) {
						$transformed_data[$key] = $value;
					}
				}
			} else {
				// Check if the key has a corresponding old key in the $fields array
				if (isset($fields[$new_key])) {
					$field_data = $fields[$new_key];
					$old_key = $field_data['old_key'];

					// Check if the field has an attribute and needs to be reverse transformed
					if ($field_data['has_attribute']) {
//						$materials_array = array(187 => 'm-earth', 137 => 'm-green', 5 => 'm-ecowood-plus', 138 => 'm-biowood-plus', 6 => 'm-biowood', 139 => 'm-basswood', 188 => 'm-ecowood');
//						// reverse the material
//						$materials_array_reverse = array_flip($materials_array);
//
//						// if value is in $materials_array_reverse, use the mapped value
//						if (isset($materials_array_reverse[$value])) {
//							$value = $materials_array_reverse[$value];
//						}
//						$louvre_size_array = array(52 => '81.2mm', 53 => '63.5mm', 54 => '76.2mm', 55 => '88.9mm', 164 => '50.8mm', 165 => '114.3mm');
//						// reverse the louvre size
//						$louvre_size_array_reverse = array_flip($louvre_size_array);
//						if (isset($louvre_size_array_reverse[$value . 'mm'])) {
//							$value = $louvre_size_array_reverse[$value . 'mm'];
//						}

						// If the attribute value is present in $atributes_old_matrix, use the mapped value
						if (isset($reverse_matrix[strtolower($value)]) && $key != 'material' && $key != 'louvreSize') {
							$value = $reverse_matrix[strtolower($value)];
						}
					}

					// Add to the transformed data array using the old key
					$transformed_data[$old_key] = $value;
				} else {
//				// If there is no specific mapping, add the value as-is
//				$transformed_data[$new_key] = $value;
				}
			}
		}

		// Define which keys should be removed when they evaluate to "No"/false
		$keysToRemove = ['property_t_buildout1', 'property_b_buildout1'];

		foreach ($transformed_data as $key => $value) {
			// 1) Convert booleans to human-readable strings
			if ($value === true) {
				$transformed_data[$key] = 'Yes';
			} elseif ($value === false) {
				// 2) If this key is in our remove-list, drop it entirely
				if (in_array($key, $keysToRemove, true)) {
					unset($transformed_data[$key]);
					continue; // skip further processing for this key
				}
				// 3) Otherwise convert the boolean false to "No"
				$transformed_data[$key] = 'No';
			}
		}

		// Return the transformed data
		return $transformed_data;
	}
}

/*
 * function for add other color earth
 *
 */
if (!function_exists('addOtherColorEarthApi')) {
	function addOtherColorEarthApi($post_id, $products)
	{
		$cart = WC()->cart->get_cart();

		$products_cart = array();
		$other_color_earth = 0;
		$other_color = 0;
		$sqm_order = 0;
		$earth_color_price = 0;
		$other_clrs_array = array();
		$items_array = array();

		// parcurgere cart
		foreach ($cart as $cart_item_key => $cart_item) {
			// get id product
			$product_id = $cart_item['product_id'];
			// make array with products id of cart items
			$products_cart[] = $product_id;
			$have_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);

			// if is not one of the special color id as product then make functions
			if (!in_array($product_id, array(72951, 337))) {
				$items_array[$product_id] = 'no_color';
				// calcualte order sqm for earth other color condition
				// start - calculate sqm total order
				$sqm = get_post_meta($product_id, 'property_total', true);
				if (!empty($sqm)) $sqm_order = (float)$sqm_order + (float)$sqm;
				// end - calculate sqm total order

				// get material
				$materials = array(187 => 'Earth', 137 => 'Green', 5 => 'EcowoodPlus', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'BasswoodPlus', 147 => 'Basswood', 188 => 'Ecowood');
				$material = get_post_meta($product_id, 'property_material', true);
				// calculate price of basic price of item without add
				$basic_price = (float)$sqm * (float)get_post_meta(1, $materials[$material], true);

				$earth_color_price = $earth_color_price + ($basic_price * 0.1);

				/*
				 * Se fac 2 array-uri, unul cu culori existente si unul cu shutters
				 * daca in shutters este unul cu other color si exista in array cu culori
				 * atunci nu se mai face nici o adaugare sau actiune cu culori
				 * daca
				 *
				 */

				if (!empty($have_shuttercolour_other) && $have_shuttercolour_other != '') {
					if ($material == 187) {
						$items_array[$product_id] = 'have_earth_other_color';
					} else {
						$items_array[$product_id] = 'have_other_color';
					}
				}
			} else {
				$other_clrs_array[] = $product_id;
				if ($product_id == 72951) {
					$other_color_earth = $other_color_earth++;
				}
				if ($product_id == 337 || $product_id == 72951) {
					$other_color = $other_color++;
				}
			}
		}

//	print_r($other_clrs_array);
//	print_r($items_array);

//    sendLogShutterModule('other color arrays', $other_clrs_array, 'test_other_color');
//    sendLogShutterModule('cart items arrays', $items_array, 'test_other_color');

		// add custom color as separate product
		$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
		if (!empty($property_shuttercolour_other) && $property_shuttercolour_other != '') {
			/**
			 * if material is earth
			 * Adauga 10% daca ORDER sqm >= 10sqm
			 * iar daca ORDER sqm < 10sqm adauga doar 100£
			 */
			$other_color_earth_id = 72951; // id prod = 72951
			$other_color_prod_id = 337;

			// if other color earth exists in one item from cart / order
			if ($products['property_material'] == 187 && in_array("have_earth_other_color", $items_array) && !in_array(72951, $other_clrs_array)) {
				// if order have total sqm < 10  then apply other color item price basic else calculate 10% of all order/cart
				if ($sqm_order < 10) {
					if (!array_key_exists($other_color_earth_id, $products_cart)) {
						WC()->cart->add_to_cart($other_color_earth_id, 1);
					}
				} else {
					/*
				 * se adauga $earth_color_price - Daca orderul are peste 10sqm, atunci de adauga 10% din pretul orderului
				 */
					if (!array_key_exists($other_color_earth_id, $products_cart)) {
						WC()->cart->add_to_cart($other_color_earth_id, 1);
					}
					foreach ($cart as $key => $item) {
						$product_id = $item['data']->get_id();
						if ($product_id == $other_color_earth_id) {
							$item['data']->set_price($earth_color_price);
						}
					}
				}
			}
			if ($products['property_material'] != 187 && in_array("have_other_color", $items_array) && !in_array(337, $other_clrs_array)) {
				if (!in_array($other_color_prod_id, $products_cart)) {
					WC()->cart->add_to_cart($other_color_prod_id, 1);
				}
			}
		}
	}
}

if (!function_exists('blackoutSqmsApi')) {

	function blackoutSqmsApi($products)
	{
		$layout_test = $products['property_layoutcode'];
		$arrLay = str_split($layout_test);
		$t = 0;
		$b = 0;
		$c = 0;
		$width = floatval($products['property_width']);
		$height = floatval($products['property_height']);
		$current_point = 0;
		$last_point = 0;
		$panels = array();
		$count_layout = 0;
		foreach ($arrLay as $k => $l) {
			$count_layout++;
			if ($l === 'T' || $l === 't') {
				$t++;
				$current_point = $products['property_t' . $t];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($l === 'b' || $l === 'B') {
				$b++;
				$current_point = $products['property_bp' . $b];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($l === 'C' || $l === 'c') {
				$c++;
				$current_point = $products['property_c' . $c];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}

			if ($count_layout == strlen($layout_test)) {
				$panels[] = $width - $current_point;
			}
		}
		$sqm_array = array();
		foreach ($panels as $k => $w) {
			$sqm_array[] = number_format((($w * $height) / 1000000), 2);
		}
		return $sqm_array;
	}
}

if (!function_exists('saveCurrentPriceItemApi')) {

	function saveCurrentPriceItemApi($prod_id, $user_id)
	{
		$materials = array("Earth", "BiowoodPlus", "Biowood", "Green", "EcowoodPlus", "Ecowood", "Basswood", "BasswoodPlus");

		foreach ($materials as $material) {
			$global_price = get_post_meta(1, $material, true);
			$user_price = get_user_meta($user_id, $material, true);
			if (!empty($global_price)) update_post_meta($prod_id, 'price_item_' . $material, $global_price);
			if (!empty($user_price)) update_post_meta($prod_id, 'price_item_' . $material, $user_price);
		}
	}
}

if (!function_exists('nrPanelsCountApi')) {

	function nrPanelsCountApi($layout_code)
	{
		$freq = array('l' => 0, 'r' => 0);
		$word = $layout_code;
		$len = strlen($word);
		for ($i = 0; $i < $len; $i++) {
			$letter = strtolower($word[$i]);
			if (array_key_exists($letter, $freq)) {
				$freq[$letter]++;
			}
		}
		$nrPanels = $freq['l'] + $freq['r'];
		return $nrPanels;
	}
}

if (!function_exists('calculatePanelsWidthApi')) {

	function calculatePanelsWidthApi($layout_code, $width, $products)
	{
		$panels = array();
		$layout_test = $layout_code;
		$arrLay = str_split($layout_test);
		$t = 0;
		$b = 0;
		$c = 0;
		$l = 0;
		$r = 0;
		$current_point = 0;
		$last_point = 0;
		$count_layout = 0;
		foreach ($arrLay as $k => $let) {
			$count_layout++;
			if ($let === 'T' || $let === 't') {
				$t++;
				$current_point = $products['property_t' . $t];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($let === 'b' || $let === 'B') {
				$b++;
				$current_point = $products['property_bp' . $b];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($let === 'C' || $let === 'c') {
				$c++;
				$current_point = $products['property_c' . $c];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			// if L or R
			if ($count_layout == strlen($layout_test)) {
				$panels[] = $width - $current_point;
			}
		}
		return $panels;
	}
}

if (!function_exists('countTApi')) {
	function countTApi($layout_code)
	{
		// Initialize a counter for 'T' or 't'
		$t_count = 0;
		// Convert the layout code string into an array of characters
		$layout_characters = str_split($layout_code);

		// Iterate through each character in the layout code
		foreach ($layout_characters as $char) {
			// Increment the counter if the character is 'T' or 't'
			if (strtoupper($char) === 'T') {
				$t_count++;
			}
		}

		// Return the count of 'T' or 't' characters
		return $t_count;
	}
}

if (!function_exists('midrailDividerCounterApi')) {
	function midrailDividerCounterApi($mid1, $mid2, $dvd1, $dvd2)
	{
		$count = 1;
		if (!empty($mid1) && $mid1 > 1) $count++;
		if (!empty($mid2) && $mid2 > 1) $count++;
		if (!empty($dvd1) && $dvd1 > 1) $count++;
		if (!empty($dvd2) && $dvd2 > 1) $count++;
		return $count;
	}
}

if (!function_exists('dolarSumApi')) {
	function dolarSumApi($name_prop, $user_id)
	{
		$result = 0;
		if (!empty(get_user_meta($user_id, $name_prop, true)) || (get_user_meta($user_id, $name_prop, true) > 0)) {
			$result = get_user_meta($user_id, $name_prop, true);
		} else {
			$result = get_post_meta(1, $name_prop, true);
		}
		return $result;
	}
}

function add_product_to_user_cart_by_email($email, $product_id, $quantity = 1)
{

	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return new WP_Error('woocommerce_not_loaded', 'WooCommerce is not loaded or activated.', array('status' => 500));
	}

	// Include WooCommerce cart functions if not already loaded
	if (!function_exists('wc_get_cart_item_data_hash')) {
		include_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-cart-functions.php';
	}

	// Get current logged-in user ID
	$user_id = get_current_user_id();
	if (!$user_id) {
		return new WP_Error('not_logged_in', 'You must be logged in to add products to the cart.', array('status' => 403));
	}

	// Set up WooCommerce customer
	if (!WC()->customer) {
		WC()->customer = new WC_Customer($user_id);
	}

	// Validate the product ID
	if (!$product_id || !wc_get_product($product_id)) {
		return new WP_Error('invalid_product', 'The product ID provided is not valid.', array('status' => 400));
	}

	// Ensure WooCommerce session and cart are initialized
	if (null === WC()->session) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
	}

	if (null === WC()->cart) {
		WC()->cart = new WC_Cart();
	}

//	// Initialize the WooCommerce session for the user
//	WC()->session->set_customer_session_cookie(true);
//
//	$items = MultiSessionNamespace\get_cart();
//
//	my_custom_log('CART ITEMS', json_encode($items));

	// Add the product to the WooCommerce cart
	$added = WC()->cart->add_to_cart($product_id, $quantity);

	if (!$added) {
		return new WP_Error('add_to_cart_failed', 'Failed to add the product to the cart.', array('status' => 500));
	}

	// Return a success response
	return new WP_REST_Response(array(
	  'message' => 'Product successfully added to the cart.',
	  'product_id' => $product_id,
	  'quantity' => $quantity,
	  'cart_item_key' => $added,
	  'user_id' => $user_id,
	), 200);

	// Return a success response
	return new WP_REST_Response(array(
	  'message' => 'Product successfully added to the cart.',
	  'product_id' => $product_id,
	  'quantity' => $quantity,
	  'cart_item_key' => $added,
	  'user_id' => $user_id,
	), 200);
}


// Register a custom REST API route
add_action('rest_api_init', function () {
	register_rest_route('custom/v1', 'matrix-add-to-cart', array(
	  'methods' => 'POST',
	  'callback' => 'matrix_add_product_to_cart',
	  'permission_callback' => function () {
		  return is_user_logged_in(); // Require user to be logged in
	  },
	));
});

/**
 * Handle adding a product to the cart
 * @param WP_REST_Request $request The request object
 * @return WP_REST_Response|WP_Error Response or error object
 */
function matrix_add_product_to_cart(WP_REST_Request $request)
{
	// Ensure WooCommerce is active
	if (!class_exists('WooCommerce')) {
		return new WP_Error('woocommerce_not_loaded', 'WooCommerce is not loaded or activated.', array('status' => 500));
	}

	// Include WooCommerce cart functions if not already loaded
	if (!function_exists('wc_get_cart_item_data_hash')) {
		include_once ABSPATH . 'wp-content/plugins/woocommerce/includes/wc-cart-functions.php';
	}

	// Get current logged-in user ID
	$user_id = get_current_user_id();
	if (!$user_id) {
		return new WP_Error('not_logged_in', 'You must be logged in to add products to the cart.', array('status' => 403));
	}

	// Set up WooCommerce customer
	if (!WC()->customer) {
		WC()->customer = new WC_Customer($user_id);
	}

	// Get parameters from the request
	$product_id = 61926;
	$quantity = $request->get_param('quantity') ? intval($request->get_param('quantity')) : 1;

	// Validate the product ID
	if (!$product_id || !wc_get_product($product_id)) {
		return new WP_Error('invalid_product', 'The product ID provided is not valid.', array('status' => 400));
	}

	// Ensure WooCommerce session and cart are initialized
	if (null === WC()->session) {
		WC()->session = new WC_Session_Handler();
		WC()->session->init();
	}

	if (null === WC()->cart) {
		WC()->cart = new WC_Cart();
	}

	// Initialize the WooCommerce session for the user
	WC()->session->set_customer_session_cookie(true);

	$items = MultiSessionNamespace\get_cart();

	my_custom_log('CART ITEMS', json_encode($items));

	// Get current cart items
	$current_cart_items = WC()->cart->get_cart();

	// Create an array to store product IDs and quantities
	$old_products = [];

	// Loop through existing cart items and store their product IDs and quantities
	foreach ($current_cart_items as $cart_item_key => $cart_item) {
		$old_products[] = [
		  'product_id' => $cart_item['product_id'],
		  'variation_id' => $cart_item['variation_id'],
		  'quantity' => $cart_item['quantity'],
		  'variation' => $cart_item['variation'],
		];
	}

	// Empty the WooCommerce cart
	WC()->cart->empty_cart();

	// Add old products back to the cart
	foreach ($old_products as $old_product) {
		WC()->cart->add_to_cart(
		  $old_product['product_id'],
		  $old_product['quantity'],
		  $old_product['variation_id'],
		  $old_product['variation']
		);
	}

	// Add the new product to the cart
//	WC()->cart->add_to_cart($new_product_id, $new_quantity);

	// Add the product to the WooCommerce cart
	$added = WC()->cart->add_to_cart($product_id, $quantity);

	if (!$added) {
		return new WP_Error('add_to_cart_failed', 'Failed to add the product to the cart.', array('status' => 500));
	}

	// Recalculate totals to ensure the cart is updated
	WC()->cart->calculate_totals();

	// Return a success response
	return new WP_REST_Response(array(
	  'message' => 'Product successfully added to the cart.',
	  'product_id' => $product_id,
	  'quantity' => $quantity,
	  'cart_item_key' => $added,
	  'user_id' => $user_id,
	), 200);
}


