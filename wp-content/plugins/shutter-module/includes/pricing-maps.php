<?php
/**
 * Shared pricing configuration maps for the Shutter Module.
 *
 * Centralises data arrays that were previously duplicated across
 * ajax-prod.php, ajax-prod-update.php, ajax-prod-individual.php,
 * and ajax-prod-update-individual.php.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the frame type depth map.
 *
 * Maps frame type term IDs to their depth in millimetres.
 * Used for buildout calculation: total = frame_depth + buildout.
 *
 * Buildout rules:
 *  - If buildout > 0 add 10%
 *  - If buildout + frame_depth > 100 add 20% instead
 *
 * @since 1.0.0
 * @return array<int, int> Frame type ID => depth in mm.
 */
function horeka_get_frames_type_map() {
	return array(
		289 => 60,
		303 => 36,
		323 => 46,
		321 => 51,
		330 => 72,
		318 => 60,
		322 => 87,
		352 => 60,
		331 => 51,
		320 => 46,
		325 => 46,
		326 => 46,
		327 => 46,
		328 => 46,
		324 => 46,
		304 => 46,
		329 => 49,
		307 => 46,
		310 => 64,
		313 => 77,
		333 => 60,
		306 => 51,
		309 => 64,
		312 => 77,
		305 => 46,
		308 => 64,
		311 => 77,
		332 => 60,
		142 => 36,
		314 => 46,
		315 => 46,
		316 => 60,
		317 => 60,
		300 => 50,
		302 => 50,
		301 => 50,
		351 => 60,
		319 => 100,
		420 => 76,
	);
}

/**
 * Get the material term ID to user meta key map.
 *
 * Maps WooCommerce/WordPress term IDs for materials to the
 * corresponding user meta key names used in pricing lookups
 * (e.g., get_user_meta( $user_id, 'Earth', true )).
 *
 * Each material also has _tax and -dolar variants in user meta
 * (e.g., 'Earth_tax', 'Earth-dolar').
 *
 * @since 1.0.0
 * @return array<int, string> Material term ID => meta key name.
 */
function horeka_get_materials_map() {
	return array(
		187 => 'Earth',
		137 => 'Green',
		5   => 'EcowoodPlus',
		138 => 'BiowoodPlus',
		6   => 'Biowood',
		139 => 'BasswoodPlus',
		147 => 'Basswood',
		188 => 'Ecowood',
	);
}

/**
 * Get the list of all material meta key names.
 *
 * Used by saveCurrentPriceItem() to save per-material pricing
 * snapshots on product posts. This is the full list (8 materials)
 * used by standard and update files.
 *
 * Note: ajax-prod-individual.php historically uses a 6-material
 * subset (excluding BiowoodPlus and BasswoodPlus). That subset
 * is preserved in that file for backwards compatibility.
 *
 * @since 1.0.0
 * @return array<int, string> Indexed array of material meta key names.
 */
function horeka_get_material_meta_keys() {
	return array(
		'Earth',
		'BiowoodPlus',
		'Biowood',
		'Green',
		'EcowoodPlus',
		'Ecowood',
		'Basswood',
		'BasswoodPlus',
	);
}

/**
 * Get the style discount rates map.
 *
 * Maps style term IDs to their discount percentage. Applied to
 * Earth/material base pricing during ALU panel calculations.
 *
 *  - Style 27 = ALU Panel Only  => 5.45% discount
 *  - Style 28 = ALU Fixed       => 3.45% discount
 *
 * @since 1.0.0
 * @return array<int, float> Style term ID => discount percentage.
 */
function horeka_get_style_discount_rates() {
	return array(
		27 => 5.45,
		28 => 3.45,
	);
}

/**
 * Look up the discount rate for a given style ID.
 *
 * Convenience wrapper around horeka_get_style_discount_rates().
 * Returns 0 if the style has no discount.
 *
 * @since 1.0.0
 * @param int $style_id The property_style term ID.
 * @return float Discount percentage (e.g. 5.45) or 0.
 */
function horeka_get_style_discount( $style_id ) {
	$rates = horeka_get_style_discount_rates();
	return isset( $rates[ $style_id ] ) ? $rates[ $style_id ] : 0;
}
