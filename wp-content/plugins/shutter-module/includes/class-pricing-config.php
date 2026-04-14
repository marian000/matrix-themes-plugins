<?php
/**
 * PricingConfig: centralised rate resolver with in-memory caching.
 *
 * Replaces the hundreds of individual get_user_meta() / get_post_meta(1, ...)
 * fallback pairs scattered across the four pricing AJAX handlers with a single
 * getRate() method that includes per-request caching.
 *
 * Usage (future integration, task-025):
 *   $config = new PricingConfig( $user_id );
 *   $rate   = $config->getRate( 'Earth' );       // GBP rate with user > global fallback
 *   $usd    = $config->getUsdRate( 'Earth' );     // appends '-dolar' automatically
 *   $tax    = $config->getTaxRate( 'Earth' );      // appends '_tax' automatically
 *
 * This file is self-contained -- it does NOT require pricing-helpers.php,
 * pricing-maps.php, or pricing-constants.php to function.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class PricingConfig
 *
 * Centralised pricing rate resolver with user-level override,
 * global fallback (post ID 1), and in-memory caching.
 *
 * @since 1.0.0
 */
class PricingConfig {

	/**
	 * The user ID for rate lookups.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	private $user_id;

	/**
	 * In-memory cache of resolved rate values (numeric).
	 *
	 * Keys are meta key names, values are floatval results.
	 * Populated lazily by getRate().
	 *
	 * @since 1.0.0
	 * @var array<string, float>
	 */
	private $cache = array();

	/**
	 * In-memory cache of resolved rate values (raw strings).
	 *
	 * Keys are meta key names, values are the raw string from meta.
	 * Populated lazily by getRateString().
	 *
	 * @since 1.0.0
	 * @var array<string, string>
	 */
	private $string_cache = array();

	/*
	|--------------------------------------------------------------------------
	| Material Constants
	|--------------------------------------------------------------------------
	|
	| Maps material term IDs to their meta key and whether ALU discount applies.
	|
	*/

	/**
	 * Material definitions indexed by term ID.
	 *
	 * @since 1.0.0
	 * @var array<int, array{key: string, alu_discount: bool}>
	 */
	const MATERIALS = array(
		188 => array( 'key' => 'Ecowood',      'alu_discount' => false ),
		5   => array( 'key' => 'EcowoodPlus',  'alu_discount' => false ),
		6   => array( 'key' => 'Biowood',      'alu_discount' => false ),
		138 => array( 'key' => 'BiowoodPlus',  'alu_discount' => false ),
		147 => array( 'key' => 'Basswood',     'alu_discount' => false ),
		139 => array( 'key' => 'BasswoodPlus', 'alu_discount' => false ),
		187 => array( 'key' => 'Earth',        'alu_discount' => true ),
	);

	/*
	|--------------------------------------------------------------------------
	| Style Classification Constants
	|--------------------------------------------------------------------------
	*/

	/** Solid panel style term IDs. */
	const SOLID_STYLES = array( 221, 227, 226, 222, 228, 230, 231, 232, 38, 39, 42, 43 );

	/** Shaped/special-shape style term IDs. */
	const SHAPED_STYLES = array( 33, 43 );

	/** Tracked style term IDs. */
	const TRACKED_STYLES = array( 35, 39, 41 );

	/** Tracked bypass style term IDs. */
	const TRACKED_BYPASS_STYLES = array( 37, 38, 40 );

	/** Arched style term IDs. */
	const ARCHED_STYLES = array( 36, 42 );

	/** Combi style term IDs. */
	const COMBI_STYLES = array( 229, 233, 40, 41 );

	/** Ringpull-eligible style term IDs. */
	const RINGPULL_STYLES = array( 221, 227, 226, 222, 228, 230, 231, 232 );

	/** French Door style term ID. */
	const STYLE_FRENCH_DOOR = 34;

	/*
	|--------------------------------------------------------------------------
	| Color Classification Constants
	|--------------------------------------------------------------------------
	*/

	/** Color term IDs that trigger the 'Colors' user/global meta surcharge (GBP). */
	const COLORS_GBP_SURCHARGE = array(
		264, 265, 266, 267, 268, 269, 270, 271, 272, 273,
		128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
		132, 255, 134, 122, 123, 133, 256, 166, 124, 125, 111,
	);

	/** Color term IDs that trigger a fixed 10% surcharge (GBP). */
	const COLORS_GBP_10PCT = array( 262, 263, 274 );

	/** Fixed surcharge percentage for the 10% color group (GBP). */
	const COLORS_GBP_10PCT_RATE = 10;

	/*
	|--------------------------------------------------------------------------
	| USD Color Surcharge Constants
	|--------------------------------------------------------------------------
	*/

	/** Stained Basswood/BasswoodPlus color surcharge (USD). */
	const USD_COLORS_BASSWOOD = array(
		'ids'  => array(
			128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
			132, 255, 134, 122, 123, 133, 256, 166, 124, 125,
		),
		'rate' => 14.57,
	);

	/** Brushed BiowoodPlus color surcharge (USD). */
	const USD_COLORS_BIOWOODPLUS = array(
		'ids'  => array(
			264, 265, 266, 267, 268, 269, 270, 271, 272, 273,
			128, 257, 127, 126, 220, 130, 253, 131, 129, 254,
			132, 255, 134, 122, 123, 133, 256, 166, 124, 125, 111,
		),
		'rate' => 35.64,
	);

	/** Painted Earth color surcharge (USD). */
	const USD_COLORS_EARTH = array(
		'ids'  => array( 258, 259, 260, 261, 262, 263 ),
		'rate' => 6.87,
	);

	/*
	|--------------------------------------------------------------------------
	| Material ID Constants
	|--------------------------------------------------------------------------
	*/

	const MATERIAL_BIOWOOD       = 6;
	const MATERIAL_BIOWOODPLUS   = 138;
	const MATERIAL_BASSWOODPLUS  = 139;
	const MATERIAL_BASSWOOD      = 147;
	const MATERIAL_EARTH         = 187;

	/** Biowood lock materials: Biowood (6) and BiowoodPlus (138). */
	const BIOWOOD_LOCK_MATERIALS = array( 6, 138 );

	/** Hardcoded lock price for biowood materials (GBP). */
	const BIOWOOD_LOCK_PRICE = 58;

	/*
	|--------------------------------------------------------------------------
	| Control Type ID Constants
	|--------------------------------------------------------------------------
	*/

	const CONTROL_CONCEALED_ROD = 403;
	const CONTROL_HIDDEN_ROD    = 387;

	/*
	|--------------------------------------------------------------------------
	| Hinge Colour ID Constants
	|--------------------------------------------------------------------------
	*/

	const HINGE_STAINLESS_STEEL = 93;
	const HINGE_HIDDEN          = 186;

	/*
	|--------------------------------------------------------------------------
	| Measurement / Fit ID Constants
	|--------------------------------------------------------------------------
	*/

	const FIT_INSIDE = 56;

	/*
	|--------------------------------------------------------------------------
	| Blade Size ID Constants
	|--------------------------------------------------------------------------
	*/

	const BLADE_FLAT_LOUVER = 52;

	/*
	|--------------------------------------------------------------------------
	| Frame Type ID Constants
	|--------------------------------------------------------------------------
	*/

	const FRAMETYPE_BLACKOUT_BLIND = 171;
	const FRAMETYPE_P4008T         = 322;
	const FRAMETYPE_4008T          = 353;
	const FRAMETYPE_P4008W         = 319;

	/*
	|--------------------------------------------------------------------------
	| Buildout Thresholds & Surcharges
	|--------------------------------------------------------------------------
	*/

	/** Maximum depth in mm before the deep buildout surcharge kicks in. */
	const BUILDOUT_DEPTH_THRESHOLD = 100;

	/** Deep buildout surcharge percentage (applied when frame_depth + buildout > threshold). */
	const BUILDOUT_DEEP_PCT = 20;

	/*
	|--------------------------------------------------------------------------
	| Fallback Rates
	|--------------------------------------------------------------------------
	|
	| Used when no user meta override exists for these specific rates.
	| The asymmetric fallback behaviour (user meta formula differs from global)
	| is preserved from the original legacy pricing code.
	|
	*/

	/** G-post fallback percentage when no user meta 'G_post' is set. */
	const G_POST_FALLBACK_PCT = 3;

	/** Tracked fallback rate (GBP per metre) when no user meta 'Tracked' is set. */
	const TRACKED_FALLBACK_RATE = 71;

	/*
	|--------------------------------------------------------------------------
	| Panel & Colour Thresholds
	|--------------------------------------------------------------------------
	*/

	/** Minimum panel width in mm; panels narrower than this trigger a surcharge. */
	const PANEL_WIDTH_MINIMUM = 200;

	/** Blackout blind default colour term ID (no colour surcharge applied). */
	const BLACKOUT_DEFAULT_COLOUR = 390;

	/*
	|--------------------------------------------------------------------------
	| SQM Minimum
	|--------------------------------------------------------------------------
	*/

	/** Minimum SQM value used for pricing. */
	const SQM_MINIMUM = 0.5;

	/*
	|--------------------------------------------------------------------------
	| Style Discount Rates
	|--------------------------------------------------------------------------
	*/

	/**
	 * Style discount rates.
	 *
	 * Style 27 = ALU Panel Only  => 5.45% discount
	 * Style 28 = ALU Fixed       => 3.45% discount
	 */
	const STYLE_DISCOUNT_RATES = array(
		27 => 5.45,
		28 => 3.45,
	);

	/*
	|--------------------------------------------------------------------------
	| Constructor
	|--------------------------------------------------------------------------
	*/

	/**
	 * Construct a PricingConfig instance for a specific user.
	 *
	 * @since 1.0.0
	 * @param int $user_id WordPress user ID for rate lookups.
	 */
	public function __construct( $user_id ) {
		$this->user_id = intval( $user_id );
	}

	/*
	|--------------------------------------------------------------------------
	| Core Rate Resolution Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get a pricing rate with user > global fallback and caching.
	 *
	 * Checks user meta first; if the value is empty or false, falls back to
	 * post meta on post ID 1 (global defaults). The resolved value is cached
	 * in memory so that subsequent calls for the same key within the same
	 * request do not trigger additional DB queries.
	 *
	 * Replaces the pattern:
	 *   if ( get_user_meta( $user_id, 'Key', true ) !== '' ) {
	 *       $val = get_user_meta( $user_id, 'Key', true );
	 *   } else {
	 *       $val = get_post_meta( 1, 'Key', true );
	 *   }
	 *
	 * @since 1.0.0
	 * @param string $key The meta key name (e.g., 'Earth', 'Solid', 'Buildout').
	 * @return float The resolved rate as a float. Returns 0.0 if neither meta exists.
	 */
	public function getRate( $key ) {
		if ( ! isset( $this->cache[ $key ] ) ) {
			$val = get_user_meta( $this->user_id, $key, true );
			if ( $val === '' || $val === false ) {
				$val = get_post_meta( 1, $key, true );
			}
			$this->cache[ $key ] = floatval( $val );
		}
		return $this->cache[ $key ];
	}

	/**
	 * Get a pricing rate as a raw string with user > global fallback and caching.
	 *
	 * Same resolution logic as getRate() but returns the raw string value
	 * without converting to float. Useful for non-numeric meta values or
	 * when the raw string representation is needed.
	 *
	 * @since 1.0.0
	 * @param string $key The meta key name.
	 * @return string The resolved rate as a string. Returns '' if neither meta exists.
	 */
	public function getRateString( $key ) {
		if ( ! isset( $this->string_cache[ $key ] ) ) {
			$val = get_user_meta( $this->user_id, $key, true );
			if ( $val === '' || $val === false ) {
				$val = get_post_meta( 1, $key, true );
			}
			$this->string_cache[ $key ] = ( $val === false ) ? '' : (string) $val;
		}
		return $this->string_cache[ $key ];
	}

	/**
	 * Check whether a rate has a user-level override (not falling back to global).
	 *
	 * Useful for logic that needs to distinguish between user-specific and
	 * global default rates (e.g., Earth material with user 18 tax uplift).
	 *
	 * @since 1.0.0
	 * @param string $key The meta key name.
	 * @return bool True if the user has a non-empty value for this key.
	 */
	public function hasUserRate( $key ) {
		$val = get_user_meta( $this->user_id, $key, true );
		return ( $val !== '' && $val !== false );
	}

	/*
	|--------------------------------------------------------------------------
	| GBP Rate Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the GBP rate for a meta key.
	 *
	 * Alias for getRate(). Provided for semantic clarity when both GBP and USD
	 * rates are used in the same context.
	 *
	 * @since 1.0.0
	 * @param string $key The meta key name (e.g., 'Earth', 'Solid').
	 * @return float The GBP rate.
	 */
	public function getGbpRate( $key ) {
		return $this->getRate( $key );
	}

	/*
	|--------------------------------------------------------------------------
	| Per-Material Rate Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get a per-material pricing rate with 4-level fallback.
	 *
	 * Resolution order:
	 *   Level 1: user_meta( user_id, 'mat_{id}_{key}' )   -- per-material per-user
	 *   Level 2: user_meta( user_id, '{key}' )             -- per-user general
	 *   Level 3: post_meta( 1, 'mat_{id}_{key}' )          -- per-material global
	 *   Level 4: post_meta( 1, '{key}' )                    -- general global
	 *
	 * @since 1.0.0
	 * @param int    $material_id The material term ID (e.g. 137 for Green).
	 * @param string $key         The option key (e.g. 'Solid', 'Buildout').
	 * @return float The resolved rate as a float.
	 */
	public function getPerMaterialRate( $material_id, $key ) {
		$meta_key = 'mat_' . intval( $material_id ) . '_' . $key;
		if ( ! isset( $this->cache[ $meta_key ] ) ) {

			// Level 1: per-material per-user override.
			$val = get_user_meta( $this->user_id, $meta_key, true );
			if ( $val !== '' && $val !== false ) {
				$this->cache[ $meta_key ] = floatval( $val );
				return $this->cache[ $meta_key ];
			}

			// Level 2: per-user general override.
			$user_val = get_user_meta( $this->user_id, $key, true );
			if ( $user_val !== '' && $user_val !== false ) {
				$this->cache[ $meta_key ] = floatval( $user_val );
				return $this->cache[ $meta_key ];
			}

			// Level 3: per-material global default (post ID 1 with mat_ prefix).
			$global_mat_val = get_post_meta( 1, $meta_key, true );
			if ( $global_mat_val !== '' && $global_mat_val !== false ) {
				$this->cache[ $meta_key ] = floatval( $global_mat_val );
				return $this->cache[ $meta_key ];
			}

			// Level 4: general global default (post ID 1 without prefix).
			$this->cache[ $meta_key ] = floatval( get_post_meta( 1, $key, true ) );
		}
		return $this->cache[ $meta_key ];
	}

	/**
	 * Get a per-material USD pricing rate with 4-level fallback.
	 *
	 * Resolution order:
	 *   Level 1: user_meta( user_id, 'mat_{id}_{key}-dolar' )   -- per-material per-user USD
	 *   Level 2: user_meta( user_id, '{key}-dolar' )             -- per-user general USD
	 *   Level 3: post_meta( 1, 'mat_{id}_{key}-dolar' )          -- per-material global USD
	 *   Level 4: post_meta( 1, '{key}-dolar' )                    -- general global USD
	 *
	 * @since 1.0.0
	 * @param int    $material_id The material term ID.
	 * @param string $key         The option key (without '-dolar' suffix).
	 * @return float The resolved USD rate as a float.
	 */
	public function getPerMaterialUsdRate( $material_id, $key ) {
		$meta_key = 'mat_' . intval( $material_id ) . '_' . $key . '-dolar';
		if ( ! isset( $this->cache[ $meta_key ] ) ) {

			// Level 1: per-material per-user override (USD).
			$val = get_user_meta( $this->user_id, $meta_key, true );
			if ( $val !== '' && $val !== false ) {
				$this->cache[ $meta_key ] = floatval( $val );
				return $this->cache[ $meta_key ];
			}

			// Level 2: per-user general override (USD).
			$user_val = get_user_meta( $this->user_id, $key . '-dolar', true );
			if ( $user_val !== '' && $user_val !== false ) {
				$this->cache[ $meta_key ] = floatval( $user_val );
				return $this->cache[ $meta_key ];
			}

			// Level 3: per-material global default, USD (post ID 1 with mat_ prefix and -dolar suffix).
			$global_mat_val = get_post_meta( 1, $meta_key, true );
			if ( $global_mat_val !== '' && $global_mat_val !== false ) {
				$this->cache[ $meta_key ] = floatval( $global_mat_val );
				return $this->cache[ $meta_key ];
			}

			// Level 4: general global default, USD.
			$this->cache[ $meta_key ] = floatval( get_post_meta( 1, $key . '-dolar', true ) );
		}
		return $this->cache[ $meta_key ];
	}

	/**
	 * Check whether a per-material rate override exists in user meta.
	 *
	 * Returns true only if 'mat_{material_id}_{key}' is set and non-empty.
	 * Does NOT fall back to user-level or global rates.
	 *
	 * @since 1.0.0
	 * @param int    $material_id The material term ID.
	 * @param string $key         The option key.
	 * @return bool True if a per-material override exists.
	 */
	public function hasPerMaterialRate( $material_id, $key ) {
		$meta_key = 'mat_' . intval( $material_id ) . '_' . $key;
		$val      = get_user_meta( $this->user_id, $meta_key, true );
		return ( $val !== '' && $val !== false );
	}

	/*
	|--------------------------------------------------------------------------
	| USD Rate Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the USD rate for a meta key.
	 *
	 * Automatically appends '-dolar' to the key. Replaces the dolarSum() helper
	 * and the direct get_user_meta( $user_id, 'Key-dolar', true ) pattern.
	 *
	 * @since 1.0.0
	 * @param string $key The base meta key name (e.g., 'Earth' resolves to 'Earth-dolar').
	 * @return float The USD rate.
	 */
	public function getUsdRate( $key ) {
		return $this->getRate( $key . '-dolar' );
	}

	/*
	|--------------------------------------------------------------------------
	| Tax Rate Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the tax rate for a material.
	 *
	 * Automatically appends '_tax' to the key. Used for the user 18 (tax uplift)
	 * calculation pattern where tax is added on top of the base material rate.
	 *
	 * @since 1.0.0
	 * @param string $material_key The material meta key (e.g., 'Earth' resolves to 'Earth_tax').
	 * @return float The tax rate percentage (e.g., 10.0 for 10%).
	 */
	public function getTaxRate( $material_key ) {
		return $this->getRate( $material_key . '_tax' );
	}

	/*
	|--------------------------------------------------------------------------
	| Material Convenience Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the GBP rate for a material by its meta key name.
	 *
	 * Convenience wrapper -- functionally identical to getRate() but makes
	 * the intent clearer when specifically fetching a material base rate.
	 *
	 * @since 1.0.0
	 * @param string $material_name The material meta key (e.g., 'Earth', 'Basswood').
	 * @return float The material GBP rate per SQM.
	 */
	public function getMaterialRate( $material_name ) {
		return $this->getRate( $material_name );
	}

	/**
	 * Get the USD rate for a material by its meta key name.
	 *
	 * Convenience wrapper -- appends '-dolar' to the material name.
	 *
	 * @since 1.0.0
	 * @param string $material_name The material meta key (e.g., 'Earth' resolves to 'Earth-dolar').
	 * @return float The material USD rate per SQM.
	 */
	public function getMaterialUsdRate( $material_name ) {
		return $this->getUsdRate( $material_name );
	}

	/**
	 * Get the material meta key name for a given term ID.
	 *
	 * Resolves a material term ID (e.g., 187) to its meta key (e.g., 'Earth')
	 * using the MATERIALS constant.
	 *
	 * @since 1.0.0
	 * @param int $material_id The material term ID.
	 * @return string|false The material meta key name, or false if not found.
	 */
	public function getMaterialKey( $material_id ) {
		$material_id = intval( $material_id );
		if ( isset( self::MATERIALS[ $material_id ] ) ) {
			return self::MATERIALS[ $material_id ]['key'];
		}
		return false;
	}

	/*
	|--------------------------------------------------------------------------
	| Discount Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the user's custom discount rate.
	 *
	 * Returns the 'discount_custom' user meta value (with global fallback).
	 * Used in the pricing calculation to subtract a percentage from the basic price.
	 *
	 * @since 1.0.0
	 * @return float The custom discount percentage (e.g., 5.0 for 5%).
	 */
	public function getDiscountCustom() {
		return $this->getRate( 'discount_custom' );
	}

	/**
	 * Look up the style discount rate for a given style term ID.
	 *
	 * Uses the STYLE_DISCOUNT_RATES constant.
	 *
	 * @since 1.0.0
	 * @param int $style_id The property_style term ID.
	 * @return float Discount percentage (e.g., 5.45) or 0.0 if no discount.
	 */
	public function getStyleDiscount( $style_id ) {
		$style_id = intval( $style_id );
		if ( isset( self::STYLE_DISCOUNT_RATES[ $style_id ] ) ) {
			return self::STYLE_DISCOUNT_RATES[ $style_id ];
		}
		return 0.0;
	}

	/*
	|--------------------------------------------------------------------------
	| User Flag Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Check if this user has the tax uplift flag.
	 *
	 * Generalises the hardcoded user ID 18 exception found throughout the AJAX
	 * pricing handlers. Maintains backward compatibility with user 18 while
	 * allowing any user to be flagged via 'has_tax_uplift' user meta.
	 *
	 * The tax uplift adds the material _tax percentage on top of the base price
	 * (e.g., $sum = ($sqm * $rate) + (($sqm * $rate) * $tax_rate) / 100).
	 *
	 * @since 1.0.0
	 * @return bool True if the user should have tax applied to material rates.
	 */
	public function hasTaxUplift() {
		// Check the new user meta flag first.
		$flag = get_user_meta( $this->user_id, 'has_tax_uplift', true );

		// Backward compatibility: user 18 always has tax uplift.
		return $flag === 'yes' || $flag === '1' || $this->user_id === 18;
	}

	/**
	 * Check if this user has sea delivery configured.
	 *
	 * Returns true if the 'SeaDelivery' user meta has a non-empty value.
	 * The actual sea delivery amount is retrieved via getRate( 'SeaDelivery' ).
	 *
	 * @since 1.0.0
	 * @return bool True if the user has a sea delivery surcharge.
	 */
	public function hasSeaDelivery() {
		return ( get_user_meta( $this->user_id, 'SeaDelivery', true ) !== '' );
	}

	/**
	 * Get the sea delivery surcharge amount.
	 *
	 * Returns the 'SeaDelivery' user meta value as a float. This is a flat
	 * amount added to the total (not a percentage).
	 *
	 * @since 1.0.0
	 * @return float The sea delivery surcharge amount, or 0.0 if not set.
	 */
	public function getSeaDelivery() {
		return $this->getRate( 'SeaDelivery' );
	}

	/*
	|--------------------------------------------------------------------------
	| Accessor Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the user ID this config instance was created for.
	 *
	 * @since 1.0.0
	 * @return int The WordPress user ID.
	 */
	public function getUserId() {
		return $this->user_id;
	}

	/*
	|--------------------------------------------------------------------------
	| Cache Management
	|--------------------------------------------------------------------------
	*/

	/**
	 * Pre-warm the cache with multiple keys in a single call.
	 *
	 * Useful for pre-loading all material rates at once before the pricing
	 * calculation loop to minimise scattered DB queries.
	 *
	 * @since 1.0.0
	 * @param array $keys Array of meta key names to pre-load.
	 * @return void
	 */
	public function preloadRates( $keys ) {
		foreach ( $keys as $key ) {
			$this->getRate( $key );
		}
	}

	/**
	 * Clear the in-memory cache.
	 *
	 * Useful if user meta is updated mid-request and rates need to be
	 * re-fetched from the database.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function clearCache() {
		$this->cache        = array();
		$this->string_cache = array();
	}

	/**
	 * Get the current cache contents (for debugging).
	 *
	 * @since 1.0.0
	 * @return array<string, float> The current numeric cache.
	 */
	public function getCache() {
		return $this->cache;
	}
}
