<?php
/**
 * AJAX handler: Save Level 3 per-material global pricing defaults.
 *
 * Receives serialised form data from the "Per Material" admin tab in page-show.php.
 * Parses l3_gbp-{material_id}-{option_key} and l3_usd-{material_id}-{option_key} fields
 * and saves them as post meta on post ID 1 using the mat_{id}_{key} naming convention.
 *
 * Empty field submissions delete the corresponding post meta entry, allowing the
 * pricing engine to fall through to Level 4 (general global default).
 *
 * Material IDs and option keys are checked against the theme lists
 * (matrix_get_material_ids(), matrix_get_per_material_option_keys()). Values go through
 * matrix_normalize_price_input(); a non-numeric or negative value is not saved and its
 * field name is returned in the "rejected" array of the JSON response.
 *
 * Security: requires manage_options capability and valid nonce.
 *
 * @package ShutterModule
 */

// Bootstrap WordPress via the direct-URL pattern used by all shutter-module AJAX handlers.
$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
include( $path . 'wp-load.php' );

// Security: require administrator capability.
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Unauthorized', '', array( 'response' => 403 ) );
}

// Verify nonce.
if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'matrix_l3_pricing_nonce' ) ) {
	wp_die( 'Invalid nonce', '', array( 'response' => 403 ) );
}

if ( ! isset( $_POST['attributes'] ) ) {
	wp_die( 'No data', '', array( 'response' => 400 ) );
}

// Key lists and price validation live in the theme (material-pricing-keys.php, loaded by functions.php).
$matrix_l3_required = array(
	'matrix_get_material_ids',
	'matrix_get_per_material_option_keys',
	'matrix_get_per_material_usd_excludes',
	'matrix_normalize_price_input',
);
if ( ! function_exists( 'matrix_normalize_price_input' ) ) {
	$matrix_keys_file = get_stylesheet_directory() . '/includes/material-pricing-keys.php';
	if ( file_exists( $matrix_keys_file ) ) {
		require_once $matrix_keys_file;
	}
}
foreach ( $matrix_l3_required as $matrix_l3_function ) {
	if ( ! function_exists( $matrix_l3_function ) ) {
		wp_die( 'Pricing key list not available', '', array( 'response' => 500 ) );
	}
}

parse_str( $_POST['attributes'], $attributes );

// Keys that have no USD variant.
$usd_excludes = matrix_get_per_material_usd_excludes();

// Whitelist of valid material IDs and option keys.
$valid_material_ids = array_keys( matrix_get_material_ids() );
$valid_option_keys  = matrix_get_per_material_option_keys();

// Fields whose value was not a number of 0 or more (stored meta left unchanged).
$rejected = array();

foreach ( $attributes as $field_name => $val ) {
	// Expected format: l3_gbp-{material_id}-{option_key} or l3_usd-{material_id}-{option_key}
	if ( ! preg_match( '/^l3_(gbp|usd)-(\d+)-(.+)$/', $field_name, $matches ) ) {
		continue;
	}

	$currency    = $matches[1];               // 'gbp' or 'usd'
	$material_id = intval( $matches[2] );     // numeric material ID
	$option_key  = $matches[3];               // option key string, checked against the whitelist below
	$value       = matrix_normalize_price_input( $val );

	// Reject unknown material IDs.
	if ( ! in_array( $material_id, $valid_material_ids, true ) ) {
		continue;
	}

	// Reject unknown option keys.
	if ( ! in_array( $option_key, $valid_option_keys, true ) ) {
		continue;
	}

	// Reject non-numeric or negative values; keep the stored value.
	if ( null === $value ) {
		$rejected[] = $field_name;
		continue;
	}

	// Build the post meta key.
	if ( 'gbp' === $currency ) {
		$meta_key = 'mat_' . $material_id . '_' . $option_key;
	} else {
		// Skip USD save for option keys that have no USD variant.
		if ( in_array( $option_key, $usd_excludes, true ) ) {
			continue;
		}
		$meta_key = 'mat_' . $material_id . '_' . $option_key . '-dolar';
	}

	// Empty submission removes Level 3 override; pricing engine falls through to Level 4.
	if ( '' === $value ) {
		delete_post_meta( 1, $meta_key );
	} else {
		update_post_meta( 1, $meta_key, $value );
	}
}

echo wp_json_encode( array( 'status' => 'ok', 'rejected' => $rejected ) );
