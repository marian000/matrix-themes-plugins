<?php
/**
 * My App Orders Template - Optimized for Performance
 */

// Cache user ID to avoid multiple function calls
$user_id = get_current_user_id();
$meta_key = 'wc_multiple_shipping_addresses';

// Function to get appcarts with caching
function get_appcarts_optimized($user_id) {
	$cache_key = 'appcarts_user_' . $user_id;
	$cached_result = wp_cache_get($cache_key, 'appcarts');

	if ($cached_result !== false) {
		return $cached_result;
	}

	$args = array(
	  'post_type' => 'appcart',
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	  'author' => $user_id,
	  'orderby' => 'date',
	  'order' => 'DESC',
	  'no_found_rows' => true, // Skip pagination info
	  'update_post_meta_cache' => false, // Skip meta cache initially
	  'update_post_term_cache' => false, // Skip term cache
	);

	$appcarts = new WP_Query($args);

	// Cache for 5 minutes
	wp_cache_set($cache_key, $appcarts, 'appcarts', 300);

	return $appcarts;
}

// Function to batch load meta data
function get_appcarts_meta_batch($post_ids) {
	if (empty($post_ids)) {
		return array();
	}

	global $wpdb;
	$post_ids_str = implode(',', array_map('intval', $post_ids));

	$meta_results = $wpdb->get_results(
	  "SELECT post_id, meta_value FROM {$wpdb->postmeta} 
         WHERE post_id IN ({$post_ids_str}) AND meta_key = 'attached_product_ids'",
	  ARRAY_A
	);

	$meta_data = array();
	foreach ($meta_results as $row) {
		$meta_data[$row['post_id']] = maybe_unserialize($row['meta_value']);
	}

	return $meta_data;
}

// Get optimized data
$appcarts = get_appcarts_optimized($user_id);

// Collect post IDs for batch meta loading
$post_ids = array();
if ($appcarts->have_posts()) {
	while ($appcarts->have_posts()) {
		$appcarts->the_post();
		$post_ids[] = get_the_ID();
	}
	$appcarts->rewind_posts(); // Reset for second loop
}

// Batch load meta data
$meta_data = get_appcarts_meta_batch($post_ids);

// Pre-generate nonce for security
$delete_nonce = wp_create_nonce("delete_appcart_post_nonce");
$ajax_url = admin_url("admin-ajax.php");
?>

    <h2>My App Orders</h2>

    <div class="show-table2"></div>

    <p></p>

    <table class="table home app-table">
        <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>View</th>
            <th>Import</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
		<?php if ($appcarts->have_posts()) : ?>
			<?php while ($appcarts->have_posts()) : $appcarts->the_post(); ?>
				<?php
				$post_id = get_the_ID();
				$prods = isset($meta_data[$post_id]) ? $meta_data[$post_id] : array();

				// Convert to string for JavaScript
				$prods_string = is_array($prods) ? implode(',', $prods) : '';

				// Cache permalink and title for performance
				$permalink = get_permalink();
				$title = get_the_title();
				$date = get_the_date();
				?>
                <tr data-post-id="<?php echo esc_attr($post_id); ?>">
                    <td><?php echo esc_html($title); ?></td>
                    <td><?php echo esc_html($date); ?></td>
                    <td>
                        <a href="<?php echo esc_url($permalink); ?>" class="btn btn-primary">View</a>
                    </td>
                    <td>
                        <button class="import-appcart btn btn-primary"
                                data-post-id="<?php echo esc_attr($post_id); ?>"
                                data-title="<?php echo esc_attr($title); ?>"
                                data-prods="<?php echo esc_attr($prods_string); ?>"
                                onclick="return BMCWcMs.command('insertAppOrder', this);">
                            Import
                        </button>
                    </td>
                    <td>
                        <button class="delete-appcart btn btn-danger delete-btn"
                                data-post-id="<?php echo esc_attr($post_id); ?>">
                            Delete
                        </button>
                    </td>
                </tr>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
            <tr>
                <td colspan="5">No App Carts found.</td>
            </tr>
		<?php endif; ?>
        </tbody>
    </table>

    <script type="text/javascript">
        (function($) {
            'use strict';

            // Cache DOM elements and values
            const ajaxUrl = '<?php echo esc_js($ajax_url); ?>';
            const deleteNonce = '<?php echo esc_js($delete_nonce); ?>';

            // Optimized delete functionality
            $(document).on('click', '.delete-appcart', function(e) {
                e.preventDefault();

                const $button = $(this);
                const postId = $button.data('post-id');
                const $row = $button.closest('tr');

                if (!confirm('Are you sure you want to delete this appCart?')) {
                    return false;
                }

                // Disable button to prevent double clicks
                $button.prop('disabled', true).text('Deleting...');

                $.ajax({
                    url: ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'delete_appcart_post',
                        post_id: postId,
                        security: deleteNonce
                    },
                    timeout: 10000, // 10 second timeout
                    success: function(response) {
                        if (response.success) {
                            // Smooth animation and row removal
                            $row.fadeOut(300, function() {
                                $(this).remove();

                                // Check if table is empty and show message
                                if ($('.table tbody tr').length === 0) {
                                    $('.table tbody').html('<tr><td colspan="5">No App Carts found.</td></tr>');
                                }
                            });
                        } else {
                            alert('Failed to delete the post.');
                            $button.prop('disabled', false).text('Delete');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', error);
                        alert('An error occurred while deleting. Please try again.');
                        $button.prop('disabled', false).text('Delete');
                    }
                });
            });

            // Prevent double submissions on import
            $(document).on('click', '.import-appcart', function() {
                const $button = $(this);
                $button.prop('disabled', true);

                // Re-enable after 2 seconds
                setTimeout(function() {
                    $button.prop('disabled', false);
                }, 2000);
            });

        })(jQuery);
    </script>

<?php
// Clean up memory
unset($appcarts, $meta_data, $post_ids);
?>