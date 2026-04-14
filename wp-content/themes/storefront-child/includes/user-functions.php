<?php
// user inputs
add_action('show_user_profile', 'my_user_profile_edit_action');
add_action('edit_user_profile', 'my_user_profile_edit_action');
function my_user_profile_edit_action($user)
{
	include_once(get_stylesheet_directory() . '/views/user-editinfo.php');
}


//Custom column for user list

// ADDING Column with registered date for user
function new_contact_registerdate($contactmethods)
{
	$contactmethods['datecreation'] = 'Date creation';
	return $contactmethods;
}


add_filter('user_contactmethods', 'new_contact_registerdate', 10, 1);

/**
 * Handles group add/remove logic for a user.
 *
 * Reads 'selected_group' and 'group_added' from $_POST. Manages
 * group membership arrays stored as post meta on post ID 1.
 *
 * @since 1.0.0
 * @param int $user_id The user being updated.
 */
function matrix_save_group_membership( $user_id ) {
	$selected_group  = isset( $_POST['selected_group'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_group'] ) ) : '';
	$group_added_val = isset( $_POST['group_added'] ) ? sanitize_key( wp_unslash( $_POST['group_added'] ) ) : '';

	if ( $group_added_val == 'yes' ) {
		$old_group_name = get_user_meta( $user_id, 'current_selected_group', true );
		$old_group      = get_post_meta( 1, $old_group_name, true );
		$new_group      = get_post_meta( 1, $selected_group, true );
		if ( ! is_array( $old_group ) ) { $old_group = array(); }
		if ( ! is_array( $new_group ) ) { $new_group = array(); }

		if ( $old_group_name != $selected_group ) {
			$key = array_search( $user_id, $old_group );
			if ( $key !== false ) {
				unset( $old_group[ $key ] );
				update_post_meta( 1, $old_group_name, $old_group );
			}
			if ( ! in_array( $user_id, $new_group ) ) {
				$new_group[] = $user_id;
				update_post_meta( 1, $selected_group, $new_group );
			}
		} else {
			if ( ! in_array( $user_id, $new_group ) ) {
				$new_group[] = $user_id;
				update_post_meta( 1, $selected_group, $new_group );
			}
		}
	} elseif ( $group_added_val == 'no' ) {
		$old_group_name = get_user_meta( $user_id, 'current_selected_group', true );
		$old_group      = get_post_meta( 1, $old_group_name, true );
		if ( $old_group_name && is_array( $old_group ) ) {
			$key = array_search( $user_id, $old_group );
			if ( $key !== false ) {
				unset( $old_group[ $key ] );
				update_post_meta( 1, $old_group_name, $old_group );
			}
		}
	}
}

/**
 * Saves boolean/simple meta fields from $_POST for non-employee users.
 *
 * Includes group-related meta (current_selected_group, group_added)
 * and general settings (max_nr_employees, suspended, view_price, etc.).
 *
 * @since 1.0.0
 * @param int $user_id The user being updated.
 */
function matrix_save_general_user_settings( $user_id ) {
	$fields = array(
		'max_nr_employees'     => 'absint',
		'suspended_user'       => 'sanitize_key',
		'view_price'           => 'sanitize_key',
		'show_biowood'         => 'sanitize_key',
		'favorite_user'        => 'sanitize_key',
		'remember_session'     => 'sanitize_key',
		'app_access'           => 'sanitize_key',
		'current_selected_group' => 'sanitize_text_field',
		'group_added'          => 'sanitize_key',
	);
	foreach ( $fields as $meta_key => $sanitize_fn ) {
		$post_key = ( $meta_key === 'current_selected_group' ) ? 'selected_group' : $meta_key;
		if ( isset( $_POST[ $post_key ] ) ) {
			$value = ( $sanitize_fn === 'absint' )
				? absint( $_POST[ $post_key ] )
				: call_user_func( $sanitize_fn, wp_unslash( $_POST[ $post_key ] ) );
			update_user_meta( $user_id, $meta_key, $value );
		}
	}
}

/**
 * For employee/salesman roles: saves the company_parent, adds to dealer's
 * employee list, and syncs pricing from the parent dealer.
 *
 * @since 1.0.0
 * @param int      $user_id The user being updated.
 * @param WP_User  $user    The user object.
 */
function matrix_save_employee_parent_and_sync( $user_id, $user ) {
	if ( ! in_array( 'senior_salesman', $user->roles ) && ! in_array( 'salesman', $user->roles ) && ! in_array( 'employe', $user->roles ) ) {
		return;
	}

	$master_dealer_id = isset( $_POST['company_parent'] ) ? absint( $_POST['company_parent'] ) : 0;
	update_user_meta( $user_id, 'company_parent', $master_dealer_id );

	if ( $master_dealer_id > 0 ) {
		$employees = get_user_meta( $master_dealer_id, 'employees', true );
		if ( ! empty( $employees ) ) {
			if ( ! in_array( $user_id, $employees ) ) {
				$employees[] = $user_id;
			}
		} else {
			$employees = array( $user_id );
		}
		update_user_meta( $master_dealer_id, 'employees', $employees );

		// Copy all pricing from parent dealer to this employee
		matrix_copy_pricing_meta( $master_dealer_id, $user_id );
	}
}

/**
 * For dealer role: propagates pricing to all employees.
 *
 * @since 1.0.0
 * @param int      $user_id The user being updated.
 * @param WP_User  $user    The user object.
 */
function matrix_cascade_pricing_to_employees( $user_id, $user ) {
	if ( ! in_array( 'dealer', $user->roles ) ) {
		return;
	}
	$employees = get_user_meta( $user_id, 'employees', true );
	if ( ! empty( $employees ) && is_array( $employees ) ) {
		foreach ( $employees as $employe_id ) {
			matrix_copy_pricing_meta( $user_id, $employe_id );
		}
	}
}

add_action('personal_options_update', 'my_user_profile_update_action');
add_action('edit_user_profile_update', 'my_user_profile_update_action');
/**
 * Main user profile update handler (orchestrator).
 *
 * Delegates to focused functions for each responsibility:
 * group membership, general settings, pricing, employee sync, dealer cascade.
 *
 * @since 1.0.0
 * @param int $user_id The user being updated.
 */
function my_user_profile_update_action( $user_id ) {
	// Security: verify nonce to prevent CSRF
	check_admin_referer( 'update-user_' . $user_id );

	// Security: verify the current user can edit this user
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	$user = get_userdata( $user_id );

	// Group membership (all users)
	matrix_save_group_membership( $user_id );

	// show_basswood is saved for ALL users including employees
	if ( isset( $_POST['show_basswood'] ) ) {
		update_user_meta( $user_id, 'show_basswood', sanitize_key( wp_unslash( $_POST['show_basswood'] ) ) );
	}

	// General settings and pricing (non-employee only)
	$is_employee = in_array( 'senior_salesman', $user->roles )
		|| in_array( 'salesman', $user->roles )
		|| in_array( 'employe', $user->roles );

	if ( ! $is_employee ) {
		matrix_save_general_user_settings( $user_id );
		matrix_save_pricing_from_post( $user_id );
		matrix_save_per_material_pricing( $user_id );
	}

	// Employee pricing sync from parent dealer
	matrix_save_employee_parent_and_sync( $user_id, $user );

	// Dealer cascade to all employees
	matrix_cascade_pricing_to_employees( $user_id, $user );
}


// Add new user with role salesman and register fields from master dealer
add_action('user_register', 'sthc_salesman_registration_imports', 10, 1);
function sthc_salesman_registration_imports($user_id)
{
	// Get the user object.
	$user = get_userdata($user_id);

	if (in_array('senior_salesman', $user->roles) || in_array('salesman', $user->roles)) {
		$master_user = get_userdata(get_current_user_id());
		if (in_array('dealer', $master_user->roles)) {
			update_user_meta($user_id, 'company_parent', $master_user->ID);
		}

		$master_dealer_id = get_user_meta($user_id, 'company_parent', true);
		if (!empty($master_dealer_id)) {
			// Copy all pricing from parent dealer to new salesman
			matrix_copy_pricing_meta( $master_dealer_id, $user_id );

			// update billing
			update_user_meta($user_id, 'billing_first_name', get_user_meta($master_dealer_id, 'billing_first_name', true));
			update_user_meta($user_id, 'billing_last_name', get_user_meta($master_dealer_id, 'billing_last_name', true));
			update_user_meta($user_id, 'billing_company', get_user_meta($master_dealer_id, 'billing_company', true));
			update_user_meta($user_id, 'billing_address_1', get_user_meta($master_dealer_id, 'billing_address_1', true));
			update_user_meta($user_id, 'billing_address_2', get_user_meta($master_dealer_id, 'billing_address_2', true));
			update_user_meta($user_id, 'billing_city', get_user_meta($master_dealer_id, 'billing_city', true));
			update_user_meta($user_id, 'billing_postcode', get_user_meta($master_dealer_id, 'billing_postcode', true));
			update_user_meta($user_id, 'billing_country', get_user_meta($master_dealer_id, 'billing_country', true));
			update_user_meta($user_id, 'billing_state', get_user_meta($master_dealer_id, 'billing_state', true));
			update_user_meta($user_id, 'billing_phone', get_user_meta($master_dealer_id, 'billing_phone', true));

			// update Shipping
			update_user_meta($user_id, 'shipping_first_name', get_user_meta($master_dealer_id, 'shipping_first_name', true));
			update_user_meta($user_id, 'shipping_last_name', get_user_meta($master_dealer_id, 'shipping_last_name', true));
			update_user_meta($user_id, 'shipping_company', get_user_meta($master_dealer_id, 'shipping_company', true));
			update_user_meta($user_id, 'shipping_address_1', get_user_meta($master_dealer_id, 'shipping_address_1', true));
			update_user_meta($user_id, 'shipping_address_2', get_user_meta($master_dealer_id, 'shipping_address_2', true));
			update_user_meta($user_id, 'shipping_city', get_user_meta($master_dealer_id, 'shipping_city', true));
			update_user_meta($user_id, 'shipping_postcode', get_user_meta($master_dealer_id, 'shipping_postcode', true));
			update_user_meta($user_id, 'shipping_country', get_user_meta($master_dealer_id, 'shipping_country', true));
			update_user_meta($user_id, 'shipping_state', get_user_meta($master_dealer_id, 'shipping_state', true));
		}
	}
}


/**
 * Display user creation date in the user profile (user-edit.php).
 */
function my_show_user_registration_date( $user ) {
	// Ensure the current user can edit this profile
	if ( ! current_user_can( 'edit_user', $user->ID ) ) {
		return;
	}

	// Get the user_registered date from the user object
	$registered_date = $user->user_registered;

	// You can format the date/time to your liking
	// e.g., wp_date( 'F j, Y H:i', strtotime( $registered_date ) );
	$formatted_date = wp_date( 'F j, Y H:i', strtotime( $registered_date ) );
	?>
	<h3><?php esc_html_e( 'Date Creation', 'storefront-child' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="user_registered"><?php esc_html_e( 'Date Creation', 'storefront-child' ); ?></label></th>
			<td>
				<input type="text" disabled="disabled" value="<?php echo esc_attr( $formatted_date ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'This is the date the user account was created.', 'storefront-child' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'my_show_user_registration_date' );
add_action( 'edit_user_profile', 'my_show_user_registration_date' );

function my_fill_datecreation_field_via_js() {

	// Retrieve the user_id parameter from the URL and sanitize it as an integer
	$user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : get_current_user_id();

	$user_data = get_userdata($user_id);
	if ( ! $user_data ) {
		return;
	}

	// Format user_registered just like in my_show_user_columns_data
	$formatted_date = wp_date( 'F j, Y H:i', strtotime( $user_data->user_registered ) );
	?>
	<script>
        (function(){
            var datecreationField = document.getElementById('datecreation');
            if(datecreationField){
                datecreationField.value = "<?php echo esc_js( $formatted_date ); ?>";
            }
        })();
	</script>
	<?php
}
add_action( 'admin_footer-user-edit.php', 'my_fill_datecreation_field_via_js' );
add_action( 'admin_footer-profile.php', 'my_fill_datecreation_field_via_js' );


/**
 * Remove the _stgh_crm_skype field from the user contact methods.
 *
 * @param array $contactmethods The array of contact methods.
 * @return array Modified array without the _stgh_crm_skype field.
 */
function remove_stgh_crm_skype_field( $contactmethods ) {
	if ( isset( $contactmethods['_stgh_crm_skype'] ) ) {
		unset( $contactmethods['_stgh_crm_skype'] );
	}
	return $contactmethods;
}
add_filter( 'user_contactmethods', 'remove_stgh_crm_skype_field' );