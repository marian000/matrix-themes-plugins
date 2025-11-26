<?php

// Add the custom filter query for order_repair post type
add_filter('parse_query', 'order_repair_filter_request_query', 10);

function order_repair_filter_request_query($query)
{
	global $pagenow; // Global variable to hold the current admin page

	// Get the post type from the URL parameter
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';

	// Check if we are in the admin area, on the 'edit.php' page, and filtering 'order_repair' post type
	if (is_admin() && $pagenow == 'edit.php' && $post_type == 'order_repair') {

		// Check if 'customer_order' filter is set and not empty
		if (isset($_GET['customer_order']) && !empty($_GET['customer_order'])) {

			// Get the user by login
			$user = get_user_by('login', $_GET['customer_order']);

			if ($user) {
				$dealer_id = $user->ID;
				$users_orders = array();

				// Check user roles and set users_orders accordingly
				if (in_array('salesman', $user->roles, true) || in_array('subscriber', $user->roles, true) || (in_array('emplimited', $user->roles) && !in_array('dealer', $user->roles))) {
					// Single dealer
					$users_orders = array($dealer_id);
				}
				if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
					// Get the company parent ID from user meta
					$parent_id = get_user_meta($dealer_id, 'company_parent', true);

					if (!empty($parent_id)) {
						// Get all employees under the parent company
						$users_orders = get_user_meta($parent_id, 'employees', true);
						$users_orders[] = $parent_id;
						$users_orders = array_reverse($users_orders);
					}
				}
				if (in_array('dealer', $user->roles)) {
					// Get all employees under the dealer
					$users_orders = get_user_meta($dealer_id, 'employees', true);
					$users_orders[] = $dealer_id;
					$users_orders = array_reverse($users_orders);
				}

				// If users_orders is not empty, modify the query to filter by post_author
				if (!empty($users_orders)) {
					$query->query_vars['author__in'] = $users_orders;
				}
			}
		}
	}
}


function add_order_repair_filters()
{
	global $typenow; // Global variable to hold the current post type

	// Check if we are on the 'order_repair' post type page
	if ($typenow == 'order_repair') {
		// Output an input field for the 'customer_order' filter
		?>
    <!--    <input type="text" name="customer_order" placeholder="--><?php //_e('Customer Order', 'theme_domain'); ?><!--" value="--><?php //echo isset($_GET['customer_order']) ? esc_attr($_GET['customer_order']) : ''; ?><!--" />-->
		<?php

		// Query to get all users
		$user_query = get_users();
		$users_data = array();
		foreach ($user_query as $user) {
			$newObject = new stdClass();
			$newObject->id = $user->ID;
			$newObject->user_login = $user->user_login;
			$newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);

			$users_data[] = $newObject;
		}

		// Initialize the dropdown filter with a default value of 'All companies'
		$filter_value = empty($_GET['customer_order']) ? 'All companies' : $_GET['customer_order'];

		// Output the company name filter dropdown
		echo '<select name="customer_order" id="company_name_filter_repair">
              <option value="">Filter By companies</option>';
		foreach ($users_data as $user) {
//          print_r($user);
			$selected = ($filter_value == $user->user_login) ? 'selected' : '';
			echo '<option value="' . $user->user_login . '" ' . $selected . '>' . $user->billingCompany . '</option>';
		}
		echo '</select>';
		?>
    <script>
        jQuery(document).ready(function ($) {
            jQuery('#company_name_filter_repair').select2({
                ajax: {
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            search: params.term,
                            action: 'search_repair_company_name'
                        };
                    },
                    processResults: function (data) {
                        var options = [];
                        if (data) {
                            console.log('data ajax return:', data);
                            // data is the array of arrays, and each of them contains ID and the Label of the option
                            $.each(data, function (index, user) { // do not forget that "index" is just auto incremented value
                                options.push({id: user.id, text: user.billingCompany});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
            });
        });
    </script>
		<?php
	}
}


add_action('restrict_manage_posts', 'add_order_repair_filters');

add_filter('parse_query', 'order_components_type_filter_request_query', 10);
function order_components_type_filter_request_query($query)
{
	global $pagenow;
	// Get the post type
	$post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';
	if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['order_components_type']) && !empty($_GET['order_components_type'])) {
		$query->query_vars['meta_query'][] = array(
			'key' => 'order_components_type',
			'value' => $_GET['order_components_type'],
			'compare' => 'LIKE',
		);
	}
	if (is_admin() && $pagenow == 'edit.php' && $post_type == 'shop_order' && isset($_GET['company_name']) && $_GET['company_name'] != '') {
		// Add meta query to fetch orders by _billing_company meta key
		$query->query_vars['meta_query'][] = array(
			'key' => '_billing_company',
			'value' => $_GET['company_name'],
			'compare' => 'LIKE',
		);
	}
}


// this action brings up a dropdown select box over the posts list in the dashboard
//add_action('restrict_manage_posts', 'my_custom_restrict_manage_posts', 50);
function my_custom_restrict_manage_posts($post_type)
{
	if (current_user_can('manage_options')) {
		if ($post_type == 'shop_order') {
			$selected = '';
			$request_attr = 'order_components_type';
			if (isset($_REQUEST[$request_attr])) {
				$selected = $_REQUEST[$request_attr];
			}
			//get unique values of the meta field to filer by.
			$results = array('FOB', 'UK');
			//build a custom dropdown list of values to filter by
			echo '<select id="order_components_type" name="order_components_type">';
			echo '<option value="">' . __('Component type', 'my-custom-domain') . ' </option>';
			foreach ($results as $type) {
				$select = ($type == $selected) ? ' selected="selected"' : '';
				echo '<option value="' . $type . '"' . $select . '>' . $type . ' </option>';
			}
			echo '</select>';
		}
	}
}


// Add custom dropdown filter to WooCommerce orders list
add_action('restrict_manage_posts', 'add_company_filter_to_orders_list', 40);

function add_company_filter_to_orders_list()
{
	global $wpdb, $post_type;
	if ('shop_order' === $post_type) {

		// Define the input field ID
		$input_id = 'company_name';

// Define the placeholder text for the input field
		$placeholder = 'Search by company name';

		$user_query = get_users();
		$users_data = array();
		foreach ($user_query as $user) {
			$newObject = new stdClass();
			$newObject->id = $user->ID;
			$newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);

			$users_data[] = $newObject;
		}

		// Initialize the dropdown filter with a default value of 'All companies'
		$filter_value = 'All companies';

		// Check if the filter value is set in the URL query string
		if (isset($_GET['company_name']) && !empty($_GET['company_name'])) {
			$filter_value = $_GET['company_name'];
		}

		// Output the company name filter dropdown
		echo '<select name="company_name" id="company_name_filter">
          <option value="">Filter By companies</option>';
		foreach ($users_data as $user) {
			$selected = ($filter_value == $user->id) ? 'selected' : '';
			echo '<option value="' . $user->id . '" ' . $selected . '>' . $user->billingCompany . '</option>';
		}
		echo '</select>';
		?>
    <script>
        jQuery(document).ready(function ($) {
            $('#company_name_filter').select2({
                ajax: {
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        console.log(params)
                        return {
                            search: params.term,
                            action: 'search_company_name'
                        };
                    },
                    processResults: function (data) {
                        console.log('data ajax return:', data);
                        var options = [];
                        if (data) {
                            // data is the array of arrays, and each of them contains ID and the Label of the option
                            $.each(data, function (index, user) { // do not forget that "index" is just auto incremented value
                                options.push({id: user.id, text: user.billingCompany});
                            });

                        }
                        return {
                            results: options
                        };
                    },
                    cache: true
                },
                minimumInputLength: 3,
            });
        });
    </script>

		<?php
	}
}


// Handle search requests from the dropdown filter
add_action('wp_ajax_search_company_name', 'search_company_name');
add_action('wp_ajax_nopriv_search_company_name', 'search_company_name');

function search_company_name()
{
	// Define the meta key used to store the company name
	$meta_key = 'billing_company';
	$company_name = $_GET['search'];

	$user_query = get_users(array(
		'role' => 'dealer',
		'meta_query' => array(
			array(
				'key' => $meta_key,
				'value' => $company_name,
				'compare' => 'LIKE',
			),
		),
	));
	$users_data = array();
	foreach ($user_query as $user) {
		$newObject = new stdClass();
		$newObject->id = get_user_meta($user->ID, 'billing_company', true);
		$newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);;
		$users_data[] = $newObject;
	}

	// Create an empty array to store unique objects
	$unique_users_data = array();

// Loop through each object in $users_data array
	foreach ($users_data as $user) {
		// Check if the id property of the current object already exists in $unique_users_data
		$existing_user = array_reduce($unique_users_data, function ($carry, $item) use ($user) {
			return $carry || ($item->id == $user->id);
		}, false);

		// If the current object is not already in $unique_users_data, add it to the array
		if (!$existing_user) {
			$unique_users_data[] = $user;
		}
	}

	echo json_encode($unique_users_data);
	die;
}


// Handle search requests from the dropdown filter
add_action('wp_ajax_search_repair_company_name', 'search_repair_company_name');
add_action('wp_ajax_nopriv_search_repair_company_name', 'search_repair_company_name');

function search_repair_company_name()
{
	// Define the meta key used to store the company name
	$meta_key = 'billing_company';
	$company_name = $_GET['search'];

	$user_query = get_users(array(
		'role' => 'dealer',
		'meta_query' => array(
			array(
				'key' => $meta_key,
				'value' => $company_name,
				'compare' => 'LIKE',
			),
		),
	));
	$users_data = array();
	foreach ($user_query as $user) {
		$newObject = new stdClass();
		$newObject->id = $user->user_login;
		// get username of user
		$newObject->user_login = $user->user_login;
		$newObject->billingCompany = get_user_meta($user->ID, 'billing_company', true);;
		$users_data[] = $newObject;
	}

	// Create an empty array to store unique objects
	$unique_users_data = array();

// Loop through each object in $users_data array
	foreach ($users_data as $user) {
		// Check if the id property of the current object already exists in $unique_users_data
		$existing_user = array_reduce($unique_users_data, function ($carry, $item) use ($user) {
			return $carry || ($item->id == $user->id);
		}, false);

		// If the current object is not already in $unique_users_data, add it to the array
		if (!$existing_user) {
			$unique_users_data[] = $user;
		}
	}

	echo json_encode($unique_users_data);
	die;
}


function select_containers()
{
	global $wpdb, $post_type;
	if ('shop_order' === $post_type) {
		$rand_posts = get_posts(array(
			'post_type' => 'container',
			'posts_per_page' => 10,
		));

		echo '<select name="container" id="containers_ids">
                <option value="">Select container</option>';
		foreach ($rand_posts as $post) :
			setup_postdata($post);
			echo '<option value="' . $post->ID . '">' . get_the_title($post->ID) . '</option>';
		endforeach;
		echo '</select>';

		?>

    <script>
        jQuery(document).ready(function ($) {
            var $customPostSelect = $('#containers_ids');

            $customPostSelect.select2({
                allowClear: false,
                placeholder: 'Containers',
                ajax: {
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    dataType: 'json',
                    delay: 500,
                    data: function (params) {
                        return {
                            action: 'select_containers_name',
                            search: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (response) {
                        if (response && response.length > 0) {
                            var results = $.map(response, function (item) {
                                return {
                                    id: item.ID,
                                    text: item.title
                                };
                            });

                            return {
                                results: results
                            };
                        } else {
                            return {
                                results: []
                            };
                        }
                    }
                }
            });
        });
    </script>

		<?php
		wp_reset_postdata();
	}
}


add_action('restrict_manage_posts', 'select_containers', 20);

// Handle search requests from the dropdown filter
add_action('wp_ajax_select_containers_name', 'select_containers_ajax');
add_action('wp_ajax_nopriv_select_containers_name', 'select_containers_ajax');

function select_containers_ajax()
{
	$containers_posts = get_posts(array(
		'post_type' => 'container',
		'posts_per_page' => 10,
	));

	// Create an empty array to store unique objects
	$results = array();

	if ($containers_posts) {
		foreach ($containers_posts as $post) {
			$results[] = array(
				'ID' => $post->ID,
				'title' => $post->post_title,
			);
		}
	}

	wp_send_json($results);
}