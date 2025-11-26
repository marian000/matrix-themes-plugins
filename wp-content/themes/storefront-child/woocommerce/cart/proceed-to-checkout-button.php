<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<!-- <a href="<?php //echo esc_url( wc_get_checkout_url() );?>" class="checkout-button button alt wc-forward">
	<?php //esc_html_e( 'Transform to order', 'woocommerce' ); ?>
</a> -->


 
 <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">



    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

    <div class="col2-set" id="customer_details">
        <div class="col-1">
            <?php do_action( 'woocommerce_checkout_billing' ); ?>
        </div>

        <div class="col-2">
            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
        </div>
    </div>

    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>



<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

<div id="order_review" class="woocommerce-checkout-review-order">
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
</div>

<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>


<!-- <a href="/checkout" class="checkout-button button alt blue wc-forward">
	<?php 
	//esc_html_e( 'Transform to order', 'woocommerce' ); ?>
</a> -->

<!-- <br>
<a href="/" class="checkout-button button transparent">
	<?php 
	//esc_html_e( 'Save for later', 'woocommerce' ); ?>
</a> -->
