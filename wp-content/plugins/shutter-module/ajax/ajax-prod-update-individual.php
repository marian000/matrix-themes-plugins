<?php
/**
 * AJAX handler: Update existing product (individual multi-section).
 *
 * Thin wrapper: bootstrap, security, input parsing, per-section pricing
 * via PricingCalculator::calculateMultiSection(), product update via
 * ProductHandler, order/cart handling via OrderHandler.
 *
 * The "individual" flow updates ONE product but calculates pricing per section.
 * Each section has its own SQM (property_total_sectionN) and layout code
 * (property_layoutcodeN). The final product price is the sum of all sections.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

// ---- Dependencies ----
require_once __DIR__ . '/../includes/pricing-helpers.php';
require_once __DIR__ . '/../includes/pricing-maps.php';
require_once __DIR__ . '/../includes/sanitize-input.php';
require_once __DIR__ . '/../includes/class-pricing-config.php';
require_once __DIR__ . '/../includes/class-pricing-result.php';
require_once __DIR__ . '/../includes/class-pricing-calculator.php';
require_once __DIR__ . '/../includes/class-product-handler.php';
require_once __DIR__ . '/../includes/class-order-handler.php';
require_once __DIR__ . '/../inc/class-pricing-log-writer.php';

// ---- Security (Task #001) ----
if ( ! is_user_logged_in() ) {
	wp_send_json_error( 'Unauthorized', 403 );
}


// ---- Parse & Sanitize ----
parse_str($_POST['prod'], $products);
$products = horeka_sanitize_pricing_input( $products );

// ---- Resolve pricing user (Task #003) ----
$user_id = get_current_user_id();

$pricing_user_id = intval( $products['customer_id'] );
if ( $pricing_user_id && $pricing_user_id !== $user_id ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		$customer_dealer     = get_user_meta( $pricing_user_id, 'company_parent', true );
		$current_user_dealer = get_user_meta( $user_id, 'company_parent', true );
		if ( intval( $customer_dealer ) !== $user_id && intval( $current_user_dealer ) !== $user_id ) {
			wp_send_json_error( 'Unauthorized to modify this customer order', 403 );
		}
	}
} else {
	$pricing_user_id = $user_id;
}

$post_id  = $products['product_id_updated'];
$order_id = $products['order_edit'];

// ---- Calculate per-section pricing ----
$config     = new PricingConfig( $pricing_user_id );
$calculator = new PricingCalculator();
$result     = $calculator->calculateMultiSection( $products, $config );

// ---- Pricing Log ----
PricingLogWriter::write( $result, $products, $pricing_user_id, intval( $post_id ), 'UPDATE' );

// ---- Clean stale meta per-section layout codes ----
$handler     = new ProductHandler();
$nr_sections = intval( $products['property_nr_sections'] );
for ( $sec = 1; $sec <= $nr_sections; $sec++ ) {
	$layout_code = isset( $products[ 'property_layoutcode' . $sec ] ) ? $products[ 'property_layoutcode' . $sec ] : '';
	$handler->cleanStaleMeta( $post_id, $layout_code );
}

// ---- Update Product ----
$handler->updateProduct( $post_id, $products, $result, $pricing_user_id );

// Save per-section USD prices as individual meta keys.
foreach ( $result->dolar_sections_price as $sec => $usd_price ) {
	update_post_meta( $post_id, 'dolar_price_section_' . $sec, floatval( $usd_price ) );
}

// ---- Order Handling ----
$order_handler = new OrderHandler();

if ( ! empty( $products['order_edit'] ) && ! empty( $products['edit_customer'] ) ) {
	$order_handler->manageOtherColorProductsIndividual( $products['order_edit'], $post_id, $products );
	$order_handler->recalculateOrder( $products['order_edit'], $pricing_user_id );
}

if ( ! empty( $products['order_item_id'] ) ) {
	wc_update_order_item_meta( $products['order_item_id'], '_qty', $products['quantity'] );
}

// ---- Cart Color & Shipping ----
$order_handler->manageOtherColorCartUpdateIndividual( $post_id, $products );
$handler->setShippingClass( $post_id );

// ---- JSON Response ----
wp_send_json_success( array(
	'post_id'   => $post_id,
	'gbp_price' => $result->gbp_price,
	'usd_price' => $result->usd_price,
) );
