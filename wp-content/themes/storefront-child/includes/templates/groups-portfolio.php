<?php

/**
 * Obține utilizatorii după companie
 * @param string $company Numele companiei sau ID-ul
 * @return array Array de obiecte WP_User
 */
function get_user_ids_by_billing_company($company_name)
{
	// Get users with matching billing company
	$users = get_users(array(
	  'meta_key' => 'billing_company',
	  'meta_value' => $company_name,
	  'fields' => 'ids', // Only return IDs to be more efficient
	));

	return $users;
}


?>

<!-- Latest compiled and minified CSS -->
<!--<link rel="stylesheet"-->
<!--      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!---->
<!-- Latest compiled JavaScript -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!-- Use Font Awesome Free CDN modified-->
<!--<link rel="stylesheet"-->
<!--      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->

<script type='text/javascript'
        src='/wp-content/themes/storefront-child/js/highcharts.js'></script>
<style>#container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }</style>


<div id="primary"
     class="content-area container">
    <main id="main"
          class="site-main"
          role="main">

        <h2>Users Group</h2>

        <!-- Multi Cart Template -->
		<?php if (is_active_sidebar('multicart_widgett')) : ?>
            <div id="bmc-woocom-multisession-2"
                 class="widget bmc-woocom-multisession-widget">
				<?php //dynamic_sidebar( 'multicart_widgett' ); ?>
            </div>
            <!-- #primary-sidebar -->
		<?php endif;

		$user_id = get_current_user_id();
		$meta_key = 'wc_multiple_shipping_addresses';

		global $wpdb;
		if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
			$addresses = maybe_unserialize($addresses);
		}

		$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

		/*
				 * Create Group
				 * Delete Group
				 * Rename Group
				 */
		//  include_once(get_stylesheet_directory() . '/views/grup-users/grup-malipulation.php');

		/*
				 * Search Group
				 */
		?>
        <br>
        <h2>Search Group Info</h2>

        <form action="" method="POST" style="display:block;">
            <div class="row">

                <div class="col-sm-3 col-lg-2 form-group">
                    <br>
                    <input type="submit"
                           name="submit"
                           value="Select"
                           class="btn btn-info">
                </div>

                <div class="col-sm-4 col-lg-4 form-grup">
                    <label for="q_Status">Select group to show chart info</label>
                    <br>
                    <select id="q_status_order_id_eq"
                            name="group_name"
                            class="form-control">
                        <option value="all_users">All Dealers</option>
						<?php
						$groups_created = get_post_meta(1, 'groups_created', true);
						// Array of WP_User objects.
						foreach ($groups_created as $key => $group_name) {
							if ($_POST['group_name'] == $group_name) {
								echo '<option value="' . $group_name . '" selected>' . $group_name . '</option>';
							} elseif ($user_id === 192 && $group_name === 'andrew_clients') {
								$group = get_post_meta(1, "andrew_clients", true);
								echo '<option value="andrew_clients" selected>andrew_clients</option>';
							} else if ($user_id === 354 && $group_name === 'alex_clients') {
								$group = get_post_meta(1, "alex_clients", true);
								echo '<option value="alex_clients" selected>alex_clients</option>';
							} else {
								echo '<option value="' . $group_name . '">' . $group_name . '</option>';
							}
						} ?>

                    </select>
                </div>

				<?php
				$month = date("m");
				$luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'); ?>
                <div class="col-sm-4 col-lg-3 form-group">
                    <label for="select-luna">Select month:</label>
                    <br>
                    <select id="select-luna" name="luna_select">
						<?php foreach ($luni as $luna => $name_month) {
							if (!empty($_POST['luna_select'])) {
								$selected = ($_POST['luna_select'] == $luna) ? 'selected' : '';
							} else {
								$selected = ($month == $luna && empty($_POST['luna_select'])) ? 'selected' : '';
							} ?>
                            <option value="<?php echo $luna; ?>" <?php echo $selected; ?>>
								<?php echo $name_month; ?>
                            </option>
						<?php } ?>
                    </select>
                </div>
                <div class="col-sm-4 col-lg-3 form-group">
                    <label for="select-luna">Select year:</label>
                    <br>
                    <select id="select-an" name="an_select">
						<?php
						// Get the current year
						$currentYear = date("Y");

						// Initialize an empty array to hold the years
						$last10Years = [];

						// Loop to get the last 10 years
						for ($i = 0; $i < 10; $i++) {
							$last10Years[] = $currentYear - $i;
						}

						// Reverse the array to have the oldest year first
						$ani = array_reverse($last10Years);

						$current_an = date("Y");
						foreach (array_reverse($ani) as $an) {
							if (!empty($_POST['an_select'])) {
								$selected = ($_POST['an_select'] == $an) ? 'selected' : '';
							} else {
								$selected = ($current_an == $an) ? 'selected' : '';
							} ?>
                            <option value="<?php echo $an; ?>" <?php echo $selected; ?>>
								<?php echo $an; ?>
                            </option>
							<?php
						} ?>
                    </select>
                </div>
            </div>
        </form>
		<?php

		//		print_r($_POST);
		$user_id = get_current_user_id();
		$allowed_user_ids = [1, 2, 18, 192, 354]; // Array of allowed user IDs. Change these to the actual IDs you want to allow.

		if (isset($_POST['group_name'])) {
			if ($_POST['group_name'] === 'all_users') {
				$users = get_users(array('fields' => array('ID')));
				$group = array();
				foreach ($users as $user) {
					$group[] = $user->ID;
				}
			} else {
				$group = get_post_meta(1, $_POST['group_name'], true);
			}
		} else {
			if ($user_id === 192) {
				$group = get_post_meta(1, "andrew_clients", true);
			} else if ($user_id === 354) {
				$group = get_post_meta(1, "alex_clients", true);
			} else {
				$groups_created = get_post_meta(1, 'groups_created', true);
				$group = array();
				for ($i = 0; $i < count($groups_created); $i++) {
					$group_single = get_post_meta(1, $groups_created[$i], true);
					$group = array_merge($group, $group_single);
				}
			}
		}

		if (empty($_POST['group_name'])) {
			$users = get_users(array('fields' => array('ID')));
			$group = array();
			foreach ($users as $user) {
				$group[] = $user->ID;
			}
		}

		$i = 1;
		// luna selectata
		if (isset($_POST['luna_select'])) {
			$selected_month = $_POST['luna_select'];
		} else {
			$selected_month = date("m");
		}
		// an selectat
		if (isset($_POST['an_select'])) {
			$selected_year = $_POST['an_select'];
		} else {
			$selected_year = date("Y");
		}

		// print_r($group);

		$orders = array();
		// foreach ($group as $key => $user_id) {
		$args = array(
		  'customer_id' => $group,
		  'limit' => -1,
		  'type' => 'shop_order',
		  'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
		  'orderby' => 'date',
		  'date_query' => array(
			'after' => date('Y-m-d', mktime(0, 0, 0, $selected_month, 0, $selected_year)),
//        'before' => date('Y-m-t', mktime(0, 0, 0, $selected_month, 1, $selected_year)),
			'before' => date('Y-m-d', strtotime('+1 month', strtotime($selected_year . '-' . $selected_month . '-1'))),
		  ),
		  'return' => 'ids',
		);

		//echo ' after: ' . date('Y-m-d', mktime(0, 0, 0, $selected_month, 0, $selected_year));
		//echo ' before: ' . date('Y-m-d', strtotime('+1 month', strtotime($selected_year . '-' . $selected_month . '-1')));

		$orders = wc_get_orders($args);
		// $orders = array_merge($orders, $orders_user);
		// }

		$total = array();
		$totaly = array();
		$group_companies = array();

		foreach ($orders as $id_order) {
			$materials = array('Earth' => 0, 'Green' => 0, 'BiowoodPlus' => 0, 'Biowood' => 0, 'Supreme' => 0, 'Ecowood' => 0, 'EcowoodPlus' => 0);

			$order = wc_get_order($id_order);
			$order_data = $order->get_data();
			$order_status = $order_data['status'];

			global $wpdb;
			$tablename = $wpdb->prefix . 'custom_orders';
			$myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

			$property_total = (float)$myOrder['sqm'];
			$user_id = get_post_meta($id_order, '_customer_user', true);
			$company = get_user_meta($user_id, 'billing_company', true);
			if (!empty($user_id)) {
				$group_companies[$company]['user_id'] = $user_id;
			}
			if (array_key_exists($company, $group_companies)) {
				$group_companies[$company]['sqm'] = $group_companies[$company]['sqm'] + $property_total;
			} else {
				$group_companies[$company]['sqm'] = $property_total;
			}

			// Check if order has type_order meta with value "awning"
			$type_order = get_post_meta($id_order, 'type_order', true);
			if ($type_order === 'awning') {
				// Initialize awning type properties if not already set
				if (!isset($group_companies[$company]['type'])) {
					$group_companies[$company]['type'] = $type_order;
					$group_companies[$company]['awning_items'] = 0;
					$group_companies[$company]['awning_subtotal'] = 0;
				}

				// Count items in order
				$items_count = count($order->get_items());

				// Get order subtotal
				$order_subtotal = $order->get_subtotal();

				// Add to company totals
				$group_companies[$company]['awning_items'] += $items_count;
				$group_companies[$company]['awning_subtotal'] += $order_subtotal;
			}

			// materials
			$items_material = order_items_materials_sqm($id_order);
//    echo '<pre>';
//    print_r($items_material);
//    echo '</pre>';
			foreach ($items_material as $material => $material_sqm) {
				$prev_sqm = $materials[$material];
				$materials[$material] = (float)$material_sqm + (float)$prev_sqm;

				$group_companies[$company][$material] = (float)$group_companies[$company][$material] + (float)$material_sqm + (float)$prev_sqm;
			}

			$i++;
		}

		$materials = array('Earth' => 0, 'Green' => 0, 'BiowoodPlus' => 0, 'Biowood' => 0, 'Supreme' => 0, 'Ecowood' => 0, 'EcowoodPlus' => 0);
		foreach ($group as $user_group_id) {
			// if $user_group_id exists in $group_companies then not do anithing , else add $group_companies with 0 value
			$company = get_user_meta($user_group_id, 'billing_company', true);
			if (!array_key_exists($company, $group_companies)) {
				$group_companies[$company]['sqm'] = 0;
				foreach ($materials as $key => $value) {
					$group_companies[$company][$key] = 0;
				}
			}
		}

		uasort($group_companies, function ($a, $b) {
			// Compară valorile 'sqm'
			// Pentru sortare ascendentă, folosește:
//			return $a['sqm'] <=> $b['sqm'];

			// Pentru sortare descendentă, inversează comparația:
			return $b['sqm'] <=> $a['sqm'];
		});

		//		echo '<pre>';
		//		print_r($group_companies);
		//		echo '</pre>';

		// Obținem valorile default pentru prețurile materialelor
		$default_EcowoodPlus = get_post_meta(1, 'EcowoodPlus', true);
		$default_Ecowood = get_post_meta(1, 'Ecowood', true);
		$default_BiowoodPlus = get_post_meta(1, 'BiowoodPlus', true);
		$default_Biowood = get_post_meta(1, 'Biowood', true);
		$default_Green = get_post_meta(1, 'Green', true);
		$default_Supreme = get_post_meta(1, 'Supreme', true);
		$default_Earth = get_post_meta(1, 'Earth', true);

		//print_r($group_companies);
		$months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
		?>
        <br>
        <div id="grup-month" class="tab-pane">

            <div class="row">
                <div class="col-md-12">
                    <div id="users_month">
                        <table class="table table-bordered table-striped">
                            <!-- Tabelul de antet -->
                            <thead>
                            <tr>
                                <th>Nr.</th>
                                <th>User</th>
                                <th>Total SQM / <?php echo esc_html($months[$selected_month]); ?></th>
                                <th colspan="2" class="text-right">Ecowood</th>
                                <th colspan="2" class="text-right">EcowoodPlus</th>
                                <th colspan="2" class="text-right">Biowood</th>
                                <th colspan="2" class="text-right">BiowoodPlus</th>
                                <th colspan="2" class="text-right">Supreme</th>
                                <th colspan="2" class="text-right">Earth</th>
                                <th colspan="2" class="text-right">Awnings</th>
                            </tr>
                            </thead>

                            <!-- Corpul tabelului -->
                            <tbody>
							<?php

							// Inițializăm variabilele pentru totaluri
							$i = 1;
							$total_sqm = 0;
							$total_sqm_earth = 0;
							$total_sqm_ecowood = 0;
							$total_sqm_ecowoodPlus = 0;
							$total_sqm_green = 0;
							$total_sqm_biowood = 0;
							$total_sqm_biowoodPlus = 0;
							$total_sqm_supreme = 0;

							// Parcurgem array-ul $companys_sqm (cheia este ID-ul userului)
							foreach ($group_companies as $company => $data) {
								// Obținem informațiile userului

// Exemplu de utilizare
								if (!empty($company)) {
									$users_from_company = get_user_ids_by_billing_company($company);
								}

//			          echo '<pre>';
//                      echo $company;
//			          print_r($users_from_company);
//			          echo '</pre>';
								// Acumulăm totalul SQM pentru raport
								$total_sqm += $group_companies[$company]['sqm'];
								$total_sqm_earth += $group_companies[$company]['Earth'];
								$total_sqm_ecowood += $group_companies[$company]['Ecowood'];
								$total_sqm_ecowoodPlus += $group_companies[$company]['EcowoodPlus'];
								$total_sqm_green += $group_companies[$company]['Green'];
								$total_sqm_biowood += $group_companies[$company]['Biowood'];
								$total_sqm_biowoodPlus += $group_companies[$company]['BiowoodPlus'];
								$total_sqm_supreme += $group_companies[$company]['Supreme'];

//			          $user_id = $group_companies[$company]['user_id'];
								$user_id = $users_from_company[0];

								// Preluăm valorile din user meta; dacă nu există, folosim valorile default
								$ecowood = get_user_meta($user_id, 'Ecowood', true);
								if (empty($ecowood)) {
									$ecowood = $default_Ecowood;
								}
								$ecowoodPlus = get_user_meta($user_id, 'EcowoodPlus', true);
								if (empty($ecowoodPlus)) {
									$ecowoodPlus = $default_EcowoodPlus;
								}
								$biowood = get_user_meta($user_id, 'Biowood', true);
								if (empty($biowood)) {
									$biowood = $default_Biowood;
								}
								$biowoodPlus = get_user_meta($user_id, 'BiowoodPlus', true);
								if (empty($biowoodPlus)) {
									$biowoodPlus = $default_BiowoodPlus;
								}
								$green = get_user_meta($user_id, 'Green', true);
								if (empty($green)) {
									$green = $default_Green;
								}
								$supreme = get_user_meta($user_id, 'Supreme', true);
								if (empty($supreme)) {
									$supreme = $default_Supreme;
								}
								$earth = get_user_meta($user_id, 'Earth', true);
								if (empty($earth)) {
									$earth = $default_Earth;
								}

								// Preluăm telefonul și adresa de livrare a userului
								$phone_number = get_user_meta($user_id, 'billing_phone', true);
								$shipping_address = array(
								  'first_name' => get_user_meta($user_id, 'shipping_first_name', true),
								  'last_name' => get_user_meta($user_id, 'shipping_last_name', true),
								  'company' => get_user_meta($user_id, 'shipping_company', true),
								  'address_1' => get_user_meta($user_id, 'shipping_address_1', true),
								  'address_2' => get_user_meta($user_id, 'shipping_address_2', true),
								  'city' => get_user_meta($user_id, 'shipping_city', true),
								  'state' => get_user_meta($user_id, 'shipping_state', true),
								  'postcode' => get_user_meta($user_id, 'shipping_postcode', true),
								  'country' => get_user_meta($user_id, 'shipping_country', true),
								);
								?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <!-- Butonul deschide un modal cu detaliile utilizatorului -->
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                data-bs-name="<?php echo esc_html($company); ?>"
                                                data-bs-phone="<?php echo esc_html($phone_number); ?>"
                                                data-bs-postcode="<?php echo esc_html($shipping_address['postcode']); ?>"
                                                data-bs-dealer="<?php echo esc_attr($user_id); ?>"
                                                data-bs-address="<?php echo esc_html($shipping_address['address_1']); ?>">
											<?php echo esc_html($company); ?>
                                        </button>
                                    </td>
                                    <td><?php echo number_format($group_companies[$company]['sqm'], 2); ?> SQM</td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($ecowood); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['Ecowood'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($ecowoodPlus); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['EcowoodPlus'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($biowood); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['Biowood'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($biowoodPlus); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['BiowoodPlus'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($supreme); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['Supreme'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">£<?php echo esc_html($earth); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['Earth'], 2); ?></td>
                                    <td class="text-right" style="background-color: #feff8252;">
                                        £<?php echo number_format($group_companies[$company]['awning_subtotal'], 2); ?></td>
                                    <td class="text-right"><?php echo number_format($group_companies[$company]['awning_items'], 2); ?></td>
                                </tr>
								<?php
								$i++;
							}
							?>
                            </tbody>

                            <!-- Tabelul de totaluri (footer) -->
                            <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total SQM <?php echo number_format($total_sqm, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_ecowood, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_ecowoodPlus, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_biowood, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_biowoodPlus, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_supreme, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format($total_sqm_earth, 2); ?></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php echo number_format(0, 2); ?></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- *********************************************************************************************
			End Total user sqm
			*********************************************************************************************	-->

    </main>
    <!-- #main -->
</div>
<!-- #primary -->

<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
	$month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
	$months_cart[] = $month;
}
?>

<script>
    jQuery(document).ready(function () {

        // Cache commonly used DOM elements to optimize performance
        var exampleModal = jQuery('#exampleModal');
        var messageText = jQuery('#message-text');
        var dealerIdField = jQuery('#dealer-id');

        // Event listener for when the modal is about to be shown
        jQuery('#exampleModal').on('show.bs.modal', function (event) {
            // Button that triggered the modal
            var button = jQuery(event.relatedTarget);

            // Extract info from data-bs-* attributes
            var name = button.data('bs-name'); // Using jQuery's .data() method
            var phone = button.data('bs-phone'); // Using jQuery's .data() method
            var address = button.data('bs-address'); // Using jQuery's .data() method
            var dealerId = button.data('bs-dealer'); // Using jQuery's .data() method
            var postcode = button.data('bs-postcode'); // Using jQuery's .data() method
            console.log('dealerId', dealerId);

            // insert into span
            jQuery('.dealer-name').text(name);
            jQuery('.dealer-phone').text(phone);
            jQuery('.dealer-postcode').text(postcode);
            jQuery('.dealer-address').text(address);

            // Update the modal's content.
            var modalTitle = jQuery(this).find('.modal-title');
            var modalBodyInput = jQuery(this).find('.modal-body input#dealer-name');
            var modalBodyInputPhone = jQuery(this).find('.modal-body input#dealer-phone');
            var modalBodyInputPostcode = jQuery(this).find('.modal-body input#dealer-postcode');
            var modalBodyInputAddress = jQuery(this).find('.modal-body input#dealer-address');
            var modalBodyInputId = jQuery(this).find('.modal-body input#dealer-id');

            modalTitle.text('Notes for ' + name); // Using .text() to update title
            modalBodyInput.val(name); // Using .val() to update input's value
            modalBodyInputPhone.val(phone); // Using .val() to update input's value
            modalBodyInputPostcode.val(postcode); // Using .val() to update input's value
            modalBodyInputAddress.val(address); // Using .val() to update input's value
            modalBodyInputId.val(dealerId); // Using .val() to update input's value

            // Clear any existing messages from previous uses of the modal and fetch new ones
            jQuery('.user-message').remove();
            fetchMessages(dealerId);
        });

        // Event listener for when the 'Send Message' button is clicked
        jQuery('.btn-primary.send-notes').click(function () {
            var message = messageText.val().trim();  // Get and trim the whitespace from the input message
            if (message) {
                sendMessage(dealerIdField.val(), message);  // Send message if not empty
            } else {
                alert('Please enter a message.');  // Alert if message is empty
            }
        });

        // Function to fetch messages for a given dealer and update the modal
        function fetchMessages(dealerId) {
            jQuery.ajax({
                url: _wpUtilSettings.ajax.url,  // URL from WP AJAX setup
                type: 'POST',
                data: {
                    action: 'get_user_messages',  // WP AJAX action
                    user_id: dealerId  // User/dealer ID for whom messages are fetched
                },
                success: function (response) {
                    if (response.success) {
                        displayMessages(response.data);  // Display messages if AJAX call was successful
                    } else {
                        console.error('Failed to retrieve messages: ', response.data);  // Log errors to console
                    }
                },
                error: function () {
                    console.error('Failed to retrieve messages.');  // Log AJAX errors to console
                }
            });
        }

        // Function to send a new message to the server
        function sendMessage(dealerId, message) {
            jQuery.ajax({
                url: _wpUtilSettings.ajax.url,
                type: 'POST',
                data: {
                    action: 'save_user_message',  // WP AJAX action
                    user_id: dealerId,  // Dealer ID
                    message: message  // Message text
                },
                success: function (response) {
                    if (response.success) {
                        messageText.val('');  // Clear the textarea after message is sent
                        fetchMessages(dealerId);

                    } else {
                        console.error('Error saving message: ', response.data);  // Log save errors to console
                    }
                },
                error: function () {
                    console.error('Error saving message.');  // Log AJAX errors to console
                }
            });
        }

        // Function to append messages to the modal body just above the textarea
        function displayMessages(messages) {
            jQuery('.user-message').remove();
            var messagesHtml = messages.map(function (message) {
                return '<p class="user-message">' + message + '</p>';  // Format each message as a paragraph
            }).join('');
            messageText.before(messagesHtml);  // Insert formatted messages before the textarea
        }


    });


</script>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notes for </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-0">
                        <label for="dealer-name" class="col-form-label">Dealer: <span class="dealer-name"></span></label>
                        <input type="hidden" class="form-control" id="dealer-name">
                    </div>
                    <div class="mb-0">
                        <label for="dealer-phone" class="col-form-label">Phone: <span class="dealer-phone"></span></label>
                        <input type="hidden" class="form-control" id="dealer-phone">
                    </div>
                    <div class="mb-0">
                        <label for="dealer-address" class="col-form-label">Adress: <span class="dealer-address"></span></label>
                        <input type="hidden" class="form-control" id="dealer-address">
                    </div>
                    <div class="mb-0">
                        <label for="dealer-postcode" class="col-form-label">Postcode: <span class="dealer-postcode"></span></label>
                        <input type="hidden" class="form-control" id="dealer-postcode">
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" id="message-text" style="height: 200px"></textarea>
                    </div>
                    <input type="hidden" class="form-control" id="dealer-id">

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary send-notes">Send notes</button>
            </div>
        </div>
    </div>
</div>