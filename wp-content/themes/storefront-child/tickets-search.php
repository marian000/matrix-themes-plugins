<?php
    /* Template Name: Custom Search  Ticket*/
    get_header(); ?>
    <div class="contentarea">
        <div id="content" class="content_right">
            <?php
                
                
                // use StgHelpdesk\Helpers;
                // use StgHelpdesk\Ticket;
            
            ?>
            
            <div id="stg-all-tickets-block">
                <h2><?php _e('My tickets', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></h2>
                
                <?php
                    
                    
                    global $paged, $post;
                    $myAccountId = get_option('woocommerce_myaccount_page_id');
                    
                    $stghTickets = get_query_var('my_tickets');
                    if (!empty($stghTickets)) {
                        $tmp = explode('/', $stghTickets);
                        if (isset($tmp[1]) && is_numeric($tmp[1])) {
                            $paged = (int)$tmp[1];
                            set_query_var('paged', (int)$tmp[1]);
                        }
                    }
                    
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $ppp = 20;
                    
                    //$user_tickets = Ticket\Stg_Helpdesk_Ticket::userTickets($ppp, $paged);
                    
                    $current_user_id = get_current_user_id();
                    
                    $args = array(
                        //'author' => isset($current_user->ID) ? $current_user->ID : '',
                        'author' => $current_user_id,
                        'post_type' => 'stgh_ticket',
                        'post_status' => 'any',
                        's' => $_GET['s']
                    );
                    
                    
                    $user_tickets = new WP_Query($args);
                    
                    //                    print_r($user_tickets);
                    
                    if ($user_tickets->have_posts()):
                        $columns = array(
                            'status' => array('title' => 'Status', 'callback' => 'status'),
                            'title' => array('title' => 'Title', 'callback' => 'title'),
                            'date' => array('title' => 'Date', 'callback' => 'date'),
                        );
                        ?>
                        <div class="stgh stgh-ticket-list">
                            <?php
                                if ($user_tickets->max_num_pages > 1) {
                                    echo '<nav class="prev-next-posts">';
                                    echo '<div class="prev-posts-link stgh-div-block-m10 stgh-div-block-float">';
                                    echo get_next_posts_link(__('Older tickets', STG_HELPDESK_TEXT_DOMAIN_NAME), $user_tickets->max_num_pages);
                                    echo '</div>';
                                    echo '<div class="next-posts-link stgh-div-block-m10 stgh-div-block-float">';
                                    echo get_previous_posts_link(__('Newer tickets', STG_HELPDESK_TEXT_DOMAIN_NAME));
                                    echo '</div>';
                                    echo '</nav>';
                                }
                            ?>
                            <?php if (defined('STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME') && $myAccountId == $post->ID): ?>
                                <span class="order">
                <a href="#" class="woocommerce-button button stg_order_get_help_NOORDER">
                    <?php
                        _e('Open Ticket', STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME)
                    ?>
                </a>
            </span>
                            <?php endif; ?>
                            
                            <table id="stgh_ticketlist" class="stgh-table stgh-table-hover">
                                <thead>
                                <tr>
                                    <?php foreach ($columns as $column_id => $column) {
                                        echo "<th id='stg-ticket-$column_id'>" . $column['title'] . "</th>";
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    while ($user_tickets->have_posts()):
                                        
                                        $user_tickets->the_post();
                                        
                                        echo '<tr';
                                        echo ' class=\'' . get_post_status(get_the_ID()) . '_color_row\'';
                                        echo '>';
                                        
                                        foreach ($columns as $column_id => $column) {
                                            
                                            echo '<td';
                                            
                                            if ('date' === $column_id) {
                                                echo ' data-order="' . strtotime(get_the_time()) . '"';
                                            }
                                            if ('status' === $column_id) {
                                                echo ' class="' . get_post_status(get_the_ID()) . '_color"';
                                            }
                                            echo '>';
                                            
                                            /* Display the content for this column */
                                            //Helpers\Stg_Helper_Template::getTicketsListColumnContent($column_id, $column);
                                            $adminArea = false;
                                            $callback = $column['callback'];
                                            
                                            switch ($callback) {
                                                
                                                case 'id':
                                                    echo '#' . get_the_ID();
                                                    break;
                                                
                                                case 'status':
                                                    $statusArray = stgh_get_statuses();
                                                    $postStatus = get_post_status(get_the_ID());
                                                    echo (isset($statusArray[$postStatus])) ? $statusArray[$postStatus] : $postStatus;
                                                    break;
                                                
                                                case 'title':
                                                    $link = get_permalink(get_the_ID());
                                                    
                                                    if ($adminArea === true) {
                                                        $link = add_query_arg(array('post' => get_the_ID(), 'action' => 'edit'), admin_url('post.php'));
                                                    }
                                                    
                                                    //}
                                                    ?>
                                                    <a href="<?php echo $link; ?>"><?php the_title(); ?></a><?php
                                                    
                                                    break;
                                                
                                                case 'date':
                                                    $offset = get_option('gmt_offset');
                                                    
                                                    /* Transform the offset in a W3C compliant format for datetime */
                                                    $offset = explode('.', $offset);
                                                    $hours = $offset[0];
                                                    $minutes = isset($offset[1]) ? $offset[1] : '00';
                                                    $sign = ('-' === substr($hours, 0, 1)) ? '-' : '+';
                                                    
                                                    /* Remove the sign from the hours */
                                                    if ('-' === substr($hours, 0, 1)) {
                                                        $hours = substr($hours, 1);
                                                    }
                                                    
                                                    if (5 == $minutes) {
                                                        $minutes = '30';
                                                    }
                                                    
                                                    if (1 === strlen($hours)) {
                                                        $hours = "0$hours";
                                                    }
                                                    
                                                    $offset = "$sign$hours:$minutes";
                                                    if ($adminArea === true) :
                                                        ?>
                                                        <time
                                                                datetime="<?php echo get_the_modified_date('Y-m-d\TH:i:s') . $offset ?>"><?php echo get_the_modified_date(get_option('date_format')) . ' ' . get_the_modified_date(get_option('time_format')); ?></time>
                                                    <?php
                                                    else:
                                                        ?>
                                                        <time
                                                                datetime="<?php echo get_the_date('Y-m-d\TH:i:s') . $offset ?>"><?php echo get_the_date(get_option('date_format')) . ' ' . get_the_date(get_option('time_format')); ?></time>
                                                    <?php
                                                    endif;
                                                    break;
                                                
                                                case 'taxonomy':
                                                    
                                                    $terms = get_the_terms(get_the_ID(), $column_id);
                                                    $list = array();
                                                    
                                                    if (empty($terms)) {
                                                        continue;
                                                    }
                                                    
                                                    foreach ($terms as $term) {
                                                        array_push($list, $term->name);
                                                    }
                                                    
                                                    echo implode(', ', $list);
                                                    
                                                    break;
                                                
                                                default:
                                                    
                                                    if (function_exists($callback)) {
                                                        call_user_func($callback, $column_id, get_the_ID());
                                                    }
                                                    
                                                    break;
                                            }
                                            
                                            echo '</td>';
                                            
                                        }
                                        
                                        echo '</tr>';
                                    
                                    endwhile;
                                    
                                    //wp_reset_query();
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        if ($user_tickets->max_num_pages > 1) {
                            echo '<nav class="prev-next-posts">';
                            echo '<div class="prev-posts-link stgh-div-block-m10 stgh-div-block-float">';
                            echo get_next_posts_link(__('Older tickets', STG_HELPDESK_TEXT_DOMAIN_NAME), $user_tickets->max_num_pages);
                            echo '</div>';
                            echo '<div class="next-posts-link stgh-div-block-m10 stgh-div-block-float">';
                            echo get_previous_posts_link(__('Newer tickets', STG_HELPDESK_TEXT_DOMAIN_NAME));
                            echo '</div>';
                            echo '</nav>';
                        }
                        ?>
                    <?php
                    else:
                        ?>
                        <?php echo getNotificationMarkup('info', __('Not found', STG_HELPDESK_TEXT_DOMAIN_NAME)); ?>
                        
                        <?php if (defined('STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME') && $myAccountId == $post->ID): ?>
                        <span class="order">
                <a href="#" class="woocommerce-button button stg_order_get_help_NOORDER">
                    <?php
                        _e('Open Ticket', STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME)
                    ?>
        </a>
        </span>
                    <?php endif; ?>
                    <?php endif;
                    
                    $url = stgh_get_submit_page_url();
                    if (!empty($url)) :
                        ?>
                        <a href="<?php echo esc_url($url); ?>"><?php _e('Open new ticket', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></a>
                    <?php endif; ?>
            
            </div>
        
        </div><!-- content -->
    </div><!-- contentarea -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>