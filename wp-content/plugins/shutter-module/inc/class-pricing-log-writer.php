<?php
/**
 * PricingLogWriter: writes pricing calculation debug output to pricing-log.txt.
 *
 * Activated only when 'shutter_log_pricing_enabled' wp_option is truthy.
 * Called from AJAX pricing handlers after PricingCalculator::calculate().
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PricingLogWriter {

	/**
	 * Write a pricing calculation result to pricing-log.txt.
	 *
	 * @since 1.0.0
	 * @param PricingResult $result   The completed pricing result object.
	 * @param array         $products The sanitized products input array.
	 * @param int           $user_id  Current user ID.
	 * @param int           $post_id  Created/updated post ID (0 if not yet created).
	 * @return void
	 */
	public static function write( PricingResult $result, array $products, int $user_id, int $post_id = 0, string $action = 'CREATE' ) {
		if ( ! (int) get_option( 'shutter_log_pricing_enabled', 0 ) ) {
			return;
		}

		$timestamp    = current_time( 'Y-m-d H:i:s' );
		$material_key = ! empty( $result->material_key ) ? $result->material_key : 'unknown';
		$sqm          = number_format( $result->sqm, 2 );
		$gbp          = number_format( $result->gbp_price, 2 );
		$usd          = number_format( $result->usd_price, 2 );
		$style_id     = isset( $products['property_style'] ) ? intval( $products['property_style'] ) : 0;
		$frame_id     = isset( $products['property_frametype'] ) ? intval( $products['property_frametype'] ) : 0;
		$order_id     = ! empty( $products['order_edit'] ) ? intval( $products['order_edit'] ) : 0;
		$cart_name    = '-';
		if ( function_exists( '\BMC\WooCommerce\MultiSession\customer_id' ) ) {
			$cart_session_id = \BMC\WooCommerce\MultiSession\customer_id();
			if ( $cart_session_id && function_exists( '\BMC\WooCommerce\MultiSession\get_carts' ) ) {
				$carts = \BMC\WooCommerce\MultiSession\get_carts();
				if ( isset( $carts[ $cart_session_id ]['name'] ) ) {
					$cart_name = $carts[ $cart_session_id ]['name'];
				}
			}
		}

		$header = sprintf(
			'=== [%s] [%s] User:%d Post:%d Order:%d Cart:%s Material:%s SQM:%s Style:%d Frame:%d GBP:%s USD:%s ===',
			$timestamp,
			strtoupper( $action ),
			$user_id,
			$post_id,
			$order_id,
			$cart_name,
			$material_key,
			$sqm,
			$style_id,
			$frame_id,
			$gbp,
			$usd
		);

		$body = '';
		foreach ( $result->debug_log as $entry ) {
			$value = $entry['value'];
			if ( is_array( $value ) ) {
				$value = wp_json_encode( $value );
			}
			$body .= sprintf( '[%s] %s: %s', $entry['phase'], $entry['label'], $value ) . PHP_EOL;
		}

		$record = $header . PHP_EOL . $body . '--- end ---' . PHP_EOL . PHP_EOL;

		self::maybe_rotate();

		file_put_contents( SHUTTER_LOG_PRICING_FILE, $record, FILE_APPEND | LOCK_EX );
	}

	/**
	 * Rotate pricing-log.txt if it exceeds the configured max size.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function maybe_rotate() {
		if ( ! file_exists( SHUTTER_LOG_PRICING_FILE ) ) {
			return;
		}

		if ( filesize( SHUTTER_LOG_PRICING_FILE ) > shutter_get_log_max_size() ) {
			rename( SHUTTER_LOG_PRICING_FILE, SHUTTER_LOG_PRICING_FILE . '.' . time() . '.bak' );
			shutter_cleanup_old_bak_files();
		}
	}
}
