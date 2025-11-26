<?php

$order = wc_get_order(get_the_id());
$order_data = $order->get_data();

$items = $order->get_items();

$country_code = WC()->countries->countries[$order->get_shipping_country()];
print_r($country_code);
if ($country_code == 'United Kingdom (UK)' || $country_code == 'Ireland') {
	$tax_rate = 20;
	echo ' tax 20 ';
} else {
	$tax_rate = 1;
	echo ' tax 1 ';
}

echo number_format((double)$order->get_total(), 2);

// echo '<pre>';
// print_r($order->get_items( 'tax' ) );
// echo '</pre>';
if (current_user_can('administrator')) {
	$new_total = 0;

	foreach ($items as $item_id => $item_data) {

		$quantity = get_post_meta($item_data['product_id'], 'quantity', true);
		$price = get_post_meta($item_data['product_id'], '_price', true);
		//    echo '<br>Price: ' . $price;
		$total = number_format($price, 2) * $quantity;
		//   echo '<br>Total: ' . $total;
		$tax = number_format(($tax_rate * $total) / 100, 2);
		//  echo '<br>Tax: ' . $tax;

		$line_tax_data = array
		(
		  'total' => array
		  (
			1 => $tax
		  ),
		  'subtotal' => array
		  (
			1 => $tax
		  )
		);

		## -- Make your checking and calculations -- ##
		$new_total = $new_total + $total + $tax; // <== Fake calculation
		//   echo '<br>';
	}
	//  echo '<br>New Total: ' . $new_total.'<br>';
}

foreach ($order->get_items('tax') as $item_id => $item_tax) {
	echo 'shipping_tax_total: ' . $tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
}

$user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
$user_id = $user_id_customer;
//echo 'USER ID '.$user_id;

$i = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12" id="items-info" style="overflow: scroll;">
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
					echo do_shortcode('[table_items_shc table_id="example2" order_id="' . get_the_id() . '" editable="false" admin="true" ]');
				}
			} else {
				echo do_shortcode('[table_items_shc table_id="example2" order_id="' . get_the_id() . '" editable="false" admin="true" ]');
			}
		}


		?>
    </div>

	<?php
	//
	//        $order = wc_get_order(get_the_id());
	//        $items = $order->get_items();
	//
	//        foreach ($items as $item_id => $item_data) {
	//            echo '<pre>';
	//            echo '<p>' . $item_id . '</p>';
	//            echo '<p>' . $item_data['quantity'] . '</p>';
	//            print_r($item_data);
	//            print_r( wc_get_order_item_meta( $item_id, '_line_tax_data', $single = true ) );
	//            echo '</pre>';
	//            /*
	//             * Doc to change order item meta: https://www.ibenic.com/manage-order-item-meta-woocommerce/
	//             */
	//            $quantity = get_post_meta($item_data['product_id'], 'quantity', true);
	//            //  wc_update_order_item_meta($item_id, '_qty', $quantity, $prev_value = '');
	//
	//        }

	?>

</div>
<div class="clearfix"></div>


