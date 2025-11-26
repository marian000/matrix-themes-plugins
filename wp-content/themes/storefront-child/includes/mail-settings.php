<?php


if (!function_exists('send_mail_china')) {
	/**
	 * Verify if is test mode or live and get mails to send for china
	 */
	function send_mail_china()
	{
		$send_to = get_option('mail_matrix_radio');
		if ($send_to == "send_test") {
			$test_mails_matrix = get_option('test_mails_matrix');
			$multiple_recipients = explode(', ', $test_mails_matrix);
		} else {
			$china_mails_matrix = get_option('china_mails_matrix');
			$multiple_recipients = explode(', ', $china_mails_matrix);
		}
		return array_values($multiple_recipients);
	}
}


if (!function_exists('send_mail_repair')) {
	/**
	 * Verify if is test mode or live and get mails to send for china
	 */
	function send_mail_repair($warranty)
	{
		$send_to = get_option('mail_matrix_radio');
		if ($send_to == "send_test") {
			$test_mails_matrix = get_option('test_mails_matrix');
			$multiple_mails = explode(', ', $test_mails_matrix);
		} else {
			if($warranty === true){
				$warranty_mails = get_option('warranty_yes_mails_matrix');
			} else {
				$warranty_mails = get_option('warranty_no_mails_matrix');
			}
			$multiple_mails = explode(', ', $warranty_mails);
		}
		return $multiple_mails;
	}
}


if (!function_exists('send_mail_matrix')) {
	/**
	 * Verify if is test mode or live and get mails to send
	 */
	function send_mail_matrix($id_user)
	{
		$user_id = $id_user;
		$send_to = get_option('mail_matrix_radio');
		if ($send_to == "send_test") {
			$test_mails_matrix = get_option('test_mails_matrix');
			$mails = explode(', ', $test_mails_matrix);
		} else {
			$no_view_price = (get_user_meta($user_id, 'view_price', true) == 'no') ? true : false;

			// if has no access to price view then is employee
			if ($no_view_price) {
				$deler_id = get_user_meta($user_id, 'company_parent', true);
				$billing_email = get_user_meta($deler_id, 'billing_email', true);
				$user_info = get_userdata($deler_id);
				$user_mail = $user_info->user_email;
				$multiple_recipients = array(
					$user_mail, $billing_email
				);
			} else {
				$billing_email = get_user_meta($user_id, 'billing_email', true);
				$user_info = get_userdata($user_id);
				$user_mail = $user_info->user_email;
				$multiple_recipients = array(
					$user_mail, $billing_email
				);
			}
			if ($user_mail == $billing_email) {
				$mails = $user_mail;
			} else {
				$mails = $multiple_recipients;
			}
		}
		return $mails;
	}
}