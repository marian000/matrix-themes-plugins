<?php
/**
 * Material Pricing Keys - Single source of truth for per-material surcharge overrides.
 *
 * This file defines the canonical list of per-material pricing meta keys.
 * Each material (Earth, Ecowood, Green, etc.) can have its own override
 * for surcharge rates (Solid, Shaped, Tracked, etc.) stored in user meta
 * with the pattern: mat_{material_id}_{option_key}
 *
 * Pure data layer -- no hooks, no side effects on include.
 *
 * @since 1.0.0
 * @package Storefront_Child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Option keys that do NOT have a USD (-dolar) variant.
 *
 * Defined once to avoid duplication across functions.
 * Mirrors the same exclusions used in matrix_get_dolar_meta_keys().
 *
 * @since 1.0.0
 * @var array
 */
$_matrix_per_material_usd_excludes = array(
	'4008T',
	'T_typeAdjustable',
	'tposttype_blackout',
	'bposttype_blackout',
);

/**
 * Returns the supported material IDs and their human-readable names.
 *
 * Mirrors PricingConfig::MATERIALS from the shutter-module plugin.
 * These are the 8 materials that support per-material surcharge overrides.
 *
 * @since 1.0.0
 * @return array Associative array: material_id => material_name.
 */
function matrix_get_material_ids() {
	return array(
		187 => 'Earth',
		188 => 'Ecowood',
		137 => 'Green',
		5   => 'EcowoodPlus',
		138 => 'BiowoodPlus',
		6   => 'Biowood',
		147 => 'Basswood',
		139 => 'BasswoodPlus',
	);
}

/**
 * Returns the canonical list of surcharge option keys that can be overridden per material.
 *
 * These 31 keys represent style, option, post, and frame surcharges.
 * They do NOT include material base rates (Earth, Ecowood, etc.),
 * business settings (discount_custom, vat_number_custom, etc.),
 * or keys not exposed in the current UI (Light_block, motor, etc.).
 *
 * The order matters for UI rendering.
 *
 * @since 1.0.0
 * @return array Indexed array of 31 surcharge option key strings.
 */
function matrix_get_per_material_option_keys() {
	return array(
		// Style surcharges
		'Solid',
		'Shaped',
		'Arched',
		'Combi',
		'French_Door',
		'Tracked',
		'TrackedByPass',
		// Option surcharges
		'Inside',
		'Buildout',
		'Bay_Angle',
		'Colors',
		'Stainless_Steel',
		'Hidden',
		'Concealed_Rod',
		// Frame surcharges
		'P4028X',
		'P4008T',
		'P4008W',
		'4008T',
		// Post surcharges
		'T_Buildout',
		'B_Buildout',
		'C_Buildout',
		'G_post',
		'B_typeFlexible',
		'T_typeAdjustable',
		// Misc surcharges
		'Flat_Louver',
		'Lock',
		'Ringpull',
		'Spare_Louvres',
		// Blackout surcharges
		'blackoutblind',
		'tposttype_blackout',
		'bposttype_blackout',
	);
}

/**
 * Generates the user meta key for a specific material + option combination.
 *
 * Pattern: mat_{material_id}_{option_key} for GBP,
 *          mat_{material_id}_{option_key}-dolar for USD.
 *
 * Examples:
 *   matrix_get_per_material_meta_key( 137, 'Solid' )          => 'mat_137_Solid'
 *   matrix_get_per_material_meta_key( 137, 'Solid', 'usd' )   => 'mat_137_Solid-dolar'
 *
 * @since 1.0.0
 * @param int    $material_id The material term ID (e.g., 137, 187).
 * @param string $option_key  The surcharge option key (e.g., 'Solid', 'Tracked').
 * @param string $variant     Currency variant: 'gbp' (default) or 'usd'.
 * @return string The generated user meta key.
 */
function matrix_get_per_material_meta_key( $material_id, $option_key, $variant = 'gbp' ) {
	$key = 'mat_' . intval( $material_id ) . '_' . $option_key;
	if ( $variant === 'usd' ) {
		$key .= '-dolar';
	}
	return $key;
}

/**
 * Returns ALL per-material meta keys for copy/delete operations.
 *
 * Iterates 8 materials x 31 option keys x 2 variants (GBP + USD),
 * excluding USD for keys that do not have a USD variant.
 *
 * Total: 8 materials x (31 GBP + 27 USD) = 464 keys.
 *
 * @since 1.0.0
 * @return array Flat indexed array of all per-material meta key strings.
 */
function matrix_get_all_per_material_meta_keys() {
	global $_matrix_per_material_usd_excludes;

	$materials   = matrix_get_material_ids();
	$option_keys = matrix_get_per_material_option_keys();
	$all_keys    = array();

	foreach ( array_keys( $materials ) as $material_id ) {
		foreach ( $option_keys as $option_key ) {
			// GBP key -- always included
			$all_keys[] = matrix_get_per_material_meta_key( $material_id, $option_key, 'gbp' );

			// USD key -- excluded for certain option keys
			if ( ! in_array( $option_key, $_matrix_per_material_usd_excludes, true ) ) {
				$all_keys[] = matrix_get_per_material_meta_key( $material_id, $option_key, 'usd' );
			}
		}
	}

	return $all_keys;
}

/**
 * Saves per-material pricing fields from $_POST to user meta.
 *
 * Reads POST fields with naming patterns:
 *   GBP: mat_price-{material_id}-{option_key}     => meta: mat_{material_id}_{option_key}
 *   USD: mat_price_dolar-{material_id}-{option_key} => meta: mat_{material_id}_{option_key}-dolar
 *
 * Only updates meta when the POST field is present (isset). Absent fields
 * are left untouched so partial saves do not clear existing overrides.
 *
 * Does NOT save USD variants for option keys in the exclude list.
 *
 * @since 1.0.0
 * @param int $user_id The WordPress user ID to save pricing for.
 * @return void
 */
function matrix_save_per_material_pricing( $user_id ) {
	global $_matrix_per_material_usd_excludes;

	$user_id = absint( $user_id );
	if ( ! $user_id || ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	$materials   = matrix_get_material_ids();
	$option_keys = matrix_get_per_material_option_keys();

	foreach ( array_keys( $materials ) as $material_id ) {
		foreach ( $option_keys as $option_key ) {
			// GBP field
			$post_key_gbp = 'mat_price-' . $material_id . '-' . $option_key;
			if ( isset( $_POST[ $post_key_gbp ] ) ) {
				$value    = sanitize_text_field( wp_unslash( $_POST[ $post_key_gbp ] ) );
				$meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'gbp' );
				update_user_meta( $user_id, $meta_key, $value );
			}

			// USD field -- skip for keys without USD variant
			if ( in_array( $option_key, $_matrix_per_material_usd_excludes, true ) ) {
				continue;
			}

			$post_key_usd = 'mat_price_dolar-' . $material_id . '-' . $option_key;
			if ( isset( $_POST[ $post_key_usd ] ) ) {
				$value    = sanitize_text_field( wp_unslash( $_POST[ $post_key_usd ] ) );
				$meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'usd' );
				update_user_meta( $user_id, $meta_key, $value );
			}
		}
	}
}

/**
 * Copies all per-material pricing meta from one user to another.
 *
 * Reads all source user meta in a single DB call for performance,
 * then iterates the canonical key list and writes each value to the target.
 *
 * Used for dealer-to-employee pricing sync.
 *
 * @since 1.0.0
 * @param int $source_user_id The user to copy per-material pricing FROM.
 * @param int $target_user_id The user to copy per-material pricing TO.
 * @return void
 */
function matrix_copy_per_material_pricing( $source_user_id, $target_user_id ) {
	$source_user_id = absint( $source_user_id );
	$target_user_id = absint( $target_user_id );

	if ( ! $source_user_id || ! $target_user_id ) {
		return;
	}

	$all_source_meta = get_user_meta( $source_user_id );
	$all_keys        = matrix_get_all_per_material_meta_keys();

	foreach ( $all_keys as $key ) {
		$value = isset( $all_source_meta[ $key ][0] ) ? $all_source_meta[ $key ][0] : '';
		update_user_meta( $target_user_id, $key, $value );
	}
}
