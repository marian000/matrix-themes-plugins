<?php

/**
 * Disable required status on default billing fields in WooCommerce checkout.
 */
function my_remove_required_billing_fields($fields)
{
	// For example, remove required from first name, last name, address, etc.
	if (isset($fields['billing']['billing_first_name'])) {
		$fields['billing']['billing_first_name']['required'] = false;
	}
	if (isset($fields['billing']['billing_last_name'])) {
		$fields['billing']['billing_last_name']['required'] = false;
	}
	if (isset($fields['billing']['billing_country'])) {
		$fields['billing']['billing_country']['required'] = false;
	}
	if (isset($fields['billing']['billing_address_1'])) {
		$fields['billing']['billing_address_1']['required'] = false;
	}
	if (isset($fields['billing']['billing_city'])) {
		$fields['billing']['billing_city']['required'] = false;
	}
	if (isset($fields['billing']['billing_postcode'])) {
		$fields['billing']['billing_postcode']['required'] = false;
	}
	if (isset($fields['billing']['billing_phone'])) {
		$fields['billing']['billing_phone']['required'] = false;
	}
	if (isset($fields['billing']['billing_email'])) {
		$fields['billing']['billing_email']['required'] = false;
	}
	// For example, remove required from first name, last name, address, etc.
	if (isset($fields['shipping']['shipping_first_name'])) {
		$fields['shipping']['shipping_first_name']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_last_name'])) {
		$fields['shipping']['shipping_last_name']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_country'])) {
		$fields['shipping']['shipping_country']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_address_1'])) {
		$fields['shipping']['shipping_address_1']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_city'])) {
		$fields['shipping']['shipping_city']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_postcode'])) {
		$fields['shipping']['shipping_postcode']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_phone'])) {
		$fields['shipping']['shipping_phone']['required'] = false;
	}
	if (isset($fields['shipping']['shipping_email'])) {
		$fields['shipping']['shipping_email']['required'] = false;
	}

	// If you want to do the same for shipping fields, repeat for $fields['shipping'].

	return $fields;
}


add_filter('woocommerce_checkout_fields', 'my_remove_required_billing_fields');

/**
 * Auto-populate ALL billing & shipping fields from user meta at checkout.
 */
add_filter('woocommerce_checkout_get_value', 'my_populate_all_checkout_fields_from_user_meta', 10, 2);
function my_populate_all_checkout_fields_from_user_meta($value, $input_key)
{
	// Only run if user is logged in
	if (is_user_logged_in()) {
		$user_id = get_current_user_id();

		// Define all the billing & shipping fields you want to fill
		$fields_to_fill = array(
			// Billing
		  'billing_first_name',
		  'billing_last_name',
		  'billing_company',
		  'billing_address_1',
		  'billing_address_2',
		  'billing_city',
		  'billing_postcode',
		  'billing_country',
		  'billing_state',
		  'billing_phone',
		  'billing_email',

			// Shipping
		  'shipping_first_name',
		  'shipping_last_name',
		  'shipping_company',
		  'shipping_address_1',
		  'shipping_address_2',
		  'shipping_city',
		  'shipping_postcode',
		  'shipping_country',
		  'shipping_state',
		);

		// If this field is one of the billing/shipping fields above, try to get user meta
		if (in_array($input_key, $fields_to_fill, true)) {
			$user_meta_value = get_user_meta($user_id, $input_key, true);
			if (!empty($user_meta_value)) {
				return $user_meta_value;
			}
		}
	}

	// Otherwise, fall back to the original value
	return $value;
}


add_action('admin_menu', 'my_awning_add_submenu_page');
function my_awning_add_submenu_page()
{
	// Add a submenu under 'shuttermodule'
	add_submenu_page(
	  'shuttermodule',               // parent slug
	  'Awnings Settings',            // page title
	  'Awnings Settings',            // menu title
	  'manage_options',              // capability
	  'awnings-settings',            // menu slug
	  'my_awning_settings_page_html' // callback function to render the page
	);
}


add_action('admin_init', 'my_awning_register_settings');
function my_awning_register_settings()
{
	// Register a new setting for "my_awning_settings_option"
	register_setting('my_awning_settings_group', 'my_awning_settings_option', [
	  'sanitize_callback' => 'my_awning_settings_sanitize',
	]);

	// Add a section for the price matrix
	add_settings_section(
	  'my_awning_section_matrix',
	  'Awnings Price Matrix',
	  'my_awning_section_matrix_cb',
	  'awnings-settings-page'
	);

	// Add a field for the JSON text of the price matrix
	add_settings_field(
	  'my_awning_field_price_matrix',
	  'Price Matrix (JSON)',
	  'my_awning_field_price_matrix_cb',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);

	// Add a field for the special color extra cost
	add_settings_field(
	  'my_awning_field_special_extra',
	  'Special Color Extra Cost',
	  'my_awning_field_special_extra_cb',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);

	// Add a field for wind & rain sensor extra
	add_settings_field(
	  'my_awning_field_wind_rain_extra',
	  'Wind & Rain Sensor Extra Cost',
	  'my_awning_field_wind_rain_extra_cb',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);

	// Add a field for the factory materials JSON
	add_settings_field(
	  'my_awning_field_factory_materials',
	  'Factory Materials (JSON)',
	  'my_awning_field_factory_materials',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);

	// Add a field for the factory colors JSON
	add_settings_field(
	  'my_awning_field_factory_colors',
	  'Factory Colors (JSON)',
	  'my_awning_field_factory_colors_cb',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);

	// Add a field for the cassette covers JSON
	add_settings_field(
	  'my_awning_field_cassette_covers',
	  'Cassette Covers (JSON)',
	  'my_awning_field_cassette_covers_cb',
	  'awnings-settings-page',
	  'my_awning_section_matrix'
	);
}


/** Section callback */
function my_awning_section_matrix_cb()
{
	echo '<p>Configure your awning price matrix, extra costs, and select fields here.</p>';
}


/** Field callbacks */
function my_awning_field_price_matrix_cb()
{
	$option = get_option('my_awning_settings_option');
	$price_matrix_json = isset($option['price_matrix_json']) ? $option['price_matrix_json'] : '';
	echo "<textarea name='my_awning_settings_option[price_matrix_json]' rows='10' cols='70' style='width:100%;'>"
	  . esc_textarea($price_matrix_json) . "</textarea>";
	echo "<p class='description'>Enter valid JSON representing the price matrix. Example: {\"1.5\":{\"2.3\":652.4}, ...}</p>";
}


function my_awning_field_special_extra_cb()
{
	$option = get_option('my_awning_settings_option');
	// Prețurile implicite pot fi setate astfel: {"USD":50,"UK":45,"China":55}
	$special_extra_json = isset($option['special_color_extra_json']) ? $option['special_color_extra_json'] : '{"USD":50,"UK":45,"China":55}';
	echo "<textarea name='my_awning_settings_option[special_color_extra_json]' rows='3' cols='70' style='width:100%;'>"
	  . esc_textarea($special_extra_json) . "</textarea>";
	echo "<p class='description'>Introduceți JSON pentru costul suplimentar la culoarea Special. Ex: {\"USD\":50,\"UK\":45,\"China\":55}</p>";
}


function my_awning_field_wind_rain_extra_cb()
{
	$option = get_option('my_awning_settings_option');
	// Exemplu de valori implicite: {"USD":80,"UK":70,"China":90}
	$wind_rain_json = isset($option['wind_rain_extra_json']) ? $option['wind_rain_extra_json'] : '{"USD":80,"UK":70,"China":90}';
	echo "<textarea name='my_awning_settings_option[wind_rain_extra_json]' rows='3' cols='70' style='width:100%;'>"
	  . esc_textarea($wind_rain_json) . "</textarea>";
	echo "<p class='description'>Introduceți JSON pentru costul suplimentar pentru Wind & Rain Sensor. Ex: {\"USD\":80,\"UK\":70,\"China\":90}</p>";
}


function my_awning_field_factory_materials()
{
	$option = get_option('my_awning_settings_option');
	$colors_json = isset($option['factory_materials_json']) ? $option['factory_materials_json'] : '';
	echo "<textarea name='my_awning_settings_option[factory_materials_json]' rows='5' cols='70' style='width:100%;'>"
	  . esc_textarea($colors_json) . "</textarea>";
	echo "<p class='description'>JSON for factory materials. Example: {\"DeLuxeAwning\":\"De Luxe Awning\",...}</p>";
}


function my_awning_field_factory_colors_cb()
{
	$option = get_option('my_awning_settings_option');
	$colors_json = isset($option['factory_colors_json']) ? $option['factory_colors_json'] : '';
	echo "<textarea name='my_awning_settings_option[factory_colors_json]' rows='5' cols='70' style='width:100%;'>"
	  . esc_textarea($colors_json) . "</textarea>";
	echo "<p class='description'>JSON for factory colors. Example: {\"Special\":\"Special (+$$)\",\"0681\":\"0681\",...}</p>";
}


function my_awning_field_cassette_covers_cb()
{
	$option = get_option('my_awning_settings_option');
	$covers_json = isset($option['cassette_covers_json']) ? $option['cassette_covers_json'] : '';
	echo "<textarea name='my_awning_settings_option[cassette_covers_json]' rows='5' cols='70' style='width:100%;'>"
	  . esc_textarea($covers_json) . "</textarea>";
	echo "<p class='description'>JSON for cassette covers. Example: {\"001-Red\":\"001-Red\",\"002-Yellow\":\"002-Yellow\"}</p>";
}


/** Sanitize callback */
function my_awning_settings_sanitize($input)
{
	// Here you could validate that the JSON fields are valid JSON,
	// parse them, etc. For simplicity, we just store them as is.
	// If you want to ensure valid JSON, you can do a try/catch with json_decode.
	return $input;
}


function my_awning_settings_page_html()
{
	// Check user capability
	if (!current_user_can('manage_options')) {
		return;
	}

	// Output settings form
	?>
    <div class="wrap">
        <h1>Awnings Settings</h1>
        <form action="options.php" method="post">
			<?php
			// Output security fields for the registered setting
			settings_fields('my_awning_settings_group');
			// Output setting sections and their fields
			do_settings_sections('awnings-settings-page');
			// Submit button
			submit_button('Save Settings');
			?>
        </form>
    </div>
	<?php
}


add_action('wp_ajax_add_custom_awning', 'add_custom_awning_handler');

function add_custom_awning_handler()
{

	my_custom_log('awning POST: ', json_encode($_POST['formData']));

	// Preluăm și sterilizăm datele din obiectul formData
	$formData = isset($_POST['formData']) ? $_POST['formData'] : array();

	$name = isset($formData['name']) ? sanitize_text_field($formData['name']) : '';
	$material = isset($formData['material']) ? sanitize_text_field($formData['material']) : '';
	$width = isset($formData['width']) ? sanitize_text_field($formData['width']) : '';
	$drop = isset($formData['drop']) ? sanitize_text_field($formData['drop']) : '';
	$color = isset($formData['color']) ? sanitize_text_field($formData['color']) : '';
	$spec_c = isset($formData['special_color_name']) ? sanitize_text_field($formData['special_color_name']) : '';
	$cover = isset($formData['cassette_cover']) ? sanitize_text_field($formData['cassette_cover']) : '';
	$cassette_colour = isset($formData['cassette_colour']) ? sanitize_text_field($formData['cassette_colour']) : '';
	$motor = isset($formData['motor']) ? sanitize_text_field($formData['motor']) : '';
	$led = isset($formData['led']) ? sanitize_text_field($formData['led']) : '';
	$pos = isset($formData['position']) ? sanitize_text_field($formData['position']) : '';
	$sensor = isset($formData['sensor']) ? sanitize_text_field($formData['sensor']) : '';

	// Preluăm prețurile calculate (ex: final_price_uk, final_price_china)
	$special_colour = isset($formData['special_colour']) ? floatval($formData['special_colour']) : 0;
	$final_price_uk = isset($formData['final_price_uk']) ? floatval($formData['final_price_uk']) : 0;
	$final_price_china = isset($formData['final_price_china']) ? floatval($formData['final_price_china']) : 0;
	$awning_sqm = isset($formData['awning_sqm']) ? floatval($formData['awning_sqm']) : 0;

	// Pentru exemplu, presupunem că magazinul folosește moneda UK
	$price = $final_price_uk;

	// Produsul "dummy" din WooCommerce (înlocuiește cu ID-ul produsului real)
//	$product_id = 62205; // Exemplu
	$product_name = 'awning product'; // Înlocuiește cu numele dorit
	$product = get_page_by_title( $product_name, OBJECT, 'product' );

	if ( $product ) {
		$product_id = $product->ID;
	} else {
		$product_id = 0; // Nu a fost găsit produsul
	}
//	$product_id = 180471; // Exemplu

	// Datele de configurare pentru coș
	$cart_item_data = array(
	  'name' => $name,
	  'material' => $material,
	  'width' => $width,
	  'drop' => $drop,
	  'color' => $color,
	  'special_color_name' => $spec_c,
	  'cassette_cover' => $cover,
	  'cassette_colour' => $cassette_colour,
	  'motor' => $motor,
	  'led' => $led,
	  'position' => $pos,
	  'sensor' => $sensor,
	  'custom_price' => $price,
	  'special_colour' => $special_colour,
	  'final_price_uk' => $final_price_uk,
	  'final_price_china' => $final_price_china,
	  'awning_sqm' => $awning_sqm,
	);

// Adăugăm un filtru temporar pentru a seta prețul custom în coș
	add_filter('woocommerce_add_cart_item', 'awnings_set_custom_price', 10, 2);
	function awnings_set_custom_price($cart_item, $cart_item_key)
	{
		if (isset($cart_item['custom_price'])) {
			$cart_item['data']->set_price(floatval($cart_item['custom_price']));
		}
		return $cart_item;
	}


	// Adăugăm produsul în coș
	$added = WC()->cart->add_to_cart($product_id, 1, 0, array(), $cart_item_data);

	if ($added) {
		wp_send_json_success(['message' => 'Produsul a fost adăugat în coș.']);
	} else {
		wp_send_json_error(['message' => 'Eroare la adăugarea produsului în coș.']);
	}

	wp_die();
}


add_action('wp_ajax_update_awning_item', 'update_awning_item_handler');
add_action('wp_ajax_nopriv_update_awning_item', 'update_awning_item_handler');

function update_awning_item_handler()
{
	// Verificăm dacă formData a fost trimis
	if (!isset($_POST['formData'])) {
		wp_send_json_error(['message' => 'Missing cart item key.']);
		wp_die();
	}

	$formData = $_POST['formData'];

	// Sterilizăm cart_item_key
	$cart_item_key = sanitize_text_field($formData['cart_item_key']);

	// Listă de câmpuri așteptate
	$fields = [
	  'name',
	  'material',
	  'width',
	  'drop',
	  'color',
	  'special_color_name',
	  'cassette_cover',
	  'cassette_colour',
	  'motor',
	  'led',
	  'position',
	  'sensor',
	  'special_colour',
	  'final_price_china',
	  'final_price_uk',
	  'custom_price',
	  'awning_sqm',
	];

	// Iterăm și sterilizăm fiecare câmp din formData
	$updated_data = [];
	foreach ($fields as $field) {
		$updated_data[$field] = isset($formData[$field]) ? sanitize_text_field($formData[$field]) : '';
	}

	// Optionally, force the custom price to be the final_price_uk value.
	// This ensures the price is updated.
	$updated_data['custom_price'] = $updated_data['final_price_uk'];

	// Preluăm coșul curent
	$cart = WC()->cart->get_cart();
	if (!isset($cart[$cart_item_key])) {
		wp_send_json_error(['message' => 'Cart item not found.']);
		wp_die();
	}

	// Obținem informațiile din item-ul curent: produsul și cantitatea
	$product_id = $cart[$cart_item_key]['data']->get_id();
	$quantity = $cart[$cart_item_key]['quantity'];

	// Add a temporary filter to update the cart item price based on custom_price.
	add_filter('woocommerce_add_cart_item', 'awnings_set_custom_price', 10, 2);
	function awnings_set_custom_price($cart_item, $cart_item_key)
	{
		if (isset($cart_item['custom_price']) && $cart_item['custom_price'] !== '') {
			$cart_item['data']->set_price(floatval($cart_item['custom_price']));
		}
		return $cart_item;
	}


	// Opțional: înregistrează în log datele actualizate pentru debugging
	error_log('Updated data: ' . print_r($updated_data, true));

	// Eliminăm item-ul curent din coș
	WC()->cart->remove_cart_item($cart_item_key);

	// Re-adăugăm produsul cu noile date în coș
	$added = WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $updated_data);

	// Recalculăm totalurile coșului pentru a reflecta modificările
	WC()->cart->calculate_totals();

	if ($added) {
		wp_send_json_success(['message' => 'Item updated successfully.', 'formData' => $formData]);
	} else {
		wp_send_json_error(['message' => 'Failed to update item.']);
	}

	wp_die();
}


add_action('woocommerce_before_calculate_totals', 'use_final_price_uk_for_cart_items', 20);
function use_final_price_uk_for_cart_items($cart)
{
	// Avoid running in admin context except for AJAX
	if (is_admin() && !defined('DOING_AJAX')) {
		return;
	}

	// Loop through cart items
	foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
		// Check if we have a final_price_uk
		if (isset($cart_item['final_price_uk']) && !empty($cart_item['final_price_uk'])) {
			$custom_price = floatval($cart_item['final_price_uk']);
			// Set the product's price
			$cart_item['data']->set_price($custom_price);
		}
	}
}


add_action('woocommerce_checkout_create_order_line_item', 'add_custom_cart_item_meta_to_order_items', 10, 4);
function add_custom_cart_item_meta_to_order_items($item, $cart_item_key, $values, $order)
{
	// Lista de chei custom pe care dorim să le transferăm
	$custom_keys = array(
	  'name',
	  'material',
	  'width',
	  'drop',
	  'color',
	  'special_color_name',
	  'cassette_cover',
	  'cassette_colour',
	  'led',
	  'motor',
	  'position',
	  'sensor',
	  'special_colour',
	  'final_price_china',
	  'final_price_uk',
	  'awning_sqm',
	);

	// Pentru fiecare cheie, dacă există valoare în $values (cart item data), adaugă-o ca meta în order line item
	foreach ($custom_keys as $key) {
		if (isset($values[$key]) && !empty($values[$key])) {
			$item->add_meta_data($key, $values[$key]);
		}
	}
}


// creat shortcode
function awning_shortcode($atts)
{

// Presupunem că suntem într-un context în care WooCommerce și coșul sunt inițializate
	$items = WC()->cart->get_cart();
	$option = get_option('my_awning_settings_option');

	if (!empty($items)): ?>
        <div class="container-fluid">
            <h2>Awning Items in Cart</h2>
            <table class="table table-bordered table-striped table-awning">
                <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
				<?php
				$i = 1;
				foreach ($items as $cart_item_key => $cart_item):
//					echo '<pre>';
//					print_r($cart_item);
//					echo '</pre>';

					$salt = 'matrixAwningSecret'; // definește o valoare secretă, ideal în wp-config.php sau într-un plugin securizat
					// Combină salt-ul cu cheia și apoi encodează cu base64
					$obfuscated_id = base64_encode($salt . $cart_item_key);
					$nonce = wp_create_nonce('edit_cart_item_' . $cart_item_key);
					$edit_url = add_query_arg([
					  'item' => $obfuscated_id,
					  'nonce' => $nonce,
					], site_url('/prod-awning/'));

					// Obținem informațiile produsului
					$product = $cart_item['data'];
					$product_name = $product->get_name();
					$product_price = $product->get_price();
					$product_qty = $cart_item['quantity'];
					$line_total = $cart_item['line_total'];

					// Extragem datele custom din $cart_item_data
					$item_data = isset($cart_item['custom_price']) ? $cart_item['custom_price'] : $product_price;
					$name = isset($cart_item['name']) ? $cart_item['name'] : '';
					$material = isset($cart_item['material']) ? $cart_item['material'] : '';
					$width = isset($cart_item['width']) ? $cart_item['width'] : '';
					$drop = isset($cart_item['drop']) ? $cart_item['drop'] : '';
					$color = isset($cart_item['color']) ? $cart_item['color'] : '';
					$spec_col = isset($cart_item['special_color_name']) ? $cart_item['special_color_name'] : '';
					$cover = isset($cart_item['cassette_cover']) ? $cart_item['cassette_cover'] : '';
					$cassette_colour = isset($cart_item['cassette_colour']) ? $cart_item['cassette_colour'] : '';
					$motor = isset($cart_item['motor']) ? $cart_item['motor'] : '';
					$led = isset($cart_item['led']) ? $cart_item['led'] : '';
					$position = isset($cart_item['position']) ? $cart_item['position'] : '';
					$sensor = isset($cart_item['sensor']) ? $cart_item['sensor'] : '';
					$price_uk = isset($cart_item['final_price_uk']) ? $cart_item['final_price_uk'] : '';
					$special_colour = isset($cart_item['special_colour']) ? $cart_item['special_colour'] : '';
					$awning_sqm = isset($cart_item['awning_sqm']) ? $cart_item['awning_sqm'] : '';

					// Puteți format prețurile cum doriți (ex. wc_price($product_price))
					?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                            <strong><?php echo esc_html($product_name); ?></strong><br>
                            <!-- Afișăm detaliile custom: -->
							<?php if ($name): ?>
                                <div>Name: <?php echo esc_html($name); ?></div>
							<?php endif; ?>
							<?php if ($material): ?>
                                <div>Material: <?php echo esc_html($material); ?></div>
							<?php endif; ?>
							<?php if ($width): ?>
                                <div>Width (mm): <?php echo esc_html($width); ?></div>
							<?php endif; ?>
							<?php if ($drop): ?>
                                <div>Drop (mm): <?php echo esc_html($drop); ?></div>
							<?php endif; ?>
							<?php if ($color) :
								$factory_colors = json_decode($option['factory_colors_json'], true);
								?>
                                <div>Fabric Colour: <?php echo esc_html($factory_colors[$color]); ?></div>
							<?php endif; ?>
							<?php if ($spec_col): ?>
                                <div>Other Fabric Name: <?php echo esc_html($spec_col); ?></div>
							<?php endif; ?>
							<?php if ($cassette_colour): ?>
                                <div>Cassette Colour: <?php echo esc_html($cassette_colour); ?></div>
							<?php endif; ?>
							<?php if ($cover): ?>
                                <div>Cassette Cover: <?php echo esc_html($cover); ?></div>
							<?php endif; ?>
							<?php if ($motor): ?>
                                <div>Motor: <?php echo esc_html($motor); ?></div>
							<?php endif; ?>
							<?php if ($position): ?>
                                <div>Position: <?php echo esc_html($position); ?></div>
							<?php endif; ?>
							<?php if ($led): ?>
                                <div>LED Arm Lights: <?php echo esc_html($led); ?></div>
							<?php endif; ?>
							<?php if ($sensor): ?>
                                <div>Wind & Rain Sensor: <?php echo esc_html($sensor); ?></div>
							<?php endif; ?>
                        </td>
                        <td><?php echo wc_price($price_uk); ?></td>
                        <td><?php echo esc_html($product_qty); ?></td>
                        <td><?php echo wc_price($price_uk); ?></td>
                        <td>
                            <!-- Exemplu de butoane tipice: Edit, Clone, Delete -->
                            <a href="<?php echo $edit_url; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <!--                            <a href="#" class="btn btn-sm btn-primary">Clone</a>-->
                            <a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" class="btn btn-sm btn-danger">Delete</a>
                        </td>
                    </tr>
					<?php $i++; endforeach; ?>
                </tbody>
            </table>
        </div>
        <style>
            .table-awning {
                width: 100%;
            }
        </style>
	<?php else: ?>
        <p>No items in cart.</p>
	<?php endif;
}


add_shortcode('awning_table_items', 'awning_shortcode');

// Shortcode for displaying order items on the Thank You page using an order_id attribute only
function awning_order_table_shortcode($atts)
{
	// Set default attributes; order_id must be provided in the shortcode
	$atts = shortcode_atts(array(
	  'order_id' => '',
	), $atts, 'awning_order_items');

	// Use the order_id passed as an attribute only
	$order_id = intval($atts['order_id']);
	if (!$order_id) {
		return '<p>Order ID is required in the shortcode attribute.</p>';
	}

	// Get the order object using the provided order_id
	$order = wc_get_order($order_id);
	if (!$order) {
		return '<p>Order not found.</p>';
	}

	$option = get_option('my_awning_settings_option');

	// Start output buffering to capture the HTML
	ob_start();
	?>
    <div class="container-fluid">
        <table class="table table-bordered table-striped table-awning" style="width: 100%;">
            <thead>
            <tr>
                <th>Nr.</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$i = 1;
			// Verificăm dacă comanda are item-uri
			$items = $order->get_items();
			if (empty($items)) {
				echo '<p>No items in order.</p>';
				return;
			}
			foreach ($items as $item_id => $item) {
				// Obținem produsul din item, dacă există
				$product = $item->get_product();
				$product_name = $product ? $product->get_name() : $item->get_name();

				// Calculăm prețul unitar și totalul pe linie
				$product_qty = $item->get_quantity();
				$line_total = $item->get_total();
				$unit_price = $product_qty > 0 ? $line_total / $product_qty : 0;

				// Dacă ai meta-uri custom stocate în comandă, le poți prelua astfel:
				$name = $item->get_meta('name');
				$material = $item->get_meta('material');
				$width = $item->get_meta('width');
				$drop = $item->get_meta('drop');
				$color = $item->get_meta('color');
				$spec_col = $item->get_meta('special_color_name');
				$cover = $item->get_meta('cassette_cover');
				$cassette_colour = $item->get_meta('cassette_colour');
				$motor = $item->get_meta('motor');
				$led = $item->get_meta('led');
				$position = $item->get_meta('position');
				$sensor = $item->get_meta('sensor');
				$price_uk = $item->get_meta('final_price_uk');
				$special_colour = $item->get_meta('special_colour');
				$awning_sqm = $item->get_meta('awning_sqm');
				?>
                <tr item_id="<?php echo $item_id; ?>">
                    <td><?php echo esc_html($i); ?></td>
                    <td>
                        <strong><?php echo esc_html($product_name); ?></strong><br>
						<?php if ($name) : ?>
                            <div>Name: <?php echo esc_html($name); ?></div>
						<?php endif; ?>
						<?php if ($material) : ?>
                            <div>Material: <?php echo esc_html($material); ?></div>
						<?php endif; ?>
						<?php if ($width) : ?>
                            <div>Width (mm): <?php echo esc_html($width); ?></div>
						<?php endif; ?>
						<?php if ($drop) : ?>
                            <div>Drop (mm): <?php echo esc_html($drop); ?></div>
						<?php endif; ?>
						<?php if ($color) :
							$factory_colors = json_decode($option['factory_colors_json'], true);
							?>
                            <div>Fabric Colour: <?php echo esc_html($factory_colors[$color]); ?></div>
						<?php endif; ?>
						<?php if ($spec_col) : ?>
                            <div>Other Fabric Name: <?php echo esc_html($spec_col); ?></div>
						<?php endif; ?>
						<?php if ($cassette_colour) : ?>
                            <div>Cassette Colour: <?php echo esc_html($cassette_colour); ?></div>
						<?php endif; ?>
						<?php if ($cover) : ?>
                            <div>Cassette Cover: <?php echo esc_html($cover); ?></div>
						<?php endif; ?>
						<?php if ($motor) : ?>
                            <div>Motor: <?php echo esc_html($motor); ?></div>
						<?php endif; ?>
						<?php if ($position) : ?>
                            <div>Position: <?php echo esc_html($position); ?></div>
						<?php endif; ?>
						<?php if ($led) : ?>
                            <div>LED Arm Lights: <?php echo esc_html($led); ?></div>
						<?php endif; ?>
						<?php if ($sensor) : ?>
                            <div>Wind &amp; Rain Sensor: <?php echo esc_html($sensor); ?></div>
						<?php endif; ?>
                    </td>
                    <td><?php echo wc_price($price_uk ? $price_uk : $unit_price); ?></td>
                    <td><?php echo esc_html($product_qty); ?></td>
                    <td><?php echo wc_price($price_uk); ?></td>
                </tr>
				<?php
				$i++;
			}
			$order_data = $order->get_data(); // The Order data
			?>
            <tfooter>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right">Products Total (excl. VAT):
                    </td>
                    <td id="total_box" class="amount">
						<?php
						echo '£' . number_format((double)$order->get_subtotal(), 2);
						?>
                    </td>
                </tr>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right">Local delivery :</td>
                    <!--                <td class="amount">$-->
                    <!--</td>-->
                    <td class="amount">£<?php echo number_format($order_data['shipping_total'], 2) ?></td>
                </tr>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right">VAT:</td>
                    <td class="amount">£<?php echo number_format($order_data['total_tax'], 2); ?></td>
                </tr>
                <tr class="table-totals">
                    <td colspan="4" style="text-align:right"><strong>Gross Total:</strong></td>
                    <td class="amount">
                        <strong>£<?php echo number_format((double)$order->get_total(), 2); ?></strong>
                    </td>
                </tr>
            </tfooter>
            </tbody>
        </table>
        <style>
            .table-awning {
                width: 100%;
            }

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
    </div>
	<?php
	return ob_get_clean();
}


add_shortcode('awning_order_items', 'awning_order_table_shortcode');

/*
* Make shortcode for template item configuration ex: form-checkout.php, thankyou, vieworder
*/
function table_csv_awning($atts)
{
	$attributes = shortcode_atts(array(
	  'table_class' => 'table table-striped',
	  'table_id' => 'example',
	  'order_id' => '',
	  'editable' => false,
	  'admin' => false,
	), $atts);
	include_once(get_stylesheet_directory() . '/views/table/csv_awning.php');
}


add_shortcode('table_csv_awning', 'table_csv_awning');