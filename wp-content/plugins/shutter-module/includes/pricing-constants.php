<?php
/**
 * Shared pricing constants for the Shutter Module.
 *
 * Centralises magic numbers (style IDs, color IDs, control type IDs,
 * frame type IDs, surcharge rates, thresholds) that were previously
 * hardcoded across ajax-prod.php, ajax-prod-update.php, and their
 * individual variants.
 *
 * IMPORTANT: Changing any value here affects ALL pricing calculations.
 * Do not modify without verifying against the live database taxonomy.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Style Classification Arrays
|--------------------------------------------------------------------------
|
| Groups of style term IDs used in pricing surcharge checks.
| Each array corresponds to a style family that triggers a specific
| pricing add-on (Solid, Shaped, Tracked, etc.).
|
*/

/** Solid panel styles (triggers 'Solid' surcharge). */
$GLOBALS['shutter_solid_styles'] = array( 221, 227, 226, 222, 228, 230, 231, 232, 38, 39, 42, 43 );

/** Shaped/special-shape styles (triggers 'Shaped' surcharge). */
$GLOBALS['shutter_shaped_styles'] = array( 33, 43 );

/** Tracked styles (triggers 'Tracked' per-metre surcharge). */
$GLOBALS['shutter_tracked_styles'] = array( 35, 39, 41 );

/** Tracked bypass styles (triggers 'TrackedByPass' per-metre surcharge). */
$GLOBALS['shutter_tracked_bypass_styles'] = array( 37, 38, 40 );

/** Arched styles (triggers 'Arched' surcharge). */
$GLOBALS['shutter_arched_styles'] = array( 36, 42 );

/** Combi styles (triggers 'Combi' surcharge). */
$GLOBALS['shutter_combi_styles'] = array( 229, 233, 40, 41 );

/**
 * Ringpull-eligible styles (solid panel styles without tracked/combi variants).
 * Only these styles may add ring pull pricing.
 */
$GLOBALS['shutter_ringpull_styles'] = array( 221, 227, 226, 222, 228, 230, 231, 232 );

/** French Door style ID (triggers flat 'French_Door' surcharge). */
$GLOBALS['shutter_style_french_door'] = 34;

/*
|--------------------------------------------------------------------------
| GBP Color Classification Arrays
|--------------------------------------------------------------------------
|
| Color term IDs that trigger percentage-based surcharges in GBP pricing.
|
*/

/**
 * Colors that trigger a surcharge via the 'Colors' user/global meta value.
 * Historically called "Colors 20%" in code comments.
 */
$GLOBALS['shutter_colors_gbp_surcharge'] = array(
	264, 265, 266, 267, 268, 269, 270, 271, 272, 273,
	128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
	132, 255, 134, 122, 123, 133, 256, 166, 124, 125, 111,
);

/**
 * Colors that trigger a fixed 10% surcharge on basic price.
 * Historically called "Colors 10%" in code comments.
 */
$GLOBALS['shutter_colors_gbp_10pct'] = array( 262, 263, 274 );

/** Fixed surcharge percentage for the 10% color group (GBP). */
$GLOBALS['shutter_colors_gbp_10pct_rate'] = 10;

/*
|--------------------------------------------------------------------------
| USD Color Surcharge Arrays
|--------------------------------------------------------------------------
|
| Material-specific color surcharges applied in the USD pricing section.
| Each entry defines the applicable color IDs and the fixed percentage rate.
|
*/

/**
 * Stained Basswood/BasswoodPlus color surcharge.
 * Applied when material is BasswoodPlus (139) or Basswood (147).
 */
$GLOBALS['shutter_usd_colors_basswood'] = array(
	'ids'  => array(
		128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
		132, 255, 134, 122, 123, 133, 256, 166, 124, 125,
	),
	'rate' => 14.57,
);

/**
 * Brushed BiowoodPlus color surcharge.
 * Applied when material is BiowoodPlus (138).
 */
$GLOBALS['shutter_usd_colors_biowoodplus'] = array(
	'ids'  => array(
		264, 265, 266, 267, 268, 269, 270, 271, 272, 273,
		128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
		132, 255, 134, 122, 123, 133, 256, 166, 124, 125, 111,
	),
	'rate' => 35.64,
);

/**
 * Painted Earth color surcharge.
 * Applied when material is Earth (187).
 */
$GLOBALS['shutter_usd_colors_earth'] = array(
	'ids'  => array( 258, 259, 260, 261, 262, 263 ),
	'rate' => 6.87,
);

/*
|--------------------------------------------------------------------------
| Material IDs (for pricing logic branching)
|--------------------------------------------------------------------------
*/

/** Biowood material ID. */
$GLOBALS['shutter_material_biowood'] = 6;

/** BiowoodPlus material ID. */
$GLOBALS['shutter_material_biowoodplus'] = 138;

/** BasswoodPlus material ID. */
$GLOBALS['shutter_material_basswoodplus'] = 139;

/** Basswood material ID. */
$GLOBALS['shutter_material_basswood'] = 147;

/** Earth material ID. */
$GLOBALS['shutter_material_earth'] = 187;

/** Biowood lock materials: Biowood (6) and BiowoodPlus (138). Hardcoded 58 GBP lock price. */
$GLOBALS['shutter_biowood_lock_materials'] = array( 6, 138 );

/** Hardcoded lock price for biowood materials (GBP). */
$GLOBALS['shutter_biowood_lock_price'] = 58;

/*
|--------------------------------------------------------------------------
| Control Type IDs
|--------------------------------------------------------------------------
*/

/** Concealed Rod control type ID. */
$GLOBALS['shutter_control_concealed_rod'] = 403;

/** Hidden Rod with Locking System control type ID. */
$GLOBALS['shutter_control_hidden_rod'] = 387;

/*
|--------------------------------------------------------------------------
| Hinge Colour IDs
|--------------------------------------------------------------------------
*/

/** Stainless Steel hinge colour ID. */
$GLOBALS['shutter_hinge_stainless_steel'] = 93;

/** Hidden hinge colour ID. */
$GLOBALS['shutter_hinge_hidden'] = 186;

/*
|--------------------------------------------------------------------------
| Measurement / Fit IDs
|--------------------------------------------------------------------------
*/

/** Inside measurement fit ID. */
$GLOBALS['shutter_fit_inside'] = 56;

/*
|--------------------------------------------------------------------------
| Blade Size IDs
|--------------------------------------------------------------------------
*/

/** Flat Louver blade size ID. */
$GLOBALS['shutter_blade_flat_louver'] = 52;

/*
|--------------------------------------------------------------------------
| Frame Type IDs (for pricing logic branching)
|--------------------------------------------------------------------------
*/

/** Blackout Blind frame type ID. */
$GLOBALS['shutter_frametype_blackout_blind'] = 171;

/** P4008T frame type ID. */
$GLOBALS['shutter_frametype_p4008t'] = 322;

/** 4008T frame type ID (variant). */
$GLOBALS['shutter_frametype_4008t'] = 353;

/** P4008W frame type ID. */
$GLOBALS['shutter_frametype_p4008w'] = 319;

/*
|--------------------------------------------------------------------------
| Buildout Thresholds & Surcharges
|--------------------------------------------------------------------------
|
| Buildout rules:
|   - If buildout > 0 AND (frame_depth + buildout) <= 100mm => standard buildout surcharge (from user/global meta)
|   - If (frame_depth + buildout) > 100mm => fixed 20% surcharge on basic price
|
*/

/** Maximum depth in mm before the deep buildout surcharge kicks in. */
$GLOBALS['shutter_buildout_depth_threshold'] = 100;

/** Deep buildout surcharge percentage (applied when frame_depth + buildout > threshold). */
$GLOBALS['shutter_buildout_deep_pct'] = 20;

/** G-post fallback percentage when no user meta 'G_post' is set. */
$GLOBALS['shutter_g_post_fallback_pct'] = 3;

/** Tracked fallback rate (GBP per metre) when no user meta 'Tracked' is set. */
$GLOBALS['shutter_tracked_fallback_rate'] = 71;

/*
|--------------------------------------------------------------------------
| SQM Minimum
|--------------------------------------------------------------------------
*/

/** Minimum SQM value used for pricing (items below this are billed at minimum). */
$GLOBALS['shutter_sqm_minimum'] = 0.5;

/*
|--------------------------------------------------------------------------
| Panel & Colour Thresholds
|--------------------------------------------------------------------------
*/

/** Minimum panel width in mm; panels narrower than this trigger a surcharge. */
$GLOBALS['shutter_panel_width_minimum'] = 200;

/** Blackout blind default colour term ID (no colour surcharge applied). */
$GLOBALS['shutter_blackout_default_colour'] = 390;
