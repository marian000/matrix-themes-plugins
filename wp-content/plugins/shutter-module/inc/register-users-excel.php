<?php

// Create a shortcode to display file upload form
function display_file_upload_form()
{
	ob_start();
	?>
    <form id="file-upload-form" enctype="multipart/form-data">
        <label for="upload-file">Choose a CSV file:</label>
        <input type="file" id="upload-file" name="upload-file" accept=".csv">
        <input type="submit" value="Upload">
    </form>
    <div id="upload-status"></div>
	<?php
	return ob_get_clean();
}


add_shortcode('file_upload_form', 'display_file_upload_form');

// Handle the parsed Excel data received via AJAX
// Register the AJAX action for processing the CSV file
// Register the AJAX action for processing the CSV file for logged-in users
// Register the AJAX action for processing the CSV file for logged-in users
add_action('wp_ajax_process_csv_file', 'process_csv_file');

/**
 * Function to process the uploaded CSV file and insert/update user details.
 */
function process_csv_file()
{
	// Check if the current user has permissions
	if (!current_user_can('manage_options')) {
		wp_send_json_error(array('message' => 'You do not have permission to upload files.'), 403);
		return;
	}

	// Verify the file upload
	if (!isset($_FILES['upload-file']) || $_FILES['upload-file']['error'] != 0) {
		wp_send_json_error(array('message' => 'File upload failed. Error: ' . $_FILES['upload-file']['error']), 400);
		return;
	}

	// Get the uploaded file
	$file = $_FILES['upload-file']['tmp_name'];

	// Check if the file is readable
	if (!is_readable($file)) {
		wp_send_json_error(array('message' => 'Uploaded file is not readable.'), 400);
		return;
	}

	// Open the file for reading
	if (($handle = fopen($file, 'r')) === FALSE) {
		wp_send_json_error(array('message' => 'Failed to open the uploaded file.'), 400);
		return;
	}

	// Read the CSV header
	$header = fgetcsv($handle, 1000, ',');
	if ($header === false) {
		wp_send_json_error(array('message' => 'Failed to read the CSV file header.'), 400);
		fclose($handle);
		return;
	}

	// Loop through each row and insert/update users
	while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
		// Map the row to variables using the provided header structure
		list(
		  $lead_recorded, $username, $password, $ecowood, $ecowood_plus, $biowood, $biowood_plus,
		  $basswood, $earth, $show_biowood, $show_basswood, $add_to_group, $group_name, $user_scanner_id, $first_name,
		  $last_name, $job_title, $company_name, $email_address, $mobile_no, $telephone_no,
		  $street_address_1, $street_address_2, $street_address_3, $postal_town, $county_province,
		  $post_code, $country
		  ) = $row;

		// Ensure a valid email is provided
		if (empty($email_address) || !is_email($email_address)) {
			continue; // Skip invalid rows
		}

		// Check if the user already exists
		$user = get_user_by('email', $email_address);
		if (!$user) {
			// Create a new user if not exists
			$user_id = wp_insert_user(array(
			  'user_login' => $username,
			  'user_pass' => !empty($password) ? $password : wp_generate_password(),
			  'user_email' => $email_address,
			  'display_name' => $first_name . ' ' . $last_name,
			  'first_name' => $first_name,
			  'last_name' => $last_name,
			  'role' => 'helpdesk_client', // Adjust role as needed
			));

			if (is_wp_error($user_id)) {
				// Log or handle user creation errors
				continue;
			}
		} else {
			$user_id = $user->ID; // Get the user ID of the existing user
		}

		// Update custom user meta data
		update_user_meta($user_id, 'job_title', $job_title);
		update_user_meta($user_id, 'company_name', $company_name);
		update_user_meta($user_id, 'user_scanner_id', $user_scanner_id);
		// Update WooCommerce billing and shipping information for the user
// Billing information
		update_user_meta($user_id, 'billing_phone', $mobile_no);
		update_user_meta($user_id, 'billing_company', $company_name);
		update_user_meta($user_id, 'billing_address_1', $street_address_1);
		update_user_meta($user_id, 'billing_address_2', $street_address_2);
		update_user_meta($user_id, 'billing_address_3', $street_address_3); // If a third line is needed, otherwise omit
		update_user_meta($user_id, 'billing_city', $postal_town);
		update_user_meta($user_id, 'billing_state', $county_province);
		update_user_meta($user_id, 'billing_postcode', $post_code);
		update_user_meta($user_id, 'billing_country', $country);

// Shipping information (if you want to set the same details as billing)
		update_user_meta($user_id, 'shipping_phone', $mobile_no); // Optional, usually not needed for shipping
		update_user_meta($user_id, 'shipping_company', $company_name);
		update_user_meta($user_id, 'shipping_address_1', $street_address_1);
		update_user_meta($user_id, 'shipping_address_2', $street_address_2);
		update_user_meta($user_id, 'shipping_address_3', $street_address_3); // If a third line is needed, otherwise omit
		update_user_meta($user_id, 'shipping_city', $postal_town);
		update_user_meta($user_id, 'shipping_state', $county_province);
		update_user_meta($user_id, 'shipping_postcode', $post_code);
		update_user_meta($user_id, 'shipping_country', $country);

		// Update additional information for biowood and groups
		update_user_meta($user_id, 'show_biowood', $show_biowood);
		update_user_meta($user_id, 'show_basswood', $show_basswood);
		update_user_meta($user_id, 'add_to_group', $add_to_group);
		update_user_meta($user_id, 'group_name', $group_name);
		update_user_meta($user_id, 'group_added', 'yes');
		// Assign the user to the group
		manage_user_group($user_id, $group_name, strtolower($add_to_group) == 'yes');

		// Assign material data as user meta
		update_user_meta($user_id, 'ecowood', $ecowood);
		update_user_meta($user_id, 'ecowood_plus', $ecowood_plus);
		update_user_meta($user_id, 'biowood', $biowood);
		update_user_meta($user_id, 'biowood_plus', $biowood_plus);
		update_user_meta($user_id, 'basswood', $basswood);
		update_user_meta($user_id, 'earth', $earth);
	}

	// Close the file after processing
	fclose($handle);

	// Send a success response
	wp_send_json_success(array('message' => 'Users processed successfully.'));
}


/**
 * Manage the user's group based on the provided parameters.
 *
 * @param int $user_id The ID of the user.
 * @param string $selected_group The new group to assign the user to.
 * @param bool $add_to_group Indicates whether the user should be added or removed from the group.
 */
function manage_user_group($user_id, $selected_group, $add_to_group) {
	// Fetch the user's old group from user meta
	$old_group_name = get_user_meta($user_id, 'current_selected_group', true);

	// If the user should be added to a group
	if ($add_to_group) {
		$old_group = $old_group_name ? get_post_meta(1, $old_group_name, true) : array();
		$new_group = get_post_meta(1, $selected_group, true) ?: array();

		if ($old_group_name && $old_group_name != $selected_group) {
			// Remove user from the old group if they're switching to a new group
			$key = array_search($user_id, $old_group);
			if ($key !== false) {
				unset($old_group[$key]);
				update_post_meta(1, $old_group_name, $old_group);
			}
		}

		// Add user to the new group if not already there
		if (!in_array($user_id, $new_group)) {
			$new_group[] = $user_id;
			update_post_meta(1, $selected_group, $new_group);
		}

		// Update the user's current group meta
		update_user_meta($user_id, 'current_selected_group', $selected_group);

	} else {
		// If the user should be removed from their current group
		if ($old_group_name) {
			$old_group = get_post_meta(1, $old_group_name, true);
			$key = array_search($user_id, $old_group);
			if ($key !== false) {
				unset($old_group[$key]);
				update_post_meta(1, $old_group_name, $old_group);
			}

			// Clear the current group meta for the user
			delete_user_meta($user_id, 'current_selected_group');
		}
	}
}