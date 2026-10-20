<?php
/**
 * Per-material rate resolution test (no database).
 *
 * Runs the real PricingConfig / PricingCalculator / PricingResult classes
 * against in-memory user meta and post meta (get_user_meta() and
 * get_post_meta() are stubbed over arrays), and checks:
 *
 *  - L1-L4 resolution order for a % key (Solid) and a fixed key (French_Door);
 *  - G_post: L3, L4, 3% fallback, explicit '0';
 *  - Tracked extra tracks: L3, L4, 71 fallback;
 *  - USD Inside / G_post / Colors (configured rate and hardcoded fallback);
 *  - Lock for Biowood: L1, L3, fixed 58 (generic L2/L4 ignored);
 *  - 4008T in USD;
 *  - rates_used snapshot (single and multi-section) and price_item_rates save;
 *  - negative stored rates treated as 0;
 *  - the items.php display helpers (snapshot first, then current resolution).
 *
 * WordPress is NOT loaded. Run from the command line only:
 *   php wp-content/plugins/shutter-module/tests/per-material-resolution.php
 *
 * Exit code 0 when every check passes, 1 otherwise.
 *
 * @package ShutterModule
 * @since   1.1.1
 */

if ( php_sapi_name() !== 'cli' ) {
	exit;
}

define( 'ABSPATH', __DIR__ . '/' );

/*
|--------------------------------------------------------------------------
| WordPress stubs (in-memory meta)
|--------------------------------------------------------------------------
*/

$GLOBALS['test_user_meta']  = array();
$GLOBALS['test_post_meta']  = array();
$GLOBALS['test_meta_saved'] = array();

/**
 * Stub of get_user_meta(): reads $GLOBALS['test_user_meta'].
 *
 * @param int    $user_id User ID.
 * @param string $key     Meta key.
 * @param bool   $single  Ignored (always single).
 * @return mixed Stored value or '' when missing, like WordPress.
 */
function get_user_meta( $user_id, $key = '', $single = false ) {
	return isset( $GLOBALS['test_user_meta'][ $user_id ][ $key ] ) ? $GLOBALS['test_user_meta'][ $user_id ][ $key ] : '';
}

/**
 * Stub of get_post_meta(): reads $GLOBALS['test_post_meta'].
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param bool   $single  Ignored (always single).
 * @return mixed Stored value or '' when missing, like WordPress.
 */
function get_post_meta( $post_id, $key = '', $single = false ) {
	return isset( $GLOBALS['test_post_meta'][ $post_id ][ $key ] ) ? $GLOBALS['test_post_meta'][ $post_id ][ $key ] : '';
}

/**
 * Stub of update_post_meta(): writes $GLOBALS['test_post_meta'].
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param mixed  $value   Value.
 * @return bool Always true.
 */
function update_post_meta( $post_id, $key, $value ) {
	$GLOBALS['test_post_meta'][ $post_id ][ $key ] = $value;
	$GLOBALS['test_meta_saved'][]                 = array( $post_id, $key );
	return true;
}

// Any PHP warning/notice is a failure: in production it lands before the JSON response.
set_error_handler(
	function ( $errno, $errstr, $errfile, $errline ) {
		if ( E_DEPRECATED === $errno || E_USER_DEPRECATED === $errno ) {
			return true;
		}
		throw new ErrorException( $errstr . ' in ' . $errfile . ':' . $errline, 0, $errno, $errfile, $errline );
	}
);

$includes = dirname( __DIR__ ) . '/includes/';
require_once $includes . 'pricing-helpers.php';
require_once $includes . 'pricing-maps.php';
require_once $includes . 'class-pricing-config.php';
require_once $includes . 'class-pricing-result.php';
require_once $includes . 'class-pricing-calculator.php';
require_once $includes . 'class-product-handler.php';

/*
|--------------------------------------------------------------------------
| Test helpers
|--------------------------------------------------------------------------
*/

const TEST_USER = 500;

$GLOBALS['test_pass'] = 0;
$GLOBALS['test_fail'] = 0;

/**
 * Reset meta to the baseline: Basswood/Biowood/Ecowood/Earth at 100 GBP and 50 USD per SQM.
 *
 * @return void
 */
function test_reset() {
	$GLOBALS['test_user_meta'] = array( TEST_USER => array() );
	$GLOBALS['test_post_meta'] = array(
		1 => array(
			'Basswood'       => '100',
			'Basswood-dolar' => '50',
			'Biowood'        => '100',
			'Biowood-dolar'  => '50',
			'Ecowood'        => '100',
			'Ecowood-dolar'  => '50',
			'Earth'          => '100',
			'Earth-dolar'    => '50',
		),
	);
}

/**
 * Set a user meta value for the test user.
 *
 * @param string $key   Meta key.
 * @param string $value Value.
 * @return void
 */
function test_user( $key, $value ) {
	$GLOBALS['test_user_meta'][ TEST_USER ][ $key ] = $value;
}

/**
 * Set a global (post ID 1) meta value.
 *
 * @param string $key   Meta key.
 * @param string $value Value.
 * @return void
 */
function test_global( $key, $value ) {
	$GLOBALS['test_post_meta'][1][ $key ] = $value;
}

/**
 * Build a product array with every field the calculator reads unguarded.
 *
 * Default: Basswood, 1 SQM, style 29 (no style surcharge), layout LR, 1000 mm wide.
 *
 * @param array $overrides Fields to override.
 * @return array
 */
function test_product( array $overrides = array() ) {
	return array_merge(
		array(
			'property_material'     => '147',
			'property_total'        => '1',
			'property_width'        => '1000',
			'property_height'       => '1000',
			'property_style'        => '29',
			'property_frametype'    => '0',
			'property_fit'          => '0',
			'property_controltype'  => '0',
			'property_hingecolour'  => '0',
			'property_bladesize'    => '0',
			'property_builtout'     => '',
			'property_shuttercolour' => '0',
			'property_layoutcode'   => 'LR',
			'property_nr_sections'  => '',
			'property_tracksnumber' => '',
			'property_ringpull_volume' => '0',
			'panels_left_right'     => '0',
		),
		$overrides
	);
}

/**
 * Run the calculator for the test user.
 *
 * @param array $overrides Product overrides.
 * @return PricingResult
 */
function test_calc( array $overrides = array() ) {
	$calculator = new PricingCalculator();
	return $calculator->calculate( test_product( $overrides ), new PricingConfig( TEST_USER ) );
}

/**
 * Compare two numbers with a small tolerance and record the result.
 *
 * @param string $label    Check description.
 * @param float  $expected Expected value.
 * @param float  $actual   Actual value.
 * @return void
 */
function test_eq( $label, $expected, $actual ) {
	if ( is_numeric( $expected ) && is_numeric( $actual ) && abs( (float) $expected - (float) $actual ) < 0.0001 ) {
		$GLOBALS['test_pass']++;
		echo "  ok    {$label}\n";
		return;
	}
	if ( ! is_numeric( $expected ) && $expected === $actual ) {
		$GLOBALS['test_pass']++;
		echo "  ok    {$label}\n";
		return;
	}
	$GLOBALS['test_fail']++;
	echo "  FAIL  {$label}: expected " . var_export( $expected, true ) . ', got ' . var_export( $actual, true ) . "\n";
}

/**
 * Record a boolean check.
 *
 * @param string $label     Check description.
 * @param bool   $condition Condition that must be true.
 * @return void
 */
function test_true( $label, $condition ) {
	test_eq( $label, true, (bool) $condition );
}

/*
|--------------------------------------------------------------------------
| 1. L1-L4 order, % key (Solid, style 221)
|--------------------------------------------------------------------------
*/
echo "1. Solid % (L4 -> L3 -> L2 -> L1)\n";
test_reset();
$solid = array( 'property_style' => '221' );
test_eq( 'no rate anywhere => 100', 100, test_calc( $solid )->gbp_price );
test_global( 'Solid', '5' );
test_eq( 'L4 Solid=5 => 105', 105, test_calc( $solid )->gbp_price );
test_global( 'mat_147_Solid', '7' );
test_eq( 'L3 mat_147_Solid=7 beats L4 => 107', 107, test_calc( $solid )->gbp_price );
test_user( 'Solid', '9' );
test_eq( 'L2 Solid=9 beats L3 => 109', 109, test_calc( $solid )->gbp_price );
test_user( 'mat_147_Solid', '11' );
$r = test_calc( $solid );
test_eq( 'L1 mat_147_Solid=11 beats L2 => 111', 111, $r->gbp_price );
test_eq( 'rates_used[Solid] = 11', 11, $r->rates_used['Solid'] );
test_eq( 'other material (188) ignores mat_147 => L2 9', 109, test_calc( array_merge( $solid, array( 'property_material' => '188' ) ) )->gbp_price );

/*
|--------------------------------------------------------------------------
| 2. L1-L4 order, fixed key (French_Door, style 34)
|--------------------------------------------------------------------------
*/
echo "2. French_Door + (fixed amount)\n";
test_reset();
$fd = array( 'property_style' => '34' );
test_global( 'French_Door', '50' );
test_eq( 'L4 50 => 150', 150, test_calc( $fd )->gbp_price );
test_global( 'mat_147_French_Door', '60' );
test_eq( 'L3 60 => 160', 160, test_calc( $fd )->gbp_price );
test_user( 'French_Door', '70' );
test_eq( 'L2 70 => 170', 170, test_calc( $fd )->gbp_price );
test_user( 'mat_147_French_Door', '80' );
test_eq( 'L1 80 => 180', 180, test_calc( $fd )->gbp_price );

/*
|--------------------------------------------------------------------------
| 3. G_post (property_g1 set)
|--------------------------------------------------------------------------
*/
echo "3. G_post\n";
test_reset();
$gp = array( 'property_g1' => '500' );
$r  = test_calc( $gp );
test_eq( 'nothing configured => 3% fallback => 103', 103, $r->gbp_price );
test_eq( 'rates_used[G_post] = 3 (fallback)', 3, $r->rates_used['G_post'] );
test_global( 'G_post', '4' );
test_eq( 'L4 G_post=4 => 104', 104, test_calc( $gp )->gbp_price );
test_global( 'mat_147_G_post', '5' );
test_eq( 'L3 mat_147_G_post=5 => 105', 105, test_calc( $gp )->gbp_price );
test_user( 'G_post', '6' );
test_eq( 'L2 G_post=6 => 106', 106, test_calc( $gp )->gbp_price );
test_user( 'mat_147_G_post', '7' );
test_eq( 'L1 mat_147_G_post=7 => 107', 107, test_calc( $gp )->gbp_price );
test_reset();
test_global( 'G_post', '0' );
test_eq( "L4 G_post='0' is configured => no fallback => 100", 100, test_calc( $gp )->gbp_price );

/*
|--------------------------------------------------------------------------
| 4. Tracked extra tracks (style 37, 4 tracks, 2000 mm => 2 extra x 2 m)
|--------------------------------------------------------------------------
*/
echo "4. Tracked extra tracks\n";
test_reset();
$tr = array(
	'property_style'        => '37',
	'property_tracksnumber' => '4',
	'property_width'        => '2000',
);
$r = test_calc( $tr );
test_eq( 'nothing configured => 71 x 2 x 2 = 284 => 384', 384, $r->gbp_price );
test_eq( 'rates_used[Tracked] = 71 (fallback)', 71, $r->rates_used['Tracked'] );
test_global( 'Tracked', '30' );
test_eq( 'L4 Tracked=30 => 30 x 4 = 120 => 220', 220, test_calc( $tr )->gbp_price );
test_global( 'mat_147_Tracked', '40' );
test_eq( 'L3 mat_147_Tracked=40 => 160 => 260', 260, test_calc( $tr )->gbp_price );
test_user( 'mat_147_Tracked', '10' );
test_eq( 'L1 mat_147_Tracked=10 => 40 => 140', 140, test_calc( $tr )->gbp_price );

/*
|--------------------------------------------------------------------------
| 5. USD Inside / G_post / Colors
|--------------------------------------------------------------------------
*/
echo "5. USD Inside, G_post, Colors\n";
test_reset();
$inside = array( 'property_fit' => '56' );
test_eq( 'Inside USD, nothing configured => 50 (unchanged)', 50, test_calc( $inside )->usd_price );
test_global( 'mat_147_Inside-dolar', '10' );
$r = test_calc( $inside );
test_eq( 'Inside USD L3 10% => 55', 55, $r->usd_price );
test_eq( 'rates_used[Inside-dolar] = 10', 10, $r->rates_used['Inside-dolar'] );
test_user( 'Inside-dolar', '20' );
test_eq( 'Inside USD L2 20% beats L3 => 60', 60, test_calc( $inside )->usd_price );

test_reset();
test_eq( 'G_post USD, nothing configured => 50 (no USD fallback)', 50, test_calc( $gp )->usd_price );
test_global( 'G_post-dolar', '6' );
test_eq( 'G_post USD L4 6% => 53', 53, test_calc( $gp )->usd_price );
test_user( 'mat_147_G_post-dolar', '8' );
test_eq( 'G_post USD L1 8% => 54', 54, test_calc( $gp )->usd_price );

test_reset();
$col_bass = array( 'property_shuttercolour' => '128' );
$r        = test_calc( $col_bass );
test_eq( 'Basswood stained USD, not configured => hardcoded 14.57%', 50 * 1.1457, $r->usd_price );
test_eq( 'rates_used[Colors-dolar] = 14.57', 14.57, $r->rates_used['Colors-dolar'] );
test_user( 'mat_147_Colors-dolar', '20' );
test_eq( 'Basswood stained USD, L1 20% => 60', 60, test_calc( $col_bass )->usd_price );
test_reset();
test_global( 'Colors-dolar', '12' );
test_eq( 'Basswood stained USD, generic L4 12% ignored => hardcoded 14.57%', 50 * 1.1457, test_calc( $col_bass )->usd_price );
test_user( 'Colors-dolar', '9' );
test_eq( 'Basswood stained USD, generic L2 9% ignored => hardcoded 14.57%', 50 * 1.1457, test_calc( $col_bass )->usd_price );
test_global( 'mat_147_Colors-dolar', '16' );
test_eq( 'Basswood stained USD, L3 16% beats generic L2/L4 => 58', 58, test_calc( $col_bass )->usd_price );

test_reset();
$col_bio = array(
	'property_material'      => '138',
	'property_shuttercolour' => '264',
);
test_global( 'BiowoodPlus', '100' );
test_global( 'BiowoodPlus-dolar', '50' );
test_eq( 'BiowoodPlus brushed USD, not configured => 35.64%', 50 * 1.3564, test_calc( $col_bio )->usd_price );
test_global( 'mat_138_Colors-dolar', '30' );
test_eq( 'BiowoodPlus brushed USD, L3 30% => 65', 65, test_calc( $col_bio )->usd_price );

test_reset();
$col_earth = array(
	'property_material'      => '187',
	'property_shuttercolour' => '258',
);
test_eq( 'Earth painted USD, not configured => 6.87%', 50 * 1.0687, test_calc( $col_earth )->usd_price );
test_user( 'mat_187_Colors-dolar', '4' );
test_eq( 'Earth painted USD, L1 4% => 52', 52, test_calc( $col_earth )->usd_price );

test_reset();
$col_eco = array(
	'property_material'      => '188',
	'property_shuttercolour' => '128',
);
test_eq( 'Ecowood colour 128 USD, not configured => no USD colour charge', 50, test_calc( $col_eco )->usd_price );
test_global( 'Colors-dolar', '12' );
test_eq( 'Ecowood colour 128 USD, generic L4 only => no USD colour charge', 50, test_calc( $col_eco )->usd_price );
test_global( 'mat_188_Colors-dolar', '8' );
test_eq( 'Ecowood colour 128 USD, L3 8% => 54', 54, test_calc( $col_eco )->usd_price );
test_eq( 'Ecowood colour 999 (not a GBP Colors colour) => no USD colour charge', 50, test_calc( array_merge( $col_eco, array( 'property_shuttercolour' => '999' ) ) )->usd_price );

/*
|--------------------------------------------------------------------------
| 6. Lock
|--------------------------------------------------------------------------
*/
echo "6. Lock (Biowood 6 / BiowoodPlus 138)\n";
test_reset();
$lock_bio = array(
	'property_material' => '6',
	'property_locks'    => 'Yes',
);
test_global( 'Lock', '35' );
test_user( 'Lock', '36' );
$r = test_calc( $lock_bio );
test_eq( 'Biowood, generic L2/L4 Lock ignored => fixed 58 => 158', 158, $r->gbp_price );
test_true( 'rates_used has no GBP Lock for the fixed price', ! array_key_exists( 'Lock', $r->rates_used ) );
test_global( 'mat_6_Lock', '30' );
$r = test_calc( $lock_bio );
test_eq( 'Biowood, L3 mat_6_Lock=30 => 30 x 2 = 60 => 160', 160, $r->gbp_price );
test_eq( 'rates_used[Lock] = 30', 30, $r->rates_used['Lock'] );
test_user( 'mat_6_Lock', '25' );
test_eq( 'Biowood, L1 mat_6_Lock=25 => 50 => 150', 150, test_calc( $lock_bio )->gbp_price );
test_eq( 'Biowood, L1 + horizontal T-post => 25 x 2 x 2 => 200', 200, test_calc( array_merge( $lock_bio, array( 'property_horizontaltpost' => 'Yes' ) ) )->gbp_price );
test_reset();
test_eq( 'Biowood fixed price + horizontal T-post => 58 x 2 => 216', 216, test_calc( array_merge( $lock_bio, array( 'property_horizontaltpost' => 'Yes' ) ) )->gbp_price );
test_global( 'mat_138_Lock', '20' );
test_global( 'BiowoodPlus', '100' );
test_eq( 'BiowoodPlus, L3 mat_138_Lock=20 => 140', 140, test_calc( array_merge( $lock_bio, array( 'property_material' => '138' ) ) )->gbp_price );
test_reset();
test_global( 'Lock-dolar', '10' );
test_eq( 'Biowood USD Lock unchanged: Lock-dolar x 2 => 70', 70, test_calc( $lock_bio )->usd_price );
test_reset();
test_global( 'Lock', '35' );
test_eq( 'Basswood default Lock: 35 x 2 => 170', 170, test_calc( array( 'property_locks' => 'Yes' ) )->gbp_price );

/*
|--------------------------------------------------------------------------
| 7. 4008T (frame type 353)
|--------------------------------------------------------------------------
*/
echo "7. 4008T GBP and USD\n";
test_reset();
$f4008t = array( 'property_frametype' => '353' );
test_global( '4008T', '10' );
test_global( 'P4008T', '99' );
test_global( '4008T-dolar', '5' );
$r = test_calc( $f4008t );
test_eq( 'GBP 4008T uses key 4008T (not P4008T) => 110', 110, $r->gbp_price );
test_eq( 'USD L4 4008T-dolar=5 => 52.5', 52.5, $r->usd_price );
test_user( 'mat_147_4008T-dolar', '7' );
$r = test_calc( $f4008t );
test_eq( 'USD L1 mat_147_4008T-dolar=7 => 53.5', 53.5, $r->usd_price );
test_eq( 'rates_used[4008T-dolar] = 7', 7, $r->rates_used['4008T-dolar'] );
test_eq( 'rates_used[4008T] = 10', 10, $r->rates_used['4008T'] );

/*
|--------------------------------------------------------------------------
| 8. Snapshot (rates_used, multi-section, price_item_rates)
|--------------------------------------------------------------------------
*/
echo "8. Rates snapshot\n";
test_reset();
test_global( 'Solid', '5' );
test_global( 'Solid-dolar', '4' );
$r = test_calc( array( 'property_style' => '221' ) );
test_eq( 'rates_used[Solid] = 5', 5, $r->rates_used['Solid'] );
test_eq( 'rates_used[Solid-dolar] = 4', 4, $r->rates_used['Solid-dolar'] );
test_true( 'rates_used has no key for an option not used (Inside)', ! array_key_exists( 'Inside', $r->rates_used ) );

$multi = test_product(
	array(
		'property_style'         => '221',
		'property_total'         => '2',
		'property_nr_sections'   => '2',
		'property_total_section1' => '1',
		'property_total_section2' => '1',
		'property_layoutcode1'   => 'L',
		'property_layoutcode2'   => 'R',
		'property_fit'           => '56',
	)
);
test_global( 'Inside', '2' );
$calculator = new PricingCalculator();
$agg        = $calculator->calculateMultiSection( $multi, new PricingConfig( TEST_USER ) );
test_eq( 'multi-section: 2 x (100 + 5% + 2%) => 214', 214, $agg->gbp_price );
test_eq( 'multi-section rates_used[Solid] = 5', 5, $agg->rates_used['Solid'] );
test_eq( 'multi-section rates_used[Inside] = 2', 2, $agg->rates_used['Inside'] );

$handler = new ProductHandler();
$method  = new ReflectionMethod( 'ProductHandler', 'saveRatesSnapshot' );
if ( PHP_VERSION_ID < 80100 ) {
	$method->setAccessible( true );
}
$method->invoke( $handler, 9001, $agg );
test_eq( 'ProductHandler saves price_item_rates[Solid]', 5, $GLOBALS['test_post_meta'][9001]['price_item_rates']['Solid'] );

/*
|--------------------------------------------------------------------------
| 9. Negative rates => 0
|--------------------------------------------------------------------------
*/
echo "9. Negative rates\n";
test_reset();
test_user( 'mat_147_Solid', '-5' );
test_global( 'Solid', '5' );
$r = test_calc( array( 'property_style' => '221' ) );
test_eq( 'L1 Solid=-5 => 0 (no discount) => 100', 100, $r->gbp_price );
test_eq( 'rates_used[Solid] = 0', 0, $r->rates_used['Solid'] );
test_user( 'mat_147_Solid-dolar', '-10' );
test_eq( 'USD L1 Solid-dolar=-10 => 0 => 50', 50, test_calc( array( 'property_style' => '221' ) )->usd_price );
test_reset();
test_global( 'mat_6_Lock', '-30' );
test_eq( 'Biowood mat_6_Lock=-30 => 0 lock charge => 100', 100, test_calc( $lock_bio )->gbp_price );
$config = new PricingConfig( TEST_USER );
test_eq( 'getPerMaterialUsdRate never negative', 0, $config->getPerMaterialUsdRate( 147, 'Nope' ) );

/*
|--------------------------------------------------------------------------
| 10. items.php display helpers
|--------------------------------------------------------------------------
*/
echo "10. items.php display helpers\n";
$items_src = file_get_contents( dirname( dirname( dirname( __DIR__ ) ) ) . '/themes/storefront-child/views/table/items.php' );
$helpers   = '';
foreach ( array( 'items_get_display_rate', 'items_get_biowood_lock_rate' ) as $fn ) {
	if ( preg_match( '/^function ' . $fn . '\(.*?^\}$/ms', $items_src, $m ) ) {
		$helpers .= $m[0] . "\n";
	}
}
test_true( 'both helpers found in items.php', substr_count( $helpers, 'function items_' ) === 2 );
eval( $helpers ); // phpcs:ignore Squiz.PHP.Eval.Discouraged -- test-only: loads two functions from a template.

test_reset();
test_global( 'Bay_Angle', '10' );
test_global( 'mat_147_Bay_Angle', '15' );
test_eq( 'no snapshot => current L3 15', '15', items_get_display_rate( TEST_USER, 147, 'Bay_Angle', 7001 ) );
test_user( 'Bay_Angle', '12' );
test_eq( 'no snapshot => current L2 12 beats L3', '12', items_get_display_rate( TEST_USER, 147, 'Bay_Angle', 7001 ) );
$GLOBALS['test_post_meta'][7001]['price_item_rates'] = array( 'Bay_Angle' => 10.0 );
test_eq( 'snapshot wins => 10', 10, items_get_display_rate( TEST_USER, 147, 'Bay_Angle', 7001 ) );
test_eq( 'key missing from snapshot => current resolution (L4 Solid 0 => "")', '', items_get_display_rate( TEST_USER, 147, 'Solid', 7001 ) );
test_eq( 'no product id => current resolution', '12', items_get_display_rate( TEST_USER, 147, 'Bay_Angle' ) );

test_global( 'Lock', '35' );
test_eq( 'Biowood helper, nothing per-material => "" (fixed 58)', '', items_get_biowood_lock_rate( TEST_USER, 6, 7002 ) );
test_global( 'mat_6_Lock', '30' );
test_eq( 'Biowood helper, L3 => 30', '30', items_get_biowood_lock_rate( TEST_USER, 6, 7002 ) );
test_user( 'mat_6_Lock', '25' );
test_eq( 'Biowood helper, L1 => 25', '25', items_get_biowood_lock_rate( TEST_USER, 6, 7002 ) );
$GLOBALS['test_post_meta'][7002]['price_item_rates'] = array( 'Solid' => 5.0 );
test_eq( 'Biowood helper, snapshot without Lock => "" (fixed 58 was charged)', '', items_get_biowood_lock_rate( TEST_USER, 6, 7002 ) );
$GLOBALS['test_post_meta'][7002]['price_item_rates'] = array( 'Lock' => 22.0 );
test_eq( 'Biowood helper, snapshot Lock => 22', 22, items_get_biowood_lock_rate( TEST_USER, 6, 7002 ) );

/*
|--------------------------------------------------------------------------
| Summary
|--------------------------------------------------------------------------
*/
echo "\n" . $GLOBALS['test_pass'] . ' passed, ' . $GLOBALS['test_fail'] . " failed\n";
exit( $GLOBALS['test_fail'] > 0 ? 1 : 0 );
