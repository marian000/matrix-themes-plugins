<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.8.0
 */

if (!defined('ABSPATH')) {
	exit;
}

$cart_id = BMC\WooCommerce\MultiSession\customer_id();
$my_session = BMC\WooCommerce\MultiSession\get_session($cart_id, get_current_user_id());
$cart_type = $my_session['carts'][$cart_id]['type'];
?>
<table class="shop_table woocommerce-checkout-review-order-table">
    <thead>
    <tr>
        <th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
        <th class="product-total"><?php _e('Total', 'woocommerce'); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php
	do_action('woocommerce_review_order_before_cart_contents');

	foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
		$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

		if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
			?>
            <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                <td class="product-name">
					<?php echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;'; ?>
					<?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times; %s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); ?>
					<?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                </td>
                <td class="product-total">
					<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                </td>
            </tr>
			<?php
		}
	}

	do_action('woocommerce_review_order_after_cart_contents');
	?>
    </tbody>
    <tfoot>

	<?php

	global $woocommerce;
	$items = $woocommerce->cart->get_cart();
	$total = 0;
	$total_prods_delivery = 0;
	$other_color = 0;

	$products_cart = array();
	foreach ($items as $item => $value) {
		//print_r($value['product_id']);
		$term_slug = '';
		$other_color_earth = 0;
		$fob_components = false;
		$prod_id = $value['product_id'];
		$products_cart[] = $prod_id;

// Preluăm meta valorile
		$property_total = get_post_meta($prod_id, 'property_total', true);
		$property_delivery_price = get_post_meta($prod_id, 'train_delivery', true);

// Convertim la float pentru a evita "non-numeric value"
		$property_delivery_price = floatval($property_delivery_price);

// Adunăm
		if ($cart_type == 'Awning') {
			$sqm = floatval($value['awning_sqm']);

			// Inițializăm prețul suplimentar cu 0
			$sqm_price = 0;

			// Dacă e definită o suprafață > 0, calculăm prețul pe baza tren_price
			if ($sqm > 0) {
				$train_price = get_user_meta(get_current_user_id(), 'train_price', true);

				// Dacă train_price nu este setat la utilizator, îl preluăm din meta (ID = 1)
				if (empty($train_price)) {
					$train_price = (float)get_post_meta(1, 'train_price', true);
				}

				// Stocăm datele pentru prețul “pe tren” și totalul pe tren
				$value['price_train'] = (float)$train_price;
				$sqm_price = $sqm * (float)$train_price;
			}
			$total_prods_delivery += $sqm_price;
		} else {
			$total_prods_delivery += $property_delivery_price;
		}
		$total += (float)$property_total;

		$material = get_post_meta($prod_id, 'property_material', true);
		$property_shuttercolour_other = get_post_meta($prod_id, 'property_shuttercolour_other', true);
		if (!empty($property_shuttercolour_other)) {
			if ($material == 187) {
				$other_color_earth = 1;
			} else {
				$other_color = 1;
			}
		}

		$term_list = wp_get_post_terms($prod_id, 'product_cat', array("fields" => "all"));
		// Check if $term_list is an array and is not empty
		if (is_array($term_list) && !empty($term_list)) {
			// Check if $term_list[0] is an object
			if (is_object($term_list[0])) {
				$term_slug = $term_list[0]->slug;
				if ($term_slug == 'components-fob') {
					$fob_components = true;
				}
			}
		}
	}

	$comp_visible = 'none';
	$show_spec = false;
	if ($term_slug != 'components' && $term_slug != 'components-fob') {
		$comp_visible = 'table-row';
		$show_spec = true;
	}

	$other_color_prod_id = 337;
	$other_color_earth_prod_id = 72951;
	if ($other_color == 1) {
		if (!in_array($other_color_prod_id, $products_cart)) {
			WC()->cart->add_to_cart($other_color_prod_id, 1);
		}
	} else {
		if (in_array($other_color_prod_id, $products_cart)) {
			WC()->cart->remove_cart_item($other_color_prod_id);
		}
	}
	if ($other_color_earth == 1) {
		if (!in_array($other_color_earth_prod_id, $products_cart)) {
			WC()->cart->add_to_cart($other_color_earth_prod_id, 1);
		}
	} else {
		if (in_array($other_color_earth_prod_id, $products_cart)) {
			WC()->cart->remove_cart_item($other_color_earth_prod_id);
		}
	}

	$shipping_cost = WC()->cart->get_cart_shipping_total();
	$shipping_taxes = WC()->cart->get_shipping_taxes(); // Use the getter method
	$shipping_total_without_tax = WC()->cart->get_shipping_total() - (isset($shipping_taxes[1]) ? $shipping_taxes[1] : 0); // Use the retrieved taxes
	$shipping_total_with_tax = (float)WC()->cart->get_cart_shipping_total();

	//
	//    if( get_current_user_id() == 199) {
	//              echo '<pre>';
	//              print_r(WC()->cart->get_shipping_tax()[1]);
	//              echo '</pre>';
	//              echo WC()->cart->shipping_taxes[1];
	//    }
	$total = WC()->cart->get_cart_subtotal();
	$amount = (float)preg_replace('#[^\d.]#', '', $woocommerce->cart->get_cart_total());

	$no_view_price = (get_user_meta(get_current_user_id(), 'view_price', true) == 'no') ? true : false;

	if (!$no_view_price) {
		?>


        <tr style="<?php echo 'display:' . $comp_visible; ?>">
            <th>Sea freight :</th>
            <td>
                <strong><span id="totalSqm"><?php echo $fob_components ? '$' : '£'; ?><?php echo number_format((float)$total_prods_delivery, 2); ?></span></strong>
            </td>
        </tr>

        <tr style="<?php echo 'display:' . $comp_visible; ?>">
            <th>Total:</th>
            <td>
                <strong><span id="totalSqm"><?php echo $fob_components ? '$' : '£'; ?><?php echo number_format((float)$amount, 2); ?></span></strong>
            </td>
        </tr>

        <tr style="<?php echo 'display:' . $comp_visible; ?>">
            <th>Delivery cost:</th>
            <td>
                <strong><span id="totalShippingCost"><?php echo $fob_components ? '$' : '£'; ?><?php echo number_format((float)$shipping_total_without_tax, 2); ?></span></strong>
            </td>
        </tr>

		<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
            </tr>
		<?php endforeach; ?>


		<?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <tr class="fee" style="<?php echo 'display:' . $comp_visible; ?>">
                <th><?php echo esc_html($fee->name); ?></th>
                <td><?php wc_cart_totals_fee_html($fee); ?></td>
            </tr>
		<?php endforeach; ?>

		<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
			<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
				<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <tr class="tax-rate tax-rate-<?php echo sanitize_title($code); ?>" style="<?php echo 'display:' . $comp_visible; ?>"
                    <th><?php echo esc_html($tax->label); ?></th>
                    <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                    </tr>
				<?php endforeach; ?>
			<?php else : ?>
                <tr class="tax-total" style="<?php echo 'display:' . $comp_visible; ?>">
                    <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                    <td>
                        <strong><?php

							$tva_shipping = (WC()->cart->shipping_total * 20) / 100;
							wc_cart_totals_taxes_total_html();

							//echo $tva_shipping + WC()->cart->get_taxes_total( true, true );
							//wc_cart_totals_taxes_total_html_custom();
							?></strong>
                    </td>
                </tr>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <th><?php _e('Gross Total', 'woocommerce'); ?></th>
            <td>
                <strong><?php
					// wc_cart_totals_order_total_html();
					echo WC()->cart->get_total()
					// wc_cart_totals_order_total_html_custom();
					?></strong>
            </td>
        </tr>

	<?php } ?>

    </tfoot>
</table>

<?php

// $rate_table = array();

// $shipping_methods = WC()->shipping->get_shipping_methods();

// foreach($shipping_methods as $shipping_method){
//     $shipping_method->init();

//     foreach($shipping_method->rates as $key=>$val)
//         $rate_table[$key] = $val->label;
// }

// echo $rate_table[WC()->session->get( 'chosen_shipping_methods' )[0]];

// echo WC()->cart->total;

// $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
// echo $chosen_shipping = $chosen_methods[0];
// echo WC()->cart->cost;

?>
