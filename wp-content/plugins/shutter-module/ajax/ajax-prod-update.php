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

echo "--------------------" . $products['page_title'];

$user_id = get_current_user_id();

global $woocommerce;
$post_id = $products['product_id_updated'];

$sum = 0;
$i = 0;

$user_id = $products['customer_id'];
$dealer_id = get_user_meta($user_id, 'company_parent', true);

$discount_custom = get_user_meta($products['customer_id'], 'discount_custom', true);

$product_attributes = array();
echo $post_id = $products['product_id_updated'];

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

echo 'Total SQM: ' . $sqm_value . ' <br> ';

// Calculate price
if ($products['property_width']) {
	$width_track = $products['property_width'];
	echo '-(Width track: ' . $width_track . ')-';
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
//    if (!empty(get_post_meta($post_id, 'price_item_Ecowood', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//        $sum = $sqm_value * get_post_meta($post_id, 'price_item_Ecowood', true);
//        echo 'SUM Ecowood: ' . $sum . ' ..... ';
//        $basic = $sqm_value * get_post_meta($post_id, 'price_item_Ecowood', true);
//        // echo 'BASIC 1: ' . $basic . ' ..... ';
//    } else {
	if (!empty(get_user_meta($products['customer_id'], 'Ecowood', true)) || (get_user_meta($products['customer_id'], 'Ecowood', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true)) * get_user_meta($products['customer_id'], 'Ecowood_tax', true)) / 100;
			echo 'SUM Ecowood: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
			echo 'SUM Ecowood: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Ecowood', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = $sqm_value * get_post_meta(1, 'Ecowood', true);
		echo 'SUM Ecowood: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'Ecowood', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
	// }
}
if ($products['property_material'] == 137) {
	if (!empty(get_user_meta($products['customer_id'], 'Green', true)) || (get_user_meta($products['customer_id'], 'Green', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Green', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Green', true)) * get_user_meta($products['customer_id'], 'Green_tax', true)) / 100;
			echo 'SUM Green: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Green', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = round($sqm_value * get_user_meta($products['customer_id'], 'Green', true), 2);
			echo 'SUM Green: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Green', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = round($sqm_value * get_post_meta(1, 'Green', true), 2);
		echo 'SUM Green: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'Green', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
}
if ($products['property_material'] == 5) {
	if (!empty(get_user_meta($products['customer_id'], 'EcowoodPlus', true)) || (get_user_meta($products['customer_id'], 'EcowoodPlus', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'EcowoodPlus', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'EcowoodPlus', true)) * get_user_meta($products['customer_id'], 'EcowoodPlus_tax', true)) / 100;
			echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'EcowoodPlus', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = round($sqm_value * get_user_meta($products['customer_id'], 'EcowoodPlus', true), 2);
			echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'EcowoodPlus', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = round($sqm_value * get_post_meta(1, 'EcowoodPlus', true), 2);
		echo 'SUM EcowoodPlus: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'EcowoodPlus', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
}
if ($products['property_material'] == 138) {
	if (!empty(get_user_meta($products['customer_id'], 'BiowoodPlus', true)) || (get_user_meta($products['customer_id'], 'BiowoodPlus', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'BiowoodPlus', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'BiowoodPlus', true)) * get_user_meta($products['customer_id'], 'BiowoodPlus_tax', true)) / 100;
			echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'BiowoodPlus', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = round($sqm_value * get_user_meta($products['customer_id'], 'BiowoodPlus', true), 2);
			echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'BiowoodPlus', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = round($sqm_value * get_post_meta(1, 'BiowoodPlus', true), 2);
		echo 'SUM BiowoodPlus: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'BiowoodPlus', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
}

if ($products['property_material'] == 6) {
	if (!empty(get_user_meta($products['customer_id'], 'Biowood', true)) || (get_user_meta($products['customer_id'], 'Biowood', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Biowood', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Biowood', true)) * get_user_meta($products['customer_id'], 'Biowood_tax', true)) / 100;
			echo 'SUM Biowood: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Biowood', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = round($sqm_value * get_user_meta($products['customer_id'], 'Biowood', true), 2);
			echo 'SUM Biowood: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Biowood', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = round($sqm_value * get_post_meta(1, 'Biowood', true), 2);
		echo 'SUM Biowood: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'Biowood', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
}

if ($products['property_material'] == 139) {
//    if (!empty(get_post_meta($post_id, 'price_item_Supreme', true)) && (empty($price_for_update) || $price_for_update == 'old')) {
//        $sum = $sqm_value * get_post_meta($post_id, 'price_item_Supreme', true);
//        echo 'SUM Supreme: ' . $sum . ' ..... ';
//        $basic = $sqm_value * get_post_meta($post_id, 'price_item_Supreme', true);
//        // echo 'BASIC 1: ' . $basic . ' ..... ';
//    } else {
	if (!empty(get_user_meta($products['customer_id'], 'Supreme', true)) || (get_user_meta($products['customer_id'], 'Supreme', true) > 0)) {
		if ($products['customer_id'] == 18) {
			$sum = ($sqm_value * get_user_meta($products['customer_id'], 'Supreme', true)) + (($sqm_value * get_user_meta($products['customer_id'], 'Supreme', true)) * get_user_meta($products['customer_id'], 'Supreme_tax', true)) / 100;
			echo 'SUM Supreme: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Supreme', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		} else {
			$sum = round($sqm_value * get_user_meta($products['customer_id'], 'Supreme', true), 2);
			echo 'SUM Supreme: ' . $sum . ' ..... ';
			$basic = $sqm_value * get_user_meta($products['customer_id'], 'Supreme', true);
			// echo 'BASIC 1: ' . $basic . ' ..... ';
		}
	} else {
		$sum = round($sqm_value * get_post_meta(1, 'Supreme', true), 2);
		echo 'SUM Supreme: ' . $sum . ' ..... ';
		$basic = $sqm_value * get_post_meta(1, 'Supreme', true);
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
	// }
}

if (!empty($discount_custom) || $discount_custom > 0) {
	echo ' Discount: ' . (($discount_custom * $basic) / 100) . ' - ' . $discount_custom . ' -';
	$sum = $sum - ($discount_custom * $basic) / 100;
	echo 'SUM Discount: ' . $sum . ' ..... ';
	// echo 'BASIC 0: ' . $basic . ' ..... ';
	$basic = $sum;
}

if ($products['property_layoutcode']) {
	// if layout code not find T or t delete typeposttype or B b
	if (strpos($products['property_layoutcode'], 't') !== true || strpos($products['property_layoutcode'], 'T') !== true) {
		update_post_meta($post_id, 'property_tposttype', '');
		delete_post_meta($post_id, 'property_tposttype');
		delete_post_meta($post_id, 't-post-type');
		for ($i = 0; $i < 5; $i++) {
			update_post_meta($post_id, 'property_t' . $i, '');
			delete_post_meta($post_id, 'property_t' . $i);
		}
	}

	if (strpos($products['property_layoutcode'], 'b') !== true || strpos($products['property_layoutcode'], 'B') !== true) {
		update_post_meta($post_id, 'property_bposttype', '');
		delete_post_meta($post_id, 'property_bposttype');
		for ($i = 0; $i < 5; $i++) {
			update_post_meta($post_id, 'property_bp' . $i, '');
			delete_post_meta($post_id, 'property_bp' . $i);
			update_post_meta($post_id, 'property_ba' . $i, '');
			delete_post_meta($post_id, 'property_ba' . $i);
		}
	}

	if (strpos($products['property_layoutcode'], 'g') !== true || strpos($products['property_layoutcode'], 'G') !== true) {
		for ($i = 0; $i < 5; $i++) {
			update_post_meta($post_id, 'property_g' . $i, '');
			delete_post_meta($post_id, 'property_g' . $i);
		}
	}

	if (strpos($products['property_layoutcode'], 'c') !== true || strpos($products['property_layoutcode'], 'C') !== true) {
		for ($i = 0; $i < 5; $i++) {
			update_post_meta($post_id, 'property_c' . $i, '');
			delete_post_meta($post_id, 'property_c' . $i);
		}
	}
}

// $sum = $sqm_value*100;
// echo 'SUM 1: '.$sum.'<br>';
// $basic = $sqm_value*100;
// // echo 'BASIC 1: '.$basic.'<br>';
// style
if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232) || ($products['property_style'] == 38) || ($products['property_style'] == 39) || $products['property_style'] == 42 || $products['property_style'] == 43) {
	if (!empty(get_user_meta($products['customer_id'], 'Solid', true)) || (get_user_meta($products['customer_id'], 'Solid', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Solid', true) * $basic) / 100;
		echo 'SUM Solid: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'Solid', true) * $basic) / 100;
		echo 'SUM Solid: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	}
}
if ($products['property_style'] == 33 || $products['property_style'] == 43) {
	if (!empty(get_user_meta($products['customer_id'], 'Shaped', true)) || (get_user_meta($products['customer_id'], 'Shaped', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Shaped', true) * $basic) / 100;
		echo 'SUM Shaped: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Shaped',true)/1;
		$sum = $sum + (get_post_meta(1, 'Shaped', true) * $basic) / 100;
		echo 'SUM Shaped: ' . $sum . ' ..... ';
		// echo 'BASIC 3: ' . $basic . ' ..... ';
	}
}
if ($products['property_style'] == 34) {
	if (!empty(get_user_meta($products['customer_id'], 'French_Door', true)) || (get_user_meta($products['customer_id'], 'French_Door', true) > 0)) {
		$sum = $sum + get_user_meta($products['customer_id'], 'French_Door', true);
		echo 'SUM French_Door: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'French_Door',true)/1;
		$sum = $sum + get_post_meta(1, 'French_Door', true);
		echo 'SUM French_Door: ' . $sum . ' ..... ';
		// echo 'BASIC 3: ' . $basic . ' ..... ';
	}
}
if ($products['property_style'] == 35 || $products['property_style'] == 39 || $products['property_style'] == 41) {
	if (!empty(get_user_meta($products['customer_id'], 'Tracked', true)) || (get_user_meta($products['customer_id'], 'Tracked', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Tracked', true) * ($width_track / 1000));
		//th        $sum = $sum + get_user_meta($products['customer_id'],'Tracked',true)*$width;
		echo 'SUM Tracked: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Tracked',true)/1;
		$sum = $sum + (get_post_meta(1, 'Tracked', true) * ($width_track / 1000));
		//th        $sum = $sum + get_post_meta(1,'Tracked',true)*$width;
		echo 'SUM Tracked: ' . $sum . ' ..... ';
		// echo 'BASIC 4: ' . $basic . ' ..... ';
	}
}
if ($products['property_style'] == 37 || $products['property_style'] == 38 || $products['property_style'] == 40) {
	if (!empty(get_user_meta($user_id, 'TrackedByPass', true)) || (get_user_meta($user_id, 'TrackedByPass', true) > 0)) {
		$sum = $sum + (get_user_meta($user_id, 'TrackedByPass', true) * ($width_track / 1000));
		echo 'SUM Tracked: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'TrackedByPass', true) * ($width_track / 1000));
		echo 'SUM Tracked: ' . $sum . ' ..... ';
		// echo 'BASIC 4: ' . $basic . ' ..... ';
	}
	if ($products['property_tracksnumber'] >= 3) {
		if (!empty(get_user_meta($user_id, 'Tracked', true)) || (get_user_meta($user_id, 'Tracked', true) > 0)) {
			$sum = $sum + (get_user_meta($user_id, 'Tracked', true) *
				($products['property_tracksnumber'] - 2) * ($width_track / 1000));
			echo 'SUM Tracked: ' . $sum . ' ..... ';
			// echo 'BASIC 2: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + 71 *
			  ($products['property_tracksnumber'] - 2) * ($width_track / 1000);
			echo 'SUM Tracked: ' . $sum . ' ..... ';
			// echo 'BASIC 4: ' . $basic . ' ..... ';
		}
	}
}

if ($products['property_lightblocks'] == 'Yes') {
	if (!empty(get_user_meta($user_id, 'Light_block', true)) || (get_user_meta($user_id, 'Light_block', true) > 0)) {
		$sum = $sum + (get_user_meta($user_id, 'Light_block', true) * $basic) / 100;
		echo 'SUM lightblocks: ' . $sum . ' ..... ';
		// echo 'BASIC: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Shaped',true)/1;
		$sum = $sum + (get_post_meta(1, 'Light_block', true) * $basic) / 100;
		echo 'SUM lightblocks: ' . $sum . ' ..... ';
		// echo 'BASIC: ' . $basic . ' ..... ';
	}
}

if ($products['property_style'] == 36 || $products['property_style'] == 42) {
	if ($products['property_material'] == 138) {
		//   $arched_price_user = get_user_meta($products['customer_id'], 'Arched', true) + 30;
		$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true);
//        $arched_price = get_post_meta(1, 'Arched', true) + 30;
		$arched_price = get_post_meta(1, 'Arched', true);
	} else {
		$arched_price_user = get_user_meta($products['customer_id'], 'Arched', true);
		$arched_price = get_post_meta(1, 'Arched', true);
	}

	if (!empty(get_user_meta($products['customer_id'], 'Arched', true)) || (get_user_meta($products['customer_id'], 'Arched', true) > 0)) {
		$sum = $sum + ($arched_price_user * $basic) / 100;
		echo 'SUM Arched: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Shaped',true)/1;
		$sum = $sum + ($arched_price * $basic) / 100;
		echo 'SUM Arched: ' . $sum . ' ..... ';
		// echo 'BASIC 3: ' . $basic . ' ..... ';
	}
}
if ($products['property_style'] == 229 || $products['property_style'] == 233) {
	if (!empty(get_user_meta($products['customer_id'], 'Combi', true)) || (get_user_meta($products['customer_id'], 'Combi', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Combi', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {

		$sum = $sum + (get_post_meta(1, 'Combi', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}
}

// measurement type
if ($products['property_fit'] == 56) {
	if (!empty(get_user_meta($user_id, 'Inside', true)) || (get_user_meta($user_id, 'Inside', true) > 0)) {
		$sum = $sum + (get_user_meta($user_id, 'Inside', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Combi',true)/1;
		$sum = $sum + (get_post_meta(1, 'Inside', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}
}

// Frame Type
if ($products['property_frametype'] == 171) {
	if (!empty(get_user_meta($products['customer_id'], 'P4028X', true)) || (get_user_meta($products['customer_id'], 'P4028X', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'P4028X', true) * $basic) / 100;
		echo 'SUM P4028X: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {

		$sum = $sum + (get_post_meta(1, 'P4028X', true) * $basic) / 100;
		echo 'SUM P4028X: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}

	if (!empty($products['property_blackoutblindcolour']) && $products['property_blackoutblindcolour'] != 390) {
		if (!empty(get_user_meta($products['customer_id'], 'blackoutblind', true)) || (get_user_meta($products['customer_id'], 'blackoutblind', true) > 0)) {
			$sum = $sum + get_user_meta($products['customer_id'], 'blackoutblind', true) * $sqm_value_blackout;
			echo 'SUM blackoutblind: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + get_post_meta(1, 'blackoutblind', true) * $sqm_value_blackout;
			echo 'SUM blackoutblind: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		}
	}

	if ($products['property_tposttype']) {
		if (!empty(get_user_meta($products['customer_id'], 'tposttype_blackout', true)) || (get_user_meta($products['customer_id'], 'tposttype_blackout', true) > 0)) {
			$sum = $sum + (get_user_meta($products['customer_id'], 'tposttype_blackout', true) * $basic) / 100;
			echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'tposttype_blackout', true) * $basic) / 100;
			echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		}
	}

	if (!empty($products['bay-post-type'])) {
		if (!empty(get_user_meta($products['customer_id'], 'bposttype_blackout', true)) || (get_user_meta($products['customer_id'], 'bposttype_blackout', true) > 0)) {
			$sum = $sum + (get_user_meta($products['customer_id'], 'bposttype_blackout', true) * $basic) / 100;
			echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'bposttype_blackout', true) * $basic) / 100;
			echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		}
	}
}
if ($products['property_frametype'] == 322) {
	if (!empty(get_user_meta($products['customer_id'], 'P4008T', true)) || (get_user_meta($products['customer_id'], 'P4008T', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'P4008T', true) * $basic) / 100;
		echo 'SUM P4008T: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {

		$sum = $sum + (get_post_meta(1, 'P4008T', true) * $basic) / 100;
		echo 'SUM P4008T: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}
}
if ($products['property_frametype'] == 353) {
	if (!empty(get_user_meta($products['customer_id'], '4008T', true)) || (get_user_meta($products['customer_id'], '4008T', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], '4008T', true) * $basic) / 100;
		echo 'SUM 4008T: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {

		$sum = $sum + (get_post_meta(1, '4008T', true) * $basic) / 100;
		echo 'SUM 4008T: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}
}
if ($products['property_frametype'] == 319) {
	if (!empty(get_user_meta($products['customer_id'], 'P4008W', true)) || (get_user_meta($products['customer_id'], 'P4008W', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'P4008W', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	} else {
		//$sum = get_post_meta(1,'Combi',true)/1;
		$sum = $sum + (get_post_meta(1, 'P4008W', true) * $basic) / 100;
		echo 'SUM Combi: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}
}

//$tracked = $sum + $sum/1;

if ($products['property_material'] == 139) {
	if ($products['property_bladesize'] == 52) {
		$sum = $sum + (get_post_meta(1, 'Flat_Louver', true) * $basic) / 100;
		echo 'SUM bladesize: ' . $sum . ' ..... ';
		// echo 'BASIC bladesize: ' . $basic . ' ..... ';
	}
}

if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
	$frdepth = $framesType[$products['property_frametype']];
	$sum_build_frame = $frdepth + $products['property_builtout'];
	if ($sum_build_frame <= 100) {
		if (!empty(get_user_meta($products['customer_id'], 'Buildout', true)) || (get_user_meta($products['customer_id'], 'Buildout', true) > 0)) {
			$sum = $sum + (get_user_meta($products['customer_id'], 'Buildout', true) * $basic) / 100;
			echo 'SUM Buildout: ' . $sum . ' ..... ';
			// echo 'BASIC 2: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'Buildout', true) * $basic) / 100;
			echo 'SUM Buildout: ' . $sum . ' ..... ';
			// echo 'BASIC 5: ' . $basic . ' ..... ';
		}
	} elseif ($sum_build_frame > 100) {
		$sum = $sum + (20 * $basic) / 100;
		echo 'SUM Buildout: ' . $sum . ' ..... ';
		// echo 'BASIC 5: ' . $basic . ' ..... ';
	}
}

if (($products['property_controltype'] == 403)) {
	if (!empty(get_user_meta($products['customer_id'], 'Concealed_Rod', true)) || (get_user_meta($products['customer_id'], 'Concealed_Rod', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Concealed_Rod', true) * $basic) / 100;
		echo 'SUM Concealed_Rod: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'Concealed_Rod', true) * $basic) / 100;
		echo 'SUM Concealed_Rod: ' . $sum . ' ..... ';
		// echo 'BASIC 6: ' . $basic . ' ..... ';
	}
}
if (($products['property_hingecolour'] == 93)) {
	if (!empty(get_user_meta($products['customer_id'], 'Stainless_Steel', true)) || (get_user_meta($products['customer_id'], 'Stainless_Steel', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Stainless_Steel', true) * $basic) / 100;
		echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'Stainless_Steel', true) * $basic) / 100;
		echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
		// echo 'BASIC 6: ' . $basic . ' ..... ';
	}
}
if ($products['property_hingecolour'] == 186) {
	if (!empty(get_user_meta($products['customer_id'], 'Hidden', true)) || (get_user_meta($products['customer_id'], 'Hidden', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Hidden', true) * $basic) / 100;
		echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
		// echo 'BASIC 2: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'Hidden', true) * $basic) / 100;
		echo 'SUM Stainless_Steel: ' . $sum . ' ..... ';
		// echo 'BASIC 6: ' . $basic . ' ..... ';
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
					echo 'SUM Bay_Angle : ' . $sum . ' ..... ';
					// echo 'BASIC 7: ' . $basic . ' ..... ';
					echo '----- Unghi diferit de  90: ' . $products['property_ba1'] . ' ADAUGARE 10%------';
					$bpost_unghi++;
					break;
				} else {
					$sum = $sum + (get_post_meta(1, 'Bay_Angle', true) * $basic) / 100;
					echo 'SUM Bay_Angle: ' . $sum . ' ..... ';
					// echo 'BASIC 7: ' . $basic . ' ..... ';
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
			echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'B_typeFlexible', true) * $basic) / 100;
			echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
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
				echo 'SUM B_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			} else {
				$sum = $sum + (get_post_meta(1, 'B_Buildout', true) * $basic) / 100;
				echo 'SUM B_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
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
		echo 'SUM G-Post: ' . $sum . ' ..... ';
		// echo 'BASIC 7: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (3 * $basic) / 100;
		echo 'SUM G-Post: ' . $sum . ' ..... ';
		// echo 'BASIC 7: ' . $basic . ' ..... ';
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
				echo 'SUM T_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			} else {
				$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
				echo 'SUM T_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
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
			echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
			echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
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
				echo 'SUM C_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			} else {
				$sum = $sum + (get_post_meta(1, 'C_Buildout', true) * $basic) / 100;
				echo 'SUM C_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			}
		}
	}
}

// ======== Start - special price for gren colors for user Perfect Shutters =========

// Colors 20%
//if ($user_id == 274 || $dealer_id == 274) {
//	// green colors
//	if ($products['property_material'] == 137) {
//		if (($products['property_shuttercolour'] == 101) || ($products['property_shuttercolour'] == 103) || ($products['property_shuttercolour'] == 104) || ($products['property_shuttercolour'] == 105) || ($products['property_shuttercolour'] == 106) || ($products['property_shuttercolour'] == 107) || ($products['property_shuttercolour'] == 108) || ($products['property_shuttercolour'] == 109) || ($products['property_shuttercolour'] == 110) || ($products['property_shuttercolour'] == 111) || ($products['property_shuttercolour'] == 112) || ($products['property_shuttercolour'] == 113) || ($products['property_shuttercolour'] == 114) || ($products['property_shuttercolour'] == 115) || ($products['property_shuttercolour'] == 116) || ($products['property_shuttercolour'] == 117) || ($products['property_shuttercolour'] == 118) || ($products['property_shuttercolour'] == 119) || ($products['property_shuttercolour'] == 120) || ($products['property_shuttercolour'] == 121)) {
//			if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
//				$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
//				echo 'SUM Colors green20%: ' . $sum . ' ..... ';
//				// echo 'BASIC 11: ' . $basic . ' ..... ';
//			} else {
//				$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
//				echo 'SUM Colors green20%: ' . $sum . ' ..... ';
//				// echo 'BASIC 11: ' . $basic . ' ..... ';
//			}
//		}
//	}
//}
// ======== End - special price for gren colors for user Perfect Shutters =========

// Colors 20%
if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
	if (!empty(get_user_meta($products['customer_id'], 'Colors', true)) || (get_user_meta($products['customer_id'], 'Colors', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Colors', true) * $basic) / 100;
		echo 'SUM Colors: ' . $sum . ' ..... ';
		// echo 'BASIC 11: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
		echo 'SUM Colors: ' . $sum . ' ..... ';
		// echo 'BASIC 11: ' . $basic . ' ..... ';
	}
}

// Colors 10%
if (($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263) || ($products['property_shuttercolour'] == 274)) {
	$sum = $sum + (10 * $basic) / 100;
	echo 'SUM Colors: ' . $sum . ' ..... ';
	// echo 'BASIC 11: ' . $basic . ' ..... ';
}

if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232)) {
	if (!empty($products['property_ringpull'] && $products['property_ringpull'] !== 'No')) {
		if (!empty(get_user_meta($products['customer_id'], 'Ringpull', true)) || (get_user_meta($products['customer_id'], 'Ringpull', true) > 0)) {
			$sum = $sum + (get_user_meta($products['customer_id'], 'Ringpull', true) * $products['property_ringpull_volume']);
			echo 'SUM Ringpull: ' . $sum . ' ..... ';
			// echo 'BASIC 8: ' . $basic . ' ..... ';
		} else {
			$sum = $sum + (get_post_meta(1, 'Ringpull', true) * $products['property_ringpull_volume']);
			echo 'SUM Ringpull: ' . $sum . ' ..... ';
			// echo 'BASIC 8: ' . $basic . ' ..... ';
		}
	}
} else {
	echo 'SUM before ringpull: ' . $sum . ' ..... ';
	echo '-(Delete ringpull: ' . $post_id, ')-';
	delete_post_meta($post_id, 'property_ringpull_volume', '');
	delete_post_meta($post_id, 'property_ringpull', 'YES');
}

$horizontal_tpost_add = 1;
if (!empty($products['property_horizontaltpost']) && $products['property_horizontaltpost'] == 'Yes') {
	$horizontal_tpost_add = 2;
}

// Count the number of 'T' or 't' characters
$t_count = countT($products['property_layoutcode']);
if (!empty($products['property_central_lock']) && $products['property_central_lock'] == 'Yes') {
	$t_count_add = ($t_count > 0) ? $t_count : 1;
	$sum = $sum + (get_post_meta(1, 'Central_Lock', true) * $t_count_add * $horizontal_tpost_add);
	echo 'SUM Central_Lock: ' . $sum . ' ..... ';
}

if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {
	// if material biowood and biowood plus add 10% for lock
	if ($products['property_material'] == 6 || $products['property_material'] == 138) {

//		$sum = $sum + (58 * $products['property_locks_volume'] * $horizontal_tpost_add);
		$sum = $sum + (58 * $horizontal_tpost_add);
		echo 'SUM Lock1: ' . $sum . ' ..... ';
	} else {
		if (!empty($products['property_lock_position'])) {
			$val_lock_position = $products['property_lock_position'];
			if ($val_lock_position == 'Central Lock') {
				$sum = $sum + (get_post_meta(1, 'Central_Lock', true) * $horizontal_tpost_add);
			}
			if ($val_lock_position == 'Top & Bottom Lock') {
				$sum = $sum + (get_post_meta(1, 'Top_Bottom_Lock', true) * $horizontal_tpost_add);
			}
			echo 'SUM lock_position: ' . $sum . ' ..... ';
		} else {
			if (!empty(get_user_meta($products['customer_id'], 'Lock', true)) || (get_user_meta($products['customer_id'], 'Lock', true) > 0)) {
				$sum = $sum + (get_user_meta($products['customer_id'], 'Lock', true) * 2 * $horizontal_tpost_add);
				echo 'SUM Lock2: ' . $sum . ' ..... ';
			} else {
				if (!empty(get_post_meta(1, 'Lock', true))) {
					$sum = $sum + (get_post_meta(1, 'Lock', true) * 2 * $horizontal_tpost_add);
					echo 'SUM Lock2: ' . $sum . ' ..... ';
				}
			}
		}
	}
}
// if layout have b t c, calculatePanelsWidth have panels with width < 200 then add to price 30,
if ($products['property_layoutcode'] == 'B' || $products['property_layoutcode'] == 'T' || $products['property_layoutcode'] == 'C') {
	$panels_width = calculatePanelsWidth($products['property_layoutcode'], $products['property_width'], $products);
	// if panels_width have width < 200 then add 30 for each panel
	foreach ($panels_width as $panel) {
		if ($panel < 200) {
			$sum = $sum + 30;
			echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
			// echo 'BASIC 8: ' . $basic . ' ..... ';
		}
	}
} else if ($width_track / nrPanelsCount($products['property_layoutcode']) < 200) {
	$nrPanels = nrPanelsCount($products['property_layoutcode']);
	$sum = $sum + $nrPanels * 30;
	echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
	// echo 'BASIC 8: ' . $basic . ' ..... ';
}

if ($products['property_controltype'] == 387) {
	$nrPanels = nrPanelsCount($products['property_layoutcode']);
	$midCount = midrailDividerCounter($products['property_midrailheight'], $products['property_midrailheight2'], $products['property_midraildivider1'], $products['property_midraildivider2']);
	if (!empty(get_user_meta($products['customer_id'], 'Louver_lock', true)) || (get_user_meta($products['customer_id'], 'Louver_lock', true) > 0)) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'Louver_lock', true) * $nrPanels * $midCount);
	} else {
		$sum = $sum + (get_post_meta(1, 'Louver_lock', true) * $nrPanels * $midCount);
	}
	echo '$nrPanels: ' . $nrPanels . ' ..... ';
	echo '$midCount: ' . $midCount . ' ... (Louver_lock * $nrPanels * $midCount) ..... ';
	echo 'SUM Hidden Rod: ' . $sum . ' ..... ';
}

if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
	if (!empty(get_user_meta($products['customer_id'], 'Spare_Louvres', true)) || (get_user_meta($products['customer_id'], 'Spare_Louvres', true) > 0)) {
		$sum = $sum + ($products['panels_left_right'] * get_user_meta($products['customer_id'], 'Spare_Louvres', true));
		echo ' Panels: ' . $products['panels_left_right'] . ' SUM Spare_Louvres: ' . $sum . ' ..... ';
		// echo 'BASIC 9: ' . $basic . ' ..... ';
	} else {
		$sum = $sum + ($products['panels_left_right'] * get_post_meta(1, 'Spare_Louvres', true));
		echo ' Panels: ' . $products['panels_left_right'] . ' SUM Spare_Louvres: ' . $sum . ' ..... ';
		// echo 'BASIC 9: ' . $basic . ' ..... ';
	}
}

// if( !empty($discount_custom) || $discount_custom > 0 ){
//     $sum = $sum - ($discount_custom*$basic)/100;
//     echo 'SUM 0: '.$sum.'<br>';
//     // echo 'BASIC 0: '.$basic.'<br>';
// }

if ($user_id == 18) {
	if (!empty(get_user_meta($products['customer_id'], 'SeaDelivery', true))) {
		$sum = $sum + (get_user_meta($products['customer_id'], 'SeaDelivery', true));
		echo 'SUM SeaDelivery: ' . $sum . ' ..... ';
		// echo 'BASIC 1: ' . $basic . ' ..... ';
	}
}

echo "Suma_total: " . $sum;

$g = 0;
$t = 0;
$b = 0;
$c = 0;

foreach ($products as $name_attr => $id) {

	update_post_meta($post_id, $name_attr, $id);

	if ($name_attr == "property_layoutcode") {
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

$sections_price = array();
if ($products['property_nr_sections']) {
	for ($sec = 1; $sec <= $products['property_nr_sections']; $sec++) {
		$sections_price[$sec] = $sum;
	}
}

if ($products['property_nr_sections']) {

	for ($i = 1; $i <= $products['property_nr_sections']; $i++) {
		$property_tb = 'property_t_buildout1_' . $i;
		$tposttype = 't-post-type' . $i;
		$property_g1 = 'property_g1';

		if (!empty($products[$property_tb])) {
			update_post_meta($post_id, $property_tb, $products[$property_tb]);
			if (!empty(get_user_meta($user_id, 'T_Buildout', true)) || (get_user_meta($user_id, 'T_Buildout', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'T_Buildout', true) * $basic) / 100;
				$sections_price[$i] = $sections_price[$i] + (get_user_meta($user_id, 'T_Buildout', true) * $basic) / 100;
				echo 'SUM T_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			} else {
				$sum = $sum + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
				$sections_price[$i] = $sections_price[$i] + (get_post_meta(1, 'T_Buildout', true) * $basic) / 100;
				echo 'SUM T_Buildout: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
				break;
			}
		}

		if (!empty($products[$tposttype])) {
			update_post_meta($post_id, $tposttype, $products[$tposttype]);

			if ($products[$tposttype] == 'adjustable') {
				if (!empty(get_user_meta($user_id, 'T_typeAdjustable', true)) || (get_user_meta($user_id, 'T_typeFlexible', true) > 0)) {
					$sum = $sum + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
					$sections_price[$i] = $sections_price[$i] + (get_user_meta($user_id, 'T_typeAdjustable', true) * $basic) / 100;
					echo 'SUM t-post-type: ' . $sum . ' ..... ';
					// echo 'BASIC 7: ' . $basic . ' ..... ';
				} else {
					$sum = $sum + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
					$sections_price[$i] = $sections_price[$i] + (get_post_meta(1, 'T_typeAdjustable', true) * $basic) / 100;
					echo 'SUM t-post-type: ' . $sum . ' ..... ';
					// echo 'BASIC 7: ' . $basic . ' ..... ';
				}
			}
		}

		if ($products[$property_g1]) {
			if (!empty(get_user_meta($user_id, 'G_post', true)) || (get_user_meta($user_id, 'G_post', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'G_post', true) * $basic) / 100;
				$sections_price[$i] = $sections_price[$i] + (get_user_meta($user_id, 'G_post', true) * $basic) / 100;
				echo 'SUM G-Post: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
			} else {
				$sum = $sum + (3 * $basic) / 100;
				$sections_price[$i] = $sections_price[$i] + (3 * $basic) / 100;
				echo 'SUM G-Post: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
			}
		}
	}
}

if ($products['property_motorized'] == 'Yes') {
	$property_nrMotors = get_post_meta($post_id, 'property_nrMotors', true);
	$price_notor = get_post_meta(1, 'motor', true);
	$property_nrRemotes = get_post_meta($post_id, 'property_nrRemotes', true);
	$price_remote = get_post_meta(1, 'remote', true);
	$sum = $sum + $property_nrMotors * $price_notor;
	$sum = $sum + $property_nrRemotes * $price_remote;
	echo 'SUM motorized: ' . $sum . ' ..... ';
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
			} elseif ($products[$property_g]) {
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
echo "<pre>";
print_r($product_attributes);
echo "</pre>";
echo "<br>Total product price: " . $sum . " Euro";
update_post_meta($post_id, '_price', floatval($sum));
update_post_meta($post_id, 'counter_g', $g);
update_post_meta($post_id, 'counter_t', $t);
update_post_meta($post_id, 'counter_b', $b);
update_post_meta($post_id, 'counter_c', $c);
update_post_meta($post_id, '_regular_price', floatval($sum));
update_post_meta($post_id, '_sale_price', floatval($sum));
// update_post_meta($post_id, 'svg_product', $products['svg']);
update_post_meta($post_id, 'attachmentDraw', $products['attachmentDraw']);
update_post_meta($post_id, 'comments_customer', $products['comments_customer']);
echo 'comments_customer' . $products['comments_customer'];

echo $t . ' nr t <br>';
echo $b . ' nr b <br>';
echo $c . ' nr c <br>';
// echo $_POST['svg'];

/*  **********************************************************************
--------------------------  Calcul Suma Dolari --------------------------
**********************************************************************  */

$sum = 0;
$basic = 0;
// Calculate price
if ($products['property_material'] == 187) {
	$sum = $sqm_value * dolarSum('Earth-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 188) {
	$sum = $sqm_value * dolarSum('Ecowood-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 137) {
	$sum = $sqm_value * dolarSum('Green-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 5) {
	$sum = $sqm_value * dolarSum('EcowoodPlus-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 138) {
	$sum = $sqm_value * dolarSum('BiowoodPlus-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 6) {
	$sum = $sqm_value * dolarSum('Biowood-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}
if ($products['property_material'] == 139) {
	$sum = $sqm_value * dolarSum('Supreme-dolar', $user_id);
	echo 'SUM 1 dolar: ' . $sum . ' ..... ';
	$basic = $sum;
	// echo 'BASIC 1 dolar: ' . $basic . ' ..... ';
}

//style

if ($products['property_material'] == 139) {
	if ($products['property_bladesize'] == 52) {
		$sum = $sum + (dolarSum('Flat_Louver-dolar', $user_id) * $basic) / 100;
		echo 'SUM bladesize: ' . $sum . ' ..... ';
		// echo 'BASIC bladesize: ' . $basic . ' ..... ';
	}
}

if (($products['property_style'] == 221) || ($products['property_style'] == 227) || ($products['property_style'] == 226) || ($products['property_style'] == 222) || ($products['property_style'] == 228) || ($products['property_style'] == 230) || ($products['property_style'] == 231) || ($products['property_style'] == 232) || ($products['property_style'] == 38) || ($products['property_style'] == 39) || $products['property_style'] == 42 || $products['property_style'] == 43) {
	$val = dolarSum('Solid-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 2: ' . $sum . ' ..... ';
	// echo 'BASIC 2: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 33 || $products['property_style'] == 43) {
	$val = dolarSum('Shaped-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 3: ' . $sum . ' ..... ';
	// echo 'BASIC 3: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 34) {
	$val = dolarSum('French_Door-dolar', $user_id);
	$sum = $sum + $val;
	echo 'SUM 3: ' . $sum . ' ..... ';
	// echo 'BASIC 3: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 35) {
	$val = dolarSum('Tracked-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 4: ' . $sum . ' ..... ';
	// echo 'BASIC 4: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 37) {
	$val = dolarSum('TrackedByPass-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 4 dolar: ' . $sum . ' ..... ';
	// echo 'BASIC 4: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 36 || $products['property_style'] == 42) {
	$val = dolarSum('Arched-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM Arched 3: ' . $sum . ' ..... ';
	// echo 'BASIC 3: ' . $basic . ' ..... ';
}
if ($products['property_style'] == 229 || $products['property_style'] == 233) {
	$val = dolarSum('Combi-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 10: ' . $sum . ' ..... ';
	// echo 'BASIC 10: ' . $basic . ' ..... ';
}

// Frame Type
if ($products['property_frametype'] == 171) {

	//$sum = get_post_meta(1,'Combi',true)/1;
	$val = dolarSum('P4028X-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM Combi: ' . $sum . ' ..... ';
	// echo 'BASIC 10: ' . $basic . ' ..... ';

	$blackoutblind = dolarSum('blackoutblind-dolar', $user_id);
	if (!empty($blackoutblind) || ($blackoutblind > 0)) {

		$sum = $sum + $blackoutblind * $sqm_value_blackout;
		echo 'SUM blackoutblind: ' . $sum . ' ..... ';
		// echo 'BASIC 10: ' . $basic . ' ..... ';
	}

	if ($products['property_tposttype']) {
		$tposttype_blackout = dolarSum('tposttype_blackout-dolar', $user_id);
		if (!empty($tposttype_blackout) || ($tposttype_blackout > 0)) {
			$sum = $sum + ($tposttype_blackout * $basic) / 100;
			echo 'SUM tposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		}
	}

	if (!empty($products['bay-post-type'])) {
		$bposttype_blackout = dolarSum('bposttype_blackout-dolar', $user_id);
		if (!empty($bposttype_blackout) || ($bposttype_blackout > 0)) {
			$sum = $sum + ($bposttype_blackout * $basic) / 100;
			echo 'SUM bposttype_blackout: ' . $sum . ' ..... ';
			// echo 'BASIC 10: ' . $basic . ' ..... ';
		}
	}
}
if ($products['property_frametype'] == 322) {
	$val = dolarSum('P4008T-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM P4008T: ' . $sum . ' ..... ';
	// echo 'BASIC 10: ' . $basic . ' ..... ';
}
if ($products['property_frametype'] == 353) {
	$val = dolarSum('4008T-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 4008T: ' . $sum . ' ..... ';
	// echo 'BASIC 10: ' . $basic . ' ..... ';
}
if ($products['property_frametype'] == 319) {
	$val = dolarSum('P4008W-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM Combi: ' . $sum . ' ..... ';
	// echo 'BASIC 10: ' . $basic . ' ..... ';
}

if (strlen($products['property_builtout']) > 0 && !empty($products['property_builtout'])) {
	$frdepth = $framesType[$products['property_frametype']];
	$sum_build_frame = $frdepth + $products['property_builtout'];
	if ($sum_build_frame <= 100) {
		$val = dolarSum('Buildout-dolar', $user_id);
		if (!empty($val) || ($val > 0)) {
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM Buildout: ' . $sum . ' ..... ';
			// echo 'BASIC 2: ' . $basic . ' ..... ';
		}
	} elseif ($sum_build_frame > 100) {
		update_post_meta($post_id, 'sum_build_frame', true);
		$sum = $sum + (20 * $basic) / 100;
		echo 'SUM Buildout: ' . $sum . ' ..... ';
		// echo 'BASIC 5: ' . $basic . ' ..... ';
	}
}
if ($products['property_controltype'] == 403) {
	$result = 0;
	if (!empty(get_user_meta($user_id, 'Concealed_Rod-dolar', true)) || (get_user_meta($user_id, 'Concealed_Rod-dolar', true) > 0)) {
		$result = get_user_meta($user_id, 'Concealed_Rod-dolar', true);
		$sum = $sum + ($basic * $result) / 100;
	} else {
		$result = get_post_meta(1, 'Concealed_Rod-dolar', true);
		$sum = $sum + ($basic * $result) / 100;
//		$sum = $sum + $result * $sqm_value;
	}
	echo 'SUM 6: ' . $sum . ' ..... ';
	// echo 'BASIC 6: ' . $basic . ' ..... ';
}
if ($products['property_controltype'] == 387) {
	$result = 0;
	if (!empty(get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true)) || (get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true) > 0)) {
		$result = get_user_meta($user_id, 'Hidden_Rod_with_Locking_System-dolar', true);
		$sum = $sum + ($basic * $result) / 100;
	} else {
		$result = get_post_meta(1, 'Hidden_Rod_with_Locking_System-dolar', true);
		$sum = $sum + $result * $sqm_value;
	}
	echo 'SUM 6: ' . $sum . ' ..... ';
	echo 'BASIC 6: ' . $basic . '<br>';
}
if ($products['property_hingecolour'] == 93) {
	$val = dolarSum('Stainless_Steel-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 6: ' . $sum . ' ..... ';
	// echo 'BASIC 6: ' . $basic . ' ..... ';
}
if ($products['property_hingecolour'] == 186) {
	$val = dolarSum('Hidden-dolar', $user_id);
	$sum = $sum + ($val * $basic) / 100;
	echo 'SUM 6: ' . $sum . ' ..... ';
	// echo 'BASIC 6: ' . $basic . ' ..... ';
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
				echo 'SUM 7: ' . $sum . ' ..... ';
				// echo 'BASIC 7: ' . $basic . ' ..... ';
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
		echo 'SUM B_typeFlexible: ' . $sum . ' ..... ';
		// echo 'BASIC 7: ' . $basic . ' ..... ';
	}
}

// If Bpost have buidout add 7%
$b_buildout = array('property_b_buildout1', 'property_b_buildout2', 'property_b_buildout3', 'property_b_buildout4', 'property_b_buildout5', 'property_b_buildout6', 'property_b_buildout7', 'property_b_buildout8', 'property_b_buildout9', 'property_b_buildout10', 'property_b_buildout11', 'property_b_buildout12', 'property_b_buildout13', 'property_b_buildout14', 'property_b_buildout15');
foreach ($b_buildout as $property_bb) {
	if (!empty($property_bb)) {
		if (!empty($products[$property_bb])) {
			$val = dolarSum('B_Buildout-dolar', $user_id);
			$sum = $sum + ($val * $basic) / 100;
			echo 'SUM 7: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
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
			echo 'SUM 7: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
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
			echo 'SUM 7: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
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
			echo 'SUM 7: ' . $sum . ' ..... ';
			// echo 'BASIC 7: ' . $basic . ' ..... ';
			break;
		}
	}
}

// Stained SUPREME 14.57%
if ($products['property_material'] == 139) {
	if (($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125)) {
		$sum = $sum + (14.57 * $basic) / 100;
		echo 'SUM 11: ' . $sum . ' ..... ';
		// echo 'BASIC 11: ' . $basic . ' ..... ';
	}
}

// Brushed BIOWOOD 35.64%
if ($products['property_material'] == 138) {
	if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
		$sum = $sum + (35.64 * $basic) / 100;
		echo 'SUM 11: ' . $sum . ' ..... ';
		// echo 'BASIC 11: ' . $basic . ' ..... ';
	}
}

// Painted EARTH shutters  6.87%
if ($products['property_material'] == 187) {
	if (($products['property_shuttercolour'] == 258) || ($products['property_shuttercolour'] == 259) || ($products['property_shuttercolour'] == 260) || ($products['property_shuttercolour'] == 261) || ($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263)) {
		$sum = $sum + (6.87 * $basic) / 100;
		echo 'SUM 11: ' . $sum . ' ..... ';
		// echo 'BASIC 11: ' . $basic . ' ..... ';
	}
}

if (!empty($products['property_ringpull'] && $products['property_ringpull'] !== 'No')) {
	$val = dolarSum('Ringpull-dolar', $user_id);
	$sum = $sum + ($val * $products['property_ringpull_volume']);
	echo 'SUM 8: ' . $sum . ' ..... ';
	// echo 'BASIC 8: ' . $basic . ' ..... ';
}

if (!empty($products['property_locks'] && $products['property_locks'] == 'Yes')) {
	$val = dolarSum('Lock-dolar', $user_id);
	$sum = $sum + ($val * 2);
	echo 'SUM 8: ' . $sum . ' ..... ';
	// echo 'BASIC 8: ' . $basic . ' ..... ';
}
if (!empty($products['property_sparelouvres'] && $products['property_sparelouvres'] == 'Yes')) {
	$val = dolarSum('Spare_Louvres-dolar', $user_id);
	$sum = $sum + ($products['panels_left_right'] * $val);
	echo 'SUM 9: ' . $sum . ' ..... ';
	// echo 'BASIC 9: ' . $basic . ' ..... ';
}

// if layout have b t c, calculatePanelsWidth have panels with width < 200 then add to price 30,
if ($products['property_layoutcode'] == 'B' || $products['property_layoutcode'] == 'T' || $products['property_layoutcode'] == 'C') {
	$panels_width = calculatePanelsWidth($products['property_layoutcode'], $products['property_width'], $products);
	// if panels_width have width < 200 then add 30 for each panel
	foreach ($panels_width as $panel) {
		if ($panel < 200) {
			$sum = $sum + 30;
			echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
			// echo 'BASIC 8: ' . $basic . ' ..... ';
		}
	}
} else if ($width_track / nrPanelsCount($products['property_layoutcode']) < 200) {
	$nrPanels = nrPanelsCount($products['property_layoutcode']);
	$sum = $sum + $nrPanels * 30;
	echo 'SUM calcPanelsWidth: ' . $sum . ' ..... ';
	// echo 'BASIC 8: ' . $basic . ' ..... ';
}

if ($products['property_motorized'] == 'Yes') {
	$property_nrMotors = get_post_meta($post_id, 'property_nrMotors', true);
	$price_notor = get_post_meta(1, 'motor-dolar', true);
	$property_nrRemotes = get_post_meta($post_id, 'property_nrRemotes', true);
	$price_remote = get_post_meta(1, 'remote-dolar', true);
	$sum = $sum + $property_nrMotors * $price_notor;
	$sum = $sum + $property_nrRemotes * $price_remote;
}

//print_r($frames);
echo "Suma_total DOLARS: " . $sum . '<br><br><br>';

update_post_meta($post_id, 'dolar_price', floatval($sum));
/*  **********************************************************************
--------------------------   END - Calcul Suma Dolari --------------------------
**********************************************************************  */

if (!empty($products['order_edit']) && !empty($products['edit_customer'])) {
	$color = 0;

	$order = wc_get_order($products['order_edit']);

	echo ' ----- Edit order ------ ';

	$products_cart = array();
	$other_color_earth = 0;
	$other_color = 0;
	$color = 0;
	$sqm_order = 0;
	$earth_color_price = 0;
	foreach ($order->get_items() as $cart_item_key => $cart_item) {
		// get id product
		$product_id = $cart_item['product_id'];
		// make array with products id of cart items
		$products_cart[] = $product_id;
		// Check for specific product IDs and change quantity
		//        $cantitate = $products['quantity'];
		//        if ($product_id == $post_id) {
		//            WC()->cart->set_quantity($cart_item_key, $cantitate); // Change quantity
		//        }
		$col = get_post_meta($post_id, 'other_color', true);
		$color = intval($color) + intval($col);

		$have_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);

		// if is not one of the special color id as product then make functions
		if (!in_array($product_id, array(72951, 337))) {
			// calcualte order sqm for earth other color condition
			// start - calculate sqm total order
			$sqm = get_post_meta($product_id, 'property_total', true);
			if (!empty($sqm)) $sqm_order = (float)$sqm_order + (float)$sqm;
			// end - calculate sqm total order

			// get material
			$materials = array(187 => 'Earth', 137 => 'Green', 5 => 'EcowoodPlus', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood');
			$material = get_post_meta($product_id, 'property_material', true);
			// calculate price of basic price of item without add
			$basic_price = (float)$sqm * (float)get_post_meta(1, $materials[$material], true);

			$earth_color_price = $earth_color_price + ($basic_price * 0.1);

			if (!empty($have_shuttercolour_other) && $have_shuttercolour_other != '') {
				if ($material == 187) {
					$other_color_earth = 1;
				} else {
					$other_color = 1;
				}
			}
		}
	}

	// add custom color as separate product
	$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
	if (!empty($property_shuttercolour_other) && $property_shuttercolour_other != '') {
		/**
		 * if material is earth
		 * Adauga 10% daca ORDER sqm >= 10sqm
		 * iar daca ORDER sqm < 10sqm adauga doar 100£
		 */
		$other_color_earth_id = 72951; // id prod = 72951
		$other_color_prod_id = 337;

		// if other color earth exists in one item from cart / order
		if ($products['property_material'] == 187) {
			$product = wc_get_product($other_color_earth_id);
			// if order have total sqm < 10  then apply other color item price basic else calculate 10% of all order/cart
			if ($sqm_order < 10) {
				if (!array_key_exists($other_color_earth_id, $products_cart)) {
					wc_get_order($products['order_edit'])->add_product($product, 1);
				}
			} else {
				/*
			 * se adauga $earth_color_price - Daca orderul are peste 10sqm, atunci de adauga 10% din pretul orderului
			 */
				if (!array_key_exists($other_color_earth_id, $products_cart)) {
					wc_get_order($products['order_edit'])->add_product($product, 1);
				}
				foreach ($order->get_items() as $key => $item) {
					$product_id = $item['data']->get_id();
					if ($product_id == $other_color_earth_id) {
						$item['data']->set_price($earth_color_price);
					}
				}
			}
		}
		if ($products['property_material'] != 187) {
			$product = wc_get_product($other_color_prod_id);
			if (!in_array($other_color_prod_id, $products_cart)) {
				wc_get_order($products['order_edit'])->add_product($product, 1);
			}
		}
	}

	// ---------------------- Recalculate total of order after add item in edit order ----------------------

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
				if ($train_price === null || $train_price === '') {
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

			// ✅ VALIDARE CANTITATE - Adaugă această verificare
			if (empty($prod_qty) || $prod_qty <= 0) {
				$prod_qty = 1;
				// Actualizează și meta-ul produsului cu valoarea corectată
				update_post_meta($product_id, 'quantity', 1);
			}

			$dolar_price = get_post_meta($product_id, 'dolar_price', true);

			$sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

			$sqm = get_post_meta($product_id, 'property_total', true);
			$property_total_sqm = floatval($sqm) * floatval($prod_qty);
			$totaly_sqm = $totaly_sqm + $property_total_sqm;

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
$other_color_earth = 0;
$other_color = 0;
$color = 0;
$sqm_order = 0;
$earth_color_price = 0;
// parcurgere cart
foreach ($cart as $cart_item_key => $cart_item) {
	// get id product
	$product_id = $cart_item['product_id'];
	// make array with products id of cart items
	$products_cart[] = $product_id;
	// Check for specific product IDs and change quantity
	//        $cantitate = $products['quantity'];
	//        if ($product_id == $post_id) {
	//            WC()->cart->set_quantity($cart_item_key, $cantitate); // Change quantity
	//        }
	$col = get_post_meta($post_id, 'other_color', true);
	$color = intval($color) + intval($col);

	$have_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);

	// if is not one of the special color id as product then make functions
	if (!in_array($product_id, array(72951, 337))) {
		// calcualte order sqm for earth other color condition
		// start - calculate sqm total order
		$sqm = get_post_meta($product_id, 'property_total', true);
		if (!empty($sqm)) $sqm_order = (float)$sqm_order + (float)$sqm;
		// end - calculate sqm total order

		// get material
		$materials = array(187 => 'Earth', 137 => 'Green', 5 => 'EcowoodPlus', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'Supreme', 188 => 'Ecowood');
		$material = get_post_meta($product_id, 'property_material', true);
		// calculate price of basic price of item without add
		$basic_price = (float)$sqm * (float)get_post_meta(1, $materials[$material], true);

		$earth_color_price = $earth_color_price + ($basic_price * 0.1);

		if (!empty($have_shuttercolour_other) && $have_shuttercolour_other != '') {
			if ($material == 187) {
				$other_color_earth = 1;
			} else {
				$other_color = 1;
			}
		}
	}
}
// add custom color as separate product
$property_shuttercolour_other = get_post_meta($post_id, 'property_shuttercolour_other', true);
if (!empty($property_shuttercolour_other) && $property_shuttercolour_other != '') {
	/**
	 * if material is earth
	 * Adauga 10% daca ORDER sqm >= 10sqm
	 * iar daca ORDER sqm < 10sqm adauga doar 100£
	 */
	$other_color_earth_id = 72951; // id prod = 72951
	$other_color_prod_id = 337;

	// if other color earth exists in one item from cart / order
	if ($products['property_material'] == 187) {
		// if order have total sqm < 10  then apply other color item price basic else calculate 10% of all order/cart
		if ($sqm_order < 10) {
			if (!array_key_exists($other_color_earth_id, $products_cart)) {
				WC()->cart->add_to_cart($other_color_earth_id, 1);
			}
		} else {
			/*
		 * se adauga $earth_color_price - Daca orderul are peste 10sqm, atunci de adauga 10% din pretul orderului
		 */
			if (!array_key_exists($other_color_earth_id, $products_cart)) {
				WC()->cart->add_to_cart($other_color_earth_id, 1);
			}
			foreach ($cart as $key => $item) {
				$product_id = $item['data']->get_id();
				if ($product_id == $other_color_earth_id) {
					$item['data']->set_price($earth_color_price);
				}
			}
		}
	}
	if ($products['property_material'] != 187) {
		if (!in_array($other_color_prod_id, $products_cart)) {
			WC()->cart->add_to_cart($other_color_prod_id, 1);
		}
	}
}
if ($other_color_earth == 0) {
	$product_id = 72951;
	$product_cart_id = WC()->cart->generate_cart_id($product_id);
	$cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
	if ($cart_item_key) WC()->cart->remove_cart_item($cart_item_key);
}
if ($other_color == 0) {
	$product_id = 337;
	$product_cart_id = WC()->cart->generate_cart_id($product_id);
	$cart_item_key = WC()->cart->find_product_in_cart($product_cart_id);
	if ($cart_item_key) WC()->cart->remove_cart_item($cart_item_key);
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


function midrailDividerCounter($mid1, $mid2, $dvd1, $dvd2)
{
	$count = 1;
	if (!empty($mid1) && $mid1 > 1) $count++;
	if (!empty($mid2) && $mid2 > 1) $count++;
	if (!empty($dvd1) && $dvd1 > 1) $count++;
	if (!empty($dvd2) && $dvd2 > 1) $count++;
	return $count;
}


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