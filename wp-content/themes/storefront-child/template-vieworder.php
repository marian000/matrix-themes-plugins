<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: vieworder
 *
 */

use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;

get_header();

$view_id = $_GET['id'] / 1498765 / 33;
$order = wc_get_order($view_id);
$order_number = $order->get_order_number(); //teo for Order id below

$order_data = $order->get_data(); // The Order data

//foreach ($order->get_items('tax') as $item_id => $item_tax) {
//    echo $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
//}

?>

  <div class="woocommerce-order">

		<?php

		$cart_id = get_post_meta($order->get_id(), 'cart_id', true);

		?>

    <!-- Tradeshutter clone order -->

    <div class="page-content-area">

      <h2>Order id - LF0<?php echo $order_number; ?></h2>
      <br>

      <div class="row">
        <div class="col-xs-12">
          <!-- <div class="alert alert-warning">
							Please note that our delivery options have changed - select your preferred option from the drop down menu when ordering.
					</div> -->

          <div class="row">
            <div class="col-sm-7 hidden">
              <!-- <h3>Plans</h3>

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
                $<?php echo $order_data['shipping_total'] + ($tax_shipping_total ?? 0); ?>
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
                <strong>Order ID - LF0<?php echo $order->get_order_number(); ?></strong>
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


          <h3 style="float: right; color: red">Love shutter promotion discount:  <strong><?php echo  $discount_custom = get_user_meta(get_current_user_id(), 'discount_custom',true); ?>%</strong></h3>


          <h3 class="pull-left">Products</h3>

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
			        echo '<br><br>';

			        if (strtolower($first_category->name) == 'awning') {
				        echo do_shortcode('[awning_order_items order_id="' . $order->get_id() . '"]');
			        }
			        if (strtolower($first_category->name) == 'pos') {
				        echo do_shortcode('[table_items_shc order_id="' . $order->ID . '" editable="false" admin="false" table_id="example" table_class="table table-striped" ]');
			        }
		        } else {
			        echo do_shortcode('[table_items_shc order_id="' . $order->get_id() . '" editable="false" admin="false" table_id="example" table_class="table table-striped" ]');
		        }
	        }
	        ?>


          <br>

        </div>
      </div>
    </div>

  </div>


<?php

echo do_shortcode('[display_factory_queries order_id="' . $order->get_id() . '"]');

get_footer();