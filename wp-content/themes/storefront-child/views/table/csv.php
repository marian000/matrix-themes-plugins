<?php
$table_class = $attributes['table_class'];
$table_id = $attributes['table_id'];
$order_id = $attributes['order_id'];
$edit = $attributes['editable'];
$admin = $attributes['admin'];

if ($order_id) {
	$order = wc_get_order($order_id);
	$order_data = $order->get_data(); // The Order data
	foreach ($order->get_items('tax') as $item_id => $item_tax) {
		$tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
	}
}

//$order = new WC_Order( $order_id );
$items = $order->get_items();
$order_data = $order->get_data();
// echo '<pre>';
// print_r($order_data);
// echo '</pre>';

$i = 0;
$atributes = get_post_meta(1, 'attributes_array_csv', true);
//print_r($atributes);
//Iterating through each "line" items in the order
foreach ($order->get_items() as $item_id => $item_data) {

	// Get an instance of corresponding the WC_Product object
	$product = $item_data->get_product();
	$product_name = $product->get_name(); // Get the product name

	$item_quantity = $item_data->get_quantity(); // Get the item quantity

	$item_total = $item_data->get_total(); // Get the item line total

	// Displaying this data (to check)
	//echo 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. number_format( $item_total, 2 );
}

$nr_code_prod = array();
foreach ($order->get_items() as $prod => $item_data) {
	$i++;
	$product = $item_data->get_product();
	$product_id = $product->id;

	$nr_g = get_post_meta($product_id, 'counter_g', true);
	$nr_t = get_post_meta($product_id, 'counter_t', true);
	$nr_b = get_post_meta($product_id, 'counter_b', true);
	$nr_c = get_post_meta($product_id, 'counter_c', true);

	$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
	if ($property_nr_sections) {
		$individual_counter = get_post_meta($product_id, 'individual_counter', true);
		for ($sec = 1; $sec <= $property_nr_sections; $sec++) {
			$nr_b = $individual_counter[$sec]['counter_b'];
			$nr_t = $individual_counter[$sec]['counter_t'];
			$nr_c = $individual_counter[$sec]['counter_c'];
			$nr_g = $individual_counter[$sec]['counter_g'];

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
//    print_r($individual_counter);
//    print_r($nr_code_prod);

	$_product = wc_get_product($product_id);
	$shipclass = $_product->get_shipping_class();

	if ($shipclass == 'ship') {
		$transport = 'BY SEA';
	} elseif ($shipclass == 'air') {
		$transport = 'BY AIR';
	}
}

$email_body = '
<table id="' . $table_id . '" style="width:100%" class="' . $table_class . '">
                <thead>
                    <tr>
                            <th>Customer:</th>
                            <th>' . $order_data['billing']['company'] . '</th>
                            <th>Client details:</th>
                            <th>' . get_post_meta($order->get_id(), 'cart_name', true) . '</th>
                            <th>Order date:</th>
                            <th>' . $order_data['date_created']->date('Y-m-d H:i:s') . '</th>
                    </tr>
                    <tr>
                            <th>Reference no:</th>
                            <th>LF0' . $order->get_order_number() . '</th>
                            <th>Clients address:</th>
                            <th>' . $order_data['billing']['address_1'] . '</th>
                            <th>Clients contact:</th>
                            <th>' . $order_data['billing']['phone'] . ' ' . $order_data['billing']['email'] . '</th>
                    </tr>
                    <tr>
                    <th>Delivery method:</th>
                    <th>' . $transport . '</th>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th>item</th>
                        <th>material</th>
                        <th>Room</th>
                        <th>instalation style</th>
                        <th>Shape</th>
                        <th>Draw</th>
                        <th>how many at this size</th>
                        <th>sqm</th>
                        <th>width</th>
                        <th>height</th>
                        <th>depth</th>
                        <th>Midrail Height </th>
                        <th>Midrail Height 2</th>
                        <th>Hidden Divider 1</th>
                        <th>Hidden Divider 2</th>
                        <th>Solid Panel Height</th>
                        <th>Solid Type</th>
                        <th>Position is Critical</th>
                        <th>T-o-T Height</th>
                        <th>Horizontal T Post </th>
                        <th>Double closing louvres </th>
                        <th>Louver</th>
                        <th>Tracked type</th>
                        <th>Free folding</th>
                        <th>By-pass Type</th>
                        <th>Light Blocks</th>
                        <th>Measure Type</th>
                        <th>frame type</th>
                        <th>stile type</th>
                        <th>Frame Left</th>
                        <th>Frame Right</th>
                        <th>Frame Top</th>
                        <th>Frame Bottom</th>
                        <th>Buildout</th>
                        <th>Hinge Colour</th>
                        <th>Shutter Colour</th>
                        <th>Other Colour</th>
                        <th>Blackout Blind Colour</th>
                        <th>Control Type</th>
                        <th>Nr. Tracks</th>
                        <th>Layout code</th>
                        <th>Motorised</th>
                        <th>Nr motors</th>
                        <th>Nr remotes</th>
                        <th>Open first</th>';
foreach ($nr_code_prod as $key => $val) {
	for ($i = 1; $i < $val + 1; $i++) {
		if ($key == 'b') {
			$email_body .= '<th>' . strtoupper($key) . 'p' . $i . '</th>';
			$email_body .= '<th>' . strtoupper($key) . 'a' . $i . '</th>';
		} else {
			$email_body .= '<th>' . strtoupper($key) . ' ' . $i . '</th>';
		}
	}
}

$email_body .= '
<th>B-post Type</th>
<th>BPosts Buildout</th>
                        <th>CPosts Buildout</th>
                        <th>TPosts Buildout</th>
                        <th>T-post Style</th>';
$email_body .= '<th>T-Post Type</th>
                        <th>Include 2 x Spare Louvre</th>
                        <th>Ring Pull</th>
                        <th>How many rings?</th>
                        <th>Locks</th>
                        <th>How many locks?</th>
                        <th>Lock Position</th>
                        <th>Louver Lock</th>
                        <th>notes</th>
                    </tr>
                </thead>
                <tbody>';
$array_att = array();
$k = 0;
foreach ($order->get_items() as $prod => $item_data) {
	$i++;
	$k++;
	$product = $item_data->get_product();
	$product_id = $product->id;

	$property_room_other = get_post_meta($product_id, 'property_room_other', true);
	$property_style = get_post_meta($product_id, 'property_style', true);
	$property_frametype = get_post_meta($product_id, 'property_frametype', true);

	$attachment = get_post_meta($product_id, 'attachment', true);
	$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
	$array_att[] = $attachment;

	$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
	$property_category = get_post_meta($product_id, 'shutter_category', true);
	$property_material = get_post_meta($product_id, 'property_material', true);
	$property_width = get_post_meta($product_id, 'property_width', true);
	$property_height = get_post_meta($product_id, 'property_height', true);
	$property_midrailheight = get_post_meta($product_id, 'property_midrailheight', true);
	$property_midrailheight2 = get_post_meta($product_id, 'property_midrailheight2', true);
	$property_midraildivider1 = get_post_meta($product_id, 'property_midraildivider1', true);
	$property_midraildivider2 = get_post_meta($product_id, 'property_midraildivider2', true);
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
	$property_builtout = get_post_meta($product_id, 'property_builtout', true);
	$property_stile = get_post_meta($product_id, 'property_stile', true);
	$property_hingecolour = get_post_meta($product_id, 'property_hingecolour', true);
	print_r($atributes[$property_hingecolour]);

	$property_shuttercolour = get_post_meta($product_id, 'property_shuttercolour', true);
	$property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
	$property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
	$property_controltype = get_post_meta($product_id, 'property_controltype', true);
	$property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
	$property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
	$property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
	$property_layoutcode_tracked = get_post_meta($product_id, 'property_layoutcode_tracked', true);
	$property_opendoor = get_post_meta($product_id, 'property_opendoor', true);
	$bayposttype = get_post_meta($product_id, 'bay-post-type', true);
	$tposttype = get_post_meta($product_id, 't-post-type', true);
	$property_t1 = get_post_meta($product_id, 'property_t1', true);
	$property_tposttype = get_post_meta($product_id, 'property_tposttype', true);
	$property_total = get_post_meta($product_id, 'property_total', true);
	$property_depth = get_post_meta($product_id, 'property_depth', true);
	$property_volume = get_post_meta($product_id, 'property_volume', true);
	$property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);
	$property_solidpanelheight = get_post_meta($product_id, 'property_solidpanelheight', true);
	$property_solidtype = get_post_meta($product_id, 'property_solidtype', true);
	$property_motorized = get_post_meta($product_id, 'property_motorized', true);
	$property_nrMotors = get_post_meta($product_id, 'property_nrMotors', true);
	$property_nrRemotes = get_post_meta($product_id, 'property_nrRemotes', true);

	$property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
	$property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
	$property_locks = get_post_meta($product_id, 'property_locks', true);
	$property_central_lock = get_post_meta($product_id, 'property_central_lock', true);
	$property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
	$property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);
	$property_lock_position = get_post_meta($product_id, 'property_lock_position', true);
	$property_louver_lock = get_post_meta($product_id, 'property_louver_lock', true);
	$price = get_post_meta($product_id, '_price', true);
	$regular_price = get_post_meta($product_id, '_regular_price', true);
	$sale_price = get_post_meta($product_id, '_sale_price', true);
	$svg_product = get_post_meta($product_id, 'svg_product', true);
	$comments_customer = get_post_meta($product_id, 'comments_customer', true);
	$property_nowarranty = get_post_meta($product_id, 'property_nowarranty', true);

	if (!empty($property_layoutcode_tracked)) {
		$property_layoutcode = $property_layoutcode_tracked;
	}

	$term_slug = "";
	$item_quantity = $item_data->get_quantity(); // Get the item quantity

	$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

	if (is_array($term_list) && !empty($term_list)) {
		// Check if $term_list[0] is an object
		if (is_object($term_list[0])) {
			$term_slug = $term_list[0]->slug;
			if ($term_slug == 'components-fob') {
				$fob_components = true;
			}
		}
	}

	if ($product_id == 337 || $product_id == 72951) {
	} elseif ($term_slug == 'pos') {
	} elseif ($property_category === 'Batten') {
		$batten_qnt = get_post_meta($product_id, 'quantity', true);
		$email_body .= '<tr>
                    <td>' . $k . '</td>
                    <td>' . $atributes[$property_material] . '</td>
                    <td>' . $property_room_other . '</td>
                    <td>Batten</td>
                    <td>';
		if (!empty($attachment)) {
			$email_body .= 'yes <br><a href="' . $attachment . '" target="_blank">img link</a> ';
		} else {
			$email_body .= 'no';
		}
		$email_body .= '</td><td>';
		if (!empty($attachmentDraw)) {
			$email_body .= 'yes <br><a href="' . $attachmentDraw . '" target="_blank">img Draw link</a> ';
		} else {
			$email_body .= 'no';
		}
		$email_body .= '</td>
                    <td>' . $batten_qnt . '</td>
                    <td>' . number_format((double)$property_total, 5) * number_format((double)$batten_qnt, 2) . '</td>
                    <td>' . $property_width . '</td>
                    <td>' . $property_height . '</td>
                    <td>' . $property_depth . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . $property_totheight . '</td>
                    <td>' . $property_horizontaltpost . '</td>
                    <td>' . $property_doubleClosingLouvres . '</td>
                    <td>' . $atributes[$property_bladesize] . '</td>
                    <td>' . $property_trackedtype . '</td>
                    <td>' . $property_freefolding . '</td>
                    <td>' . $property_bypasstype . '</td>
                    <td>' . $property_lightblocks . '</td>
                    <td>' . $atributes[$property_fit] . '</td>
                    <td>' . $atributes[$property_frametype] . '</td>
                    <td>' . $atributes[$property_stile] . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>' . $property_builtout . '</td>
                    <td>' . $atributes[$property_hingecolour] . '</td>
                    <td>' . $atributes[$property_shuttercolour] . '</td>
                    <td>' . $property_shuttercolour_other . '</td>
                    <td>' . $atributes[$property_blackoutblindcolour] . '</td>
                    <td>' . $atributes[$property_controltype] . '</td><td></td>';

		if ($property_style == 37) {
			if ($property_tracksnumber) {
				$email_body .= ' <td>' . $property_tracksnumber . '</td>';
			}
		} else {
			$email_body .= ' <td></td>';
		}
		$motorizedNR = $property_motorized == 'Yes' ? $property_nrMotors : ' ';
		$remotesNR = $property_motorized == 'Yes' ? $property_nrRemotes : ' ';

		$email_body .= '<td style="text-transform: uppercase;">' . strtoupper($property_layoutcode) . '</td>';
		$email_body .= '<td style="text-transform: uppercase;">' . $property_motorized . '</td>';
		$email_body .= '<td style="text-transform: uppercase;">' . $motorizedNR . '</td>';
		$email_body .= '<td style="text-transform: uppercase;">' . $remotesNR . '</td>';
		$email_body .= '<td>' . $property_opendoor . '</td>';
		foreach ($nr_code_prod as $key => $val) {
			for ($i = 1; $i < $val + 1; $i++) {
				if ($val > 0) {
					if ($key == 'b') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_bp' . $i, true) . '</td>';
						$email_body .= '<td>' . get_post_meta($product_id, 'property_ba' . $i, true) . '</td>';
					} elseif ($key == 'c') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_c' . $i, true) . '</td>';
					} elseif ($key == 't') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_t' . $i, true) . '</td>';
					} elseif ($key == 'g') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_g' . $i, true) . '</td>';
					}
				} else {
					$email_body .= '<td>1</td>';
				}
			}
		}
		// show builout if letter is in code
		$email_body .= '<td>' . get_post_meta($product_id, 'bay-post-type', true) . '</td>';
		$email_body .= '<td>' . get_post_meta($product_id, 'property_b_buildout1', true) . '</td>';
		$email_body .= '<td>' . get_post_meta($product_id, 'property_c_buildout1', true) . '</td>';
		$email_body .= '<td>' . get_post_meta($product_id, 'property_t_buildout1', true) . '</td>';
		$email_body .= '<td>' . $tposttype . '</td>';

		$email_body .= '<td>';
		if (!empty(get_post_meta($product_id, 'property_t1', true))) {
			$email_body .= $atributes[$property_tposttype];
		} else {
			$email_body .= ' ';
		}
		$email_body .= '</td>
                        <td>' . $property_sparelouvres . '</td>
                        <td>' . $property_ringpull . '</td>
                        <td>';
		if ($property_ringpull == "Yes") {
			$email_body .= $property_ringpull_volume;
		} else {
			$email_body .= '';
		}
		$email_body .= '</td>';
		if ($property_locks == "Yes" || $property_central_lock == "Yes") {
			$lock_position = ($property_locks == "Yes") ? "Top & Bottom Locks" : "Central Lock";
			$email_body .= '<td>Yes</td>';
			$email_body .= '<td>' . $property_locks_volume . '</td>';
			$email_body .= '<td>' . $lock_position . '</td>';
			$email_body .= '<td>' . $property_louver_lock . '</td>';
		} else {
			$email_body .= '<td></td>';
			$email_body .= '<td></td>';
			$email_body .= '<td></td>';
			$email_body .= '<td></td>';
		}
		$email_body .= '<td>';
		if ($property_nowarranty == "Yes") {
			$email_body .= 'OUT OF WARRANTY ACCEPTED <br>';
		}
		$email_body .= $comments_customer . '</td>
            </tr>';
	} else {

		if (!empty($property_nr_sections)) {
			for ($sec = 1; $sec <= $property_nr_sections; $sec++) {

				$email_body .= '<tr>
                            <td>' . $k . '</td>
                            <td>' . $atributes[$property_material] . '</td>
                            <td>' . $property_room_other . ' - Section' . $sec . '</td>
                            <td>' . 'Individual Bay Window - ' . $atributes[$property_style];
				$email_body .= '</td>
                            <td>';
				if (!empty($attachment)) {
					$email_body .= 'yes <br><a href="' . $attachment . '" target="_blank">img link</a> ';
				} else {
					$email_body .= 'no';
				}
				$email_body .= '</td><td>';
				if (!empty($attachmentDraw)) {
					$email_body .= 'yes <br><a href="' . $attachmentDraw . '" target="_blank">img Draw link</a> ';
				} else {
					$email_body .= 'no';
				}
				$property_total_section = get_post_meta($product_id, 'property_total_section' . $sec, true);
				$email_body .= '</td>
                            <td>' . $item_quantity . '</td>
                            <td>' . number_format((double)$property_total_section, 2) * number_format((double)$item_quantity, 2) . '</td>
                            <td>' . get_post_meta($product_id, 'property_width' . $sec, true) . '</td>
                            <td>' . $property_height . '</td>
                            <td></td>
                            <td>' . $property_midrailheight . '</td>
                            <td>' . $property_midrailheight2 . '</td>
                            <td>' . $property_midraildivider1 . '</td>
                            <td>' . $property_midraildivider2 . '</td>
                            <td>' . $property_solidpanelheight . '</td>
                             <td>' . $property_solidtype . '</td>
                            <td>';

				if (!empty($atributes[$property_midrailpositioncritical])) {
					$email_body .= '' . $atributes[$property_midrailpositioncritical] . '';
				} else {
					$email_body .= 'No';
				}
				$email_body .= '</td>
                            <td>' . $property_totheight . '</td>
                            <td>' . $property_horizontaltpost . '</td>
                            <td>' . $property_doubleClosingLouvres . '</td>
                            <td>' . $atributes[$property_bladesize] . '</td>
                            <td>' . $property_trackedtype . '</td>
                            <td>' . $property_freefolding . '</td>
                            <td>' . $property_bypasstype . '</td>
                            <td>' . $property_lightblocks . '</td>
                            <td>' . $atributes[$property_fit] . '</td>
                            <td>' . $atributes[$property_frametype] . '</td>
                            <td>' . $atributes[$property_stile] . '</td>
                            <td>' . $atributes[$property_frameleft] . '</td>
                            <td>' . $atributes[$property_frameright] . '</td>
                            <td>' . $atributes[$property_frametop] . '</td>
                            <td>' . $atributes[$property_framebottom] . '</td>
                            <td>' . $property_builtout . '</td>
                            <td>' . $atributes[$property_hingecolour] . '</td>
                            <td>' . $atributes[$property_shuttercolour] . '</td>
                            <td>' . $property_shuttercolour_other . '</td>
                            <td>' . $atributes[$property_blackoutblindcolour] . '</td>
                            <td>' . $atributes[$property_controltype] . '</td>';
				if ($property_style == 37) {
					if ($property_tracksnumber) {
						$email_body .= ' <td>' . $property_tracksnumber . '</td>';
					}
				} else {
					$email_body .= ' <td></td>';
				}

				$email_body .= '<td style="text-transform: uppercase;">' . strtoupper(get_post_meta($product_id, 'property_layoutcode' . $sec, true)) . '</td>';
				$email_body .= '<td style="text-transform: uppercase;">' . $property_motorized . '</td>';
				$email_body .= '<td style="text-transform: uppercase;">' . $motorizedNR . '</td>';
				$email_body .= '<td style="text-transform: uppercase;">' . $remotesNR . '</td>';
				$email_body .= '<td>' . get_post_meta($product_id, 'property_opendoor' . $sec, true) . '</td>';
				foreach ($nr_code_prod as $key => $val) {
					for ($i = 1; $i < $val + 1; $i++) {
						if ($key == 'b') {
							$email_body .= '<td>' . get_post_meta($product_id, 'property_bp' . $i . '_' . $sec, true) . '</td>';
							$email_body .= '<td>' . get_post_meta($product_id, 'property_ba' . $i . '_' . $sec, true) . '</td>';
						} elseif ($key == 'c') {
							$email_body .= '<td>' . get_post_meta($product_id, 'property_c' . $i . '_' . $sec, true) . '</td>';
						} elseif ($key == 't') {
							$email_body .= '<td>' . get_post_meta($product_id, 'property_t' . $i . '_' . $sec, true) . '</td>';
						} elseif ($key == 'g') {
							$email_body .= '<td>' . get_post_meta($product_id, 'property_g' . $i . '_' . $sec, true) . '</td>';
						}
					}
				}
				$email_body .= '<td>' . get_post_meta($product_id, 'bay-post-type' . '_' . $sec, true) . '</td>';
				$email_body .= '<td>' . get_post_meta($product_id, 'property_b_buildout1' . '_' . $sec, true) . '</td>';
				$email_body .= '<td>' . get_post_meta($product_id, 'property_c_buildout1' . '_' . $sec, true) . '</td>';
				$email_body .= '<td>' . get_post_meta($product_id, 'property_t_buildout1' . '_' . $sec, true) . '</td>';
				$tposttypeSec = get_post_meta($product_id, 't-post-type' . $sec, true);
				$email_body .= '<td>' . $tposttypeSec . '</td>';
				$email_body .= '<td>';
				if (!empty(get_post_meta($product_id, 'property_t1' . '_' . $sec, true))) {
					$email_body .= $atributes[$property_tposttype];
				} else {
					$email_body .= ' ';
				}
				$email_body .= '</td>
                                <td>' . $property_sparelouvres . '</td>
                                <td>' . $property_ringpull . '</td>
                                <td>';
				if ($property_ringpull == "Yes") {
					$email_body .= $property_ringpull_volume;
				} else {
					$email_body .= '';
				}
				$email_body .= '</td>';
				if ($property_locks == "Yes" || $property_central_lock == "Yes") {
					$lock_position = ($property_locks == "Yes") ? "Top & Bottom Locks" : "Central Lock";
					$email_body .= '<td>Yes</td>';
					$email_body .= '<td>' . $property_locks_volume . '</td>';
					$email_body .= '<td>' . $lock_position . '</td>';
					$email_body .= '<td>' . $property_louver_lock . '</td>';
				} else {
					$email_body .= '<td></td>';
					$email_body .= '<td></td>';
					$email_body .= '<td></td>';
					$email_body .= '<td></td>';
				}
				$email_body .= '<td>';
				if ($property_nowarranty == "Yes") {
					$email_body .= 'OUT OF WARRANTY ACCEPTED <br>';
				}
				$email_body .= $comments_customer . '</td>
                    </tr>';
			}
		} else {
			$email_body .= '<tr>
                            <td>' . $k . '</td>
                            <td>' . $atributes[$property_material] . '</td>
                            <td>' . $property_room_other . '</td>
                            <td>' . $atributes[$property_style];
			$email_body .= '</td>
                            <td>';
			if (!empty($attachment)) {
				$email_body .= 'yes <br><a href="' . $attachment . '" target="_blank">img link</a> ';
			} else {
				$email_body .= 'no';
			}
			$email_body .= '</td><td>';
			if (!empty($attachmentDraw)) {
				$email_body .= 'yes <br><a href="' . $attachmentDraw . '" target="_blank">img Draw link</a> ';
			} else {
				$email_body .= 'no';
			}
			$email_body .= '</td>
                            <td>' . $item_quantity . '</td>
                            <td>' . number_format((double)$property_total, 2) * number_format((double)$item_quantity, 2) . '</td>
                            <td>' . $property_width . '</td>
                            <td>' . $property_height . '</td>
                            <td></td>
                            <td>' . $property_midrailheight . '</td>
                            <td>' . $property_midrailheight2 . '</td>
                            <td>' . $property_midraildivider1 . '</td>
                            <td>' . $property_midraildivider2 . '</td>
                            <td>' . $property_solidpanelheight . '</td>
                             <td>' . $property_solidtype . '</td>
                            <td>';

			if (!empty($atributes[$property_midrailpositioncritical])) {
				$email_body .= '' . $atributes[$property_midrailpositioncritical] . '';
			} else {
				$email_body .= 'No';
			}
			$email_body .= '</td>
                            <td>' . $property_totheight . '</td>
                            <td>' . $property_horizontaltpost . '</td>
                            <td>' . $property_doubleClosingLouvres . '</td>
                            <td>' . $atributes[$property_bladesize] . '</td>
                            <td>' . $property_trackedtype . '</td>
                            <td>' . $property_freefolding . '</td>
                            <td>' . $property_bypasstype . '</td>
                            <td>' . $property_lightblocks . '</td>
                            <td>' . $atributes[$property_fit] . '</td>
                            <td>' . $atributes[$property_frametype] . '</td>
                            <td>' . $atributes[$property_stile] . '</td>
                            <td>' . $atributes[$property_frameleft] . '</td>
                            <td>' . $atributes[$property_frameright] . '</td>
                            <td>' . $atributes[$property_frametop] . '</td>
                            <td>' . $atributes[$property_framebottom] . '</td>
                            <td>' . $property_builtout . '</td>
                            <td>' . $atributes[$property_hingecolour] . '</td>
                            <td>' . $atributes[$property_shuttercolour] . '</td>
                            <td>' . $property_shuttercolour_other . '</td>
                            <td>' . ($atributes[$property_blackoutblindcolour] ?? '') . '</td>
                            <td>' . $atributes[$property_controltype] . '</td>';
			if ($property_style == 37) {
				if ($property_tracksnumber) {
					$email_body .= ' <td>' . $property_tracksnumber . '</td>';
				}
			} else {
				$email_body .= ' <td></td>';
			}

			$motorizedNR = $property_motorized == 'Yes' ? $property_nrMotors : ' ';
			$remotesNR = $property_motorized == 'Yes' ? $property_nrRemotes : ' ';

			$email_body .= '<td style="text-transform: uppercase;">' . strtoupper($property_layoutcode) . '</td>';
			$email_body .= '<td style="text-transform: uppercase;">' . $property_motorized . '</td>';
			$email_body .= '<td style="text-transform: uppercase;">' . $motorizedNR . '</td>';
			$email_body .= '<td style="text-transform: uppercase;">' . $remotesNR . '</td>';
			$email_body .= '<td>' . $property_opendoor . '</td>';
			foreach ($nr_code_prod as $key => $val) {
				for ($i = 1; $i < $val + 1; $i++) {
					if ($key == 'b') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_bp' . $i, true) . '</td>';
						$email_body .= '<td>' . get_post_meta($product_id, 'property_ba' . $i, true) . '</td>';
					} elseif ($key == 'c') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_c' . $i, true) . '</td>';
					} elseif ($key == 't') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_t' . $i, true) . '</td>';
					} elseif ($key == 'g') {
						$email_body .= '<td>' . get_post_meta($product_id, 'property_g' . $i, true) . '</td>';
					}
				}
			}
			$email_body .= '<td>' . get_post_meta($product_id, 'bay-post-type', true) . '</td>';
			$email_body .= '<td>' . get_post_meta($product_id, 'property_b_buildout1', true) . '</td>';
			$email_body .= '<td>' . get_post_meta($product_id, 'property_c_buildout1', true) . '</td>';
			$email_body .= '<td>' . get_post_meta($product_id, 'property_t_buildout1', true) . '</td>';
			$email_body .= '<td>' . $tposttype . '</td>';

			$email_body .= '<td>';
			if (!empty(get_post_meta($product_id, 'property_t1', true))) {
				$email_body .= $atributes[$property_tposttype];
			} else {
				$email_body .= ' ';
			}
			$email_body .= '</td>
                                <td>' . $property_sparelouvres . '</td>
                                <td>' . $property_ringpull . '</td>
                                <td>';
			if ($property_ringpull == "Yes") {
				$email_body .= $property_ringpull_volume;
			} else {
				$email_body .= '';
			}
			$email_body .= '</td>';
			if ($property_locks == "Yes" || $property_central_lock == "Yes") {
				$lock_position = ($property_locks == "Yes") ? "Top & Bottom Locks" : "Central Lock";
				$email_body .= '<td>Yes</td>';
				$email_body .= '<td>' . $property_locks_volume . '</td>';
				$email_body .= '<td>' . $lock_position . '</td>';
				$email_body .= '<td>' . $property_louver_lock . '</td>';
			} else {
				$email_body .= '<td></td>';
				$email_body .= '<td></td>';
				$email_body .= '<td></td>';
				$email_body .= '<td></td>';
			}
			$email_body .= '<td>';
			if ($property_nowarranty == "Yes") {
				$email_body .= 'OUT OF WARRANTY ACCEPTED <br>';
			}
			$email_body .= $comments_customer . '</td>
                    </tr>';
		}
	}
}
$email_body .= '
                 </tbody>
            </table>';

?>

<?php if ($admin == 'true') { ?>
	<?php echo $email_body; ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.js"></script>

  <script>
      function download_csv(csv, filename) {
          var csvFile;
          var downloadLink;

          // CSV FILE
          csvFile = new Blob([csv], {
              type: "text/csv"
          });

          // Download link
          downloadLink = document.createElement("a");

          // File name
          downloadLink.download = filename;

          // We have to create a link to the file
          downloadLink.href = window.URL.createObjectURL(csvFile);

          console.log(downloadLink.href);
          jQuery('.url_down').attr('href', downloadLink.href);
          // Make sure that the link is not displayed
          downloadLink.style.display = "none";

          // Add the link to your DOM
          document.body.appendChild(downloadLink);

          // Lanzamos
          downloadLink.click();
      }

      function export_table_to_csv(html, filename) {
          var csv = [];
          var rows = document.querySelectorAll(".show_tabble > table#example tr");

          for (var i = 0; i < rows.length; i++) {
              var row = [],
                  cols = rows[i].querySelectorAll("td, th");

              for (var j = 0; j < cols.length; j++)
                  row.push(cols[j].innerText);

              csv.push(row.join(","));
          }

          // Download CSV
          download_csv(csv.join(" \n "), filename);

          console.log(csv);
          console.log(filename);
      }

      jQuery("button.download").on("click", function (e) {
          e.preventDefault();
          var html = document.querySelector("#external-csv.show_tabble > table#example").outerHTML;
          export_table_to_csv(html, "table-order.csv");

          //console.log(html);

      });


      jQuery(document).ready(function () {


          jQuery('#send_mail.send-m').on('click', function (e) {
              e.preventDefault();

              var content = jQuery('textarea[name="table"]').val();
              var id = jQuery('input[name="id_ord"]').val();
              var id_ord_original = jQuery('input[name="id_ord_original"]').val();
              var name = jQuery('input[name="order_name"]').val();
              var items_buy = jQuery('#items-info').html();

              var data = {
                  table: content,
                  id: id,
                  id_ord_original: id_ord_original,
                  name: name,
                  items_table: items_buy
              }
              console.log('send mail: ', data);

              jQuery.ajax({
                  method: "POST",
                  url: "/wp-content/themes/storefront-child/csvs/create-csv-admin.php",
                  data: {
                      table: content,
                      id: id,
                      id_ord_original: id_ord_original,
                      name: name,
                      items_table: items_buy
                  }
              })
                  .done(function (msg) {
                      alert("mail send");
                      console.log("Data Saved: " + msg);
                  });

          });


          jQuery('#send_qcuickbooks_invoice').on('click', function (e) {
              e.preventDefault();
              console.log('send_qcuickbooks_invoice clicked');


              var id_ord_original = jQuery('input[name="id_ord_original"]').val();
              var name = jQuery('input[name="order_name"]').val();

              jQuery.ajax({
                  method: "POST",
                  url: "/wp-content/themes/storefront-child/quickBooks/InvoiceAndBilling.php",
                  data: {
                      id_ord_original: id_ord_original,
                      name: name
                  }
              })
                  .done(function (data) {

                      alert('QuickBooks Invoice Created!');
                      console.log("QuickBooks Invoice: " + data);

                  });

          });

      });


      // cand se incarca pagina se verifica pretul de la fiecare item

  </script>

  <input type="hidden" name="id_ord_original" value="<?php echo $order_id; ?>">
  <input type="hidden" name="id_ord" value="<?php echo $order->get_order_number(); ?>">

  <textarea style="display: none;" name="table" id="tableToMakeMail" cols="30"
            rows="10"><?php echo $email_body; ?></textarea>

<?php } else { ?>
  <textarea style="display: none;" name="table" id="tableToMakeMail" cols="30"
            rows="10"><?php echo $email_body; ?></textarea>
<?php } ?>
