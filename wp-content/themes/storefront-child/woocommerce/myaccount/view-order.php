<?php
    /**
     * View Order
     *
     * Shows the details of a particular order on the account page.
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
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
     * @version 3.0.0
     */
    
    if (!defined('ABSPATH')) {
        exit;
    }

?>

<?php //do_action( 'woocommerce_view_order', $order_id );
    $order_data = $order->get_data();
    
    //$order_data = $order->get_data();
    $order = wc_get_order($order_id);
    $order_number = $order->get_order_number(); //teo for Order id below
    
    foreach ($order->get_items('tax') as $item_id => $item_tax) {
        $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
    }
?>

<!-- Lifetimeshutters clone order -->

<div class="page-content-area">
    
    <h1 class="entry-title text-center">Order id - LF<?php echo $order_number; ?></h1>
    
    <br>
    
    <div class="row">
        <div class="col-xs-12">
            <!-- <div class="alert alert-warning">
                Please note that our delivery options have changed - select your preferred option from the drop down menu when ordering.
            </div> -->
            
            <div class="row">
                <div class="col-sm-7">
                    <!-- <h3>Plans</h3> -->
                    
                    <!-- <table class="table table-bordered table-striped">
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
                    </table> -->
                
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
            
            <table class="table table-striped table-bordered table-hover dataTable table-loose" style="margin-top:1em">
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
            <table class="table table-striped table-bordered table-hover dataTable table-loose" style="margin-top:1em">
                <tbody>
                <tr>
                    <td>
                        <strong>Comments for your order</strong>:
                    </td>
                    <td>
                        <strong>Shipping costs</strong>: £<?php echo $order_data['shipping_total'] + $tax_shipping_total; ?>
                    </td>
                    <td>
                        <strong>Order status</strong>:
                        <span class="statusOrder" style="background-color:#5BC0DE; color: #FFFFFF;"><?php echo '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>'; ?></span>
                        <a class="btn btn-info" href="/orders/view_quote?id=41075" style="display:none">
                            <i class="fa fa-credit-card"></i> Pay by Debit Card</a>
                    </td>
                </tr>
                </tbody>
            </table>
            
            <h3>Customer info</h3>
            <table class="table table-striped table-bordered table-hover dataTable table-loose" style="margin-top:1em">
                <tbody>
                <tr>
                    <td>
                        <strong>Comments</strong>
                    </td>
                    <td></td>
                    <td>
                        <strong>Order ID - LF0<?php echo $order->get_order_number(); ?></strong>
                    </td>
                    <td colspan="9"><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>
                </tr>
                </tbody>
            </table>
            
            <h3>Delivery</h3>
            <table class="table table-striped table-bordered table-hover dataTable table-loose" style="margin-top:1em">
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
            
            <h3 class="pull-left">Products</h3>
            
            <?php
                echo do_shortcode('[table_items_shc order_id="' . $order_id . '" editable="false" admin="false" table_id="example" table_class="table table-striped" ]');
            ?>
        </div>
    </div>
</div>

<script>

    jQuery(document).ready(function () {

        jQuery('#new_order_message_send_button').on('click', function () {

            var comment = jQuery('textarea#new_order_message_textarea').val();
            var id = jQuery('input[name="id_order"]').val();

            jQuery.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/order-comment-ajax.php",
                data: {
                    comment: comment,
                    id: id
                }
            })
                .done(function (msg) {
                    //alert( "Data Saved: " + msg );
                    console.log("Data Saved: " + msg);
                    location.reload();
                });


        });

    });

</script>
