<?php


//======================================================================
// 1. HOOKS PRINCIPALE (Înlocuiesc add_action('save_post', ...))
//======================================================================

/**
 * Funcție declanșată la salvarea unui post de tip 'shop_order'.
 * Gestionează actualizarea meta datelor specifice comenzii din $_POST.
 *
 * @param int $post_id ID-ul comenzii salvate.
 */
function matrix_on_save_shop_order($post_id) {
	// Apelăm funcția principală de salvare, specificând tipul postului
	matrix_handle_post_save($post_id, 'shop_order');
}
add_action('save_post_shop_order', 'matrix_on_save_shop_order');

/**
 * Funcție declanșată la salvarea unui post de tip 'container'.
 * Gestionează actualizarea meta datelor specifice containerului din $_POST.
 *
 * @param int $post_id ID-ul containerului salvat.
 */
function matrix_on_save_container($post_id) {
	// Apelăm funcția principală de salvare, specificând tipul postului
	matrix_handle_post_save($post_id, 'container');
}
add_action('save_post_container', 'matrix_on_save_container');


//======================================================================
// 2. FUNCȚIA CENTRALĂ DE GESTIONARE SAVE_POST
//======================================================================

/**
 * Gestionează logica de salvare a meta datelor pentru diferite tipuri de post.
 * Include verificări de securitate și deleagă sarcinile către funcții specializate.
 *
 * @param int    $post_id   ID-ul postului salvat.
 * @param string $post_type Tipul postului ('shop_order', 'container', etc.).
 */
function matrix_handle_post_save($post_id, $post_type) {
	/*
	 * Verificări de securitate și validare inițială.
	 */

	// Verificare Nonce (TREBUIE să adaugi un câmp nonce în formularul tău!)
	// Înlocuiește 'matrix_meta_box_nonce_action' și 'matrix_meta_box_nonce_field' cu valorile tale reale.
	/*
	if (!isset($_POST['matrix_meta_box_nonce_field']) || !wp_verify_nonce($_POST['matrix_meta_box_nonce_field'], 'matrix_meta_box_nonce_action')) {
		error_log("Nonce verification failed for post ID: {$post_id}");
		return;
	}
	*/
	// @TODO: Decomentează și implementează verificarea nonce de mai sus!

	// Ignoră salvările automate (autosave)
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Ignoră reviziile
	if (wp_is_post_revision($post_id)) {
		return;
	}

	// Verifică permisiunile utilizatorului (exemplu generic, ajustează dacă e necesar)
	if (!current_user_can('edit_post', $post_id)) {
		error_log("User does not have permission to edit post ID: {$post_id}");
		return;
	}

	// Verifică dacă $_POST există (ar trebui să existe la o salvare din admin)
	if (empty($_POST)) {
		// Poate fi o salvare programatică, nu din formular. Poate vrei să ieși sau să gestionezi diferit.
		error_log("Skipping matrix_handle_post_save for post ID {$post_id} as \$_POST is empty.");
		return;
	}

	error_log("matrix_handle_post_save running for post ID: {$post_id}, type: {$post_type}");


	/*
	 * Delegarea sarcinilor către funcții specializate bazate pe datele primite.
	 */

	// --- Actualizare Referință Comandă (Cart Name) ---
	if (isset($_POST['order_ref']) && $post_type === 'shop_order') {
		matrix_handle_order_ref_update($post_id, sanitize_text_field($_POST['order_ref']));
	}

	// --- Actualizare Data Livrări (Deliveries Start) ---
	// Se aplică la 'container' (afectând comenzile din el) și direct la postul salvat (poate fi și comandă)
	if (isset($_POST['deliveries'])) {
		matrix_handle_deliveries_update($post_id, sanitize_text_field($_POST['deliveries']), $post_type);
	}
	// Gestionează și câmpul specific 'deliveries-repair' (probabil pentru comenzi?)
	if (isset($_POST['deliveries-repair']) && $post_type === 'shop_order') {
		// Presupunem că 'deliveries-repair' actualizează același meta 'delivereis_start' ca și 'deliveries'
		update_post_meta($post_id, 'delivereis_start', sanitize_text_field($_POST['deliveries-repair']));
		error_log("Updated 'delivereis_start' from 'deliveries-repair' for order ID: {$post_id}");
	}


	// --- Gestionare Upload Fișiere CSV ---
	if (isset($_FILES['CSVUpload']) && !empty($_FILES['CSVUpload']['tmp_name'])) {
		matrix_handle_csv_upload($post_id, $_FILES['CSVUpload'], 'csv_upload_path');
	}
	if (isset($_FILES['CSVUploadInvoice']) && !empty($_FILES['CSVUploadInvoice']['tmp_name'])) {
		matrix_handle_csv_upload($post_id, $_FILES['CSVUploadInvoice'], 'csv_upload_path_invoice');
	}


	// --- Actualizare Prețuri/Cantități Item Comandă ---
	// Rulează doar pentru 'shop_order' și dacă datele necesare sunt prezente
	if (isset($_POST['item_price']) && is_array($_POST['item_price']) && $post_type === 'shop_order') {
		// Pasează întregul $_POST pentru acces facil la item_vat, item_qty etc.
		matrix_handle_item_price_update($post_id, $_POST);
	}


	// --- Asignare Container (doar pentru comenzi) ---
	if ($post_type === 'shop_order') {
		if (isset($_POST['container'])) {
			// Asigură-te că ID-ul containerului este un număr întreg pozitiv
			$new_container_id = absint($_POST['container']);
			if ($new_container_id > 0) {
				matrix_handle_container_assignment($post_id, $new_container_id);
			}
		} elseif (isset($_POST['container-repair'])) {
			// Logica duplicată pentru 'container-repair', folosim aceeași funcție helper
			$new_container_id = absint($_POST['container-repair']);
			if ($new_container_id > 0) {
				matrix_handle_container_assignment($post_id, $new_container_id);
			}
		}
	}


	// --- Actualizare Status Comandă (probabil custom, nu statusul WC) ---
	if (isset($_POST['order_status'])) {
		// Presupunem că este relevant pentru orice post type unde apare câmpul
		update_post_meta($post_id, 'order_status', sanitize_text_field($_POST['order_status']));
		error_log("Updated 'order_status' meta for post ID: {$post_id}");
	}


	// --- Actualizare Cost Reparație (și trimitere email) ---
	// Pare specific pentru un CPT de reparații, dar verificarea lipsește. Să presupunem că e relevant unde apare câmpul.
	// @TODO: Adaugă o verificare get_post_type() dacă e specific unui CPT.
	if (isset($_POST['cost_repair']) && isset($_POST['type_cost'])) {
		matrix_handle_cost_repair_update($post_id, $_POST); // Pasează tot $_POST
	}


	// --- Recalculare Totaluri Comandă și Actualizare Tabelă Custom ---
	// Rulează necondiționat la fiecare salvare a unei comenzi din admin
	if ($post_type === 'shop_order') {
		error_log("Triggering order recalculation for order ID: {$post_id}");
		matrix_handle_order_recalculation($post_id);
	}

}


//======================================================================
// 3. FUNCȚII SPECIALIZATE (Handlers)
//======================================================================

/**
 * Actualizează meta câmpul 'cart_name' pentru o comandă.
 *
 * @param int    $order_id    ID-ul comenzii.
 * @param string $cart_name   Noul nume al coșului (referință).
 */
function matrix_handle_order_ref_update($order_id, $cart_name) {
	update_post_meta($order_id, 'cart_name', $cart_name);
	error_log("Updated 'cart_name' for order ID: {$order_id}");
}

//----------------------------------------------------------------------

/**
 * Actualizează data de start a livrărilor ('delivereis_start').
 * Dacă postul este un 'container', actualizează și toate comenzile asociate.
 *
 * @param int    $post_id      ID-ul postului salvat (container sau comandă).
 * @param string $delivery_date Data livrării.
 * @param string $post_type    Tipul postului.
 */
function matrix_handle_deliveries_update($post_id, $delivery_date, $post_type) {
	error_log("Handling deliveries update for post ID: {$post_id}, type: {$post_type}");

	// Dacă este un container, actualizează toate comenzile conținute
	if ($post_type === 'container') {
		$container_orders = get_post_meta($post_id, 'container_orders', true);
		if (is_array($container_orders) && !empty($container_orders)) {
			error_log("Updating deliveries for orders in container {$post_id}: " . implode(', ', $container_orders));
			foreach ($container_orders as $order_id) {
				if (get_post_type($order_id) === 'shop_order') { // Verificare suplimentară
					update_post_meta($order_id, 'delivereis_start', $delivery_date);
				}
			}
		} else {
			error_log("No orders found in container {$post_id} to update deliveries.");
		}
	}

	// Actualizează meta și pentru postul curent (poate fi containerul însuși sau o comandă direct)
	update_post_meta($post_id, 'delivereis_start', $delivery_date);
	error_log("Updated 'delivereis_start' directly for post ID: {$post_id}");
}

//----------------------------------------------------------------------

/**
 * Gestionează upload-ul unui fișier și salvează calea în post meta.
 * Funcție reutilizabilă pentru diferite upload-uri CSV.
 *
 * @param int    $post_id    ID-ul postului.
 * @param array  $file_data  Datele din $_FILES pentru fișierul respectiv.
 * @param string $meta_key   Cheia meta unde se salvează calea fișierului.
 * @return bool True la succes, False la eroare.
 */
function matrix_handle_csv_upload($post_id, $file_data, $meta_key) {
	if (!function_exists('wp_handle_upload')) {
		require_once(ABSPATH . 'wp-admin/includes/file.php');
	}

	// Verifică dacă există erori de upload inițiale
	if ($file_data['error'] !== UPLOAD_ERR_OK) {
		error_log("File upload error for post ID {$post_id}, meta key {$meta_key}. Error code: " . $file_data['error']);
		return false;
	}


	$upload_overrides = ['test_form' => false];
	// Poți adăuga restricții de tip MIME aici:
	// $upload_overrides['mimes'] = ['csv' => 'text/csv'];

	$movefile = wp_handle_upload($file_data, $upload_overrides);

	if ($movefile && empty($movefile['error'])) {
		$file_path = $movefile['file']; // Calea absolută pe server
		$file_url = $movefile['url'];   // URL-ul fișierului (poate fi util)

		update_post_meta($post_id, $meta_key, $file_path); // Sau $file_url, depinde ce ai nevoie
		error_log("File uploaded successfully for post ID {$post_id}. Meta key: {$meta_key}. Path: {$file_path}");
		return true;
	} else {
		$error_message = isset($movefile['error']) ? $movefile['error'] : 'Unknown upload error.';
		error_log("File upload failed for post ID {$post_id}, meta key {$meta_key}. Error: " . $error_message);
		// Poți adăuga un admin notice aici dacă vrei să informezi userul
		// add_action('admin_notices', function() use ($error_message) { ... });
		return false;
	}
}

//----------------------------------------------------------------------

/**
 * Actualizează prețurile, cantitățile și taxele pentru itemii unei comenzi
 * pe baza datelor din $_POST. Recalculează totalurile comenzii.
 *
 * @param int   $order_id ID-ul comenzii.
 * @param array $post_data Array-ul $_POST care conține 'item_price', 'item_vat', 'item_qty'.
 */
function matrix_handle_item_price_update($order_id, $post_data) {
	error_log("Handling item price update for order ID: {$order_id}");
	$order = wc_get_order($order_id);

	if (!$order) {
		error_log("Could not get order object for ID: {$order_id} in item price update.");
		return;
	}

	$items = $order->get_items(); // Obține itemii de tip 'line_item'
	$total_tax_calculated = 0;
	$subtotal_price_calculated = 0;
	$items_updated = false;

	foreach ($items as $item_id => $item_data) {
		// Verifică dacă există date pentru acest item_id în POST
		if (isset($post_data['item_price'][$item_id]) && isset($post_data['item_vat'][$item_id]) && isset($post_data['item_qty'][$item_id])) {

			$item_price = wc_format_decimal($post_data['item_price'][$item_id]); // Prețul per item (subtotal linie / qty)
			$item_vat   = wc_format_decimal($post_data['item_vat'][$item_id]);   // Taxa per item (taxa linie / qty)
			$item_qty   = wc_stock_amount($post_data['item_qty'][$item_id]);     // Cantitatea

			if ($item_qty <= 0) {
				error_log("Skipping item ID {$item_id} for order {$order_id} due to zero or negative quantity.");
				continue; // Sau poate ar trebui ștearsă linia? Deocamdată o sărim.
			}

			$line_subtotal = $item_price * $item_qty;
			$line_tax      = $item_vat * $item_qty;

			// Actualizează meta datele itemului
			// Nota: WC calculează _line_total din _line_subtotal + _line_tax.
			// Actualizarea directă a _line_total poate fi suprascrisă dacă nu setezi și subtotalul/taxa corect.
			// Este mai sigur să actualizezi cantitatea, subtotalul și taxa.
			wc_update_order_item_meta($item_id, '_qty', $item_qty);
			wc_update_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($line_subtotal));
			wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($line_tax));
			// Actualizăm și _line_total pentru consistență cu logica veche, deși nu e strict necesar dacă recalculăm totalul comenzii
			wc_update_order_item_meta($item_id, '_line_total', wc_format_decimal($line_subtotal));
			// Meta _line_subtotal_tax este de obicei același cu _line_tax
			wc_update_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($line_tax));


			$subtotal_price_calculated += $line_subtotal;
			$total_tax_calculated += $line_tax;
			$items_updated = true;
			error_log("Updated item {$item_id} in order {$order_id}: Qty={$item_qty}, Subtotal={$line_subtotal}, Tax={$line_tax}");

		}
	}

	// Dacă s-au actualizat itemi, recalculăm și actualizăm totalurile comenzii
	if ($items_updated) {
		try {
			// Obținem taxa de transport (dacă există)
			$shipping_tax_total = 0;
			foreach ($order->get_taxes() as $tax_id => $tax_item) {
				$shipping_tax_total += $tax_item->get_shipping_tax_total();
			}

			// Calculăm noul total general
			$order_shipping_total = $order->get_shipping_total();
			$new_order_total = $subtotal_price_calculated + $total_tax_calculated + $order_shipping_total + $shipping_tax_total;

			// Actualizăm meta-urile principale ale comenzii
			// Folosim $order->set_props() și $order->save() pentru o abordare mai orientată obiect
			$order->set_props([
			  'total_tax' => wc_format_decimal($total_tax_calculated),
			  'total'     => wc_format_decimal($new_order_total),
				// Subtotalul este suma subtotalurilor liniilor *înainte* de discounturi. Poate necesita ajustare.
				// Pentru simplitate, folosim suma calculată, dar WC poate calcula diferit.
			  'cart_tax' => wc_format_decimal($total_tax_calculated), // cart_tax este de obicei suma taxelor pe itemi
			]);

			// Actualizare specifică a liniei de taxă (dacă este necesar și știm ID-ul)
			// Logica originală actualiza o linie de taxă specifică. Replicăm cu precauție.
			$tax_lines = $order->get_items('tax');
			if (!empty($tax_lines)) {
				$tax_item_meta_id = key($tax_lines); // Ia ID-ul primei linii de taxă
				if ($tax_item_meta_id) {
					wc_update_order_item_meta($tax_item_meta_id, 'tax_amount', wc_format_decimal($total_tax_calculated));
					error_log("Updated tax line item {$tax_item_meta_id} tax_amount for order {$order_id}.");
				}
			}

			$order->save(); // Salvează modificările comenzii (totaluri, etc.)
			error_log("Order totals updated for order ID: {$order_id}. New Total: {$new_order_total}, New Tax: {$total_tax_calculated}");

		} catch (Exception $e) {
			error_log("Error saving order totals for order ID {$order_id}: " . $e->getMessage());
		}

	} else {
		error_log("No items updated based on POST data for order ID: {$order_id}");
	}
}


//----------------------------------------------------------------------

/**
 * Asignează o comandă unui container specific.
 * Îndepărtează comanda din containerul vechi (dacă există) și o adaugă în cel nou.
 *
 * @param int $order_id       ID-ul comenzii.
 * @param int $new_container_id ID-ul noului container.
 */
function matrix_handle_container_assignment($order_id, $new_container_id) {
	error_log("Assigning order ID: {$order_id} to container ID: {$new_container_id}");

	// 1. Îndepărtează comanda din containerul vechi
	$old_container_id = get_post_meta($order_id, 'container_id', true);
	if ($old_container_id && $old_container_id != $new_container_id) {
		$old_container_id = absint($old_container_id); // Asigură ID valid
		$old_container_orders = get_post_meta($old_container_id, 'container_orders', true);

		if (is_array($old_container_orders)) {
			$key = array_search($order_id, $old_container_orders);
			if (false !== $key) {
				unset($old_container_orders[$key]);
				// Reindexează array-ul dacă este necesar (opțional)
				// $old_container_orders = array_values($old_container_orders);
				update_post_meta($old_container_id, 'container_orders', $old_container_orders);
				error_log("Removed order ID {$order_id} from old container ID {$old_container_id}.");
			}
		}
	}

	// 2. Adaugă comanda în containerul nou
	$new_container_orders = get_post_meta($new_container_id, 'container_orders', true);
	if (empty($new_container_orders) || !is_array($new_container_orders)) {
		$new_container_orders = []; // Inițializează dacă nu există sau nu e array
	}

	if (!in_array($order_id, $new_container_orders)) {
		$new_container_orders[] = $order_id;
		update_post_meta($new_container_id, 'container_orders', $new_container_orders);
		error_log("Added order ID {$order_id} to new container ID {$new_container_id}.");
	} else {
		error_log("Order ID {$order_id} already exists in container ID {$new_container_id}.");
	}


	// 3. Actualizează meta comenzii cu ID-ul noului container
	update_post_meta($order_id, 'container_id', $new_container_id);
	error_log("Updated 'container_id' meta for order ID {$order_id} to {$new_container_id}.");
}

//----------------------------------------------------------------------

/**
 * Actualizează costul reparației și trimite email de notificare dacă este necesar.
 * Logica pare specifică unui CPT de reparații care are legătură cu o comandă originală.
 *
 * @param int   $repair_post_id ID-ul postului de reparație (sau unde se salvează costul).
 * @param array $post_data Array-ul $_POST.
 */
function matrix_handle_cost_repair_update($repair_post_id, $post_data) {
	error_log("Handling cost repair update for post ID: {$repair_post_id}");

	$new_cost_str = $post_data['cost_repair']; // Valoarea din formular
	$type_cost = sanitize_text_field($post_data['type_cost']);

	// Obținem valorile vechi pentru comparație și context
	$old_cost_field = get_post_meta($repair_post_id, 'cost_repair', true);
	$warranty_data = get_post_meta($repair_post_id, 'warranty', true); // Pare a fi un array de la itemi
	$original_order_id = get_post_meta($repair_post_id, 'order-id-original', true);
	$order_id_scv = get_post_meta($repair_post_id, 'order-id-scv', true); // Un alt ID de referință?

	// Actualizăm tipul costului indiferent dacă costul s-a schimbat
	update_post_meta($repair_post_id, 'type_cost', $type_cost);

	// Continuăm doar dacă costul introdus este diferit de cel salvat anterior
	// Comparăm ca stringuri sau ca numere? Să folosim numere pentru siguranță.
	$new_cost_float = floatval(str_replace(',', '.', $new_cost_str)); // Convertim în float (gestionăm virgula ca separator zecimal dacă e cazul)
	$old_cost_float = floatval($old_cost_field);

	// Folosim o toleranță mică pentru comparația float
	$tolerance = 0.001;
	if (abs($new_cost_float - $old_cost_float) > $tolerance) {
		error_log("Cost repair changed for post ID {$repair_post_id}. Old: {$old_cost_float}, New Input: {$new_cost_float}. Triggering update and email.");

		// Actualizează statusul (custom?) la 'pending'
		update_post_meta($repair_post_id, 'order_status', 'pending');

		// Verifică dacă există o comandă originală asociată
		if (!$original_order_id) {
			error_log("Missing 'order-id-original' meta for repair post ID: {$repair_post_id}. Cannot process warranty items or send email.");
			// Salvăm costul introdus direct, fără calcul de garanție
			update_post_meta($repair_post_id, 'cost_repair', $new_cost_float);
			error_log("Saved raw new cost {$new_cost_float} for repair post ID: {$repair_post_id} due to missing original order ID.");
			return;
		}

		$order = wc_get_order($original_order_id);
		if (!$order) {
			error_log("Could not get original order object for ID: {$original_order_id} (from repair post {$repair_post_id}).");
			update_post_meta($repair_post_id, 'cost_repair', $new_cost_float);
			error_log("Saved raw new cost {$new_cost_float} for repair post ID: {$repair_post_id} due to failed original order fetch.");
			return;
		}

		$items = $order->get_items();
		$warranty_cost = 0; // Cost adițional pentru itemii fără garanție
		$has_non_warranty_items = false;

		// Calculează costul adițional pentru itemii fără garanție (dacă tipul e 'sqm')
		if ($type_cost == 'sqm' && is_array($warranty_data)) {
			foreach ($items as $item_id => $item_data) {
				// Verifică dacă există status garanție pentru acest item și dacă e 'No'
				if (isset($warranty_data[$item_id]) && $warranty_data[$item_id] == 'No') {
					$item_cost_w = 10; // Cost de bază per item fără garanție
					$product_id = $item_data['product_id'];
					$property_total = get_post_meta($product_id, 'property_total', true); // Presupunem că e SQM

					if ($property_total && is_numeric($property_total) && $property_total > 1) {
						$item_cost_w = 10 * floatval($property_total); // Multiplică cu SQM dacă e > 1
					}
					$warranty_cost += $item_cost_w;
					$has_non_warranty_items = true;
					error_log("Item ID {$item_id} (Product ID {$product_id}) - No Warranty. Adding cost: {$item_cost_w}. SQM: {$property_total}");
				}
			}
		}

		// Calculează costul final: cost nou introdus + cost garanție + TVA (20%? - Hardcodat)
		// @TODO: Rata TVA (0.2) ar trebui să fie configurabilă sau obținută din setări WC.
		$tax_rate_hardcoded = 0.20;
		$cost_before_tax = $new_cost_float + $warranty_cost;
		$final_cost = $cost_before_tax * (1 + $tax_rate_hardcoded);


		// Salvează costul final calculat
		update_post_meta($repair_post_id, 'cost_repair', number_format($final_cost, 2, '.', '')); // Salvăm formatat
		error_log("Calculated final cost for repair post {$repair_post_id}: Base={$new_cost_float}, Warranty={$warranty_cost}, TaxRate={$tax_rate_hardcoded}, Final={$final_cost}");

		// Trimite email DOAR dacă au existat itemi fără garanție (conform logicii originale?)
		// @TODO: Clarifică condiția de trimitere email. Original pare să trimită dacă costul s-a schimbat și *potențial* există itemi fără garanție.
		// Să presupunem că emailul se trimite dacă costul s-a modificat și *este mai mare decât zero*.
		if ($final_cost > 0) {

			$billing_email = $order->get_billing_email();
			$billing_name = $order->get_formatted_billing_full_name();

			// Destinatari (Hardcodat - nu e ideal)
			// @TODO: Destinatarii ar trebui să fie configurabili.
			$multiple_recipients = [

			  'marian93nes@gmail.com',
				// Adaugă și email-ul clientului?
				// $billing_email
			];

			$subject = get_the_title($repair_post_id) . ' - Repair Order Update'; // Titlu mai generic
			// Construiește corpul emailului
			$repair_permalink = get_permalink($repair_post_id);
			$body = "<p>Dear {$billing_name},</p>";
			$body .= "<p>Thank you for your recent repair order with Matrix.</p>";
			// Adaugă detalii dacă există itemi fără garanție
			if ($has_non_warranty_items) {
				$subject .= ' - Requires Payment'; // Adaugă la subiect
				$body .= "<p>Some items in your repair were identified as not under warranty.</p>";
			}
			$body .= "<p>Please quote the following details should you have any questions regarding this order at any stage.</p>";
			$body .= "<p>Lifetime Number: LFR{$order_id_scv}</p>"; // Presupune că $order_id_scv este mereu prezent
			$body .= "<p>You can find the Repair Order Summary by following this link:<br>";
			$body .= "<a href='{$repair_permalink}'>{$repair_permalink}</a></p>";

			// Adaugă secțiunea de plată dacă costul e pozitiv
			if ($final_cost > 0) {
				$body .= "<p>Your repair order has been processed. Once payment has been received manufacturing/repair will commence.</p>";
				$body .= "<p>Payments can be made via international bank transfer to the following account:</p>";
				$body .= "<p>Account Name: Lifetime Shutters<br>Sort Code: 20-46-73<br>Account Number: 13074145</p>";
				$body .= "<p>Amount due: £" . number_format($final_cost, 2, '.', '') . "</p>";
			} else {
				$body .= "<p>Your repair order is being processed and we will contact you with delivery arrangements in due course.</p>";
			}

			$body .= "<p>Should you have any questions please contact us at order@lifetimeshutters.com</p>";
			$body .= "<p>Kind regards,<br>Accounts Department</p>";


			$headers = ['Content-Type: text/html; charset=UTF-8', 'From: Service LifetimeShutters <service@lifetimeshutters.co.uk>'];

			// Trimite email-ul
			$mail_sent = wp_mail($multiple_recipients, $subject, $body, $headers);

			if ($mail_sent) {
				error_log("Notification email sent successfully for repair post {$repair_post_id} to " . implode(', ', $multiple_recipients));
			} else {
				error_log("Failed to send notification email for repair post {$repair_post_id}.");
			}
		} else {
			error_log("Skipping email notification for repair post {$repair_post_id} as final cost is zero or less.");
		}

	}
	// else { error_log("Cost repair unchanged for post ID {$repair_post_id}."); }
}

//----------------------------------------------------------------------

/**
 * Recalculează totalurile unei comenzi pe baza metadatelor produselor
 * (preț, cantitate, SQM, preț tren) și actualizează tabela custom `wp_custom_orders`.
 * Atenție: Această funcție suprascrie totalurile calculate de WC și folosește o logică custom.
 *
 * @param int $order_id ID-ul comenzii de recalculat.
 */
function matrix_handle_order_recalculation($order_id) {
	error_log("--- Starting Order Recalculation for Order ID: {$order_id} ---");

	$order = wc_get_order($order_id);
	if (!$order) {
		error_log("Recalculation Error: Could not get order object for ID: {$order_id}");
		return;
	}

	$user_id_customer = $order->get_customer_id(); // Metodă mai sigură decât get_post_meta
	$items = $order->get_items(); // Obține doar itemii de tip 'line_item'

	// Inițializează totalurile calculate
	$calculated_subtotal_gbp = 0; // Suma totalurilor liniilor (preț * cantitate + preț tren)
	$calculated_total_tax_gbp = 0; // Suma taxelor liniilor
	$calculated_total_usd = 0;     // Suma prețurilor în USD * cantitate
	$calculated_total_sqm = 0;     // Suma totală SQM * cantitate
	$calculated_order_train_price = 0; // Suma totală a prețului de transport 'tren'

	$order_contains_pos = false; // Flag pentru a detecta produse POS

	// Determină rata de taxare (Logica originală pare specifică UK/Irlanda)
	// @TODO: Această logică ar trebui ideal să folosească clasele de taxe WC.
	$shipping_country = $order->get_shipping_country();
	$tax_rate_percent = 0;
	if (in_array($shipping_country, ['GB', 'IE'])) {
		$tax_rate_percent = 20;
	}
	error_log("Order {$order_id}: Shipping Country={$shipping_country}, Determined Tax Rate={$tax_rate_percent}%");


	// --- Iterează prin itemii comenzii ---
	foreach ($items as $item_id => $item_data) {
		$product_id = $item_data['product_id'];
		$_product = $item_data->get_product(); // Obține obiectul produs

		if (!$_product) {
			error_log("Recalculation Warning for Order {$order_id}: Could not get product object for Product ID: {$product_id}, Item ID: {$item_id}");
			continue;
		}

		// Verifică dacă produsul aparține categoriilor POS (20, 34, 26)
		// @TODO: ID-urile categoriilor (20, 34, 26) ar trebui stocate în opțiuni/constante, nu hardcodate.
		if (has_term([20, 34, 26], 'product_cat', $product_id)) {
			$order_contains_pos = true;
			error_log("Order {$order_id}: Item ID {$item_id} (Product ID {$product_id}) is a POS item. Skipping custom calculation for this item.");
			// Poate ar trebui să adunăm totalurile existente ale acestor itemi la totalul final?
			// Deocamdată, îi ignorăm complet în calculul custom, conform logicii originale.
			continue; // Treci la următorul item
		}

		// --- Obține datele necesare din meta produs ---
		// Folosim $item_data->get_quantity() în loc de meta, e mai sigur.
		// $quantity_meta = get_post_meta($product_id, 'quantity', true); // Logica originală citea meta 'quantity' a produsului! NU a itemului comenzii. Folosim cantitatea itemului.
		$item_quantity = $item_data->get_quantity();

		// Prețul de bază al produsului
		// Folosim prețul produsului direct, nu meta. Poate fi preț normal sau de vânzare.
		// $price_meta = get_post_meta($product_id, '_price', true); // Logica originală citea meta '_price'. Folosim prețul curent.
		$base_price = floatval($_product->get_price());

		// Metraj pătrat (SQM)
		$sqm = floatval(get_post_meta($product_id, 'property_total', true));

		// Preț transport 'tren'
		$train_price_per_sqm = 0;
		$item_train_price_total = 0;
		if ($sqm > 0 && $user_id_customer > 0) { // Prețul tren se aplică doar dacă avem SQM și client logat?

			// MODIFICARE: Verifică mai întâi dacă există preț train original salvat în primul produs
			$original_train_price = null;
			$first_item_processed = false;

			// Caută în primul item al comenzii dacă există price_item_train
			if (!$first_item_processed) {
				$original_train_price = get_post_meta($product_id, 'price_item_train', true);
				$first_item_processed = true;

				if (!empty($original_train_price) && is_numeric($original_train_price)) {
					error_log("Order {$order_id}: Found original train price in first item meta: {$original_train_price}");
				}
			}

			// Folosește prețul original dacă există, altfel folosește logica actuală
			if (!empty($original_train_price) && is_numeric($original_train_price)) {
				$train_price_per_sqm = floatval($original_train_price);
				error_log("Order {$order_id}: Using original train price from item meta: {$train_price_per_sqm}");
			} else {
				// Logica originală - folosește prețul curent din user_meta sau global
				$train_price_user = get_user_meta($user_id_customer, 'train_price', true);
				$train_price_per_sqm = ($train_price_user !== null && $train_price_user !== '')
				  ? floatval($train_price_user)
				  : floatval(get_post_meta(1, 'train_price', true)); // Default global

				error_log("Order {$order_id}: No original train price found, using current price: {$train_price_per_sqm}");
			}

			if ($train_price_per_sqm > 0) {
				$item_train_price_total = $sqm * $item_quantity * $train_price_per_sqm;
				// Salvează prețul tren calculat pe meta produs (Logica originală făcea asta)
				update_post_meta($product_id, 'train_delivery', $item_train_price_total);
				$calculated_order_train_price += $item_train_price_total;
			}
		}


		// Preț în USD (din meta produs)
		$dolar_price = floatval(get_post_meta($product_id, 'dolar_price', true));


		// --- Calculează valorile pentru linia curentă ---
		$line_subtotal_gbp = ($base_price * $item_quantity) + $item_train_price_total;
		$line_tax_gbp = ($line_subtotal_gbp * $tax_rate_percent) / 100;
		$line_total_usd = $dolar_price * $item_quantity;
		$line_total_sqm = $sqm * $item_quantity;


		// --- Actualizează meta itemului în comandă ---
		// Formatăm valorile numerice folosind funcțiile WC
		$line_tax_data_array = ['total' => [], 'subtotal' => []];
		if ($line_tax_gbp > 0) {
			// Presupunem că ID-ul ratei de taxare este 1 (hardcodat în logica originală)
			// @TODO: ID-ul taxei (1) ar trebui determinat dinamic sau configurat.
			$tax_rate_id_hardcoded = 1;
			$line_tax_data_array = [
			  'total'    => [$tax_rate_id_hardcoded => wc_format_decimal($line_tax_gbp)],
			  'subtotal' => [$tax_rate_id_hardcoded => wc_format_decimal($line_tax_gbp)],
			];
		}

		wc_update_order_item_meta($item_id, '_qty', $item_quantity); // Cantitatea e deja corectă, dar o rescriem pentru consistență.
		wc_update_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($line_subtotal_gbp));
		wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($line_tax_gbp));
		wc_update_order_item_meta($item_id, '_line_total', wc_format_decimal($line_subtotal_gbp)); // WC calculează total = subtotal + tax, deci suprascriem subtotalul aici.
		wc_update_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($line_tax_gbp));
		wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data_array); // Salvează array-ul cu ID-ul ratei de taxare

		error_log("Order {$order_id}, Item ID {$item_id}: Qty={$item_quantity}, BasePrice={$base_price}, TrainPrice={$item_train_price_total}, SubtotalGBP={$line_subtotal_gbp}, TaxGBP={$line_tax_gbp}, TotalUSD={$line_total_usd}, TotalSQM={$line_total_sqm}");

		// --- Adaugă la totalurile calculate ale comenzii ---
		$calculated_subtotal_gbp += $line_subtotal_gbp;
		$calculated_total_tax_gbp += $line_tax_gbp;
		$calculated_total_usd += $line_total_usd;
		$calculated_total_sqm += $line_total_sqm;

	} // Sfârșit foreach items

	// --- Actualizează meta și totalurile comenzii (doar dacă nu conține POS) ---
	if ($order_contains_pos === false) {
		error_log("Order {$order_id} does not contain POS items. Proceeding with final total updates.");

		// Salvează prețul total 'tren' pe meta comenzii
		if ($calculated_order_train_price > 0) {
			update_post_meta($order_id, 'order_train', number_format($calculated_order_train_price, 2, '.', ''));
			error_log("Order {$order_id}: Updated 'order_train' meta: " . number_format($calculated_order_train_price, 2, '.', ''));
		}

		// Obține costul de transport și taxa pe transport din comandă
		$order_shipping_total = $order->get_shipping_total();
		$order_shipping_tax = $order->get_shipping_tax(); // Obține taxa totală pe transport


		// Calculează totalul final GBP al comenzii
		$final_order_total_gbp = $calculated_subtotal_gbp + $calculated_total_tax_gbp + $order_shipping_total + $order_shipping_tax;

		// Actualizează totalurile principale ale comenzii folosind metodele WC
		try {
			$order->set_props([
			  'total'     => wc_format_decimal($final_order_total_gbp),
			  'total_tax' => wc_format_decimal($calculated_total_tax_gbp + $order_shipping_tax), // Taxa totală = taxa pe itemi + taxa pe transport
			  'cart_tax'  => wc_format_decimal($calculated_total_tax_gbp), // Taxa doar pe itemi
				// Nota: WC calculează subtotalul înainte de discounturi. Setarea manuală aici poate fi imprecisă.
				// Setăm subtotalul calculat pentru consistență cu logica originală, dar e posibil să nu reflecte subtotalul real WC.
				// 'subtotal' => wc_format_decimal($calculated_subtotal_gbp), // Comentat - lăsăm WC să gestioneze subtotalul pe cât posibil
			]);
			$order->save();
			error_log("Order {$order_id}: Final totals updated. TotalGBP={$final_order_total_gbp}, TotalTaxGBP=" . wc_format_decimal($calculated_total_tax_gbp + $order_shipping_tax));

		} catch (Exception $e) {
			error_log("Order {$order_id}: Error saving final order totals: " . $e->getMessage());
		}


		// --- Actualizează tabela custom `wp_custom_orders` ---
		// @TODO: Numele tabelei ar trebui prefixat corect și verificat.
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_orders'; // Asigură prefix corect

		$idOrder = $order_id;
		// Folosește prepare pentru siguranță
		$order_exists_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `{$table_name}` WHERE `idOrder` = %d", $idOrder));

		// Date comune pentru insert/update
		$custom_data = [
		  'usd_price'       => number_format($calculated_total_usd, 2, '.', ''),
		  'gbp_price'       => number_format($final_order_total_gbp, 2, '.', ''), // Folosim totalul final calculat
		  'subtotal_price'  => number_format($calculated_subtotal_gbp, 2, '.', ''), // Folosim subtotalul calculat (itemi + tren)
		  'sqm'             => number_format($calculated_total_sqm, 2, '.', ''),
		  'shipping_cost'   => number_format($order_shipping_total, 2, '.', ''),
			// 'delivery' și 'status' par să lipsească din calculul curent, le preluăm din comandă
		  'delivery'        => $order->get_shipping_method(), // Sau $order->get_shipping_methods()[0]->get_method_id() ? Depinde ce vrei să stochezi.
		  'status'          => $order->get_status(),
			// 'reference' și 'createTime' sunt doar pentru insert
		];


		if ($order_exists_count > 0) {
			// Update
			$where = ['idOrder' => $idOrder];
			$updated = $wpdb->update($table_name, $custom_data, $where);

			if ($updated !== false) {
				error_log("Order {$order_id}: Updated record in `{$table_name}`.");
			} else {
				error_log("Order {$order_id}: Failed to update record in `{$table_name}`. WPDB Error: " . $wpdb->last_error);
			}
		} else {
			// Insert
			// Adaugă câmpurile specifice pentru insert
			$custom_data['idOrder'] = $idOrder;
			$custom_data['reference'] = 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($idOrder, 'cart_name', true); // Folosește cart_name actualizat
			$custom_data['createTime'] = $order->get_date_created() ? $order->get_date_created()->date('Y-m-d H:i:s') : current_time('mysql', 1); // Data creării comenzii

			$inserted = $wpdb->insert($table_name, $custom_data);

			if ($inserted) {
				error_log("Order {$order_id}: Inserted new record into `{$table_name}`.");
			} else {
				error_log("Order {$order_id}: Failed to insert record into `{$table_name}`. WPDB Error: " . $wpdb->last_error);
			}
		}
	} else {
		error_log("Order {$order_id} contains POS items. Skipping final total update and custom table update.");
	}
	error_log("--- Finished Order Recalculation for Order ID: {$order_id} ---");
}


//
//function save_order_post($post_id)
//{
//	error_log('eroare custom save_order_post');
//
//	if (get_post_type($post_id) == 'shop_order') {
//		$order_id = $post_id;
//		$order = new WC_Order($order_id);
//
//		$order_status = $order->get_status();
//		if ($order_status == 'on-hold') {
//			$name = get_post_meta($order_id, 'cart_name', true);
//
//			$single_email = 'marian93nes@gmail.com';
//			$multiple_recipients = array(
//				'caroline@anyhooshutter.com', 'july@anyhooshutter.com', 'tudor@lifetimeshutters.com',
//			);
//
//			$subject = 'ON-HOLD Order LF0' . $order->get_order_number() . ' - ' . $name . ' - for REVISION';
//			$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');
//
//			$mess = '
//        Hi July, Kevin <br />
//        <br />
//       Please put this order ON-HOLD until revised by dealer.<br />
//The revised order will be sent to you when ready.<br />
//        <br />
//        Kind regards. <br />
//        <br />
//        ';
//
//			wp_mail($single_email, $subject, $mess, $headers);
//		}
//	}
//}
//
//
//add_action('save_post', 'save_order_post');