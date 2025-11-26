<?php

/**
 * Register meta box ITEMS ORDER.
 */
function matrix_all_meta_boxes()
{
	// single product meta boxes
	add_meta_box(
		'product_video_meta_box', // ID of the meta box
		'Product Video', // Title of the meta box
		'show_product_video_meta_box', // Callback function to display the meta box
		'product', // Post type
		'normal', // Context
		'low' // Priority
	);

	// Order Meta Boxes
	add_meta_box('items-order', __('Items Order', 'textdomain'), 'wpdocs_items_order_callback', 'shop_order', 'normal', 'high');
	add_meta_box('order-csv', __('CSV Order', 'textdomain'), 'wpdocs_my_display_callback', 'shop_order');
	add_meta_box('factory-queries', __('Factory Queries', 'textdomain'), 'wpdocs_my_display_tickets_order', 'shop_order');
	add_meta_box('change-order-ref', esc_html__('Order Ref', 'text-domain'), 'change_order_ref_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('deliveries-start-meta-box-id', esc_html__('Deliveries Details And Comment', 'text-domain'), 'deliveries_render_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('container-meta-box-id', esc_html__('Select Container for order', 'text-domain'), 'containers_render_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('shipping-box-id', esc_html__('Select shipping for order', 'text-domain'), 'shipping_render_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('quickbooks-box-id', esc_html__('Select quickbooks status', 'text-domain'), 'quickbooks_render_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('items-edit-box-id', esc_html__('Edit items by Customer', 'text-domain'), 'items_edit_render_menu_meta_box', 'shop_order', 'side', 'low');
	add_meta_box('order-edit-price-old-new', esc_html__('Edit Order by Old or New price material', 'text-domain'), 'order_price_edit_render_menu_meta_box', 'shop_order', 'side', 'low');

	// Product Meta Boxes
	add_meta_box('image-svg', __('Svg - Image', 'textdomain'), 'wpdocs_my_display_img', 'product');
	add_meta_box('dolar-price', __('Dolar Price', 'textdomain'), 'wpdocs_items_dolar_price_callback', 'product', 'side', 'high');

	// Container Meta Boxes
	add_meta_box('deliveries-start-container-meta-box-id', esc_html__('Deliveries Details And Comment', 'text-domain'), 'deliveries_render_menu_container_meta_box', 'container', 'side', 'low');
	// megabox for container average orders cost transport
	add_meta_box('average-transport-container-meta-box-id', esc_html__('Average Transport', 'text-domain'), 'average_transport_menu_container_meta_box', 'container', 'side', 'low');
//    add_meta_box('containers-info-container-meta-box-id', esc_html__('Calculate Info Container', 'text-domain'), 'containers_info_menu_container_meta_box', 'container', 'side', 'low');
	add_meta_box('container-lf-list-meta-box-id', esc_html__('Container LF list', 'text-domain'), 'lf_list_container_meta_box', 'container', 'side', 'low');
	add_meta_box('container-lf-list-status-meta-box-id', esc_html__('Container LF list Status', 'text-domain'), 'lf_list_container_status_meta_box', 'container', 'side', 'low');

	add_meta_box('csv-order-deliveries', esc_html__('Deliveries Orders CSV', 'text-domain'), 'csv_oder_deliveries_render', 'container', 'side', 'low');
	add_meta_box('csv-invoice-deliveries', esc_html__('Deliveries Invoice CSV', 'text-domain'), 'csv_invoice_deliveries_render', 'container', 'side', 'low');
	add_meta_box('cintainer-orders', __('Container Orders', 'textdomain'), 'wpdocs_container_orders_callback', 'container', 'normal', 'high');
	add_meta_box('cintainer-orders-prices', __('Container Orders Prices', 'textdomain'), 'wpdocs_container_orders_prices_callback', 'container', 'normal', 'high');

	// Order Repair Meta Boxes
	add_meta_box('repair-order', __('Repair Order Details', 'textdomain'), 'wpdocs_repair_order_details_callback', 'order_repair', 'normal', 'high');
	add_meta_box('repair-order-container', esc_html__('Select Container for order repair', 'text-domain'), 'wpdocs_repair_order_container_callback', 'order_repair', 'side', 'low');
	add_meta_box('repair-order-status', esc_html__('Select Status Order Repair', 'text-domain'), 'repair_oder_status_content', 'order_repair', 'side', 'low');
	add_meta_box('repair-order-deliveries', esc_html__('Deliveries Details And Comment', 'text-domain'), 'repair_oder_deliveries_render', 'order_repair', 'side', 'low');
	add_meta_box('repair-order-repairpay', esc_html__('No warranty Cost', 'text-domain'), 'repair_oder_warranty_payment', 'order_repair', 'side', 'low');

	// Ticket Meta Boxes
	add_meta_box('dealer-notification', __('Dealer Notification', 'textdomain'), 'dealer_notification_callback', 'stgh_ticket', 'side', 'default');
}


add_action('add_meta_boxes', 'matrix_all_meta_boxes');

/**
 * @param WP_Post $post Current post object.
 */
function wpdocs_items_order_callback($post)
{
	include_once(get_stylesheet_directory() . '/views/items-order.php');
}


function wpdocs_my_display_callback($post)
{
	include_once(get_stylesheet_directory() . '/views/csv-order.php');
}


function wpdocs_my_display_tickets_order($post)
{
	// include_once(get_stylesheet_directory() . '/views/order-tickets.php');
	echo do_shortcode('[display_factory_queries order_id="' . $post->ID . '"]');
}


function wpdocs_my_display_img($post)
{
	include_once(get_stylesheet_directory() . '/views/img-svg.php');
}


function wpdocs_container_orders_callback($post)
{
	include_once(get_stylesheet_directory() . '/views/container-orders.php');
}


function wpdocs_container_orders_prices_callback($post)
{
	include_once(get_stylesheet_directory() . '/views/container-orders-prices.php');
}


function wpdocs_repair_order_details_callback($post)
{
	include_once(get_stylesheet_directory() . '/views/repair-order/repair-order-details.php');
}


function wpdocs_items_dolar_price_callback($post)
{
	$product = $post->ID;
	echo '<div>Price: $ <input type="text" name="dolar_price" value="" ></div>';
}


function wpdocs_repair_order_container_callback($meta_id)
{

	$order_id = get_the_id();

	$rand_posts = get_posts(array(
		'post_type' => 'container',
		'posts_per_page' => 15,
	));
	if ($rand_posts) {
		echo '	<select name="container-repair">
		<option value="" >Select container</option>
	';
		foreach ($rand_posts as $post) :
			setup_postdata($post);
			$container_orders = get_post_meta($post->ID, 'container_orders', true);
			if (in_array($order_id, $container_orders)) {
				echo '<option value="' . $post->ID . '" selected >' . get_the_title($post->ID) . '</option>';
			} else {
				echo '<option value="' . $post->ID . '">' . get_the_title($post->ID) . '</option>';
			}
		endforeach;
		echo '</select>';
		wp_reset_postdata();
	}
}


//Add field
// inputs for deliveries start for shop_order post
function deliveries_render_menu_meta_box($meta_id)
{
	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Set Deliveries Start', 'text-domain') . '</label>';
	$title_field = get_post_meta($meta_id->ID, 'delivereis_start', true);
	$outline .= '<input type="text" name="deliveries" id="title_field" class="title_field" value="' . esc_attr($title_field) . '" style="width:auto;"/>';

	$outline .= '<br><br><p><strong>Order Comments</strong></p><p>' . get_post_meta($meta_id->ID, 'order_comment', true) . '</p>';

	echo $outline;
}


//Add field
// inputs for deliveries start for container post
function deliveries_render_menu_container_meta_box($meta_id)
{
	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Set Deliveries Start', 'text-domain') . '</label>';
	$title_field = get_post_meta($meta_id->ID, 'delivereis_start', true);
	$outline .= '<input type="text" name="deliveries" id="title_field" class="title_field delivery_start" value="' . esc_attr($title_field) . '" style="width:auto;"/>';

	$outline .= '<br><br><p><strong>Order Comments</strong></p><p>' . get_post_meta($meta_id->ID, 'order_comment', true) . '</p>';
	echo $outline;
}


function average_transport_menu_container_meta_box($meta_id)
{
	$outline = '<label for="avg_train" style="width:150px; display:inline-block;">' . esc_html__('Set Avg.Train $/m2', 'text-domain') . '</label>';
	$avg_train = get_post_meta($meta_id->ID, 'average_train', true);
	$outline .= '<input type="text" name="avg-train" id="avg_train" class="title_field avg" value="' . esc_attr($avg_train) . '" style="width:auto;"/>';

	$outline .= '<label for="avg_truck" style="width:150px; display:inline-block;">' . esc_html__('Set Avg.Truck $/m2', 'text-domain') . '</label>';
	$avg_truck = get_post_meta($meta_id->ID, 'average_truck', true);
	$outline .= '<input type="text" name="avg-truck" id="avg_truck" class="title_field avg" value="' . esc_attr($avg_truck) . '" style="width:auto;"/></br>';

	$outline .= "<script>
                var timer = null;
                jQuery('input.avg').keyup(function(){
                    clearTimeout(timer);
                    timer = setTimeout(sendData, 2000)
                });
                function sendData() {
                    var values = {};
                    jQuery('input.avg').each(function() {
                        if(this.value > 0) { values[this.name] = parseFloat(this.value); }
                        else { values[this.name] = parseFloat(0); }
                    });
            
                    console.log(values);
                    var data = {
                        'action': 'average_per_month_train',
                        'avgs': values,
                        'container_id': " . $meta_id->ID . ",
                    };
                    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                    jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
                        console.log(response);
                        jQuery( '#average-transport-container-meta-box-id .inside' )
                        .append( '<p><b>Avgs. Saved!</b></p>' );
                    });
                }
            </script>";

	echo $outline;
}


function csv_oder_deliveries_render($meta_id)
{
	$title_field = get_post_meta($meta_id->ID, 'delivereis_start', true);
	$csv_upload_path = get_post_meta($meta_id->ID, 'csv_upload_path', true);
	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Upload CSV Delivery Orders', 'text-domain') . '</label>';
	if ($csv_upload_path != '') {
		$outline .= '<input type="hidden" id="csv_upload_path" name="csv_upload_path" value="' . $csv_upload_path . '">';
		$outline .= '<button id="CSVUpload" id-container="' . $meta_id->ID . '" class="btn btn-primary" >Read CSV</button>';
		$outline .= '<button id="CSVReset" id-container="' . $meta_id->ID . '" class="btn btn-primary" >Reset CSV</button>';
	} else {
		$outline .= '<input type="file" name="CSVUpload">';
	}
	$outline .= '<script>
                        jQuery(document).ready(function(){
                            jQuery("#CSVUpload").on("click",function(e) {
                                e.preventDefault();
                                var csv_path = jQuery("#csv_upload_path").val();
                                var container_id = jQuery("#CSVUpload").attr("id-container");
                                
                                jQuery.ajax({
                                    url: "/wp-content/themes/storefront-child/ajax/csv-container-compare.php",
                                    type: "POST",
                                    data: { csv_path: csv_path, container_id: container_id },
                                    beforeSend: function () {
                                        jQuery("body .spinner-modal").show();
                                    },
                                    complete: function () {
                                        jQuery("body .spinner-modal").hide();
                                    },
                                    success: function (result) {
                                        console.log(result);
                                    }
                                })
                                .done(function(data){
                                    console.log(data);
                                    // location.reload();
                                });
                            });
                            
                            jQuery("#CSVReset").on("click",function(e) {
                                e.preventDefault();
                                var container_id_reset = jQuery("#CSVReset").attr("id-container");
                                
                                jQuery.ajax({
                                    url: "/wp-content/themes/storefront-child/ajax/csv-container-compare.php",
                                    type: "POST",
                                    data: {  container_id_reset: container_id_reset },
                                    beforeSend: function () {
                                        jQuery("body .spinner-modal").show();
                                    },
                                    complete: function () {
                                        jQuery("body .spinner-modal").hide();
                                    },
                                    success: function (result) {
                                        console.log(result);
                                    }
                                })
                                .done(function(data){
                                    console.log(data);
                                    location.reload();
                                });
                            });

                        });
                    </script>';

	echo $outline;
	$csv_orders_array = get_post_meta($meta_id->ID, 'csv_orders_array', true);
	//print_($csv_orders_array);
}


function csv_invoice_deliveries_render($meta_id)
{
	$title_field = get_post_meta($meta_id->ID, 'delivereis_start', true);
	$csv_upload_path_invoice = get_post_meta($meta_id->ID, 'csv_upload_path_invoice', true);
	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Upload CSV Delivery Invoice', 'text-domain') . '</label>';
	if ($csv_upload_path_invoice != '') {
		$outline .= '<input type="hidden" id="csv_upload_path_invoice" name="csv_upload_path_invoice" value="' . $csv_upload_path_invoice . '">';
		$outline .= '<button id="CSVUploadInvoice" id-container="' . $meta_id->ID . '" class="btn btn-primary" >Read CSV</button>';
		$outline .= '<button id="CSVResetInvoice" id-container="' . $meta_id->ID . '" class="btn btn-primary" >Reset CSV</button>';
	} else {
		$outline .= '<input type="file" name="CSVUploadInvoice">';
	}
	$outline .= '<script>
                        jQuery(document).ready(function(){
                            jQuery("#CSVUploadInvoice").on("click",function(e) {
                                e.preventDefault();
                                var csv_path = jQuery("#csv_upload_path_invoice").val();
                                var container_id = jQuery("#CSVUploadInvoice").attr("id-container");
                                
                                jQuery.ajax({
                                    url: "/wp-content/themes/storefront-child/ajax/csv-container-compare-invoice.php",
                                    type: "POST",
                                    data: { csv_path: csv_path, container_id: container_id },
                                    beforeSend: function () {
                                        jQuery("body .spinner-modal").show();
                                    },
                                    complete: function () {
                                        jQuery("body .spinner-modal").hide();
                                    },
                                    success: function (result) {
                                        console.log(result);
                                    }
                                })
                                .done(function(data){
                                    // alert(data);
                                    console.log(data);
                                     setTimeout(function () {
                                        location.reload();
                                    }, 500);
                                });
                            });
                            
                            jQuery("#CSVResetInvoice").on("click",function(e) {
                                e.preventDefault();
                                var container_id_reset = jQuery("#CSVResetInvoice").attr("id-container");
                                
                                jQuery.ajax({
                                    url: "/wp-content/themes/storefront-child/ajax/csv-container-compare-invoice.php",
                                    type: "POST",
                                    data: {  container_id_reset: container_id_reset },
                                    beforeSend: function () {
                                        jQuery("body .spinner-modal").show();
                                    },
                                    complete: function () {
                                        jQuery("body .spinner-modal").hide();
                                    },
                                    success: function (result) {
                                        console.log(result);
                                    }
                                })
                                .done(function(data){
                                    console.log(data);
                                    setTimeout(function () {
                                        location.reload();
                                    }, 500);
                                });
                            });

                        });
                    </script>';

	echo $outline;
	$csv_orders_array = get_post_meta($meta_id->ID, 'csv_orders_array', true);
	//print_($csv_orders_array);
}


// inputs for change ref_order post
function change_order_ref_menu_meta_box($meta_id)
{

	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Set Order Ref', 'text-domain') . '</label>';
	$order_ref = get_post_meta($meta_id->ID, 'cart_name', true);
	$outline .= '<input type="text" name="order_ref" id="title_field" class="title_field" value="' . esc_attr($order_ref) . '" style="width:auto;"/>';

	$outline .= '<br>';

	echo $outline;
}


//Add field
function containers_render_menu_meta_box($meta_id)
{

	$order_id = get_the_id();

	$rand_posts = get_posts(array(
		'post_type' => 'container',
		'posts_per_page' => 15,
	));
	if ($rand_posts) {
		echo '	<select name="container">
		<option value="" >Select container</option>
	';
		foreach ($rand_posts as $post) :
			setup_postdata($post);
			$container_orders = get_post_meta($post->ID, 'container_orders', true);
			if (in_array($order_id, $container_orders)) {
				echo '<option value="' . $post->ID . '" selected >' . get_the_title($post->ID) . '</option>';
			} else {
				echo '<option value="' . $post->ID . '">' . get_the_title($post->ID) . '</option>';
			}
		endforeach;
		echo '</select>';
		wp_reset_postdata();
	}
}


function shipping_render_menu_meta_box($meta_id)
{

	$order_id = get_the_id();
	$order = wc_get_order($order_id);
	$items = $order->get_items();
	$user_id_customer = get_post_meta($order_id, '_customer_user', true);
	$user_id = $user_id_customer;
	$total = 0;

	foreach ($items as $item => $value) {
		//print_r($value['product_id']);
		$prod_id = $value['product_id'];

		$_product = wc_get_product($prod_id);
		$shipclass = $_product->get_shipping_class();
	}

	ob_start();
	?>
  <br>
  <div class="shipping-methods-select">
    Select Delivery Method:
    <select name="shipping-method" id="shipping-select">
			<?php
			$shipping = new WC_Shipping();
			$shipping_classes = $shipping->get_shipping_classes();

			foreach ($shipping_classes as $shipping_class) {
				if ($shipping_class->slug != 'pos' || $shipping_class->slug != 'components') {
					if ($shipclass == $shipping_class->slug) {
						if ($shipping_class->slug == 'ship') {
							$train_price = get_user_meta($user_id_customer, 'train_price', true);
							if ($train_price === null || $train_price === '') {
								$train_price = get_post_meta(1, 'train_price', true);
							}
							$train_exist = strpos($shipping_class->name, 'By Train');
							if ($train_exist !== false) {
								$shipping_name = 'By Train - surcharge £' . $train_price . '/sqm - Express Delivery';
							} else {
								$shipping_name = $shipping_class->name;
							}
							echo '<option value="' . $shipping_class->slug . '" selected >' . $shipping_name . '</option>';
						} else {
							echo '<option value="' . $shipping_class->slug . '" selected >' . $shipping_class->name . '</option>';
						}
					} else {
						echo '<option value="' . $shipping_class->slug . '">' . $shipping_class->name . '</option>';
					}
				}
			}
			?>
    </select>
    <br>
  </div>

  <script>
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
              },
              beforeSend: function () {
                  jQuery("body .spinner-modal").show();
              },
              complete: function () {
                  jQuery("body .spinner-modal").hide();
              },
          })
              .done(function (data) {
                  // alert( "shipping: " + data );
                  // jQuery('.show-shipping').html(data);
                  setTimeout(function () {
                      location.reload();
                  }, 500);

              });
      });
  </script>
	<?php

	$out = ob_get_contents();
	ob_end_clean();

	echo $out;
}


function quickbooks_render_menu_meta_box()
{
	$order_id = get_the_id();
	$order = wc_get_order($order_id);

	ob_start();
	?>
	<?php
	$QB_invoice = get_post_meta($order_id, 'QB_invoice', true);
	$selected = '';
	if ($QB_invoice == true) $selected = 'selected';
	?>
  <div class="quickbooks-type-select">
    Select Quicbooks Invoice Type:
    <select name="quickbooks-type" id="quickbooks-type">
      <option value="0">No Invoice</option>
      <option value="1" <?php echo $selected; ?>>Enable Invoice</option>
    </select>
    <input type="hidden" name="quickbooks-order-id" value="<?php echo $order_id; ?>">
    <br>
  </div>

  <script>
      jQuery('select#quickbooks-type').on('change', function (e) {
          e.preventDefault();


          var quickbooks_invoice = jQuery('select[name="quickbooks-type"]').val();
          var order_id = jQuery('input[name="quickbooks-order-id"]').val();

          jQuery.ajax({
              method: "POST",
              url: "/wp-content/themes/storefront-child/ajax/quickbooks-invoice-change.php",
              data: {
                  order_id: order_id,
                  quickbooks_invoice: quickbooks_invoice
              },
              beforeSend: function () {
                  jQuery("body .spinner-modal").show();
              },
              complete: function () {
                  jQuery("body .spinner-modal").hide();
              },
              success: function (data) {
                  success = true;
                  alert('QuickBooks Type Change!');
                  console.log("QuickBooks Invoice: " + data);
              },
              fail: function (xhr, textStatus, errorThrown) {
                  success = true;
                  jQuery(this).remove();
                  alert('QuickBooks Type Change Failed!');
                  console.log("QuickBooks Invoice Failed: " + errorThrown);
              }
          })
              .done(function (data) {
                  // alert( "shipping: " + data );
                  // jQuery('.show-shipping').html(data);
                  setTimeout(function () {
                      location.reload();
                  }, 500);

              });
      });
  </script>
	<?php

	$out = ob_get_contents();
	ob_end_clean();

	echo $out;
}


function items_edit_render_menu_meta_box()
{
	$order_id = get_the_id();

	// Get an instance of the WC_Order Object from the Order ID (if required)
	$order = wc_get_order($order_id);

	// Get the order meta data in an unprotected array
	$data = $order->get_data(); // The Order data

	// Get the Customer ID (User ID)
	$customer_id = $data['customer_id'];

	ob_start();

	$orders_to_edit = get_user_meta($customer_id, 'orders_to_edit', true);
	$selected = '';
	if (isset($orders_to_edit[$order_id]) && $orders_to_edit[$order_id] == 'editable') {
		$selected = 'selected';
	}

	// print_r($orders_to_edit);
	?>
  <div class="orders-to-select">
    Select Order Editable Type:
    <select name="orders_to_edit" id="orders_to_edit">
      <option value="0">Not Editable</option>
      <option value="1" <?php echo $selected; ?>>Editable</option>
    </select>
    <input type="hidden" name="order-items-id" value="<?php echo $order_id; ?>">
    <input type="hidden" name="customer-id" value="<?php echo $customer_id; ?>">
    <br>
  </div>

  <script>
      jQuery('select#orders_to_edit').on('change', function (e) {
          e.preventDefault();

          var orders_to_edit = jQuery('select[name="orders_to_edit"]').val();
          var order_id = jQuery('input[name="order-items-id"]').val();
          var customer_id = jQuery('input[name="customer-id"]').val();

          console.log(orders_to_edit + ' ' + order_id + ' ' + customer_id);

          jQuery.ajax({
              method: "POST",
              url: "/wp-content/themes/storefront-child/ajax/order-edit-customer.php",
              data: {
                  order_id: order_id,
                  orders_to_edit: orders_to_edit,
                  customer_id: customer_id
              },
              beforeSend: function () {
                  jQuery("body .spinner-modal").show();
              },
              complete: function () {
                  jQuery("body .spinner-modal").hide();
              },
              success: function (data) {
                  success = true;
                  console.log("order item editable: " + data);
                  alert('Order editable by customer!');
              },
              fail: function (xhr, textStatus, errorThrown) {
                  success = true;
                  jQuery(this).remove();
                  console.log("order item editable Failed: " + errorThrown);
                  alert('Order editable by customer Failed!');
              }
          })
              .done(function (data) {
                  // alert( "shipping: " + data );
                  // jQuery('.show-shipping').html(data);
                  setTimeout(function () {
                      location.reload();
                  }, 500);

              });
      });
  </script>
	<?php

	$out = ob_get_contents();
	ob_end_clean();

	echo $out;
}


function order_price_edit_render_menu_meta_box()
{
	$order_id = get_the_id();
	$order = wc_get_order($order_id);

	ob_start();
	$price_for_update = get_post_meta($order_id, 'price_for_update', true);
	$selected = '';
	if ($price_for_update == 'new') $selected = 'selected';
	?>
  <div class="orders-to-select">
    Select Order Editable Type:
    <select name="price_for_update" id="price_for_update">
      <option value="old">Old Price</option>
      <option value="new" <?php echo $selected; ?>>New Price</option>
    </select>
    <input type="hidden" name="order-id" value="<?php echo $order_id; ?>">
    <br>
  </div>

  <script>
      jQuery('select#price_for_update').on('change', function (e) {
          e.preventDefault();

          var price_for_update = jQuery('select[name="price_for_update"]').val();
          var order_id = jQuery('input[name="order-id"]').val();

          jQuery.ajax({
              method: "POST",
              url: "/wp-content/themes/storefront-child/ajax/meta-boxes/order-price-for-update.php",
              data: {
                  order_id: order_id,
                  price_for_update: price_for_update,
              },
              beforeSend: function () {
                  jQuery("body .spinner-modal").show();
              },
              complete: function () {
                  jQuery("body .spinner-modal").hide();
              },
              success: function (data) {
                  success = true;
                  console.log("order price: " + data);
                  alert('Order price set to ' + price_for_update);
              },
              fail: function (xhr, textStatus, errorThrown) {
                  success = true;
                  jQuery(this).remove();
                  console.log("order price Failed: " + errorThrown);
                  alert('Order price set Failed!');
              }
          })
              .done(function (data) {
                  // alert( "shipping: " + data );
                  // jQuery('.show-shipping').html(data);
                  setTimeout(function () {
                      location.reload();
                  }, 500);

              });
      });
  </script>
	<?php

	$out = ob_get_contents();
	ob_end_clean();

	echo $out;
}


function repair_oder_status_content()
{
	$order_statuses = array('account-on-hold' => 'Account-on-hold', 'on-hold' => 'On hold', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled', 'refound' => 'Refunded', 'inproduction' => 'In Production', 'transit' => 'Order Shipped', 'waiting' => 'Waiting delivery', 'pending' => 'Pending payment');
	$current_order_status = get_post_meta(get_the_ID(), 'order_status', true);
//	foreach ($order_statuses as $key => $val) {
//		echo $key . '<br>';
//	}
	?>
  <p class="form-field form-field-wide wc-order-status">
    <label for="order_status">
      Status:
    </label>
    <select id="order_status"
            name="order_status">
			<?php
			foreach ($order_statuses as $key => $val) {
				if ($current_order_status == $key) {
					echo '<option value="' . $key . '" selected>' . $val . '</option>';
				} else {
					echo '<option value="' . $key . '">' . $val . '</option>';
				}
			}
			?>
    </select>
  </p>
	<?php
}


function repair_oder_deliveries_render($meta_id)
{

	$outline = '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Set Deliveries Start', 'text-domain') . '</label>';
	$title_field = get_post_meta($meta_id->ID, 'delivereis_start', true);
	$outline .= '<input type="text" name="deliveries-repair" id="title_field" class="title_field" value="' . esc_attr($title_field) . '" style="width:auto;"/>';

	echo $outline;
}


function repair_oder_warranty_payment($meta_id)
{

	$no_warranty = false;
	$order_id = get_post_meta($meta_id->ID, 'order-id-original', true);
	$warranty = get_post_meta($meta_id->ID, 'warranty', true);
	$type_cost = get_post_meta($meta_id->ID, 'type_cost', true);
	$order = new WC_Order($order_id);
	$items = $order->get_items();
	foreach ($items as $item_id => $item_data) {
		if ($warranty[$item_id] == 'No') {
			$no_warranty = true;
		}
	}

//	if ($no_warranty) {
	$sqm = ($type_cost == 'sqm') ? 'checked' : '';
	$fixed = ($type_cost == 'fixed') ? 'checked' : '';
	$outline = '<label for="sqm" style="display: inline-block; float: left;margin: 0 10px;"><input type="radio" id="sqm" name="type_cost" value="sqm" ' . $sqm . '> /sqm</label> ';
	$outline .= ' <label for="fixed" style="display: inline-block;margin: 0 10px;"><input type="radio" id="fixed" name="type_cost" value="fixed" ' . $fixed . '>
                       fixed</label><br>';
	$outline .= '<label for="title_field" style="width:150px; display:inline-block;">' . esc_html__('Set Cost Repair', 'text-domain') . '</label>';
	$cost_field = get_post_meta($meta_id->ID, 'cost_repair', true);
	$outline .= '<input type="text" name="cost_repair" id="title_field" class="title_field" value="' . esc_attr($cost_field) . '" style="width:auto;"/>';

	echo $outline;
//	}
}


function dealer_notification_callback()
{
	global $post;

	$location = get_post_meta($post->ID, 'location', true);
	// Output the field
	echo '<button class="button button-primary button-large send-dealer-notification" idpost="' . $post->ID . '" >Send Mail Notification</button>';

	echo '
        <script>
            jQuery(".send-dealer-notification").click(function(e){
                e.preventDefault();
               
                var idTicket = jQuery(".send-dealer-notification").attr("idpost");
                 console.log("click: "+idTicket);
                jQuery.ajax({
                  method: "POST",
                  url: "/wp-content/themes/storefront-child/ajax/send-dealer-notification.php",
                  data: { idTicket: idTicket }
                })
                  .done(function( msg ) {
                    alert( "Data Saved: " + msg );
                  });
            });
        </script>
        ';
}


function containers_info_menu_container_meta_box($meta_id)
{
	include 'meta-boxes-content/containers-info-sync.php';
}


function lf_list_container_meta_box($meta_id)
{
	include 'meta-boxes-content/container-lf-list.php';
}


function lf_list_container_status_meta_box($meta_id)
{
	include 'meta-boxes-content/container-lf-list-status.php';
}


function show_product_video_meta_box($post)
{
	// Add a nonce field for security
	wp_nonce_field('product_video_nonce', 'product_video_nonce');

	// Get the current video URLs
	$video_urls = get_post_meta($post->ID, 'product_video_url', true);

	// If there are no video URLs, initialize the array
	if (!is_array($video_urls)) {
		$video_urls = array('');
	}

	// Add an input field for each video URL
	foreach ($video_urls as $index => $video_url) {
		echo '<input type="text" id="product_video_url_' . $index . '" name="product_video_url[]" value="' . esc_attr($video_url) . '" class="video_url" style="width: 100%;">';
	}

	// Add the upload button
	echo '<button id="upload-button" class="button">Upload Video</button>';

	// Add some JavaScript to handle the add and remove buttons
	?>
  <script>
      jQuery(document).ready(function ($) {
          $("#add-video-url").click(function (e) {
              e.preventDefault();
              $(".video-url-wrapper:last").clone().appendTo(".video-url-wrapper:last").parent();
              $(".video-url-wrapper:last input").val("");
          });
          $(document).on("click", ".remove-video-url", function (e) {
              e.preventDefault();
              if ($(".video-url-wrapper").length > 1) {
                  $(this).parent().remove();
              }
          });
      });


      jQuery(document).ready(function ($) {
          var mediaUploader;

          $('#upload-button').click(function (e) {
              e.preventDefault();
              // If the uploader object has already been created, reopen the dialog
              if (mediaUploader) {
                  mediaUploader.open();
                  return;
              }
              // Extend the wp.media object
              mediaUploader = wp.media.frames.file_frame = wp.media({
                  title: 'Choose Video',
                  button: {
                      text: 'Choose Video'
                  },
                  multiple: true  // Allow multiple files to be selected
              });
              // When a video is selected, grab the URL and append it to the text field's value
              mediaUploader.on('select', function () {
                  var attachments = mediaUploader.state().get('selection').map(
                      function (attachment) {
                          attachment.toJSON();
                          return attachment;
                      });

                  var video_urls = $('#video_url').val();
                  attachments.forEach(function (attachment) {
                      if (video_urls) {
                          video_urls += ',' + attachment.attributes.url;
                      } else {
                          video_urls = attachment.attributes.url;
                      }
                  });

                  $('#video_url').val(video_urls);
              });
              // Open the uploader dialog
              mediaUploader.open();
          });
      });


  </script>
	<?php
}


function save_product_video_meta_box($post_id)
{
	// Check if the nonce is set
	if (!isset($_POST['product_video_nonce'])) {
		return;
	}

	// Verify the nonce
	if (!wp_verify_nonce($_POST['product_video_nonce'], 'product_video_nonce')) {
		return;
	}

	// Check if the user has the capability to edit the post
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	// Save the video URLs
	if (isset($_POST['product_video_url'])) {
		update_post_meta($post_id, 'product_video_url', $_POST['product_video_url']);
	}
}


add_action('save_post', 'save_product_video_meta_box');

function display_product_videos()
{
	global $post;

	// Get the video URLs
	$video_urls = get_post_meta($post->ID, 'product_video_url', true);

	// If there are no video URLs, exit the function
	if (!$video_urls || !is_array($video_urls)) {
		return;
	}

	// Loop through the video URLs and display each one
	foreach ($video_urls as $video_url) {
		// Check if the video URL is not empty
		if (!empty($video_url)) {
			// Display the video
			echo '<div class="product-video">';
			echo '<video width="320" height="240" controls>';
			echo '<source src="' . esc_url($video_url) . '" type="video/mp4">';
			echo 'Your browser does not support the video tag.';
			echo '</video>';
			echo '</div>';
		}
	}
}


add_action('woocommerce_after_single_product_summary', 'display_product_videos', 25);
