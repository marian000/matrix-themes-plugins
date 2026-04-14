<?php
/**
 * PricingResult: data transfer object for pricing calculation output.
 *
 * Holds all intermediate and final values produced by PricingCalculator.
 * No business logic -- this is a pure data container.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PricingResult
 *
 * Immutable-ish data object returned by PricingCalculator::calculate().
 * All properties are public for direct read access from the AJAX handlers.
 *
 * @since 1.0.0
 */
class PricingResult {

	/**
	 * Final GBP price (total after all phases).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $gbp_price = 0.0;

	/**
	 * Final USD price (total after all USD phases).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $usd_price = 0.0;

	/**
	 * Effective SQM value used for pricing (after minimum enforcement).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $sqm = 0.0;

	/**
	 * GBP basic price (base material price, updated after custom discount).
	 *
	 * Used as the multiplier base for percentage surcharges.
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $basic = 0.0;

	/**
	 * Earth material basic price (set only for Earth material, after ALU discount).
	 *
	 * Stored as 'basic_earth_price' post meta. Zero for non-Earth materials.
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $basic_earth_price = 0.0;

	/**
	 * Per-section GBP prices (indexed by section number, 1-based).
	 *
	 * @since 1.0.0
	 * @var array<int, float>
	 */
	public $sections_price = array();

	/**
	 * Per-section USD prices (indexed by section number, 1-based).
	 *
	 * @since 1.0.0
	 * @var array<int, float>
	 */
	public $dolar_sections_price = array();

	/**
	 * Counter for non-Earth 'other color' items in cart/order context.
	 *
	 * Used by the AJAX handler for addOtherColorEarth logic.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $other_color_earth = 0;

	/**
	 * SQM per blackout blind section (only for frametype 171).
	 *
	 * Indexed array of SQM values per panel section.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $sqm_parts = array();

	/**
	 * Total SQM for blackout blind sections (sum with per-section minimum of 1).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $sqm_value_blackout = 0.0;

	/**
	 * The GBP material rate per SQM (for snapshot saving).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $material_rate = 0.0;

	/**
	 * The material meta key name (e.g. 'Earth', 'Basswood').
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $material_key = '';

	/**
	 * Whether ALU discount was applied (Earth-only flag).
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $has_alu_discount = false;

	/**
	 * Individual section counters (t, g, b, c per section).
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $individual_counter = array();

	/**
	 * The width_track value used in tracked calculations.
	 *
	 * @since 1.0.0
	 * @var float
	 */
	public $width_track = 0.0;

	/**
	 * Whether buildout exceeded the deep threshold (for post meta flag).
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $has_deep_buildout = false;

	/**
	 * Number of motors (read from product post meta during motorized phase).
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $nr_motors = 0;

	/**
	 * Number of remotes (read from product post meta during motorized phase).
	 *
	 * @since 1.0.0
	 * @var int
	 */
	public $nr_remotes = 0;

	/**
	 * Debug log entries for tracing calculation steps.
	 *
	 * Each entry is an associative array with 'phase', 'label', 'value' keys.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public $debug_log = array();

	/**
	 * Add a debug log entry.
	 *
	 * @since 1.0.0
	 * @param string $phase The calculation phase name.
	 * @param string $label A human-readable description of the step.
	 * @param mixed  $value The value at this step (typically $sum).
	 * @return void
	 */
	public function log( $phase, $label, $value = null ) {
		$this->debug_log[] = array(
			'phase' => $phase,
			'label' => $label,
			'value' => $value,
		);
	}
}
