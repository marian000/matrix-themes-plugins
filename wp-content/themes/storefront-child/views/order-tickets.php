<?php

use StgHelpdesk\Helpers\Stg_Helper_UploadFiles;

$order_id = $attributes['order_id'];
// $order_id = get_the_id();


// the meta_key 'diplay_on_homepage' with the meta_value 'true'
$stgh_ticket_args = array(
    'posts_per_page' => -1,
    'post_type' => 'stgh_ticket',
    'meta_key' => 'order_id',
    'meta_value' => $order_id,
    'fields' => 'ids'
);

$stgh_ticket = new WP_Query($stgh_ticket_args);

//    echo '<pre>';
//    print_r($stgh_ticket->posts);
//    echo '</pre>';
// $ticket_id = $stgh_ticket->posts[0];

foreach ($stgh_ticket->posts as $ticket_id) {


    if ($ticket_id) {

        $default = array(
            'posts_per_page' => -1,
            'orderby' => 'post_date',
            'order' => 'ASC',
            'post_type' => 'stgh_ticket_comments',
            'post_parent' => $ticket_id,
            //'post_parent' => 18401,
            'post_status' => array(
                'publish',
                'inherit'
            ),
        );

//echo '<pre>';
//print_r($ticket_id);
//echo '</pre>';

        $stgh_ticket_comments = get_posts($default);

//echo '<pre>';
//print_r($stgh_ticket_comments);
//echo '</pre>';
    }
    ?>

    <div class="stg-single-ticket">
        <p><strong><?php echo get_the_title($ticket_id); ?></strong></p>
        <?php

        if ($stgh_ticket_comments) {
            if ($ticket_id) {
                $post = get_post($ticket_id);
                $author_id = $post->post_author;
                $nicename = get_the_author_meta('nicename', $author_id);
                $email = get_the_author_meta('email', $author_id);
                $comment_author = get_post_meta($post->ID, '_stgh_comment_author_name', true);
                $name = ($comment_author != '') ? $comment_author : 'Lifetime Factory';

//                $attachments = Stg_Helper_UploadFiles::getAttachments($post->ID);
//                foreach ($attachments as $attachment_id => $attachment) {
//                    $link = add_query_arg(array('ticket-attachment' => $attachment['id']), home_url());
//
//                    echo '<li><a href="' . 'https://matrix.lifetimeshutters.com?ticket-attachment='.$attachment['id'] . '" target="_blank">' . 'https://matrix.lifetimeshutters.com?ticket-attachment='.$attachment['id'] . '</a></li>  ';
//
//                }

                ?>

                <div class="ava_block" id="<?php echo $ticket_id; ?>">
                    <img alt=""
                         src="https://secure.gravatar.com/avatar/0ac62ff9b8dbde29262ad2b62e294e34?s=64&amp;d=mm&amp;r=g"
                         srcset="https://secure.gravatar.com/avatar/0ac62ff9b8dbde29262ad2b62e294e34?s=128&amp;d=mm&amp;r=g 2x"
                         class="avatar avatar-64 photo" height="64" width="64">
                </div>
                <div class="stgh-div-block-in-row">
                    <b><?php echo $name; ?></b>
                    <span><?php echo $post->post_date; ?></span>
                    <br>
                    <br>
                    <?php echo $output = apply_filters('the_content', $post->post_content); ?>
                    <br>
                    <?php
                    $children = get_children($ticket_id);
                    $attachments = Stg_Helper_UploadFiles::getAttachments($post->ID);
                    if ($attachments) {
                        echo ' <p><strong>Attachments:</strong></p><ul>';

                        foreach ($attachments as $attachment_id => $attachment) {
                            $link = add_query_arg(array('ticket-attachment' => $attachment['id']), home_url());
                            echo '<li><a href="' . 'https://matrix.lifetimeshutters.com?ticket-attachment=' . $attachment['id'] . '" target="_blank">' . 'https://matrix.lifetimeshutters.com?ticket-attachment=' . $attachment['id'] . '</a></li>  ';

                        }
                        echo '</ul>';
                    }
                    ?>
                </div>

                <?php

            }

            $cmmt_n = 0;
            foreach ($stgh_ticket_comments as $post_comment) :
                $cmmt_n++;
                $content = apply_filters('the_content', $post_comment->post_content);
                if ($cmmt_n > 1) {
                    setup_postdata($post_comment);
                    if (!empty($content)) {
                        $author_id = $post_comment->post_author;
                        $first_name = get_the_author_meta('first_name', $author_id);
                        $last_name = get_the_author_meta('last_name', $author_id);
                        $email = get_the_author_meta('email', $author_id);
                        $comment_author = get_post_meta($post_comment->ID, '_stgh_comment_author_name', true);
                        // $name = ($comment_author != '') ? $comment_author : $first_name . ' ' . $last_name;
                        $name = $first_name . ' ' . $last_name;
                        ?>

                        <div class="ava_block" id="<?php echo $post_comment->ID; ?>">
                            <img alt=""
                                 src="https://secure.gravatar.com/avatar/0ac62ff9b8dbde29262ad2b62e294e34?s=64&amp;d=mm&amp;r=g"
                                 srcset="https://secure.gravatar.com/avatar/0ac62ff9b8dbde29262ad2b62e294e34?s=128&amp;d=mm&amp;r=g 2x"
                                 class="avatar avatar-64 photo" height="64" width="64">
                        </div>
                        <div class="stgh-div-block-in-row">
                            <b><?php echo $name; ?></b>
                            <span><?php echo $post_comment->post_date; ?></span>
                            <br>
                            <br>
                            <?php echo $content; ?>
                            <br>
                            <?php
                            $children = get_children($post_comment->ID);
                            $attachments = Stg_Helper_UploadFiles::getAttachments($post_comment->ID);
                            if ($attachments) {
                                echo ' <p><strong>Attachments:</strong></p><ul>';

                                foreach ($attachments as $attachment_id => $attachment) {
                                    $link = add_query_arg(array('ticket-attachment' => $attachment['id']), home_url());
                                    echo '<li><a href="' . 'https://matrix.lifetimeshutters.com?ticket-attachment=' . $attachment['id'] . '" target="_blank">' . 'https://matrix.lifetimeshutters.com?ticket-attachment=' . $attachment['id'] . '</a></li>  ';

                                }
                                echo '</ul>';
                            }
                            ?>
                        </div>

                        <?php
                    }
                }
            endforeach;
            wp_reset_postdata();
        }
        ?>
    </div>
    <div class="clearfix"></div>
    <br>
    <hr>


    <?php
}