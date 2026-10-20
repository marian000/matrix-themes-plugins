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
 * Returns the per-material option keys that do NOT have a USD (-dolar) variant.
 *
 * Single source for the L1 (user) and L3 (global) per-material UIs and save handlers.
 * Keep in sync with the exclusions in matrix_get_dolar_meta_keys().
 *
 * @since 1.1.0
 * @return array Indexed array of option key strings.
 */
function matrix_get_per_material_usd_excludes() {
	return array(
		'T_typeAdjustable',
	);
}

/**
 * Returns the human-readable labels for the per-material option keys.
 *
 * Used by the L1 (user profile) and L3 (shutter-module settings) per-material tables.
 *
 * @since 1.1.0
 * @return array Associative array: option_key => label.
 */
function matrix_get_per_material_option_labels() {
	return array(
		'Solid'              => 'Solid %',
		'Shaped'             => 'Shaped %',
		'Arched'             => 'Arched %',
		'Combi'              => 'Combi %',
		'French_Door'        => 'French Door +',
		'Tracked'            => 'Tracked +',
		'TrackedByPass'      => 'TrackedByPass +',
		'Inside'             => 'Inside Fit %',
		'Buildout'           => 'Buildout %',
		'Bay_Angle'          => 'Bay Angle %',
		'Colors'             => 'Colors %',
		'Stainless_Steel'    => 'Stainless Steel %',
		'Hidden'             => 'Hidden Hinge %',
		'Concealed_Rod'      => 'Concealed Rod %',
		'P4028X'             => 'P4028X %',
		'P4008T'             => 'P4008T %',
		'P4008W'             => 'P4008W %',
		'4008T'              => '4008T %',
		'T_Buildout'         => 'T_Buildout %',
		'B_Buildout'         => 'B_Buildout %',
		'C_Buildout'         => 'C_Buildout %',
		'G_post'             => 'G_post %',
		'B_typeFlexible'     => 'B_typeFlexible %',
		'T_typeAdjustable'   => 'T_typeAdjustable %',
		'Flat_Louver'        => 'Flat Louver %',
		'Lock'               => 'Lock +',
		'Ringpull'           => 'Ringpull +',
		'Spare_Louvres'      => 'Spare Louvres +',
		'blackoutblind'      => 'Blackout Blind +',
		'tposttype_blackout' => 'T-Post Blackout %',
		'bposttype_blackout' => 'B-Post Blackout %',
	);
}

/**
 * Normalises a submitted price value before it is saved.
 *
 * Trims the value and accepts a comma as decimal separator. An empty value is
 * allowed (it means "no override"). A numeric value of 0 or more is rounded to
 * two decimals. Anything else (text, negative numbers, arrays) is rejected.
 *
 * @since 1.1.0
 * @param mixed $raw The raw submitted value (already unslashed).
 * @return string|null Normalised value, '' for empty, or null when the value is rejected
 *                     and the stored meta must be left unchanged.
 */
function matrix_normalize_price_input( $raw ) {
	if ( ! is_scalar( $raw ) ) {
		return null;
	}

	$value = str_replace( ',', '.', trim( (string) $raw ) );

	if ( '' === $value ) {
		return '';
	}

	if ( ! is_numeric( $value ) || (float) $value < 0 ) {
		return null;
	}

	// + 0 turns a negative zero ('-0') into 0.
	return (string) ( round( (float) $value, 2 ) + 0 );
}

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
 * Total: 8 materials x (31 GBP + 30 USD) = 488 keys.
 *
 * @since 1.0.0
 * @return array Flat indexed array of all per-material meta key strings.
 */
function matrix_get_all_per_material_meta_keys() {
	$usd_excludes = matrix_get_per_material_usd_excludes();
	$materials    = matrix_get_material_ids();
	$option_keys  = matrix_get_per_material_option_keys();
	$all_keys     = array();

	foreach ( array_keys( $materials ) as $material_id ) {
		foreach ( $option_keys as $option_key ) {
			// GBP key -- always included
			$all_keys[] = matrix_get_per_material_meta_key( $material_id, $option_key, 'gbp' );

			// USD key -- excluded for certain option keys
			if ( ! in_array( $option_key, $usd_excludes, true ) ) {
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
 * Values are passed through matrix_normalize_price_input(); a rejected value
 * (non-numeric or negative) leaves the stored meta unchanged.
 *
 * Does NOT save USD variants for option keys in the exclude list.
 *
 * @since 1.0.0
 * @param int $user_id The WordPress user ID to save pricing for.
 * @return void
 */
function matrix_save_per_material_pricing( $user_id ) {
	$user_id = absint( $user_id );
	if ( ! $user_id || ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	$usd_excludes = matrix_get_per_material_usd_excludes();
	$materials    = matrix_get_material_ids();
	$option_keys  = matrix_get_per_material_option_keys();

	foreach ( array_keys( $materials ) as $material_id ) {
		foreach ( $option_keys as $option_key ) {
			// GBP field
			$post_key_gbp = 'mat_price-' . $material_id . '-' . $option_key;
			if ( isset( $_POST[ $post_key_gbp ] ) ) {
				$value = matrix_normalize_price_input( wp_unslash( $_POST[ $post_key_gbp ] ) );
				if ( null !== $value ) {
					$meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'gbp' );
					update_user_meta( $user_id, $meta_key, $value );
				}
			}

			// USD field -- skip for keys without USD variant
			if ( in_array( $option_key, $usd_excludes, true ) ) {
				continue;
			}

			$post_key_usd = 'mat_price_dolar-' . $material_id . '-' . $option_key;
			if ( isset( $_POST[ $post_key_usd ] ) ) {
				$value = matrix_normalize_price_input( wp_unslash( $_POST[ $post_key_usd ] ) );
				if ( null !== $value ) {
					$meta_key = matrix_get_per_material_meta_key( $material_id, $option_key, 'usd' );
					update_user_meta( $user_id, $meta_key, $value );
				}
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
