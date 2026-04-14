<?php
/**
 * Shared input sanitization for pricing AJAX handlers.
 *
 * Centralises the sanitization of the parsed $products array so that
 * all four pricing AJAX files (ajax-prod, ajax-prod-update, and their
 * individual variants) share identical sanitisation logic.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize the parsed products array from the configurator form.
 *
 * Applies intval/floatval/sanitize_text_field to all known fields.
 * Modifies the array in place and returns it.
 *
 * @since 1.0.0
 * @param array $products The parsed form data (from parse_str of $_POST['prod']).
 * @return array The sanitized products array.
 */
function horeka_sanitize_pricing_input( array $products ) {
	// ---- Numeric inputs ----
	$int_fields = array(
		'customer_id', 'product_id', 'product_id_updated',
		'order_edit', 'edit_customer',
		'property_width', 'property_height',
		'property_material', 'property_style', 'property_frametype',
		'property_bladesize', 'property_builtout', 'property_fit',
		'property_hingecolour', 'property_controltype',
		'property_shuttercolour', 'property_tracksnumber',
		'property_ringpull_volume', 'property_nr_sections',
		'panels_left_right', 'order_item_id',
	);
	foreach ( $int_fields as $field ) {
		$products[ $field ] = intval( $products[ $field ] ?? 0 );
	}

	// Float fields.
	$products['property_total'] = floatval( $products['property_total'] ?? 0 );

	// Quantity with minimum of 1.
	$products['quantity'] = max( 1, intval( $products['quantity'] ?? 1 ) );

	// ---- String fields ----
	$string_fields = array(
		'page_title', 'property_room_other',
		'property_layoutcode', 'property_shuttercolour_other',
	);
	foreach ( $string_fields as $field ) {
		$products[ $field ] = sanitize_text_field( $products[ $field ] ?? '' );
	}

	// ---- Yes/No fields ----
	$yn_fields = array(
		'property_lightblocks', 'property_locks', 'property_central_lock',
		'property_ringpull', 'property_sparelouvres', 'property_motorized',
		'property_horizontaltpost',
	);
	foreach ( $yn_fields as $field ) {
		$products[ $field ] = sanitize_text_field( $products[ $field ] ?? '' );
	}

	// ---- Per-section numeric fields (individual flow) ----
	for ( $i = 1; $i <= 15; $i++ ) {
		$products[ 'property_t' . $i ]  = intval( $products[ 'property_t' . $i ] ?? 0 );
		$products[ 'property_bp' . $i ] = intval( $products[ 'property_bp' . $i ] ?? 0 );
	}
	if ( ! empty( $products['property_nr_sections'] ) ) {
		for ( $i = 1; $i <= $products['property_nr_sections']; $i++ ) {
			$products[ 'property_total_section' . $i ]  = floatval( $products[ 'property_total_section' . $i ] ?? 0 );
			$products[ 'property_layoutcode' . $i ]     = sanitize_text_field( $products[ 'property_layoutcode' . $i ] ?? '' );
		}
	}

	return $products;
}
