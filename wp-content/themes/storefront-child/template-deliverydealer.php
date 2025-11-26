<?php

/**
 * The template for displaying full width pages.
 *
 * Template Name: Deliverys Dealer
 *
 */

get_header();

?>

  <div id="primary" class="content-area">

    <main id="main" class="site-main" role="main">


			<?php if (current_user_can('employe') || current_user_can('administrator') || get_current_user_id() == 83) {

				$id_container = base64_decode($_GET['id_container']);

				$dealer_id = base64_decode($_GET['dealer_id']);

				$id_selected_container = $id_container;

				$container_orders = get_post_meta($id_selected_container, 'container_orders', true);

				$csv_orders_array_cartons = get_post_meta($id_selected_container, 'csv_orders_array_cartons', true);

				$csv_orders_array_cartons_repair = get_post_meta($id_selected_container, 'csv_orders_array_cartons_repair', true);

				$csv_orders_array_panels = get_post_meta($id_selected_container, 'csv_orders_array_panels', true);

				$csv_orders_array_frames = get_post_meta($id_selected_container, 'csv_orders_array_frames', true);

				$csv_orders_array_panels_repair = get_post_meta($id_selected_container, 'csv_orders_array_panels_repair', true);

				$csv_orders_array_frames_repair = get_post_meta($id_selected_container, 'csv_orders_array_frames_repair', true);

				$csv_orders_array_repair_sqm = get_post_meta($id_selected_container, 'csv_orders_array_repair', true);

//                echo '<pre>';
//                print_r($container_orders);
//                echo '</pre>';

//print_r($container_orders);

				//  arsort($container_orders);

//print_r($container_orders);

				?>

        <div class="clearfix"></div>

        <div class="row container-order">


          <div class="col-md-12">


            <form method="post" id="form-van-service" class="form-inline">


              <br>


              <table id="example"

                     style="width:100%; display:table;"

                     class="table table-striped">
                <thead style="font-weight: bold;">

                <tr>

                  <th>

                    Nr.

                  </th>

                  <th>

                    Van

                  </th>

                  <th>

                    Order

                  </th>

                  <th>

                    Order

                    Ref

                  </th>

                  <th>

                    SQM

                  </th>

                  <th>

                    Panels

                  </th>

                  <th>

                    Frames

                  </th>

                  <th>

                    Cartons

                  </th>

                  <th>

                    Weight

                  </th>

                  <!--                                    <th>-->

                  <!--                                        Delivery cost-->

                  <!--                                    </th>-->


                </tr>

                </thead>

                <tbody>


								<?php

								$total_sqm = 0;
								$weight_final = 0;
								$total_weight = 0;
								$total_cost = 0;

								$total_panels = 0;
								$total_frames = 0;
								$total_cartons = 0;
								$index_order = 0;

								if ($container_orders) {

									foreach ($container_orders as $order_id) {

										if (get_post_type($order_id) === 'order_repair') {
											$repair_id = $order_id;
											$order_id = get_post_meta($order_id, 'order-id-original', true);

											$order = wc_get_order($order_id);
											$order_data = $order->get_data();
										} elseif (get_post_type($order_id) === 'shop_order') {
											$repair_id = '';
											$order = wc_get_order($order_id);
											$order_data = $order->get_data();
										}

										$user_id_customer = get_post_meta($order->get_id(), '_customer_user', true);

										if ($user_id_customer == $dealer_id) {

											foreach ($order->get_items('tax') as $item_id => $item_tax) {
												$tax_shipping_total = $item_tax->get_shipping_tax_total();
												// Tax shipping total

											}

											if (current_user_can('administrator')) {
//                                                echo '<pre>';
//                                                print_r($order_id);
//                                                echo '<br>' . $order->get_order_number();
//                                                echo '</pre>';
											}

											$name = get_user_meta($dealer_id, 'shipping_first_name', true) . ' ' . get_user_meta($dealer_id, 'shipping_last_name', true);

											//  $phone_dealer = $order_data['billing']['phone'];
											$phone_dealer = get_user_meta($dealer_id, 'billing_phone', true);

											$company_dealer = $order_data['billing']['company'];

											//  $city_dealer = $order_data['billing']['city'];
											$city_dealer = get_user_meta($dealer_id, 'shipping_city', true);

											$address_dealer = get_user_meta($dealer_id, 'shipping_address_1', true) . ' - ' . get_user_meta($dealer_id, 'shipping_postcode', true);

											// $order = wc_get_order($order);

											// $order_data = $order->get_data();

											$full_payment = 0;

											$user_id_customer = get_post_meta($order_id, '_customer_user', true);

											//echo 'USER ID '.$user_id;

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

											$nr_code_prod = array();

											if ($order_data['shipping_total'] >= 0) {

//                                        if ($order_data['customer_id'] == 32 || $order_data['customer_id'] == 42 || $order_data['customer_id'] == 44 || $order_data['customer_id'] == 48 || $order_data['shipping_total'] > 0 ) {

												$index_order++;

												?>

                        <tr>

                          <td>

                            <!-- se  cumuleaza sqm, weight si price -->

														<?php

														echo $index_order;

														?>

                          </td>

                          <td>

														<?php echo get_post_meta($order_id, 'van_nr', true); ?>

                          </td>

                          <td>


														<?php if (get_post_type($repair_id) == 'order_repair') { ?>

                              LFR<?php echo $order->get_order_number(); ?>

														<?php } else { ?>

                              LF0<?php echo $order->get_order_number(); ?>

														<?php } ?>


                          </td>

                          <td><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></td>

                          <td><span id="sqm"><?php

															$sqm_total = 0;

															$weight_final = 0;

															foreach ($items as $item_id => $item_data) {

																$product_id = $item_data['product_id'];

																$property_total = get_post_meta($product_id, 'property_total', true);

																$sqm_total += (float)$property_total * (int)$item_data['quantity'];

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
																$sqm_total = $csv_orders_array_repair_sqm[$order_id];
																echo number_format($sqm_total, 2);
															} else {
																echo number_format($sqm_total, 2);
															}

															$total_sqm = $total_sqm + number_format($sqm_total, 2); ?></span>
                          </td>

                          <td>

														<?php
														if (get_post_type($repair_id) == 'order_repair') {
															$cartons = isset($csv_orders_array_cartons_repair[$order_id]) ? $csv_orders_array_cartons_repair[$order_id] : 0;
															$panels = isset($csv_orders_array_panels_repair[$order_id]) ? $csv_orders_array_panels_repair[$order_id] : 0;
															$frames = isset($csv_orders_array_frames_repair[$order_id]) ? $csv_orders_array_frames_repair[$order_id] : 0;
														} else {
															$cartons = isset($csv_orders_array_cartons[$order_id]) ? $csv_orders_array_cartons[$order_id] : 0;
															$panels = isset($csv_orders_array_panels[$order_id]) ? $csv_orders_array_panels[$order_id] : 0;
															$frames = isset($csv_orders_array_frames[$order_id]) ? $csv_orders_array_frames[$order_id] : 0;
														}

														$total_cartons += $cartons;
														$total_panels += $panels;
														$total_frames += $frames;

														echo $panels;
														?>
                          </td>
                          <td><?php echo $frames; ?></td>
                          <td><?php echo $cartons; ?></td>
                          <td>
                                                        <span id="weight"><?php echo number_format($weight_final, 2);
																													$total_weight = $total_weight + number_format($weight_final, 2); ?></span>
                          </td>
                          <!--                                                    <td>-->
                          <!--                                                        <span id="price">-->
													<?php //echo number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', '');

													//                                                            $total_cost = $total_cost + number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', ''); ?><!--</span>-->

                          <!--                                                    </td>-->

                        </tr>


											<?php }
										}
									}
								} ?>

                </tbody>

                <tfoot style="font-weight: bold;">

                <tr>

                  <td>

                    Nr.

                  </td>

                  <td>

                    Van

                  </td>

                  <td>

                    Order

                  </td>

                  <td>

                    Order

                    Ref

                  </td>

                  <td>Total SQM<br/>

										<?php echo $total_sqm; ?>

                  </td>

                  <td>Total Panels<br/>

										<?php echo $total_panels; ?>

                  </td>

                  <td>Total Frames<br/>

										<?php echo $total_frames; ?>

                  </td>

                  <td>Total Cartons<br/>

										<?php echo $total_cartons; ?>

                  </td>

                  <td>Total Weight<br/>

										<?php echo $total_weight; ?>

                  </td>

                  <!--                                    <th>Total delivery<br/>-->

                  <!--                                        --><?php //echo $total_cost; ?>

                  <!--                                    </th>-->

                </tr>

                </tfoot>

              </table>


            </form>


            <input type="hidden" name="company_dealer"

                   value="<?php echo (empty($company_dealer)) ? $name : $company_dealer;; ?>">

            <input type="hidden" name="address_dealer" value="<?php echo $address_dealer; ?>">

            <input type="hidden" name="city_dealer" value="<?php echo $city_dealer; ?>">

            <input type="hidden" name="dealer_name" value="<?php echo $name; ?>">

            <input type="hidden" name="phone_dealer" value="<?php echo $phone_dealer; ?>">

						<?php

						$title_cont = get_the_title($id_container);

						$pieces = explode("-", $title_cont);

						$container_nr = $pieces[1];

						?>


            <input type="hidden" name="id_container" value="<?php echo $container_nr; ?>">


            <div class="show-info"></div>


            <hr>


          </div>

        </div>


        <div class="clearfix"></div>


        <style>

            .row.container-order {

                margin-bottom: 50px;

            }

            tfoot td, thead th {

                font-weight: 900;

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

                  // 'print',

                  {

                      text: 'Back to Dealer List',

                      className: 'btn btn-primary',

                      action: function () {

                          window.location.href = '<?php echo get_site_url() . '/delivery-service/?order_id=' . $id_container . '&submit=Search'; ?>';

                      }

                  },

                  // {
                  //     extend: 'csvHtml5',
                  //     text: 'CSV',
                  //     className: 'btn btn-primary',
                  //     filename: jQuery('input[name="company_dealer"]').val() + '_' + jQuery('input[name="id_container"]').val(),
                  //     exportOptions: {
                  //         columns: ':visible'
                  //     }
                  // },

                  {
                      extend: 'excelHtml5',
                      text: 'XLS',
                      className: 'btn btn-info',
                      filename: jQuery('input[name="company_dealer"]').val() + '_' + jQuery('input[name="id_container"]').val(),
                      exportOptions: {
                          columns: ':visible'
                      }
                  },

                  {

                      text: 'PDF',
                      extend: 'pdfHtml5',

                      filename: jQuery('input[name="company_dealer"]').val() + '_' + jQuery('input[name="id_container"]').val(),

                      orientation: 'portrait', //portrait

                      pageSize: 'A4', //A3 , A5 , A6 , legal , letter

                      exportOptions: {

                          columns: ':visible',

                          search: 'applied',

                          order: 'applied'

                      },

                      footer: true,

                      customize: function (doc) {

                          //Remove the title created by datatTables

                          doc.content.splice(0, 1);

                          //Create a date string that we use in the footer. Format is dd-mm-yyyy

                          var now = new Date();

                          var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();

                          // Logo converted to base64

                          // var logo = getBase64FromImageUrl('https://datatables.net/media/images/logo.png');

                          // The above call should work, but not when called from codepen.io

                          // So we use a online converter and paste the string in.

                          // Done on http://codebeautify.org/image-to-base64-converter

                          // It's a LONG string scroll down to see the rest of the code !!!

                          var company = jQuery('input[name="company_dealer"]').val();

                          var address = jQuery('input[name="address_dealer"]').val();

                          var city = jQuery('input[name="city_dealer"]').val();

                          var phone = jQuery('input[name="phone_dealer"]').val();

                          var id_container = jQuery('input[name="id_container"]').val();

                          var dealer_name = jQuery('input[name="dealer_name"]').val();

                          var logo = 'data:image/png;base64,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';

                          // A documentation reference can be found at

                          // https://github.com/bpampuch/pdfmake#getting-started

                          // Set page margins [left,top,right,bottom] or [horizontal,vertical]

                          // or one number for equal spread

                          // It's important to create enough space at the top for a header !!!

                          doc.pageMargins = [20, 130, 20, 120];

                          // Set the font size fot the entire document

                          doc.defaultStyle.fontSize = 10;

                          // Set the fontsize for the table header

                          // Create a header object with 3 columns

                          // Left side: Logo

                          // Middle: brandname

                          // Right side: A document title

                          doc.styles.tableHeader = {

                              fontSize: 11,

                              background: 'white',

                              alignment: 'center',

                              bold: true,

                          };

                          doc.styles.tableFooter = {

                              fontSize: 11,

                              background: 'white',

                              alignment: 'center',

                              bold: true,

                          };

                          doc['header'] = (function () {

                              return {

                                  columns: [

                                      {

                                          alignment: 'left',

                                          fontSize: 11,

                                          margin: [10, 0],

                                          text: [

                                              {text: 'Lifetime Shutters Ltd \nUNIT 1,\nMill Farm Business Park,\nMillfield Road, Hounslow \nMiddlesex TW4 5PY\nTel: 02089401418 \n\n'},

                                              {text: 'Delivey Note #' + id_container, bold: true},

                                          ]

                                          // text: 'Lifetime Shutters Ltd \nUNIT 1,\nMill Farm Business Park,\nMillfield Road, Hounslow \nMiddlesex TW4 5PY\nTel: 02089401418 \n\nDelivey Note #' + id_container,


                                      },

                                      {

                                          alignment: 'center',

                                          image: logo,

                                          width: 90

                                      },

                                      {

                                          alignment: 'right',

                                          fontSize: 11,

                                          text: [

                                              {text: 'TO: ' + company + '\n', bold: true},

                                              {text: address + '\n' + city + '\n'},

                                              {text: 'Contact: ' + dealer_name + '\nTel: ' + phone, bold: true},

                                          ]

                                          // text: 'TO: ' + company + '\n' + address + '\n' + city + '\nTel: ' + phone,

                                      }

                                  ],

                                  margin: 20

                              }

                          });

                          // Create a footer object with 2 columns

                          // Left side: report creation date

                          // Right side: current page and total pages

                          doc['footer'] = (function (page, pages) {

                              return {

                                  columns: [

                                      {

                                          border: true,

                                          alignment: 'left',

                                          fontSize: 12,

                                          text: 'Date received: ______________________________ \nPrint Name: ________________________________ \nSigned: ____________________________________ \n(Received in good condition)',

                                      },

                                      {

                                          alignment: 'right',

                                          fontSize: 9,

                                          text: ['\n\n\npage ', {text: page.toString()}, ' of ', {text: pages.toString()}]

                                      }

                                  ],

                                  margin: 20

                              }

                          });

                          // Change dataTable layout (Table styling)

                          // To use predefined layouts uncomment the line below and comment the custom lines below

                          // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly

                          var objLayout = {};

                          objLayout['hLineWidth'] = function (i) {

                              return .5;

                          };

                          objLayout['vLineWidth'] = function (i) {

                              return .5;

                          };

                          objLayout['hLineColor'] = function (i) {

                              return '#aaa';

                          };

                          objLayout['vLineColor'] = function (i) {

                              return '#aaa';

                          };

                          objLayout['paddingLeft'] = function (i) {

                              return 4;

                          };

                          objLayout['paddingRight'] = function (i) {

                              return 4;

                          };

                          doc.content[0].layout = objLayout;

                      }

                  }

              ]


          });

      });


  </script>


<?php

get_footer();

