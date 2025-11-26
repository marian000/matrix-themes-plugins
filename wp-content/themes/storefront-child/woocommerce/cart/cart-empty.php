<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();


$cart_id = BMC\WooCommerce\MultiSession\customer_id();
//print_r($cart_id);

$my_session = BMC\WooCommerce\MultiSession\get_session($cart_id, get_current_user_id());

//echo '<pre>';
//print_r($my_session['carts'][$cart_id]);
//echo '</pre>';

$cart_type = $my_session['carts'][$cart_id]['type'];

/**
 * @hooked wc_empty_cart_message - 10
 */
do_action('woocommerce_cart_is_empty');

if (wc_get_page_id('shop') > 0) : ?>

<?php
$mystring = get_the_title();
$findmePos = '-pos';
$findmeCompoenent = 'component';
$pos = strpos($mystring, $findme);

if (strpos($mystring, $findmePos) !== false) { ?>
<a href="/order-pos/" class="btn btn-primary blue"> Order POS</a>
<br>

<div class="order-summary-table" style="overflow: auto;">
    <table id="example" style="width:100%" class="table table-striped">
        <thead>
        <tr>
            <!-- <th>item</th> -->

            <th>Item</th>
            <th></th>
            <th></th>
            <th>Price</th>
            <th></th>
            <th>Quantity</th>
            <th>Total</th>
            <th></th>

        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>


    <?php }
    elseif (strpos($mystring, $findmeCompoenent) !== false) {

    $first_element_cart = reset(WC()->cart->get_cart());

    $product_id = $first_element_cart['product_id'];
    $term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

    $user = wp_get_current_user();
    $roles = $user->roles;
    if (in_array('china_admin', $roles)) {
        // echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
        echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
    } else {
        if ($term_list[0]->slug == 'components') {
            echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
            // echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components FOB</a>';
        } elseif ($term_list[0]->slug == 'components-fob') {
            echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
        } else {
            // echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
            echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
            echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components FOB</a>';
        }
    }

    ?>

    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <table id="example" style="width:100%" class="table table-striped">
            <thead>
            <tr>
                <!-- <th>item</th> -->

                <th>Item</th>
                <th></th>
                <th></th>
                <th>Price</th>
                <th></th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>


        <?php }
        elseif ($cart_type == 'Awning') {
	        ?>

            <div class="row">
                <div class="col-md-8">
                    <a href="/prod-awning/" class="btn btn-primary blue"> + Add New Awning</a>
                </div>
            </div>

            <br><br>

	        <?php
        }
        else {

        if (get_current_user_id() === 1) {
	        // Retrieve the current post title
	        $title = get_the_title();

// Remove the prefix 'Order - App-' from the title
	        $title = str_replace('Order - App-', '', $title);
	        print_r($title); // For debugging purposes

// Retrieve the custom post of type 'appcart' by title
	        $appcart = get_page_by_title($title, OBJECT, 'appcart');

// Check if the appcart post exists
	        if ( ! $appcart ) {
		        echo 'No appcart found with the title: ' . esc_html($title);
		        return;
	        }

// Get the meta field 'attached_product_ids' from the appcart post
	        $product_ids_meta = get_post_meta($appcart->ID, 'attached_product_ids', true);

// Check if the meta field is not empty
	        if ( empty( $product_ids_meta ) ) {
		        return;
	        }

// If the meta value is not already an array, treat it as a comma-separated string
	        if ( ! is_array( $product_ids_meta ) ) {
		        // Check if the string contains a comma
		        if ( strpos( $product_ids_meta, ',' ) === false ) {
			        // Single ID, convert to integer and put into array
			        $product_ids = array( intval( $product_ids_meta ) );
		        } else {
			        // Comma-separated IDs: explode into an array and convert each to an integer
			        $product_ids = array_map( 'intval', explode( ',', $product_ids_meta ) );
		        }
	        } else {
		        // Already an array; make sure each element is an integer
		        $product_ids = array_map( 'intval', $product_ids_meta );
	        }

// Set default quantity for each product
	        $quantity = 1;

// Loop through each product ID and add it to the WooCommerce cart
	        foreach ( $product_ids as $product_id ) {
		        $added = WC()->cart->add_to_cart( $product_id, $quantity );
		        if ( ! $added ) {
			        error_log( "Product ID {$product_id} could not be added to the cart." );
		        }
	        }

// Optional: Recalculate cart totals and refresh the customer session cookie
	        WC()->cart->calculate_totals();
	        WC()->session->set_customer_session_cookie(true);

	        wp_redirect( home_url('/checkout') );
        }
            ?>

            <a href="/prod1-all/" class="btn btn-primary blue"> + Add New Shutter</a>
            <a href="/prod-individual/" class="btn btn-primary blue"> + Add Individual Bay Shutter</a>
            <a href="/prod2-all/" class="btn btn-primary blue"> + Add New Shutter & Blackout Blind</a>
            <a href="/prod5/" class="btn btn-danger blue"> + Add Batten</a>

            <br><br>

            <!-- <p class="return-to-shop">
		<a class="button wc-backward" href="/">
			<?php
            // _e( 'Return to Progress Orders', 'woocommerce' )
            ?>
		</a>
	</p> -->
            <?php
        }
        endif; ?>
