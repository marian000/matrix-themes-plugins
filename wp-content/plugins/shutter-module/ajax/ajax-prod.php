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

// ---- Resolve pricing user ----
// Order-edit create: price with the customer's rates (same check as ajax-prod-update.php).
// The product author stays the current user.
$user_id = get_current_user_id();

$pricing_user_id = $user_id;
if ( ! empty( $products['order_edit'] ) && ! empty( $products['edit_customer'] ) ) {
	$pricing_user_id = isset( $products['customer_id'] ) ? intval( $products['customer_id'] ) : 0;
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
}

// ---- Calculate ----
$config     = new PricingConfig( $pricing_user_id );
$calculator = new PricingCalculator();
$result     = $calculator->calculate( $products, $config );

// ---- Create Product ----
$handler = new ProductHandler();
$post_id = $handler->createProduct( $products, $result, $user_id, $pricing_user_id );

// ---- Pricing Log ----
PricingLogWriter::write( $result, $products, $pricing_user_id, $post_id );

// ---- Cart or Order ----
$order_handler = new OrderHandler();

global $woocommerce;
if ( ! empty( $products['order_edit'] ) && ! empty( $products['edit_customer'] ) ) {
	update_post_meta( $post_id, 'order_edit', $products['order_edit'] );

	$order_handler->addToOrder( $post_id, $products );
	$order_handler->manageOtherColorProducts( $products['order_edit'], $post_id, $products );
	$order_handler->recalculateOrder( $products['order_edit'], $pricing_user_id );
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
