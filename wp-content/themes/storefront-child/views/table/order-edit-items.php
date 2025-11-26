<?php
$table_class = $attributes['table_class'];
$table_id = $attributes['table_id'];
$order_id = $attributes['order_id'];
$edit = $attributes['editable'];
$view_price = $attributes['view_price'];
$admin = $attributes['admin'];
if ($order_id) {
	$order = wc_get_order($order_id);
	$order_data = $order->get_data(); // The Order data
	foreach ($order->get_items('tax') as $item_id => $item_tax) {
		$tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
	}
}

global $post;

$term_slug = "";
$i = 0;
$total_prods_delivery = 0;
$total_sqm = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$edit_placed_order_param = '';
$edit_placed_order = '';
$template_order_edit_customer = false;
if (!empty($order_id)) {
	$items = $order->get_items();
	$user_id_customer = get_post_meta($order_id, '_customer_user', true);
	$user_id = $user_id_customer;
	if (get_page_template_slug($post->ID) == 'template-orderedit.php') {
		$edit_placed_order = '&order_edit_customer=editable';
		$template_order_edit_customer = true;
	}
} else {
	$items = WC()->cart->get_cart();
	$user_id_customer = get_current_user_id();
	$user_id = $user_id_customer;

	$cart_items = WC()->cart->get_cart();
	$first_element_cart = reset($cart_items);
	$product_id = $first_element_cart['product_id'];

	$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
	$fob_components = false;

// Check if $term_list is an array and is not empty
	if (is_array($term_list) && !empty($term_list)) {
		// Check if $term_list[0] is an object
		if (is_object($term_list[0])) {
			$term_slug = $term_list[0]->slug;
			if ($term_slug == 'components-fob') {
				$fob_components = true;
			}
		}
	}
}
$var_view_price = get_user_meta(get_current_user_id(), 'view_price', true);
$view_price = ($var_view_price == 'yes' || $var_view_price == '') ? true : false;
$deler_id = get_user_meta($user_id, 'company_parent', true);
//if (current_user_can('administrator') || get_current_user_id() == $deler_id) $view_price = true;

$nr_code_prod = array();
foreach ($items as $item_id => $item_data) {
	$i++;
	// $product = $item_data->get_product();
	$product_id = $item_data['product_id'];
	$property_delivery_price = get_post_meta($product_id, 'train_delivery', true);

// Get the delivery price and convert to a float
	$property_delivery_price = floatval($property_delivery_price);

// Now, perform the addition
	$total_prods_delivery = $total_prods_delivery + $property_delivery_price;
	$property_total_sqm = get_post_meta($product_id, 'property_total', true);
	$total_sqm = $total_sqm + $property_total_sqm;
	if ($i == 1) {
		$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
		$fob_components = false;
		// Check if $term_list is an array and is not empty
		if (is_array($term_list) && !empty($term_list)) {
			// Check if $term_list[0] is an object
			if (is_object($term_list[0])) {
				$term_slug = $term_list[0]->slug;
				if ($term_slug == 'components-fob') {
					$fob_components = true;
				}
			}
		}
	}
	$nr_t = get_post_meta($product_id, 'counter_t', true);
	$nr_b = get_post_meta($product_id, 'counter_b', true);
	$nr_c = get_post_meta($product_id, 'counter_c', true);
	$nr_g = get_post_meta($product_id, 'counter_g', true);

	$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
	if ($property_nr_sections) {
		$individual_counter = get_post_meta($product_id, 'individual_counter', true);
		for ($sec = 1; $sec <= $property_nr_sections; $sec++) {
			$nr_b = $individual_counter[$sec]['counter_b'];
			$nr_t = $individual_counter[$sec]['counter_t'];
			$nr_c = $individual_counter[$sec]['counter_c'];
			$nr_g = $individual_counter[$sec]['counter_g'];
		}
	}

	if (!empty($nr_code_prod)) {
		if ($nr_code_prod['b'] < $nr_b) {
			$nr_code_prod['b'] = intval($nr_b);
		}
		if ($nr_code_prod['t'] < $nr_t) {
			$nr_code_prod['t'] = intval($nr_t);
		}
		if ($nr_code_prod['c'] < $nr_c) {
			$nr_code_prod['c'] = intval($nr_c);
		}
		if ($nr_code_prod['g'] < $nr_g) {
			$nr_code_prod['g'] = intval($nr_g);
		}
	} else {
		$nr_code_prod['b'] = intval($nr_b);
		$nr_code_prod['t'] = intval($nr_t);
		$nr_code_prod['c'] = intval($nr_c);
		$nr_code_prod['g'] = intval($nr_g);
	}
}

?>
<table id="<?php
echo $table_id; ?>" style="width:100%" class="table-bordered <?php
echo $table_class; ?>">
    <thead>
    <tr>
        <th>Nr.</th>
        <th>Item</th>
        <th></th>
        <th></th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
		<?php
		if ($edit === 'true' || $admin == 'true') { ?>
            <th></th>
			<?php
		} ?>
    </tr>
    </thead>
    <tbody>
	<?php
	$array_att = array();
	$j = 0;
	foreach ($items as $item_id => $item_data) {
		$j++;
		//$product = $item_data->get_product();
		$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
		// print_r($item_id);
		$product_id = $item_data['product_id'];
		$product = wc_get_product($product_id);
		$title = get_the_title($product_id);
		$property_room_other = get_post_meta($product_id, 'property_room_other', true);
		$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
		$property_style = get_post_meta($product_id, 'property_style', true);
		$property_frametype = get_post_meta($product_id, 'property_frametype', true);
		$attachment = get_post_meta($product_id, 'attachment', true);
		$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
		$array_att[] = $attachment;
		$property_category = get_post_meta($product_id, 'shutter_category', true);
		$property_material = get_post_meta($product_id, 'property_material', true);
		$property_width = get_post_meta($product_id, 'property_width', true);
		$property_height = get_post_meta($product_id, 'property_height', true);
		$property_midrailheight = get_post_meta($product_id, 'property_midrailheight', true);
		$property_midrailheight2 = get_post_meta($product_id, 'property_midrailheight2', true);
		$property_midraildivider1 = get_post_meta($product_id, 'property_midraildivider1', true);
		$property_midraildivider2 = get_post_meta($product_id, 'property_midraildivider2', true);
		$property_solidtype = get_post_meta($product_id, 'property_solidtype', true);
		$property_midrailpositioncritical = get_post_meta($product_id, 'property_midrailpositioncritical', true);
		$property_totheight = get_post_meta($product_id, 'property_totheight', true);
		$property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);
		$property_doubleClosingLouvres = get_post_meta($product_id, 'property_double_closing_louvres', true);
		$property_bladesize = get_post_meta($product_id, 'property_bladesize', true);
		$property_trackedtype = get_post_meta($product_id, 'property_trackedtype', true);
		$property_freefolding = get_post_meta($product_id, 'property_freefolding', true);
		$property_bypasstype = get_post_meta($product_id, 'property_bypasstype', true);
		$property_lightblocks = get_post_meta($product_id, 'property_lightblocks', true);
		$property_tracksnumber = get_post_meta($product_id, 'property_tracksnumber', true);
		$property_fit = get_post_meta($product_id, 'property_fit', true);
		$property_frameleft = get_post_meta($product_id, 'property_frameleft', true);
		$property_frameright = get_post_meta($product_id, 'property_frameright', true);
		$property_frametop = get_post_meta($product_id, 'property_frametop', true);
		$property_framebottom = get_post_meta($product_id, 'property_framebottom', true);
		$bayposttype = get_post_meta($product_id, 'bay-post-type', true);
		$tposttype = get_post_meta($product_id, 't-post-type', true);
		$property_builtout = get_post_meta($product_id, 'property_builtout', true);
		$property_stile = get_post_meta($product_id, 'property_stile', true);
		$property_hingecolour = get_post_meta($product_id, 'property_hingecolour', true);
		$property_shuttercolour = get_post_meta($product_id, 'property_shuttercolour', true);
		$property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
		$property_controltype = get_post_meta($product_id, 'property_controltype', true);
		$property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
		$property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
		$property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
		$property_layoutcode_tracked = get_post_meta($product_id, 'property_layoutcode_tracked', true);
		$property_t1 = get_post_meta($product_id, 'property_t1', true);
		$property_g1 = get_post_meta($product_id, 'property_g1', true);
		$property_tposttype = get_post_meta($product_id, 'property_tposttype', true);
		$property_total = get_post_meta($product_id, 'property_total', true);
		$property_depth = get_post_meta($product_id, 'property_depth', true);
		$property_volume = get_post_meta($product_id, 'property_volume', true);
		$property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
		$property_solidpanelheight = get_post_meta($product_id, 'property_solidpanelheight', true);
		$property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
		$property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
		$property_locks = get_post_meta($product_id, 'property_locks', true);
		$property_central_lock = get_post_meta($product_id, 'property_central_lock', true);
		$property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
		$property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);
		$property_lock_position = get_post_meta($product_id, 'property_lock_position', true);
		$property_louver_lock = get_post_meta($product_id, 'property_louver_lock', true);
		$property_nowarranty = get_post_meta($product_id, 'property_nowarranty', true);
		$comments_customer = get_post_meta($product_id, 'comments_customer', true);
		$property_motorized = get_post_meta($product_id, 'property_motorized', true);
		$property_nrMotors = get_post_meta($product_id, 'property_nrMotors', true);
		$property_nrRemotes = get_post_meta($product_id, 'property_nrRemotes', true);
//Returns All Term Items for "product_cat"
		$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
		$attribute_pa_colors = wc_get_order_item_meta($item_id, 'pa_colors', true);
		$attribute_pa_covered = wc_get_order_item_meta($item_id, 'pa_covered', true);
		$comp_note = wc_get_order_item_meta($item_id, 'comp_note', true);
		$price = get_post_meta($product_id, '_price', true);
		$regular_price = get_post_meta($product_id, '_regular_price', true);
		$sale_price = get_post_meta($product_id, '_sale_price', true);
		$sections_price = get_post_meta($product_id, 'sections_price', true);
		// 187 => 'Earth'
		$base_price_earth = get_post_meta($product_id, 'basic_earth_price', true);

		if (!$view_price) {
			$price = 0;
			$regular_price = 0;
			$sale_price = 0;
			$sections_price = 0;
		}
		// echo '<pre>';
		// print_r($term_list->slug);
		// echo '</pre>';
		if ($product_id == 337 || $product_id == 72951) { ?>
            <tr class="prod-item" data-pord-id="<?php
			echo $product_id; ?>">
                <td>
					<?php echo $j; ?>
                </td>
                <td colspan="3">
					<?php
					echo get_the_title($product_id); ?>
                </td>
                <td>£
					<?php
					echo number_format($price, 2); ?>
                </td>
                <td></td>
                <td>£
					<?php
					echo number_format($price, 2); ?>
                </td>
				<?php
				if ($edit === 'true') { ?>
                    <td>
                    </td>
					<?php
				} ?>
				<?php
				if ($admin === 'true') { ?>
                    <td>
                    </td>
					<?php
				} ?>
            </tr>
			<?php
		} elseif ($term_slug == 'pos' || $term_slug == 'components' || $term_slug == 'components-fob') {
			?>
            <tr class="prod-item" data-pord-id="<?php
			echo $product_id; ?>">
                <td>
					<?php echo $j; ?>
                </td>
                <td>
                    <strong><?php
						echo get_the_title($product_id); ?></strong>
                </td>
                <td colspan="2"><?php
					if (!empty($attribute_pa_colors)) echo '<strong>Color:</strong> ' . $attribute_pa_colors . '<br>';
					if (!empty($attribute_pa_covered)) echo '<strong>Covered:</strong> ' . $attribute_pa_covered . '<br>';
					if (!empty($comp_note)) echo '<strong>Your Note:</strong> ' . $comp_note;
					?>
                </td>
                <td><?php
					echo $fob_components ? '$' : '£'; ?>
					<?php
					echo number_format($price, 2); ?>
                </td>
                <td><?php
					$quantity = 1;
					$quantity_prod = get_post_meta($product_id, 'quantity', true);
					if (!empty($quantity_prod)) {
						$quantity = $quantity_prod;
					} else {
						$quantity = isset($item_data['quantity']) ? intval($item_data['quantity']) : 0;
					}
					echo $quantity; ?></td>
                <td><?php
					echo $fob_components ? '$' : '£'; ?>
					<?php
					$total = $price * $quantity;  // Perform multiplication first

					$formatted_total = number_format($total, 2);  // Then format the result
					echo $formatted_total;
					?>
                </td>
				<?php
				if ($edit === 'true') { ?>
                    <td>
						<?php
						echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
						  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
						  esc_url(wc_get_cart_remove_url($item_id)),
						  __('Remove this item', 'woocommerce'),
						  esc_attr($product_id),
						  esc_attr($_product->get_sku())
						));
						?>
                    </td>
					<?php
				} ?>
				<?php
				if ($admin === 'true') { ?>
                    <td>
                    </td>
					<?php
				} ?>
            </tr>
			<?php
		} elseif ($property_category === 'Batten') { ?>
            <tr class="prod-item" data-pord-id="<?php
			echo $product_id; ?>">
                <td>
					<?php echo $j; ?>
                </td>
                <td>
					<?php
					echo $property_category; ?>
                    <br> Room:
                    <strong>
						<?php
						echo $property_room_other; ?></strong>
                    <br> Material:
                    <strong>
						<?php
						echo $atributes[$property_material]; ?></strong>
                </td>
                <td>
                </td>
                <td>
                    Image:
					<?php
					if (!empty($attachment)) { ?>
                        <a href="<?php
						echo get_post_meta($product_id, 'attachment', true); ?>" target="_blank">
                            <img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">
                        </a>
						<?php
					} else {
						echo '<strong>no</strong>';
					}
					?>
                    Draw Image:
					<?php
					if (!empty($attachmentDraw)) { ?>
                        <a href="<?php
						echo get_post_meta($product_id, 'attachment_draw', true); ?>" target="_blank">
                            <img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">
                        </a>
						<?php
					} else {
						echo '<strong>no</strong>';
					}
					?>
                    <br>
                    <br>Shutter Colour:<strong>
						<?php
						echo $atributes[$property_shuttercolour]; ?></strong>
                    <br>
                    Notes:<strong>
						<?php
						if ($property_nowarranty == "Yes") {
							echo 'OUT OF WARRANTY ACCEPTED <br>';
						}
						echo $comments_customer; ?></strong>
                </td>
                <td><!-- $ 5000 -->
					<?php
					if ($view_price || current_user_can('administrator')) {
						$batten_type = get_post_meta($product_id, 'batten_type', true);
						if ($batten_type == 'custom') {
							if (!empty(get_user_meta($user_id, 'BattenCustom', true)) || (get_user_meta($user_id, 'BattenCustom', true) > 0)) {
								echo '£' . get_user_meta($user_id, 'BattenCustom', true);
							} else {
								echo '£' . get_post_meta(1, 'BattenCustom', true);
							}
						} elseif ($batten_type == 'standard') {
							if (!empty(get_user_meta($user_id, 'BattenStandard', true)) || (get_user_meta($user_id, 'BattenStandard', true) > 0)) {
								echo '£' . get_user_meta($user_id, 'BattenStandard', true);
							} else {
								echo '£' . get_post_meta(1, 'BattenStandard', true);
							}
						}
					}
					?>
                </td>
                <td>
                    <strong>
						<?php
						echo number_format((double)$property_volume, 5); ?> sq/m </strong>
                    <br>
					<?php
					$quantity = 1;
					$quantity_prod = get_post_meta($product_id, 'quantity', true);
					if (!empty($quantity_prod)) {
						$quantity = $quantity_prod;
					} else {
						$quantity = isset($item_data['quantity']) ? intval($item_data['quantity']) : 0;
					}
					echo 'Qty:  <strong>' . $quantity . '</strong>'; ?>
                    <br> Width:<strong>
						<?php
						echo $property_width; ?></strong>
                    <br> Height:<strong>
						<?php
						echo $property_height; ?></strong>
                    <br> Depth :<strong>
						<?php
						echo $property_depth; ?></strong>
                    <br>
                </td>
                <td class="price-batten">
					<?php
					if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
						$total = $price * $quantity;  // Perform multiplication first

						$formatted_total = number_format($total, 2);  // Then format the result
						echo '£ ' . $formatted_total;
					}
					?>
                </td>
				<?php
				//                        if (current_user_can('china_admin')) {
				//                            echo '<td></td>';
				//                        } else {

				if ($edit == 'true' && $admin == 'false') { ?>
                    <td>
						<?php
						$pieces = explode("-", $title);
						if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter') || ($property_category === 'prod1') || ($pieces[0] == 'prod1')) {
							?>
                        <a class="btn btn-primary transparent"
                           href="/prod1-all/?id=<?php
						   echo $product_id * 1498765 * 33;
						   echo $edit_placed_order; ?>">Edit</a><?php
						} elseif (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')) {
							?>
                        <a class="btn btn-primary transparent"
                           href="/prod2-all/?id=<?php
						   echo $product_id * 1498765 * 33;
						   echo $edit_placed_order; ?>">Edit</a><?php
						} elseif ($property_category === 'Batten') {
							?>
                        <a class="btn btn-primary transparent"
                           href="/product5-edit/?id=<?php
						   echo $product_id * 1498765 * 33;
						   echo $edit_placed_order; ?>">Edit</a><?php
						}
						if (get_page_template_slug($post->ID) != 'template-orderedit.php') {

//   if (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')
							$clone_categories = array(
							  'Shutter' => '/prod1-all/',
							  'Shutter & Blackout Blind' => '/prod2-all/',
							  'Batten' => '/product5-edit/',
							);
							$prodId_encode = base64_encode($product_id);
							?>
                            <br>
                            <a
                              class="btn btn-primary clone-item"
                              item-id="<?php echo $item_id; ?>"
                              href="<?php echo $clone_categories[$property_category] . '?clone=' . $prodId_encode; ?>"
                            >
                                Clone
                            </a>
                            <br>
							<?php
							echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
							  esc_url(wc_get_cart_remove_url($item_id)),
							  __('Remove this item', 'woocommerce'),
							  esc_attr($product_id),
							  esc_attr($_product->get_sku())
							));
						} elseif (get_page_template_slug($post->ID) == 'template-orderedit.php') {
							?>
                            <br>
                            <buttton
                              class="btn btn-danger delete-item"
                              item-id="<?php
							  echo $item_id; ?>">
                                Delete
                            </buttton>
							<?php
						}
						?>
                    </td>
					<?php
				} ?>
				<?php
				if ($admin == 'true') { ?>
                    <td>
						<?php
						$pieces = explode("-", $title);
						$property_category;
						if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
							?>
                        <a class="btn btn-primary transparent" target="_blank"
                           href="/prod1-all/?order_id=<?php
						   echo $order_id * 1498765 * 33; ?>&id=<?php
						   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
						   echo $user_id_customer; ?>&item_id=<?php
						   echo $item_id; ?>">
                                Edit</a><?php
						} elseif ($property_category === 'Shutter & Blackout Blind' || ($pieces[0] == 'Shutter & Blackout Blind')) {
							?>
                        <a class="btn btn-primary transparent" target="_blank"
                           href="/prod2-all/?order_id=<?php
						   echo $order_id * 1498765 * 33; ?>&id=<?php
						   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
						   echo $user_id_customer; ?>&item_id=<?php
						   echo $item_id; ?>">
                                Edit</a><?php
						} elseif ($property_category === 'Batten') {
							?>
                        <a class="btn btn-primary transparent" target="_blank"
                           href="/product5-admin/?order_id=<?php
						   echo $order_id * 1498765 * 33; ?>&id=<?php
						   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
						   echo $user_id_customer; ?>&item_id=<?php
						   echo $item_id; ?>">
                                Edit</a><?php
						}
						?>
                    </td>
					<?php
				}
				//   }
				?>
            </tr>
			<?php
		} else {

			if (!empty($property_nr_sections)) {
				for ($sec = 1; $sec <= $property_nr_sections; $sec++) {

					?>
                    <tr class="prod-item" data-pord-id="<?php
					echo $product_id; ?>">
                        <td>
							<?php echo $j; ?>
                        </td>
                        <!-- <td><?php
						// echo $j;
						?></td> -->
                        <td>
                            <strong>
								<?php
								echo $atributes[$property_material]; ?>
                            </strong>
                            <br> Room:
                            <strong>
								<?php
								echo $property_room_other . ' - Section' . $sec; ?></strong>
                            <br> Installation style:<strong>
								<?php
								echo 'Individual Bay Window - ' . $atributes[$property_style];
								if ($property_style == 36) {
									$user_Arched = get_user_meta($user_id, 'Arched', true);
									$module_Arched = get_post_meta(1, 'Arched', true);
									if ($user_Arched) {
										echo ' (+' . $user_Arched . '%)';
									} else {
										echo ' (+' . $module_Arched . '%)';
									}
								} elseif ($property_style == 34) {
									echo ' (+£125)';
								} elseif ($property_style == 33) {
									if (get_user_meta($user_id, 'Shaped', true)) {
										$shaped_procent = get_user_meta($user_id, 'Shaped', true);
									} else {
										$shaped_procent = get_post_meta(1, 'Shaped', true);
									}
									echo ' (+' . $shaped_procent . '%)';
								}
								?></strong>
                            <br> Has drawing image:
							<?php
							if (!empty($attachment)) {
								echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
							} else {
								echo '<strong>no</strong>';
							}
							?>
                            <br> Has drawing:
							<?php
							if (!empty($attachmentDraw)) {
								echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
							} else {
								echo '<strong>no</strong>';
							}
							?>
                            <br> Midrail Height:<strong>
								<?php
								echo $property_midrailheight; ?></strong>
                            <br>
							<?php
							if (!empty($property_midrailheight2)) {
								echo 'Midrail Height 2: <strong>' . $property_midrailheight2 . '</strong><br>';
							}
							?>
							<?php
							if (!empty($property_midraildivider1)) {
								echo 'Hidden Divider 1: <strong>' . $property_midraildivider1 . '</strong><br>';
							}
							?>
							<?php
							if (!empty($property_midraildivider2)) {
								echo 'Hidden Divider 2: <strong>' . $property_midraildivider2 . '</strong><br>';
							}
							if (!empty($property_solidpanelheight)) {
								echo 'Solid Panel Height: <strong>' . $property_solidpanelheight . '(+' . get_post_meta(1, 'Solid', true) . '%)</strong><br>';
							}
							if (!empty($property_solidtype && strpos('Solid', $atributes[$property_style]))) {
								echo 'Solid Type: <strong>' . $property_solidtype . '</strong><br>';
							}
							if (!in_array($property_style, [38, 39])) {
								?> Position is Critical:<strong>
									<?php
									echo $atributes[$property_midrailpositioncritical] ?></strong>
                                <br>
								<?php
							}
							if (!empty($property_totheight)) {
								echo 'T-o-T Height: <strong>' . $property_totheight . '</strong><br>';
							}
							?>
							<?php
							if (!empty($property_horizontaltpost)) {
								echo 'Horizontal T Post: <strong>' . $property_horizontaltpost . '</strong><br>';
							}
							if (!in_array($property_style, [38, 39])) {
								?> Louvre size:<strong>
									<?php
									echo $atributes[$property_bladesize]; ?></strong>
                                <br>
								<?php
							}
							if (!empty($property_doubleClosingLouvres)) {
								echo 'Double closing louvres: <strong>' . $property_doubleClosingLouvres . '</strong><br>';
							}
							if ($property_style == 35 || $property_style == 39 || $property_style == 41) {
								echo 'Tracked type: <strong>' . $property_trackedtype . '</strong><br>';
								echo 'Free folding: <strong>' . $property_freefolding . '</strong><br>';
							} elseif ($property_style == 37 || $property_style == 38 || $property_style == 40) {
								echo 'Tracked type: <strong>' . $property_trackedtype . '</strong><br>';
								echo 'By-pass Type: <strong>' . $property_bypasstype . '</strong><br>';
								echo 'Light Blocks: <strong>' . $property_lightblocks . '</strong><br>';
							} else {
								if ($property_fit == 56) {
									if (get_user_meta($user_id, 'Inside', true)) {
										$measure_procent = get_user_meta($user_id, 'Inside', true);
									} else {
										$measure_procent = get_post_meta(1, 'Inside', true);
									}
									echo 'Measure Type: <strong>' . $atributes[$property_fit] . '(+' . $measure_procent . '%)</strong>';
								} else {
									echo 'Measure Type: <strong>' . $atributes[$property_fit] . '</strong>';
								}
							}
							?>
                        </td>
                        <td>Frame type:<strong>
								<?php
								echo $atributes[$property_frametype]; ?></strong>
                            <br> Stile type:<strong>
								<?php
								echo $atributes[$property_stile]; ?></strong>
                            <br> Frame Left:<strong>
								<?php
								echo $atributes[$property_frameleft]; ?></strong>
                            <br> Frame Right:<strong>
								<?php
								echo $atributes[$property_frameright]; ?></strong>
                            <br> Frame Top:<strong>
								<?php
								echo $atributes[$property_frametop]; ?></strong>
                            <br> Frame Bottom:<strong>
								<?php
								echo $atributes[$property_framebottom]; ?></strong>
                            <br>
							<?php
							if (!empty($property_builtout)) {
								$user_buildout = get_user_meta($user_id, 'Buildout', true);
								$module_buildout = get_post_meta(1, 'Buildout', true);
								if ($user_buildout) {
									echo 'Buildout: <strong>' . $property_builtout . '</strong> (+' . $user_buildout . '%)';
								} else {
									echo 'Buildout: <strong>' . $property_builtout . '</strong> (+' . $module_buildout . '%)';
								}
							}
							?>
                        </td>
                        <td>Hinge Colour:<strong>
								<?php
								echo $atributes[$property_hingecolour]; ?></strong>
                            <br> Shutter Colour:<strong>
								<?php
								echo $atributes[$property_shuttercolour]; ?></strong>
                            <br>
							<?php
							$pieces = explode("-", $title);
							if (!empty($property_shuttercolour_other)) {
								echo 'Other Colour: <strong>' . $property_shuttercolour_other . '</strong><br>';
							}
							if (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')) {
								?>
                                Blackout Blind Colour:
                                <strong>
									<?php
									echo $atributes[$property_blackoutblindcolour]; ?></strong>
                                <br>
								<?php
							}
							?>
                            Control Type:<strong>
								<?php
								echo $atributes[$property_controltype]; ?><?php
								if ($atributes[$property_controltype] == 'Clearview' || $property_controltype == 403) {
									$user_control = get_user_meta($user_id, 'Concealed_Rod', true);
									$module_control = get_post_meta(1, 'Concealed_Rod', true);
									if ($user_control) {
										echo ' (+' . $user_control . '%)';
									} else {
										echo ' (+' . $module_control . '%)';
									}
								}
								?>
                            </strong>
							<?php
							if (!empty($atributes[$property_controlsplitheight])) { ?>
                                <br> Control Split Height:
                                <strong>
								<?php
								echo $atributes[$property_controlsplitheight]; ?></strong><?php
							} ?>
                            <br> Layout code:<strong style="text-transform:uppercase;">
								<?php
								echo get_post_meta($product_id, 'property_layoutcode' . $sec, true);
								?></strong>
                            <br>
							<?php
							$property_opendoor = get_post_meta($product_id, 'property_opendoor' . $sec, true);
							if ($property_opendoor) {
								?>
                                Open first:<strong>
									<?php
									echo $property_opendoor; ?>
                                </strong>
                                <br>
								<?php
							} ?>
							<?php
							if ($property_style == 37 || $property_style == 38 || $property_style == 40) {
								if ($property_tracksnumber) {
									echo 'Number of Tracks: ';
									echo '<strong>' . $property_tracksnumber . '</strong><br>';
								}
							}

							$tbuilout = 0;
							$cbuilout = 0;
							$bbuilout = 0;
							$bposttype = 0;
							$tposttype_count = 0;
							$unghi = 0;
							$unghi_t = 0;
							foreach ($nr_code_prod as $key => $val) {
								for ($i = 1; $i <= 10; $i++) {
									if ($key == 'c') {
										if (!empty(get_post_meta($product_id, 'property_c' . $i . '_' . $sec, true))) {
											echo 'CPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_c' . $i . '_' . $sec, true) . '</strong><br>';
										}
										if (!empty(get_post_meta($product_id, 'property_c_buildout' . $i . '_' . $sec, true))) {
											echo 'CPost Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_c_buildout' . $i . '_' . $sec, true) . '</strong>';
											$cbuilout++;
											if ($cbuilout == 1) {
												$user_c_build = get_user_meta($user_id, 'C_Buildout', true);
												$module_c_build = get_post_meta(1, 'C_Buildout', true);
												if ($user_c_build) {
													echo ' (+' . $user_c_build . '%)';
												} else {
													echo ' (+' . $module_c_build . '%)';
												}
											}
										}
									} elseif ($key == 't') {
										if (!empty(get_post_meta($product_id, 'property_t' . $i . '_' . $sec, true))) {
											echo 'TPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_t' . $i . '_' . $sec, true) . '</strong><br>';
										}
										if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i . '_' . $sec, true))) {
											echo 'TPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_t_buildout' . $i . '_' . $sec, true) . '</strong>';
											$tbuilout++;
											if ($tbuilout == 1) {
												$user_t_build = get_user_meta($user_id, 'T_Buildout', true);
												$module_t_build = get_post_meta(1, 'T_Buildout', true);
												if ($user_t_build) {
													echo ' (+' . $user_t_build . '%)';
												} else {
													echo ' (+' . $module_t_build . '%)';
												}
											}
											echo "<br>";
										}
									} elseif ($key == 'g') {
										if (!empty(get_post_meta($product_id, 'property_g' . $i . '_' . $sec, true))) {
											echo 'GPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_g' . $i . '_' . $sec, true) . '</strong><br>';
										}
									}
								}
							}
							$tposttypeSec = get_post_meta($product_id, 't-post-type' . $sec, true);
							if ($tposttypeSec) {
								echo 'T-Post Style: <strong>' . $tposttypeSec;
								echo '</strong>';
								echo '<br>';
								$tposttype_count++;
							}
							?>
							<?php
							if (!empty(get_post_meta($product_id, 'property_t1_' . $sec, true))) {
								if ($property_tposttype) { ?> T-Post Type:
                                    <strong>
										<?php
										echo $atributes[$property_tposttype]; ?></strong>
                                    <br>
									<?php
								}
							} ?>
							<?php
							if ($property_sparelouvres == 'Yes') { ?> Include 2 x Spare Louvre:
                                <strong>
									<?php
									echo $property_sparelouvres; ?></strong> (+6£)
                                <br>
								<?php
							} ?>
							<?php
							if ($property_ringpull == 'Yes') { ?> Ring Pull:
                                <strong>
									<?php
									echo $property_ringpull; ?></strong> (+35£)
                                <br>
                                How many rings?:
                                <strong>
									<?php
									echo $property_ringpull_volume; ?></strong>
                                <br>
								<?php
							} ?>
							<?php
							if ($property_locks == 'Yes' || $property_central_lock == "Yes") { ?> Locks:
                                <strong>Yes</strong>
								<?php
								if (!empty(get_user_meta($user_id_customer, 'Lock', true)) || (get_user_meta($user_id_customer, 'Lock', true) > 0)) {
									echo '(+' . get_user_meta($user_id_customer, 'Lock', true) . '£)';
								} else {
									echo '(+' . get_post_meta(1, 'Lock', true) . '£)';
								} ?>
                                <br>
                                <!--                How many locks?:-->
                                <!--                <strong>-->
                                <!--									--><?php
								//									echo $property_locks_volume; ?><!-- set(s)</strong>-->
                                <!--                <br>-->
                                Lock Position:
                                <strong>
									<?php
									$lock_position = ($property_locks == "Yes") ? "Top & Bottom Locks" : "Central Lock";
									echo $lock_position; ?></strong>
                                <br>
                                <!--                Louver Lock:-->
                                <!--                <strong>-->
                                <!--									--><?php
//									echo $property_louver_lock; ?><!--</strong>-->
                                <!--                <br>-->
								<?php
							} ?>
                            Notes:<strong>
								<?php
								if ($property_nowarranty == "Yes") {
									echo 'OUT OF WARRANTY ACCEPTED <br>';
								}
								echo $comments_customer; ?></strong>

							<?php
							$pictures = get_post_meta($product_id, 'pictures', true);
							if (!empty($pictures)) {
								echo '<br><span>Images:</span>';
								// Wrapper-ul de imagini va sta pe același rând cu span-ul
								echo '<div style="display: inline-block; margin-left: 10px;">';

								$img_index = 1;
								foreach ($pictures as $imageUrl) {
									// Fiecare 'imagine' e afișată într-un container cu display:inline-block;
									echo '<div style="display: inline-block; margin-right: 15px;">';
									echo '<a href="' . esc_url($imageUrl) . '" target="_blank">';
									echo 'Image-' . $img_index;
									echo '</a>';
									echo '</div>';
									$img_index++;
								}
								echo '</div>';
							}
							?>
                        </td>
                        <td>
							<?php
							//                            print_r($property_material);
							//                            echo ' -- ';
							//                            print_r($user_id);
							//                            echo ' -- ';
							//                            print_r($product_id);
							//                            echo ' -- ';

							if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
								$materials = array(187 => 'Earth', 137 => 'Green', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood', 5 => 'EcowoodPlus');
								$price_for_update = get_post_meta($order_id, 'price_for_update', true);
								foreach ($materials as $key => $material) {
									if ($property_material == $key) {
										$price_for_update = get_post_meta($order_id, 'price_for_update', true);
										if ($price_for_update == 'old') {
											$material_price = get_post_meta($product_id, 'price_item_' . $material, true);
										} else {
											if (!empty(get_user_meta($user_id, $material, true)) || (get_user_meta($user_id, $material, true) > 0)) {
												$material_price = get_user_meta($user_id, $material, true);
											} else {
												$material_price = get_post_meta(1, $material, true);
											}
										}
										$material_price = get_post_meta($product_id, 'price_item_' . $material, true);
									}
//                                else {
//                                    if (!empty(get_user_meta($user_id, $atributes[$property_material], true)) || (get_user_meta($user_id, $atributes[$property_material], true) > 0)) {
//                                        $material_price = get_user_meta($user_id, $atributes[$property_material], true);
//                                    } else {
//                                        $material_price = get_post_meta(1, $atributes[$property_material], true);
//                                    }
//                                }
								}

								if ($property_style == 27 || $property_style == 28) {
									// show base earth price
									echo '£ ' . number_format($base_price_earth, 2);
								} else {
									echo '£ ' . $material_price;
								}
							}
							?>
                        </td>
                        <td>
                            <strong>
								<?php
								$property_total_section = get_post_meta($product_id, 'property_total_section' . $sec, true);
								// if($sec == 1) echo number_format((double)$property_total, 2).' sq/m ';
								echo number_format($property_total_section, 2) . ' sq/m ';
								?> </strong>
                            <br>
							<?php
							$quantity = 1;
							$quantity_prod = get_post_meta($product_id, 'quantity', true);
							if (!empty($quantity_prod)) {
								$quantity = $quantity_prod;
							} else {
								$quantity = isset($item_data['quantity']) ? intval($item_data['quantity']) : 0;
							}
							echo 'Qty:  <strong>' . $quantity . '</strong>'; ?>
                            <br> Width:<strong>
								<?php
								echo get_post_meta($product_id, 'property_width' . $sec, true); ?></strong>
                            <br> Height:<strong>
								<?php
								echo $property_height; ?></strong>
                            <br>
                        </td>
                        <td>
							<?php
							if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
								$sum = number_format($sections_price[$sec], 2);
								echo '£' . number_format($sum, 2);
								// echo '<br>' . $product->get_price();
							}
							?>
                        </td>
						<?php
						if ($edit == 'true' && $admin == 'false' && $sec == 1) { ?>
                            <td>
								<?php
								$pieces = explode("-", $title);
								if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter') || ($property_category === 'prod1') || ($pieces[0] == 'prod1')) {
									?>
                                <a class="btn btn-primary transparent"
                                   href="/prod1-all/?id=<?php
								   echo $product_id * 1498765 * 33;
								   echo $edit_placed_order; ?>">Edit</a><?php
								} elseif (($pieces[1] == 'individual')) {
									?>
                                <a class="btn btn-primary transparent"
                                   href="/prod-individual/?id=<?php
								   echo $product_id * 1498765 * 33;
								   echo $edit_placed_order; ?>">Edit</a><?php
								} elseif (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')) {
									?>
                                <a class="btn btn-primary transparent"
                                   href="/prod2-all/?id=<?php
								   echo $product_id * 1498765 * 33;
								   echo $edit_placed_order; ?>">Edit</a><?php
								} elseif ($property_category === 'Batten') {
									?>
                                <a class="btn btn-primary transparent"
                                   href="/product5-edit/?id=<?php
								   echo $product_id * 1498765 * 33;
								   echo $edit_placed_order; ?>">Edit</a><?php
								}
								if (get_page_template_slug($post->ID) != 'template-orderedit.php') {

//   if (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')
									$clone_categories = array(
									  'Shutter' => '/prod1-all/',
									  'Shutter & Blackout Blind' => '/prod2-all/',
									  'Batten' => '/product5-edit/',
									);
									$prodId_encode = base64_encode($product_id);
									?>
                                    <br>
                                    <a
                                      class="btn btn-primary clone-item"
                                      item-id="<?php echo $item_id; ?>"
                                      href="<?php echo $clone_categories[$property_category] . '?clone=' . $prodId_encode; ?>"
                                    >
                                        Clone
                                    </a>
                                    <br>
									<?php
									echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
									  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
									  esc_url(wc_get_cart_remove_url($item_id)),
									  __('Remove this item', 'woocommerce'),
									  esc_attr($product_id),
									  esc_attr($_product->get_sku())
									));
								} elseif (get_page_template_slug($post->ID) == 'template-orderedit.php') {
									?>
                                    <br>
                                <buttton class="btn btn-danger delete-item"
                                         item-id="<?php
								         echo $item_id; ?>">
                                        Delete
                                    </buttton><?php
								}
								?>
                            </td>
							<?php
						} ?>
						<?php
						if ($admin == 'true' && $sec == 1) { ?>
                            <td>
								<?php
								$pieces = explode("-", $title);
								$property_category;
								if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
									?>
                                <a class="btn btn-primary transparent" target="_blank"
                                   href="/prod1-all/?id=<?php
								   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
								   echo $user_id_customer; ?>&item_id=<?php
								   echo $item_id; ?>">
                                        Edit</a><?php
								} elseif (($pieces[1] == 'individual')) {
									?>
                                <a class="btn btn-primary transparent" target="_blank"
                                   href="/prod-individual/?order_id=<?php
								   echo $order_id * 1498765 * 33; ?>&id=<?php
								   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
								   echo $user_id_customer; ?>&item_id=<?php
								   echo $item_id; ?>">Edit</a><?php
								} elseif ($property_category === 'Shutter & Blackout Blind' || ($pieces[0] == 'Shutter & Blackout Blind')) {
									?>
                                <a class="btn btn-primary transparent" target="_blank"
                                   href="/prod2-all/?id=<?php
								   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
								   echo $user_id_customer; ?>&item_id=<?php
								   echo $item_id; ?>">
                                        Edit</a><?php
								} elseif ($property_category === 'Batten') {
									?>
                                <a class="btn btn-primary transparent" target="_blank"
                                   href="/product5-admin/?id=<?php
								   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
								   echo $user_id_customer; ?>&item_id=<?php
								   echo $item_id; ?>">
                                        Edit</a><?php
								}

								?>
                            </td>
							<?php
						}
						// }
						?>
                    </tr>
					<?php
				}
			} else {

				?>
                <tr class="prod-item" data-pord-id="<?php
				echo $product_id; ?>">
                    <td>
						<?php echo $j; ?>
                    </td>
                    <td>
                        <strong>
							<?php
							echo $atributes[$property_material]; ?>
                        </strong>
                        <br> Room:
                        <strong>
							<?php
							echo $property_room_other; ?></strong>
                        <br> Installation style:<strong>
							<?php
							echo $atributes[$property_style];
							if ($property_style == 36) {
								$user_Arched = get_user_meta($user_id, 'Arched', true);
								$module_Arched = get_post_meta(1, 'Arched', true);
								if ($user_Arched) {
									echo ' (+' . $user_Arched . '%)';
								} else {
									echo ' (+' . $module_Arched . '%)';
								}
							} elseif ($property_style == 34) {
								echo ' (+£125)';
							} elseif ($property_style == 33) {
								if (get_user_meta($user_id, 'Shaped', true)) {
									$shaped_procent = get_user_meta($user_id, 'Shaped', true);
								} else {
									$shaped_procent = get_post_meta(1, 'Shaped', true);
								}
								echo ' (+' . $shaped_procent . '%)';
							}
							?></strong>
                        <br> Has drawing image:
						<?php
						if (!empty($attachment)) {
							echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
						} else {
							echo '<strong>no</strong>';
						}
						?>
                        <br> Has drawing:
						<?php
						if (!empty($attachmentDraw)) {
							echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
						} else {
							echo '<strong>no</strong>';
						}
						?>
                        <br> Midrail Height:<strong>
							<?php
							echo $property_midrailheight; ?></strong>
                        <br>
						<?php
						if (!empty($property_midrailheight2)) {
							echo 'Midrail Height 2: <strong>' . $property_midrailheight2 . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_midraildivider1)) {
							echo 'Hidden Divider 1: <strong>' . $property_midraildivider1 . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_midraildivider2)) {
							echo 'Hidden Divider 2: <strong>' . $property_midraildivider2 . '</strong><br>';
						}
						if (!empty($property_solidpanelheight)) {
							echo 'Solid Panel Height: <strong>' . $property_solidpanelheight . '(+' . get_post_meta(1, 'Solid', true) . '%)</strong><br>';
						}
						if (!empty($property_solidtype && strpos('Solid', $atributes[$property_style]))) {
							echo 'Solid Type: <strong>' . $property_solidtype . '</strong><br>';
						}
						if (!in_array($property_style, [38, 39])) {
							?> Position is Critical:<strong>
								<?php
								echo $atributes[$property_midrailpositioncritical] ?></strong>
                            <br>
							<?php
						}
						if (!empty($property_totheight)) {
							echo 'T-o-T Height: <strong>' . $property_totheight . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_horizontaltpost)) {
							echo 'Horizontal T Post: <strong>' . $property_horizontaltpost . '</strong><br>';
						}
						if (!in_array($property_style, [38, 39])) {
							?> Louvre size:<strong>
								<?php
								echo $atributes[$property_bladesize]; ?></strong>
                            <br>
							<?php
						}
						if (!empty($property_doubleClosingLouvres)) {
							echo 'Double closing louvres: <strong>' . $property_doubleClosingLouvres . '</strong><br>';
						}
						if ($property_style == 35 || $property_style == 39 || $property_style == 41) {
							echo 'Tracked type: <strong>' . $property_trackedtype . '</strong><br>';
							echo 'Free folding: <strong>' . $property_freefolding . '</strong><br>';
						} elseif ($property_style == 37 || $property_style == 38 || $property_style == 40) {
							echo 'Tracked type: <strong>' . $property_trackedtype . '</strong><br>';
							if ($property_style != 38) {
								echo 'By-pass Type: <strong>' . $property_bypasstype . '</strong><br>';
							}
							echo 'Light Blocks: <strong>' . $property_lightblocks . '</strong><br>';
						} else {
							if ($property_fit == 56) {
								if (get_user_meta($user_id, 'Inside', true)) {
									$measure_procent = get_user_meta($user_id, 'Inside', true);
								} else {
									$measure_procent = get_post_meta(1, 'Inside', true);
								}
								echo 'Measure Type: <strong>' . $atributes[$property_fit] . '(+' . $measure_procent . '%)</strong>';
							} else {
								echo 'Measure Type: <strong>' . $atributes[$property_fit] . '</strong>';
							}
						}
						?>
                    </td>
                    <td>Frame type:<strong>
							<?php
							echo $atributes[$property_frametype]; ?></strong>
                        <br> Stile type:<strong>
							<?php
							echo $atributes[$property_stile]; ?></strong>
                        <br> Frame Left:<strong>
							<?php
							echo $atributes[$property_frameleft]; ?></strong>
                        <br> Frame Right:<strong>
							<?php
							echo $atributes[$property_frameright]; ?></strong>
                        <br> Frame Top:<strong>
							<?php
							echo $atributes[$property_frametop]; ?></strong>
                        <br> Frame Bottom:<strong>
							<?php
							echo $atributes[$property_framebottom]; ?></strong>
                        <br>
						<?php
						if (!empty($property_builtout)) {
							$user_buildout = get_user_meta($user_id, 'Buildout', true);
							$module_buildout = get_post_meta(1, 'Buildout', true);
							if ($user_buildout) {
								echo 'Buildout: <strong>' . $property_builtout . '</strong> (+' . $user_buildout . '%)';
							} else {
								echo 'Buildout: <strong>' . $property_builtout . '</strong> (+' . $module_buildout . '%)';
							}
						}
						?>
                    </td>
                    <td>Hinge Colour:<strong>
							<?php
							echo $atributes[$property_hingecolour]; ?></strong>
                        <br> Shutter Colour:<strong>
							<?php
							echo $atributes[$property_shuttercolour]; ?></strong>
                        <br>
						<?php
						$pieces = explode("-", $title);
						if (!empty($property_shuttercolour_other)) {
							echo 'Other Colour: <strong>' . $property_shuttercolour_other . '</strong><br>';
						}
						if (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')) {
							?>
                            Blackout Blind Colour:
                            <strong>
								<?php
								echo $atributes[$property_blackoutblindcolour]; ?></strong>
                            <br>
							<?php
						}
						?>
                        Control Type:<strong>
							<?php
							echo $atributes[$property_controltype]; ?><?php
							if ($atributes[$property_controltype] == 'Clearview' || $property_controltype == 403) {
								$user_control = get_user_meta($user_id, 'Concealed_Rod', true);
								$module_control = get_post_meta(1, 'Concealed_Rod', true);
								if ($user_control) {
									echo ' (+' . $user_control . '%)';
								} else {
									echo ' (+' . $module_control . '%)';
								}
							}
							?>
                        </strong>
						<?php
						if (!empty($atributes[$property_controlsplitheight])) { ?>
                            <br> Control Split Height:
                            <strong>
							<?php
							echo $atributes[$property_controlsplitheight]; ?></strong><?php
						} ?>
                        <br> Layout code:<strong style="text-transform:uppercase;">
							<?php
							if ($property_layoutcode) {
								echo $property_layoutcode;
							} else {
								echo $property_layoutcode_tracked;
							}
							?></strong> <br>
						<?php if ($property_category === 'Shutter & Blackout Blind') { ?>
                            Motorised:<strong style="text-transform:uppercase;">
								<?php
								$property_motorized = get_post_meta($product_id, 'property_motorized', true);
								echo $property_motorized;
								?></strong> <br>
							<?php if ($property_motorized == 'Yes') { ?>
                                Number of motors:<strong style="text-transform:uppercase;">
									<?php
									echo get_post_meta($product_id, 'property_nrMotors', true);
									?></strong>
                                <br> Number of remotes:<strong style="text-transform:uppercase;">
									<?php
									echo get_post_meta($product_id, 'property_nrRemotes', true);
									?></strong><br>
							<?php }
						}

						$property_opendoor = get_post_meta($product_id, 'property_opendoor', true);
						if ($property_opendoor) {
							?>
                            Open first:<strong>
								<?php
								echo $property_opendoor; ?>
                            </strong>
                            <br>
							<?php
						} ?>
						<?php
						if ($property_style == 37 || $property_style == 38 || $property_style == 40) {
							if ($property_tracksnumber) {
								echo 'Number of Tracks: ';
								echo '<strong>' . $property_tracksnumber . '</strong><br>';
							}
						}

						$tbuilout = 0;
						$cbuilout = 0;
						$bbuilout = 0;
						$bposttype = 0;
						$tposttype_count = 0;
						$unghi = 0;
						$unghi_t = 0;
						foreach ($nr_code_prod as $key => $val) {
							for ($i = 1; $i < $val + 1; $i++) {
								if ($key == 'b') {
									if (!empty(get_post_meta($product_id, 'property_bp' . $i, true))) {
										echo 'BPost' . $i . ': <strong>' . get_post_meta($product_id, 'property_bp' . $i, true) . '/' . get_post_meta($product_id, 'property_ba' . $i, true) . '</strong>';
										if ((get_post_meta($product_id, 'property_ba' . $i, true) == 90) || (get_post_meta($product_id, 'property_ba' . $i, true) == 135)) {
											echo '<br>';
											if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
												echo 'BPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong><br>';
												$bbuilout++;
												if ($bbuilout == 1) {
													$user_b_build = get_user_meta($user_id, 'B_Buildout', true);
													$module_b_build = get_post_meta(1, 'B_Buildout', true);
													if ($user_b_build) {
														echo ' (+' . $user_b_build . '%)';
													} else {
														echo ' (+' . $module_b_build . '%)';
													}
												}
											}
										} else {
											$unghi++;
											if ($unghi == 1 && $bayposttype == 'normal') {
												$user_b_angle = get_user_meta($user_id, 'Bay_Angle', true);
												$module_b_angle = get_post_meta(1, 'Bay_Angle', true);
												if ($user_b_angle) {
													echo ' (+' . $user_b_angle . '%)';
												} else {
													echo ' (+' . $module_b_angle . '%)';
												}
											}
											echo '<br>';
											if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
												echo 'BPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong><br>';
											}
										}
									}
								}
								if ($key == 'c') {
									if (!empty(get_post_meta($product_id, 'property_c' . $i, true))) {
										echo 'CPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_c' . $i, true) . '</strong><br>';
									}
									if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i, true))) {
										echo 'CPost Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_c_buildout' . $i, true) . '</strong>';
										$cbuilout++;
										if ($cbuilout == 1) {
											$user_c_build = get_user_meta($user_id, 'C_Buildout', true);
											$module_c_build = get_post_meta(1, 'C_Buildout', true);
											if ($user_c_build) {
												echo ' (+' . $user_c_build . '%)';
											} else {
												echo ' (+' . $module_c_build . '%)';
											}
										}
									}
									//echo 'c'.$i.': '.get_post_meta( $product_id, 'property_c'.$i, true ).'<br> ';
								} elseif ($key == 't') {
									if (!empty(get_post_meta($product_id, 'property_t' . $i, true))) {
										echo 'TPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_t' . $i, true) . '</strong><br>';
									}
									if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i, true))) {
										echo 'TPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_t_buildout' . $i, true) . '</strong>';
										$tbuilout++;
										if ($tbuilout == 1) {
											$user_t_build = get_user_meta($user_id, 'T_Buildout', true);
											$module_t_build = get_post_meta(1, 'T_Buildout', true);
											if ($user_t_build) {
												echo ' (+' . $user_t_build . '%)';
											} else {
												echo ' (+' . $module_t_build . '%)';
											}
										}
										echo "<br>";
									}
									//echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
								} elseif ($key == 'g') {
									if (!empty(get_post_meta($product_id, 'property_g' . $i, true))) {
										echo 'GPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_g' . $i, true) . '</strong><br>';
									}
//                                        if (!empty(get_post_meta($product_id, 'property_g_buildout' . $i, true))) {
//                                            echo 'GPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_g_buildout' . $i, true) . '</strong>';
//                                            if (!empty(get_user_meta($user_id_customer, 'G_Buildout', true)) || (get_user_meta($user_id_customer, 'G_Buildout', true) > 0)) {
//                                                echo '(+'/get_user_meta($user_id_customer, 'G_Buildout', true);
//                                            } else {
//                                                echo '(+'.get_post_meta(1, 'G_Buildout', true);
//                                            }
//                                            echo '%)<br />';
//                                        }
									//echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
								}
							}
						}
						if ($bposttype == 0 && $bayposttype) {
							echo 'B-post Type: <strong>' . $bayposttype;
							if ($bayposttype == 'flexible') {
								if (!empty(get_user_meta($user_id_customer, 'B_typeFlexible', true)) || (get_user_meta($user_id_customer, 'B_typeFlexible', true) > 0)) {
									echo '(+' . get_user_meta($user_id_customer, 'B_typeFlexible', true) . '%)';
								} else {
									echo '(+' . get_post_meta(1, 'B_typeFlexible', true) . '%)';
								}
							}
							echo '</strong>';
							echo '<br>';
							$bposttype++;
						}
						if ($tposttype_count == 0 && $tposttype) {
							echo 'T-Post Style: <strong>' . $tposttype;
							if ($tposttype == 'adjustable') {
								if (!empty(get_user_meta($user_id_customer, 'T_typeAdjustable', true)) || (get_user_meta($user_id_customer, 'B_typeFlexible', true) > 0)) {
									echo '(+' . get_user_meta($user_id_customer, 'T_typeAdjustable', true) . '%)';
								} else {
									echo '(+' . get_post_meta(1, 'T_typeAdjustable', true) . '%)';
								}
							}
							echo '</strong>';
							echo '<br>';
							$tposttype_count++;
						}
						?>
						<?php
						if (!empty(get_post_meta($product_id, 'property_t1', true))) {
							if ($property_tposttype) { ?> T-Post Type:
                                <strong>
									<?php
									echo $atributes[$property_tposttype]; ?></strong>
                                <br>
								<?php
							}
						} ?>
						<?php
						if ($property_sparelouvres == 'Yes') { ?> Include 2 x Spare Louvre:
                            <strong>
								<?php
								echo $property_sparelouvres; ?></strong> (+6£)
                            <br>
							<?php
						} ?>
						<?php
						if ($property_ringpull == 'Yes') { ?> Ring Pull:
                            <strong>
								<?php
								echo $property_ringpull; ?></strong> (+35£)
                            <br>
                            How many rings?:
                            <strong>
								<?php
								echo $property_ringpull_volume; ?></strong>
                            <br>
							<?php
						} ?>
						<?php
						if ($property_locks == 'Yes' || $property_central_lock == "Yes") { ?> Locks:
                            <strong>Yes</strong>
							<?php
							if (!empty(get_user_meta($user_id_customer, 'Lock', true)) || (get_user_meta($user_id_customer, 'Lock', true) > 0)) {
								echo '(+' . get_user_meta($user_id_customer, 'Lock', true) . '£)';
							} else {
								echo '(+' . get_post_meta(1, 'Lock', true) . '£)';
							} ?>
                            <br>
                            <!--              How many locks?:-->
                            <!--              <strong>-->
                            <!--								--><?php
							//								echo $property_locks_volume; ?><!-- set(s)</strong>-->
                            <!--              <br>-->
                            Lock Position:
                            <strong>
								<?php
								$lock_position = ($property_locks == "Yes") ? "Top & Bottom Locks" : "Central Lock";
								echo $lock_position; ?></strong>
                            <br>
                            <!--              Louver Lock:-->
                            <!--              <strong>-->
                            <!--								--><?php
//								echo $property_louver_lock; ?><!--</strong>-->
                            <!--              <br>-->
							<?php
						} ?>
                        Notes:<strong>
							<?php
							if ($property_nowarranty == "Yes") {
								echo 'OUT OF WARRANTY ACCEPTED <br>';
							}
							echo $comments_customer; ?></strong>

						<?php
						$pictures = get_post_meta($product_id, 'pictures', true);
						if (!empty($pictures)) {
							echo '<br><span>Images:</span>';
							// Wrapper-ul de imagini va sta pe același rând cu span-ul
							echo '<div style="display: inline-block; margin-left: 10px;">';

							$img_index = 1;
							foreach ($pictures as $imageUrl) {
								// Fiecare 'imagine' e afișată într-un container cu display:inline-block;
								echo '<div style="display: inline-block; margin-right: 15px;">';
								echo '<a href="' . esc_url($imageUrl) . '" target="_blank">';
								echo 'Image-' . $img_index;
								echo '</a>';
								echo '</div>';
								$img_index++;
							}
							echo '</div>';
						}
						?>
                    </td>
                    <td>
						<?php
						if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
							$materials = array(187 => 'Earth', 137 => 'Green', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood', 5 => 'EcowoodPlus');
							$price_for_update = get_post_meta($order_id, 'price_for_update', true);
							foreach ($materials as $key => $material) {
								if ($property_material == $key) {
									$price_for_update = get_post_meta($order_id, 'price_for_update', true);
									if ($price_for_update == 'old') {
										$material_price = get_post_meta($product_id, 'price_item_' . $material, true);
									} else {
										if (!empty(get_user_meta($user_id, $material, true)) || (get_user_meta($user_id, $material, true) > 0)) {
											$material_price = get_user_meta($user_id, $material, true);
										} else {
											$material_price = get_post_meta(1, $material, true);
										}
									}
									$material_price = get_post_meta($product_id, 'price_item_' . $material, true);
								}
//                                else {
//                                    if (!empty(get_user_meta($user_id, $atributes[$property_material], true)) || (get_user_meta($user_id, $atributes[$property_material], true) > 0)) {
//                                        $material_price = get_user_meta($user_id, $atributes[$property_material], true);
//                                    } else {
//                                        $material_price = get_post_meta(1, $atributes[$property_material], true);
//                                    }
//                                }
							}
							if ($property_style == 27 || $property_style == 28) {
								// show base earth price
								echo '£ ' . number_format($base_price_earth, 2);
							} else {
								echo '£ ' . $material_price;
							}
						}
						?>
                    </td>
                    <td>
                        <strong>
							<?php
							echo number_format((double)$property_total, 2); ?> sq/m </strong>
                        <br>
						<?php
						$quantity = 1;
						$quantity_prod = get_post_meta($product_id, 'quantity', true);
						if (!empty($quantity_prod)) {
							$quantity = $quantity_prod;
						} else {
							$quantity = isset($item_data['quantity']) ? intval($item_data['quantity']) : 0;
						}
						echo 'Qty:  <strong>' . $quantity . '</strong>'; ?>
                        <br> Width:<strong>
							<?php
							echo $property_width; ?></strong>
                        <br> Height:<strong>
							<?php
							echo $property_height; ?></strong>
                        <br>
                    </td>
                    <td>
						<?php

						if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
							// Convert $price to a float and ensure quantity is an integer.
							$price = floatval($price);
							$quantity = 1;
							$quantity_prod = get_post_meta($product_id, 'quantity', true);
							if (!empty($quantity_prod)) {
								$quantity = $quantity_prod;
							} else {
								$quantity = isset($item_data['quantity']) ? intval($item_data['quantity']) : 0;
							}

// Now perform the multiplication
							$total = $price * $quantity;

							$formatted_total = number_format($total, 2);  // Then format the result
							echo '£' . $formatted_total;
						}
						// echo '<br>' . $product->get_price();
						?>
                    </td>
					<?php
					//                        if (current_user_can('china_admin')) {
					//                            echo '<td></td>';
					//                        } else {
					//
					if ($edit == 'true' && $admin == 'false') { ?>
                        <td>
							<?php
							$pieces = explode("-", $title);
							if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter') || ($property_category === 'prod1') || ($pieces[0] == 'prod1')) {
								?>
                            <a class="btn btn-primary transparent"
                               href="/prod1-all/?id=<?php
							   echo $product_id * 1498765 * 33;
							   echo $edit_placed_order; ?>">Edit</a><?php
							} elseif (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')) {
								?>
                            <a class="btn btn-primary transparent"
                               href="/prod2-all/?id=<?php
							   echo $product_id * 1498765 * 33;
							   echo $edit_placed_order; ?>">Edit</a><?php
							} elseif ($property_category === 'Batten') {
								?>
                            <a class="btn btn-primary transparent"
                               href="/product5-edit/?id=<?php
							   echo $product_id * 1498765 * 33;
							   echo $edit_placed_order; ?>">Edit</a><?php
							}
							if (get_page_template_slug($post->ID) != 'template-orderedit.php') {

//   if (($property_category === 'Shutter & Blackout Blind') || ($pieces[0] == 'Shutter & Blackout Blind') || ($property_category === 'prod2') || ($pieces[0] == 'prod2')
								$clone_categories = array(
								  'Shutter' => '/prod1-all/',
								  'Shutter & Blackout Blind' => '/prod2-all/',
								  'Batten' => '/product5-edit/',
								);
								$prodId_encode = base64_encode($product_id);
								?>
                                <br>
                                <a
                                  class="btn btn-primary clone-item"
                                  item-id="<?php echo $item_id; ?>"
                                  href="<?php echo $clone_categories[$property_category] . '?clone=' . $prodId_encode; ?>"
                                >
                                    Clone
                                </a>
                                <br>
								<?php
								echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
								  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
								  esc_url(wc_get_cart_remove_url($item_id)),
								  __('Remove this item', 'woocommerce'),
								  esc_attr($product_id),
								  esc_attr($_product->get_sku())
								));
							} elseif (get_page_template_slug($post->ID) == 'template-orderedit.php') {
								?>
                                <br>
                            <buttton class="btn btn-danger delete-item"
                                     item-id="<?php
							         echo $item_id; ?>">
                                    Delete
                                </buttton><?php
							}
							?>
                        </td>
						<?php
					} ?>
					<?php
					if ($admin == 'true') { ?>
                        <td>
							<?php
							$pieces = explode("-", $title);
							$property_category;
							if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
								?>
                            <a class="btn btn-primary transparent" target="_blank"
                               href="/prod1-all/?order_id=<?php
							   echo $order_id * 1498765 * 33; ?>&id=<?php
							   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
							   echo $user_id_customer; ?>&item_id=<?php
							   echo $item_id; ?>">
                                    Edit</a><?php
							} elseif ($property_category === 'Shutter & Blackout Blind' || ($pieces[0] == 'Shutter & Blackout Blind')) {
								?>
                            <a class="btn btn-primary transparent" target="_blank"
                               href="/prod2-all/?order_id=<?php
							   echo $order_id * 1498765 * 33; ?>&id=<?php
							   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
							   echo $user_id_customer; ?>&item_id=<?php
							   echo $item_id; ?>">
                                    Edit</a><?php
							} elseif ($property_category === 'Batten') {
								?>
                            <a class="btn btn-primary transparent" target="_blank"
                               href="/product5-admin/?order_id=<?php
							   echo $order_id * 1498765 * 33; ?>&id=<?php
							   echo $product_id * 1498765 * 33; ?>&cust_id=<?php
							   echo $user_id_customer; ?>&item_id=<?php
							   echo $item_id; ?>">
                                    Edit</a><?php
							}

							?>
                        </td>
						<?php
					}
					// }
					?>
                </tr>
				<?php
			}
		}
	} ?>
	<?php
	if ($term_slug != 'components-fob') {
		$excl_vat = 'excl. VAT';
	} else {
		$excl_vat = 'FOB China';
	}
	if (!current_user_can('china_admin') && $view_price || current_user_can('administrator')) {
		if ($edit === 'false' || $admin === 'true' || $template_order_edit_customer) {
			// Determină prețul de tren corect
			$train_price = null;

// Dacă există comandă, încearcă să găsești prețul din primul produs cu price_item_train
			if (!empty($order_id) && $order) {
				foreach ($items as $item_id => $item_data) {
					$product_id = $item_data['product_id'];
					$train_price = get_post_meta($product_id, 'price_item_train', true);
					if ($train_price > 0) {
						break; // Folosește primul preț găsit
					}
				}
			}

// Dacă nu s-a găsit preț salvat, folosește prețul default
			if ($train_price === null) {
				$train_price = get_user_meta($user_id, 'train_price', true);
				if (empty($train_price)) {
					$train_price = get_post_meta(1, 'train_price', true);
				}
			}

// Asigură-te că train_price este un număr valid
			$train_price = floatval($train_price);

			?>
            <tr class="table-totals-delivery">
                <td colspan="4" style="text-align:right">Sea freight:</td>
                <td>£<?php echo number_format((double)$train_price, 2); ?> sq/m</td>
                <td class="quantity"><?php echo number_format($total_sqm, 2); ?> sq/m</td>
                <td id="total_box" class="amount">
					<?php echo $fob_components ? '$' . number_format($total_prods_delivery, 2) : '£', number_format($total_prods_delivery, 2); ?>
                </td>

            </tr>
            <tr class="table-totals">
                <td colspan="4" style="text-align:right">Products Total (<?php
					echo $excl_vat; ?>):
                </td>
                <td></td>
                <td class="quantity"></td>
                <td id="total_box" class="amount">
					<?php
					echo $fob_components ? '$' . number_format((double)$order->get_subtotal(), 2) : '£' . number_format((double)$order->get_subtotal(), 2);
					?>
                </td>

            </tr>
			<?php
			if ($term_slug != 'components-fob' && (!current_user_can('china_admin') && $view_price || current_user_can('administrator'))) {
				?>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right">Local delivery :</td>
                    <td></td>
                    <td class="quantity"></td>
                    <!--                <td class="amount">$-->
					<?php
					//echo $order_data['shipping_total'] + $tax_shipping_total; ?><!--</td>-->
                    <td class="amount">£<?php
						echo number_format($order_data['shipping_total'], 2) ?></td>

                </tr>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right">VAT:</td>
                    <td></td>
                    <td class="quantity"></td>
                    <td class="amount">£<?php
						echo number_format($order_data['total_tax'], 2); ?></td>

                </tr>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right"><strong>Gross Total:</strong></td>
                    <td></td>
                    <td class="quantity"></td>
                    <td class="amount">
                        <strong>£<?php
							echo number_format((double)$order->get_total(), 2); ?>
                        </strong>
                    </td>

                </tr>
				<?php
			}
			?>
			<?php
		}
	} ?>
    </tbody>
</table>
<style>
    .table-bordered th, .table-bordered tr td {
        border: 1px solid;
    }

    tr.table-totals td {
        vertical-align: unset;
    }

    #example2 td, #example td {
        vertical-align: middle !important;
    }
</style>

<script>

    jQuery('.btn.btn-danger.delete-item').click(function (e) {
        e.preventDefault();
        var item_id = jQuery(this).attr('item-id');
        var order_ID = <?php echo json_encode($order_id); ?>;
        console.log(item_id);

        jQuery.ajax({
            method: "POST",
            url: "/wp-content/themes/storefront-child/ajax/delete-order-edited-item.php",
            data: {
                item_id: item_id,
                order_id: order_ID
            }
        })
            .done(function (data) {
                console.log(data);
                setTimeout(function () {
                    location.reload();
                }, 500);

            });
    });

</script>

<?php
//function priceItemMaterialCustom($property_material, $user_id, $post_id)
//{
//    $materials = array(187 => 'Earth', 137 => 'Green', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood', 5 => 'EcowoodPlus');
//
//    foreach ($materials as $key => $material) {
//        if ($property_material == $key) {
//            if (!empty(get_post_meta($post_id, 'price_item_' . $material, true))) {
//                $material_price = get_post_meta($post_id, 'price_item_' . $material, true);
//            } else {
//                if (!empty(get_user_meta($user_id, $material, true)) || (get_user_meta($user_id, $material, true) > 0)) {
//                    $material_price = get_user_meta($user_id, $material, true);
//                } else {
//                    $material_price = get_post_meta(1, $material, true);
//                }
//            }
//        } else{
//            $material_price = 0;
//        }
//    }
//    return $material_price;
//}
?>
