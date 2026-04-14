<?php
/**
 * PricingCalculator: central pricing engine for the Shutter Module.
 *
 * Implements ALL 13 pricing phases extracted from ajax-prod.php.
 * This is a pure calculation class -- no echo, no print_r, no side effects
 * (no update_post_meta, no cart operations, no WooCommerce calls).
 *
 * All rates are fetched via PricingConfig (no direct get_user_meta / get_post_meta).
 * All debug output goes to PricingResult::$debug_log.
 *
 * Usage:
 *   require_once __DIR__ . '/class-pricing-config.php';
 *   require_once __DIR__ . '/class-pricing-result.php';
 *   require_once __DIR__ . '/class-pricing-calculator.php';
 *
 *   $config     = new PricingConfig( $user_id );
 *   $calculator = new PricingCalculator();
 *   $result     = $calculator->calculate( $products, $config );
 *
 *   // Use $result->gbp_price, $result->usd_price, etc.
 *
 * Dependencies:
 *   - class-pricing-config.php (PricingConfig)
 *   - class-pricing-result.php (PricingResult)
 *   - pricing-helpers.php      (blackoutSqms, nrPanelsCount, calculatePanelsWidth, countT, midrailDividerCounter)
 *   - pricing-maps.php         (horeka_get_frames_type_map, horeka_get_style_discount)
 *   - pricing-constants.php    ($GLOBALS arrays)
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PricingCalculator
 *
 * Pure calculation engine. Input: products array + PricingConfig.
 * Output: PricingResult with all computed values.
 *
 * @since 1.0.0
 */
class PricingCalculator {

	/**
	 * The PricingConfig instance for rate lookups.
	 *
	 * @since 1.0.0
	 * @var PricingConfig
	 */
	private $config;

	/**
	 * The parsed products array (from parse_str of $_POST['prod']).
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $products;

	/**
	 * The PricingResult being built.
	 *
	 * @since 1.0.0
	 * @var PricingResult
	 */
	private $result;

	/**
	 * Frame type depth map (frame type ID => depth in mm).
	 *
	 * @since 1.0.0
	 * @var array<int, int>
	 */
	private $frames_type_map;

	/**
	 * Running GBP sum during calculation.
	 *
	 * @since 1.0.0
	 * @var float
	 */
	private $sum = 0.0;

	/**
	 * GBP basic price (base for percentage surcharges).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	private $basic = 0.0;

	/**
	 * SQM value (after minimum enforcement, before blackout override).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	private $sqm_value = 0.0;

	/**
	 * Blackout blind SQM total (sum of per-section SQMs with min 1).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	private $sqm_value_blackout = 0.0;

	/**
	 * Width track value (property_width, used for tracked calculations).
	 *
	 * @since 1.0.0
	 * @var float
	 */
	private $width_track = 0.0;

	/**
	 * Material definition from PricingConfig::MATERIALS (or null).
	 *
	 * @since 1.0.0
	 * @var array|null
	 */
	private $material_def;

	/**
	 * Material meta key name (e.g. 'Earth').
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $material_key = '';

	/**
	 * Material term ID (from property_material).
	 *
	 * Used to resolve per-material rate overrides via
	 * PricingConfig::getPerMaterialRate().
	 *
	 * @since 1.0.0
	 * @var int
	 */
	private $material_id = 0;

	/*
	|--------------------------------------------------------------------------
	| Main Entry Point
	|--------------------------------------------------------------------------
	*/

	/**
	 * Calculate complete GBP and USD pricing for a product.
	 *
	 * Runs all 13 phases in order and returns a PricingResult.
	 *
	 * @since 1.0.0
	 * @param array         $products The parsed product properties array.
	 * @param PricingConfig $config   The rate resolver instance.
	 * @return PricingResult The complete calculation result.
	 */
	public function calculate( array $products, PricingConfig $config ) {
		$this->config          = $config;
		$this->products        = $products;
		$this->result          = new PricingResult();
		$this->frames_type_map = horeka_get_frames_type_map();
		$this->sum             = 0.0;
		$this->basic           = 0.0;

		// Resolve material definition.
		$material_id        = intval( $this->products['property_material'] );
		$this->material_id  = $material_id;
		$this->material_def = isset( PricingConfig::MATERIALS[ $material_id ] )
			? PricingConfig::MATERIALS[ $material_id ]
			: null;
		$this->material_key = $this->material_def ? $this->material_def['key'] : '';

		// Phase 1: SQM
		$this->calculateSqm();

		// Phase 2: Base Material Price (GBP)
		$this->calculateBasePrice();

		// Phase 3: Custom Discount
		$this->applyCustomDiscount();

		// Phase 4: Style Surcharges
		$this->applyStyleSurcharges();

		// Phase 5: Option Surcharges (light block, inside fit, concealed rod, hidden rod, hinges)
		$this->applyOptionSurcharges();

		// Phase 6: Frame Type Surcharges
		$this->applyFrameTypeSurcharges();

		// Phase 7: Buildout
		$this->applyBuildout();

		// Phase 8: Post Surcharges (bay angle, flexible B-post, B/T/C buildout, G-post, T-adjustable)
		$this->applyPostSurcharges();

		// Phase 9: Color Surcharges (GBP)
		$this->applyColorSurcharges();

		// Phase 10: Accessories (ring pull, locks, central lock, louver lock, spare louvres, sea delivery, panel width)
		$this->applyAccessories();

		// Phase 11: Sections (per-section pricing)
		$this->calculateSections();

		// Phase 12: Motorized
		$this->applyMotorized();

		// Capture final GBP result.
		$this->result->gbp_price   = $this->sum;
		$this->result->basic       = $this->basic;
		$this->result->sqm         = $this->sqm_value;
		$this->result->width_track = $this->width_track;
		$this->result->material_key  = $this->material_key;
		$this->result->material_rate = $this->material_def
			? $this->config->getGbpRate( $this->material_key )
			: 0.0;

		// Phase 13: USD Calculation (complete parallel calculation)
		$this->calculateUsd();

		// Build individual counters for sections.
		$this->buildIndividualCounters();

		return $this->result;
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 1: SQM Calculation
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 1: Calculate SQM with minimum enforcement and blackout special case.
	 *
	 * Standard: round((width/1000) * (height/1000), 2), minimum 0.5
	 * Blackout Blind (frametype 171): per-section SQM via blackoutSqms()
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function calculateSqm() {
		$this->sqm_value = round( floatval( $this->products['property_total'] ), 2 );

		if ( intval( $this->products['property_frametype'] ) == PricingConfig::FRAMETYPE_BLACKOUT_BLIND ) {
			$this->sqm_value_blackout = 0;
			$sqm_parts = blackoutSqms( $this->products );

			foreach ( $sqm_parts as $sqm ) {
				if ( $sqm < 1 ) {
					$this->sqm_value_blackout = $this->sqm_value_blackout + 1;
				} else {
					$this->sqm_value_blackout = $this->sqm_value_blackout + number_format( $sqm, 2 );
				}
			}

			$this->result->sqm_parts           = $sqm_parts;
			$this->result->sqm_value_blackout   = $this->sqm_value_blackout;
			$this->result->log( 'sqm', 'Blackout blind SQM parts', $sqm_parts );
			$this->result->log( 'sqm', 'Blackout blind SQM total', $this->sqm_value_blackout );
		} elseif ( $this->sqm_value < PricingConfig::SQM_MINIMUM ) {
			$this->sqm_value = PricingConfig::SQM_MINIMUM;
		}

		// Width track (used in tracked style calculations).
		if ( $this->products['property_width'] ) {
			$this->width_track = floatval( $this->products['property_width'] );
		}

		$this->result->log( 'sqm', 'SQM value', $this->sqm_value );
		$this->result->log( 'sqm', 'Width track', $this->width_track );
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 2: Base Material Price (GBP)
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 2: Calculate base material price using config-driven material loop.
	 *
	 * Includes tax uplift for flagged users and ALU discount for Earth.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function calculateBasePrice() {
		if ( ! $this->material_def ) {
			return;
		}

		$rate = $this->config->getGbpRate( $this->material_key );

		// Base price calculation.
		$this->sum   = $this->sqm_value * $rate;
		$this->basic = $this->sum;

		// Tax uplift (replaces hardcoded $user_id == 18 check).
		if ( $this->config->hasTaxUplift() ) {
			$tax_rate  = $this->config->getTaxRate( $this->material_key );
			$this->sum = ( $this->sqm_value * $rate ) + ( ( $this->sqm_value * $rate ) * $tax_rate ) / 100;
		}

		$this->result->log( 'base_price', 'SUM ' . $this->material_key, $this->sum );
		$this->result->log( 'base_price', 'BASIC 1', $this->basic );

		// ALU discount (Earth only -- applies discount based on per-SQM rate, not on $sum).
		if ( $this->material_def['alu_discount'] ) {
			$discount_rate = horeka_get_style_discount( intval( $this->products['property_style'] ) );

			if ( $discount_rate > 0 ) {
				$this->sum   -= ( $rate * $discount_rate ) / 100;
				$this->basic -= ( $this->basic * $discount_rate ) / 100;
			}

			$this->result->basic_earth_price = floatval( $this->basic );
			$this->result->has_alu_discount  = true;
			$this->result->log( 'base_price', 'ALU discount rate', $discount_rate );
			$this->result->log( 'base_price', 'basic_earth_price', $this->basic );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 3: Custom Discount
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 3: Apply custom percentage discount and reset $basic.
	 *
	 * Pattern: $sum -= ($discount_custom * $basic) / 100; then $basic = $sum.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyCustomDiscount() {
		$discount_custom = $this->config->getDiscountCustom();

		if ( $discount_custom > 0 ) {
			$discount_amount = number_format( ( $discount_custom * $this->basic ) / 100, 2, '.', '' );
			$this->sum       = $this->sum - $discount_amount;
			$this->basic     = $this->sum;

			$this->result->log( 'custom_discount', 'Discount %', $discount_custom );
			$this->result->log( 'custom_discount', 'Discount amount', $discount_amount );
			$this->result->log( 'custom_discount', 'SUM after discount', $this->sum );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 4: Style Surcharges
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 4: Apply style-based surcharges (Solid, Shaped, French Door, Tracked, etc.).
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyStyleSurcharges() {
		$style = intval( $this->products['property_style'] );

		// Solid styles.
		if ( in_array( $style, PricingConfig::SOLID_STYLES ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Solid' );
			$this->addSurcharge( 'style', 'Solid', ( $rate * $this->basic ) / 100, $rate );
		}

		// Shaped styles.
		if ( in_array( $style, PricingConfig::SHAPED_STYLES ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Shaped' );
			$this->addSurcharge( 'style', 'Shaped', ( $rate * $this->basic ) / 100, $rate );
		}

		// French Door (flat amount, not percentage).
		if ( $style == PricingConfig::STYLE_FRENCH_DOOR ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'French_Door' );
			$this->addSurcharge( 'style', 'French_Door', $rate );
		}

		// Tracked styles (per-metre: rate * (width / 1000)).
		if ( in_array( $style, PricingConfig::TRACKED_STYLES ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Tracked' );
			$this->addSurcharge( 'style', 'Tracked', $rate * ( $this->width_track / 1000 ) );
		}

		// Tracked bypass styles (per-metre: rate * (width / 1000)).
		if ( in_array( $style, PricingConfig::TRACKED_BYPASS_STYLES ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'TrackedByPass' );
			$this->addSurcharge( 'style', 'TrackedByPass', $rate * ( $this->width_track / 1000 ) );

			// Extra tracks (3+): add Tracked rate * (tracksnumber - 2) * (width / 1000).
			if ( intval( $this->products['property_tracksnumber'] ) >= 3 ) {
				$extra_tracks = intval( $this->products['property_tracksnumber'] ) - 2;

				// Per-material or user 'Tracked' meta => use rate; else use the tracked fallback rate.
				$has_tracked = $this->config->hasPerMaterialRate( $this->material_id, 'Tracked' )
					|| $this->config->hasUserRate( 'Tracked' );
				if ( $has_tracked ) {
					$tracked_rate = $this->config->getPerMaterialRate( $this->material_id, 'Tracked' );
					$this->addSurcharge( 'style', 'Tracked extra tracks x' . $extra_tracks, $tracked_rate * $extra_tracks * ( $this->width_track / 1000 ) );
				} else {
					$this->addSurcharge( 'style', 'Tracked extra tracks x' . $extra_tracks, PricingConfig::TRACKED_FALLBACK_RATE * $extra_tracks * ( $this->width_track / 1000 ) );
				}
			}
		}

		// Light block strip (Yes/No field -- placed here matching original order).
		if ( $this->getProductField( 'property_lightblocks' ) == 'Yes' ) {
			$rate = $this->config->getRate( 'Light_block' );
			$this->addSurcharge( 'style', 'Light_block', ( $rate * $this->basic ) / 100, $rate );
		}

		// Arched styles.
		if ( in_array( $style, PricingConfig::ARCHED_STYLES ) ) {
			// Original code quirk: fetches arched rate from $products['customer_id'] meta
			// when user has 'Arched' user meta, and from global (post 1) otherwise.
			// In the create flow, customer_id is typically 0, so get_user_meta(0, ...) returns ''
			// and both code paths effectively use global. The BiowoodPlus branch formerly added +30
			// but that was commented out.
			//
			// Simplified: use config->getPerMaterialRate() which resolves mat > user > global.
			$arched_rate = $this->config->getPerMaterialRate( $this->material_id, 'Arched' );
			$this->addSurcharge( 'style', 'Arched', ( $arched_rate * $this->basic ) / 100, $arched_rate );
		}

		// Combi styles.
		if ( in_array( $style, PricingConfig::COMBI_STYLES ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Combi' );
			$this->addSurcharge( 'style', 'Combi', ( $rate * $this->basic ) / 100, $rate );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 5: Option Surcharges
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 5: Apply option-based surcharges (inside fit, concealed rod, hidden rod, hinges).
	 *
	 * Note: Light block is applied in Phase 4 to match the original code order.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyOptionSurcharges() {
		// Inside fit (measurement type).
		if ( intval( $this->products['property_fit'] ) == PricingConfig::FIT_INSIDE ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Inside' );
			$this->addSurcharge( 'options', 'inside', ( $rate * $this->basic ) / 100, $rate );
		}

		// Concealed Rod control type.
		if ( intval( $this->products['property_controltype'] ) == PricingConfig::CONTROL_CONCEALED_ROD ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Concealed_Rod' );
			$this->addSurcharge( 'options', 'Concealed_Rod', ( $rate * $this->basic ) / 100, $rate );
		}

		// Hidden Rod with Locking System (GBP).
		// Asymmetric: user/per-material meta => ($basic * rate) / 100; global => rate * $sqm_value.
		if ( intval( $this->products['property_controltype'] ) == PricingConfig::CONTROL_HIDDEN_ROD ) {
			$has_hrod = $this->config->hasPerMaterialRate( $this->material_id, 'Hidden_Rod_with_Locking_System' )
				|| $this->config->hasUserRate( 'Hidden_Rod_with_Locking_System' );
			$hrod_rate = $this->config->getPerMaterialRate( $this->material_id, 'Hidden_Rod_with_Locking_System' );
			if ( $has_hrod ) {
				$this->addSurcharge( 'options', 'Hidden_Rod_with_Locking_System', ( $this->basic * $hrod_rate ) / 100, $hrod_rate );
			} else {
				$this->addSurcharge( 'options', 'Hidden_Rod_with_Locking_System', $hrod_rate * $this->sqm_value );
			}
		}

		// Stainless Steel hinge colour.
		if ( intval( $this->products['property_hingecolour'] ) == PricingConfig::HINGE_STAINLESS_STEEL ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Stainless_Steel' );
			$this->addSurcharge( 'options', 'Stainless_Steel', ( $rate * $this->basic ) / 100, $rate );
		}

		// Hidden hinge colour.
		if ( intval( $this->products['property_hingecolour'] ) == PricingConfig::HINGE_HIDDEN ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Hidden' );
			$this->addSurcharge( 'options', 'Hidden', ( $rate * $this->basic ) / 100, $rate );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 6: Frame Type Surcharges
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 6: Apply frame type surcharges (P4028X, blackout extras, P4008T, 4008T, P4008W, Flat Louver).
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyFrameTypeSurcharges() {
		$frametype   = intval( $this->products['property_frametype'] );
		$material_id = intval( $this->products['property_material'] );

		// Blackout Blind (frametype 171).
		if ( $frametype == PricingConfig::FRAMETYPE_BLACKOUT_BLIND ) {
			// P4028X surcharge.
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'P4028X' );
			$this->addSurcharge( 'frametype', 'P4028X', ( $rate * $this->basic ) / 100, $rate );

			// Blackout blind colour surcharge (if colour is set and not 390).
			$blackout_colour = isset( $this->products['property_blackoutblindcolour'] )
				? intval( $this->products['property_blackoutblindcolour'] )
				: 0;

			if ( ! empty( $blackout_colour ) && $blackout_colour != PricingConfig::BLACKOUT_DEFAULT_COLOUR ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'blackoutblind' );
				$this->addSurcharge( 'frametype', 'blackoutblind', $rate * $this->sqm_value_blackout );
			}

			// T-post type blackout surcharge.
			if ( ! empty( $this->products['property_tposttype'] ) ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'tposttype_blackout' );
				$this->addSurcharge( 'frametype', 'tposttype_blackout', ( $rate * $this->basic ) / 100, $rate );
			}

			// B-post type blackout surcharge.
			if ( ! empty( $this->products['bay-post-type'] ) ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'bposttype_blackout' );
				$this->addSurcharge( 'frametype', 'bposttype_blackout', ( $rate * $this->basic ) / 100, $rate );
			}
		}

		// P4008T.
		if ( $frametype == PricingConfig::FRAMETYPE_P4008T ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'P4008T' );
			$this->addSurcharge( 'frametype', 'P4008T', ( $rate * $this->basic ) / 100, $rate );
		}

		// 4008T.
		if ( $frametype == PricingConfig::FRAMETYPE_4008T ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, '4008T' );
			$this->addSurcharge( 'frametype', '4008T', ( $rate * $this->basic ) / 100, $rate );
		}

		// P4008W.
		if ( $frametype == PricingConfig::FRAMETYPE_P4008W ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'P4008W' );
			$this->addSurcharge( 'frametype', 'P4008W', ( $rate * $this->basic ) / 100, $rate );
		}

		// Flat Louver blade reduction (Basswood/BasswoodPlus only).
		if (
			$material_id == PricingConfig::MATERIAL_BASSWOODPLUS ||
			$material_id == PricingConfig::MATERIAL_BASSWOOD
		) {
			if ( intval( $this->products['property_bladesize'] ) == PricingConfig::BLADE_FLAT_LOUVER ) {
				// Note: original uses get_post_meta(1, ...) only -- no user override.
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'Flat_Louver' );
				$this->addSurcharge( 'frametype', 'Flat_Louver', ( $rate * $this->basic ) / 100, $rate );
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 7: Buildout
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 7: Apply main buildout surcharge with frame depth calculation.
	 *
	 * Rules:
	 * - If buildout > 0 and (frame_depth + buildout) <= 100mm: standard buildout rate.
	 * - If (frame_depth + buildout) > 100mm: fixed 20% surcharge on basic.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyBuildout() {
		$builtout  = $this->products['property_builtout'];
		$frametype = intval( $this->products['property_frametype'] );

		if ( strlen( $builtout ) > 0 && ! empty( $builtout ) ) {
			$frdepth = isset( $this->frames_type_map[ $frametype ] )
				? $this->frames_type_map[ $frametype ]
				: 0;
			$sum_build_frame = $frdepth + intval( $builtout );

			if ( $sum_build_frame <= PricingConfig::BUILDOUT_DEPTH_THRESHOLD ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'Buildout' );
				$this->addSurcharge( 'buildout', 'Buildout (standard)', ( $rate * $this->basic ) / 100, $rate );
			} elseif ( $sum_build_frame > PricingConfig::BUILDOUT_DEPTH_THRESHOLD ) {
				$this->result->has_deep_buildout = true;
				$this->addSurcharge( 'buildout', 'Buildout (deep 20%)', ( PricingConfig::BUILDOUT_DEEP_PCT * $this->basic ) / 100, PricingConfig::BUILDOUT_DEEP_PCT );
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 8: Post Surcharges
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 8: Apply post-related surcharges.
	 *
	 * Bay angle, Flexible B-post, B/T/C buildout, G-post, T-adjustable, C buildout.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyPostSurcharges() {
		// Bay angle surcharge.
		$bayangle_keys = array();
		for ( $i = 1; $i <= 15; $i++ ) {
			$bayangle_keys[] = 'property_ba' . $i;
		}

		foreach ( $bayangle_keys as $property_ba ) {
			if ( ! empty( $property_ba ) ) {
				$bay_post_type = isset( $this->products['bay-post-type'] ) ? $this->products['bay-post-type'] : '';
				if ( ! empty( $this->products[ $property_ba ] ) && $bay_post_type == 'normal' ) {
					$angle = intval( $this->products[ $property_ba ] );
					if ( $angle == 90 || $angle == 135 ) {
						// No surcharge for 90 or 135 degree angles.
					} else {
						$rate = $this->config->getPerMaterialRate( $this->material_id, 'Bay_Angle' );
						$this->addSurcharge( 'posts', 'Bay_Angle', ( $rate * $this->basic ) / 100, $rate );
						break;
					}
				}
			}
		}

		// Flexible B-post type.
		$bay_post_type = isset( $this->products['bay-post-type'] ) ? $this->products['bay-post-type'] : '';
		if ( ! empty( $bay_post_type ) ) {
			if ( $bay_post_type == 'flexible' ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'B_typeFlexible' );
				$this->addSurcharge( 'posts', 'B_typeFlexible', ( $rate * $this->basic ) / 100, $rate );
			}
		}

		// B-post buildout (break on first found).
		$this->applyPostBuildout( 'property_b_buildout', 'B_Buildout' );

		// G-post surcharge.
		if ( ! empty( $this->products['property_g1'] ) ) {
			$has_gpost = $this->config->hasPerMaterialRate( $this->material_id, 'G_post' )
				|| $this->config->hasUserRate( 'G_post' );
			if ( $has_gpost ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'G_post' );
				$this->addSurcharge( 'posts', 'G_post', ( $rate * $this->basic ) / 100, $rate );
			} else {
				$this->addSurcharge( 'posts', 'G_post (fallback)', ( PricingConfig::G_POST_FALLBACK_PCT * $this->basic ) / 100, PricingConfig::G_POST_FALLBACK_PCT );
			}
		}

		// T-post buildout (break on first found).
		$this->applyPostBuildout( 'property_t_buildout', 'T_Buildout' );

		// T-post type adjustable.
		$t_post_type = isset( $this->products['t-post-type'] ) ? $this->products['t-post-type'] : '';
		if ( ! empty( $t_post_type ) ) {
			if ( $t_post_type == 'adjustable' ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'T_typeAdjustable' );
				$this->addSurcharge( 'posts', 'T_typeAdjustable', ( $rate * $this->basic ) / 100, $rate );
			}
		}

		// C-post buildout (break on first found).
		$this->applyPostBuildout( 'property_c_buildout', 'C_Buildout' );
	}

	/**
	 * Apply a post buildout surcharge (B, T, or C).
	 *
	 * Iterates property_X_buildout1..15, applies rate on first found, then breaks.
	 *
	 * @since 1.0.0
	 * @param string $prefix   The property prefix (e.g. 'property_b_buildout').
	 * @param string $rate_key The config rate key (e.g. 'B_Buildout').
	 * @return void
	 */
	private function applyPostBuildout( $prefix, $rate_key ) {
		for ( $i = 1; $i <= 15; $i++ ) {
			$key = $prefix . $i;
			if ( ! empty( $this->products[ $key ] ) ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, $rate_key );
				$this->addSurcharge( 'posts', $rate_key, ( $rate * $this->basic ) / 100, $rate );
				break;
			}
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 9: Color Surcharges (GBP)
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 9: Apply color-based surcharges (GBP).
	 *
	 * Two groups:
	 * - Colors GBP surcharge (via 'Colors' user/global meta).
	 * - Colors 10% fixed surcharge.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyColorSurcharges() {
		$colour = intval( $this->products['property_shuttercolour'] );

		// Colors GBP surcharge (via per-material/user/global meta).
		if ( in_array( $colour, PricingConfig::COLORS_GBP_SURCHARGE ) ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Colors' );
			$this->addSurcharge( 'colors', 'Colors', ( $rate * $this->basic ) / 100, $rate );
		}

		// Colors 10% fixed surcharge.
		if ( in_array( $colour, PricingConfig::COLORS_GBP_10PCT ) ) {
			$this->addSurcharge( 'colors', 'Colors 10%', ( PricingConfig::COLORS_GBP_10PCT_RATE * $this->basic ) / 100, PricingConfig::COLORS_GBP_10PCT_RATE );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 10: Accessories
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 10: Apply accessory surcharges.
	 *
	 * Ring Pull, Locks, Central Lock, Louver Lock, Spare Louvres, SeaDelivery, Panel width.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyAccessories() {
		$style = intval( $this->products['property_style'] );

		// Ring Pull (solid panel styles only).
		if ( in_array( $style, PricingConfig::RINGPULL_STYLES ) ) {
			$ringpull = $this->getProductField( 'property_ringpull' );
			if ( ! empty( $ringpull ) && $ringpull !== 'No' ) {
				$rate   = $this->config->getPerMaterialRate( $this->material_id, 'Ringpull' );
				$volume = intval( $this->products['property_ringpull_volume'] );
				$this->addSurcharge( 'accessories', 'Ringpull x' . $volume, $rate * $volume );
			}
		}

		// Horizontal T-post multiplier (used for locks).
		$horizontal_tpost_add = 1;
		if ( $this->getProductField( 'property_horizontaltpost' ) == 'Yes' ) {
			$horizontal_tpost_add = 2;
		}

		// Central Lock.
		$t_count = countT( $this->products['property_layoutcode'] );
		if ( $this->getProductField( 'property_central_lock' ) == 'Yes' ) {
			$t_count_add   = ( $t_count > 0 ) ? $t_count : 1;
			$central_price = $this->config->getRate( 'Central_Lock' );
			$this->addSurcharge( 'accessories', 'Central_Lock', $central_price * $t_count_add * $horizontal_tpost_add );
		}

		// Locks.
		if ( $this->getProductField( 'property_locks' ) == 'Yes' ) {
			$material_id = intval( $this->products['property_material'] );

			// Biowood / BiowoodPlus: hardcoded lock price.
			if ( in_array( $material_id, PricingConfig::BIOWOOD_LOCK_MATERIALS ) ) {
				$this->addSurcharge( 'accessories', 'Lock (biowood)', PricingConfig::BIOWOOD_LOCK_PRICE * $horizontal_tpost_add );
			} else {
				// Lock position handling.
				$lock_position = isset( $this->products['property_lock_position'] )
					? $this->products['property_lock_position']
					: '';

				if ( ! empty( $lock_position ) ) {
					if ( $lock_position == 'Central Lock' ) {
						$this->addSurcharge( 'accessories', 'Lock (Central Lock)', $this->config->getRate( 'Central_Lock' ) * $horizontal_tpost_add );
					}
					if ( $lock_position == 'Top & Bottom Lock' ) {
						$this->addSurcharge( 'accessories', 'Lock (Top & Bottom)', $this->config->getRate( 'Top_Bottom_Lock' ) * $horizontal_tpost_add );
					}
				} else {
					$rate = $this->config->getPerMaterialRate( $this->material_id, 'Lock' );
					$this->addSurcharge( 'accessories', 'Lock (default)', $rate * 2 * $horizontal_tpost_add );
				}
			}
		}

		// Panel width surcharge.
		$layout_code = $this->products['property_layoutcode'];
		if ( $layout_code == 'B' || $layout_code == 'T' || $layout_code == 'C' ) {
			$panels_width = calculatePanelsWidth( $layout_code, $this->products['property_width'], $this->products );
			foreach ( $panels_width as $panel ) {
				if ( $panel < PricingConfig::PANEL_WIDTH_MINIMUM ) {
					$this->addSurcharge( 'accessories', 'panel_width_price', $this->config->getRate( 'panel_width_price' ) );
				}
			}
		} elseif ( $this->width_track > 0 ) {
			$nr_panels = nrPanelsCount( $layout_code );
			if ( $nr_panels > 0 && ( $this->width_track / $nr_panels ) < PricingConfig::PANEL_WIDTH_MINIMUM ) {
				$this->addSurcharge( 'accessories', 'panel_width_price (uniform)', $nr_panels * $this->config->getRate( 'panel_width_price' ) );
			}
		}

		// Louver Lock (Hidden Rod only).
		if ( intval( $this->products['property_controltype'] ) == PricingConfig::CONTROL_HIDDEN_ROD ) {
			$nr_panels = nrPanelsCount( $this->products['property_layoutcode'] );
			$mid_count = midrailDividerCounter(
				isset( $this->products['property_midrailheight'] ) ? $this->products['property_midrailheight'] : 0,
				isset( $this->products['property_midrailheight2'] ) ? $this->products['property_midrailheight2'] : 0,
				isset( $this->products['property_midraildivider1'] ) ? $this->products['property_midraildivider1'] : 0,
				isset( $this->products['property_midraildivider2'] ) ? $this->products['property_midraildivider2'] : 0
			);

			$rate = $this->config->getRate( 'Louver_lock' );
			$this->addSurcharge( 'accessories', 'Louver_lock', $rate * $nr_panels * $mid_count );
			$this->result->log( 'accessories', 'nrPanels', $nr_panels );
			$this->result->log( 'accessories', 'midCount', $mid_count );
		}

		// Spare Louvres.
		if ( $this->getProductField( 'property_sparelouvres' ) == 'Yes' ) {
			$rate = $this->config->getPerMaterialRate( $this->material_id, 'Spare_Louvres' );
			$panels_lr = intval( $this->products['panels_left_right'] );
			$this->addSurcharge( 'accessories', 'Spare_Louvres x' . $panels_lr, $panels_lr * $rate );
		}

		// Sea Delivery (flat amount).
		if ( $this->config->hasSeaDelivery() ) {
			$this->addSurcharge( 'accessories', 'SeaDelivery', $this->config->getSeaDelivery() );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 11: Sections (per-section pricing)
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 11: Calculate per-section pricing for multi-window products.
	 *
	 * For each section, applies T_Buildout, T_typeAdjustable, and G_post
	 * surcharges, then snapshots $sum into sections_price.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function calculateSections() {
		$nr_sections = intval( $this->products['property_nr_sections'] );

		if ( ! $nr_sections ) {
			return;
		}

		// Initialize sections_price array.
		for ( $sec = 1; $sec <= $nr_sections; $sec++ ) {
			$this->result->sections_price[ $sec ] = 0;
		}

		for ( $i = 1; $i <= $nr_sections; $i++ ) {
			$property_tb = 'property_t_buildout1_' . $i;
			$tposttype   = 't-post-type' . $i;

			// T-buildout per section -- break on first found (only charged once).
			if ( ! empty( $this->products[ $property_tb ] ) ) {
				$rate = $this->config->getPerMaterialRate( $this->material_id, 'T_Buildout' );
				$this->addSurcharge( 'sections', 'T_Buildout section ' . $i, ( $rate * $this->basic ) / 100, $rate );
				break;
			}

			// T-post type adjustable per section.
			if ( ! empty( $this->products[ $tposttype ] ) ) {
				if ( $this->products[ $tposttype ] == 'adjustable' ) {
					$rate = $this->config->getPerMaterialRate( $this->material_id, 'T_typeAdjustable' );
					$this->addSurcharge( 'sections', 'T_typeAdjustable section ' . $i, ( $rate * $this->basic ) / 100, $rate );
				}
			}

			// G-post per section.
			if ( ! empty( $this->products['property_g1'] ) ) {
				$has_gpost = $this->config->hasPerMaterialRate( $this->material_id, 'G_post' )
					|| $this->config->hasUserRate( 'G_post' );
				if ( $has_gpost ) {
					$rate = $this->config->getPerMaterialRate( $this->material_id, 'G_post' );
					$this->addSurcharge( 'sections', 'G_post section ' . $i, ( $rate * $this->basic ) / 100, $rate );
				} else {
					$this->addSurcharge( 'sections', 'G_post (fallback) section ' . $i, ( PricingConfig::G_POST_FALLBACK_PCT * $this->basic ) / 100, PricingConfig::G_POST_FALLBACK_PCT );
				}
			}

			$this->result->sections_price[ $i ] = $this->sum;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 12: Motorized
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 12: Apply motorized surcharges (motor + remote).
	 *
	 * NOTE: The original code reads property_nrMotors and property_nrRemotes from
	 * post meta (get_post_meta($post_id, ...)). Since PricingCalculator is pure
	 * calculation without side effects, these values must be passed in $products.
	 * If they are not present in $products, we fall back to 0.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function applyMotorized() {
		if ( $this->getProductField( 'property_motorized' ) != 'Yes' ) {
			return;
		}

		// Motor count and price.
		$nr_motors  = intval( isset( $this->products['property_nrMotors'] ) ? $this->products['property_nrMotors'] : 0 );
		$motor_price = $this->config->getRate( 'motor' );

		// Remote count and price.
		$nr_remotes   = intval( isset( $this->products['property_nrRemotes'] ) ? $this->products['property_nrRemotes'] : 0 );
		$remote_price = $this->config->getRate( 'remote' );

		$this->addSurcharge( 'motorized', 'Motors x' . $nr_motors, $nr_motors * $motor_price );
		$this->addSurcharge( 'motorized', 'Remotes x' . $nr_remotes, $nr_remotes * $remote_price );

		$this->result->nr_motors  = $nr_motors;
		$this->result->nr_remotes = $nr_remotes;
	}

	/*
	|--------------------------------------------------------------------------
	| Phase 13: USD Calculation
	|--------------------------------------------------------------------------
	*/

	/**
	 * Phase 13: Complete parallel USD calculation.
	 *
	 * Uses USD rates (key-dolar suffix) with different style/option scopes
	 * than GBP. Material-specific color surcharges instead of generic percentages.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function calculateUsd() {
		$usd_sum   = 0.0;
		$usd_basic = 0.0;

		if ( ! $this->material_def ) {
			$this->result->usd_price = 0.0;
			return;
		}

		// Base material price (USD).
		$usd_sum   = $this->sqm_value * $this->config->getPerMaterialUsdRate( $this->material_id, $this->material_key );
		$usd_basic = $usd_sum;

		$this->result->log( 'usd', 'SUM 1 dolar', $usd_sum );
		$this->result->log( 'usd', 'BASIC 1 dolar', $usd_basic );

		$style       = intval( $this->products['property_style'] );
		$material_id = intval( $this->products['property_material'] );

		// Flat Louver (USD) -- placed before styles in original.
		if (
			( $material_id == PricingConfig::MATERIAL_BASSWOODPLUS || $material_id == PricingConfig::MATERIAL_BASSWOOD ) &&
			intval( $this->products['property_bladesize'] ) == PricingConfig::BLADE_FLAT_LOUVER
		) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Flat_Louver' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Flat_Louver dolar', $usd_sum );
		}

		// Solid styles (USD).
		if ( in_array( $style, PricingConfig::SOLID_STYLES ) ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Solid' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Solid dolar', $usd_sum );
		}

		// Shaped styles (USD).
		if ( in_array( $style, PricingConfig::SHAPED_STYLES ) ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Shaped' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Shaped dolar', $usd_sum );
		}

		// French Door (USD -- flat amount).
		if ( $style == PricingConfig::STYLE_FRENCH_DOOR ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'French_Door' );
			$usd_sum += $val;
			$this->result->log( 'usd', 'SUM French_Door dolar', $usd_sum );
		}

		// Tracked (USD) -- narrower scope than GBP: only style 35, not all TRACKED_STYLES.
		if ( $style == 35 ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Tracked' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Tracked dolar', $usd_sum );
		}

		// TrackedByPass (USD) -- narrower scope than GBP: only style 37, not all TRACKED_BYPASS_STYLES.
		if ( $style == 37 ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'TrackedByPass' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM TrackedByPass dolar', $usd_sum );
		}

		// Arched (USD).
		if ( in_array( $style, PricingConfig::ARCHED_STYLES ) ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Arched' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Arched dolar', $usd_sum );
		}

		// Combi (USD) -- narrower scope than GBP: only styles 229, 233, not all COMBI_STYLES.
		if ( $style == 229 || $style == 233 ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Combi' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Combi dolar', $usd_sum );
		}

		// Frame type surcharges (USD).
		$frametype = intval( $this->products['property_frametype'] );

		// Blackout Blind (USD).
		if ( $frametype == PricingConfig::FRAMETYPE_BLACKOUT_BLIND ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'P4028X' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM P4028X dolar', $usd_sum );

			$blackoutblind = $this->config->getPerMaterialUsdRate( $this->material_id, 'blackoutblind' );
			if ( $blackoutblind > 0 ) {
				$usd_sum += $blackoutblind * $this->sqm_value_blackout;
				$this->result->log( 'usd', 'SUM blackoutblind dolar', $usd_sum );
			}

			if ( ! empty( $this->products['property_tposttype'] ) ) {
				$tposttype_blackout = $this->config->getPerMaterialUsdRate( $this->material_id, 'tposttype_blackout' );
				if ( $tposttype_blackout > 0 ) {
					$usd_sum += ( $tposttype_blackout * $usd_basic ) / 100;
					$this->result->log( 'usd', 'SUM tposttype_blackout dolar', $usd_sum );
				}
			}

			if ( ! empty( $this->products['bay-post-type'] ) ) {
				$bposttype_blackout = $this->config->getPerMaterialUsdRate( $this->material_id, 'bposttype_blackout' );
				if ( $bposttype_blackout > 0 ) {
					$usd_sum += ( $bposttype_blackout * $usd_basic ) / 100;
					$this->result->log( 'usd', 'SUM bposttype_blackout dolar', $usd_sum );
				}
			}
		}

		// P4008T (USD).
		if ( $frametype == PricingConfig::FRAMETYPE_P4008T ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'P4008T' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM P4008T dolar', $usd_sum );
		}

		// 4008T (USD).
		if ( $frametype == PricingConfig::FRAMETYPE_4008T ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, '4008T' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM 4008T dolar', $usd_sum );
		}

		// P4008W (USD).
		if ( $frametype == PricingConfig::FRAMETYPE_P4008W ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'P4008W' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM P4008W dolar', $usd_sum );
		}

		// Buildout (USD).
		$builtout = $this->products['property_builtout'];
		if ( strlen( $builtout ) > 0 && ! empty( $builtout ) ) {
			$frdepth = isset( $this->frames_type_map[ $frametype ] )
				? $this->frames_type_map[ $frametype ]
				: 0;
			$sum_build_frame = $frdepth + intval( $builtout );

			if ( $sum_build_frame <= PricingConfig::BUILDOUT_DEPTH_THRESHOLD ) {
				$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Buildout' );
				if ( $val > 0 ) {
					$usd_sum += ( $val * $usd_basic ) / 100;
					$this->result->log( 'usd', 'SUM Buildout dolar', $usd_sum );
				}
			} elseif ( $sum_build_frame > PricingConfig::BUILDOUT_DEPTH_THRESHOLD ) {
				$usd_sum += ( PricingConfig::BUILDOUT_DEEP_PCT * $usd_basic ) / 100;
				$this->result->log( 'usd', 'SUM Buildout deep dolar', $usd_sum );
			}
		}

		// Concealed Rod (USD) -- both user and global use the same formula.
		if ( intval( $this->products['property_controltype'] ) == PricingConfig::CONTROL_CONCEALED_ROD ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Concealed_Rod' );
			$usd_sum += ( $usd_basic * $val ) / 100;
			$this->result->log( 'usd', 'SUM Concealed_Rod dolar', $usd_sum );
		}

		// Hidden Rod with Locking System (USD).
		// Asymmetric: user/per-material meta => ($basic * rate) / 100; global => rate * $sqm_value.
		if ( intval( $this->products['property_controltype'] ) == PricingConfig::CONTROL_HIDDEN_ROD ) {
			$has_hrod_usd = $this->config->hasPerMaterialRate( $this->material_id, 'Hidden_Rod_with_Locking_System-dolar' )
				|| $this->config->hasUserRate( 'Hidden_Rod_with_Locking_System-dolar' );
			$hrod_usd_rate = $this->config->getPerMaterialUsdRate( $this->material_id, 'Hidden_Rod_with_Locking_System' );
			if ( $has_hrod_usd ) {
				$usd_sum += ( $usd_basic * $hrod_usd_rate ) / 100;
			} else {
				$usd_sum += $hrod_usd_rate * $this->sqm_value;
			}
			$this->result->log( 'usd', 'SUM Hidden_Rod dolar', $usd_sum );
		}

		// Stainless Steel hinge (USD).
		if ( intval( $this->products['property_hingecolour'] ) == PricingConfig::HINGE_STAINLESS_STEEL ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Stainless_Steel' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Stainless_Steel dolar', $usd_sum );
		}

		// Hidden hinge (USD).
		if ( intval( $this->products['property_hingecolour'] ) == PricingConfig::HINGE_HIDDEN ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Hidden' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM Hidden dolar', $usd_sum );
		}

		// Bay angle (USD).
		for ( $i = 1; $i <= 15; $i++ ) {
			$property_ba   = 'property_ba' . $i;
			$bay_post_type = isset( $this->products['bay-post-type'] ) ? $this->products['bay-post-type'] : '';
			if ( ! empty( $this->products[ $property_ba ] ) && $bay_post_type == 'normal' ) {
				$angle = intval( $this->products[ $property_ba ] );
				if ( $angle == 90 || $angle == 135 ) {
					// No surcharge.
				} else {
					$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Bay_Angle' );
					$usd_sum += ( $val * $usd_basic ) / 100;
					$this->result->log( 'usd', 'SUM Bay_Angle dolar', $usd_sum );
					break;
				}
			}
		}

		// Flexible B-post type (USD).
		$bay_post_type = isset( $this->products['bay-post-type'] ) ? $this->products['bay-post-type'] : '';
		if ( ! empty( $bay_post_type ) && $bay_post_type == 'flexible' ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'B_typeFlexible' );
			$usd_sum += ( $val * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM B_typeFlexible dolar', $usd_sum );
		}

		// B-post buildout (USD).
		$usd_sum = $this->applyUsdPostBuildout( $usd_sum, $usd_basic, 'property_b_buildout', 'B_Buildout' );

		// T-post buildout (USD).
		$usd_sum = $this->applyUsdPostBuildout( $usd_sum, $usd_basic, 'property_t_buildout', 'T_Buildout' );

		// Per-section T-post buildout (USD).
		if ( intval( $this->products['property_nr_sections'] ) > 0 ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$property_tb = 'property_t_buildout1_' . $i;
				if ( ! empty( $this->products[ $property_tb ] ) ) {
					$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'T_Buildout' );
					$usd_sum += ( $val * $usd_basic ) / 100;
					$this->result->log( 'usd', 'SUM T_Buildout section dolar', $usd_sum );
					break;
				}
			}
		}

		// C-post buildout (USD).
		$usd_sum = $this->applyUsdPostBuildout( $usd_sum, $usd_basic, 'property_c_buildout', 'C_Buildout' );

		// Material-specific color surcharges (USD).
		// Stained Basswood/BasswoodPlus.
		if (
			( $material_id == PricingConfig::MATERIAL_BASSWOODPLUS || $material_id == PricingConfig::MATERIAL_BASSWOOD ) &&
			in_array( intval( $this->products['property_shuttercolour'] ), PricingConfig::USD_COLORS_BASSWOOD['ids'] )
		) {
			$usd_sum += ( PricingConfig::USD_COLORS_BASSWOOD['rate'] * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM USD Basswood color', $usd_sum );
		}

		// Brushed BiowoodPlus.
		if (
			$material_id == PricingConfig::MATERIAL_BIOWOODPLUS &&
			in_array( intval( $this->products['property_shuttercolour'] ), PricingConfig::USD_COLORS_BIOWOODPLUS['ids'] )
		) {
			$usd_sum += ( PricingConfig::USD_COLORS_BIOWOODPLUS['rate'] * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM USD BiowoodPlus color', $usd_sum );
		}

		// Painted Earth.
		if (
			$material_id == PricingConfig::MATERIAL_EARTH &&
			in_array( intval( $this->products['property_shuttercolour'] ), PricingConfig::USD_COLORS_EARTH['ids'] )
		) {
			$usd_sum += ( PricingConfig::USD_COLORS_EARTH['rate'] * $usd_basic ) / 100;
			$this->result->log( 'usd', 'SUM USD Earth color', $usd_sum );
		}

		// Ring Pull (USD).
		$ringpull = $this->getProductField( 'property_ringpull' );
		if ( ! empty( $ringpull ) && $ringpull !== 'No' ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Ringpull' );
			$usd_sum += $val * intval( $this->products['property_ringpull_volume'] );
			$this->result->log( 'usd', 'SUM Ringpull dolar', $usd_sum );
		}

		// Locks (USD -- simplified: rate * 2, no biowood special case).
		if ( $this->getProductField( 'property_locks' ) == 'Yes' ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Lock' );
			$usd_sum += $val * 2;
			$this->result->log( 'usd', 'SUM Lock dolar', $usd_sum );
		}

		// Spare Louvres (USD).
		if ( $this->getProductField( 'property_sparelouvres' ) == 'Yes' ) {
			$val = $this->config->getPerMaterialUsdRate( $this->material_id, 'Spare_Louvres' );
			$panels_lr = intval( $this->products['panels_left_right'] );
			$usd_sum += $panels_lr * $val;
			$this->result->log( 'usd', 'SUM Spare_Louvres dolar', $usd_sum );
		}

		// Panel width surcharge (USD).
		$layout_code = $this->products['property_layoutcode'];
		if ( $layout_code == 'B' || $layout_code == 'T' || $layout_code == 'C' ) {
			$panels_width = calculatePanelsWidth( $layout_code, $this->products['property_width'], $this->products );
			foreach ( $panels_width as $panel ) {
				if ( $panel < PricingConfig::PANEL_WIDTH_MINIMUM ) {
					$usd_sum += $this->config->getRate( 'dolar_price-panel_width_price' );
					$this->result->log( 'usd', 'SUM panel_width dolar', $usd_sum );
				}
			}
		} elseif ( $this->width_track > 0 ) {
			$nr_panels = nrPanelsCount( $layout_code );
			if ( $nr_panels > 0 && ( $this->width_track / $nr_panels ) < PricingConfig::PANEL_WIDTH_MINIMUM ) {
				$usd_sum += $nr_panels * $this->config->getRate( 'dolar_price-panel_width_price' );
				$this->result->log( 'usd', 'SUM panel_width dolar (uniform)', $usd_sum );
			}
		}

		// Motorized (USD).
		if ( $this->getProductField( 'property_motorized' ) == 'Yes' ) {
			$nr_motors    = intval( isset( $this->products['property_nrMotors'] ) ? $this->products['property_nrMotors'] : 0 );
			$motor_price  = $this->config->getRate( 'motor-dolar' );
			$nr_remotes   = intval( isset( $this->products['property_nrRemotes'] ) ? $this->products['property_nrRemotes'] : 0 );
			$remote_price = $this->config->getRate( 'remote-dolar' );

			$usd_sum += $nr_motors * $motor_price;
			$usd_sum += $nr_remotes * $remote_price;
			$this->result->log( 'usd', 'SUM motorized dolar', $usd_sum );
		}

		$this->result->usd_price = $usd_sum;
		$this->result->log( 'usd', 'Final USD price', $usd_sum );
	}

	/**
	 * Apply a USD post buildout surcharge (B, T, or C).
	 *
	 * Iterates property_X_buildout1..15, applies USD rate on first found, then breaks.
	 *
	 * @since 1.0.0
	 * @param float  $usd_sum   Current USD sum.
	 * @param float  $usd_basic USD basic price.
	 * @param string $prefix    The property prefix (e.g. 'property_b_buildout').
	 * @param string $rate_key  The config rate key (e.g. 'B_Buildout').
	 * @return float Updated USD sum.
	 */
	private function applyUsdPostBuildout( $usd_sum, $usd_basic, $prefix, $rate_key ) {
		for ( $i = 1; $i <= 15; $i++ ) {
			$key = $prefix . $i;
			if ( ! empty( $this->products[ $key ] ) ) {
				$val = $this->config->getPerMaterialUsdRate( $this->material_id, $rate_key );
				$usd_sum += ( $val * $usd_basic ) / 100;
				$this->result->log( 'usd', 'SUM ' . $rate_key . ' dolar', $usd_sum );
				break;
			}
		}
		return $usd_sum;
	}

	/*
	|--------------------------------------------------------------------------
	| Individual Section Counters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Build individual section counters for t, g, b, c posts per section.
	 *
	 * This is not a pricing phase but is needed by the AJAX handler for
	 * post meta storage.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function buildIndividualCounters() {
		$nr_sections = intval( $this->products['property_nr_sections'] );

		if ( ! $nr_sections ) {
			return;
		}

		$individual_counter = array();

		for ( $sec = 1; $sec <= $nr_sections; $sec++ ) {
			$t = 0;
			$g = 0;
			$individual_counter[ $sec ] = array(
				'counter_b' => 0,
				'counter_c' => 0,
				'counter_t' => 0,
				'counter_g' => 0,
			);

			for ( $i = 1; $i <= 10; $i++ ) {
				$property_t = 'property_t' . $i . '_' . $sec;
				$property_g = 'property_g' . $i . '_' . $sec;

				if ( ! empty( $this->products[ $property_t ] ) ) {
					$t++;
				}
				if ( ! empty( $this->products[ $property_g ] ) ) {
					$g++;
				}
			}

			$individual_counter[ $sec ]['counter_t'] = $t;
			$individual_counter[ $sec ]['counter_g'] = $g;
		}

		$this->result->individual_counter = $individual_counter;
	}

	/*
	|--------------------------------------------------------------------------
	| Multi-Section Aggregation (Individual Flow)
	|--------------------------------------------------------------------------
	*/

	/**
	 * Calculate pricing for an individual (multi-section) product.
	 *
	 * Runs PricingCalculator::calculate() once per section, overriding the SQM
	 * and layout code for each section, then aggregates the results into a
	 * single PricingResult with summed GBP/USD totals and per-section arrays.
	 *
	 * Used by ajax-prod-individual.php and ajax-prod-update-individual.php to
	 * avoid duplicating the section loop and aggregation logic.
	 *
	 * @since 1.0.0
	 * @param array         $products The full parsed product properties array.
	 * @param PricingConfig $config   The rate resolver instance.
	 * @return PricingResult Aggregated result with per-section and total prices.
	 */
	public function calculateMultiSection( array $products, PricingConfig $config ) {
		$nr_sections  = intval( $products['property_nr_sections'] );
		$gbp_sections = array();
		$usd_sections = array();
		$total_gbp    = 0.0;
		$total_usd    = 0.0;
		$last_result  = null;

		if ( $nr_sections > 0 ) {
			for ( $sec = 1; $sec <= $nr_sections; $sec++ ) {
				// Build per-section products array: override SQM and layout code.
				$section_products = $products;
				$section_products['property_total'] = floatval( $products[ 'property_total_section' . $sec ] ?? 0 );

				// Map per-section layout code to the standard field.
				if ( ! empty( $products[ 'property_layoutcode' . $sec ] ) ) {
					$section_products['property_layoutcode'] = $products[ 'property_layoutcode' . $sec ];
				}

				$result = $this->calculate( $section_products, $config );

				$gbp_sections[ $sec ] = $result->gbp_price;
				$usd_sections[ $sec ] = $result->usd_price;
				$total_gbp           += $result->gbp_price;
				$total_usd           += $result->usd_price;
				$last_result          = $result;
			}
		} else {
			// Fallback: single calculation if no sections defined.
			$last_result = $this->calculate( $products, $config );
			$total_gbp   = $last_result->gbp_price;
			$total_usd   = $last_result->usd_price;
		}

		// Build aggregated PricingResult.
		$aggregated                          = new PricingResult();
		$aggregated->gbp_price               = $total_gbp;
		$aggregated->usd_price               = $total_usd;
		$aggregated->basic                   = $last_result->basic;
		$aggregated->sqm                     = floatval( $products['property_total'] );
		$aggregated->sqm_parts               = $last_result->sqm_parts;
		$aggregated->sqm_value_blackout      = $last_result->sqm_value_blackout;
		$aggregated->material_key            = $last_result->material_key;
		$aggregated->material_rate           = $last_result->material_rate;
		$aggregated->has_alu_discount        = $last_result->has_alu_discount;
		$aggregated->basic_earth_price       = $last_result->basic_earth_price;
		$aggregated->has_deep_buildout       = $last_result->has_deep_buildout;
		$aggregated->width_track             = $last_result->width_track;
		$aggregated->sections_price          = $gbp_sections;
		$aggregated->dolar_sections_price    = $usd_sections;
		$aggregated->individual_counter      = $last_result->individual_counter;

		return $aggregated;
	}

	/*
	|--------------------------------------------------------------------------
	| Utility Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get a product field value safely.
	 *
	 * Returns empty string if the field does not exist.
	 *
	 * @since 1.0.0
	 * @param string $field The field name.
	 * @return mixed The field value or empty string.
	 */
	private function getProductField( $field ) {
		return isset( $this->products[ $field ] ) ? $this->products[ $field ] : '';
	}

	/**
	 * Add a surcharge to $this->sum and log it with amount breakdown.
	 *
	 * @since 1.0.0
	 * @param string $phase  The calculation phase name.
	 * @param string $label  The option name.
	 * @param float  $amount The GBP amount being added.
	 * @param float  $rate   The percentage rate (0 if not a % surcharge).
	 * @return void
	 */
	private function addSurcharge( string $phase, string $label, float $amount, float $rate = 0.0 ) {
		$this->sum += $amount;
		if ( $rate > 0 ) {
			$value = sprintf(
				'rate=%s%% +£%s total=£%s',
				number_format( $rate, 2 ),
				number_format( $amount, 2 ),
				number_format( $this->sum, 2 )
			);
		} else {
			$value = sprintf(
				'+£%s total=£%s',
				number_format( $amount, 2 ),
				number_format( $this->sum, 2 )
			);
		}
		$this->result->log( $phase, $label, $value );
	}
}
