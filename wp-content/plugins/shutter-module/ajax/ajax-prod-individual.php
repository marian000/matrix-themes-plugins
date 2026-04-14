<?php
/**
 * AJAX handler: Create new product (individual multi-section).
 *
 * Thin wrapper: bootstrap, security, input parsing, per-section pricing
 * via PricingCalculator::calculateMultiSection(), product creation via
 * ProductHandler, cart/order handling via OrderHandler.
 *
 * The "individual" flow creates ONE product but calculates pricing per section.
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

// ---- Calculate per-section pricing ----
$user_id    = get_current_user_id();
$config     = new PricingConfig( $user_id );
$calculator = new PricingCalculator();
$result     = $calculator->calculateMultiSection( $products, $config );

// ---- Create Product ----
$handler = new ProductHandler();
$post_id = $handler->createProduct( $products, $result, $user_id );

// ---- Pricing Log ----
PricingLogWriter::write( $result, $products, $user_id, $post_id );

// Save per-section USD prices as individual meta keys.
foreach ( $result->dolar_sections_price as $sec => $usd_price ) {
	update_post_meta( $post_id, 'dolar_price_section_' . $sec, floatval( $usd_price ) );
}

// ---- Cart or Order ----
$order_handler = new OrderHandler();

global $woocommerce;
if ( ! empty( $products['order_edit'] ) && ! empty( $products['edit_customer'] ) ) {
	update_post_meta( $post_id, 'order_edit', $products['order_edit'] );

	$order_handler->addToOrder( $post_id, $products );
	$order_handler->manageOtherColorProductsIndividual( $products['order_edit'], $post_id, $products );
	$order_handler->recalculateOrder( $products['order_edit'], $user_id );
} else {
	$handler->addToCart( $post_id, $products );
	$order_handler->manageOtherColorCartIndividual( $post_id, $products );
	$handler->setShippingClass( $post_id );
}

// ---- JSON Response ----
wp_send_json_success( array(
	'post_id'   => $post_id,
	'gbp_price' => $result->gbp_price,
	'usd_price' => $result->usd_price,
) );
