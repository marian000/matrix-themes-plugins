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
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_id();
$shipping_last_name = get_user_meta( $user_id,'shipping_last_name',true );
$shipping_first_name = get_user_meta( $user_id,'shipping_first_name',true );
$shipping_company = get_user_meta( $user_id,'shipping_company',true );
$shipping_address_1 = get_user_meta( $user_id,'shipping_address_1',true );
$shipping_address_2 = get_user_meta( $user_id,'shipping_address_2',true );
$shipping_city = get_user_meta( $user_id,'shipping_city',true );
$shipping_postcode = get_user_meta( $user_id,'shipping_postcode',true );
$shipping_country = get_user_meta( $user_id,'shipping_country',true );
$vat_number_custom = get_user_meta( $user_id,'vat_number_custom',true );
$shipping_state = get_user_meta( $user_id,'shipping_state',true );
$shipping_phone = get_user_meta( $user_id,'shipping_phone',true );
$shipping_email = get_user_meta( $user_id,'shipping_email',true );
$tax_id = get_user_meta( $user_id,'tax_id',true );
$tax_office = get_user_meta( $user_id,'tax_office',true );
$vat_number = get_user_meta($user_id, 'vat_number_custom',true);

if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_before_payment' );
}
?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) : ?>
		<ul class="wc_payment_methods payment_methods methods">
			<?php
			if ( ! empty( $available_gateways ) ) {
				foreach ( $available_gateways as $gateway ) {
					wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
				}
			} else {
				echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
			}
			?>
		</ul>
	<?php endif; ?>
	<div class="form-row place-order">
		<noscript>
			<?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
			<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
		</noscript>

		<?php wc_get_template( 'checkout/terms.php' ); ?>

		<?php do_action( 'woocommerce_review_order_before_submit' ); ?>
		<?php

global $woocommerce;
$items = $woocommerce->cart->get_cart();
$total = 0;

foreach( $items as $item=>$value ){
	//print_r($value['product_id']);												
	$prod_id = $value['product_id']; 
	$prod_qty = $value['quantity']; 

	$property_total = get_post_meta($prod_id,'property_total',true);
	$total = $total + $property_total*$value['quantity'];
}

$user_id = get_current_user_id();

?>

<div class="delivery-details sss" style="padding-bottom:30px;">
		<div class="col-sm-12 col-md-12">
		Total sq/m: <strong><span id="totalSqm"><strong><span id="totalSqm"><?php echo number_format((float)$total, 2, '.', ''); ?></span></strong></span></strong>
		<br>
		Customer Discount: <strong><span id="totalShippingCost"><?php echo  $discount_custom = get_user_meta($user_id, 'discount_custom',true); ?>%</span></strong>
		<br>
		Delivery address:
		<strong><span><?php echo $shipping_address_1.', '.$shipping_city.', '.$shipping_postcode; ?></span><strong>
	
		</div>
		
</div>
<p></p>
<br><br>

<button class="button alt confirmation-order" >Place order</button>

<script>
	jQuery('.button.alt.confirmation-order').on('click',function(e) {
		e.preventDefault();
		var status = confirm("Please confirm Once you click Confirm, you will receive an invoice by email and your order will be prepared for manufacture. It is not possible to cancel your order after this stage. Please note, invoice must be settled within 24 hours or order will be changed to ‘Unsubmitted’ and you will need to repeat the process. Once invoice is settled job will begin manufacture immediately.");
		if(status == false){
			jQuery('.alert.alert-danger.hide').removeClass('hide');
			jQuery('.alert.alert-danger.hide').show();
		}
		else{
			jQuery('.button.alt.hide').click();
		}

	});
</script>

		<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt hide" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

		<?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
	</div>
</div>
<?php
if ( ! is_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
