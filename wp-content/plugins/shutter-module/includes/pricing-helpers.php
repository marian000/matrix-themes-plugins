<?php
/**
 * Shared pricing helper functions.
 *
 * Used by ajax-prod.php, ajax-prod-update.php, and their individual variants.
 * Extracted in Task #019 to eliminate code duplication across the four pricing
 * AJAX handlers.
 *
 * @package ShutterModule
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure pricing maps are available (Task #020)
require_once __DIR__ . '/pricing-maps.php';

// Ensure pricing constants are available (Task #021)
require_once __DIR__ . '/pricing-constants.php';

/**
 * Calculate SQM per section for Blackout Blind (frametype 171).
 *
 * Parses the layout code to determine panel widths at each T-post, B-post,
 * and C-post position, then returns the SQM for each resulting section.
 *
 * @since 1.0.0
 * @param array $products The parsed product properties array.
 * @return array Indexed array of SQM values (as formatted strings) per section.
 */
if ( ! function_exists( 'blackoutSqms' ) ) {
	function blackoutSqms($products)
	{
		$layout_test = $products['property_layoutcode'];
		$arrLay = str_split($layout_test);
		$t = 0;
		$b = 0;
		$c = 0;
		$width = floatval($products['property_width']);
		$height = floatval($products['property_height']);
		$current_point = 0;
		$last_point = 0;
		$panels = array();
		$count_layout = 0;
		foreach ($arrLay as $k => $l) {
			$count_layout++;
			if ($l === 'T' || $l === 't') {
				$t++;
				$current_point = $products['property_t' . $t];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($l === 'b' || $l === 'B') {
				$b++;
				$current_point = $products['property_bp' . $b];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($l === 'C' || $l === 'c') {
				$c++;
				$current_point = $products['property_c' . $c];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}

			if ($count_layout == strlen($layout_test)) {
				$panels[] = $width - $current_point;
			}
		}
		$sqm_array = array();
		foreach ($panels as $k => $w) {
			$sqm_array[] = number_format((($w * $height) / 1000000), 2);
		}
		return $sqm_array;
	}
}

/**
 * Count L and R panels in a layout code string.
 *
 * Counts occurrences of 'L'/'l' and 'R'/'r' characters in the layout code
 * to determine the total number of openable panels.
 *
 * @since 1.0.0
 * @param string $layout_code The layout code string (e.g., "LTRL").
 * @return int Total number of L + R panels.
 */
if ( ! function_exists( 'nrPanelsCount' ) ) {
	function nrPanelsCount($layout_code)
	{
		$freq = array('l' => 0, 'r' => 0);
		$word = $layout_code;
		$len = strlen($word);
		for ($i = 0; $i < $len; $i++) {
			$letter = strtolower($word[$i]);
			if (array_key_exists($letter, $freq)) {
				$freq[$letter]++;
			}
		}
		$nrPanels = $freq['l'] + $freq['r'];
		return $nrPanels;
	}
}

/**
 * Calculate individual panel widths from a layout code.
 *
 * Parses the layout code to determine where T-posts, B-posts, and C-posts
 * divide the total width, returning the width of each resulting panel section.
 *
 * @since 1.0.0
 * @param string $layout_code The layout code string.
 * @param float  $width       The total opening width in mm.
 * @param array  $products    The parsed product properties array (contains property_t*, property_bp*, property_c* positions).
 * @return array Indexed array of panel widths in mm.
 */
if ( ! function_exists( 'calculatePanelsWidth' ) ) {
	function calculatePanelsWidth($layout_code, $width, $products)
	{
		$panels = array();
		$layout_test = $layout_code;
		$arrLay = str_split($layout_test);
		$t = 0;
		$b = 0;
		$c = 0;
		$l = 0;
		$r = 0;
		$current_point = 0;
		$last_point = 0;
		$count_layout = 0;
		foreach ($arrLay as $k => $let) {
			$count_layout++;
			if ($let === 'T' || $let === 't') {
				$t++;
				$current_point = $products['property_t' . $t];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($let === 'b' || $let === 'B') {
				$b++;
				$current_point = $products['property_bp' . $b];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			if ($let === 'C' || $let === 'c') {
				$c++;
				$current_point = $products['property_c' . $c];
				$panels[] = $current_point - $last_point;
				$last_point = $current_point;
			}
			// if L or R
			if ($count_layout == strlen($layout_test)) {
				$panels[] = $width - $current_point;
			}
		}
		return $panels;
	}
}

/**
 * Count the number of T-post characters in a layout code.
 *
 * Counts occurrences of 'T' or 't' in the layout code string to determine
 * the number of T-post divisions.
 *
 * @since 1.0.0
 * @param string $layout_code The layout code string.
 * @return int Number of T-post characters found.
 */
if ( ! function_exists( 'countT' ) ) {
	function countT($layout_code)
	{
		// Initialize a counter for 'T' or 't'
		$t_count = 0;
		// Convert the layout code string into an array of characters
		$layout_characters = str_split($layout_code);

		// Iterate through each character in the layout code
		foreach ($layout_characters as $char) {
			// Increment the counter if the character is 'T' or 't'
			if (strtoupper($char) === 'T') {
				$t_count++;
			}
		}

		// Return the count of 'T' or 't' characters
		return $t_count;
	}
}

/**
 * Count the number of active midrails and dividers.
 *
 * Returns a count starting at 1 (base), incrementing for each active
 * midrail or divider position that has a value greater than 1.
 *
 * @since 1.0.0
 * @param mixed $mid1 Midrail 1 position value.
 * @param mixed $mid2 Midrail 2 position value.
 * @param mixed $dvd1 Divider 1 position value.
 * @param mixed $dvd2 Divider 2 position value.
 * @return int Count of active sections (1 to 5).
 */
if ( ! function_exists( 'midrailDividerCounter' ) ) {
	function midrailDividerCounter($mid1, $mid2, $dvd1, $dvd2)
	{
		$count = 1;
		if (!empty($mid1) && $mid1 > 1) $count++;
		if (!empty($mid2) && $mid2 > 1) $count++;
		if (!empty($dvd1) && $dvd1 > 1) $count++;
		if (!empty($dvd2) && $dvd2 > 1) $count++;
		return $count;
	}
}

/**
 * Get USD exchange rate with user-level override and global fallback.
 *
 * Checks user meta first; if empty, falls back to global default stored
 * in post meta on post ID 1.
 *
 * @since 1.0.0
 * @param string $name_prop The meta key name (e.g., 'Green-dolar').
 * @param int    $user_id   The user ID to check for per-user rate.
 * @return mixed The rate value from user meta or global fallback. Returns 0 if neither exists.
 */
if ( ! function_exists( 'dolarSum' ) ) {
	function dolarSum($name_prop, $user_id)
	{
		$result = 0;
		if (get_user_meta($user_id, $name_prop, true) !== '') {
			$result = get_user_meta($user_id, $name_prop, true);
		} else {
			$result = get_post_meta(1, $name_prop, true);
		}
		return $result;
	}
}

/**
 * Snapshot current material rates to product meta for historical price tracking.
 *
 * For each material, saves the effective rate (user-level if set, otherwise global)
 * as post meta on the product post. This preserves the pricing at the time of
 * order creation for audit and repricing purposes.
 *
 * @since 1.0.0
 * @param int $prod_id The product post ID to save price snapshots on.
 * @param int $user_id The user ID for rate lookup.
 */
if ( ! function_exists( 'saveCurrentPriceItem' ) ) {
	function saveCurrentPriceItem($prod_id, $user_id)
	{
		$materials = horeka_get_material_meta_keys();

		foreach ($materials as $material) {
			$global_price = get_post_meta(1, $material, true);
			$user_price = get_user_meta($user_id, $material, true);
			if (!empty($global_price)) update_post_meta($prod_id, 'price_item_' . $material, $global_price);
			if (!empty($user_price)) update_post_meta($prod_id, 'price_item_' . $material, $user_price);
		}
	}
}
