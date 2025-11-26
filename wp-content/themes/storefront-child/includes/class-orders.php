<?php

class OrdersCustom
{
	/**
	 * Create custom orders table with proper security
	 */
	public function orderTablesCreate()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'custom_orders';
		$charset_collate = $wpdb->get_charset_collate();

		// Correct table existence check
		$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
		$table_exists = $wpdb->get_var($query);

		if ($table_exists !== $table_name) {
			$sql = "CREATE TABLE `{$table_name}` (
                id INT NOT NULL AUTO_INCREMENT,
                idOrder varchar(255) DEFAULT '' NOT NULL,
                reference varchar(100) DEFAULT '' NOT NULL,
                usd_price DECIMAL(10,2) DEFAULT 0.00 NOT NULL,
                gbp_price DECIMAL(10,2) DEFAULT 0.00 NOT NULL,
                subtotal_price DECIMAL(10,2) DEFAULT 0.00 NOT NULL,
                sqm DECIMAL(8,2) DEFAULT 0.00 NOT NULL,
                shipping_cost DECIMAL(10,2) DEFAULT 0.00 NOT NULL,
                delivery varchar(50) DEFAULT '' NOT NULL,
                status varchar(50) DEFAULT '' NOT NULL,
                createTime datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id),
                INDEX idx_idOrder (idOrder),
                INDEX idx_status (status),
                INDEX idx_createTime (createTime)
            ) {$charset_collate}";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			$result = dbDelta($sql);

			if ($result) {
				add_option('custom_orders_table', "1.0");
				return true;
			}

			return false;
		}

		return true; // Table already exists
	}

	/**
	 * Safely alter table to add new columns
	 */
	public function alterTableCustomOrders()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'custom_orders';

		// Verify table exists first
		$table_exists = $wpdb->get_var($wpdb->prepare(
		  "SHOW TABLES LIKE %s",
		  $wpdb->esc_like($table_name)
		));

		if ($table_exists !== $table_name) {
			return new WP_Error('table_not_found', 'Custom orders table does not exist');
		}

		$columns_to_add = [
		  'idUser' => [
			'exists' => false,
			'sql' => "ALTER TABLE `{$table_name}` ADD `idUser` INT DEFAULT 0 NOT NULL AFTER `idOrder`"
		  ],
		  'idRepair' => [
			'exists' => false,
			'sql' => "ALTER TABLE `{$table_name}` ADD `idRepair` INT DEFAULT 0 NOT NULL AFTER `idUser`"
		  ],
		  'favoriteUser' => [
			'exists' => false,
			'sql' => "ALTER TABLE `{$table_name}` ADD `favoriteUser` varchar(50) DEFAULT '' NOT NULL AFTER `idRepair`"
		  ]
		];

		// Check which columns exist
		foreach ($columns_to_add as $column => &$info) {
			$column_exists = $wpdb->get_results($wpdb->prepare(
			  "SHOW COLUMNS FROM `{$table_name}` LIKE %s",
			  $column
			));
			$info['exists'] = !empty($column_exists);
		}

		// Add missing columns
		$added_columns = [];
		foreach ($columns_to_add as $column => $info) {
			if (!$info['exists']) {
				$result = $wpdb->query($info['sql']);
				if ($result !== false) {
					$added_columns[] = $column;
				} else {
					return new WP_Error('alter_failed',
					  sprintf('Failed to add column %s: %s', $column, $wpdb->last_error)
					);
				}
			}
		}

		if (!empty($added_columns)) {
			// Add indexes for new columns
			if (in_array('idUser', $added_columns)) {
				$wpdb->query("ALTER TABLE `{$table_name}` ADD INDEX idx_idUser (`idUser`)");
			}
			if (in_array('idRepair', $added_columns)) {
				$wpdb->query("ALTER TABLE `{$table_name}` ADD INDEX idx_idRepair (`idRepair`)");
			}

			update_option('custom_orders_table_version', '1.2');
			return sprintf('Added columns: %s', implode(', ', $added_columns));
		}

		return 'All columns already exist';
	}

	/**
	 * Safely populate user IDs from WooCommerce orders
	 */
	public function populateUserIds()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'custom_orders';

		// Get orders without user IDs
		$orders = $wpdb->get_results($wpdb->prepare(
		  "SELECT id, idOrder FROM `{$table_name}` 
             WHERE (idUser = 0 OR idUser IS NULL) 
             AND idOrder != '' 
             LIMIT 100"
		));

		if (empty($orders)) {
			return 'No orders to update';
		}

		$updated_count = 0;
		$errors = [];

		foreach ($orders as $order) {
			// Validate order ID is numeric
			if (!is_numeric($order->idOrder)) {
				continue;
			}

			$customer_id = get_post_meta($order->idOrder, '_customer_user', true);

			if (!empty($customer_id) && is_numeric($customer_id)) {
				$result = $wpdb->update(
				  $table_name,
				  ['idUser' => intval($customer_id)],
				  ['id' => intval($order->id)],
				  ['%d'],
				  ['%d']
				);

				if ($result !== false) {
					$updated_count++;
				} else {
					$errors[] = "Failed to update order ID {$order->id}";
				}
			}
		}

		$message = "Updated {$updated_count} orders";
		if (!empty($errors)) {
			$message .= '. Errors: ' . implode(', ', $errors);
		}

		return $message;
	}



	/**
	 * Get table statistics
	 */
	public function getTableStats()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'custom_orders';

		$stats = $wpdb->get_row($wpdb->prepare(
		  "SELECT 
                COUNT(*) as total_orders,
                COUNT(CASE WHEN idUser > 0 THEN 1 END) as orders_with_users,
                COUNT(CASE WHEN idUser = 0 OR idUser IS NULL THEN 1 END) as orders_without_users
             FROM `{$table_name}`"
		), ARRAY_A);

		return $stats;
	}
}