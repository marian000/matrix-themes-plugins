<?php

/**

 * Customer invoice email

 *

 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.

 *

 * HOWEVER, on occasion WooCommerce will need to update template files and you

 * (the theme developer) will need to copy the new files to your theme to

 * maintain compatibility. We try to do this as little as possible, but it does

 * happen. When this occurs the version of the template file will be bumped and

 * the readme will list any important changes.

 *

 * @see         https://docs.woocommerce.com/document/template-structure/

 * @author      WooThemes

 * @package     WooCommerce/Templates/Emails

 * @version     3.7.0

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



/**

 * Executes the e-mail header.

 *

 * @hooked WC_Emails::email_header() Output the email header

 */

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>



<?php if ( $order->has_status( 'pending' ) ) : ?>

	<p>

	<?php

	printf(

		wp_kses(

			/* translators: %1s item is the name of the site, %2s is a html link */

			__( 'An order has been created for you on %1$s. %2$s', 'woocommerce' ),

			array(

				'a' => array(

					'href' => array(),

				),

			)

		),

		esc_html( get_bloginfo( 'name', 'display' ) ),

		'<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'

	);

	?>

	</p>

<?php endif; ?>



<?php



/**

 * Hook for the woocommerce_email_order_details.

 *

 * @hooked WC_Emails::order_details() Shows the order details table.

 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.

 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.

 * @since 2.5.0

 */



do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

?>

<div>

<p>Dear <?php echo get_user_meta(get_current_user_id(),'billing_company',true); ?>, </p>



<p>Thank you for your recent order with Matrix. </p>



<p>You can find the invoice for order reference '<?php echo get_post_meta($order->id,'cart_name',true); ?> - <?php echo $order->id; ?>' by following this link:</p>

<p><a href="https://matrix.lifetimeshutters.com/view-order/?id=<?php echo ($order->id)*1498765*33; ?>">

https://matrix.lifetimeshutters.com/view-order/?id=<?php echo ($order->id)*1498765*33; ?></a></p>



<p>Your order has been sent to our factory ready for manufacture. Once payment has been received manufacturing will commence. </p>



<p>Payments can be made via bank transfer to the following account: </p>



<p>Account Name: <?php echo get_user_meta(get_current_user_id(),'billing_company',true); ?></p>

<p>Sort Code: 20-46-73</p>

<p>Account Number: 13074145</p>

<p>Order Reference: LF0<?php echo $order->get_order_number(); ?></p>







<p>Should you have any questions please contact us at order@lifetimeshutters.com </p>



<p>Kind regards, </p>



<p>Accounts Department </p>



</div>

