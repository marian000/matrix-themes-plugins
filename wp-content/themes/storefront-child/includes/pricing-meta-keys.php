<?php
/**
 * Pricing Meta Keys - Single source of truth for all pricing-related user meta keys.
 *
 * This file defines the canonical list of pricing meta keys used throughout
 * the Matrix UK platform. All pricing copy/save operations should reference
 * these functions instead of hardcoding key lists.
 *
 * @since 1.0.0
 * @package Storefront_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the canonical list of base pricing meta keys.
 *
 * These keys represent the business settings, materials, surcharges,
 * and options that each dealer/employee has pricing for.
 *
 * @since 1.0.0
 * @return array Indexed array of meta key strings.
 */
function matrix_get_price_meta_keys() {
	return array(
		// Business settings
		'vat_number_custom',
		'email_contabil',
		'discount_custom',
		'train_price',
		'discount_components',
		// Materials (base GBP price per sqm)
		'BattenStandard',
		'BattenCustom',
		'Earth',
		'Ecowood',
		'EcowoodPlus',
		'Green',
		'Biowood',
		'BiowoodPlus',
		'Basswood',
		'BasswoodPlus',
		// Style surcharges
		'Solid',
		'Shaped',
		'Tracked',
		'TrackedByPass',
		'Arched',
		'Inside',
		'Buildout',
		// Option surcharges
		'Stainless_Steel',
		'Hidden',
		'Concealed_Rod',
		'Bay_Angle',
		'Colors',
		'Ringpull',
		'Spare_Louvres',
		'T_Buildout',
		'B_Buildout',
		'C_Buildout',
		'B_typeFlexible',
		'blackoutblind',
		'T_typeAdjustable',
		'tposttype_blackout',
		'bposttype_blackout',
		// Product-specific
		'Lock',
		'P4028X',
		'P4008T',
		'P4008W',
		'4008T',
		'Combi',
		'French_Door',
		'G_post',
		'Flat_Louver',
	);
}

/**
 * Returns the list of tax meta keys (material_tax variants).
 *
 * @since 1.0.0
 * @return array Indexed array of tax meta key strings.
 */
function matrix_get_tax_meta_keys() {
	return array(
		'BattenStandard_tax',
		'BattenCustom_tax',
		'Earth_tax',
		'Ecowood_tax',
		'EcowoodPlus_tax',
		'Green_tax',
		'Biowood_tax',
		'BiowoodPlus_tax',
		'Basswood_tax',
		'BasswoodPlus_tax',
		'SeaDelivery',
	);
}

/**
 * Returns the list of dolar (USD) price meta keys.
 *
 * Not all base keys have dolar variants. Business settings and some
 * option surcharges (T_typeAdjustable, tposttype_blackout,
 * bposttype_blackout, 4008T) do not have USD equivalents.
 *
 * @since 1.0.0
 * @return array Indexed array of dolar meta key strings.
 */
function matrix_get_dolar_meta_keys() {
	$dolar_keys = array();
	$base_keys  = matrix_get_price_meta_keys();
	// These keys do not have dolar variants in the existing codebase
	$exclude = array(
		'vat_number_custom',
		'email_contabil',
		'discount_custom',
		'train_price',
		'discount_components',
		'T_typeAdjustable',
		'tposttype_blackout',
		'bposttype_blackout',
		'4008T',
	);
	foreach ( $base_keys as $key ) {
		if ( ! in_array( $key, $exclude, true ) ) {
			$dolar_keys[] = $key . '-dolar';
		}
	}
	return $dolar_keys;
}

/**
 * Returns ALL pricing meta keys (base + tax + dolar).
 *
 * @since 1.0.0
 * @return array Merged array of all pricing meta keys.
 */
function matrix_get_all_pricing_meta_keys() {
	return array_merge(
		matrix_get_price_meta_keys(),
		matrix_get_tax_meta_keys(),
		matrix_get_dolar_meta_keys()
	);
}

/**
 * Returns mapping from meta key to $_POST field name for price fields.
 *
 * The POST form uses different prefix patterns:
 * - Base prices: 'price-' prefix (e.g., 'price-Earth')
 * - Tax keys: 'price-' prefix with special mappings
 * - Dolar keys: 'price-dolar-' prefix (e.g., 'price-dolar-Earth')
 * - Business settings: direct meta key (no prefix)
 *
 * @since 1.0.0
 * @return array Associative array: meta_key => post_field_name.
 */
function matrix_get_price_post_field_map() {
	$map = array();

	// Business settings use their meta key directly as POST key
	$business_keys = array( 'vat_number_custom', 'email_contabil', 'discount_custom', 'train_price', 'discount_components' );

	// Base price fields
	foreach ( matrix_get_price_meta_keys() as $key ) {
		if ( in_array( $key, $business_keys, true ) ) {
			$map[ $key ] = $key;
		} else {
			$map[ $key ] = 'price-' . $key;
		}
	}

	// Tax fields: special mapping (BattenStandard_tax -> price-Batten_tax)
	$tax_post_map = array(
		'BattenStandard_tax' => 'price-Batten_tax',
		'BattenCustom_tax'   => 'price-BattenCustom_tax',
		'Earth_tax'          => 'price-Earth_tax',
		'Ecowood_tax'        => 'price-Ecowood_tax',
		'EcowoodPlus_tax'    => 'price-EcowoodPlus_tax',
		'Green_tax'          => 'price-Green_tax',
		'Biowood_tax'        => 'price-Biowood_tax',
		'BiowoodPlus_tax'    => 'price-BiowoodPlus_tax',
		'Basswood_tax'       => 'price-Basswood_tax',
		'BasswoodPlus_tax'   => 'price-BasswoodPlus_tax',
		'SeaDelivery'        => 'price-SeaDelivery',
	);
	$map = array_merge( $map, $tax_post_map );

	// Dolar fields use 'price-dolar-' prefix
	foreach ( matrix_get_dolar_meta_keys() as $dolar_key ) {
		// Strip '-dolar' suffix to get the base key for the POST field
		$base_key          = str_replace( '-dolar', '', $dolar_key );
		$map[ $dolar_key ] = 'price-dolar-' . $base_key;
	}

	return $map;
}

/**
 * Copies all pricing meta from one user to another.
 *
 * Reads all source meta in a single DB call for performance.
 * Copies base GBP, tax, and dolar pricing keys.
 *
 * @since 1.0.0
 * @param int $source_user_id The user to copy pricing FROM.
 * @param int $target_user_id The user to copy pricing TO.
 */
function matrix_copy_pricing_meta( $source_user_id, $target_user_id ) {
	$all_source_meta = get_user_meta( $source_user_id );
	$all_keys        = matrix_get_all_pricing_meta_keys();

	foreach ( $all_keys as $key ) {
		$value = isset( $all_source_meta[ $key ][0] ) ? $all_source_meta[ $key ][0] : '';
		update_user_meta( $target_user_id, $key, $value );
	}

	// Copy per-material pricing overrides (e.g., mat_137_Solid, mat_187_Tracked-dolar)
	matrix_copy_per_material_pricing( $source_user_id, $target_user_id );
}

/**
 * Saves all pricing fields from $_POST to user meta with proper sanitization.
 *
 * Reads the POST field map and saves each field that exists in $_POST.
 * Uses sanitize_email() for email_contabil, sanitize_text_field() for all others.
 *
 * @since 1.0.0
 * @param int $user_id The user to save pricing for.
 */
function matrix_save_pricing_from_post( $user_id ) {
	$field_map = matrix_get_price_post_field_map();

	foreach ( $field_map as $meta_key => $post_key ) {
		if ( isset( $_POST[ $post_key ] ) ) {
			if ( $meta_key === 'email_contabil' ) {
				$value = sanitize_email( wp_unslash( $_POST[ $post_key ] ) );
			} else {
				$value = sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) );
			}
			update_user_meta( $user_id, $meta_key, $value );
		}
	}
}
