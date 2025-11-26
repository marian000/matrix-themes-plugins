<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
	'wp-load.php');

$atribute = get_post_meta(1, 'attributes_array', true);

parse_str($_POST['prod'], $products);
echo "<pre>";
//print_r($products); // Only for print array
echo "</pre>";

echo "--------------------" . $products['page_title'];

$user_id = get_current_user_id();
$dealer_id = get_user_meta($user_id, 'company_parent', true);

global $woocommerce;
$post_id = $products['product_id_updated'];
echo 'specific_id id: ' . $post_id;
echo ' property_total: ';
echo $products['property_total'];

update_post_meta($post_id, 'property_volume', $products['property_total']);
update_post_meta($post_id, 'batten_type', $products['batten_type']);

$sum = 0;
$i = 0;

$discount_custom = get_user_meta($products['customer_id'], 'discount_custom', true);

$batten_price = get_post_meta(1, 'BattenStandard', true);
$battenCustom_price = get_post_meta(1, 'BattenCustom', true);

$batten_type = get_post_meta($post_id, 'batten_type', true);

$product_attributes = array();
echo $post_id = $products['product_id_updated'];

// Calculate price
echo '$batten_type 1: ' . $batten_type . '<br>';

//    if ($batten_type == 'custom') {

if (!empty(get_user_meta($user_id, 'BattenCustom', true)) || (get_user_meta($user_id, 'BattenCustom', true) > 0)) {
	if ($user_id == 18) {
		$sum = ($products['property_total'] * get_user_meta($user_id, 'BattenCustom', true)) + (($products['property_total'] * get_user_meta($user_id, 'Batten', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
		echo 'SUM Earth: ' . $sum . '<br>';
		$basic = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
		echo 'BASIC 1: ' . $basic . '<br>';
	} else {
		$sum = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
		echo 'SUM Earth: ' . $sum . '<br>';
		$basic = $products['property_total'] * get_user_meta($user_id, 'BattenCustom', true);
		echo 'BASIC 1: ' . $basic . '<br>';
	}
} else {
	$sum = $products['property_total'] * get_post_meta(1, 'BattenCustom', true);
	echo 'SUM Earth: ' . $sum . '<br>';
	$basic = $products['property_total'] * get_post_meta(1, 'BattenCustom', true);
	echo 'BattenCustom 1: ' . get_post_meta(1, 'BattenCustom', true) . '<br>';
}

// ======== Start - special price for gren colors for user Perfect Shutters =========

// Colors 20%
if ($user_id == 274 || $dealer_id == 274) {
	// green colors
	if ($products['property_material'] == 137) {
		if (($products['property_shuttercolour'] == 101) || ($products['property_shuttercolour'] == 103) || ($products['property_shuttercolour'] == 104) || ($products['property_shuttercolour'] == 105) || ($products['property_shuttercolour'] == 106) || ($products['property_shuttercolour'] == 107) || ($products['property_shuttercolour'] == 108) || ($products['property_shuttercolour'] == 109) || ($products['property_shuttercolour'] == 110) || ($products['property_shuttercolour'] == 111) || ($products['property_shuttercolour'] == 112) || ($products['property_shuttercolour'] == 113) || ($products['property_shuttercolour'] == 114) || ($products['property_shuttercolour'] == 115) || ($products['property_shuttercolour'] == 116) || ($products['property_shuttercolour'] == 117) || ($products['property_shuttercolour'] == 118) || ($products['property_shuttercolour'] == 119) || ($products['property_shuttercolour'] == 120) || ($products['property_shuttercolour'] == 121)) {
			if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
				$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . '<br>';
				echo 'BASIC 11: ' . $basic . '<br>';
			} else {
				$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
				echo 'SUM Colors: ' . $sum . '<br>';
				echo 'BASIC 11: ' . $basic . '<br>';
			}
		}
	}
}
// ======== END - special price for gren colors for user Perfect Shutters =========

// Colors 20%
if (($products['property_shuttercolour'] == 264) || ($products['property_shuttercolour'] == 265) || ($products['property_shuttercolour'] == 266) || ($products['property_shuttercolour'] == 267) || ($products['property_shuttercolour'] == 268) || ($products['property_shuttercolour'] == 269) || ($products['property_shuttercolour'] == 270) || ($products['property_shuttercolour'] == 271) || ($products['property_shuttercolour'] == 272) || ($products['property_shuttercolour'] == 273) || ($products['property_shuttercolour'] == 128) || ($products['property_shuttercolour'] == 257) || ($products['property_shuttercolour'] == 127) || ($products['property_shuttercolour'] == 126) || ($products['property_shuttercolour'] == 220) || ($products['property_shuttercolour'] == 130) || ($products['property_shuttercolour'] == 253) || ($products['property_shuttercolour'] == 131) || ($products['property_shuttercolour'] == 129) || ($products['property_shuttercolour'] == 254) || ($products['property_shuttercolour'] == 132) || ($products['property_shuttercolour'] == 255) || ($products['property_shuttercolour'] == 134) || ($products['property_shuttercolour'] == 122) || ($products['property_shuttercolour'] == 123) || ($products['property_shuttercolour'] == 133) || ($products['property_shuttercolour'] == 256) || ($products['property_shuttercolour'] == 166) || ($products['property_shuttercolour'] == 124) || ($products['property_shuttercolour'] == 125) || ($products['property_shuttercolour'] == 111)) {
	if (!empty(get_user_meta($user_id, 'Colors', true)) || (get_user_meta($user_id, 'Colors', true) > 0)) {
		$sum = $sum + (get_user_meta($user_id, 'Colors', true) * $basic) / 100;
		echo 'SUM Colors: ' . $sum . '<br>';
		echo 'BASIC 11: ' . $basic . '<br>';
	} else {
		$sum = $sum + (get_post_meta(1, 'Colors', true) * $basic) / 100;
		echo 'SUM Colors: ' . $sum . '<br>';
		echo 'BASIC 11: ' . $basic . '<br>';
	}
}
// Colors 10%
if (($products['property_shuttercolour'] == 262) || ($products['property_shuttercolour'] == 263) || ($products['property_shuttercolour'] == 274)) {
	$sum = $sum + (10 * $basic) / 100;
	echo 'SUM Colors: ' . $sum . '<br>';
	echo 'BASIC 11: ' . $basic . '<br>';
}

//    } elseif( $batten_type == 'standard') {
//
//        if (!empty(get_user_meta($user_id, 'BattenStandard', true)) || (get_user_meta($user_id, 'BattenStandard', true) > 0)) {
//            if ($user_id == 18) {
//                $sum = ($products['property_total'] * get_user_meta($user_id, 'BattenStandard', true)) + (($products['property_total'] * get_user_meta($user_id, 'BattenStandard', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
//                echo 'SUM Earth: ' . $sum . '<br>';
//                $basic = $products['property_total'] * get_user_meta($user_id, 'BatBattenStandardten', true);
//                echo 'BASIC 1: ' . $basic . '<br>';
//            } else {
//                $sum = $products['property_total'] * get_user_meta($user_id, 'BattenStandard', true);
//                echo 'SUM Earth: ' . $sum . '<br>';
//                $basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard', true);
//                echo 'BASIC 1: ' . $basic . '<br>';
//            }
//        } else {
//            $sum = $products['property_total'] * get_post_meta(1, 'BattenStandard', true);
//            echo 'SUM Earth: ' . $sum . '<br>';
//            $basic = $products['property_total'] * get_post_meta(1, 'BattenStandard', true);
//            echo 'BASIC 1: ' . $basic . '<br>';
//            echo 'BattenStandard 1: ' . get_post_meta(1, 'BattenStandard', true) . '<br>';
//        }
//
//    }

//
//    $sum = $products['property_total'] * 5000;
//echo 'SUM 1: ' . $sum . '<br>';
//$basic = $products['property_total'] * 5000;
//echo 'BASIC 1: ' . $basic . '<br>';

echo "Suma_total: " . $sum;

$product_attributes = array();
foreach ($products as $name_attr => $id) {
	update_post_meta($post_id, $name_attr, $id);
	if (!is_array($id)) {
		//echo $product.'<br>';
		//$sum = $sum + $id;

		if ($name_attr == "property_width") {
			$width = $id;
			echo "gasit width";
		}
		if ($name_attr == "property_height") {
			$height = $id;
			echo "gasit height";
		}
		echo '<br>' . $width . '<br>';
		echo '<br>' . $height . '<br>';

		//    $sum = ($width*$height)/1000/4;
		//    echo $sum;

		if (array_key_exists($id, $atribute)) {

			if ($name_attr == "product_id" || $name_attr == "property_width" || $name_attr == "property_height" || $name_attr == "property_midrailheight" || $name_attr == "property_builtout" || $name_attr == "property_layoutcode" || $name_attr == "quantity") {
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
unset($product_attributes['shutter_svg']);
update_post_meta($post_id, '_product_attributes', $product_attributes);
echo "<pre>";
print_r($product_attributes);
echo "</pre>";
echo "<br>Total product price: " . $sum . " Euro";
update_post_meta($post_id, '_price', floatval($sum));
update_post_meta($post_id, '_regular_price', floatval($sum));
update_post_meta($post_id, '_sale_price', floatval($sum));

if (!empty($products['order_item_id'])) {
	wc_update_order_item_meta($products['order_item_id'], '_qty', $products['quantity']);
}

/*  **********************************************************************
--------------------------   START - Calcul Suma Dolari --------------------------
**********************************************************************  */
$sum_dolar = 0;
$i = 0;

$discount_custom = get_user_meta($user_id, 'discount_custom', true);

// Calculate price
if (!empty(get_user_meta($user_id, 'BattenStandard-dolar', true)) || (get_user_meta($user_id, 'BattenStandard-dolar', true) > 0)) {
	if ($user_id == 18) {
		$sum_dolar = ($products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true)) + (($products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true)) * get_user_meta($user_id, 'Earth_tax', true)) / 100;
		echo 'SUM Earth: ' . $sum_dolar . '<br>';
		$basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
		echo 'BASIC 1: ' . $basic . '<br>';
	} else {
		$sum_dolar = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
		echo 'SUM Earth: ' . $sum_dolar . '<br>';
		$basic = $products['property_total'] * get_user_meta($user_id, 'BattenStandard-dolar', true);
		echo 'BASIC 1: ' . $basic . '<br>';
	}
} else {
	$sum_dolar = $products['property_total'] * get_post_meta(1, 'BattenStandard-dolar', true);
	echo 'SUM Earth: ' . $sum_dolar . '<br>';
	$basic = $products['property_total'] * get_post_meta(1, 'BattenStandard-dolar', true);
	echo 'BASIC 1: ' . $basic . '<br>';
}

//$sum_dolar = $products['property_total'] * 4300;
//echo 'SUM 1: ' . $sum . '<br>';
//$basic = $products['property_total'] * 4300;
//echo 'BASIC 1: ' . $basic . '<br>';

echo "Suma_total: " . $sum_dolar;

echo "<br>Total product price: " . $sum_dolar . " Euro";

update_post_meta($post_id, 'dolar_price', $sum_dolar);

/*  **********************************************************************
--------------------------   END - Calcul Suma Dolari --------------------
**********************************************************************  */

global $woocommerce;
//$woocommerce->cart->add_to_cart( $post_id );
echo "Added to Cart";

$color = 0;

echo 'specific_id id: ' . $post_id;
$cart = WC()->cart->get_cart();
echo '<br>cantitate: ' . $products['quantity'];
$products_cart = array();
foreach ($cart as $cart_item_key => $cart_item) {
	$product_id = $cart_item['product_id'];
	$products_cart[] = $product_id;
	// Check for specific product IDs and change quantity
	echo '<br>$product_id: ' . $product_id;
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
