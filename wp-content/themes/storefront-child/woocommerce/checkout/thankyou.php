<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     3.7.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$post_order_no_csv = 0;
$j = 0;
$awning = false;
?>

<div class="woocommerce-order">

    <?php if ($order) : ?>

        <?php if ($order->has_status('failed')) : ?>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                <?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?>
            </p>

            <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                <a href="<?php echo esc_url($order->get_checkout_payment_url()); ?>" class="button pay">
                    <?php _e('Pay', 'woocommerce') ?>
                </a>
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="button pay">
                        <?php _e('My account', 'woocommerce'); ?>
                    </a>
                <?php endif; ?>
            </p>

        <?php else : ?>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                <?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), $order); ?>
            </p>

        <?php endif; ?>

    <?php else : ?>

        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
            <?php echo apply_filters('woocommerce_thankyou_order_received_text', __('Thank you. Your order has been received.', 'woocommerce'), null); ?>
        </p>

    <?php endif;

    $cart_id = get_post_meta($order->get_id(), 'cart_id', true);

    ?>






    <?php //do_action( 'woocommerce_view_order', $order_id );
    $user_id = get_current_user_id();

    $order_data = $order->get_data();

    // Get tax for shipping
    foreach ($order->get_items('tax') as $item_id => $item_tax) {
        $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
    }

    ?>
    <!-- Lifetimeshutters clone order -->

    <div class="page-content-area">

        <div class="row">
            <div class="col-xs-12">
                <!-- <div class="alert alert-warning">
                    Please note that our delivery options have changed - select your preferred option from the drop down menu when ordering.
                </div> -->

                <div class="row">
                    <div class="col-sm-7 hidden">
                        <h3>Plans</h3>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="2">No documents yet</td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <br>
                <table class="table table-striped table-bordered table-hover dataTable table-loose">
                    <tbody>
                    <tr>
                        <td>
                            <strong>Address</strong>
                        </td>
                        <td>
                            <?php echo $order_data['billing']['address_1']; ?>
                        </td>
                        <td>
                            <strong>City</strong>
                        </td>
                        <td><?php echo $order_data['billing']['city']; ?></td>
                        <td>
                            <strong>County</strong>
                        </td>
                        <td></td>
                        <td>
                            <strong>Country</strong>
                        </td>
                        <td><?php echo $order_data['billing']['country']; ?></td>
                        <td>
                            <strong>Postcode</strong>
                        </td>
                        <td><?php echo $order_data['billing']['postcode']; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>E-mail</strong>
                        </td>
                        <td><?php echo $order_data['billing']['email']; ?></td>
                        <td>
                            <strong>Phone</strong>
                        </td>
                        <td colspan="9"><?php echo $order_data['billing']['phone']; ?></td>
                    </tr>
                    <tr>
                        <td>
                            <strong>VAT</strong>
                        </td>
                        <td colspan="10"><?php echo $order_data['total_tax']; ?></td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-striped table-bordered table-hover dataTable table-loose"
                       style="margin-top:1em">
                    <tbody>
                    <tr>
                        <td>
                            <strong>Submitted</strong>
                        </td>
                        <td><?php echo '<mark class="order-date">' . wc_format_datetime($order->get_date_created()) . '</mark>'; ?></td>
                        <td>
                            <strong>Updated</strong>
                        </td>
                        <td colspan="9"><?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?></td>
                    </tr>
                    </tbody>
                </table>

                <h3>Information</h3>
                <table class="table table-striped table-bordered table-hover dataTable table-loose"
                       style="margin-top:1em">
                    <tbody>
                    <tr>
                        <td>
                            <strong>Comments for your order</strong>:
                        </td>
                        <td>
                            <strong>Shipping costs</strong>:
                            £<?php echo $order_data['shipping_total'] + $tax_shipping_total; ?>
                        </td>
                        <td>
                            <strong>Order status</strong>:
                            <span class="statusOrder"
                                  style="background-color:#5BC0DE; color: #FFFFFF;"><?php echo '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>'; ?></span>
                            <a class="btn btn-info" href="/orders/view_quote?id=41075" style="display:none">
                                <i class="fa fa-credit-card"></i> Pay by Debit Card</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h3>Customer info</h3>
                <table class="table table-striped table-bordered table-hover dataTable table-loose"
                       style="margin-top:1em">
                    <tbody>
                    <tr>
                        <td>
                            <strong>Comments</strong>
                        </td>
                        <td></td>
                        <td>
                            <strong>Order reference</strong>
                        </td>
                        <td colspan="9"><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>
                    </tr>
                    </tbody>
                </table>

                <h3>Delivery</h3>
                <table class="table table-striped table-bordered table-hover dataTable table-loose"
                       style="margin-top:1em">
                    <tbody>
                    <tr>
                        <td>
                            <strong>Deliveries Start</strong>
                        </td>
                        <td></td>
                        <td>
                            <strong>Delivery Method</strong>
                        </td>
                        <td><?php echo $order->get_shipping_method(); ?></td>
                        <td>
                            <strong>Boxes</strong>
                        </td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

                <?php
                $view_price = (get_user_meta($user_id, 'view_price', true) == 'no') ? false : true;
                $style_hide = ($view_price == false) ? "display: none;" : "";

                ?>

                <h3 class="pull-left" style="<?php echo $style_hide; ?>">Products</h3>

                <?php
                $i = 0;
                $atributes = get_post_meta(1, 'attributes_array', true);
                $items = $order->get_items();

                $nr_code_prod = array();
                foreach ($items as $item_id => $item_data) {
                    $i++;
// $product = $item_data->get_product();
                    $product_id = $item_data['product_id'];

                    $nr_t = get_post_meta($product_id, 'counter_t', true);
                    $nr_b = get_post_meta($product_id, 'counter_b', true);
                    $nr_c = get_post_meta($product_id, 'counter_c', true);

                    if (!empty($nr_code_prod)) {
                        if ($nr_code_prod['t'] < $nr_t) {
                            $nr_code_prod['t'] = $nr_t;
                        }
                        if ($nr_code_prod['b'] < $nr_b) {
                            $nr_code_prod['b'] = $nr_b;
                        }
                        if ($nr_code_prod['c'] < $nr_c) {
                            $nr_code_prod['c'] = $nr_c;
                        }
                    } else {
                        $nr_code_prod['t'] = $nr_t;
                        $nr_code_prod['b'] = $nr_b;
                        $nr_code_prod['c'] = $nr_c;
                    }

                }

                ?>
                <div id="itemstobuy" style="<?php echo $style_hide; ?>">
                    <table id="info-user" border="1" style="width:100%" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Customer:</th>
                            <th><?php echo $order_data['billing']['company']; ?></th>
                            <th>Client details:</th>
                            <th><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></th>
                            <th>Order date:</th>
                            <th><?php echo $order_data['date_created']->date('Y-m-d H:i:s'); ?></th>
                        </tr>
                        <tr>
                            <th>Reference no:</th>
                            <th>LF0<?php echo $order->get_order_number(); ?></th>
                            <th>Clients address:</th>
                            <th><?php echo $order_data['billing']['address_1']; ?></th>
                            <th>Clients contact:</th>
                            <th><?php echo $order_data['billing']['phone'] . ' ' . $order_data['billing']['email']; ?></th>
                        </tr>
                    </table>

	                <?php

	                // Get order items
	                $items = $order->get_items();

	                // Make sure there is at least one item
	                if (!empty($items)) {
		                // Get the first order item
		                $first_item = current($items);
		                $product_id = $first_item->get_product_id();

		                // Retrieve product categories (taxonomy 'product_cat')
		                $terms = get_the_terms($product_id, 'product_cat');

		                if ($terms && !is_wp_error($terms)) {
			                // Get the first category (if more than one exists)
			                $first_category = current($terms);

			                if (strtolower($first_category->name) == 'awning') {
				                echo do_shortcode('[awning_order_items order_id="' . $order->get_id() . '"]');
			                }
			                if (strtolower($first_category->name) == 'pos') {
				                echo do_shortcode('[table_csv_shc order_id="' . get_the_id() . '" table_id="example" table_class="table table-striped shortcode" admin="true" editable="false" ]');
			                }
		                } else {
//		                    echo '<p>No category found for the first product.</p>';
			                echo do_shortcode('[table_items_shc order_id="' . $order_data['id'] . '" editable="false" admin="false" view_price="true" table_id="example" table_class="example-class table table-striped" ]');
		                }
	                }

	                ?>

                </div>
            </div>
        </div>
    </div>

    <?php
    if ($awning == true) {
	    echo '<div style="display:none">';
	    echo do_shortcode('[table_csv_awning order_id="' . $order->get_id() . '" table_id="example" table_class="table table-striped shortcode" admin="true" editable="false" ]');
	    echo '</div>';
    } else {
	    echo do_shortcode('[table_csv_shc order_id="' . $order->get_id() . '" table_id="example" table_class="table table-striped shortcode" admin="false" editable="false" ]');
    }



    $user_id = get_current_user_id();
    $meta_key = 'wc_multiple_shipping_addresses';

    global $wpdb;
    if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
        $addresses = maybe_unserialize($addresses);
    }

    $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

    $userialize_data = unserialize($addresses[0]->meta_value);
    $session_key = $userialize_data['customer_id'];

    $mystring = get_post_meta($order->get_id(), 'cart_name', true);
    $findme = '-pos';
    $pos = strpos($mystring, $findme);
    ?>

    <a class="btn btn-danger delete-btn hidden" id="checkout-delete-btn" href="#delete"
       target="<?php echo $session_key; ?>" onclick="return BMCWcMs.command('delete', this);">Delete</a>

    <!-- old -->
    <!-- <input type="hidden" name="id_ord" value="
<?php // echo $order->get_id();
    ?>"> -->
    <!-- new -->
    <input type="hidden" name="id_ord" value="<?php echo $order->get_order_number(); ?>">
    <input type="hidden" name="id_ord_original" value="<?php echo $order->get_id(); ?>">
    <input type="hidden" name="order_name" value="<?php echo get_post_meta($order->get_id(), 'cart_name', true); ?>">
    <input type="hidden" name="post_order_no_csv" value="<?php echo $post_order_no_csv; ?>">


    <script>
        // jQuery("#tableToMakeMail").load(function () {
        //     // Handler for .load() called.
        // });
        //
        jQuery(document).ready(function () {

            var content = jQuery('textarea[name="table"]').val();
            var id = jQuery('input[name="id_ord"]').val();
            var id_ord_original = jQuery('input[name="id_ord_original"]').val();
            var name = jQuery('input[name="order_name"]').val();
            var items_buy = jQuery('#itemstobuy').html();
            var pos = jQuery('input[name="post_order_no_csv"]').val();

            jQuery.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/csvs/createcsv.php",
                data: {
                    table: content,
                    id: id,
                    id_ord_original: id_ord_original,
                    name: name,
                    items_table: items_buy,
                    pos: pos
                }
            })
                .done(function (msg) {
                    console.log("CSV Created!");
                    setTimeout(function () {
                        jQuery('#loader-bkg').hide();
                    }, 1000);

                })
                .fail(function () {
                    jQuery('#loader-bkg').show();
                    alert("Error send mail!");
                });


            jQuery.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/insert-orders.php",
                data: {id_ord_original: id_ord_original,},
            })
                .done(function (data) {
                    console.log('order insert in table');
                    setTimeout(function () {
                        jQuery('#loader-bkg').hide();
                    }, 1000);
                });


            jQuery('#checkout-delete-btn').click();
            console.log('click delete');

        });

    </script>

</div>

<div id="loader-bkg">
    <h1>Please Wait!!!</h1>
    <div id="loader"></div>
</div>


<style>
    /* Center the loader */
    #loader-bkg {
        position: fixed;
        left: 17%;
        top: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.97);
    }

    #loader-bkg > h1 {
        position: absolute;
        top: 41%;
        z-index: 1;
        margin: -75px 0 0 -75px;
        display: block;
        width: 100%;
        text-align: center;
    }

    #loader {
        position: absolute;
        left: calc(50% - 60px);
        top: 50%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>