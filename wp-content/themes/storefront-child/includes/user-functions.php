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

add_action('personal_options_update', 'my_user_profile_update_action');
add_action('edit_user_profile_update', 'my_user_profile_update_action');
function my_user_profile_update_action($user_id)
{
	//selected_group
	$selected_group = $_POST['selected_group'];
	if ($_POST['group_added'] == 'yes') {
		// old is user old group
		$old_group_name = get_user_meta($user_id, 'current_selected_group', true);
		$old_group = get_post_meta(1, $old_group_name, true);
		$new_group = get_post_meta(1, $selected_group, true);
		$new_group_name = $selected_group;
		if ($old_group_name != $new_group_name) {
			$key = array_search($user_id, $old_group);
			unset($old_group[$key]);
			update_post_meta(1, $old_group_name, $old_group);

			if (!in_array($user_id, $new_group)) {
				$new_group[] = $user_id;
				update_post_meta(1, $new_group_name, $new_group);
			}
		} else {
			if (!in_array($user_id, $new_group)) {
				$new_group[] = $user_id;
				update_post_meta(1, $new_group_name, $new_group);
			}
		}
	} elseif ($_POST['group_added'] == 'no') {
		$old_group_name = get_user_meta($user_id, 'current_selected_group', true);
		$old_group = get_post_meta(1, $old_group_name, true);
		if ($old_group_name) {
			$key = array_search($user_id, $old_group);
			unset($old_group[$key]);
			update_post_meta(1, $old_group_name, $old_group);
		}
	}

	// Get the user object.
	$user = get_userdata($user_id);

	if (!in_array('senior_salesman', $user->roles) || !in_array('salesman', $user->roles) || !in_array('employe', $user->roles)) {
		update_user_meta($user_id, 'max_nr_employees', $_POST['max_nr_employees']);
		update_user_meta($user_id, 'suspended_user', $_POST['suspended_user']);
		update_user_meta($user_id, 'view_price', $_POST['view_price']);
		update_user_meta($user_id, 'show_biowood', $_POST['show_biowood']);
		update_user_meta($user_id, 'favorite_user', $_POST['favorite_user']);
		update_user_meta($user_id, 'remember_session', $_POST['remember_session']);
		update_user_meta($user_id, 'app_access', $_POST['app_access']);
		update_user_meta($user_id, 'current_selected_group', $_POST['selected_group']);
		update_user_meta($user_id, 'group_added', $_POST['group_added']);
		update_user_meta($user_id, 'vat_number_custom', $_POST['vat_number_custom']);
		update_user_meta($user_id, 'email_contabil', $_POST['email_contabil']);
		update_user_meta($user_id, 'discount_custom', $_POST['discount_custom']);
		update_user_meta($user_id, 'train_price', $_POST['train_price']);
		update_user_meta($user_id, 'discount_components', $_POST['discount_components']);

		update_user_meta($user_id, 'BattenStandard', $_POST['price-BattenStandard']);
		update_user_meta($user_id, 'BattenCustom', $_POST['price-BattenCustom']);
		update_user_meta($user_id, 'Earth', $_POST['price-Earth']);
		update_user_meta($user_id, 'Ecowood', $_POST['price-Ecowood']);
		update_user_meta($user_id, 'EcowoodPlus', $_POST['price-EcowoodPlus']);
		update_user_meta($user_id, 'Green', $_POST['price-Green']);
		update_user_meta($user_id, 'Biowood', $_POST['price-Biowood']);
		update_user_meta($user_id, 'BiowoodPlus', $_POST['price-BiowoodPlus']);
		update_user_meta($user_id, 'Supreme', $_POST['price-Supreme']);
		update_user_meta($user_id, 'Solid', $_POST['price-Solid']);
		update_user_meta($user_id, 'Shaped', $_POST['price-Shaped']);
		update_user_meta($user_id, 'Tracked', $_POST['price-Tracked']);
		update_user_meta($user_id, 'TrackedByPass', $_POST['price-TrackedByPass']);
		update_user_meta($user_id, 'Arched', $_POST['price-Arched']);
		update_user_meta($user_id, 'Inside', $_POST['price-Inside']);
		update_user_meta($user_id, 'Buildout', $_POST['price-Buildout']);
		update_user_meta($user_id, 'Stainless_Steel', $_POST['price-Stainless_Steel']);
		update_user_meta($user_id, 'Hidden', $_POST['price-Hidden']);
		update_user_meta($user_id, 'Concealed_Rod', $_POST['price-Concealed_Rod']);
		update_user_meta($user_id, 'Bay_Angle', $_POST['price-Bay_Angle']);
		update_user_meta($user_id, 'Colors', $_POST['price-Colors']);
		update_user_meta($user_id, 'Ringpull', $_POST['price-Ringpull']);
		update_user_meta($user_id, 'Spare_Louvres', $_POST['price-Spare_Louvres']);
		update_user_meta($user_id, 'T_Buildout', $_POST['price-T_Buildout']);
		update_user_meta($user_id, 'B_Buildout', $_POST['price-B_Buildout']);
		update_user_meta($user_id, 'C_Buildout', $_POST['price-C_Buildout']);
		update_user_meta($user_id, 'B_typeFlexible', $_POST['price-B_typeFlexible']);
		update_user_meta($user_id, 'blackoutblind', $_POST['price-blackoutblind']);
		update_user_meta($user_id, 'T_typeAdjustable', $_POST['price-T_typeAdjustable']);
		update_user_meta($user_id, 'tposttype_blackout', $_POST['price-tposttype_blackout']);
		update_user_meta($user_id, 'bposttype_blackout', $_POST['price-bposttype_blackout']);

		update_user_meta($user_id, 'Lock', $_POST['price-Lock']);
		update_user_meta($user_id, 'P4028X', $_POST['price-P4028X']);
		update_user_meta($user_id, 'P4008T', $_POST['price-P4008T']);
		update_user_meta($user_id, 'P4008W', $_POST['price-P4008W']);
		update_user_meta($user_id, '4008T', $_POST['price-4008T']);
		update_user_meta($user_id, 'Combi', $_POST['price-Combi']);
		update_user_meta($user_id, 'French_Door', $_POST['price-French_Door']);
		update_user_meta($user_id, 'G_post', $_POST['price-G_post']);
		update_user_meta($user_id, 'Flat_Louver', $_POST['price-Flat_Louver']);
		// Tax for MikeR
		update_user_meta($user_id, 'BattenStandard_tax', $_POST['price-Batten_tax']);
		update_user_meta($user_id, 'BattenCustom_tax', $_POST['price-BattenCustom_tax']);
		update_user_meta($user_id, 'Earth_tax', $_POST['price-Earth_tax']);
		update_user_meta($user_id, 'Ecowood_tax', $_POST['price-Ecowood_tax']);
		update_user_meta($user_id, 'EcowoodPlus_tax', $_POST['price-EcowoodPlus_tax']);
		update_user_meta($user_id, 'Green_tax', $_POST['price-Green_tax']);
		update_user_meta($user_id, 'Biowood_tax', $_POST['price-Biowood_tax']);
		update_user_meta($user_id, 'BiowoodPlus_tax', $_POST['price-BiowoodPlus_tax']);
		update_user_meta($user_id, 'Supreme_tax', $_POST['price-Supreme_tax']);
		update_user_meta($user_id, 'SeaDelivery', $_POST['price-SeaDelivery']);

		// Dolar Prices
		update_user_meta($user_id, 'BattenStandard-dolar', $_POST['price-dolar-BattenStandard']);
		update_user_meta($user_id, 'BattenCustom-dolar', $_POST['price-dolar-BattenCustom']);
		update_user_meta($user_id, 'Earth-dolar', $_POST['price-dolar-Earth']);
		update_user_meta($user_id, 'Ecowood-dolar', $_POST['price-dolar-Ecowood']);
		update_user_meta($user_id, 'EcowoodPlus-dolar', $_POST['price-dolar-EcowoodPlus']);
		update_user_meta($user_id, 'Green-dolar', $_POST['price-dolar-Green']);
		update_user_meta($user_id, 'Biowood-dolar', $_POST['price-dolar-Biowood']);
		update_user_meta($user_id, 'BiowoodPlus-dolar', $_POST['price-dolar-BiowoodPlus']);
		update_user_meta($user_id, 'Supreme-dolar', $_POST['price-dolar-Supreme']);
		update_user_meta($user_id, 'Solid-dolar', $_POST['price-dolar-Solid']);
		update_user_meta($user_id, 'Shaped-dolar', $_POST['price-dolar-Shaped']);
		update_user_meta($user_id, 'Tracked-dolar', $_POST['price-dolar-Tracked']);
		update_user_meta($user_id, 'TrackedByPass-dolar', $_POST['price-dolar-TrackedByPass']);
		update_user_meta($user_id, 'Arched-dolar', $_POST['price-dolar-Arched']);
		update_user_meta($user_id, 'Inside-dolar', $_POST['price-dolar-Inside']);
		update_user_meta($user_id, 'Buildout-dolar', $_POST['price-dolar-Buildout']);
		update_user_meta($user_id, 'Stainless_Steel-dolar', $_POST['price-dolar-Stainless_Steel']);
		update_user_meta($user_id, 'Hidden-dolar', $_POST['price-dolar-Hidden']);
		update_user_meta($user_id, 'Concealed_Rod-dolar', $_POST['price-dolar-Concealed_Rod']);
		update_user_meta($user_id, 'Bay_Angle-dolar', $_POST['price-dolar-Bay_Angle']);
		update_user_meta($user_id, 'Colors-dolar', $_POST['price-dolar-Colors']);
		update_user_meta($user_id, 'Ringpull-dolar', $_POST['price-dolar-Ringpull']);
		update_user_meta($user_id, 'Spare_Louvres-dolar', $_POST['price-dolar-Spare_Louvres']);
		update_user_meta($user_id, 'T_Buildout-dolar', $_POST['price-dolar-T_Buildout']);
		update_user_meta($user_id, 'B_Buildout-dolar', $_POST['price-dolar-B_Buildout']);
		update_user_meta($user_id, 'C_Buildout-dolar', $_POST['price-dolar-C_Buildout']);
		update_user_meta($user_id, 'B_typeFlexible-dolar', $_POST['price-dolar-B_typeFlexible']);
		update_user_meta($user_id, 'blackoutblind-dolar', $_POST['price-dolar-blackoutblind']);
		update_user_meta($user_id, 'Lock-dolar', $_POST['price-dolar-Lock']);
		update_user_meta($user_id, 'P4028X-dolar', $_POST['price-dolar-P4028X']);
		update_user_meta($user_id, 'P4008T-dolar', $_POST['price-dolar-P4008T']);
		update_user_meta($user_id, 'P4008W-dolar', $_POST['price-dolar-P4008W']);
		update_user_meta($user_id, 'Combi-dolar', $_POST['price-dolar-Combi']);
		update_user_meta($user_id, 'French_Door-dolar', $_POST['price-dolar-French_Door']);
		update_user_meta($user_id, 'G_post-dolar', $_POST['price-dolar-G_post']);
		update_user_meta($user_id, 'Flat_Louver-dolar', $_POST['price-dolar-Flat_Louver']);
	}

	if (in_array('senior_salesman', $user->roles) || in_array('salesman', $user->roles) || in_array('employe', $user->roles)) {
		update_user_meta($user_id, 'company_parent', $_POST['company_parent']);

		$master_dealer_id = $_POST['company_parent'];

		$employees = get_user_meta($master_dealer_id, 'employees', true);
		if (!empty($employees)) {
			if (!in_array($user_id, $employees)) {
				$employees[] = $user_id;
			}
		} else {
			$employees = array($user_id);
		}
		update_user_meta($master_dealer_id, 'employees', $employees);

		update_user_meta($user_id, 'vat_number_custom', get_user_meta($master_dealer_id, 'vat_number_custom', true));
		update_user_meta($user_id, 'email_contabil', get_user_meta($master_dealer_id, 'email_contabil', true));
		update_user_meta($user_id, 'discount_custom', get_user_meta($master_dealer_id, 'discount_custom', true));
		update_user_meta($user_id, 'train_price', get_user_meta($master_dealer_id, 'train_price', true));
		update_user_meta($user_id, 'discount_components', get_user_meta($master_dealer_id, 'discount_components', true));

		update_user_meta($user_id, 'BattenStandard', get_user_meta($master_dealer_id, 'BattenStandard', true));
		update_user_meta($user_id, 'BattenCustom', get_user_meta($master_dealer_id, 'BattenCustom', true));
		update_user_meta($user_id, 'Earth', get_user_meta($master_dealer_id, 'Earth', true));
		update_user_meta($user_id, 'Ecowood', get_user_meta($master_dealer_id, 'Ecowood', true));
		update_user_meta($user_id, 'EcowoodPlus', get_user_meta($master_dealer_id, 'EcowoodPlus', true));
		update_user_meta($user_id, 'Green', get_user_meta($master_dealer_id, 'Green', true));
		update_user_meta($user_id, 'Biowood', get_user_meta($master_dealer_id, 'Biowood', true));
		update_user_meta($user_id, 'BiowoodPlus', get_user_meta($master_dealer_id, 'BiowoodPlus', true));
		update_user_meta($user_id, 'Supreme', get_user_meta($master_dealer_id, 'Supreme', true));
		update_user_meta($user_id, 'Solid', get_user_meta($master_dealer_id, 'Solid', true));
		update_user_meta($user_id, 'Shaped', get_user_meta($master_dealer_id, 'Shaped', true));
		update_user_meta($user_id, 'Tracked', get_user_meta($master_dealer_id, 'Tracked', true));
		update_user_meta($user_id, 'TrackedByPass', get_user_meta($master_dealer_id, 'TrackedByPass', true));
		update_user_meta($user_id, 'Arched', get_user_meta($master_dealer_id, 'Arched', true));
		update_user_meta($user_id, 'Inside', get_user_meta($master_dealer_id, 'Inside', true));
		update_user_meta($user_id, 'Buildout', get_user_meta($master_dealer_id, 'Buildout', true));
		update_user_meta($user_id, 'Stainless_Steel', get_user_meta($master_dealer_id, 'Stainless_Steel', true));
		update_user_meta($user_id, 'Hidden', get_user_meta($master_dealer_id, 'Hidden', true));
		update_user_meta($user_id, 'Concealed_Rod', get_user_meta($master_dealer_id, 'Concealed_Rod', true));
		update_user_meta($user_id, 'Bay_Angle', get_user_meta($master_dealer_id, 'Bay_Angle', true));
		update_user_meta($user_id, 'Colors', get_user_meta($master_dealer_id, 'Colors', true));
		update_user_meta($user_id, 'Ringpull', get_user_meta($master_dealer_id, 'Ringpull', true));
		update_user_meta($user_id, 'Spare_Louvres', get_user_meta($master_dealer_id, 'Spare_Louvres', true));
		update_user_meta($user_id, 'T_Buildout', get_user_meta($master_dealer_id, 'T_Buildout', true));
		update_user_meta($user_id, 'B_Buildout', get_user_meta($master_dealer_id, 'B_Buildout', true));
		update_user_meta($user_id, 'C_Buildout', get_user_meta($master_dealer_id, 'C_Buildout', true));
		update_user_meta($user_id, 'B_typeFlexible', get_user_meta($master_dealer_id, 'B_typeFlexible', true));
		update_user_meta($user_id, 'blackoutblind', get_user_meta($master_dealer_id, 'blackoutblind', true));
		update_user_meta($user_id, 'Lock', get_user_meta($master_dealer_id, 'Lock', true));
		update_user_meta($user_id, 'P4028X', get_user_meta($master_dealer_id, 'P4028X', true));
		update_user_meta($user_id, 'P4008T', get_user_meta($master_dealer_id, 'P4008T', true));
		update_user_meta($user_id, 'P4008W', get_user_meta($master_dealer_id, 'P4008W', true));
		update_user_meta($user_id, 'Combi', get_user_meta($master_dealer_id, 'Combi', true));
		update_user_meta($user_id, 'French_Door', get_user_meta($master_dealer_id, 'French_Door', true));
		update_user_meta($user_id, 'G_post', get_user_meta($master_dealer_id, 'G_post', true));
		update_user_meta($user_id, 'Flat_Louver', get_user_meta($master_dealer_id, 'Flat_Louver', true));
		// Tax for MikeR
		update_user_meta($user_id, 'BattenStandard_tax', get_user_meta($master_dealer_id, 'BattenStandard_tax', true));
		update_user_meta($user_id, 'BattenCustom_tax', get_user_meta($master_dealer_id, 'BattenCustom_tax', true));
		update_user_meta($user_id, 'Earth_tax', get_user_meta($master_dealer_id, 'Earth_tax', true));
		update_user_meta($user_id, 'Ecowood_tax', get_user_meta($master_dealer_id, 'Ecowood_tax', true));
		update_user_meta($user_id, 'EcowoodPLus_tax', get_user_meta($master_dealer_id, 'EcowoodPLus_tax', true));
		update_user_meta($user_id, 'Green_tax', get_user_meta($master_dealer_id, 'Green_tax', true));
		update_user_meta($user_id, 'Biowood_tax', get_user_meta($master_dealer_id, 'Biowood_tax', true));
		update_user_meta($user_id, 'BiowoodPlus_tax', get_user_meta($master_dealer_id, 'BiowoodPlus_tax', true));
		update_user_meta($user_id, 'Supreme_tax', get_user_meta($master_dealer_id, 'Supreme_tax', true));
		update_user_meta($user_id, 'SeaDelivery', get_user_meta($master_dealer_id, 'SeaDelivery', true));
	}

	if (in_array('dealer', $user->roles)) {

		$employees = get_user_meta($master_dealer_id, 'employees', true);
		if (!empty($employees)) {
			$master_dealer_id = $user_id;

			foreach ($employees as $employe_id) {

				update_user_meta($employe_id, 'vat_number_custom', get_user_meta($master_dealer_id, 'vat_number_custom', true));
				update_user_meta($employe_id, 'email_contabil', get_user_meta($master_dealer_id, 'email_contabil', true));
				update_user_meta($employe_id, 'discount_custom', get_user_meta($master_dealer_id, 'discount_custom', true));
				update_user_meta($employe_id, 'train_price', get_user_meta($master_dealer_id, 'train_price', true));
				update_user_meta($employe_id, 'discount_components', get_user_meta($master_dealer_id, 'discount_components', true));

				update_user_meta($employe_id, 'BattenStandard', get_user_meta($master_dealer_id, 'BattenStandard', true));
				update_user_meta($employe_id, 'BattenCustom', get_user_meta($master_dealer_id, 'BattenCustom', true));
				update_user_meta($employe_id, 'Earth', get_user_meta($master_dealer_id, 'Earth', true));
				update_user_meta($employe_id, 'Ecowood', get_user_meta($master_dealer_id, 'Ecowood', true));
				update_user_meta($employe_id, 'EcowoodPlus', get_user_meta($master_dealer_id, 'EcowoodPlus', true));
				update_user_meta($employe_id, 'Green', get_user_meta($master_dealer_id, 'Green', true));
				update_user_meta($employe_id, 'Biowood', get_user_meta($master_dealer_id, 'Biowood', true));
				update_user_meta($employe_id, 'BiowoodPlus', get_user_meta($master_dealer_id, 'BiowoodPlus', true));
				update_user_meta($employe_id, 'Supreme', get_user_meta($master_dealer_id, 'Supreme', true));
				update_user_meta($employe_id, 'Solid', get_user_meta($master_dealer_id, 'Solid', true));
				update_user_meta($employe_id, 'Shaped', get_user_meta($master_dealer_id, 'Shaped', true));
				update_user_meta($employe_id, 'Tracked', get_user_meta($master_dealer_id, 'Tracked', true));
				update_user_meta($employe_id, 'TrackedByPass', get_user_meta($master_dealer_id, 'TrackedByPass', true));
				update_user_meta($employe_id, 'Arched', get_user_meta($master_dealer_id, 'Arched', true));
				update_user_meta($employe_id, 'Inside', get_user_meta($master_dealer_id, 'Inside', true));
				update_user_meta($employe_id, 'Buildout', get_user_meta($master_dealer_id, 'Buildout', true));
				update_user_meta($employe_id, 'Stainless_Steel', get_user_meta($master_dealer_id, 'Stainless_Steel', true));
				update_user_meta($employe_id, 'Hidden', get_user_meta($master_dealer_id, 'Hidden', true));
				update_user_meta($employe_id, 'Concealed_Rod', get_user_meta($master_dealer_id, 'Concealed_Rod', true));
				update_user_meta($employe_id, 'Bay_Angle', get_user_meta($master_dealer_id, 'Bay_Angle', true));
				update_user_meta($employe_id, 'Colors', get_user_meta($master_dealer_id, 'Colors', true));
				update_user_meta($employe_id, 'Ringpull', get_user_meta($master_dealer_id, 'Ringpull', true));
				update_user_meta($employe_id, 'Spare_Louvres', get_user_meta($master_dealer_id, 'Spare_Louvres', true));
				update_user_meta($employe_id, 'T_Buildout', get_user_meta($master_dealer_id, 'T_Buildout', true));
				update_user_meta($employe_id, 'B_Buildout', get_user_meta($master_dealer_id, 'B_Buildout', true));
				update_user_meta($employe_id, 'C_Buildout', get_user_meta($master_dealer_id, 'C_Buildout', true));
				update_user_meta($employe_id, 'B_typeFlexible', get_user_meta($master_dealer_id, 'B_typeFlexible', true));
				update_user_meta($employe_id, 'blackoutblind', get_user_meta($master_dealer_id, 'blackoutblind', true));
				update_user_meta($employe_id, 'Lock', get_user_meta($master_dealer_id, 'Lock', true));
				update_user_meta($employe_id, 'P4028X', get_user_meta($master_dealer_id, 'P4028X', true));
				update_user_meta($employe_id, 'P4008T', get_user_meta($master_dealer_id, 'P4008T', true));
				update_user_meta($employe_id, 'P4008W', get_user_meta($master_dealer_id, 'P4008W', true));
				update_user_meta($employe_id, 'Combi', get_user_meta($master_dealer_id, 'Combi', true));
				update_user_meta($employe_id, 'French_Door', get_user_meta($master_dealer_id, 'French_Door', true));
				update_user_meta($employe_id, 'G_post', get_user_meta($master_dealer_id, 'G_post', true));
				update_user_meta($employe_id, 'Flat_Louver', get_user_meta($master_dealer_id, 'Flat_Louver', true));
				// Tax for MikeR
				update_user_meta($employe_id, 'BattenStandard_tax', get_user_meta($master_dealer_id, 'BattenStandard_tax', true));
				update_user_meta($employe_id, 'BattenCustom_tax', get_user_meta($master_dealer_id, 'BattenCustom_tax', true));
				update_user_meta($employe_id, 'Earth_tax', get_user_meta($master_dealer_id, 'Earth_tax', true));
				update_user_meta($employe_id, 'Ecowood_tax', get_user_meta($master_dealer_id, 'Ecowood_tax', true));
				update_user_meta($employe_id, 'EcowoodPlus_tax', get_user_meta($master_dealer_id, 'EcowoodPlus_tax', true));
				update_user_meta($employe_id, 'Green_tax', get_user_meta($master_dealer_id, 'Green_tax', true));
				update_user_meta($employe_id, 'Biowood_tax', get_user_meta($master_dealer_id, 'Biowood_tax', true));
				update_user_meta($employe_id, 'BiowoodPlus_tax', get_user_meta($master_dealer_id, 'BiowoodPlus_tax', true));
				update_user_meta($employe_id, 'Supreme_tax', get_user_meta($master_dealer_id, 'Supreme_tax', true));
				update_user_meta($employe_id, 'SeaDelivery', get_user_meta($master_dealer_id, 'SeaDelivery', true));
			}
		}
	}
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
			update_user_meta($user_id, 'vat_number_custom', get_user_meta($master_dealer_id, 'vat_number_custom', true));
			update_user_meta($user_id, 'email_contabil', get_user_meta($master_dealer_id, 'email_contabil', true));
			update_user_meta($user_id, 'discount_custom', get_user_meta($master_dealer_id, 'discount_custom', true));
			update_user_meta($user_id, 'train_price', get_user_meta($master_dealer_id, 'train_price', true));
			update_user_meta($user_id, 'discount_components', get_user_meta($master_dealer_id, 'discount_components', true));

			update_user_meta($user_id, 'BattenStandard', get_user_meta($master_dealer_id, 'BattenStandard', true));
			update_user_meta($user_id, 'BattenCustom', get_user_meta($master_dealer_id, 'BattenCustom', true));
			update_user_meta($user_id, 'Earth', get_user_meta($master_dealer_id, 'Earth', true));
			update_user_meta($user_id, 'Ecowood', get_user_meta($master_dealer_id, 'Ecowood', true));
			update_user_meta($user_id, 'EcowoodPlus', get_user_meta($master_dealer_id, 'EcowoodPlus', true));
			update_user_meta($user_id, 'Green', get_user_meta($master_dealer_id, 'Green', true));
			update_user_meta($user_id, 'Biowood', get_user_meta($master_dealer_id, 'Biowood', true));
			update_user_meta($user_id, 'BiowoodPlus', get_user_meta($master_dealer_id, 'BiowoodPlus', true));
			update_user_meta($user_id, 'Supreme', get_user_meta($master_dealer_id, 'Supreme', true));
			update_user_meta($user_id, 'Solid', get_user_meta($master_dealer_id, 'Solid', true));
			update_user_meta($user_id, 'Shaped', get_user_meta($master_dealer_id, 'Shaped', true));
			update_user_meta($user_id, 'Tracked', get_user_meta($master_dealer_id, 'Tracked', true));
			update_user_meta($user_id, 'Buildout', get_user_meta($master_dealer_id, 'Buildout', true));
			update_user_meta($user_id, 'Stainless_Steel', get_user_meta($master_dealer_id, 'Stainless_Steel', true));
			update_user_meta($user_id, 'Hidden', get_user_meta($master_dealer_id, 'Hidden', true));
			update_user_meta($user_id, 'Concealed_Rod', get_user_meta($master_dealer_id, 'Concealed_Rod', true));
			update_user_meta($user_id, 'Bay_Angle', get_user_meta($master_dealer_id, 'Bay_Angle', true));
			update_user_meta($user_id, 'Colors', get_user_meta($master_dealer_id, 'Colors', true));
			update_user_meta($user_id, 'Ringpull', get_user_meta($master_dealer_id, 'Ringpull', true));
			update_user_meta($user_id, 'Spare_Louvres', get_user_meta($master_dealer_id, 'Spare_Louvres', true));
			update_user_meta($user_id, 'T_Buildout', get_user_meta($master_dealer_id, 'T_Buildout', true));
			update_user_meta($user_id, 'B_Buildout', get_user_meta($master_dealer_id, 'B_Buildout', true));
			update_user_meta($user_id, 'C_Buildout', get_user_meta($master_dealer_id, 'C_Buildout', true));
			update_user_meta($user_id, 'B_typeFlexible', get_user_meta($master_dealer_id, 'B_typeFlexible', true));
			update_user_meta($user_id, 'blackoutblind', get_user_meta($master_dealer_id, 'blackoutblind', true));
			update_user_meta($user_id, 'Lock', get_user_meta($master_dealer_id, 'Lock', true));
			update_user_meta($user_id, 'P4028X', get_user_meta($master_dealer_id, 'P4028X', true));
			update_user_meta($user_id, 'P4008T', get_user_meta($master_dealer_id, 'P4008T', true));
			update_user_meta($user_id, 'P4008W', get_user_meta($master_dealer_id, 'P4008W', true));
			update_user_meta($user_id, 'Combi', get_user_meta($master_dealer_id, 'Combi', true));
			update_user_meta($user_id, 'French_Door', get_user_meta($master_dealer_id, 'French_Door', true));
			update_user_meta($user_id, 'G_post', get_user_meta($master_dealer_id, 'G_post', true));
			update_user_meta($user_id, 'Flat_Louver', get_user_meta($master_dealer_id, 'Flat_Louver', true));
			// Tax for MikeR
			update_user_meta($user_id, 'BattenStandard_tax', get_user_meta($master_dealer_id, 'BattenStandard_tax', true));
			update_user_meta($user_id, 'BattenCustom_tax', get_user_meta($master_dealer_id, 'BattenCustom_tax', true));
			update_user_meta($user_id, 'Earth_tax', get_user_meta($master_dealer_id, 'Earth_tax', true));
			update_user_meta($user_id, 'Ecowood_tax', get_user_meta($master_dealer_id, 'Ecowood_tax', true));
			update_user_meta($user_id, 'EcowoodPlus_tax', get_user_meta($master_dealer_id, 'EcowoodPlus_tax', true));
			update_user_meta($user_id, 'Green_tax', get_user_meta($master_dealer_id, 'Green_tax', true));
			update_user_meta($user_id, 'Biowood_tax', get_user_meta($master_dealer_id, 'Biowood_tax', true));
			update_user_meta($user_id, 'BiowoodPlus_tax', get_user_meta($master_dealer_id, 'BiowoodPlus_tax', true));
			update_user_meta($user_id, 'Supreme_tax', get_user_meta($master_dealer_id, 'Supreme_tax', true));
			update_user_meta($user_id, 'SeaDelivery', get_user_meta($master_dealer_id, 'SeaDelivery', true));

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
	// e.g., date( 'F j, Y H:i', strtotime( $registered_date ) );
	$formatted_date = date( 'F j, Y H:i', strtotime( $registered_date ) );
	?>
	<h3><?php esc_html_e( 'Date Creation', 'textdomain' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="user_registered"><?php esc_html_e( 'Date Creation', 'textdomain' ); ?></label></th>
			<td>
				<input type="text" disabled="disabled" value="<?php echo esc_attr( $formatted_date ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'This is the date the user account was created.', 'textdomain' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'my_show_user_registration_date' );
add_action( 'edit_user_profile', 'my_show_user_registration_date' );

function my_fill_datecreation_field_via_js( $user ) {

	// Retrieve the user_id parameter from the URL and sanitize it as an integer
	$user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;

	$user = get_userdata($user_id);

	// Format user_registered just like in my_show_user_columns_data
	$formatted_date = date( 'F j, Y H:i', strtotime( $user->user_registered ) );
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