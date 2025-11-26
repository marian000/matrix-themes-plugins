<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: DeliveryService template
 *
 */

get_header();

$container_id = (isset($_GET['container_id'])) ? $_GET['container_id'] : '';

?>

  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

			<?php if (current_user_can('employe') || current_user_can('administrator') || get_current_user_id() == 83) { ?>

        <form action="" method="GET">
          <div class="row">
            <div class="col-sm-6 col-lg-6 form-group">
              <label for="container_select">Show orders from container</label>
              <br>

              <select name="container_id" id="container_select" class="form-control">
								<?php
								$rand_posts = get_posts(array(
									'post_type' => 'container',
									'posts_per_page' => 25,
								));

								if ($rand_posts) {
									foreach ($rand_posts as $post) :
										$eta = get_post_meta(get_the_id(), 'delivereis_start', true);
										?>
                    <option value="<?php echo get_the_id(); ?>" <?php if ($container_id == get_the_id()) {
											echo 'selected';
										} ?> ><?php echo $eta . ' - ' . get_the_title(); ?></option>
									<?php
									endforeach;
									wp_reset_postdata();
								}
								?>
              </select>
            </div>
            <div class="col-sm-3 col-lg-3 form-group">

            </div>
            <div class="col-sm-3 col-lg-3 form-group">
              <br>
              <input type="submit" name="submit" value="Search" class="btn btn-info">

            </div>
          </div>

        </form>

				<?php

				if (!empty($_GET['container_id'])) {

					$container_id = $_GET['container_id'];
				} else {

					$args = array(
						'post_type' => 'container',
						'posts_per_page' => 1,
						'orderby' => 'post_date',
						'order' => 'DESC',
					);

					$container_posts = get_posts($args);

					foreach ($container_posts as $post) {
						$container_id = $post->ID;
					}
				}

				$id_selected_container = $container_id;

				$container_orders = get_post_meta($id_selected_container, 'container_orders', true);
				$csv_orders_array = get_post_meta($id_selected_container, 'csv_orders_array', true);
				$csv_orders_array_cartons = get_post_meta($id_selected_container, 'csv_orders_array_cartons', true);
				$csv_orders_array_cartons_repair = get_post_meta($id_selected_container, 'csv_orders_array_cartons_repair', true);

				$csv_orders_array_panels = get_post_meta($id_selected_container, 'csv_orders_array_panels', true);
				$csv_orders_array_frames = get_post_meta($id_selected_container, 'csv_orders_array_frames', true);
				$csv_orders_array_panels_repair = get_post_meta($id_selected_container, 'csv_orders_array_panels_repair', true);
				$csv_orders_array_frames_repair = get_post_meta($id_selected_container, 'csv_orders_array_frames_repair', true);
				$csv_orders_array_repair_sqm = get_post_meta($id_selected_container, 'csv_orders_array_repair', true);

//print_r($container_orders);
				//  arsort($container_orders);
//print_r($container_orders);
				?>
        <div class="clearfix"></div>
        <div class="row container-order">

          <div class="col-md-12">

            <form method="post" id="form-van-service" class="form-inline">

              <div class="form-group">
                <input type="hidden" name="container_selected"
                       value="<?php echo $id_selected_container; ?>">
                <input type="submit" class="button btn btn-default" name="submit"
                       value="Insert Orders to Van">
              </div>
              <div class="form-group">
              </div>
              <div class="form-group">
                <label for="van_nr"> Insert Van Number:</label>
                <input type="number" class="form-control" min="1" max="10" name="van_number" id="van_nr"
                       value="">
              </div>

              <br>

              <table id="example"
                     style="width:100%; display:table;"
                     class="table table-striped">
                <thead>
                <tr>
                  <th>
                  </th>
                  <th>
                    Van
                  </th>
                  <th>
                    COMPANY NAME
                  </th>
                  <th>
                    PHONE
                  </th>
                  <th>
                    DELIVERY ADD
                  </th>
                  <th>
                    SQM
                  </th>
                  <th>
                    PANELS
                  </th>
                  <th>
                    FRAMES
                  </th>
                  <th>
                    CARTONS
                  </th>
                  <th>
                    WEIGHT
                  </th>
                  <!--                                    <th>-->
                  <!--                                        DELIVERY COST-->
                  <!--                                    </th>-->

                </tr>
                </thead>
                <tbody>

								<?php
								$total_sqm = 0;
								$total_weight = 0;
								$total_cost = 0;

								$container_orders_dealer = array();
								$data = array();
								$total_panels = 0;
								$total_frames = 0;
								$total_cartons = 0;
								$old_user = 0;

								if ($container_orders) {
									foreach ($container_orders as $order_id) {
										if (get_post_type($order_id) == 'shop_order' || get_post_type($order_id) == 'order_repair') {

											if (get_post_type($order_id) == 'order_repair') {
												$order_is_repair = true;
												$repair_id = $order_id;
												$order_id = get_post_meta($order_id, 'order-id-original', true);
												$order = wc_get_order($order_id);
												$order_data = $order->get_data();
											} else {
												$repair_id = '';
												$order = wc_get_order($order_id);
												$order_data = $order->get_data();
											}
											$user_id_customer = get_post_meta($order_id, '_customer_user', true);
											if (!array_key_exists($user_id_customer, $container_orders_dealer)) {
												$container_orders_dealer[$user_id_customer] = array();
												$data = array();
											} else {
												$data = $container_orders_dealer[$user_id_customer];
											}

//                                        if (current_user_can('administrator')) {
//                                            echo '<pre>';
//                                            print_r($order_id);
//                                            echo '</pre>';
//                                        }
											$data['orders'][] = $order_id;

											foreach ($order->get_items('tax') as $item_id => $item_tax) {
												$tax_shipping_total = $item_tax->get_shipping_tax_total();
											}
											if (empty($data['tax_shipping_total'])) {
												$data['tax_shipping_total'] = 0 + $tax_shipping_total;
											} else {
												$data['tax_shipping_total'] = $data['tax_shipping_total'] + $tax_shipping_total;
											}
											$full_payment = 0;

											$i = 0;
											$atributes = get_post_meta(1, 'attributes_array', true);
											$items = $order->get_items();

											// get items and verfy shipping method
											$item_nr = 0;
											if ($item_nr < 1) {
												foreach ($items as $item_id => $item_data) {
													$product_id = $item_data['product_id'];
													$_product = wc_get_product($product_id);

													$shipclass = $_product->get_shipping_class();
													$item_nr++;
												}
											}

											if ($order_data['shipping_total'] >= 0) {

												$sqm_total = 0;
												$weight_final = 0;
												foreach ($items as $item_id => $item_data) {
													$product_id = $item_data['product_id'];
													$property_total = get_post_meta($product_id, 'property_total', true);
													$sqm_total = (float)$sqm_total + ((float)$property_total * (int)$item_data['quantity']);

													$property_material = get_post_meta($product_id, 'property_material', true);

													if ($property_material == 138) {
//                                                                $weight_final = $weight_final + $sqm_total*11;
														$weight_final = $weight_final + ($property_total * $item_data['quantity'] * 11);
													} elseif ($property_material == 139) {
//                                                                $weight_final = $weight_final + $sqm_total*12;
														$weight_final = $weight_final + ($property_total * $item_data['quantity'] * 12);
													} elseif ($property_material == 137) {
//                                                                $weight_final = $weight_final + $sqm_total*13;
														$weight_final = $weight_final + ($property_total * $item_data['quantity'] * 13);
													} elseif ($property_material == 187) {
//                                                                $weight_final = $weight_final + $sqm_total*15;
														$weight_final = $weight_final + ($property_total * $item_data['quantity'] * 14);
													} elseif ($property_material == 188) {
//                                                                $weight_final = $weight_final + $sqm_total*15;
														$weight_final = $weight_final + ($property_total * $item_data['quantity'] * 13);
													}
												}

												if (get_post_type($repair_id) == 'order_repair') {
													// Check if the index exists in the array
													if (isset($csv_orders_array_repair_sqm[$order_id])) {
														$sqm_total = $csv_orders_array_repair_sqm[$order_id];
														if ($order_is_repair) {
															$total_sqm += (float)$sqm_total;
														}
													} else {
														// Handle the case where the index doesn't exist
														// For example, set $sqm_total to 0 or some default value
														$sqm_total = 0;
													}
												} else {
													// Check if the index exists in the array
													if (isset($csv_orders_array[$order_id])) {
														$sqm_total = $csv_orders_array[$order_id];
														$total_sqm += (float)$sqm_total;
													} else {
														// Handle the case where the index doesn't exist
														// For example, set $sqm_total to 0 or some default value
														$sqm_total = 0;
													}
												}

												if (empty($data['sqm_total'])) {
													$data['sqm_total'] = 0 + (float)$sqm_total;
												} else {
													$data['sqm_total'] = $data['sqm_total'] + (float)$sqm_total;
												}

												if (get_post_type($repair_id) == 'order_repair') {
													// Check if the index exists in the arrays
													$cartons = isset($csv_orders_array_cartons_repair[$order_id]) ? $csv_orders_array_cartons_repair[$order_id] : 0;
													$panels = isset($csv_orders_array_panels_repair[$order_id]) ? $csv_orders_array_panels_repair[$order_id] : 0;
													$frames = isset($csv_orders_array_frames_repair[$order_id]) ? $csv_orders_array_frames_repair[$order_id] : 0;
												} else {
													// Check if the index exists in the arrays
													$cartons = isset($csv_orders_array_cartons[$order_id]) ? $csv_orders_array_cartons[$order_id] : 0;
													$panels = isset($csv_orders_array_panels[$order_id]) ? $csv_orders_array_panels[$order_id] : 0;
													$frames = isset($csv_orders_array_frames[$order_id]) ? $csv_orders_array_frames[$order_id] : 0;
												}

												$total_cartons = $total_cartons + (float)$cartons;
												$total_panels = $total_panels + (float)$panels;
												$total_frames = $total_frames + (float)$frames;
//                                            $data['cartons'] = $data['cartons'] + $cartons;
//                                            $data['panels'] = $data['panels'] + $cartons;
//                                            $data['frames'] = $data['frames'] + $cartons;
												if (empty($data['cartons'])) {
													$data['cartons'] = 0 + (float)$cartons;
													$data['panels'] = 0 + (float)$panels;
													$data['frames'] = 0 + (float)$frames;
												} else {
													$data['cartons'] = $data['cartons'] + (float)$cartons;
													$data['panels'] = $data['panels'] + (float)$panels;
													$data['frames'] = $data['frames'] + (float)$frames;
												}

												if (empty($data['weight_final'])) {
													$data['weight_final'] = 0 + $weight_final;
												} else {
													$data['weight_final'] = $data['weight_final'] + $weight_final;
												}
												$total_weight = $total_weight + number_format($weight_final, 2);
												$price = number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', '');
												$total_cost = $total_cost + number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', '');

												if (empty($data['price'])) {
													$data['price'] = 0 + $price;
												} else {
													$data['price'] = $data['price'] + $price;
												}
												$container_orders_dealer[$user_id_customer] = $data;
											}
										}
									}
								}

								foreach ($container_orders_dealer

								as $dealer_id => $data) {
								$name = get_user_meta($dealer_id, 'first_name', true) . ' ' . get_user_meta($dealer_id, 'last_name', true);
								$company = get_user_meta($dealer_id, 'shipping_company', true);
								$phone = get_user_meta($dealer_id, 'billing_phone', true);
								$address = get_user_meta($dealer_id, 'shipping_address_1', true);
								$postcode = get_user_meta($dealer_id, 'shipping_postcode', true);
								$city = get_user_meta($dealer_id, 'shipping_city', true);
								$country = get_user_meta($dealer_id, 'shipping_country', true);
								?>
                <tr>
                  <td>
                    <!-- se  cumuleaza sqm, weight si price -->
										<?php
										if (empty(get_user_meta($dealer_id, 'van_nr_' . $id_selected_container, true))) {
											?>
                      <input type="checkbox" value="<?php echo $dealer_id; ?>" name="container-dealer">
											<?php
										}
										?>
                  </td>
                  <td>
										<?php echo get_user_meta($dealer_id, 'van_nr_' . $id_selected_container, true); ?>
                  </td>
                  <td>
                    <a
                      href="/delivery-info/?id_container=<?php echo base64_encode($id_selected_container); ?>&dealer_id=<?php echo base64_encode($dealer_id); ?>"
                      class="">
											<?php
											echo (empty($company)) ? $name : $company;
											?>
                    </a>
                  </td>
                  <td><?php echo $phone; ?></td>
                  <td><?php echo $address . ' - ' . $postcode . ' - ' . $city . ' - ' . $country; ?></td>
                  <td><?php echo number_format($data['sqm_total'], 2); ?></td>
                  <td><?php echo $data['panels']; ?></td>
                  <td><?php echo $data['frames']; ?></td>
                  <td><?php echo $data['cartons']; ?></td>
                  <td><?php echo number_format($data['weight_final'], 2); ?></td>
                  <!--                                    <td>-->
									<?php //echo $data['price']; ?><!--</td>-->
									<?php
									}

									?>
                </tbody>
                <tfoot>
                <tr>
                  <th>
                  </th>
                  <th>
                    Van
                  </th>
                  <th>
                    Company Name
                  </th>
                  <th>
                    Phone
                  </th>
                  <th>
                    Delivery Add
                  </th>
                  <th>Total SQM<br/>
										<?php echo number_format($total_sqm, 2); ?>
                  </th>
                  <th>Total Panels<br/>
										<?php echo $total_panels; ?>
                  </th>
                  <th>Total Frames<br/>
										<?php echo $total_frames; ?>
                  </th>
                  <th>Total Cartons<br/>
										<?php echo $total_cartons; ?>
                  </th>
                  <th>Total Weight<br/>
										<?php echo number_format($total_weight, 2); ?>
                  </th>
                  <!--                                    <th>Total delivery<br/>-->
                  <!--                                        --><?php //echo $total_cost; ?>
                  <!--                                    </th>-->
                </tr>
                </tfoot>

                </tbody>
              </table>

            </form>

						<?php
						//                        if (current_user_can('administrator')) {
						//                            echo '<pre>';
						//                            print_r($container_orders_dealer);
						//                            echo '</pre>';
						//                        }
						?>

            <div class="show-info"></div>

            <hr>

            <!-- Select van -->
            <div class="row">
              <div class="col-sm-6 col-lg-6 form-group">
                <label for="order_select">Show Vans</label>
                <br>

                <select name="van_select" id="van_select" class="form-control">
									<?php
									for ($i = 1; $i < 11; $i++) {
										?>
                    <option value="<?php echo 'van_' . $i; ?>"> <?php echo 'van_' . $i; ?> </option>
										<?php
									}
									?>
                </select>
              </div>
              <div class="col-sm-3 col-lg-3 form-group">

              </div>
              <div class="col-sm-3 col-lg-3 form-group">
                <br>
                <input type="submit" name="submit" value="Get Van Orders"
                       class="btn btn-info van-info-submit">

              </div>
            </div>

            <div class="show-info-van"></div>

          </div>
        </div>

        <div class="clearfix"></div>

        <style>
            .row.container-order {
                margin-bottom: 50px;
            }
        </style>

			<? } ?>

    </main>
  </div>

  <script>

      jQuery(document).ready(function () {
          jQuery('#example').DataTable({
              lengthMenu: [100, 200, 500],
              dom: 'Bfrtip',
              buttons: [
                  'print'
              ]

          });


          //se  cumuleaza sqm, weight si price
          jQuery("#form-van-service").on("submit", function (event) {
              event.preventDefault();
              var van_data = jQuery(this).serialize();
              var selected = new Array();
              var container_id = jQuery('input[name="container_selected"]').val();
              var van_number = jQuery('input[name="van_number"]').val();

              jQuery('input:checkbox[name="container-dealer"]:checked').each(function () {
                  selected.push(jQuery(this).val());
                  // console.log(selected);
              });


              jQuery.ajax({
                  method: "POST",
                  url: "/wp-content/themes/storefront-child/ajax/van-ajax.php",
                  data: {
                      van: selected,
                      container_id: container_id,
                      van_number: van_number
                  }
              })
                  .done(function (msg) {
                      jQuery('.show-info').html(msg);
                  });
          });


          // info van details
          jQuery(".van-info-submit").on("click", function (event) {
              event.preventDefault();
              var van_select = jQuery('#van_select').val();
              var container_id = jQuery('input[name="container_selected"]').val();

              jQuery.ajax({
                  method: "POST",
                  url: "/wp-content/themes/storefront-child/ajax/vaninfo-ajax.php",
                  data: {
                      van_select: van_select,
                      container_id: container_id
                  }
              })
                  .done(function (msg) {
                      jQuery('.show-info-van').html(msg);
                  });
          });

      });

  </script>

<?php

//if(get_current_user_id() == 1){
//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//}

get_footer();
