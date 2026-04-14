<?php
/**
 * AJAX handler: Create new product (standard single-section).
 *
 * Thin wrapper: bootstrap, security, input parsing, delegation to classes, response.
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

// ---- Security ----
if ( ! is_user_logged_in() ) {
	wp_send_json_error( 'Unauthorized', 403 );
}


// ---- Parse & Sanitize ----
parse_str($_POST['prod'], $products);
$products = horeka_sanitize_pricing_input( $products );

// ---- Calculate ----
$user_id = get_current_user_id();

$config     = new PricingConfig( $user_id );
$calculator = new PricingCalculator();
$result     = $calculator->calculate( $products, $config );

// ---- Create Product ----
$handler = new ProductHandler();
$post_id = $handler->createProduct( $products, $result, $user_id );

// ---- Pricing Log ----
PricingLogWriter::write( $result, $products, $user_id, $post_id );

// ---- Cart or Order ----
$order_handler = new OrderHandler();

global $woocommerce;
if ( ! empty( $products['order_edit'] ) && ! empty( $products['edit_customer'] ) ) {
	update_post_meta( $post_id, 'order_edit', $products['order_edit'] );

	$order_handler->addToOrder( $post_id, $products );
	$order_handler->manageOtherColorProducts( $products['order_edit'], $post_id, $products );
	$order_handler->recalculateOrder( $products['order_edit'], $user_id );
} else {
	$handler->addToCart( $post_id, $products );
	$order_handler->manageOtherColorCart( $post_id, $products );
	$handler->setShippingClass( $post_id );
}

// ---- JSON Response ----
wp_send_json_success( array(
	'post_id'   => $post_id,
	'gbp_price' => $result->gbp_price,
	'usd_price' => $result->usd_price,
) );
