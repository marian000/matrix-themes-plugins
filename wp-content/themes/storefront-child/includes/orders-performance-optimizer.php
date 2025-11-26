<?php
/**
 * Orders Performance Optimizer - Bulk Loading Solution
 * Reduce N+1 query problem for WooCommerce orders admin page
 */

class OrdersPerformanceOptimizer {

	private static $instance = null;
	private $bulk_data_loaded = false;

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		if (function_exists('my_custom_log')) {
			// my_custom_log("Optimizer Init", "OrdersPerformanceOptimizer initialized successfully");
		}
		$this->init_hooks();
	}

	private function init_hooks() {
		// Hook pentru detectarea paginii de orders
		add_action('current_screen', array($this, 'maybe_preload_data'));

		// Hook pentru definirea coloanelor (folosind manage_edit-shop_order_columns pentru compatibilitate)
		add_filter('manage_edit-shop_order_columns', array($this, 'add_custom_columns'), 20);

		// Hook pentru column content optimizat
		add_action('manage_shop_order_posts_custom_column', array($this, 'optimized_column_content'), 20, 2);
	}

	/**
	 * Adaugă coloanele personalizate cu aceeași logică ca în codul original
	 */
	public function add_custom_columns($columns) {
		$reordered_columns = array();

		// Inserting columns to a specific location (exact ca în codul original)
		foreach ($columns as $key => $column) {
			$reordered_columns[$key] = $column;

			if ($key == 'order_number') {
				// Inserting after "order_number" column
				$reordered_columns['order_reff'] = __('Order Ref', 'theme_domain');
				$reordered_columns['order_QB'] = __('QB Invoice', 'theme_domain');
			}

			if ($key == 'order_status') {
				// Inserting after "Status" column
				$reordered_columns['eta_col'] = __('ETA', 'theme_domain');
				$reordered_columns['container-id'] = __('Container', 'theme_domain');
				// $reordered_columns['order-shipping'] = __('Delivery', 'theme_domain'); // commented in original
				$reordered_columns['order-sqm'] = __('SQM', 'theme_domain');
				$reordered_columns['order-price-dolar'] = __('Total $', 'theme_domain');
			}
		}

		// Hide columns for china_admin users (exact ca în original)
		if (current_user_can('china_admin')) {
			unset($reordered_columns['order_total']);
		}

		return $reordered_columns;
	}

	/**
	 * Detectează dacă suntem pe pagina de orders și inițializează pre-loading
	 */
	public function maybe_preload_data($current_screen) {
		if ($current_screen->id === 'edit-shop_order') {
			add_action('pre_get_posts', array($this, 'setup_bulk_loading'), 5);
		}
	}

	/**
	 * Setup pentru bulk loading înainte de query
	 */
	public function setup_bulk_loading($query) {
		if ($query->is_main_query() && is_admin()) {
			add_action('wp', array($this, 'bulk_load_orders_data'), 1);
		}
	}

	/**
	 * Încarcă toate datele necesare într-o singură operație
	 */
	public function bulk_load_orders_data() {
		if ($this->bulk_data_loaded) {
			return; // Previne încărcarea multiplă
		}

		global $wpdb;

		// Obține order IDs de pe pagina curentă
		$order_ids = $this->get_current_page_order_ids();

		if (empty($order_ids)) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Bulk Loading", "No order IDs found for current page");
			}
			return;
		}

		$order_ids_string = implode(',', array_map('intval', $order_ids));

		// 1. Pre-load custom_orders data
		$this->preload_custom_orders_data($order_ids_string);

		// 2. Pre-load meta data
		$this->preload_meta_data($order_ids_string);

		// 3. Pre-load user favorites
		$this->preload_user_favorites_data();

		// 4. Pre-load status array (o singură dată)
		$this->preload_status_data();

		$this->bulk_data_loaded = true;

		// Log pentru debugging
		if (function_exists('my_custom_log')) {
			// my_custom_log("Bulk Loading", "Pre-loaded data for " . count($order_ids) . " orders");
		}
	}

	/**
	 * Obține ID-urile comenzilor de pe pagina curentă (versiune simplificată și sigură)
	 */
	private function get_current_page_order_ids() {
		try {
			// Folosim o abordare mai simplă pentru a obține order IDs-urile
			global $wp_query;

			if (!isset($wp_query->posts) || empty($wp_query->posts)) {
				return array();
			}

			$order_ids = array();
			foreach ($wp_query->posts as $post) {
				if ($post->post_type === 'shop_order') {
					$order_ids[] = intval($post->ID);
				}
			}

			if (function_exists('my_custom_log')) {
				// my_custom_log("Order IDs", "Found " . count($order_ids) . " orders on current page");
			}

			return $order_ids;

		} catch (Exception $e) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Error", "Error getting order IDs: " . $e->getMessage());
			}
			return array();
		}
	}

	/**
	 * Pre-load custom_orders data
	 */
	private function preload_custom_orders_data($order_ids_string) {
		if (empty($order_ids_string)) {
			return;
		}

		global $wpdb;
		$tablename = $wpdb->prefix . 'custom_orders';

		try {
			$custom_orders_data = $wpdb->get_results(
			  "SELECT idOrder, sqm, usd_price, gbp_price, delivery 
                 FROM {$tablename} 
                 WHERE idOrder IN ($order_ids_string)",
			  OBJECT_K
			);

			wp_cache_set('bulk_custom_orders', $custom_orders_data, 'orders_optimizer', 300);

			if (function_exists('my_custom_log')) {
				// my_custom_log("Bulk Loading", "Loaded " . count($custom_orders_data) . " custom orders records");
			}
		} catch (Exception $e) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Error", "Error loading custom orders data: " . $e->getMessage());
			}
		}
	}

	/**
	 * Pre-load meta data
	 */
	private function preload_meta_data($order_ids_string) {
		if (empty($order_ids_string)) {
			return;
		}

		global $wpdb;

		$meta_keys = array(
		  '_customer_user',
		  'cart_name',
		  'container_id',
		  'delivereis_start',
		  'ticket_id_for_order'
		);

		$meta_keys_string = "'" . implode("','", $meta_keys) . "'";

		try {
			$meta_data = $wpdb->get_results(
			  "SELECT post_id, meta_key, meta_value 
                 FROM {$wpdb->postmeta} 
                 WHERE post_id IN ($order_ids_string) 
                 AND meta_key IN ($meta_keys_string)",
			  ARRAY_A
			);

			// Organizează meta data pentru acces rapid
			$organized_meta = array();
			foreach ($meta_data as $meta) {
				$organized_meta[$meta['post_id']][$meta['meta_key']] = $meta['meta_value'];
			}

			wp_cache_set('bulk_meta_data', $organized_meta, 'orders_optimizer', 300);

			if (function_exists('my_custom_log')) {
				// my_custom_log("Bulk Loading", "Loaded " . count($meta_data) . " meta records");
			}
		} catch (Exception $e) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Error", "Error loading meta data: " . $e->getMessage());
			}
		}
	}

	/**
	 * Pre-load user favorites data
	 */
	private function preload_user_favorites_data() {
		global $wpdb;

		$meta_data = wp_cache_get('bulk_meta_data', 'orders_optimizer');
		if (!$meta_data) return;

		$customer_ids = array();
		foreach ($meta_data as $post_id => $meta) {
			if (isset($meta['_customer_user']) && $meta['_customer_user']) {
				$customer_ids[] = intval($meta['_customer_user']);
			}
		}

		if (empty($customer_ids)) return;

		try {
			$customer_ids_string = implode(',', array_unique($customer_ids));
			$user_favorites = $wpdb->get_results(
			  "SELECT user_id, meta_value 
                 FROM {$wpdb->usermeta} 
                 WHERE user_id IN ($customer_ids_string) 
                 AND meta_key = 'favorite_user'",
			  OBJECT_K
			);

			wp_cache_set('bulk_user_favorites', $user_favorites, 'orders_optimizer', 300);

			if (function_exists('my_custom_log')) {
				// my_custom_log("Bulk Loading", "Loaded " . count($user_favorites) . " user favorites");
			}
		} catch (Exception $e) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Error", "Error loading user favorites: " . $e->getMessage());
			}
		}
	}

	/**
	 * Pre-load status data
	 */
	private function preload_status_data() {
		try {
			if (function_exists('stgh_get_statuses')) {
				$status_array = stgh_get_statuses();
				wp_cache_set('bulk_status_array', $status_array, 'orders_optimizer', 600);
			}
		} catch (Exception $e) {
			if (function_exists('my_custom_log')) {
				// my_custom_log("Error", "Error loading status data: " . $e->getMessage());
			}
		}
	}

	/**
	 * Optimized column content cu bulk loaded data (logica exactă din codul original)
	 */
	public function optimized_column_content($column, $post_id) {
		// Retrieve pre-loaded data from cache
		$custom_orders_data = wp_cache_get('bulk_custom_orders', 'orders_optimizer');
		$meta_data = wp_cache_get('bulk_meta_data', 'orders_optimizer');
		$user_favorites = wp_cache_get('bulk_user_favorites', 'orders_optimizer');
		$status_array = wp_cache_get('bulk_status_array', 'orders_optimizer');

		// Fallback la query direct dacă cache-ul nu e disponibil
		if (!$custom_orders_data) {
			global $wpdb;
			$tablename = $wpdb->prefix . 'custom_orders';
			$myOrder = $wpdb->get_row($wpdb->prepare(
			  "SELECT sqm, usd_price, gbp_price, delivery FROM $tablename WHERE idOrder = %d",
			  $post_id
			), ARRAY_A);
		} else {
			$myOrder = isset($custom_orders_data[$post_id]) ? (array)$custom_orders_data[$post_id] : array();
		}

		$post_meta = isset($meta_data[$post_id]) ? $meta_data[$post_id] : array();
		$customer_id = isset($post_meta['_customer_user']) ? $post_meta['_customer_user'] : get_post_meta($post_id, '_customer_user', true);

		if (isset($user_favorites[$customer_id])) {
			$favorite = $user_favorites[$customer_id]->meta_value;
		} else {
			$favorite = get_user_meta($customer_id, 'favorite_user', true);
		}

		// Get order status (folosind cache-ul WooCommerce)
		$order = wc_get_order($post_id);
		$order_status = $order ? $order->get_status() : '';

		// Switch exact ca în codul original
		switch ($column) {
			case 'order-shipping':
				echo ($myOrder && isset($myOrder['delivery']) && $myOrder['delivery'] === 'air') ? '<strong>By Air</strong>' : '';
				break;

			case 'order-sqm':
				$sqm_val = isset($myOrder['sqm']) ? floatval($myOrder['sqm']) : 0;
				echo number_format($sqm_val, 3);
				break;

			case 'order-price-dolar':
				$usd_price = isset($myOrder['usd_price']) ? floatval($myOrder['usd_price']) : 0;
				echo '$' . number_format($usd_price, 2);
				break;

			case 'order_reff':
				if (function_exists('display_meta_value')) {
					display_meta_value($post_id, 'cart_name', '<small>(<em>no value</em>)</small>');
				} else {
					$cart_name = isset($post_meta['cart_name']) ? $post_meta['cart_name'] : get_post_meta($post_id, 'cart_name', true);
					echo $cart_name ? esc_html($cart_name) : '<small>(<em>no value</em>)</small>';
				}
				break;

			case 'order_QB':
				if (function_exists('display_quickbooks_invoice_button')) {
					display_quickbooks_invoice_button($post_id);
				} else {
					echo '<button disabled>QB</button>';
				}
				break;

			case 'container-id':
				if (function_exists('display_meta_value')) {
					display_meta_value($post_id, 'container_id', '<small>(<em>no value</em>)</small>', true);
				} else {
					$container_id = isset($post_meta['container_id']) ? $post_meta['container_id'] : get_post_meta($post_id, 'container_id', true);
					echo $container_id ? esc_html($container_id) : '<small>(<em>no value</em>)</small>';
				}
				break;

			case 'eta_col':
				$this->render_eta_column_optimized($post_meta, $favorite, $order_status, $status_array, $post_id);
				break;

			case 'order_status':
				if (function_exists('display_order_status')) {
					display_order_status($post_id, $order_status);
				} else {
					echo esc_html($order_status);
				}
				break;
		}
	}

	/**
	 * Render ETA column cu optimizări (logica exactă din original)
	 */
	private function render_eta_column_optimized($post_meta, $favorite, $order_status, $status_array, $post_id) {
		$delivereis_start = isset($post_meta['delivereis_start']) ? $post_meta['delivereis_start'] : get_post_meta($post_id, 'delivereis_start', true);
		$ticket_id_for_order = isset($post_meta['ticket_id_for_order']) ? $post_meta['ticket_id_for_order'] : get_post_meta($post_id, 'ticket_id_for_order', true);

		// Culorile exact ca în original (observ că era o greșeală în cod: '#ff000' în loc de '#ff0000')
		$colors_ticket = array(
		  'stgh_notanswered' => '#ff0000', // Fixed typo from original
		  'stgh_answered' => '#ff0000',
		  'stgh_new' => '#ff0000'
		);

		if ($delivereis_start != "") {
			echo esc_html($delivereis_start);
		} else {
			if ($ticket_id_for_order) {
				$postStatus = get_post_status($ticket_id_for_order);
				$color = isset($colors_ticket[$postStatus]) ? $colors_ticket[$postStatus] : '#000000';

				echo '<span style="color: ' . esc_attr($color) . '">';
				if ($status_array && isset($status_array[$postStatus])) {
					echo esc_html($status_array[$postStatus]);
				} else {
					echo esc_html($postStatus);
				}
				echo '</span>';
			} else {
				if ($favorite === "yes") {
					echo 'In Production';
				} else {
					if ($order_status == 'inproduction') {
						echo 'In Production';
					} else if ($order_status == 'processing') {
						echo 'In Production';
					} else {
						echo 'On Hold';
					}
				}
			}
		}
	}
}

// Inițializează optimizatorul
function init_orders_performance_optimizer() {
	if (is_admin()) {
		OrdersPerformanceOptimizer::getInstance();
	}
}
add_action('init', 'init_orders_performance_optimizer');