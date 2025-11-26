<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: order edit customer
 *
 */

get_header();

$view_id = $_GET['id'] / 1498765 / 33;
$order = wc_get_order($view_id);
$order_number = $order->get_order_number(); //teo for Order id below

$order_data = $order->get_data(); // The Order data

//foreach ($order->get_items('tax') as $item_id => $item_tax) {
//    echo $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
//}

// ---------------------- Recalculate total of order after add item in edit order ----------------------

$items = $order->get_items();
$total_dolar = 0;
$sum_total_dolar = 0;
$totaly_sqm = 0;
$new_total = 0;
$order_train = 0;

$country_code = WC()->countries->countries[$order->get_shipping_country()];
if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
	$tax_rate = 20;
} else {
	$tax_rate = 0;
}

$pos = false;

foreach ($items as $item_id => $item_data) {

	if (has_term(20, 'product_cat', $item_data['product_id']) || has_term(34, 'product_cat', $item_data['product_id']) || has_term(26, 'product_cat', $item_data['product_id'])) {
		$pos = true;
		// do something if product with ID = 971 has tag with ID = 5
	} else {

// Fetch product data
		$quantity = get_post_meta($item_data['product_id'], 'quantity', true); // Get the quantity of the product
		$price = get_post_meta($item_data['product_id'], '_price', true); // Get the price of the product
		$total = $price * $quantity; // Calculate the total price for the quantity

// Fetch property data
		$sqm = get_post_meta($item_data['product_id'], 'property_total', true); // Get the property total in square meters

// Check if property total (sqm) is greater than 0
		if ($sqm > 0) {
			// Fetch train price for the current user, fallback to default if not set
			$train_price = get_user_meta(get_current_user_id(), 'train_price', true);
					if ($train_price === null || $train_price === '') {
				$train_price = get_post_meta(1, 'train_price', true); // Get default train price if user-specific price is not set
			}

			// Calculate new price based on sqm, quantity, and train price
			$new_price = floatval($sqm * $quantity) * floatval($train_price);

			// Update post meta with the new train delivery price
			update_post_meta($item_data['product_id'], 'train_delivery', $new_price);

			// Accumulate order train delivery cost
			$order_train += $new_price;
		} else {
			$new_price = 0; // No additional price if sqm is not greater than 0
		}
// Update total price with new price
		$total = (float)($total + $new_price);

// Calculate tax based on total price and tax rate
		$tax = (float)(($tax_rate * $total) / 100);

// Prepare line tax data
		$line_tax_data = array(
			'total' => array(
				1 => $tax, // Total tax amount
			),
			'subtotal' => array(
				1 => $tax, // Subtotal tax amount
			),
		);

// Additional code (if any) should follow here

		/*
					 * Doc to change order item meta: https://www.ibenic.com/manage-order-item-meta-woocommerce/
					 */
		if ($quantity == 0 || empty($quantity)) {
			$quantity = 1;
			$total = $price * $quantity;
			$tax = ($tax_rate * $total) / 100;
		}

		wc_update_order_item_meta($item_id, '_qty', $quantity, $prev_value = '');
		wc_update_order_item_meta($item_id, '_line_tax', $tax, $prev_value = '');
		wc_update_order_item_meta($item_id, '_line_subtotal_tax', $tax, $prev_value = '');
		wc_update_order_item_meta($item_id, '_line_subtotal', $total, $prev_value = '');
		wc_update_order_item_meta($item_id, '_line_total', $total, $prev_value = '');
		wc_update_order_item_meta($item_id, '_line_tax_data', $line_tax_data);

		// USD price
		//$prod_qty = $item_data['quantity'];
		$product_id = $item_data['product_id'];
		$prod_qty = get_post_meta($product_id, 'quantity', true);
		$dolar_price = get_post_meta($product_id, 'dolar_price', true);

		$sum_total_dolar = floatval($sum_total_dolar) + floatval($dolar_price) * floatval($prod_qty);

		$sqm = get_post_meta($product_id, 'property_total', true);
		$property_total_sqm = floatval($sqm) * floatval($prod_qty);
		$totaly_sqm = $totaly_sqm + $property_total_sqm;

		## -- Make your checking and calculations -- ##
		$new_total = $new_total + $total + $tax; // <== Fake calculation

		$_product = wc_get_product($product_id);
	}
}

if ($order_train > 0) {
	update_post_meta($view_id, 'order_train', $order_train);
}

foreach ($order->get_items('tax') as $item_id => $item_tax) {
	// Tax shipping total
	$tax_shipping_total = $item_tax->get_shipping_tax_total();
}
//$total_price = $subtotal_price + $total_tax;
$order_data = $order->get_data();
$total_price = $new_total + $order_data['shipping_total'] + $tax_shipping_total;
update_post_meta($view_id, '_order_total', $total_price);

/*
 * UPDATE custom_orders table on each shop_order post update
 */
$property_total_gbp = $order->get_total();
$property_subtotal_gbp = $order->get_subtotal();

$order_shipping_total = $order->shipping_total;

// $property_total_gbp = floatval(get_post_meta($order->id, '_order_total', true));

global $wpdb;

$idOrder = $view_id;
$orderExist = $wpdb->get_var("SELECT COUNT(*) FROM `wp_custom_orders` WHERE `idOrder` = $idOrder");

if ($orderExist != 0) {
	/*
		 * Update table
		 */
	$tablename = $wpdb->prefix . 'custom_orders';

	$data = array(
		'usd_price' => $sum_total_dolar,
		'gbp_price' => $property_total_gbp,
		'subtotal_price' => $property_subtotal_gbp,
		'sqm' => $totaly_sqm,
		'shipping_cost' => $order_shipping_total,
	);
	$where = array(
		"idOrder" => $idOrder,
	);
	$wpdb->update($tablename, $data, $where);
}

?>

  <div class="woocommerce-order">
    <!-- Tradeshutter clone order -->

    <div class="page-content-area">

      <h2>Order id - LF0<?php echo $order_number; ?></h2>
      <br>

      <div class="row">
        <div class="col-xs-12">

          <a href="/prod1-all/?order_id=<?php
					echo $order->ID * 1498765 * 33; ?>&order_edit_customer=editable" class="btn btn-primary blue"> + Add
            New Shutter</a>
          <a href="/prod-individual/?order_id=<?php
					echo $order->ID * 1498765 * 33; ?>&order_edit_customer=editable" class="btn btn-primary blue"> + Add
            Individual Bay Shutter</a>
          <a href="/prod2-all/?order_id=<?php
					echo $order->ID * 1498765 * 33; ?>&order_edit_customer=editable" class="btn btn-primary blue"> + Add
            New Shutter &amp; Blackout Blind</a>
          <a href="/prod5/?order_id=<?php
					echo $order->ID * 1498765 * 33; ?>&order_edit_customer=editable" class="btn btn-primary blue"> + Add
            Batten</a>

					<?php
					echo do_shortcode('[table_items_shc id=1 order_id="' . $order->ID . '" editable="true" admin="false" table_id="example" table_class="table table-striped" ]');
					?>

          <br>

          <div>
            <!--                    <a href="/" class="btn btn-primary transparent"> Save for Later </a>-->

            <button class="btn btn-danger finish-edit float-right right" order-finish="<?php
						echo $order->ID * 1498765 * 33; ?>" style="float: right;">Finish Order Edit
            </button>
          </div>

          <br>
					<?php
					$view_price = (get_user_meta(get_current_user_id(), 'view_price', true) == 'no') ? false : true;
					$style_hide = ($view_price == false) ? "display: none;" : "";

					?>
          <div id="itemstobuy" class="hidden">
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
						echo do_shortcode('[table_order_edit_shc id=2 order_id="' . $order->ID . '" editable="false" admin="false" table_id="example2" table_class="table table-striped" ]');
						?>

          </div>

					<?php
					echo do_shortcode('[table_csv_shc id=3 order_id="' . $view_id . '" table_id="example3" table_class="table table-striped shortcode" admin="false" editable="false" ]');
					?>

        </div>
      </div>
    </div>

    <input type="hidden" name="id_ord" value="<?php echo $order->get_order_number(); ?>">
    <input type="hidden" name="id_ord_original" value="<?php echo $order->ID; ?>">
    <input type="hidden" name="order_name" value="<?php echo get_post_meta($order->ID, 'cart_name', true); ?>">
    <input type="hidden" name="post_order_no_csv" value="0">

  </div>

  <script>

      jQuery('button.btn.btn-danger.finish-edit').click(function (e) {
          e.preventDefault();
          jQuery('#loader-bkg').show();

          var order_finish = jQuery(this).attr('order-finish');
          console.log(order_finish);

          var content = jQuery('textarea[name="table"]').val();
          var id = jQuery('input[name="id_ord"]').val();
          var id_ord_original = jQuery('input[name="id_ord_original"]').val();
          var name = jQuery('input[name="order_name"]').val();
          var items_buy = jQuery('#itemstobuy').html();
          var pos = jQuery('input[name="post_order_no_csv"]').val();

          jQuery.ajax({
              method: "POST",
              url: "/wp-content/themes/storefront-child/ajax/order-edit-customer.php",
              data: {
                  order_finish: order_finish
              }
          })
              .done(function (msg) {
                  console.log(msg);

                  jQuery.ajax({
                      method: "POST",
                      url: "/wp-content/themes/storefront-child/csvs/create-csv-reedit.php",
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
                          alert("Order Revised!");
                          setTimeout(function () {
                              //window.location.href = '<?php echo get_site_url(); ?>';
                              header('Location: '.get_site_url());
                              jQuery('#loader-bkg').hide();
                          }, 500);

                      })
                      .fail(function () {
                          jQuery('#loader-bkg').show();
                          alert("Error send mail!");
                      });

                  setTimeout(function () {
                      window.location.href = '/';
                  }, 500);

              });

      });

  </script>

  <div id="loader-bkg" style="display: none;">
    <div id="loader"></div>
  </div>

<?php
get_footer();