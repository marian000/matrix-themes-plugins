<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
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
 * @version     3.5.3
 */

if (!defined('ABSPATH')) {
	exit;
}

$cart_id = BMC\WooCommerce\MultiSession\customer_id();
$my_session = BMC\WooCommerce\MultiSession\get_session($cart_id, get_current_user_id());
$cart_type = $my_session['carts'][$cart_id]['type'];

//    if (!is_ajax()) {
//        do_action('woocommerce_review_order_before_payment');
//    }
$suspended = get_user_meta(get_current_user_id(), 'suspended_user', true);
if ($suspended != 'yes') {

	?>


    <div id="payment" class="woocommerce-checkout-payment">
	<?php if (WC()->cart->needs_payment()) :

		$user_id = get_current_user_id();
		$shipping_last_name = get_user_meta($user_id, 'shipping_last_name', true);
		$shipping_first_name = get_user_meta($user_id, 'shipping_first_name', true);
		$shipping_company = get_user_meta($user_id, 'shipping_company', true);
		$shipping_address_1 = get_user_meta($user_id, 'shipping_address_1', true);
		$shipping_address_2 = get_user_meta($user_id, 'shipping_address_2', true);
		$shipping_city = get_user_meta($user_id, 'shipping_city', true);
		$shipping_postcode = get_user_meta($user_id, 'shipping_postcode', true);
		$shipping_country = get_user_meta($user_id, 'shipping_country', true);
		$vat_number_custom = get_user_meta($user_id, 'vat_number_custom', true);
		$shipping_state = get_user_meta($user_id, 'shipping_state', true);
		$shipping_phone = get_user_meta($user_id, 'shipping_phone', true);
		$shipping_email = get_user_meta($user_id, 'shipping_email', true);
		$tax_id = get_user_meta($user_id, 'tax_id', true);
		$tax_office = get_user_meta($user_id, 'tax_office', true);
		$vat_number = get_user_meta($user_id, 'vat_number_custom', true);
		?>
        <div class="show-shipping"></div>
        <ul class="wc_payment_methods payment_methods methods">
			<?php
			if (!empty($available_gateways)) {
				foreach ($available_gateways as $gateway) {
					wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
        </ul>

        <div class="form-row place-order">
            <noscript>
				<?php esc_html_e('Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'); ?>
                <br/>
                <button type="submit" class="button alt" name="woocommerce_checkout_update_totals"
                        value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
            </noscript>

			<?php wc_get_template('checkout/terms.php'); ?>

			<?php // do_action('woocommerce_review_order_before_submit');
			?>
			<?php

			global $woocommerce;
			$items = $woocommerce->cart->get_cart();
			$total = 0;
			$term_slug = '';

			foreach ($items as $item => $value) {
				// print_r($value['product_id']);
				$prod_id = $value['product_id'];
				$sqm_awning = floatval($value['awning_sqm']);

				$prod_qty = 1;
				$quantity_prod = get_post_meta($prod_id, 'quantity', true);
				if (!empty($quantity_prod)) {
					$prod_qty = $quantity_prod;
				} else {
					$prod_qty = isset($value['quantity']) ? intval($value['quantity']) : 0;
				}

				$property_total = get_post_meta($prod_id, 'property_total', true);
//                if($prod_id == 337){
//                    $property_total = get_post_meta($prod_id, '_price', true);
//                    $total = $total + $property_total;
//                }
//                else{

				//  }
				if ($cart_type == 'Awning') {
					$total = $total + $sqm_awning * $prod_qty;

				} else {
					$total = $total + (float)$property_total * $prod_qty;
				}

				$_product = wc_get_product($prod_id);
				$shipclass = $_product->get_shipping_class();

				$term_list = wp_get_post_terms($prod_id, 'product_cat', array("fields" => "all"));
				// Check if $term_list is an array and is not empty
				if (is_array($term_list) && !empty($term_list)) {
					// Check if $term_list[0] is an object
					if (is_object($term_list[0])) {
						$term_slug = $term_list[0]->slug;
					}
				}
			}

			$user_id = get_current_user_id();

			$comp_visible = 'none';
			$show_spec = false;
			if ($term_slug != 'components' && $term_slug != 'components-fob') {
				$comp_visible = 'block';
				$show_spec = true;
			}

			$train_price = get_user_meta(get_current_user_id(), 'train_price', true);
			if ($train_price === null || $train_price === '') {
				$train_price = get_post_meta(1, 'train_price', true);
			}
			?>
            <div class="shipping-methods-select" style="<?php echo 'display:' . $comp_visible; ?>">
                <div class="col-sm-12 col-md-12">
                    Select Delivery Method:
                    <select name="shipping-method" id="shipping-select">
						<?php

						$shipping = new WC_Shipping();
						$shipping_classes = $shipping->get_shipping_classes();
						if ($shipclass != 'pos' && $shipclass != 'component') {
							foreach ($shipping_classes as $shipping_class) {

								if ($shipping_class->slug != 'pos' || $shipping_class->slug != 'component') {
									if ($shipping_class->slug == 'ship') {

										$train_exist = strpos($shipping_class->name, 'By Train');
										if ($train_exist !== false) {
											$shipping_name = 'By Train - Express Delivery - surcharge £' . number_format((float)$train_price, 2) . '/sqm';
										} else {
											$shipping_name = $shipping_class->name;
										}
										echo '<option value="' . $shipping_class->slug . '" selected >' . $shipping_name . '</option>';
									}
//                                    if ($shipclass == $shipping_class->slug) {
//                                        echo '<option value="' . $shipping_class->slug . '" selected >' . $shipping_class->name . '</option>';
//                                    } else {
//                                        echo '<option value="' . $shipping_class->slug . '">' . $shipping_class->name . '</option>';
//                                    }
								}
							}
						} elseif ($shipclass == 'pos') {
							echo '<option value="pos" selected >POS – Samples</option>';
						} else {
							echo '<option value="component" selected >Component Delivery</option>';
						}
						?>
                    </select>
                    <br>
                    <!--                    <button class="btn btn-primary recalculate-shipping">Recalculate</button>-->
                </div>
            </div>

            <div class="delivery-details sss" style="padding-bottom:30px;s<?php echo 'display:' . $comp_visible; ?>">
                <div class="col-sm-12 col-md-12">
					<?php if ($show_spec) { ?>
                        By Sea - Express Delivery Surcharge:
                        <strong>
                            <span>
                                £<?php echo number_format((float)$train_price, 2); ?> per sq/m
                            </span>
                        </strong>
                        <br>
						<?php if ($cart_type == 'Awning') {
							?>
                            Total sq/m:
                            <strong><span id="totalSqm">
                                <strong>
                                    <span id="totalSqm">
                                        <?php echo number_format((float)$total, 2, '.', ''); ?>
                                    </span>
                                </strong>
                            </span></strong>
                            <br>
							<?php
						} else { ?>
                            Total sq/m:
                            <strong><span id="totalSqm">
                                <strong>
                                    <span id="totalSqm">
                                        <?php echo number_format((float)$total, 2, '.', ''); ?>
                                    </span>
                                </strong>
                            </span></strong>
                            <br>
						<?php } ?>
                        Delivery address:
                        <strong>
                            <span>
                                <?php echo $shipping_address_1 . ', ' . $shipping_city . ', ' . $shipping_postcode; ?>
                            </span>
                        </strong>
                        <br>
                        Love shutter promotion discount: <strong><span
                              id="totalShippingCost"><?php echo $discount_custom = get_user_meta($user_id, 'discount_custom', true); ?>%</span></strong>
					<?php } ?>
                    <div style="padding: 15px 0;">
                        <input type="checkbox" value="true" name="accept_terms">&nbsp;&nbsp;I have read and
                        acccept
                        the
                        <a href="javascript:void()"
                           onclick="window.open('/wp-content/uploads/Specifications/Terms&Conditions.pdf','','height=800,width=900,resizable=yes')"
                           title="Terms &amp; Conditions">Lifetime Shutters Order Terms and Conditions</a>
                    </div>

                </div>

            </div>
            <p></p>
            <br><br>

            <button class="button alt confirmation-order disabled">Place order</button>

            <script>

                jQuery('input[name="accept_terms"]').on('click', function (e) {
                    if (jQuery('input#my_field_name').val().length === 0) {
                        alert('Cart Name is not set!');
                        addCartName();
                    }

                    if (jQuery(this).is(':checked')) {
                        jQuery('.button.alt.confirmation-order').removeClass('disabled');
                    } else {
                        jQuery('.button.alt.confirmation-order').addClass('disabled');
                    }
                });

                function addCartName() {
                    var cartName = prompt("Please enter your Cart Name:", "");
                    console.log(cartName);
                    if (cartName == null || cartName == "") {
                        alert('Cart Name is not set!');
                    } else {
                        jQuery('input#my_field_name').attr('value', cartName);
                    }
                }

                jQuery('.button.alt.confirmation-order').on('click', function (e) {
                    e.preventDefault();

                    if (!jQuery(this).hasClass("disabled")) {
                        var status = confirm("Please confirm Once you click Confirm, you will receive an invoice by email and your order will be prepared for manufacture. It is not possible to cancel your order after this stage. Please note, invoice must be settled within 24 hours or order will be changed to ‘Unsubmitted’ and you will need to repeat the process. Once invoice is settled job will begin manufacture immediately.");
                        if (status == false) {
                            jQuery('.alert.alert-danger.hide').removeClass('hide');
                            jQuery('.alert.alert-danger.hide').show();
                        } else {
                            jQuery('.button.alt.hide').click();
                        }
                    } else {
                        var status = confirm("Please Accept Terms and Conditions.")
                    }

                });


                // Marian Putirac - change shipping class for products

                jQuery('select#shipping-select').on('change', function (e) {
                    e.preventDefault();

                    var prod_ids = new Array();

                    jQuery("tr.prod-item").each(function () {
                        console.log("id : " + jQuery(this).attr('data-pord-id'));
                        prod_ids.push(jQuery(this).attr('data-pord-id'));
                    });

                    var shipping_class = jQuery('select[name="shipping-method"]').val();

                    jQuery.ajax({
                        method: "POST",
                        url: "/wp-content/themes/storefront-child/ajax/shipping-change.php",
                        data: {
                            prod_ids: prod_ids,
                            shipping_class: shipping_class
                        }
                    })
                        .done(function (data) {
                            //alert( "Data Saved: " + msg );
                            //jQuery('.show-shipping').html(data);

                            setTimeout(function () {
                                location.reload();
                            }, 1000);

                        });
                });

            </script>

			<?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt hide" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine
			?>

			<?php // do_action('woocommerce_review_order_after_submit');
			?>

			<?php wp_nonce_field('woocommerce-process_checkout'); ?>
        </div>
        </div>
	<?php endif;
}

//    if (!is_ajax()) {
//        do_action('woocommerce_review_order_after_payment');
//    }
