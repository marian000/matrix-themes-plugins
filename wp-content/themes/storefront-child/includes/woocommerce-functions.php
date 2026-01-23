<?php

/**
 * @snippet       Always Show Variation Price @ WooCommerce Single Product
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */

/**
 * Add a custom text input field to the product page
 */
function component_add_text_field()
{
?>
	<div class="comp-note-wrap">
		<label for="comp-note">Add note: </label>
		<textarea name="comp-note" id="comp-note" cols="5" rows="5"></textarea>
	</div>
	<br>
<?php }


add_action('woocommerce_before_add_to_cart_button', 'component_add_text_field');

/**
 * Add custom cart item data
 */
function component_add_cart_item_data($cart_item_data, $product_id, $variation_id)
{
	if (isset($_POST['comp-note'])) {
		$cart_item_data['comp_note'] = sanitize_text_field($_POST['comp-note']);
	}
	return $cart_item_data;
}


add_filter('woocommerce_add_cart_item_data', 'component_add_cart_item_data', 10, 3);

/**
 * Display custom item data in the cart
 */
function component_get_item_data($item_data, $cart_item_data)
{
	if (isset($cart_item_data['comp_note'])) {
		$item_data[] = array(
			'key' => __('comp_note', 'plugin-republic'),
			'value' => wc_clean($cart_item_data['comp_note']),
		);
	}
	return $item_data;
}


add_filter('woocommerce_get_item_data', 'component_get_item_data', 10, 2);

/**
 * Add custom meta to order
 */
function compoennt_checkout_create_order_line_item($item, $cart_item_key, $values, $order)
{
	if (isset($values['comp_note'])) {
		$item->add_meta_data(
			__('comp_note', 'plugin-republic'),
			$values['comp_note'],
			true
		);
	}
}


add_action('woocommerce_checkout_create_order_line_item', 'compoennt_checkout_create_order_line_item', 10, 4);

add_filter('woocommerce_show_variation_price', '__return_true');

/**
 * Add the custom inputs to the checkout
 **/
add_action('woocommerce_after_order_notes', 'my_custom_checkout_field');

function my_custom_checkout_field($checkout)
{
	echo '<div id="my_custom_checkout_field" class="hidden"><h3>' . __('My Field') . '</h3>';

	global $wpdb;
	$user_id = get_current_user_id();
	$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");
	$userialize_data = unserialize($addresses[0]->meta_value);
	//print_r($userialize_data);
	//echo '<br>'.$userialize_data['customer_id'];
	$id_cart = $userialize_data['customer_id'];
	foreach ($userialize_data['carts'] as $key => $carts) {

		if ($userialize_data['customer_id'] == $key) {
			$name = $carts['name'];
		}
	}

	/**
	 * Output the field. This is for 1.4.
	 *
	 * To make it compatible with 1.3 use $checkout->checkout_form_field instead:
	 **/
	woocommerce_form_field('my_field_name', array(
		'type' => 'text',
		'class' => array('my-field-class orm-row-wide'),
		'label' => __('Name Cart'),
		'placeholder' => __($name),
		'default' => $name,
	), $checkout->get_value($name));

	woocommerce_form_field('cart_id', array(
		'type' => 'text',
		'class' => array('my-field-class orm-row-wide'),
		'label' => __('ID Cart'),
		'placeholder' => __($id_cart),
		'default' => $id_cart,
	), $checkout->get_value($id_cart));

	echo '</div>';

	/**
	 * Optional Javascript to limit the field to a country. This one shows for italy only.
	 **/
?>
	<script type="text/javascript">
		jQuery('select#billing_country').on('change', function() {

			var country = jQuery('select#billing_country').val();

			var check_countries = new Array(<?php echo '"IT"'; ?>);
			if (country && jQuery.inArray(country, check_countries) >= 0) {
				jQuery('#my_custom_checkout_field').fadeIn();
			} else {
				jQuery('#my_custom_checkout_field').fadeOut();
				jQuery('#my_custom_checkout_field input').val('');
			}

		});
	</script>
<?php
}


/**
 * Update the order meta with field value
 **/
add_action('woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta');
function my_custom_checkout_field_update_order_meta($order_id)
{
	if ($_POST['my_field_name']) {
		update_post_meta($order_id, 'cart_name', $_POST['my_field_name']);
		if (empty($_POST['my_field_name'])) {
			update_post_meta($order_id, 'cart_name', 'NO NAME PROVIDED !!!');
		}
	}
	if ($_POST['cart_id']) {
		update_post_meta($order_id, 'cart_id', esc_attr($_POST['cart_id']));
	}
}


add_action('woocommerce_new_order', 'send_mails_customer', 1, 1);
function send_mails_customer($order_id)
{
	$order = new WC_Order($order_id);
	$items = $order->get_items();
	include_once(get_stylesheet_directory() . '/includes/neworder-csv.php');
}


add_action('woocommerce_order_status_changed', 'status_changed_processsing');
function status_changed_processsing($order_id, $checkout = null)
{
	$order = new WC_Order($order_id);
	$current_status = $order->status;
	$previous_status = get_post_meta($order_id, 'previous_status', true);
	if (empty($previous_status)) {
		update_post_meta($order_id, 'previous_status', $current_status);
	} else {
		if ($current_status == 'pending' || $current_status == 'inproduction' || $current_status == 'processing') {
			update_post_meta($order_id, 'previous_status', $current_status);
		}
	}
	update_post_meta($order_id, 'current_status', $current_status);

	if ($order->status == 'on-hold' && $order->fulfillment_status == 'fulfilled') {
		//assign statu to that order
		$billing_company = get_post_meta($order_id, '_billing_company', true);
		if ($billing_company == 'Lifetime Shutters' || get_current_user_id() == 1) {
			$order->status = 'wc-inproduction';
		} else {
			$order->status = 'wc-pending';
		}
		WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger($order_id);
	}

	if ($order->status == 'cancelled') {
		$single_email = 'marian93nes@gmail.com';
		$multiple_recipients = array(
			'caroline@anyhooshutter.com',
			'july@anyhooshutter.com',
			'tudor@lifetimeshutters.com',
		);
		$name = get_post_meta($order_id, 'cart_name', true);
		$subject = 'CANCELLED: Order LF0' . $order->get_order_number() . ' - ' . $name . '';
		$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

		$mess = '
        Hi July, Kevin <br />
        <br />
       Please CANCEL this order as requested by dealer.<br />
        <br />
        Kind regards. <br />
        <br />
        ';
		// wp_mail( $single_email, $subject2, $mess, $headers2, $attachments ); //temporary disabled attachments by Teo

		wp_mail($multiple_recipients, $subject, $mess, $headers);
	}
}


add_filter('woocommerce_payment_complete_order_status', 'rfvc_update_order_status', 10, 2);
function rfvc_update_order_status($order_status, $order_id)
{
	$order = new WC_Order($order_id);

	if ('processing' == $order_status && ('on-hold' == $order->status || 'pending' == $order->status || 'failed' == $order->status)) {
		WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger($order_id);
		$billing_company = get_post_meta($order_id, '_billing_company', true);
		if ($billing_company == 'Lifetime Shutters' || get_current_user_id() == 1) {
			return 'wc-inproduction';
		} else {
			return 'wc-pending';
		}
	}
	return $order_status;
}


add_filter('woocommerce_cheque_process_payment_order_status', 'matrix_change_order_to_agent_processing', 10, 1);
function matrix_change_order_to_agent_processing($status)
{
	if (
		get_current_user_id() == 18 || get_current_user_id() == 211 ||
		get_current_user_id() == 1 || get_current_user_id() == 192 ||
		get_current_user_id() == 183 || get_current_user_id() == 184
	) {
		return 'wc-inproduction';
	} else {
		return 'wc-pending';
	}
}


add_action('woocommerce_thankyou', 'autocomplete_all_orders');
function autocomplete_all_orders($order_id)
{
	$email = new WC_Email_Customer_Invoice();
	$email->trigger($order_id);

	$mailer = WC()->mailer();
	$mails = $mailer->get_emails();
	if (!empty($mails)) {
		foreach ($mails as $mail) {
			if ($mail->id == 'customer_invoice') {
				$mail->trigger($order_id);
			}
		}
	}

	if (!$order_id) {
		return;
	}

	$order = wc_get_order($order_id);
	$billing_company = get_post_meta($order_id, '_billing_company', true);
	if ($billing_company == 'Lifetime Shutters' || get_current_user_id() == 1) {
		$order->update_status('wc-inproduction');
	} else {
		$order->update_status('wc-pending');
	}

	WC()->mailer()->get_emails()['WC_Email_New_Order']->trigger($order_id);
}


function wc_cart_totals_order_total_html_custom($value)
{

	$first_element_cart = reset(WC()->cart->get_cart());
	$product_id = $first_element_cart['product_id'];
	$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
	$fob_components = false;
	if ($term_list[0]->slug == 'components-fob') {
		$fob_components = true;
		$value = '<strong>$' . WC()->cart->total . '</strong> ';
	} else {
		$value = '<strong>' . WC()->cart->total . '</strong> ';
	}

	return $value;
}


add_filter('woocommerce_cart_totals_order_total_html', 'wc_cart_totals_order_total_html_custom', 20, 1);

// Set shipping method for checkout/order
add_action('woocommerce_before_checkout_shipping_form', 'matrix_before_shipping');
function matrix_before_shipping($checkout)
{
	// check the user credentials here and if the condition is met set the shipping method
	WC()->session->set('chosen_shipping_methods', array('ship'));
}


// Include Custom Order Status in Woocommerce Orders sales reports
add_filter('woocommerce_reports_order_statuses', 'include_custom_order_status_to_reports', 20, 1);
function include_custom_order_status_to_reports($statuses)
{
	// Adding the custom order status to the 3 default woocommerce order statuses
	return array('inproduction', 'completed', 'pending', 'processing', 'paid', 'waiting', 'revised', 'inrevision', 'on-hold');
}


/***************************************************************/
// Function to eliminate shipping tax on new order
/***************************************************************/
add_action('woocommerce_checkout_order_processed', 'matrix_status_pending', 1, 1);
function matrix_status_pending($order_id)
{
	//$order_data = $order->get_data();
	$order = wc_get_order($order_id);
	$order_number = $order->get_order_number(); //teo for Order id below

	foreach ($order->get_items('tax') as $item_id => $item_tax) {
		echo $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
	}

	$order_shipping = get_post_meta($order_id, '_order_shipping', true);

	$order_shipping_correct = $order_shipping - $tax_shipping_total;

	update_post_meta($order_id, '_order_shipping', $order_shipping_correct);
}


add_action('woocommerce_checkout_order_processed', 'change_details_after_place_order', 1, 1);
function change_details_after_place_order($order_id)
{

	$order = wc_get_order($order_id);
	$items = $order->get_items();
	$user_id_customer = get_post_meta($order_id, '_customer_user', true);
	$new_total = 0;
	$order_train = 0;

	update_post_meta($order_id, 'previous_status', $order->get_status());
	$country_code = WC()->countries->countries[$order->get_shipping_country()];
	if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
		$tax_rate = 20;
	} else {
		$tax_rate = 0;
	}

	$pos = false;
	$components = false;
	$components_type = '';
	write_log('inside post_save update order 2');

	foreach ($items as $item_id => $item_data) {

		if (has_term(array('components', 'components-fob'), 'product_cat', $item_data['product_id'])) {
			$components = true;
			if (has_term('components-fob', 'product_cat', $item_data['product_id'])) {
				$components_type = 'FOB';
			} elseif (has_term('components', 'product_cat', $item_data['product_id'])) {
				$components_type = 'UK';
			}
		} elseif (has_term('awning', 'product_cat', $item_data['product_id'])) {
			return;
		} elseif (has_term(20, 'product_cat', $item_data['product_id']) || has_term(34, 'product_cat', $item_data['product_id']) || has_term(26, 'product_cat', $item_data['product_id'])) {
			$pos = true;
			// do something if product with ID = 971 has tag with ID = 5
		} else {

			$quantity = get_post_meta($item_data['product_id'], 'quantity', true);
			$price = get_post_meta($item_data['product_id'], '_price', true);
			$total = floatval($price) * $quantity;
			$sqm = get_post_meta($item_data['product_id'], 'property_total', true);
			if ($sqm > 0) {
				$train_price = get_user_meta($user_id_customer, 'train_price', true);
				// Check if $train_price is strictly null or an empty string
				if ($train_price === null || $train_price === '') {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				update_post_meta($item_data['product_id'], 'price_item_train', $train_price);
				$new_price = floatval($sqm * $quantity) * floatval($train_price);
				update_post_meta($item_data['product_id'], 'train_delivery', $new_price);
				$order_train = $order_train + $new_price;
			} else {
				$new_price = 0;
			}
			$total = $total + $new_price;
			$tax = ($tax_rate * $total) / 100;

			$line_tax_data = array(
				'total' => array(
					1 => $tax,
				),
				'subtotal' => array(
					1 => $tax,
				),
			);
			/*
			 * Doc to change order item meta: https://www.ibenic.com/manage-order-item-meta-woocommerce/
			 */
			if ($quantity == 0 || empty($quantity)) {
				$quantity = 1;
				$total = floatval($price) * $quantity;
				$tax = ($tax_rate * $total) / 100;
			}

			wc_update_order_item_meta($item_id, '_qty', $quantity, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_tax', $tax, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_subtotal_tax', $tax, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_subtotal', $total, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_total', $total, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data);

			## -- Make your checking and calculations -- ##
			$new_total = $new_total + $total + $tax; // <== Fake calculation
		}
	}

	if ($order_train > 0) {
		update_post_meta($order_id, 'order_train', $order_train);
	}

	if ($pos === false) {

		foreach ($order->get_items('tax') as $item_id => $item_tax) {
			// Tax shipping total
			$tax_shipping_total = $item_tax->get_shipping_tax_total();
		}
		//$total_price = $subtotal_price + $total_tax;
		$order_data = $order->get_data();
		$total_price = $new_total + $order_data['shipping_total'] + $tax_shipping_total;
		update_post_meta($order_id, '_order_total', $total_price);
	}

	if ($components === true) {
		update_post_meta($order_id, 'order_components_type', $components_type);
	} else {
		update_post_meta($order_id, 'order_components_type', 'no component find');
	}
}


// Change cart items price if they are components
add_action('woocommerce_before_calculate_totals', 'set_custom_cart_item_price_components', 99);

function set_custom_cart_item_price_components($cart_object)
{

	// First loop to check if product 11 is in cart
	foreach ($cart_object->cart_contents as $key => $cart_item) {
		// get term to find if is component
		$product_cat = '';
		$terms = get_the_terms($cart_item['product_id'], 'product_cat');
		if ($terms) {
			foreach ($terms as $term) {
				$product_cat = $term->slug;
			}
		}

		if ($product_cat == 'components') {
			// get discount user meta
			$discount_components = floatval(get_user_meta(get_current_user_id(), 'discount_components', true));
			// get regular price
			$OriginalPrice = floatval($cart_item['data']->get_price());

			// calculate new price with discount
			if (!empty($discount_components) || $discount_components > 0) {
				$discount_price = (float)(($discount_components * $OriginalPrice) / 100);

				$FinalPrice = $OriginalPrice - $discount_price;
			} else {
				$FinalPrice = (float)$cart_item['data']->get_price();
			}
			// set new price item
			$cart_item['data']->set_price($FinalPrice);
		}

		// Fix: changed || to && (logical error - condition was always true)
		if (empty($product_cat) && ($product_cat != 'components-fob' && $product_cat != 'pos')) {
			$sqm = get_post_meta($cart_item['product_id'], 'property_total', true);
			$quantity = get_post_meta($cart_item['product_id'], 'quantity', true);
			if ($sqm > 0) {
				$train_price = get_user_meta(get_current_user_id(), 'train_price', true);
				// Check if $train_price is strictly null or an empty string
				if ($train_price === null || $train_price === '') {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				update_post_meta($cart_item['product_id'], 'price_item_train', $train_price);
				$new_price = (float)($sqm) * (float)$train_price;
				update_post_meta($cart_item['product_id'], 'train_delivery', $new_price);
			}
			// Fix: Don't add train_delivery to item price - it's shown separately as "Sea freight"
			$price = get_post_meta($cart_item['product_id'], '_price', true);
			$cart_item['data']->set_price(floatval($price));
		}

		if ($product_cat == 'awning') {

			// Check if we have a final_price_uk
			if (isset($cart_item['final_price_uk']) && !empty($cart_item['final_price_uk'])) {
				$custom_price = floatval($cart_item['final_price_uk']);
				// Set the product's price
				$cart_item['data']->set_price($custom_price);
			}
			if (isset($cart_item['awning_sqm']) && !empty($cart_item['awning_sqm'])) {
				$custom_price = floatval($cart_item['final_price_uk']);
				$sqm = floatval($cart_item['awning_sqm']);

				// Inițializăm prețul suplimentar cu 0
				$sqm_price = 0;

				// Dacă e definită o suprafață > 0, calculăm prețul pe baza tren_price
				if ($sqm > 0) {
					$train_price = get_user_meta(get_current_user_id(), 'train_price', true);

					// Dacă train_price nu este setat la utilizator, îl preluăm din meta (ID = 1)
					if (empty($train_price)) {
						$train_price = (float)get_post_meta(1, 'train_price', true);
					}

					// Stocăm datele pentru prețul “pe tren” și totalul pe tren
					$cart_item['price_train'] = (float)$train_price;
					$sqm_price = $sqm * (float)$train_price;
					$cart_item['train_delivery'] = $sqm_price;
				}

				// Calculăm prețul final ca sumă a prețului de bază și a celui suplimentar
				$FinalPrice = $custom_price + $sqm_price;

				// Setăm noul preț în coș
				$cart_item['data']->set_price($FinalPrice);
			}
		}
	}
}


// change price html single template
add_filter('woocommerce_get_price_html', 'wpa83368_price_html', 100, 2);
function wpa83368_price_html($price, $product)
{
	// get term to find if is component
	$terms = get_the_terms($product->id, 'product_cat');
	foreach ($terms as $term) {
		$product_cat = $term->slug;
	}

	// return $product->price;
	if ($product_cat == 'components') {
		//        if ( in_array ( $product->product_type, $product_types ) && !(is_shop()) ) {
		//            return '';
		//        }
		return '£<span>' . $product->get_price() . ' <small>per BOX +VAT</small></span>';
	} elseif ($product_cat == 'components-fob') {
		//        if ( in_array ( $product->product_type, $product_types ) && !(is_shop()) ) {
		//            return '';
		//        }
		return '$<span>' . $product->get_price() . ' <small>per BOX</small></span>';
	} elseif ($product_cat == 'pos') {
		//        if ( in_array ( $product->product_type, $product_types ) && !(is_shop()) ) {
		//            return '';
		//        }
		return '$<span>' . $product->get_price() . ' <small>+VAT</small></span>';
	} else {
		return '£<span>' . $product->get_price() . '' . $product->get_price_suffix() . '</span>';
	}
}


// Add Total SQM to WooCommerce Reports page
add_action('admin_footer', 'matrix_add_sqm_to_wc_reports');
function matrix_add_sqm_to_wc_reports() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'woocommerce_page_wc-reports') {
        return;
    }

    global $wpdb;

    // Get date range from URL parameters
    $range = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : '7day';
    $start_date = '';
    $end_date = '';

    switch ($range) {
        case 'year':
            $start_date = date('Y-01-01');
            $end_date = date('Y-m-d');
            break;
        case 'last_month':
            $start_date = date('Y-m-01', strtotime('last month'));
            $end_date = date('Y-m-t', strtotime('last month'));
            break;
        case 'month':
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-d');
            break;
        case 'custom':
            $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-d', strtotime('-7 days'));
            $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : date('Y-m-d');
            break;
        default: // 7day
            $start_date = date('Y-m-d', strtotime('-7 days'));
            $end_date = date('Y-m-d');
    }

    // Query total SQM from wp_custom_orders
    $statuses = array('inproduction', 'completed', 'pending', 'processing', 'paid', 'waiting', 'revised', 'inrevision', 'on-hold');
    $status_placeholders = implode(',', array_fill(0, count($statuses), '%s'));

    $query = $wpdb->prepare(
        "SELECT COALESCE(SUM(sqm), 0) as total_sqm
         FROM {$wpdb->prefix}custom_orders
         WHERE DATE(createTime) BETWEEN %s AND %s
         AND status IN ($status_placeholders)",
        array_merge([$start_date, $end_date], $statuses)
    );

    $total_sqm = $wpdb->get_var($query);
    $total_sqm = number_format(floatval($total_sqm), 2);

    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var sqmHtml = '<li style="border-color: #3498db;">' +
            '<strong style="font-size: 24px;"><?php echo esc_js($total_sqm); ?> m²</strong>' +
            '<small>total SQM in this period</small>' +
            '</li>';

        // Insert after "items purchased" or at the end of the legend
        var $legend = $('.chart-legend');
        if ($legend.length) {
            $legend.append(sqmHtml);
        }
    });
    </script>
    <?php
}
