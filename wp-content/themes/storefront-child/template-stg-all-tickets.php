<?php

/**
 * The template for displaying full width pages.
 *
 * Template Name: my-tyckets template
 *
 */

use StgHelpdesk\Helpers;
use StgHelpdesk\Ticket;

get_header();

$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

?>

  <div id="stg-all-tickets-block blablabla">
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

		$user_tickets = Ticket\Stg_Helpdesk_Ticket::userTickets($ppp, $paged);

		$user_id = get_current_user_id();
		$users_employee = get_user_meta($user_id, 'employees', true);

		$user = get_userdata($user_id);
		// get user company_parent id from meta
		$deler_id = get_user_meta($user_id, 'company_parent', true);

		if (!empty($users_employee)) {
			$users_orders = $users_employee;
			$users_orders[] = $user_id;
			$users_orders = array_reverse($users_orders);
		} else {
			// initiate users_orders array with logged user id
			$users_orders = array($user_id);
			if (!empty($deler_id)) {
				// if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders
				if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
//					$users_employee = get_user_meta($deler_id, 'employees', true);
//					$users_orders[] = $deler_id;
					$users_orders = array();
					$users_orders = get_user_meta($deler_id, 'employees', true);
					$users_orders[] = $deler_id;
					$users_orders = array_reverse($users_orders);
				}
			}
		}

		//		if ($user_id === 18) {
		//			$users_orders = array();
		//			$users_orders = get_user_meta(18, 'employees', true);
		//			$users_orders[] = 18;
		//			$users_orders = array_reverse($users_orders);
		//		}

		$tickets_status = array('stgh_new', 'stgh_answered', 'stgh_notanswered');
		foreach ($users_orders as $user_id) {
			foreach ($tickets_status as $status) {
				$args = array(
					//'author' => isset($current_user->ID) ? $current_user->ID : '',
					'_author_or_stgh_contact' => $user_id,
					'post_type' => STG_HELPDESK_POST_TYPE,
					'post_status' => $status,
					'order' => 'DESC',
					'orderby' => 'date',
					'posts_per_page' => 100,
					'no_found_rows' => false,
					'cache_results' => false,
					'paged' => $paged,
					'update_post_term_cache' => false,
					'update_post_meta_cache' => false,
					'meta_query' => array(
						array(
							'key' => '_stgh_contact',
							'value' => $user_id,
						),
					),
					's' => $search_query, // Add the search parameter
				);
				$user_tickets = new WP_Query($args);

				if ($user_tickets->have_posts()):

//					if (get_current_user_id() == 18) {
					echo '<h3><strong>' . get_user_meta($user_id, 'first_name', true) . ' ' . get_user_meta($user_id, 'last_name', true) . '</strong></h3>';

					?>
          <form method="GET" class="row" style="margin:0;">
            <div class="col-sm-6 col-lg-3 form-group">
            <input type="text" name="search" placeholder="Search by title" value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>" style="width: 100%">
            </div>
            <div class="col-sm-2 col-lg-1 form-group">
            <button type="submit" class="btn btn-primary btn-lg">Search</button>
            </div>
          </form>
					<?php
//					}

					$columns = Helpers\Stg_Helper_Template::getTicketsListColumns();
					?>
          <div class="stgh stgh-ticket-list">
						<?php
						if ($status == 'stgh_notanswered') {
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
									Helpers\Stg_Helper_Template::getTicketsListColumnContent($column_id, $column);

									echo '</td>';
								}

								echo '</tr>';

							endwhile;

							//wp_reset_query();
							?>
              </tbody>
            </table>
            <hr>
            <br>
          </div>

					<?php
					if ($status == 'stgh_notanswered') {
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
					}
					?>
				<?php
				else:
					?>
					<?php
//					if ($status != 'stgh_answered' && $user_id !== 18) {
//						echo getNotificationMarkup('info', __('Not found', STG_HELPDESK_TEXT_DOMAIN_NAME));
//					}
//
					?>

					<?php if (defined('STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME') && $myAccountId == $post->ID): ?>
          <span class="order">
                <a href="#" class="woocommerce-button button stg_order_get_help_NOORDER">
                    <?php
										_e('Open Ticket', STG_HELPDESK_WC_SUPPORT_TEXT_DOMAIN_NAME)
										?>
        </a>
        </span>
				<?php endif;
				endif;

				$url = stgh_get_submit_page_url();
				if (!empty($url)) :
					?>
          <a href="<?php echo esc_url($url); ?>"><?php _e('Open new ticket', STG_HELPDESK_TEXT_DOMAIN_NAME); ?></a>
				<?php endif;

				wp_reset_postdata();
			}
		}
		?>

  </div>

<?php
get_footer();
