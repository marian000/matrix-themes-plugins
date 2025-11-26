<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

// Include funcția de logging
if (file_exists($path . 'wp-content/plugins/your-plugin/custom-logs.php')) {
	include_once($path . 'wp-content/plugins/your-plugin/custom-logs.php');
}

// Funcție simplă de logging dacă nu avem custom-logs.php
if (!function_exists('my_custom_log')) {
	function my_custom_log($title, $content) {
		error_log("[" . date('Y-m-d H:i:s') . "] $title: $content");
	}
}

my_custom_log("Script Start", "Scriptul de procesare comenzi a început");

$id_ord_original = $_POST['id_ord_original'];
my_custom_log("Input Data", "ID comandă primit: " . ($id_ord_original ? $id_ord_original : 'GOOL (procesare toate comenzile)'));

if (!empty($id_ord_original)) {
	my_custom_log("Single Order Processing", "Procesează o singură comandă: $id_ord_original");

	$order = wc_get_order($id_ord_original);

	if (!$order) {
		my_custom_log("Order Error", "Comanda cu ID $id_ord_original nu a fost găsită");
		exit("Order not found");
	}

	my_custom_log("Order Found", "Comanda găsită cu succes. Status: " . $order->get_status());

	$items = $order->get_items();
	$order_data = $order->get_data();
	$order_status = $order_data['status'];

	my_custom_log("Order Data", "Numărul de items: " . count($items) . ", Status: $order_status");

	$suma = $order->get_total();
	$order_data['date_created']->date('Y-m-d H:i:s');

	$text = $order_data['date_created']->date('Y-m-d H:i:s');
	$texty = $order_data['date_created']->date('Y-m-d H:i:s');
	preg_match('/-(.*?)-/', $text, $match);
	preg_match('/(.*?)-/', $text, $match_year);
	$month = $match[1];
	$year = $match_year[1];

	preg_match('/(.*?)-/', $texty, $match2);
	$pieces = explode("-", $match2[0]);
	$pieces[0]; // piece1
	$year = $pieces[0];

	$property_total_gbp = $order->get_total();
	$property_subtotal_gbp = $order->get_subtotal();
	$order_shipping_total = $order->shipping_total;

	my_custom_log("Order Totals", "Total GBP: $property_total_gbp, Subtotal: $property_subtotal_gbp, Shipping: $order_shipping_total");

	$total_dolar = 0;
	$sum_total_dolar = 0;
	$totaly_sqm = 0;

	my_custom_log("Items Processing", "Începe procesarea items-urilor");

	foreach ($items as $item_id => $item_data) {
		$prod_id = $item_id;
		$prod_qty = $item_data['quantity'];
		$product_id = $item_data['product_id'];

		my_custom_log("Item Processing", "Procesează item ID: $item_id, Product ID: $product_id, Qty: $prod_qty");

		$dolar_price = get_post_meta($product_id, 'dolar_price', true);
		if (empty($dolar_price)) {
			my_custom_log("Item Warning", "Preț USD lipsă pentru produsul $product_id");
			$dolar_price = 0;
		}

		$sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

		$_product = wc_get_product($product_id);
		if (!$_product) {
			my_custom_log("Product Error", "Produsul cu ID $product_id nu a fost găsit");
			continue;
		}

		$shipclass = $_product->get_shipping_class();

		$sqm = get_post_meta($product_id, 'property_total', true);
		if (empty($sqm)) {
			my_custom_log("Item Warning", "SQM lipsă pentru produsul $product_id");
			$sqm = 0;
		}

		$property_total_sqm = floatval($sqm) * floatval($prod_qty);
		$totaly_sqm = $totaly_sqm + $property_total_sqm;

		my_custom_log("Item Calculated", "Product $product_id: USD=$dolar_price, SQM=$sqm, Total SQM=$property_total_sqm");
	}

	my_custom_log("Items Complete", "Finalizat procesarea items. Total USD: $sum_total_dolar, Total SQM: $totaly_sqm");

	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';

	my_custom_log("Database", "Pregătește inserarea în tabela: $tablename");

	// Verifică dacă comanda există deja
	$existing_order = $wpdb->get_var($wpdb->prepare(
	  "SELECT COUNT(*) FROM $tablename WHERE idOrder = %s",
	  $order->get_id()
	));

	if ($existing_order > 0) {
		my_custom_log("Database Warning", "Comanda {$order->get_id()} există deja în tabela custom_orders");
	}

	$data = array(
	  'idOrder' => $order->get_id(),
	  'idUser' => get_post_meta($order->get_id(), '_customer_user', true),
	  'reference' => 'LF0' . get_post_meta($order->get_id(),'_order_number', true) . ' - ' . get_post_meta($order->get_id(), 'cart_name', true),
	  'usd_price' => $sum_total_dolar,
	  'gbp_price' => $property_total_gbp,
	  'subtotal_price' => $property_subtotal_gbp,
	  'sqm' => $totaly_sqm,
	  'shipping_cost' => number_format($order_shipping_total, 2),
	  'delivery' => $shipclass,
	  'status' => $order_status,
	  'createTime' => $order_data['date_created']->date('Y-m-d H:i:s'),
	);

	my_custom_log("Database Data", "Date pregătite pentru inserare: " . json_encode($data));

	$insert_result = $wpdb->insert($tablename, $data);

	if ($insert_result === false) {
		my_custom_log("Database Error", "Eroare la inserarea comenzii {$order->get_id()}: " . $wpdb->last_error);
		echo "Database insert failed: " . $wpdb->last_error;
	} else {
		my_custom_log("Database Success", "Comanda {$order->get_id()} inserată cu succes. Insert ID: " . $wpdb->insert_id);
		echo "Order processed successfully. Insert ID: " . $wpdb->insert_id;
	}

} else {
	my_custom_log("Bulk Processing", "Începe procesarea tuturor comenzilor");

	// Get all orders
	$orders = wc_get_orders(array(
	  'limit' => -1,
	  'orderby' => 'date',
	  'order' => 'ASC',
	));

	my_custom_log("Orders Found", "Găsite " . count($orders) . " comenzi pentru procesare");

	$processed_count = 0;
	$error_count = 0;

	foreach ($orders as $order) {
		$processed_count++;
		$order_id = $order->get_id();

		my_custom_log("Bulk Order", "Procesează comanda $processed_count din " . count($orders) . " - ID: $order_id");

		// EROARE CRITICĂ GĂSITĂ! Linia următoare suprascrie $order cu o valoare incorectă
		// $order = wc_get_order($id_ord_original); // ACEASTĂ LINIE CAUZEAZĂ PROBLEMA!

		// Corectare: eliminăm linia problematică - $order este deja correct din foreach

		if (!$order) {
			my_custom_log("Bulk Order Error", "Comanda cu ID $order_id nu a fost găsită");
			$error_count++;
			continue;
		}

		$items = $order->get_items();
		$order_data = $order->get_data();
		$order_status = $order_data['status'];

		$suma = $order->get_total();
		$order_data['date_created']->date('Y-m-d H:i:s');

		$text = $order_data['date_created']->date('Y-m-d H:i:s');
		$texty = $order_data['date_created']->date('Y-m-d H:i:s');
		preg_match('/-(.*?)-/', $text, $match);
		preg_match('/(.*?)-/', $text, $match_year);
		$month = $match[1];
		$year = $match_year[1];

		preg_match('/(.*?)-/', $texty, $match2);
		$pieces = explode("-", $match2[0]);
		$pieces[0]; // piece1
		$year = $pieces[0];

		$property_total_gbp = $order->get_total();
		$property_subtotal_gbp = $order->get_subtotal();
		$order_shipping_total = $order->shipping_total;

		$total_dolar = 0;
		$sum_total_dolar = 0;
		$totaly_sqm = 0;

		foreach ($items as $item_id => $item_data) {
			$prod_qty = $item_data['quantity'];
			$product_id = $item_data['product_id'];
			$dolar_price = get_post_meta($product_id, 'dolar_price', true);

			$sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

			$_product = wc_get_product($product_id);
			if (!$_product) {
				my_custom_log("Bulk Product Error", "Produsul cu ID $product_id nu a fost găsit în comanda $order_id");
				continue;
			}

			$shipclass = $_product->get_shipping_class();

			$sqm = get_post_meta($product_id, 'property_total', true);
			$property_total_sqm = floatval($sqm) * floatval($prod_qty);
			$totaly_sqm = $totaly_sqm + floatval($property_total_sqm); // Corectare: eliminat number_format
		}

		global $wpdb;
		$tablename = $wpdb->prefix . 'custom_orders';

		// Verifică dacă comanda există deja
		$existing_order = $wpdb->get_var($wpdb->prepare(
		  "SELECT COUNT(*) FROM $tablename WHERE idOrder = %s",
		  $order->get_id()
		));

		if ($existing_order > 0) {
			my_custom_log("Bulk Skip", "Comanda {$order->get_id()} există deja în tabela custom_orders - se sare");
			continue;
		}

		$data = array(
		  'idOrder' => $order->get_id(),
		  'idUser' => get_post_meta($order->get_id(), '_customer_user', true),
		  'reference' => 'LF0' . get_post_meta($order->get_id(),'_order_number', true) . ' - ' . get_post_meta($order->get_id(), 'cart_name', true),
		  'usd_price' => $sum_total_dolar,
		  'gbp_price' => $property_total_gbp,
		  'subtotal_price' => $property_subtotal_gbp,
		  'sqm' => $totaly_sqm,
		  'shipping_cost' => number_format($order_shipping_total, 2),
		  'delivery' => $shipclass,
		  'status' => $order_status,
		  'createTime' => $order_data['date_created']->date('Y-m-d H:i:s'),
		);

		$insert_result = $wpdb->insert($tablename, $data);

		if ($insert_result === false) {
			my_custom_log("Bulk Insert Error", "Eroare la inserarea comenzii {$order->get_id()}: " . $wpdb->last_error);
			$error_count++;
		} else {
			my_custom_log("Bulk Insert Success", "Comanda {$order->get_id()} inserată cu succes");
		}

		// Previne timeout-ul pentru procesarea în bulk
		if ($processed_count % 10 == 0) {
			my_custom_log("Bulk Progress", "Procesate $processed_count comenzi din " . count($orders));
		}
	}

	my_custom_log("Bulk Complete", "Procesarea completă. Total procesate: $processed_count, Erori: $error_count");
	echo "Bulk processing complete. Processed: $processed_count, Errors: $error_count";
}

my_custom_log("Script End", "Scriptul de procesare comenzi s-a terminat");
