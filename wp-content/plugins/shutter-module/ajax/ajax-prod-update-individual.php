<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
  'wp-load.php');

//$atributezzz = include(__DIR__ . '/atributes_array.php');

$atribute = get_post_meta(1, 'attributes_array', true);
// update_post_meta(1, 'attributes_array', $atributezzz);

/*
 * Frame types ID => Depth calculater
 *
 * 	Calcul suprafata de facturare
    acum:		$width * $height / 1e6
    in plus:	daca se foloseste Z-frame
                daca una din perechile left|right($sillw) sau top|bottom($sillh) au valoarea "sill" sau "no"


Calcul buildout
acum:		daca $buildout > 0 se adauga 10%
in plus:	daca si $buildout + $frdepth =< 100 raman +10%
        daca $buildout + $frdepth > 100 se adauga 20%

 */
$framesType = array(289 => 60, 303 => 36, 323 => 46, 321 => 51, 330 => 72, 318 => 60, 322 => 87, 352 => 60, 331 => 51, 320 => 46, 325 => 46, 326 => 46, 327 => 46, 328 => 46, 324 => 46, 304 => 46, 329 => 49, 307 => 46, 310 => 64, 313 => 77, 333 => 60, 306 => 51, 309 => 64, 312 => 77, 305 => 46, 308 => 64, 311 => 77, 332 => 60, 142 => 36, 314 => 46, 315 => 46, 316 => 60, 317 => 60, 300 => 50, 302 => 50, 301 => 50, 351 => 60, 319 => 100, 420 => 76);

parse_str($_POST['prod'], $products);
echo "<pre>";
//print_r($products); // Only for print array
echo "</pre>";

echo "--------------------" . $products['page_title'] . '  .....  ';;

$user_id = $products['customer_id'];
$dealer_id = get_user_meta($user_id, 'company_parent', true);

global $woocommerce;
$post_id = $products['product_id_updated'];

$sum = 0;
$i = 0;

$user_id = $products['customer_id'];

$discount_custom = get_user_meta($products['customer_id'], 'discount_custom', true);

$product_attributes = array();
$post_id = $products['product_id_updated'];
echo 'Product ID: ' . $post_id . '  .....  ';

$order_id = $products['order_edit'];
$price_for_update = get_post_meta($order_id, 'price_for_update', true);

$sqm_value = round($products['property_total'], 2);
if ($products['property_frametype'] == 171) {
	$sqm_value_blackout = 0;
	$sqm_parts = blackoutSqms($products);
	foreach ($sqm_parts as $k => $sqm) {
		if ($sqm < 1) {
			$sqm_value_blackout = $sqm_value_blackout + 1;
		} else {
			$sqm_value_blackout = $sqm_value_blackout + number_format($sqm, 2);
		}
	}
	update_post_meta($post_id, 'sqm_parts', $sqm_parts);
	update_post_meta($post_id, 'sqm_value_parts', $sqm_value_blackout);
} elseif ($sqm_value < 0.5) {
	$sqm_value = 0.5;
}

echo 'Total SQM ALL: ' . $sqm_value . '  .....\n  ';
$sum_sections = 0;
$sections_price = array();
if ($products['property_nr_sections']) {
	for ($sec = 1; $sec <= $products['property_nr_sections']; $sec++) {
		$sections_price[$sec] = 0;

		$sqm_value = round($products['property_total_section' . $sec], 2);
		if ($products['property_frametype'] == 171 && $sqm_value < 1) {
			$sqm_value = 1;
		} elseif ($sqm_value < 0.5) {
			$sqm_value = 0.5;
		}

		echo 'Total SQM: ' . $sqm_value . '  .....\n  ';

		// Calculate price
		if ($products['property_width']) {
			$width_track = $products['property_width'];
			echo 'Width track: ' . $width_track;
		}

		if ($products['property_material'] == 187) {
//    if (!empty(get_post_meta($post_id, 'price_item_Earth', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//        $sum = $sqm_value * get_post_meta($post_id, 'price_item_Earth', true);
//        echo 'SUM Earth: ' . $sum . ' ..... ';
//        $basic = $sqm_value * get_post_meta($post_id, 'price_item_Earth', true);
//        // echo 'BASIC 1: ' . $basic . ' ..... ';
//    } else {
			if (!empty(get_user_meta($products['customer_id'], 'Earth', true)) || (get_user_meta($products['customer_id'], 'Earth', true) > 0)) {
				if ($products['customer_id'] == 18) {
					$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Earth', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Earth', true)) * get_user_meta($products['customer_id'], 'Earth_tax', true)) / 100;
					echo 'SUM Earth: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Earth', true);
					// echo 'BASIC 1: ' . $basic . ' .....

					// earth alu
					if ($products['property_style'] == 27) {
						$sum = get_user_meta($products['customer_id'], 'Earth', true) - (get_user_meta($products['customer_id'], 'Earth', true) * 5.45) / 100;
						$basic = $basic - ($basic * 5.45) / 100;
					}
					if ($products['property_style'] == 28) {
						$sum = get_user_meta($products['customer_id'], 'Earth', true) - (get_user_meta($products['customer_id'], 'Earth', true) * 3.45) / 100;
						$basic = $basic - ($basic * 3.45) / 100;
					}
					update_post_meta($post_id, 'basic_earth_price', floatval($basic));
					echo 'SUM Earth ALU: ' . $sum . ' ..... ';
				} else {
					$sum = $sqm_value * get_user_meta($products['customer_id'], 'Earth', true);
					echo 'SUM Earth: ' . $sum . ' ..... ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Earth', true);
					// echo 'BASIC 1: ' . $basic . ' ..... ';

					// earth alu
					if ($products['property_style'] == 27) {
						$sum = get_user_meta($products['customer_id'], 'Earth', true) - (get_user_meta($products['customer_id'], 'Earth', true) * 5.45) / 100;
						$basic = $basic - ($basic * 5.45) / 100;
					}
					if ($products['property_style'] == 28) {
						$sum = get_user_meta($products['customer_id'], 'Earth', true) - (get_user_meta($products['customer_id'], 'Earth', true) * 3.45) / 100;
						$basic = $basic - ($basic * 3.45) / 100;
					}
					update_post_meta($post_id, 'basic_earth_price', floatval($basic));
					echo 'SUM Earth ALU: ' . $sum . ' ..... ';
				}
			} else {
				$sum = $sqm_value * get_post_meta(1, 'Earth', true);
				echo 'SUM Earth: ' . $sum . ' ..... ';
				$basic = $sqm_value * get_post_meta(1, 'Earth', true);
				// echo 'BASIC 1: ' . $basic . ' ..... ';

				// earth alu
				if ($products['property_style'] == 27) {
					$sum = get_post_meta(1, 'Earth', true) - (get_post_meta(1, 'Earth', true) * 5.45) / 100;
					$basic = $basic - ($basic * 5.45) / 100;
				}
				if ($products['property_style'] == 28) {
					$sum = get_post_meta(1, 'Earth', true) - (get_post_meta(1, 'Earth', true) * 3.45) / 100;
					$basic = $basic - ($basic * 3.45) / 100;
				}
				update_post_meta($post_id, 'basic_earth_price', floatval($basic));
				echo 'SUM Earth ALU: ' . $sum . ' ..... ';
			}
			// }
//
//	if ($products['property_style'] == 27) {
//		$sum = $sum - ($sum * 5.45) / 100;
//		$basic = $basic - ($basic * 5.45) / 100;
//	}
//	if ($products['property_style'] == 28) {
//		$sum = $sum - ($sum * 3.45) / 100;
//		$basic = $basic - ($basic * 3.45) / 100;
//	}
//	update_post_meta($post_id, 'basic_earth_price', floatval($basic));
//	echo 'SUM Earth ALU: ' . $sum . ' ..... ';
		}

		if ($products['property_material'] == 188) {
//            if (!empty(get_post_meta($post_id, 'price_item_Ecowood', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//                $sum = $sqm_value * get_post_meta($post_id, 'price_item_Ecowood', true);
//                echo 'SUM Ecowood: ' . $sum . '  .....\n  ';
//                $basic = $sqm_value * get_post_meta($post_id, 'price_item_Ecowood', true);
//                // echo 'BASIC 1: ' . $basic . '<br>';
//            } else {
			if (!empty(get_user_meta($products['customer_id'], 'Ecowood', true)) || (get_user_meta($products['customer_id'], 'Ecowood', true) > 0)) {
				if ($products['customer_id'] == 18) {
					$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true)) * get_user_meta($products['customer_id'], 'Ecowood_tax', true)) / 100;
					echo 'SUM Ecowood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
					// echo 'BASIC 1: ' . $basic . '<br>';
				} else {
					$sum = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
					echo 'SUM Ecowood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
					// echo 'BASIC 1: ' . $basic . '<br>';
				}
			} else {
				$sum = $sqm_value * get_post_meta(1, 'Ecowood', true);
				echo 'SUM Ecowood: ' . $sum . '  .....\n  ';
				$basic = $sqm_value * get_post_meta(1, 'Ecowood', true);
				// echo 'BASIC 1: ' . $basic . '<br>';
			}
			//  }
		}
		if ($products['property_material'] == 137) {
//            if (!empty(get_post_meta($post_id, 'price_item_Green', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//                $sum = $sqm_value * get_post_meta($post_id, 'price_item_Green', true);
//                echo 'SUM Green: ' . $sum . '  .....\n  ';
//                $basic = $sqm_value * get_post_meta($post_id, 'price_item_Green', true);
//                // echo 'BASIC 1: ' . $basic . '<br>';
//            } else {
			if (!empty(get_user_meta($products['customer_id'], 'Green', true)) || (get_user_meta($products['customer_id'], 'Green', true) > 0)) {
				if ($products['customer_id'] == 18) {
					$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Green', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Green', true)) * get_user_meta($products['customer_id'], 'Green_tax', true)) / 100;
					echo 'SUM Green: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Green', true);
					// echo 'BASIC 1: ' . $basic . '<br>';
				} else {
					$sum = round($sqm_value * get_user_meta($products['customer_id'], 'Green', true), 2);
					echo 'SUM Green: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Green', true);
//					echo '<br>BASIC 1: ' . $basic . '<br>';
				}
			} else {
				$sum = round($sqm_value * get_post_meta(1, 'Green', true), 2);
				echo 'SUM Green: ' . $sum . '  .....\n  ';
				$basic = $sqm_value * get_post_meta(1, 'Green', true);
//				echo '<br>BASIC 1: ' . $basic . '<br>';
			}
			//  }
		}
		if ($products['property_material'] == 138) {
			if (!empty(get_user_meta($user_id, 'BiowoodPlus', true)) || (get_user_meta($user_id, 'BiowoodPlus', true) > 0)) {
				if ($user_id == 18) {
					$sum = ($sqm_value * get_user_meta($user_id, 'BiowoodPlus', true)) + (($sqm_value * get_user_meta($user_id, 'BiowoodPlus', true)) * get_user_meta($user_id, 'BiowoodPlus_tax', true)) / 100;
					echo 'SUM BiowoodPlus: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
//					echo 'BASIC 1: ' . $basic . '<br>';
				} else {
					$sum = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
					echo 'SUM BiowoodPlus: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($user_id, 'BiowoodPlus', true);
//					echo 'BASIC 1: ' . $basic . '<br>';
				}
				update_post_meta($post_id, 'price_item_BiowoodPlus', get_user_meta($user_id, 'BiowoodPlus', true));
			} else {
				$sum = $sqm_value * get_post_meta(1, 'BiowoodPlus', true);
				echo 'SUM BiowoodPlus: ' . $sum . '  .....\n  ';
				$basic = $sqm_value * get_post_meta(1, 'BiowoodPlus', true);
//				echo 'BASIC 1: ' . $basic . '<br>';
				update_post_meta($post_id, 'price_item_BiowoodPlus', get_post_meta(1, 'BiowoodPlus', true));
			}
		}
		if ($products['property_material'] == 6) {
			if (!empty(get_user_meta($user_id, 'Biowood', true)) || (get_user_meta($user_id, 'Biowood', true) > 0)) {
				if ($user_id == 18) {
					$sum = ($sqm_value * get_user_meta($user_id, 'Biowood', true)) + (($sqm_value * get_user_meta($user_id, 'Biowood', true)) * get_user_meta($user_id, 'Biowood_tax', true)) / 100;
					echo 'SUM Biowood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($user_id, 'Biowood', true);
//					echo 'BASIC 1: ' . $basic . '<br>';
				} else {
					$sum = $sqm_value * get_user_meta($user_id, 'Biowood', true);
					echo 'SUM Biowood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($user_id, 'Biowood', true);
//					echo 'BASIC 1: ' . $basic . '<br>';
				}
				update_post_meta($post_id, 'price_item_Biowood', get_user_meta($user_id, 'Biowood', true));
			} else {
				$sum = $sqm_value * get_post_meta(1, 'Biowood', true);
				echo 'SUM Biowood: ' . $sum . '  .....\n  ';
				$basic = $sqm_value * get_post_meta(1, 'Biowood', true);
//				echo 'BASIC 1: ' . $basic . '<br>';
				update_post_meta($post_id, 'price_item_Biowood', get_post_meta(1, 'Biowood', true));
			}
		}
		if ($products['property_material'] == 139 || $products['property_material'] == 147) {
//            if (!empty(get_post_meta($post_id, 'price_item_Basswood', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//                $sum = $sqm_value * get_post_meta($post_id, 'price_item_Basswood', true);
//                echo 'SUM Basswood: ' . $sum . '  .....\n  ';
//                $basic = $sqm_value * get_post_meta($post_id, 'price_item_Basswood', true);
//                // echo 'BASIC 1: ' . $basic . '<br>';
//            } else {
			if (!empty(get_user_meta($products['customer_id'], 'Basswood', true)) || (get_user_meta($products['customer_id'], 'Basswood', true) > 0)) {
				if ($products['customer_id'] == 18) {
					$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Basswood', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Basswood', true)) * get_user_meta($products['customer_id'], 'Basswood_tax', true)) / 100;
					echo 'SUM Basswood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Basswood', true);
					// echo 'BASIC 1: ' . $basic . '<br>';
				} else {
					$sum = round($sqm_value * get_user_meta($products['customer_id'], 'Basswood', true), 2);
					echo 'SUM Basswood: ' . $sum . '  .....\n  ';
					$basic = $sqm_value * get_user_meta($products['customer_id'], 'Basswood', true);
//					echo '<br>BASIC 1: ' . $basic . '<br>';
				}
			} else {
				$sum = round($sqm_value * get_post_meta(1, 'Basswood', true), 2);
				echo 'SUM Basswood: ' . $sum . '  .....\n  ';
				$basic = $sqm_value * get_post_meta(1, 'Basswood', true);
//				echo '<br>BASIC 1: ' . $basic . '<br>';
			}
			//  }
		}

		if (!empty($discount_custom) || $discount_custom > 0) {
			echo ' Discount: ' . (($discount_custom * $basic) / 100) . ' - ' . $discount_custom . ' -';
			$sum = $sum - ($discount_custom * $basic) / 100;
			echo 'SUM Discount: ' . $sum . '  .....\n  ';
			echo '<br>BASIC 0: ' . $basic . '<br>';
			$basic = $sum;
		}

		// $sum = $sqm_value*100;
		// echo 'SUM 1: '.$sum.'<br>';
		// $basic = $sqm_value*100;
		// echo '<br>BASIC 1: '.$basic.'<br>';
		// style

		if ($products['property_layoutcode' . $sec]) {
			// if layout code not find T or t delete typeposttype or B b
			if (strpos($products['property_layoutcode . $sec'], 't') !== true || strpos($products['property_layoutcode' . $sec], 'T') !== true) {
				update_post_meta($post_id, 'property_tposttype', '');
				delete_post_meta($post_id, 'property_tposttype');
				delete_post_meta($post_id, 't-post-type');
				for ($i = 0; $i < 5; $i++) {
					update_post_meta($post_id, 'property_t' . $i, '');
					delete_post_meta($post_id, 'property_t' . $i);
				}
			}

			if (strpos($products['property_layoutcode' . $sec], 'b') !== true || strpos($products['property_layoutcode' . $sec], 'B') !== true) {
				update_post_meta($post_id, 'property_bposttype', '');
				delete_post_meta($post_id, 'property_bposttype');
				for ($i = 0; $i < 5; $i++) {
					update_post_meta($post_id, 'property_bp' . $i, '');
					delete_post_meta($post_id, 'property_bp' . $i);
					update_post_meta($post_id, 'property_ba' . $i, '');
					delete_post_meta($post_id, 'property_ba' . $i);
				}
			}

			if (strpos($products['property_layoutcode' . $sec], 'g') !== true || strpos($products['property_layoutcode' . $sec], 'G') !== true) {
				for ($i = 0; $i < 5; $i++) {
					update_post_meta($post_id, 'property_g' . $i, '');
					delete_post_meta($post_id, 'property_g' . $i);
				}
			}

			if (strpos($products['property_layoutcode' . $sec], 'c') !== true || strpos($products['property_layoutcode' . $sec], 'C') !== true) {
				for ($i = 0; $i < 5; $i++) {
					update_post_meta($post_id, 'property_c' . $i, '');
					delete_post_meta($post_id, 'property_c' . $i);
				}
			}
		}

		if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232)) {
			if (!empty(get_user_meta($products['customer_id'], 'Solid', true)) || (get_user_meta($products['customer_id'], 'Solid', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Solid', true) * $basic) / 100;
				echo 'SUM Solid: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Solid', true) * $basic) / 100;
				echo 'SUM Solid: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			}
		}
		if ($products['property_style'] == 33) {
			if (!empty(get_user_meta($products['customer_id'], 'Shaped', true)) || (get_user_meta($products['customer_id'], 'Shaped', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Shaped', true) * $basic) / 100;
				echo 'SUM Shaped: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'Shaped',true)/1;
				$sum = $sum + (get_post_meta(1, 'Shaped', true) * $basic) / 100;
				echo 'SUM Shaped: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 3: ' . $basic . '<br>';
			}
		}
		if ($products['property_style'] == 34) {
			if (!empty(get_user_meta($products['customer_id'], 'French_Door', true)) || (get_user_meta($products['customer_id'], 'French_Door', true) > 0)) {
				$sum = $sum + get_user_meta($products['customer_id'], 'French_Door', true);
				echo 'SUM French_Door: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'French_Door',true)/1;
				$sum = $sum + get_post_meta(1, 'French_Door', true);
				echo 'SUM French_Door: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 3: ' . $basic . '<br>';
			}
		}
		if ($products['property_style'] == 35 || $products['property_style'] == 39 || $products['property_style'] == 41) {
			if (!empty(get_user_meta($products['customer_id'], 'Tracked', true)) || (get_user_meta($products['customer_id'], 'Tracked', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Tracked', true) * ($width_track / 1000));
				//th        $sum = $sum + get_user_meta($products['customer_id'],'Tracked',true)*$width;
				echo 'SUM Tracked: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'Tracked',true)/1;
				$sum = $sum + (get_post_meta(1, 'Tracked', true) * ($width_track / 1000));
				//th        $sum = $sum + get_post_meta(1,'Tracked',true)*$width;
				echo 'SUM Tracked: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 4: ' . $basic . '<br>';
			}
		}
		if ($products['property_style'] == 37 || $products['property_style'] == 38 || $products['property_style'] == 40) {
			if (!empty(get_user_meta($user_id, 'TrackedByPass', true)) || (get_user_meta($user_id, 'TrackedByPass', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'TrackedByPass', true) * ($width_track / 1000));
				echo 'SUM Tracked: ' . $sum . '  .....\n  ';
				// echo 'BASIC 2: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'TrackedByPass', true) * ($width_track / 1000));
				echo 'SUM Tracked: ' . $sum . '  .....\n  ';
				// echo 'BASIC 4: ' . $basic . '<br>';
			}
			if ($products['property_tracksnumber'] == 3) {
				if (!empty(get_user_meta($user_id, 'Tracked', true)) || (get_user_meta($user_id, 'Tracked', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'Tracked', true) * ($width_track / 1000));
					echo 'SUM Tracked: ' . $sum . '  .....\n  ';
					// echo 'BASIC 2: ' . $basic . '<br>';
				} else {
					$sum = $sum + 71 * ($width_track / 1000);
					echo 'SUM Tracked: ' . $sum . '  .....\n  ';
					// echo 'BASIC 4: ' . $basic . '<br>';
				}
			}
		}
		if ($products['property_style'] == 36) {
			if ($products['property_material'] == 138) {
				$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true) + 30;
				$arched_price = get_post_meta(1, 'Arched', true) + 30;
			} else {
				$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true);
				$arched_price = get_post_meta(1, 'Arched', true);
			}

			if (!empty(get_user_meta($products['customer_id'], 'Arched', true)) || (get_user_meta($products['customer_id'], 'Arched', true) > 0)) {
				$sum = $sum + ($arched_price_user * $basic) / 100;
				echo 'SUM Arched: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'Shaped',true)/1;
				$sum = $sum + ($arched_price * $basic) / 100;
				echo 'SUM Arched: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 3: ' . $basic . '<br>';
			}
		}
		if ($products['property_style'] == 229 || $products['property_style'] == 233) {
			if (!empty(get_user_meta($products['customer_id'], 'Combi', true)) || (get_user_meta($products['customer_id'], 'Combi', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Combi', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 10: ' . $basic . '<br>';
			} else {

				$sum = $sum + (get_post_meta(1, 'Combi', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 10: ' . $basic . '<br>';
			}
		}

		// measurement type
		if ($products['property_fit'] == 56) {
			if (!empty(get_user_meta($user_id, 'Inside', true)) || (get_user_meta($user_id, 'Inside', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'Inside', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'Combi',true)/1;
				$sum = $sum + (get_post_meta(1, 'Inside', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}
		}

		// Frame Type
		if ($products['property_frametype'] == 171) {
			if (!empty(get_user_meta($products['customer_id'], 'P4028X', true)) || (get_user_meta($products['customer_id'], 'P4028X', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'P4028X', true) * $basic) / 100;
				echo 'SUM P4028X: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			} else {

				$sum = $sum + (get_post_meta(1, 'P4028X', true) * $basic) / 100;
				echo 'SUM P4028X: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}

			if (!empty($products['property_blackoutblindcolour']) && $products['property_blackoutblindcolour'] != 390) {
				if (!empty(get_user_meta($products['customer_id'], 'blackoutblind', true)) || (get_user_meta($products['customer_id'], 'blackoutblind', true) > 0)) {
					$sum = $sum + get_user_meta($products['customer_id'], 'blackoutblind', true) * round($products['property_total'], 2);
					echo 'SUM blackoutblind: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				} else {
					$sum = $sum + get_post_meta(1, 'blackoutblind', true) * round($products['property_total'], 2);
					echo 'SUM blackoutblind: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				}
			}

			if ($products['property_tposttype']) {
				if (!empty(get_user_meta($products['customer_id'], 'tposttype_blackout', true)) || (get_user_meta($products['customer_id'], 'tposttype_blackout', true) > 0)) {
					$sum = $sum + (get_user_meta($products['customer_id'], 'tposttype_blackout', true) * $basic) / 100;
					echo 'SUM tposttype_blackout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'tposttype_blackout', true) * $basic) / 100;
					echo 'SUM tposttype_blackout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				}
			}
		}
		if ($products['property_frametype'] == 322) {
			if (!empty(get_user_meta($products['customer_id'], 'P4008T', true)) || (get_user_meta($products['customer_id'], 'P4008T', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'P4008T', true) * $basic) / 100;
				echo 'SUM P4008T: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			} else {

				$sum = $sum + (get_post_meta(1, 'P4008T', true) * $basic) / 100;
				echo 'SUM P4008T: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}
		}
		if ($products['property_frametype'] == 353) {
			if (!empty(get_user_meta($products['customer_id'], '4008T', true)) || (get_user_meta($products['customer_id'], '4008T', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], '4008T', true) * $basic) / 100;
				echo 'SUM P4008T: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			} else {

				$sum = $sum + (get_post_meta(1, '4008T', true) * $basic) / 100;
				echo 'SUM P4008T: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}
		}
		if ($products['property_frametype'] == 319) {
			if (!empty(get_user_meta($products['customer_id'], 'P4008W', true)) || (get_user_meta($products['customer_id'], 'P4008W', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'P4008W', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			} else {
				//$sum = get_post_meta(1,'Combi',true)/1;
				$sum = $sum + (get_post_meta(1, 'P4008W', true) * $basic) / 100;
				echo 'SUM Combi: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}
		}

		//$tracked = $sum + $sum/1;

		if ($products['property_material'] == 139 || $products['property_material'] == 147) {
			if ($products['property_bladesize'] == 52) {
				$sum = $sum + (get_post_meta(1, 'Flat_Louver', true) * $basic) / 100;
				echo 'SUM bladesize: ' . $sum . '  .....\n  ';
				// echo 'BASIC bladesize: ' . $basic . '<br>';
			}
		}

		if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
			$frdepth = $framesType[$products['property_frametype']];
			$sum_build_frame = $frdepth + $products['property_builtout'];
			if ($sum_build_frame <= 100) {
				if (!empty(get_user_meta($products['customer_id'], 'Buildout', true)) || (get_user_meta($products['customer_id'], 'Buildout', true) > 0)) {
					$sum = $sum + (get_user_meta($products['customer_id'], 'Buildout', true) * $basic) / 100;
					echo 'SUM Buildout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 2: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'Buildout', true) * $basic) / 100;
					echo 'SUM Buildout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 5: ' . $basic . '<br>';
				}
			} elseif ($sum_build_frame > 100) {
				$sum = $sum + (20 * $basic) / 100;
				echo 'SUM Buildout: ' . $sum . '  .....\n  ';
				// echo 'BASIC 5: ' . $basic . '<br>';
			}
		}

		if (($products['property_controltype'] == 403)) {
			if (!empty(get_user_meta($products['customer_id'], 'Concealed_Rod', true)) || (get_user_meta($products['customer_id'], 'Concealed_Rod', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Concealed_Rod', true) * $basic) / 100;
				echo 'SUM Concealed_Rod: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Concealed_Rod', true) * $basic) / 100;
				echo 'SUM Concealed_Rod: ' . $sum . '  .....\n  ';
//				echo '<br>BASIC property_controltype: ' . $basic . '<br>';
			}
		}
		if (($products['property_hingecolour'] == 93)) {
			if (!empty(get_user_meta($products['customer_id'], 'Stainless_Steel', true)) || (get_user_meta($products['customer_id'], 'Stainless_Steel', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Stainless_Steel', true) * $basic) / 100;
				echo 'SUM Stainless_Steel: ' . $sum . '  .....\n  ';
//				echo '<br>BASIC 2: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Stainless_Steel', true) * $basic) / 100;
				echo 'SUM Stainless_Steel: ' . $sum . '  .....\n  ';
//				echo '<br>BASIC property_hingecolour: ' . $basic . '<br>';
			}
		}
		if ($products['property_hingecolour'] == 186) {
			if (!empty(get_user_meta($products['customer_id'], 'Hidden', true)) || (get_user_meta($products['customer_id'], 'Hidden', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Hidden', true) * $basic) / 100;
				echo 'SUM Stainless_Steel: ' . $sum . '  .....\n  ';
				// echo 'BASIC 2: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Hidden', true) * $basic) / 100;
				echo 'SUM Stainless_Steel: ' . $sum . '  .....\n  ';
				// echo 'BASIC property_hingecolour: ' . $basic . '<br>';
			}
		}
		$bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
		$bpost_unghi = 0;
		foreach ($bayangle as $property_ba) {
			if (!empty($property_ba)) {
				if (!empty($products[$property_ba]) && $products['bay-post-type'] == 'normal') {
					if ($products[$property_ba] == 90 || $products[$property_ba] == 135) {
						echo '----- Unghi egal cu 135 sau 90: ' . $products['property_ba1'] . ' ------';
					} else {
						if (!empty(get_user_meta($products['customer_id'], 'Bay_Angle', true)) || (get_user_meta($products['customer_id'], 'Bay_Angle', true) > 0)) {
							$sum = $sum + (get_user_meta($products['customer_id'], 'Bay_Angle', true) * $basic) / 100;
							echo 'SUM Bay_Angle : ' . $sum . '  .....\n  ';
							echo '<br>BASIC 7: ' . $basic . '<br>';
							echo '----- Unghi diferit de  90: ' . $products['property_ba1'] . ' ADAUGARE 10%------';
							$bpost_unghi++;
							break;
						} else {
							$sum = $sum + (get_post_meta(1, 'Bay_Angle', true) * $basic) / 100;
							echo 'SUM Bay_Angle: ' . $sum . '  .....\n  ';
							echo '<br>BASIC 7: ' . $basic . '<br>';
							echo '----- Unghi diferit de  90: ' . $products['property_ba1'] . ' ADAUGARE 10%------';
							$bpost_unghi++;
							break;
						}
					}
				}
			}
		}

		if (!empty($products['bay-post-type'])) {
			update_post_meta($post_id, 'bay-post-type', $products['bay-post-type']);

			if ($products['bay-post-type'] == 'flexible') {
				if (!empty(get_user_meta($user_id, 'B_typeFlexible', true)) || (get_user_meta($user_id, 'B_typeFlexible', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'B_typeFlexible', true) * $basic) / 100;
					echo 'SUM B_typeFlexible: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'B_typeFlexible', true) * $basic) / 100;
					echo 'SUM B_typeFlexible: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				}
			}
		}

		// If Bpost have buidout add 7%
		$b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
		update_post_meta($post_id, 'property_b_buildout1', $products['property_b_buildout1']);
		foreach ($b_buildout as $property_bb) {
			if (!empty($property_bb)) {
				if (!empty($products[$property_bb])) {
					if (!empty(get_user_meta($products['customer_id'], 'B_Buildout', true)) || (get_user_meta($products['customer_id'], 'B_Buildout', true) > 0)) {
						$sum = $sum + (get_user_meta($products['customer_id'], 'B_Buildout', true) * $basic) / 100;
						echo 'SUM B_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					} else {
						$sum = $sum + (get_post_meta(1, 'B_Buildout', true) * $basic) / 100;
						echo 'SUM B_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}
		}

		// If G-post exist add 3%
		$g_post = get_post_meta($post_id, 'property_g1', true);
		if (!empty($g_post)) {
			if (!empty(get_user_meta($products['customer_id'], 'G_post', true)) || (get_user_meta($products['customer_id'], 'G_post', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'G_post', true) * $basic) / 100;
				echo 'SUM G-Post: ' . $sum . '  .....\n  ';
				// echo 'BASIC 7: ' . $basic . '<br>';
			} else {
				$sum = $sum + (3 * $basic) / 100;
				echo 'SUM G-Post: ' . $sum . '  .....\n  ';
				// echo 'BASIC 7: ' . $basic . '<br>';
			}
		}

		// If tpost have buidout add 7%
		$t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
		update_post_meta($post_id, 'property_t_buildout1', $products['property_t_buildout1']);
		foreach ($t_buildout as $property_tb) {
			if (!empty($property_tb)) {
				if (!empty($products[$property_tb])) {
					if (!empty(get_user_meta($products['customer_id'], 'T_Buildout', true)) || (get_user_meta($products['customer_id'], 'T_Buildout', true) > 0)) {
						$sum = $sum + (get_user_meta($products['customer_id'], 'T_Buildout', true) * $basic) / 100;
						echo 'SUM T_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					} else {
						$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
						echo 'SUM T_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}
		}

		if (!empty($products['t-post-type'])) {
			update_post_meta($post_id, 't-post-type', $products['t-post-type']);

			if ($products['t-post-type'] == 'adjustable') {
				if (!empty(get_user_meta($user_id, 'T_typeAdjustable', true)) || (get_user_meta($user_id, 'T_typeFlexible', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
					echo 'SUM B_typeFlexible: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
					echo 'SUM B_typeFlexible: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				}
			}
		}

		// If Cpost have buidout add 7%
		$c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
		update_post_meta($post_id, 'property_c_buildout1', $products['property_c_buildout1']);
		foreach ($c_buildout as $property_cb) {
			if (!empty($property_cb)) {
				if (!empty($products[$property_cb])) {
					if (!empty(get_user_meta($products['customer_id'], 'C_Buildout', true)) || (get_user_meta($products['customer_id'], 'C_Buildout', true) > 0)) {
						$sum = $sum + (get_user_meta($products['customer_id'], 'C_Buildout', true) * $basic) / 100;
						echo 'SUM C_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					} else {
						$sum = $sum + (get_post_meta(1, 'C_Buildout', true) * $basic) / 100;
						echo 'SUM C_Buildout: ' . $sum . '  .....\n  ';
						echo '<br>BASIC 7: ' . $basic . '<br>';
						break;
					}
				}
			}
		}

// ======== Start - special price for gren colors for user Perfect Shutters =========

// Colors 20%
//		if ($user_id == 274 || $dealer_id == 274) {
//			// green colors
//			if ($products['property_material'] == 137) {
//				if (($products['property_shuttercolour'] == 101) || ($products['property_shuttercolour'] == 103) || ($products['property_shuttercolour'] == 104) || ($products['property_shuttercolour'] == 105) || ($products['property_shuttercolour'] == 106) || ($products['property_shuttercolour'] == 107) || ($products['property_shuttercolour'] == 108) || ($products['property_shuttercolour'] == 109) || ($products['property_shuttercolour'] == 110) || ($products['property_shuttercolour'] == 111) || ($products['property_shuttercolour'] == 112) || ($products['property_shuttercolour'] == 113) || ($products['property_shuttercolour'] == 114) || ($products['property_shuttercolour'] == 115) || ($products['property_shuttercolour'] == 116) || ($products['property_shuttercolour'] == 117) || ($products['property_shuttercolour'] == 118) || ($products['property_shuttercolour'] == 119) || ($products['property_shuttercolour'] == 120) || ($products['property_shuttercolour'] == 121)) {
//					if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
//						$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
//						echo 'SUM Colors: ' . $sum . '  .....\n  ';
//						// echo 'BASIC 11: ' . $basic . '<br>';
//					} else {
//						$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
//						echo 'SUM Colors: ' . $sum . '  .....\n  ';
//						// echo 'BASIC 11: ' . $basic . '<br>';
//					}
//				}
//			}
//	}
// ======== End - special price for gren colors for user Perfect Shutters =========

		// Colors 20%
		if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
			if (!empty(get_user_meta($products['customer_id'], 'Colors', true)) || (get_user_meta($products['customer_id'], 'Colors', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 11: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . '  .....\n  ';
				echo '<br>BASIC 11: ' . $basic . '<br>';
			}
		}

		// Colors 10%
		if (($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263) || ($products['property_shuttercolour'] == 274)) {
			$sum = $sum + (10 * $basic) / 100;
			echo 'SUM Colors: ' . $sum . '  .....\n  ';
			// echo 'BASIC 11: ' . $basic . '<br>';
		}

		if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232)) {
			if (!empty($products['property_ringpull'] && $products['property_ringpull'] !== 'No')) {
				if (!empty(get_user_meta($products['customer_id'], 'Ringpull', true)) || (get_user_meta($products['customer_id'], 'Ringpull', true) > 0)) {
					$sum = $sum + (get_user_meta($products['customer_id'], 'Ringpull', true) * $products['property_ringpull_volume']);
					echo 'SUM Ringpull: ' . $sum . '  .....\n  ';
					// echo '<br>BASIC: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'Ringpull', true) * $products['property_ringpull_volume']);
					echo 'SUM Ringpull: ' . $sum . '  .....\n  ';
					// echo '<br>BASIC: ' . $basic . '<br>';
				}
			}
		} else {
			echo 'Delete ringpull: ' . $post_id . '  .....\n  ';
			delete_post_meta($post_id, 'property_ringpull_volume', '');
			delete_post_meta($post_id, 'property_ringpull', 'YES');
		}

		$horizontal_tpost_add = 1;
		if (!empty($products['property_horizontaltpost']) && $products['property_horizontaltpost'] == 'Yes') {
			$horizontal_tpost_add = 2;
		}

// Count the number of 'T' or 't' characters
		$t_count = countT($products['property_layoutcode' . $sec]);
		if (!empty($products['property_central_lock']) && $products['property_central_lock'] == 'Yes') {
			$t_count_add = ($t_count > 0) ? $t_count : 1;
			$sum = $sum + (get_post_meta(1, 'Central_Lock', true) * $t_count_add * $horizontal_tpost_add);
			echo 'SUM Central_Lock: ' . $sum . '  .....\n  ';
		}

		if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {
			// if material biowood and biowood plus add 10% for lock
			if ($products['property_material'] == 6 || $products['property_material'] == 138) {

				$sum = $sum + (58 * $horizontal_tpost_add);
				echo 'SUM Lock: ' . $sum . '  .....\n  ';
			} else {
				if (!empty(get_user_meta($products['customer_id'], 'Lock', true)) || (get_user_meta($products['customer_id'], 'Lock', true) > 0)) {
					$sum = $sum + (get_user_meta($products['customer_id'], 'Lock', true) * 2 * $horizontal_tpost_add);
					echo 'SUM Lock: ' . $sum . '  .....\n  ';
					// echo '<br>BASIC: ' . $basic . '<br>';
				} else {
					if (!empty(get_post_meta(1, 'Lock', true))) {
						$sum = $sum + (get_post_meta(1, 'Lock', true) * 2 * $horizontal_tpost_add);
						echo 'SUM Lock: ' . $sum . '  .....\n  ';
					}
				}
			}
		}

		echo ' before spare louvres: ' . $sum . '  .....\n  ';

		if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
			if (!empty(get_user_meta($products['customer_id'], 'Spare_Louvres', true)) || (get_user_meta($products['customer_id'], 'Spare_Louvres', true) > 0)) {
				$sum = $sum + ($products['panels_left_right'] * get_user_meta($products['customer_id'], 'Spare_Louvres', true));
				echo ' Panels: ' . $products['panels_left_right'] . ' SUM Spare_Louvres: ' . $sum . '  .....\n  ';
//			echo '<br>BASIC 9: ' . $basic . '<br>';
			} else {
				$sum = $sum + ($products['panels_left_right'] * get_post_meta(1, 'Spare_Louvres', true));
				echo ' Panels: ' . $products['panels_left_right'] . ' SUM Spare_Louvres: ' . $sum . '  .....\n  ';
//			echo '<br>BASIC 9: ' . $basic . '<br>';
			}
		}

		// if( !empty($discount_custom) || $discount_custom > 0 ){
		//     $sum = $sum - ($discount_custom*$basic)/100;
		//     echo 'SUM 0: '.$sum.'<br>';
		//     echo '<br>BASIC 0: '.$basic.'<br>';
		// }

		if ($user_id == 18) {
			if (!empty(get_user_meta($products['customer_id'], 'SeaDelivery', true))) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'SeaDelivery', true));
				echo 'SUM SeaDelivery: ' . $sum . '  .....\n  ';
				// echo 'BASIC 1: ' . $basic . '<br>';
			}
		}

		echo "Suma_total: " . $sum;

		$g = 0;
		$t = 0;
		$b = 0;
		$c = 0;

		foreach ($products as $name_attr => $id) {

			update_post_meta($post_id, $name_attr, $id);

			if ($name_attr == "property_layoutcode" . $sec) {
				update_post_meta($post_id, $name_attr, strtoupper($id));
			}

			if (!is_array($id)) {
				//echo $product.'<br>';
				//$sum = $sum + $id;

				if ($name_attr == "property_width") {
					$width = $id;
					//echo "gasit width";

				}
				if ($name_attr == "property_height") {
					$height = $id;
					//echo "gasit height";
				}
				//echo '<br>'.$width.'<br>';
				//echo '<br>'.$height.'<br>';

				if ($name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15") {
					$t++;
				} elseif ($name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15") {
					$g++;
				} elseif ($name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15") {
					$b++;
				} elseif ($name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15") {
					$c++;
				}

				if (array_key_exists($id, $atribute)) {

					if ($name_attr == "product_id" || $name_attr == "property_width" || $name_attr == "property_height" || $name_attr == "property_solidpanelheight" || $name_attr == "property_midrailheight" || $name_attr == "property_builtout" || $name_attr == "property_layoutcode" || $name_attr == "property_opendoor" || $name_attr == "quantity_split" || $name_attr == "property_ba1" || $name_attr == "property_ba2" || $name_attr == "property_ba3" || $name_attr == "property_ba4" || $name_attr == "property_ba5" || $name_attr == "property_ba6" || $name_attr == "property_ba7" || $name_attr == "property_ba8" || $name_attr == "property_ba9" || $name_attr == "property_ba10" || $name_attr == "property_ba11" || $name_attr == "property_ba12" || $name_attr == "property_ba13" || $name_attr == "property_ba14" || $name_attr == "property_ba15" || $name_attr == "property_bp1" || $name_attr == "property_bp2" || $name_attr == "property_bp3" || $name_attr == "property_bp4" || $name_attr == "property_bp5" || $name_attr == "property_bp6" || $name_attr == "property_bp7" || $name_attr == "property_bp8" || $name_attr == "property_bp9" || $name_attr == "property_bp10" || $name_attr == "property_bp11" || $name_attr == "property_bp12" || $name_attr == "property_bp13" || $name_attr == "property_bp14" || $name_attr == "property_bp15" || $name_attr == "property_t1" || $name_attr == "property_t2" || $name_attr == "property_t3" || $name_attr == "property_t4" || $name_attr == "property_t5" || $name_attr == "property_t6" || $name_attr == "property_t7" || $name_attr == "property_t8" || $name_attr == "property_t9" || $name_attr == "property_t10" || $name_attr == "property_t11" || $name_attr == "property_t12" || $name_attr == "property_t13" || $name_attr == "property_t14" || $name_attr == "property_t15" || $name_attr == "property_g1" || $name_attr == "property_g2" || $name_attr == "property_g3" || $name_attr == "property_g4" || $name_attr == "property_g5" || $name_attr == "property_g6" || $name_attr == "property_g7" || $name_attr == "property_g8" || $name_attr == "property_g9" || $name_attr == "property_g10" || $name_attr == "property_g11" || $name_attr == "property_g12" || $name_attr == "property_g13" || $name_attr == "property_g14" || $name_attr == "property_g15" || $name_attr == "property_c1" || $name_attr == "property_c2" || $name_attr == "property_c3" || $name_attr == "property_c4" || $name_attr == "property_c5" || $name_attr == "property_c6" || $name_attr == "property_c7" || $name_attr == "property_c8" || $name_attr == "property_c9" || $name_attr == "property_c10" || $name_attr == "property_c11" || $name_attr == "property_c12" || $name_attr == "property_c13" || $name_attr == "property_c14" || $name_attr == "property_c15") {
						$name_attr = explode("_", $name_attr);
						$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
						  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
						  'value' => $id, // set attribute value
						  'position' => $i,
						  'is_visible' => 1,
						  'is_variation' => 0,
						  'is_taxonomy' => 0,
						);
						$i++;
					} else {
						$name_attr = explode("_", $name_attr);
						$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
						  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
						  'value' => $atribute[$id], // set attribute value
						  'position' => $i,
						  'is_visible' => 1,
						  'is_variation' => 0,
						  'is_taxonomy' => 0,
						);
						$i++;
					}
				} else {
					$name_attr = explode("_", $name_attr);
					$product_attributes[($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2])] = array(
					  'name' => wc_clean($name_attr[0] . ' ' . $name_attr[1] . ' ' . $name_attr[2]), // set attribute name
					  'value' => $id, // set attribute value
					  'position' => $i,
					  'is_visible' => 1,
					  'is_variation' => 0,
					  'is_taxonomy' => 0,
					);
					$i++;
				}
			}
		}

		if ($products['property_nr_sections']) {

			$property_tb = 'property_t_buildout1_' . $sec;
			$tposttype = 't-post-type' . $sec;
			$property_g1 = 'property_g1_' . $sec;

			if (!empty($products[$property_tb])) {
				update_post_meta($post_id, $property_tb, $products[$property_tb]);
				if (!empty(get_user_meta($user_id, 'T_Buildout', true)) || (get_user_meta($user_id, 'T_Buildout', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'T_Buildout', true) * $basic) / 100;
					echo 'SUM T_Buildout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				} else {
					$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
					echo 'SUM T_Buildout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				}
			}

			if (!empty($products[$tposttype])) {
				update_post_meta($post_id, $tposttype, $products[$tposttype]);

				if ($products[$tposttype] == 'adjustable') {
					if (!empty(get_user_meta($user_id, 'T_typeAdjustable', true)) || (get_user_meta($user_id, 'T_typeFlexible', true) > 0)) {
						$sum = $sum + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
						echo 'SUM t-post-type: ' . $sum . '  .....\n  ';
						// echo 'BASIC 7: ' . $basic . '<br>';
					} else {
						$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
						echo 'SUM t-post-type: ' . $sum . '  .....\n  ';
						// echo 'BASIC 7: ' . $basic . '<br>';
					}
				}
			}

			if ($products[$property_g1]) {
				if (!empty(get_user_meta($user_id, 'G_post', true)) || (get_user_meta($user_id, 'G_post', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'G_post', true) * $basic) / 100;
					echo 'SUM G-Post: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				} else {
					$sum = $sum + (3 * $basic) / 100;
					echo 'SUM G-Post: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
				}
			}
			$sections_price[$sec] = $sum;
			echo ' PRICE SECTION: ' . $sum . '  .....\n  ';
		}
	}
}

// print_r($sections_price);
foreach ($sections_price as $price_section) {
	$sum_sections = $sum_sections + $price_section;
}

$individual_counter = array();
if ($products['property_nr_sections']) {
	$nr_sections = $products['property_nr_sections'];
	for ($sec = 1; $sec <= $nr_sections; $sec++) {
		$t = 0;
		$g = 0;
		$individual_counter[$sec]['counter_b'] = 0;
		$individual_counter[$sec]['counter_c'] = 0;
		$individual_counter[$sec]['counter_t'] = 0;
		$individual_counter[$sec]['counter_g'] = 0;
		for ($i = 1; $i <= 10; $i++) {
			$property_t = "property_t" . $i . "_" . $sec;
			$property_g = "property_g" . $i . "_" . $sec;
			if ($products[$property_t]) {
				$t++;
			}
			if ($products[$property_g]) {
				$g++;
			}
		}
		$individual_counter[$sec]['counter_t'] = $t;
		$individual_counter[$sec]['counter_g'] = $g;
	}
}

update_post_meta($post_id, 'sections_price', $sections_price);
update_post_meta($post_id, 'individual_counter', $individual_counter);
unset($product_attributes['shutter_svg']);
update_post_meta($post_id, '_product_attributes', $product_attributes);

echo "<br>Total product price: " . $sum_sections . " Euro";
update_post_meta($post_id, '_price', floatval($sum_sections));
update_post_meta($post_id, 'counter_g', $g);
update_post_meta($post_id, 'counter_t', $t);
update_post_meta($post_id, 'counter_b', $b);
update_post_meta($post_id, 'counter_c', $c);
update_post_meta($post_id, '_regular_price', floatval($sum_sections));
update_post_meta($post_id, '_sale_price', floatval($sum_sections));
// update_post_meta($post_id, 'svg_product', $products['svg']);
update_post_meta($post_id, 'attachmentDraw', $products['attachmentDraw']);

echo $t . ' nr t <br>';
echo $b . ' nr b <br>';
echo $c . ' nr c <br>';
// echo $_POST['svg'];

$sqm_value = round($products['property_total'], 2);

/*  **********************************************************************
--------------------------  Calcul Suma Dolari --------------------------
**********************************************************************  */

$sum_sections = 0;
$sections_price = array();
if ($products['property_nr_sections']) {
	for ($sec = 1; $sec <= $products['property_nr_sections']; $sec++) {
		$sections_price[$sec] = 0;


		$sqm_value = round($products['property_total_section' . $sec], 2);
		if ($products['property_frametype'] == 171 && $sqm_value < 1) {
			$sqm_value = 1;
		} elseif ($sqm_value < 0.5) {
			$sqm_value = 0.5;
		}

		$sum = 0;
		$basic = 0;
// Calculate price
		if ($products['property_material'] == 187) {
			$sum = $sqm_value * dolarSum('Earth-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 188) {
			$sum = $sqm_value * dolarSum('Ecowood-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 5) {
			$sum = $sqm_value * dolarSum('EcowoodPlus-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 137) {
			$sum = $sqm_value * dolarSum('Green-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 138) {
			$sum = $sqm_value * dolarSum('BiowoodPlus-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 6) {
			$sum = $sqm_value * dolarSum('Biowood-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}
		if ($products['property_material'] == 139 || $products['property_material'] == 147) {
			$sum = $sqm_value * dolarSum('Basswood-dolar', $user_id);
			echo 'SUM 1 dolar: ' . $sum . ' ..... ';
			$basic = $sum;
			// echo 'BASIC 1 dolar: ' . $basic . '<br>';
		}

//style

		if ($products['property_material'] == 139 || $products['property_material'] == 147) {
			if ($products['property_bladesize'] == 52) {
				$sum = $sum + (dolarSum('Flat_Louver-dolar', $user_id) * $basic) / 100;
				echo 'SUM bladesize: ' . $sum . '  .....\n  ';
				// echo 'BASIC bladesize: ' . $basic . '<br>';
			}
		}

		if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232) || ($products['property_style'] == 38) || ($products['property_style'] == 39)) {
			$val = dolarSum('Solid-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 2: ' . $sum . '  .....\n  ';
			// echo 'BASIC 2: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 33) {
			$val = dolarSum('Shaped-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 3: ' . $sum . '  .....\n  ';
			// echo 'BASIC 3: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 34) {
			$val = dolarSum('French_Door-dolar', $user_id);
			$sum = $sum + $val;
			echo 'SUM 3: ' . $sum . '  .....\n  ';
			echo '<br>BASIC 3: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 35) {
			$val = dolarSum('Tracked-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 4: ' . $sum . '  .....\n  ';
			// echo 'BASIC 4: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 37) {
			$val = dolarSum('TrackedByPass-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 4 dolar: ' . $sum . '  .....\n  ';
			// echo 'BASIC 4: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 36) {
			$val = dolarSum('Arched-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM Arched 3: ' . $sum . '  .....\n  ';
			// echo 'BASIC 3: ' . $basic . '<br>';
		}
		if ($products['property_style'] == 229 || $products['property_style'] == 233) {
			$val = dolarSum('Combi-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 10: ' . $sum . '  .....\n  ';
			// echo 'BASIC 10: ' . $basic . '<br>';
		}

// Frame Type
		if ($products['property_frametype'] == 171) {

			//$sum = get_post_meta(1,'Combi',true)/1;
			$val = dolarSum('P4028X-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM Combi: ' . $sum . '  .....\n  ';
			// echo 'BASIC 10: ' . $basic . '<br>';

			$blackoutblind = dolarSum('blackoutblind-dolar', $user_id);
			if (!empty($blackoutblind) || ($blackoutblind > 0)) {

				$sum = $sum + $blackoutblind * $sqm_value_blackout;
				echo 'SUM blackoutblind: ' . $sum . '  .....\n  ';
				// echo 'BASIC 10: ' . $basic . '<br>';
			}

			if ($products['property_tposttype']) {
				$tposttype_blackout = dolarSum('tposttype_blackout-dolar', $user_id);
				if (!empty($tposttype_blackout) || ($tposttype_blackout > 0)) {
					$sum = $sum + ($tposttype_blackout * $basic) / 100;
					echo 'SUM tposttype_blackout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				}
			}

			if (!empty($products['bay-post-type'])) {
				$bposttype_blackout = dolarSum('bposttype_blackout-dolar', $user_id);
				if (!empty($bposttype_blackout) || ($bposttype_blackout > 0)) {
					$sum = $sum + ($bposttype_blackout * $basic) / 100;
					echo 'SUM bposttype_blackout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 10: ' . $basic . '<br>';
				}
			}
		}
		if ($products['property_frametype'] == 322) {
			$val = dolarSum('P4008T-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM P4008T: ' . $sum . '  .....\n  ';
			// echo 'BASIC 10: ' . $basic . '<br>';
		}
		if ($products['property_frametype'] == 353) {
			$val = dolarSum('4008T-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 4008T: ' . $sum . '  .....\n  ';
			// echo 'BASIC 10: ' . $basic . '<br>';
		}
		if ($products['property_frametype'] == 319) {
			$val = dolarSum('P4008W-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM Combi: ' . $sum . '  .....\n  ';
			// echo 'BASIC 10: ' . $basic . '<br>';
		}

		if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
			$frdepth = $framesType[$products['property_frametype']];
			$sum_build_frame = $frdepth + $products['property_builtout'];
			if ($sum_build_frame <= 100) {
				$val = dolarSum('Buildout-dolar', $user_id);
				if (!empty($val) || ($val > 0)) {
					$sum = $sum + ($val * $basic) / 100;
					echo 'SUM Buildout: ' . $sum . '  .....\n  ';
					// echo 'BASIC 2: ' . $basic . '<br>';
				}
			} elseif ($sum_build_frame > 100) {
				update_post_meta($post_id, 'sum_build_frame', true);
				$sum = $sum + (20 * $basic) / 100;
				echo 'SUM Buildout: ' . $sum . '  .....\n  ';
				// echo 'BASIC 5: ' . $basic . '<br>';
			}
		}
		if ($products['property_controltype'] == 403) {
			$result = 0;
			if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) && (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
				$result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
				$sum = $sum + ($basic * $result) / 100;
			} else {
				$result = get_post_meta(1, 'Concealed_Rod-dolar', true);
				$sum = $sum + ($basic * $result) / 100;
//				$sum = $sum + $result * $sqm_value;
			}
			echo 'SUM 6: ' . $sum . '  .....\n  ';
			// echo 'BASIC 6: ' . $basic . '<br>';
		}
		if ($products['property_hingecolour'] == 93) {
			$val = dolarSum('Stainless_Steel-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 6: ' . $sum . '  .....\n  ';
			// echo 'BASIC 6: ' . $basic . '<br>';
		}
		if ($products['property_hingecolour'] == 186) {
			$val = dolarSum('Hidden-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 6: ' . $sum . '  .....\n  ';
			// echo 'BASIC 6: ' . $basic . '<br>';
		}
		$bayangle = array('property_ba1', 'property_ba2', 'property_ba3', 'property_ba4', 'property_ba5', 'property_ba6', 'property_ba7', 'property_ba8', 'property_ba9', 'property_ba10', 'property_ba11', 'property_ba12', 'property_ba13', 'property_ba14', 'property_ba15');
		$bpost_unghi = 0;
		foreach ($bayangle as $property_ba) {
			if (!empty($property_ba)) {
				if (!empty($products[$property_ba]) && $products['bay-post-type'] == 'normal') {
					if ($products[$property_ba] == 90 || $products[$property_ba] == 135) {
						// echo '----- Unghi egal cu 135 sau 90: '.$property_ba.' ------';
					} else {
						$val = dolarSum('Bay_Angle-dolar', $user_id);
						$sum = $sum + ($val * $basic) / 100;
						echo 'SUM 7: ' . $sum . '  .....\n  ';
						// echo 'BASIC 7: ' . $basic . '<br>';
						// echo '----- Unghi diferit de  90: '.$property_ba.' ADAUGARE 10%------';
						$bpost_unghi++;
						break;
					}
				}
			}
		}

		if (!empty($products['bay-post-type'])) {

			if ($products['bay-post-type'] == 'flexible') {
				$val = dolarSum('B_typeFlexible-dolar', $user_id);
				$sum = $sum + ($val * $basic) / 100;
				echo 'SUM B_typeFlexible: ' . $sum . '  .....\n  ';
				// echo 'BASIC 7: ' . $basic . '<br>';
			}
		}

// If Bpost have buidout add 7%
		$b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
		foreach ($b_buildout as $property_bb) {
			if (!empty($property_bb)) {
				if (!empty($products[$property_bb])) {
					$val = dolarSum('B_Buildout-dolar', $user_id);
					$sum = $sum + ($val * $basic) / 100;
					echo 'SUM 7: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
					break;
				}
			}
		}

// If tpost have buidout add 7%
		$t_buildout = array('property_t_buildout1', 'property_t_buildout2', 'property_t_buildout3', 'property_t_buildout4', 'property_t_buildout5', 'property_t_buildout6', 'property_t_buildout7', 'property_t_buildout8', 'property_t_buildout9', 'property_t_buildout10', 'property_t_buildout11', 'property_t_buildout12', 'property_t_buildout13', 'property_t_buildout14', 'property_t_buildout15');
		foreach ($t_buildout as $property_tb) {
			if (!empty($property_tb)) {
				if (!empty($products[$property_tb])) {
					$val = dolarSum('T_Buildout-dolar', $user_id);
					$sum = $sum + ($val * $basic) / 100;
					echo 'SUM 7: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
					break;
				}
			}
		}

		if ($products['property_nr_sections']) {
			for ($i = 1; $i <= 10; $i++) {
				$property_tb = 'property_t_buildout1_' . $i;
				if (!empty($products[$property_tb])) {
					$val = dolarSum('T_Buildout-dolar', $user_id);
					$sum = $sum + ($val * $basic) / 100;
					echo 'SUM 7: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
					break;
				}
			}
		}
// If Cpost have buidout add 7%
		$c_buildout = array('property_c_buildout1', 'property_c_buildout2', 'property_c_buildout3', 'property_c_buildout4', 'property_c_buildout5', 'property_c_buildout6', 'property_c_buildout7', 'property_c_buildout8', 'property_c_buildout9', 'property_c_buildout10', 'property_c_buildout11', 'property_c_buildout12', 'property_c_buildout13', 'property_c_buildout14', 'property_c_buildout15');
		foreach ($c_buildout as $property_cb) {
			if (!empty($property_cb)) {
				if (!empty($products[$property_cb])) {
					$val = dolarSum('C_Buildout-dolar', $user_id);
					$sum = $sum + ($val * $basic) / 100;
					echo 'SUM 7: ' . $sum . '  .....\n  ';
					// echo 'BASIC 7: ' . $basic . '<br>';
					break;
				}
			}
		}

// Stained BASSWOOD 14.57%
		if ($products['property_material'] == 139 || $products['property_material'] == 147) {
			if (($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125)) {
				$sum = $sum + (14.57 * $basic) / 100;
				echo 'SUM 11: ' . $sum . '  .....\n  ';
				// echo 'BASIC 11: ' . $basic . '<br>';
			}
		}

// Brushed BIOWOOD 35.64%
		if ($products['property_material'] == 138) {
			if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
				$sum = $sum + (35.64 * $basic) / 100;
				echo 'SUM 11: ' . $sum . '  .....\n  ';
				// echo 'BASIC 11: ' . $basic . '<br>';
			}
		}

// Painted EARTH shutters  6.87%
		if ($products['property_material'] == 187) {
			if (($products['property_shuttercolour'] == 258) || ($products['property_shuttercolour'] == 259) || ($products['property_shuttercolour'] == 260) || ($products['property_shuttercolour'] == 261) || ($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263)) {
				$sum = $sum + (6.87 * $basic) / 100;
				echo 'SUM 11: ' . $sum . '  .....\n  ';
				// echo 'BASIC 11: ' . $basic . '<br>';
			}
		}

		if (!empty($products['property_ringpull'] && $products['property_ringpull'] !== 'No')) {
			$val = dolarSum('Ringpull-dolar', $user_id);
			$sum = $sum + ($val * $products['property_ringpull_volume']);
			echo 'SUM 8: ' . $sum . '  .....\n  ';
			// echo 'BASIC 8: ' . $basic . '<br>';
		}

		if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {
			$val = dolarSum('Lock-dolar', $user_id);
			$sum = $sum + ($val * 2);
			echo 'SUM 8: ' . $sum . '  .....\n  ';
			// echo 'BASIC 8: ' . $basic . '<br>';
		}
		if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
			$val = dolarSum('Spare_Louvres-dolar', $user_id);
			$sum = $sum + ($products['panels_left_right'] * $val);
			echo 'SUM 9: ' . $sum . '  .....\n  ';
			// echo 'BASIC 9: ' . $basic . '<br>';
		}
		$sections_price[$sec] = $sum;
		update_post_meta($post_id, 'dolar_price_section_' . $sec, floatval(floatval($sum)));
	}
}
// print_r($sections_price);
foreach ($sections_price as $price_section) {
	$sum_sections = $sum_sections + $price_section;
}
//print_r($frames);
echo "Suma_total DOLARS: " . $sum . '<br><br><br>';

update_post_meta($post_id, 'dolar_price', floatval(floatval($sum_sections)));
/*  **********************************************************************
--------------------------   END - Calcul Suma Dolari --------------------------
**********************************************************************  */

if (!empty($products['order_edit']) && !empty($products['edit_customer'])) {
	echo ' ----- Edit order ------ ';
	$color = 0;

	$order = wc_get_order($products['order_edit']);

	$products_cart = array();
	foreach ($order->get_items() as $cart_item_key => $cart_item) {
		$product_id = $cart_item['product_id'];
		$products_cart[] = $product_id;
		// Check for specific product IDs and change quantity
		//        $cantitate = $products['quantity'];
		//        if ($product_id == $post_id) {
		//            WC()->cart->set_quantity($cart_item_key, $cantitate); // Change quantity
		//        }
		$col = get_post_meta($post_id, 'other_color', true);
		$color = intval($color) + intval($col);
	}
	// add custom color as separate product
	$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
	if (!empty($property_shuttercolour_other)) {

		$other_color_prod_id = 337;
		if (!in_array($other_color_prod_id, $products_cart)) {
			$product = wc_get_product($other_color_prod_id);
			wc_get_order($products['order_edit'])->add_product($product, $products['quantity']);
		}
	}

	// ---------------------- Recalculate total of order after add item in edit order ----------------------

	echo ' ----- Recaulculate total order ----- ';

	$order_id = $products['order_edit'];
	$order = wc_get_order($order_id);
	$user_id_customer = get_post_meta($order_id, '_customer_user', true);
	$items = $order->get_items();
	$total_dolar = 0;
	$sum_total_dolar = 0;
	$totaly_sqm = 0;
	$new_total = 0;

	$country_code = WC()->countries->countries[$order->get_shipping_country()];
	if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
		$tax_rate = 20;
	} else {
		$tax_rate = 0;
	}

	$pos = false;

	foreach ($items as $item_id => $item_data) {

		if (has_term(20, 'product_cat', $item_data['product_id']) || has_term(34, 'product_cat', $item_data['product_id']) || has_term(26, 'product_cat', $item_data['product_id'])) {
			$pos = true;
			// do something if product with ID = 971 has tag with ID = 5

		} else {

			$quantity = get_post_meta($item_data['product_id'], 'quantity', true);
			$price = get_post_meta($item_data['product_id'], '_price', true);
			$total = $price * $quantity;
			$sqm = get_post_meta($item_data['product_id'], 'property_total', true);
			if ($sqm > 0) {
				$train_price = get_user_meta($user_id_customer, 'train_price', true);
				if (empty($train_price)) {
					$train_price = get_post_meta(1, 'train_price', true);
				}
				$new_price = floatval($sqm * $quantity) * floatval($train_price);
				update_post_meta($item_data['product_id'], 'train_delivery', $new_price);
				// $new_price = 0;
			} else {
				$new_price = 0;
			}
			$total = number_format($total + $new_price, 2);
			$tax = number_format(($tax_rate * $total) / 100, 2);

			$line_tax_data = array(
			  'total' => array(
				1 => $tax,
			  ),
			  'subtotal' => array(
				1 => $tax,
			  ),
			);
			/*
			 * Doc to change order item meta: https://www.ibenic.com/manage-order-item-meta-woocommerce/
			 */
			if ($quantity == 0 || empty($quantity)) {
				$quantity = 1;
				$total = $price * $quantity;
				$tax = ($tax_rate * $total) / 100;
			}

			wc_update_order_item_meta($item_id, '_qty', $quantity, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_tax', $tax, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_subtotal_tax', $tax, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_subtotal', $total, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_total', $total, $prev_value = '');
			wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data);

			// USD price
			//$prod_qty = $item_data['quantity'];
			$product_id = $item_data['product_id'];
			$prod_qty = get_post_meta($product_id, 'quantity', true);
			$dolar_price = get_post_meta($product_id, 'dolar_price', true);

			$sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

			$sqm = get_post_meta($product_id, 'property_total', true);
			$property_total_sqm = floatval($sqm) * floatval($prod_qty);
			$totaly_sqm = $totaly_sqm + $property_total_sqm;

			echo '<br>$totaly_sqm: ' . $totaly_sqm . '<br>';

			## -- Make your checking and calculations -- ##
			$new_total = $new_total + $total + $tax; // <== Fake calculation

			$_product = wc_get_product($product_id);
		}
	}

	if ($pos === false) {

		foreach ($order->get_items('tax') as $item_id => $item_tax) {
			// Tax shipping total
			$tax_shipping_total = $item_tax->get_shipping_tax_total();
		}
		//$total_price = $subtotal_price + $total_tax;
		$order_data = $order->get_data();
		$order_status = $order_data['status'];
		$total_price = $new_total + $order_data['shipping_total'] + $tax_shipping_total;
		update_post_meta($order_id, '_order_total', $total_price);

		/*
		 * UPDATE custom_orders table on each shop_order post update
		 */
		$property_total_gbp = $order->get_total();
		$property_subtotal_gbp = $order->get_subtotal();

		$order_shipping_total = $order->shipping_total;

		// $property_total_gbp = floatval(get_post_meta($order->id, '_order_total', true));

		global $wpdb;

		$idOrder = $order_id;
		$orderExist = $wpdb->get_var("SELECT COUNT(*) FROM `wp_custom_orders` WHERE `idOrder` = $idOrder");

		if ($orderExist != 0) {
			/*
			 * Update table
			 */
			$tablename = $wpdb->prefix . 'custom_orders';

			$data = array(
			  'usd_price' => $sum_total_dolar,
			  'gbp_price' => $property_total_gbp,
			  'subtotal_price' => $property_subtotal_gbp,
			  'sqm' => $totaly_sqm,
			  'shipping_cost' => $order_shipping_total,
			  'status' => $order_status,
			);
			$where = array(
			  "idOrder" => $order_id,
			);
			$wpdb->update($tablename, $data, $where);
		} else {
			/*
			 * Insert in table
			 */

			$order_status = $order_data['status'];

			$tablename = $wpdb->prefix . 'custom_orders';
			$shipclass = $_product->get_shipping_class();

			$data = array(
			  'idOrder' => $order_id,
			  'reference' => 'LF0' . $order->get_order_number() . ' - ' . get_post_meta($order_id, 'cart_name', true),
			  'usd_price' => $sum_total_dolar,
			  'gbp_price' => $property_total_gbp,
			  'subtotal_price' => $property_subtotal_gbp,
			  'sqm' => $totaly_sqm,
			  'shipping_cost' => number_format($order_shipping_total, 2),
			  'delivery' => $shipclass,
			  'status' => $order_status,
			  'createTime' => $order_data['date_created']->date('Y-m-d H:i:s'),
			);
			$wpdb->insert($tablename, $data);
		}
	}
}

if (!empty($products['order_item_id'])) {
	wc_update_order_item_meta($products['order_item_id'], '_qty', $products['quantity']);
}

echo $string = str_replace('\"', '', $_POST['svg']); // Replaces all spaces with hyphens.

global $woocommerce;
//$woocommerce->cart->add_to_cart( $post_id );

$color = 0;

echo 'specific_id id: ' . $post_id;
$cart = WC()->cart->get_cart();

$products_cart = array();
foreach ($cart as $cart_item_key => $cart_item) {
	$product_id = $cart_item['product_id'];
	$products_cart[] = $product_id;
	// Check for specific product IDs and change quantity
	$cantitate = $products['quantity'];
	if ($product_id == $post_id) {
		$woocommerce->cart->set_quantity($cart_item_key, $products['quantity']); // Change quantity
	}

	$col = get_post_meta($post_id, 'other_color', true);
	$color = intval($color) + intval($col);
}
// add custom color as separate product
$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
if (!empty($property_shuttercolour_other)) {

	$other_color_prod_id = 337;
	if (!in_array($other_color_prod_id, $products_cart)) {
		WC()->cart->add_to_cart($other_color_prod_id, 1);
	}
}

// set shipping class default for product
$product = wc_get_product($product_id);
$int_shipping = get_term_by('slug', 'ship', 'product_shipping_class');
$product->set_shipping_class_id($int_shipping->term_id);
$product->save();

///  change name of image

// echo $attachment = get_post_meta( $post_id, 'attachment', true );

// $post = get_post($post_id);
// $file = get_post_meta( $post_id, 'attachment', true );
// $path = pathinfo($file);

// $newfilename = $products['property_room_other'];
// $newfile = $path['dirname']."/".$newfilename.".".$path['extension'];

// rename($file, $newfile);
// update_attached_file( $post_id, $newfile );
// update_post_meta( $post_id, 'attachment', $newfile );

function dolarSum($name_prop, $user_id)
{
	$result = 0;
	if (!empty(get_user_meta($user_id, $name_prop, true)) || (get_user_meta($user_id, $name_prop, true) > 0)) {
		$result = get_user_meta($user_id, $name_prop, true);
	} else {
		$result = get_post_meta(1, $name_prop, true);
	}
	return $result;
}


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