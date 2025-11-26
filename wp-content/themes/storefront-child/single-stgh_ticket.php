<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

<?php

use StgHelpdesk\Ticket\Stg_Helpdesk_TicketComments;
use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;
use StgHelpdesk\Helpers\Stg_Helper_Template;

if (!defined('ABSPATH')) {
  exit;
}
global $post;

$author = get_user_by('id', $post->post_author);

if (current_user_can('administrator')) {
//  echo '<pre>';
//  print_r($author);
//  echo '</pre>';
}

?>

  <div class="stg-single-ticket">
  <div class="ava_block"><?php echo get_avatar($post->post_author, '64', get_option('avatar_default')); ?></div>
  <div class="stgh-div-block-in-row">
    <b><?php
      echo $author->data->display_name; ?></b>
    <span><?php printf(__('%s ago', STG_HELPDESK_TEXT_DOMAIN_NAME), human_time_diff(get_the_time('U', $post->ID), current_time('timestamp'))); ?></span>
    <br>

    <?php
    echo apply_filters('the_content', $post->post_content);

    echo Stg_Helper_UploadFiles::getAttachmentsBlock($post->ID); ?>
  </div>

  <?php

  if (isset($_REQUEST['stgh_message'])) { ?>
    <div class="stgh_clearleft"><?php echo $_REQUEST['stgh_message']; ?></div>
  <?php }

  $replies = Stg_Helpdesk_TicketComments::instance();
  $replies = $replies->get(array(
    'order' => 'ASC',
    'post_status' => array('publish', 'inherit'),
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => '_stgh_type_color',
        'value' => 'agent',
      ),
      array(
        'key' => '_stgh_type_color',
        'value' => 'vote',
      ),
      array(
        'key' => '_stgh_type_color',
        'value' => 'user',
      ),
      array(
        'key' => '_stgh_type_color',
        'value' => 'agent-to-other-person',
      ),
    ),
  ));
  if ($replies) { ?>
  <div class="stgh-div-block"><h3><?php _e('Replies', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></h3></div>
  <?php
  foreach ($replies as $reply) {
    $replyContent = apply_filters('the_content', $reply->post_content, $reply->ID);

    if (empty($replyContent))
      continue;

    $comment_author = get_user_by('id', $reply->post_author);

    if ($comment_author->ID != $author->ID){
//  if (current_user_can('administrator')) {
//    // must be LifetimeFactory or employee
//    echo 'comment autor 1 <pre>';
//    print_r($comment_author->data->display_name);
//    echo '</pre>';
//  }
      ?>
      <div
        class="ava_block"><img alt="" src="/wp-content/uploads/2018/09/cropped-favicon-1-270x270.png"
                               class="avatar avatar-64 photo" width="64" height="64"></div>
      <div class="stgh-div-block-in-row">
        <b><?php echo $comment_author->data->display_name; ?></b>

        <?php
      }
      else{
        // must be customer of order
//        if (current_user_can('administrator')) {
//          echo 'comment autor 2 <pre>';
//          print_r($comment_author->data->display_name);
//          echo '</pre>';
//        }
      ?>
      <div
        class="ava_block"><?php echo get_avatar($reply->post_author, '64', get_option('avatar_default')); ?></div>
      <div class="stgh-div-block-in-row">
        <b><?php echo $comment_author->data->display_name; ?></b>

        <?php
        }
        ?>
        <span><?php printf(__('%s ago', STG_HELPDESK_TEXT_DOMAIN_NAME), human_time_diff(get_the_time('U', $reply->ID), current_time('timestamp'))); ?></span>
        <br>
        <?php
        echo $replyContent;
        ?>

        <?php echo Stg_Helper_UploadFiles::getAttachmentsBlock($reply->ID); ?>
      </div>
      <?php
    }
    }

    if ($post->post_status == 'stgh_closed') {
      echo '<div class="stgh_clearleft">' . getNotificationMarkup('info', __('The ticket has been closed. ', STG_HELPDESK_TEXT_DOMAIN_NAME)) . '</div>';
    }
    /**
     * Display the reply form
     */
    ?>
    <div class="stgh-div-block"><h3><?php _e('Write a reply', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></h3></div>

    <div class="stgh-div-block stgh_width100pro">
      <form id="stgh-new-reply" class="stgh-form" enctype="multipart/form-data"
            action="<?php echo get_permalink($post->ID); ?>" method="post">
        <div id="stgh-reply-box" class="stgh-form-group stgh-textarea">
                <textarea id="stgh-reply-textarea" class="form-control" required="required"
                          placeholder="<?php _e('Type your reply here.', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>"
                          name="stgh_user_comment" rows="5"></textarea>
        </div>

        <?php Stg_Helper_Template::getTemplate('stg-upload-file-field') ?>

        <input type="hidden" value="<?php echo $post->ID; ?>" name="ticket_id">
        <button class="stgh-btn stgh-btn-default"
                data-onsubmit="<?php _e('Please Wait...', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>" value=""
                name="stgh-submit"
                type="submit"><?php _e('Reply', STG_HELPDESK_TEXT_DOMAIN_NAME); ?>
        </button>
      </form>
    </div>
  </div>

  // after submit setInNotAnswered

<?php
do_action('storefront_sidebar');
get_footer();
