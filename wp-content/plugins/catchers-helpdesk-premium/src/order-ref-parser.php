<?php
/**
 * Order reference parser & ticket lookup helpers.
 *
 * Centralized helpers for extracting LF order references from email subjects
 * and locating the canonical helpdesk ticket for a given order.
 *
 * Replaces the duplicated explode("LF", ...) logic that previously lived in
 * stgh_letter_check_ticket_by_subject() and stgh_letter_save().
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'stgh_extract_order_number_from_subject' ) ) {
	/**
	 * Extract the WooCommerce order number from a helpdesk email subject.
	 *
	 * Accepted patterns (case-insensitive, leading zeros stripped):
	 *   LF024478, LF24478, LF0024478, LF 024478  -> "24478"
	 *   LFR24478, LF R024478                     -> "24478"
	 *
	 * Repair notification emails carry BOTH references in one subject:
	 *   "Repair Order LFR24478 for Original Order LF024478"
	 * Both digits are the same WooCommerce order number — the repair post only
	 * stores it as meta `order-id-scv` (see themes/storefront-child/ajax/repair-ajax.php)
	 * and never gets an `_order_number` of its own. The optional R is therefore
	 * consumed and discarded: repairs are tracked on the original order's ticket.
	 *
	 * Returns null when no LF reference is present (caller decides fallback).
	 *
	 * @param string $subject Raw email subject (already stripped of Re:/Fwd: prefixes is fine).
	 * @return string|null Normalized order number without leading zeros, or null.
	 */
	function stgh_extract_order_number_from_subject( $subject ) {
		if ( ! is_string( $subject ) || $subject === '' ) {
			return null;
		}

		// Match LF only when not preceded by another letter (avoids false hits
		// like SELF1234, MYSELF42, etc.). Then optional whitespace, optional
		// leading zeros, optional R repair-prefix, more zeros, digits.
		if ( ! preg_match( '/(?<![A-Z])LF\s*0*R?\s*0*(\d+)/i', $subject, $m ) ) {
			return null;
		}

		$digits = ltrim( $m[1], '0' );

		// All-zero reference (LF000, LF0) is not a real order — treat as no match.
		if ( $digits === '' ) {
			return null;
		}

		return $digits;
	}
}

if ( ! function_exists( 'stgh_format_order_ref' ) ) {
	/**
	 * Format a normalized order number back to the display reference shown to users.
	 *
	 * Numeric orders (e.g. "24478") -> "LF024478"
	 *
	 * Repair references are normalized to the original order by
	 * stgh_extract_order_number_from_subject(), so there is no "LFR" output form.
	 *
	 * @param string|null $customer_order Output of stgh_extract_order_number_from_subject().
	 * @return string Empty string for invalid input.
	 */
	function stgh_format_order_ref( $customer_order ) {
		if ( $customer_order === null || $customer_order === '' ) {
			return '';
		}

		// Defensive: a caller may still hold an "R"-prefixed value produced by
		// the previous parser (stored meta, cached variable).
		$customer_order = ltrim( (string) $customer_order, 'Rr' );

		if ( $customer_order === '' ) {
			return '';
		}

		return 'LF0' . $customer_order;
	}
}

if ( ! function_exists( 'stgh_find_ticket_for_order' ) ) {
	/**
	 * Locate the canonical helpdesk ticket for a given WooCommerce order number.
	 *
	 * Looks up the shop_order post by _order_number meta, then reads the
	 * ticket_id_for_order meta written when the first email for that order
	 * arrived. Returns both IDs so callers can back-fill missing meta.
	 *
	 * @param string $order_number Normalized order number (no LF prefix, no leading zeros).
	 * @return array{order_id:int, ticket_id:int}
	 */
	function stgh_find_ticket_for_order( $order_number ) {
		global $wpdb;

		$result = array(
			'order_id'  => 0,
			'ticket_id' => 0,
		);

		if ( $order_number === null || $order_number === '' ) {
			return $result;
		}

		// Join wp_posts so a stray _order_number on some other post type (or on a
		// trashed order) cannot be mistaken for a live WooCommerce order.
		$order_id = (int) $wpdb->get_var( $wpdb->prepare(
			"SELECT pm.post_id
			   FROM {$wpdb->postmeta} pm
			   INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
			  WHERE pm.meta_key = '_order_number'
			    AND pm.meta_value = %s
			    AND p.post_type = 'shop_order'
			    AND p.post_status != 'trash'
			  LIMIT 1",
			$order_number
		) );

		if ( ! $order_id ) {
			return $result;
		}

		$result['order_id'] = $order_id;

		$ticket_id = (int) get_post_meta( $order_id, 'ticket_id_for_order', true );

		if ( $ticket_id ) {
			$status = get_post_status( $ticket_id );
			if ( $status && $status !== 'trash' && $status !== false ) {
				$result['ticket_id'] = $ticket_id;
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'stgh_helpdesk_alert_recipients' ) ) {
	/**
	 * Recipients for internal helpdesk alerts (mail that could not be routed to
	 * an order, missing order, etc.). Kept in one place so the address does not
	 * have to be hunted down across mailbox-helpers.php.
	 *
	 * @return array List of email addresses.
	 */
	function stgh_helpdesk_alert_recipients() {
		$recipients = array( 'tudor@lifetimeshutters.com' );

		return array_values( array_filter( array_map( 'trim', (array) apply_filters( 'stgh_helpdesk_alert_recipients', $recipients ) ) ) );
	}
}

if ( ! function_exists( 'stgh_mailbox_logger' ) ) {
	/**
	 * Get the mailbox processing logger.
	 *
	 * Wrapper that returns a Stg_Helper_Logger tagged "mailbox" so all incoming
	 * email decisions land in a single dedicated log file instead of being
	 * scattered via wp_mail() calls (sendLogMatrixMail debug emails).
	 *
	 * @return \StgHelpdesk\Helpers\Stg_Helper_Logger
	 */
	function stgh_mailbox_logger() {
		return \StgHelpdesk\Helpers\Stg_Helper_Logger::getLogger( 'mailbox' );
	}
}
