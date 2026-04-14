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

parse_str( $_POST['attributes'], $attributes );

// Keys that have no USD variant (consistent with material-pricing-keys.php USD exclusions).
$usd_excludes = array( '4008T', 'T_typeAdjustable', 'tposttype_blackout', 'bposttype_blackout' );

// Whitelist of valid material IDs (mirrors PricingConfig::MATERIALS and matrix_get_material_ids()).
$valid_material_ids = array( 187, 188, 137, 5, 138, 6, 147, 139 );

foreach ( $attributes as $field_name => $val ) {
	// Expected format: l3_gbp-{material_id}-{option_key} or l3_usd-{material_id}-{option_key}
	if ( ! preg_match( '/^l3_(gbp|usd)-(\d+)-(.+)$/', $field_name, $matches ) ) {
		continue;
	}

	$currency    = $matches[1];               // 'gbp' or 'usd'
	$material_id = intval( $matches[2] );     // numeric material ID
	$option_key  = sanitize_text_field( $matches[3] );  // option key string
	$value       = sanitize_text_field( $val );

	// Reject unknown material IDs.
	if ( ! in_array( $material_id, $valid_material_ids, true ) ) {
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

echo json_encode( array( 'status' => 'ok' ) );
