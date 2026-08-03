<?php

use StghPhpImap\IncomingMail;
use StgHelpdesk\Helpers\Stg_Helpdesk_Mailbox;
use StgHelpdesk\Helpers\Stg_Helper_Email;
use StgHelpdesk\Helpers\Stg_Helper_Logger;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;

if (!function_exists('stgh_mailbox_connect')) {
	/**
	 * @return \StghPhpImap\Mailbox
	 */
	function stgh_mailbox_connect()
	{
		// get some options from settings
		$login = stgh_get_option('stg_mail_login');
		$password = stgh_get_option('mail_pwd');
		$encryption = stgh_get_option('mail_encryption', 'SSL');
		$ssl = $encryption == 'SSL' ? 1 : false;
		$tls = $encryption == 'TLS' ? 1 : false;
		$server = stgh_get_option('mail_server', false);
		$port = stgh_get_option('mail_port', false);
		$protocol = stgh_get_option('mail_protocol', 'pop3');
		$validateCert = false;
		$attachmentDir = '/home/dematrix/public_html/wp-content/uploads/mail_attachments/';

		$mailbox = Stg_Helpdesk_Mailbox::instance($server, $port, $login, $password, $attachmentDir, $ssl,
			$validateCert, $tls);

		return $mailbox->connect($protocol);
	}
}

if (!function_exists('stgh_mailbox_disconnect')) {
	function stgh_mailbox_disconnect()
	{
		// get some options from settings
		$login = stgh_get_option('stg_mail_login');
		$password = stgh_get_option('mail_pwd');
		$encryption = stgh_get_option('mail_encryption', 'SSL');
		$ssl = $encryption == 'SSL' ? 1 : 0;
		$tls = $encryption == 'TLS' ? 1 : 0;
		$server = stgh_get_option('mail_server', false);
		$port = stgh_get_option('mail_port', false);
		$protocol = stgh_get_option('mail_protocol', 'pop3');
		$validateCert = false;
		$attachmentDir = __DIR__;

		$mailbox = Stg_Helpdesk_Mailbox::instance($server, $port, $login, $password, $attachmentDir, $ssl,
			$validateCert, $tls);

		$mailbox->disconnect($protocol);
	}
}

if (!function_exists('stgh_mailbox_check_connection')) {
	/**
	 * Check connection
	 *
	 * @return bool
	 */
	function stgh_mailbox_check_connection()
	{
		try {
			stgh_mailbox_is_imap_exist();
			$connection = stgh_mailbox_connect();

			$connection->checkMailbox();
		} catch (Exception $e) {
			return $e->getMessage();
		}

		return true;
	}
}

if (!function_exists('stgh_mailbox_is_imap_exist')) {
	function stgh_mailbox_is_imap_exist()
	{
		// Check PHP compiled with IMAP and IMAP compiled with SSL.
		if (!function_exists('imap_open')) {
			throw new \StghPhpImap\Exception(" IMAP doesn't exist. PHP must be compiled with IMAP enabled");
		}
		$encryption = stgh_get_option('mail_encryption', false);
		if ($encryption == 'SSL') {

			$phpimap = stgh_parse_phpextension('imap');
			if (isset($phpimap["SSL Support"]) && strtolower($phpimap["SSL Support"]) != 'enabled') {
				throw new \StghPhpImap\Exception(" PHP IMAP must be compiled with SSl enabled");
			}
		}
	}
}

if (!function_exists('stgh_mailbox_list_mail')) {
	function stgh_mailbox_list_mail($status)
	{
		try {
			// $status = strtoupper($status);
			$connection = stgh_mailbox_connect();

			return $connection->searchMailbox($status);
		} catch (Exception $e) {
			return false;
		}
	}
}

if (!function_exists('stgh_mailbox_list_unread')) {
	/**
	 * get unread mails
	 *
	 * @return array|bool
	 */
	function stgh_mailbox_list_unread()
	{
		return stgh_mailbox_list_mail('unseen');
	}
}

if (!function_exists('stgh_mailbox_list_all')) {
	/**
	 * Get all mails
	 *
	 * @return array|bool
	 */
	function stgh_mailbox_list_all()
	{
		return stgh_mailbox_list_mail('all');
	}
}

if (!function_exists('stgh_letter_get')) {
	/**
	 * @param string $id
	 * @param bool $delete
	 * @param bool|null $markAsSeen Null = derive from $delete.
	 *
	 * @return bool|\StghPhpImap\IncomingMail
	 */
	function stgh_letter_get($id, $delete = true, $markAsSeen = null)
	{
		try {
			$connection = stgh_mailbox_connect();

			// Inspection reads must not flag the message. searchMailbox('unseen')
			// is the only queue we have, so a letter flagged \Seen by a run that
			// then died half way through would never be retried. Flagging is done
			// explicitly at the end of stgh_letter_to_post() instead.
			if ($markAsSeen === null) {
				$markAsSeen = (bool) $delete;
			}

			$mail = $connection->getMail($id, $markAsSeen);
			if ($delete) {
				$connection->deleteMail($id);
			}

			return $mail;
		} catch (Exception $e) {
			return false;
		}
	}
}

if (!function_exists('stgh_letter_fetch_failed')) {
	/**
	 * Remembers whether the last stgh_letter_save() bailed out because the letter
	 * itself could not be read (transient, worth retrying) as opposed to an
	 * outcome that was already handled and logged.
	 *
	 * stgh_letter_save() returns false for both, and it also returns false on the
	 * perfectly normal path where an email is appended as a comment to an existing
	 * ticket ($res is never assigned in that branch). Without this flag,
	 * stgh_letter_to_post() would have to re-fetch the letter over IMAP just to
	 * tell the cases apart.
	 *
	 * @param bool|null $value Null to read, bool to set.
	 *
	 * @return bool
	 */
	function stgh_letter_fetch_failed($value = null)
	{
		static $failed = false;

		if ($value !== null) {
			$failed = (bool) $value;
		}

		return $failed;
	}
}

if (!function_exists('stgh_mailbox_mark_read')) {
	/**
	 * Flag a message as read so the next cron run does not pick it up again.
	 *
	 * @param string $id
	 *
	 * @return bool
	 */
	function stgh_mailbox_mark_read($id)
	{
		try {
			stgh_mailbox_connect()->markMailAsRead($id);

			return true;
		} catch (Exception $e) {
			stgh_mailbox_logger()->log( "mark_read: failed for letterId={$id}: " . $e->getMessage() );

			return false;
		}
	}
}

if (!function_exists('stgh_letter_is_ticket')) {
	/**
	 * Check letter is ticket
	 *
	 * @param $id
	 * @param bool $checkBySubject
	 *
	 * @return bool
	 */
	function stgh_letter_is_ticket($id, $checkBySubject = true)
	{
		return 'ticket' === stgh_letter_type($id, $checkBySubject);
	}
}

if (!function_exists('stgh_letter_is_comment')) {
	/**
	 * Check letter is comment
	 *
	 * @param $id
	 * @param bool $checkBySubject
	 *
	 * @return bool
	 */
	function stgh_letter_is_comment($id, $checkBySubject = true)
	{
		return 'comment' === stgh_letter_type($id, $checkBySubject);
	}
}

if (!function_exists('stgh_letter_check_ticket_by_subject')) {
	/**
	 * @param $data
	 * @param null $address
	 *
	 * @return string
	 */
	function stgh_letter_check_ticket_by_subject($data, $address = null)
	{
		global $wpdb;
		if (is_null($address)) {
			$letter = stgh_letter_get($data, false);
			if (false != $letter) {
				$subject = $letter->subject;
				$address = $letter->fromAddress;
			} else {
				return false;
			}
		} else {
			$subject = $data;
		}

		stgh_mailbox_logger()->log( "check_ticket_by_subject: subject=\"{$subject}\"" );

		$subject = trim(preg_replace("/Re\:|re\:|RE\:|REPLY\:|Reply\:|reply\:|Forward\:|FORWARD\:|forward\:|Fwd\:|fwd\:|FWD\:/i", '', $subject));
		$user = get_user_by('email', $address);

		if (!$user) {
			return false;
		}

		$customer_order = stgh_extract_order_number_from_subject( $subject );

		if ( $customer_order === null ) {
			stgh_mailbox_logger()->log( "check_ticket_by_subject: no LF reference in subject \"{$subject}\"" );
			return false;
		}

		stgh_mailbox_logger()->log( "check_ticket_by_subject: extracted order={$customer_order} from \"{$subject}\"" );

		$lookup = stgh_find_ticket_for_order( $customer_order );

		return $lookup['ticket_id'] ?: false;
	}
}

if (!function_exists('stgh_get_hash_from_letter')) {

	function stgh_get_hash_from_letter($content)
	{
		$start = strpos($content, 'stgh_ticket_start_key:References: ');

		if ($start !== false) {
			return substr($content, $start + 34, 42);
		}

		return false;
	}
}

if (!function_exists('stgh_get_reply_from_letter')) {

	function stgh_get_reply_from_letter($content)
	{
		$start = strpos($content, 'stgh_ticket_start_key:In-Reply-To: ');

		if ($start !== false) {
			return substr($content, $start + 34, 42);
		}

		return false;
	}
}

if (!function_exists('stgh_letter_type')) {
	/**
	 * Get ticket type
	 *
	 * @param $id
	 * @param bool|true $checkBySubject
	 *
	 * @return bool|string
	 */
	function stgh_letter_type($id, $checkBySubject = true)
	{
		$letter = stgh_letter_get($id, false);

		if (false !== $letter) {
			$letterInfo = stgh_mailbox_connect()->getMailsInfo(array($id));

			if (!empty($letterInfo)) {
				$letterInfo = reset($letterInfo);

				$hasReference = isset($letterInfo->references) && isset($letterInfo->in_reply_to);
				$ticketStartHash = stgh_get_hash_from_letter($letter->textPlain);

				$hasSubject = false;

				if ($checkBySubject) {
					$hasSubject = stgh_letter_check_ticket_by_subject($letter->subject, $letter->fromAddress);
				}

				if ($ticketStartHash !== false) {
					return 'comment';
				}

				if ($hasReference || $hasSubject) {

					if ($hasSubject && !$hasReference) {
						return get_post_type($hasSubject) == STG_HELPDESK_COMMENTS_POST_TYPE ? 'comment' : 'ticket';
					}

					return 'comment';
				}

				return 'ticket';
			}
		}

		return false;
	}
}

if (!function_exists('stgh_letter_get_reference')) {
	/**
	 * Get reference
	 *
	 * @param $id
	 *
	 * @return bool|mixed
	 */
	function stgh_letter_get_reference($id)
	{
		if (stgh_letter_is_comment($id)) {
			$letterInfo = stgh_mailbox_connect()->getMailsInfo(array($id));

			$letterInfo = reset($letterInfo);

			if (empty($letterInfo->references)) {
				return false;
			}

			preg_match('/<.+?>/i', $letterInfo->references, $match);
			if (empty($match)) {
				return false;
			}

			$reference = reset($match);

			return $reference;
		}

		return false;
	}
}

if (!function_exists('stgh_letter_get_in_reply_to')) {
	/**
	 * Get in-reply-to
	 *
	 * @param $id
	 *
	 * @return bool|mixed
	 */
	function stgh_letter_get_in_reply_to($id)
	{
		if (stgh_letter_is_comment($id)) {
			$letterInfo = stgh_mailbox_connect()->getMailsInfo(array($id));

			$letterInfo = reset($letterInfo);

			if (empty($letterInfo->in_reply_to)) {
				return false;
			}

			preg_match('/<.+?>/i', $letterInfo->in_reply_to, $match);
			if (empty($match)) {
				return false;
			}

			$in_reply_to = reset($match);

			return $in_reply_to;
		}

		return false;
	}
}

if (!function_exists('stgh_letter_get_actual_body')) {
	/**
	 * Get real message body
	 *
	 * @param \StghPhpImap\IncomingMail |string $data mail id
	 *
	 * @return mixed|string
	 */
	function stgh_letter_get_actual_body($data)
	{
		if ($data instanceof \StghPhpImap\IncomingMail) {
			if (isset($data->textPlain)) {
				$body = $data->textPlain;
			} else {
				return '';
			}
		} else {
			$body = $data;
		}

		// ---- this line and those below will be ignored ----
		$cut_line = stgh_get_option('stgh_email_cut_line', '---- this line and those below will be ignored ----');
		if ($cut_line) {
			$pos = strpos($body, $cut_line);
			if ($pos)
				$body = substr($body, 0, $pos - 1);
		}
		// Remove quoted lines (lines that begin with '>').
		$body = preg_replace("/(^\w.+:\n)?(^>.*(\n|$))+/mi", '', $body);
		// Remove lines beginning with 'On' and ending with 'wrote:' (matches
		// Mac OS X Mail, Gmail).
		$body = preg_replace("/^(On).*(wrote:).*$/sm", '', $body);
		// Remove lines like '----- Original Message -----' (some other clients).
		// Also remove lines like '--- On ... wrote:' (some other clients).
		$body = preg_replace("/^---.*$/mi", '', $body);
		// Remove lines like '____________' (some other clients).
		$body = preg_replace("/^____________.*$/mi", '', $body);
		// Remove blocks of text with formats like:
		//   - 'From: Sent: To: Subject:'
		//   - 'From: To: Sent: Subject:'
		//   - 'From: Date: To: Reply-to: Subject:'
		$body = preg_replace("/From:.*^(To:).*^(Subject:).*/sm", '', $body);
		// Remove any remaining whitespace.
		$body = trim($body);

		return $body;
	}
}

// TO DO
if (!function_exists('stgh_letter_get_author')) {
	/**
	 * Identifies who sent the letter. If necessary, user registers.
	 * Returns user id
	 *
	 * @param $letter
	 *
	 * @return bool|int
	 */
	function stgh_letter_get_author($letter)
	{
		if (!$letter) {
			$log = Stg_Helper_Logger::getLogger();
			$log->log('Can\'t get letter author for letter');

			return false;
		}

		$email = $letter->fromAddress;

		$replyToArray = $letter->replyTo;

		if (is_array($replyToArray) && count($replyToArray) == 1) {
			$replyTo = current(array_keys($replyToArray));
		}

		if (!empty($replyTo))
			$email = $replyTo;

		$user = get_user_by('email', $email);
		$contactInfo = stgh_parse_letter_contact($letter);
		if (!$user) {
			// create user
			$userId = register_new_user_without_notification($email, $email);

			if ($userId instanceof \WP_Error) {
				$log = Stg_Helper_Logger::getLogger();
				$log->log("Errors while user creating " . var_export($userId->get_error_messages()));

				return false;
			}

			// set roles
			$user = new WP_User($userId);
			$user->set_role('stgh_client');

			$params = array(
				'contact_id' => $userId,
				'created_at' => $user->user_registered,
			);
			do_action('stgh_client_saved', $params);

			//set meta crm
			update_user_meta($userId, 'first_name', $contactInfo['firstName']);
			update_user_meta($userId, 'last_name', $contactInfo['lastName']);
			add_user_meta($userId, '_stgh_crm_company', $contactInfo['company']);

			//set display name
			wp_update_user(array('ID' => $userId, 'display_name' => $contactInfo['firstName'] . " " . $contactInfo['lastName']));
		} else {
			$user->add_role('stgh_client');

			if (!get_user_meta($user->ID, '_stgh_crm_company', true)) {
				update_user_meta($user->ID, '_stgh_crm_company', $contactInfo['company']);
			}

			if (!get_user_meta($user->ID, 'first_name', true)) {
				update_user_meta($user->ID, 'first_name', $contactInfo['firstName']);
			}

			if (!get_user_meta($user->ID, 'last_name', true)) {
				update_user_meta($user->ID, 'last_name', $contactInfo['lastName']);
			}
		}

		return $user->ID;
	}
}

// TO DO
if (!function_exists('stgh_letter_save_attachments')) {
	/**
	 * Save mail attacments
	 *
	 * @param $letter
	 * @param $id
	 * @param int $parent_id
	 */
	function stgh_letter_save_attachments($letter, $id, $parent_id = 0)
	{
		if (!$letter) {
			return false;
		}

		$attachments = $letter->getAttachments();

		if ($attachments && is_array($attachments)) {
			foreach ($attachments as $k => $item) {
				$filename = $item->filePath;

				$filetype = wp_check_filetype(basename($filename), null);
				$file = array(
					'name' => $item->name,
					'type' => $filetype,
					'tmp_name' => $item->filePath,
					'size' => filesize($item->filePath),
					'error' => 0,
				);

				Stg_Helper_UploadFiles::uploadFile($file, $id, $parent_id, 'wp_handle_local_upload');

				$tmp_name = $item->filePath;
				$uploads_dir = '/public_html/wp-content/uploads/mail_attachments';
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($item->name);
				// move_uploaded_file($tmp_name, "$uploads_dir/$name");
			}
		} else {
			foreach ($attachments as $k => $item) {
				$filename = $item->filePath;

				$filetype = wp_check_filetype(basename($filename), null);
				$file = array(
					'name' => $item->name,
					'type' => $filetype,
					'tmp_name' => $item->filePath,
					'size' => filesize($item->filePath),
					'error' => 0,
				);

				Stg_Helper_UploadFiles::uploadFile($file, $id, $parent_id, 'wp_handle_local_upload');

				$tmp_name = $item->filePath;
				$uploads_dir = '/public_html/wp-content/uploads/mail_attachments';
				// basename() may prevent filesystem traversal attacks;
				// further validation/sanitation of the filename may be appropriate
				$name = basename($item->name);
				//  move_uploaded_file($tmp_name, "$uploads_dir/$name");
			}
		}
	}
}

if (!function_exists('stgh_letter_get_parent_postId')) {
	/**
	 * Get ticket parent id
	 *
	 * @param $id int Letter id
	 *
	 * @return int Ticket id
	 */
	function stgh_letter_get_parent_postId($id)
	{
		if (!stgh_letter_is_comment($id)) {
			return false;
		}

		global $wpdb;
		// parent message id
		$parent_messageId = stgh_letter_get_reference($id);

		$letter = stgh_letter_get($id, false);
		$parentHash = stgh_get_hash_from_letter($letter->textPlain);
		$parentReply = stgh_get_reply_from_letter($letter->textPlain);

		if (!$parent_messageId)
			$parent_messageId = $parentHash;

		if (!$parent_messageId) {
			return false;
		}

		// Get post_id from wp_postmeta
		$query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_stgh_references' AND meta_value LIKE ('%$parent_messageId%') LIMIT 1";
		$result = $wpdb->get_results($query);

		if (!isset($result[0]->post_id) || empty($result[0]->post_id)) {
			$in_reply_to = stgh_letter_get_in_reply_to($id);

			if (!$in_reply_to)
				$in_reply_to = $parentReply;

			$query = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_stgh_references' AND meta_value LIKE ('%$in_reply_to%') LIMIT 1";
			$result = $wpdb->get_results($query);
		}

		return isset($result[0]->post_id) ? $result[0]->post_id : null;
	}
}

if (!function_exists('stgh_letter_save')) {
	/**
	 * Save ticket or comment
	 *
	 * @param $letterId
	 *
	 * @return bool|int|WP_Error
	 */
	function stgh_letter_save($letterId)
	{

		stgh_mailbox_logger()->log( "letter_save: processing letterId={$letterId}" );

		stgh_letter_fetch_failed(false);

		// Letter
		$letter = stgh_letter_get($letterId, false);

		if (!$letter) {
			stgh_letter_fetch_failed(true);

			$log = Stg_Helper_Logger::getLogger();
			$log->log('I can\'t find letter with ID=' . $letterId . ', so I skip saving ticket.');
			stgh_mailbox_logger()->log( "letter_save: letterId={$letterId} could not be read from the mailbox" );

			return false;
		}

		$autoreply = false;
		$logString = "#{$letter->fromAddress} && ID:{$letter->id}: ignoring email with ";
		if ($letter->xAutoResponseSuppress == "all" || $letter->xAutoResponseSuppress == "autoreply" || $letter->xAutoResponseSuppress == "oof") {
			$logString .= "X-Auto-Response-Suppress: {$letter->xAutoResponseSuppress} header; ";
			$autoreply = true;
		}

		if ($letter->xAutoreply == "yes") {
			$logString .= "X-AutoReply:{$letter->xAutoreply} header; ";
			$autoreply = true;
		}

		if ($letter->autoSubmitted == "auto-replied" || $letter->autoSubmitted == "auto-generated") {
			$logString .= "Auto-Submitted:{$letter->autoSubmitted} header; ";
			$autoreply = true;
		}
		if ($autoreply) {
			$logger = Stg_Helper_Logger::getLogger('autoreplies');
			$logger->log($logString);
			return false;
		}

		// Author
		$author_id = stgh_letter_get_author($letter);

		if (false === $author_id) {
			$log = Stg_Helper_Logger::getLogger();
			$log->log('I can\'t create a user, so I skip saving ticket.');

			return false;
		}
		// Letter type
		$type = stgh_letter_type($letterId);

		// Letter content
		$content = addslashes(stgh_letter_get_actual_body($letter));

		$res = false;
		if ($type == 'comment') { // saveComment
			$post = array();
			// Ticket id
			$post['ticket_id'] = stgh_letter_get_parent_postId($letterId);
			$post['stg_messsageId'] = $letter->messageId;
			$post['stgh_user_comment'] = $content;
			$post['stgh_userid_comment'] = $author_id;
			if (!$post['ticket_id']) {
				$type = 'ticket';
			} else {
				// If ticket is closed - it necessary to open
				$ticket = Stg_Helpdesk_Ticket::getInstance($post['ticket_id']);
				if ($ticket->isClosed()) {
					$ticket->open($post['ticket_id']);
				}

				$res = Stg_Helpdesk_TicketComments::saveCommentPublic($post, stgh_get_option('stg_mail_login'));

				// stgh_letter_save_attachments($letter, $res, $post['ticket_id']);

				if ($res) {
					$letterSrc = stgh_get_eml_src($post['ticket_id'], $res);
					$connection = stgh_mailbox_connect();
					$connection->saveMail($letterId, $letterSrc);
				}
			}
		}

		if ($type == 'ticket') { // saveTicket
			$post = array();
			// Marian Putirac - Subject custom edit
			$string = trim(preg_replace("/Re\:|re\:|RE\:|REPLY\:|Reply\:|reply\:|Forward\:|FORWARD\:|forward\:|Fwd\:|fwd\:|FWD\:/i", '', $letter->subject));

			$customer_order = stgh_extract_order_number_from_subject( $string );

			stgh_mailbox_logger()->log( "letter_save[ticket]: subject=\"{$letter->subject}\" extracted_order=" . ( $customer_order ?: 'null' ) );

			$lookup              = stgh_find_ticket_for_order( $customer_order );
			$order_id            = $lookup['order_id'];
			$ticket_id_for_order = $lookup['ticket_id'];

			// Prefer the canonical ticket_id_for_order meta over post_exists()
			// (which only matches identical subjects). Back-fill the meta if a
			// legacy ticket is found via post_exists so future emails consolidate.
			$post_id = (int) $ticket_id_for_order;

			if ( ! $post_id ) {
				$candidate = (int) post_exists( $string );
				if ( $candidate ) {
					$candidate_type   = get_post_type( $candidate );
					$candidate_status = get_post_status( $candidate );
					if ( $candidate_type === STG_HELPDESK_POST_TYPE && $candidate_status !== 'trash' ) {
						$post_id = $candidate;
						if ( $order_id ) {
							update_post_meta( $order_id, 'ticket_id_for_order', $post_id );
							stgh_mailbox_logger()->log( "letter_save[ticket]: back-filled ticket_id_for_order={$post_id} on order={$order_id}" );
						}
					} else {
						stgh_mailbox_logger()->log( "letter_save[ticket]: post_exists matched non-ticket id={$candidate} type={$candidate_type}, ignoring" );
					}
				}
			}

			if ($post_id) {
				//echo 'exista';

				$data = apply_filters('stgh_public_ticket_comment_args', array(
					'post_content' => $content,
					'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
					'post_author' => 2,
					'post_parent' => $post_id,
					'post_status' => 'publish',
					'ping_status' => 'closed',
					'comment_status' => 'closed',
				));

				// Marian Putirac - custom reply mail to china and Tudor

				$user_id_ticket = get_post_meta($post_id, '_stgh_contact', true);

				$detalii = 'Action required for On Hold Order - ' . stgh_format_order_ref( $customer_order );

				// A ticket without a valid _stgh_contact (legacy data, or an orphan
				// ticket created when the order was missing) used to fatal here on
				// $user_info->user_email. Notify the team instead of dying.
				$user_info = $user_id_ticket ? get_userdata($user_id_ticket) : false;

				if ($user_info) {
					$first_name = get_user_meta($user_info->ID, 'billing_first_name', true);
					$last_name = get_user_meta($user_info->ID, 'billing_last_name', true);

					$multiple_recipients = array_merge(stgh_helpdesk_alert_recipients(), array($user_info->user_email));

					$subject_c = $detalii;
					$body_c = 'Dear ' . $first_name . ' ' . $last_name . ',
                        <br><br>
                        The above order has gone on hold at the factory as they have questions they need you to answer,
                        <br><br>
                        Please log on to the Matrix Ordering System at Factory Querries Page to address the question.
                        <br><br>
                        In case of any queries please contact Customer Support on 0777 246 0159
                        <br><br>
                        Best Regards<br>
                        Matrix Ordering System';
					$headers_c = array('Content-Type: text/html; charset=UTF-8');

					wp_mail($multiple_recipients, $subject_c, $body_c, $headers_c);
				} else {
					stgh_mailbox_logger()->log( "letter_save[ticket]: ticket {$post_id} has no valid _stgh_contact (value=" . var_export($user_id_ticket, true) . "), customer not notified" );

					wp_mail(
						stgh_helpdesk_alert_recipients(),
						'Helpdesk: ticket without contact - ' . stgh_format_order_ref( $customer_order ),
						'A new email was attached to ticket #' . $post_id . ' (' . stgh_format_order_ref( $customer_order ) . ')
                        but the ticket has no valid contact user, so the customer was not notified.
                        <br><br>
                        Email subject: ' . esc_html( $letter->subject ) . '
                        <br><br>
                        Matrix Ordering System',
						array('Content-Type: text/html; charset=UTF-8')
					);
				}

				// End custom mail reply

				// Check status current post. If is closed - reopened and set work status

				$close_ticket = true;
				$parentPost = Stg_Helpdesk_Ticket::getInstance($post_id);
				if ($parentPost->isClosed()) {
					$close_ticket = false;
				}
				$comment_id = Stg_Helpdesk_TicketComments::addToPost($data, $post_id, $data['post_author']);

				// Marian Putirac answered
				// Get the current user ID
				$user_id = get_current_user_id();

// Retrieve the list of employees and dealer ID for the current user
				$users_employee = get_user_meta($user_id, 'employees', true);
				$deler_id = get_user_meta($user_id, 'company_parent', true);

// Initialize users_orders with the current user, to be used for checking ticket authorship
				$users_orders = array($user_id);

// Adjust the users_orders based on user roles and relationships
				$user = get_userdata($user_id);
				if (!empty($users_employee)) {
					$users_orders = array_merge($users_orders, $users_employee);
				}
				if (!empty($deler_id) && (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles))) {
					$dealer_employees = get_user_meta($deler_id, 'employees', true);
					$users_orders = array_merge($users_orders, $dealer_employees, array($deler_id));
				}

// Ensure unique and reverse the array to prioritize the current user
				$users_orders = array_unique($users_orders);
				$users_orders = array_reverse($users_orders);

// Determine if the current user is the ticket author
				$ticket_author = Stg_Helpdesk_Ticket::getAuthor($post_id);
				if ($ticket_author && in_array($ticket_author->ID, $users_orders)) {
					// Current user is the ticket author or related, set ticket as not answered
					Stg_Helpdesk_Ticket::setInNotAnswered($post_id);
					stgh_add_event_ticket_to_reply($post_id, array('post_status_override'), array('post_status_override' => 'stgh_notanswered'), $comment_id);
				} else {
					// Current user is not the ticket author or related, set ticket as answered
					Stg_Helpdesk_Ticket::setInAnswered($post_id);
					stgh_add_event_ticket_to_reply($post_id, array('post_status_override'), array('post_status_override' => 'stgh_answered'), $comment_id);
					update_post_meta($post_id, 'post_status_override_history', 'stgh_answered');
				}

				add_post_meta($comment_id, '_stgh_type_color', 'agent', true);
				Stg_Helper_Email::sendEventTicket('agent_reply', $comment_id);

				stgh_letter_save_attachments($letter, $comment_id, $post_id);

				$attachments = $letter->attachments;
				$letter2 = stgh_letter_get($letterId, false);

				/* Load media uploader related files. */
				require_once(ABSPATH . 'wp-admin/includes/image.php');
				require_once(ABSPATH . 'wp-admin/includes/file.php');

				//Marian Putirac incercare ticket upload attachments
				// if post have url-s add new to ticket to array
				$attachmenst_urls_meta = get_post_meta($post_id, 'attachmenst_urls_meta', true);

				if (empty($attachmenst_urls_meta)) {
					$attachmenst_urls_meta = array();
				}

				update_post_meta($post_id, 'attachmenst_urls_meta', $attachmenst_urls_meta);
			} else {

				stgh_mailbox_logger()->log( "letter_save[ticket]: no existing ticket for order=" . ( $customer_order ?: 'null' ) . ", creating new" );

				if (!empty($customer_order)) {

					$order_ref = stgh_format_order_ref( $customer_order );
					$string    = trim(preg_replace("/Re\:|re\:|RE\:|REPLY\:|Reply\:|reply\:|Forward\:|FORWARD\:|forward\:|Fwd\:|fwd\:|FWD\:/i", '', $letter->subject));

					$post['stg_ticket_subject'] = $string;
					$post['stg_ticket_message'] = $content;
					$post['stg_messsageId'] = $letter->messageId;

					// $order_id comes from the stgh_find_ticket_for_order() lookup above.
					// The previous implementation ran a second get_posts() query and put
					// the whole ticket creation inside its foreach, so an order that could
					// not be found produced zero iterations: no ticket, no log, no alert.
					if ($order_id) {

						$order = wc_get_order($order_id);

						$order_detail = array();
						$order_detail['customer_id'] = (int) get_post_meta($order_id, '_customer_user', true);
						$order_detail['customer_name'] = trim(get_post_meta($order_id, '_billing_first_name', true) . ' ' . get_post_meta($order_id, '_billing_last_name', true));

						$user_info = $order_detail['customer_id'] ? get_userdata($order_detail['customer_id']) : false;

						$post['stg_ticket_author'] = $order_detail['customer_id'] ?: $author_id;
						$res = Stg_Helpdesk_Ticket::saveTicket($post, $author_id, true, stgh_get_option('stg_mail_login'));

						if (!$res) {
							stgh_mailbox_logger()->log( "letter_save[ticket]: saveTicket FAILED for order={$order_ref} (order_id={$order_id})" );

							wp_mail(
								stgh_helpdesk_alert_recipients(),
								'Helpdesk: could not create ticket for ' . $order_ref,
								'An email for order ' . $order_ref . ' arrived but the ticket could not be created, so the message was not saved.
                        <br><br>
                        Email subject: ' . esc_html( $letter->subject ) . '<br>
                        From: ' . esc_html( $letter->fromAddress ) . '
                        <br><br>
                        Best Regards<br>
                        Matrix Ordering System',
								array('Content-Type: text/html; charset=UTF-8')
							);
						} else {

							stgh_mailbox_logger()->log( "letter_save[ticket]: created ticket={$res} for order={$order_ref} (order_id={$order_id})" );

							stgh_letter_save_attachments($letter, $res, 0);

							update_post_meta($res, 'order_id', $order_id);
							update_post_meta($res, 'order_ref', $order_ref);
							update_post_meta($res, '_stgh_contact', $post['stg_ticket_author']);
							update_post_meta($order_id, 'ticket_id_for_order', $res);

							/* Load media uploader related files. */
							require_once(ABSPATH . 'wp-admin/includes/image.php');
							require_once(ABSPATH . 'wp-admin/includes/file.php');

							//Marian Putirac incercare ticket upload attachments
							// if post have url-s add new to ticket to array
							$attachmenst_urls_meta = get_post_meta($res, 'attachmenst_urls_meta', true);

							if (empty($attachmenst_urls_meta)) {
								$attachmenst_urls_meta = array();
							}

							update_post_meta($res, 'attachmenst_urls_meta', $attachmenst_urls_meta);

							$detalii = 'Action required for On Hold Order - ' . ( $order ? 'LF0' . $order->get_order_number() : $order_ref ) . ' - ' . get_post_meta($order_id, 'cart_name', true);

							$body_c = 'Dear ' . $order_detail['customer_name'] . ',
                        <br><br>
                        The above order has gone on hold at the factory as they have questions they need you to answer,
                        <br><br>
                        Please log on to the Matrix Ordering System at Factory Querries Page  to address the question.
                        <br><br>
                        In case of any queries please contact Customer Support on 0777 246 0159
                        <br><br>
                        Best Regards<br>
                        Matrix Ordering System';
							$headers_c = array('Content-Type: text/html; charset=UTF-8');

							$multiple_recipients = stgh_helpdesk_alert_recipients();
							if ($user_info) {
								$multiple_recipients[] = $user_info->user_email;
							} else {
								stgh_mailbox_logger()->log( "letter_save[ticket]: order {$order_ref} has no customer user, only internal recipients notified" );
							}

							wp_mail($multiple_recipients, $detalii, $body_c, $headers_c);
						}
					} else {

						// The subject carries a valid LF reference but no such order
						// exists. Create the ticket anyway so the email is never lost,
						// flag it for manual triage and alert the team.
						stgh_mailbox_logger()->log( "letter_save[ticket]: order {$order_ref} not found in database, creating orphan ticket" );

						$post['stg_ticket_author'] = $author_id;
						$res = Stg_Helpdesk_Ticket::saveTicket($post, $author_id, true, stgh_get_option('stg_mail_login'));

						if ($res) {
							stgh_letter_save_attachments($letter, $res, 0);

							update_post_meta($res, 'order_ref', $order_ref);
							update_post_meta($res, '_stgh_order_missing', 1);

							stgh_mailbox_logger()->log( "letter_save[ticket]: orphan ticket={$res} created for {$order_ref}" );
						} else {
							stgh_mailbox_logger()->log( "letter_save[ticket]: saveTicket FAILED for orphan {$order_ref}" );
						}

						wp_mail(
							stgh_helpdesk_alert_recipients(),
							'Helpdesk: order ' . $order_ref . ' not found',
							'An email referencing order ' . $order_ref . ' arrived, but that order does not exist in the system.
                        <br><br>
                        Email subject: ' . esc_html( $letter->subject ) . '<br>
                        From: ' . esc_html( $letter->fromAddress ) . '<br>
                        Ticket created for manual triage: ' . ( $res ? '#' . $res : 'FAILED - the email was not saved' ) . '
                        <br><br>
                        Best Regards<br>
                        Matrix Ordering System',
							array('Content-Type: text/html; charset=UTF-8')
						);
					}

					if ($res) {
						$letterSrc = stgh_get_eml_src($res);
						$connection = stgh_mailbox_connect();
						$connection->saveMail($letterId, $letterSrc);
					}
				} else {

					stgh_mailbox_logger()->log( "letter_save[ticket]: no order ref in subject \"{$letter->subject}\", skipping" );

					$subject_c = $letter->subject;
					$body_c = 'Dear customer,
                        <br><br>
                        This mail has no order ref in subject!
                        <br><br>
                        Best Regards<br>
                        Matrix Ordering System';
					$headers_c = array('Content-Type: text/html; charset=UTF-8');

					wp_mail(stgh_helpdesk_alert_recipients(), $subject_c, $body_c, $headers_c);
				}
			}
		}

		// $res is false whenever no post was created (and on the branch that only
		// appends a comment to an existing ticket), so guard the write.
		if ($res && !empty($letter->cc) && is_array($letter->cc)) {
			$cc = implode(", ", array_keys($letter->cc));
			$cc && add_post_meta($res, '_stgh_reply_cc', $cc, true);
		}

		return $res;
	}
}

if (!function_exists('stgh_letter_to_post')) {
	/*
	 * Save letter as post
	 */
	function stgh_letter_to_post($letterId)
	{
		$id = stgh_letter_save($letterId);
		if (!$id) {
			// The letter could not be read at all: transient, leave it unread so
			// the next run retries it.
			if (stgh_letter_fetch_failed()) {
				stgh_mailbox_logger()->log( "letter_to_post: letterId={$letterId} could not be fetched, left unread for the next run" );

				return $id;
			}

			// Everything else was already handled and logged by stgh_letter_save():
			// a comment appended to an existing ticket, an autoreply that was
			// deliberately skipped, or an error that raised its own alert. Flag it,
			// otherwise every cron run would process it again.
			stgh_mailbox_logger()->log( "letter_to_post: letterId={$letterId} returned no new post (handled elsewhere), marking read" );
			stgh_mailbox_mark_read($letterId);

			return $id;
		}

		if ($id) {
			$letter = stgh_letter_get($letterId, false);
			$type = stgh_letter_type($letterId);
			$parent_id = ($type == 'comment' ? stgh_letter_get_parent_postId($letterId) : 0);
			stgh_letter_save_attachments($letter, $id, $parent_id);

			if (!(bool)stgh_get_option('mail_leave_on_server', false)
				|| (stgh_get_option('mail_protocol_visible') == 'POP3')) {
				stgh_letter_get($letterId, true);
			} else {
				// Kept on the server: flag it, or the next run would process it
				// again and duplicate the ticket.
				stgh_mailbox_mark_read($letterId);
			}

			//email
			if ($type == 'comment') {
				$commentAuthor = intval(get_post($id)->post_author);
				$ticketAuthor = intval(get_post($parent_id)->post_author);
				$ticketContact = intval(get_post_meta($parent_id, '_stgh_contact', true));;

				$commentAuthorFull = get_user_by('id', $commentAuthor);

				if (!empty($commentAuthorFull) &&
					(in_array('stgh_manager', (array)$commentAuthorFull->roles) || in_array('administrator', (array)$commentAuthorFull->roles))) {
					Stg_Helper_Email::sendEventTicket('agent_reply', $id);
				} elseif ($commentAuthor == $ticketContact || $commentAuthor == $ticketAuthor) {
					Stg_Helper_Email::sendEventTicket('client_reply', $id);
				} else {
					Stg_Helper_Email::sendEventTicket('agent_reply', $id);
					Stg_Helper_Email::sendEventTicket('client_reply', $id);
				}
			} else {
				Stg_Helper_Email::sendEventTicket('ticket_open', $id);
				Stg_Helper_Email::sendEventTicket('ticket_assign', $id);
			}
		}

		return $id;
	}
}

if (!function_exists('stgh_letters_handler')) {
	/**
	 * Letter handler
	 * Get unread letters and save it as post
	 */
	function stgh_letters_handler()
	{
		try {
			stgh_mailbox_is_imap_exist();
		} catch (Exception $e) {
			return array('status' => false, 'msg' => $e->getMessage());
		}

		$lettersIDs = stgh_mailbox_list_unread();

		if (is_array($lettersIDs)) {
			$amount = 0;
			if ($lettersIDs) {
				foreach ($lettersIDs as $letterId) {
					if (stgh_letter_to_post($letterId))
						$amount++;
				}
			}
			return array('status' => true, 'amount' => $amount);
		}

		return array('status' => false);
	}
}

if (!function_exists('stgh_parse_letter_contact')) {
	/**
	 * Email parsing
	 *
	 * @param IncomingMail $letter
	 *
	 * @return array
	 */
	function stgh_parse_letter_contact($letter)
	{
		$contact = array();
		$contact['email'] = $letter->fromAddress;

		if (empty($letter->fromName)) {
			$names = preg_replace('/@.*$/i', '', $contact['email']);
			$names = explode('.', $names);
		} else {
			$names = explode(' ', $letter->fromName);
		}

		$names = array_map('ucfirst', $names);
		$contact['firstName'] = array_shift($names);
		$contact['lastName'] = implode(' ', $names);

		$contact['company'] = strtolower($letter->fromAddress);

		preg_match('/\((.*)\)/i', $contact['lastName'], $companyMatches);

		if (!empty($companyMatches)) {
			$contact['lastName'] = trim(substr($contact['lastName'], 0, strpos($contact['lastName'], '(')));
			$contact['company'] = $companyMatches[1];
		}

		if (false !== $pos = strpos($contact['firstName'], ',')) {
			$lastName = trim(substr($contact['firstName'], 0, $pos));
			$contact['firstName'] = $contact['lastName'];
			$contact['lastName'] = $lastName;
		}

		return $contact;
	}
}

if (!function_exists('register_new_user_without_notification')) {
	function register_new_user_without_notification($user_login, $user_email)
	{
		remove_all_filters('registration_errors');

		$errors = new \WP_Error();

		$sanitized_user_login = sanitize_user($user_login);
		/**
		 * Filter the email address of a user being registered.
		 *
		 * @param string $user_email The email address of the new user.
		 *
		 * @since 2.1.0
		 *
		 */
		$user_email = apply_filters('user_registration_email', $user_email);

		// Check the username
		if ($sanitized_user_login == '') {
			$errors->add('empty_username', __('<strong>ERROR</strong>: Please enter a username.'));
		} elseif (!validate_username($user_login)) {
			$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.'));
			$sanitized_user_login = '';
		} elseif (username_exists($sanitized_user_login)) {
			$errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered. Please choose another one.'));
		}

		// Check the e-mail address
		if ($user_email == '') {
			$errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.'));
		} elseif (!is_email($user_email)) {
			$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.'));
			$user_email = '';
		} elseif (email_exists($user_email)) {
			$errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.'));
		}

		/**
		 * Fires when submitting registration form data, before the user is created.
		 *
		 * @param string $sanitized_user_login The submitted username after being sanitized.
		 * @param string $user_email The submitted email.
		 * @param WP_Error $errors Contains any errors with submitted username and email,
		 *                                       e.g., an empty field, an invalid username or email,
		 *                                       or an existing username or email.
		 *
		 * @since 2.1.0
		 *
		 */
		do_action('register_post', $sanitized_user_login, $user_email, $errors);

		/**
		 * Filter the errors encountered when a new user is being registered.
		 *
		 * The filtered WP_Error object may, for example, contain errors for an invalid
		 * or existing username or email address. A WP_Error object should always returned,
		 * but may or may not contain errors.
		 *
		 * If any errors are present in $errors, this will abort the user's registration.
		 *
		 * @param WP_Error $errors A WP_Error object containing any errors encountered
		 *                                       during registration.
		 * @param string $sanitized_user_login User's username after it has been sanitized.
		 * @param string $user_email User's email.
		 *
		 * @since 2.1.0
		 *
		 */
		$errors = apply_filters('registration_errors', $errors, $sanitized_user_login, $user_email);

		if ($errors->get_error_code())
			return $errors;

		$user_pass = wp_generate_password(12, false);
		$user_id = wp_create_user($sanitized_user_login, $user_pass, $user_email);
		if (!$user_id || is_wp_error($user_id)) {
			$errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">webmaster</a> !'), get_option('admin_email')));
			return $errors;
		}

		update_user_option($user_id, 'default_password_nag', true, true); //Set up the Password change nag.\

		return $user_id;
	}
}

if (!function_exists('stgh_init_smtp')) {
	function stgh_init_smtp($phpmailer)
	{
		$sender = Stg_Helper_Email::getSender();

		$phpmailer->IsSMTP();
		$from_email = $sender['from_email'];;
		$phpmailer->From = $from_email;
		$from_name = $sender['from_name'];;
		$phpmailer->FromName = $from_name;

		$phpmailer->SetFrom($phpmailer->From, $phpmailer->FromName);

		if (stgh_get_option('mail_sender_encryption') !== '') {
			$phpmailer->SMTPSecure = strtolower(stgh_get_option('mail_sender_encryption'));
		}

		$phpmailer->Host = stgh_get_option('mail_sender_server');
		$phpmailer->Port = stgh_get_option('mail_sender_port');

		$phpmailer->SMTPAuth = true;
		$phpmailer->Username = stgh_get_option('mail_sender_login');
		$phpmailer->Password = stgh_get_option('mail_sender_pwd');

		$phpmailer->SMTPAutoTLS = false;
	}
}

if (!function_exists('stgh_get_eml_src')) {
	function stgh_get_eml_src($ticketId, $commentId = null)
	{
		$letterPath = Stg_Helper_UploadFiles::getUploadDir($ticketId);
		if (!empty($commentId)) {
			$letterName = "ticket-{$ticketId}-{$commentId}.eml";
		} else {
			$letterName = "ticket-{$ticketId}.eml";
		}

		return $letterPath . "/" . $letterName;
	}
}



