<?php

use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Ticket\Stg_Helpdesk_Ticket;
use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

echo $order_id = $_POST['order_id'];
echo '<br>';
echo $orders_to_edit = $_POST['orders_to_edit'];
echo '<br>';
echo $customer_id = $_POST['customer_id'];
echo '<br>';
echo $order_finish = $_POST['order_finish'] / 1498765 / 33;
echo '<br>';


if (empty($order_finish) || $order_finish == 0) {
    echo 'order to edit';
    $orders_to_edit_array = get_user_meta($customer_id, 'orders_to_edit', true);
    if (empty($orders_to_edit)) {
        $orders_array = array();
    } else {
        if (is_array($orders_to_edit_array)) {
            $orders_array = $orders_to_edit_array;
        } else {
            $orders_array = array();
        }
    }
    $order = new WC_Order($order_id);
    if ($orders_to_edit == 1) {
        $orders_array[$order_id] = 'editable';
        update_user_meta($customer_id, 'orders_to_edit', $orders_array);

        $order->update_status('wc-inrevision', 'order_note');
        // order note is optional, if you want to  add a note to order

    } else {
        if (array_key_exists($order_id, $orders_array)) {
            unset($orders_array[$order_id]);
        }
        update_user_meta($customer_id, 'orders_to_edit', $orders_array);
    }

    $name = get_post_meta($order_id, 'cart_name', true);

    $single_email = 'marian93nes@gmail.com';
    $multiple_recipients = array(
        'caroline@anyhooshutter.com', 'july@anyhooshutter.com', 'tudor@lifetimeshutters.com'
    );

    $subject = 'ON-HOLD Order LF0' . $order->get_order_number() . ' - ' . $name . ' - for REVISION';
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: Matrix-LifetimeShutters <order@lifetimeshutters.com>');

    $mess = '
        Hi July, Kevin <br />
        <br />
       Please put this order ON-HOLD until revised by dealer.<br />
The revised order will be sent to you when ready.<br />
        <br />
        Kind regards. <br />
        <br />
        ';


    wp_mail($multiple_recipients, $subject, $mess, $headers);

    if ($orders_to_edit == 1) {
        createTicketOrderEdit($order_id, $customer_id);
    }
} else {

    $customer_id = get_current_user_id();
    $orders_array = get_user_meta($customer_id, 'orders_to_edit', true);
    echo $order_finish;
    if (array_key_exists($order_finish, $orders_array)) {
        echo 'order find to delete';
        createResponseTicket($order_finish);
        unset($orders_array[$order_finish]);
    } else {
        echo 'order not find to delete';
    }
    update_user_meta($customer_id, 'orders_to_edit', $orders_array);

    $order = new WC_Order($order_finish);
    $order->update_status('wc-revised', 'order_note');
    // order note is optional, if you want to  add a note to order

}
print_r($orders_array);


function createTicketOrderEdit($order_id, $customer_id)
{
// Get $order object from order ID
    $order = wc_get_order($order_id);

// Now you have access to (see above)...
    if ($order) {
        $customer_id = $order->get_customer_id();
        $subject = 'Order Revision- LF0' . $order->get_order_number() . ' - ' . get_post_meta($order_id, 'cart_name', true) . '';
        $content = 'PDF Link : <a href="/wp-content/uploads/csv-pdf/' . 'LF0' . $order->get_order_number() . '.pdf' . '" target="_blank">Open PDF</a>';

        // set ticket content
        $post['stg_ticket_subject'] = $subject;
        $post['stg_ticket_message'] = '';
        $post['stg_ticket_author'] = $customer_id;
        $post['stg_messsageId'] = '';
        $post_id = Stg_Helpdesk_Ticket::saveTicket($post, $customer_id, false, 'website');

        if ($post_id) {
            $my_post = array(
                'ID' => $post_id,
                'post_content' => $content,
            );
            wp_update_post($my_post);

            update_post_meta($post_id, 'order_id', $order_id);
            update_post_meta($post_id, 'order_ref', 'LF' . $order->get_order_number());
            update_post_meta($post_id, '_stgh_contact', $customer_id);
            update_post_meta($order_id, 'ticket_id_for_order', $post_id);

            /* Load media uploader related files. */
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');

            //Marian Putirac incercare ticket upload attachments
            // if post have url-s add new to ticket to array
            $attachmenst_urls_meta = get_post_meta($post_id, 'attachmenst_urls_meta', true);

            if (empty($attachmenst_urls_meta)) {
                $attachmenst_urls_meta = WP_CONTENT_DIR . '/uploads/csv-pdf/' . 'LF0' . $order->get_order_number() . '.pdf';
            }

            update_post_meta($post_id, 'attachmenst_urls_meta', $attachmenst_urls_meta);

            Stg_Helper_UploadFiles::handleUploadsForm($post_id);
        }
    }
}


function createResponseTicket($order_id)
{
    // Get $order object from order ID
    $order = wc_get_order($order_id);
// Now you have access to (see above)...
    if ($order) {
        $customer_id = $order->get_customer_id();

        $ticket_id_for_order = get_post_meta($order_id, 'ticket_id_for_order', true);
        if (!empty($ticket_id_for_order)) {
            $subject = 'Order Revision- LF0' . $order->get_order_number() . ' - ' . get_post_meta($order_id, 'cart_name', true) . '';
            $content = 'PDF REVISED Link : <a href="/wp-content/uploads/csv-pdf/' . 'REVISED-LF0' . $order->get_order_number() . '.pdf' . '" target="_blank">Open PDF</a>';


            $data = apply_filters('stgh_public_ticket_comment_args', array(
                'post_content' => $content,
                'post_type' => STG_HELPDESK_COMMENTS_POST_TYPE,
                'post_author' => 2,
                'post_parent' => $ticket_id_for_order,
                'post_status' => 'publish',
                'ping_status' => 'closed',
                'comment_status' => 'closed',
            ));

            $close_ticket = true;
            $parentPost = Stg_Helpdesk_Ticket::getInstance($ticket_id_for_order);
            if ($parentPost->isClosed()) {
                $close_ticket = false;
            }
            $comment_id = Stg_Helpdesk_TicketComments::addToPost($data, $ticket_id_for_order, $data['post_author']);

            $userId = $customer_id;
            $ticket_author = Stg_Helpdesk_Ticket::getAuthor($ticket_id_for_order);
            if ($ticket_author && $userId == $ticket_author->ID) {
                Stg_Helpdesk_Ticket::setInNotAnswered($ticket_id_for_order);

                stgh_add_event_ticket_to_reply($ticket_id_for_order, array('post_status_override'), array('post_status_override' => 'stgh_notanswered'), $comment_id);

            } else {
                Stg_Helpdesk_Ticket::setInAnswered($ticket_id_for_order);

                stgh_add_event_ticket_to_reply($ticket_id_for_order, array('post_status_override'), array('post_status_override' => 'stgh_answered'), $comment_id);

            }
        }
    }
}