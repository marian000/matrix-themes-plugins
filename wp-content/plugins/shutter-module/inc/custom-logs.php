<?php

// Define paths to log files in the plugin directory.
define( 'MY_PLUGIN_LOG_FILE', plugin_dir_path( __DIR__ ) . 'custom-log.txt' );
define( 'SHUTTER_LOG_PRICING_FILE', plugin_dir_path( __DIR__ ) . 'pricing-log.txt' );
define( 'SHUTTER_LOG_CHECKOUT_FILE', plugin_dir_path( __DIR__ ) . 'checkout-log.txt' );

/**
 * Get the maximum log file size in bytes from wp_options.
 *
 * @since 1.0.0
 * @return int Maximum log file size in bytes.
 */
function shutter_get_log_max_size() {
	return absint( get_option( 'shutter_log_max_size_mb', 3 ) ) * 1024 * 1024;
}

/**
 * Clean up old .bak log files that exceed the retention period.
 *
 * @since 1.0.0
 */
function shutter_cleanup_old_bak_files() {
	$retention_days = absint( get_option( 'shutter_log_retention_days', 60 ) );
	$log_dir        = plugin_dir_path( __DIR__ );
	$cutoff_time    = time() - ( $retention_days * DAY_IN_SECONDS );

	$bak_files = glob( $log_dir . '*.bak' );
	if ( ! is_array( $bak_files ) ) {
		return;
	}

	foreach ( $bak_files as $bak_file ) {
		if ( filemtime( $bak_file ) < $cutoff_time ) {
			wp_delete_file( $bak_file );
		}
	}
}

/**
 * Logs custom events to a file in the plugin directory.
 *
 * @since 1.0.0
 * @param string $title   The title of the log entry.
 * @param string $content The detailed content of the log entry.
 */
function my_custom_log( $title, $content ) {
	$date      = current_time( 'Y-m-d H:i:s' );
	$log_entry = sprintf( "[%s] %s: %s%s", $date, $title, $content, PHP_EOL );

	$rotated = false;
	if ( file_exists( MY_PLUGIN_LOG_FILE ) && filesize( MY_PLUGIN_LOG_FILE ) > shutter_get_log_max_size() ) {
		rename( MY_PLUGIN_LOG_FILE, MY_PLUGIN_LOG_FILE . '.' . time() . '.bak' );
		$rotated = true;
	}

	file_put_contents( MY_PLUGIN_LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX );

	if ( $rotated ) {
		shutter_cleanup_old_bak_files();
	}
}

/**
 * Register default log options if they do not exist yet.
 *
 * @since 1.0.0
 */
function shutter_register_default_log_options() {
	if ( get_option( 'shutter_log_max_size_mb' ) === false ) {
		update_option( 'shutter_log_max_size_mb', 3 );
	}
	if ( get_option( 'shutter_log_retention_days' ) === false ) {
		update_option( 'shutter_log_retention_days', 60 );
	}
	if ( get_option( 'shutter_log_pricing_enabled' ) === false ) {
		update_option( 'shutter_log_pricing_enabled', 0 );
	}
	if ( get_option( 'shutter_log_checkout_enabled' ) === false ) {
		update_option( 'shutter_log_checkout_enabled', 1 );
	}
}
add_action( 'init', 'shutter_register_default_log_options' );

add_action( 'admin_menu', function () {
	add_menu_page( 'Custom Log', 'Custom Log', 'manage_options', 'custom-log', 'my_custom_log_viewer' );
});

/**
 * Displays the log viewer admin page with a tabbed UI.
 *
 * Tabs:
 *   1. General Log   - View/clear custom-log.txt
 *   2. Products Log  - View/clear pricing-log.txt
 *   3. Settings      - Configure max size, retention, pricing toggle
 *
 * @since 2.0.0
 */
function my_custom_log_viewer() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	$allowed_tabs = array( 'general', 'products', 'checkout', 'settings' );
	$tab          = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	if ( ! in_array( $tab, $allowed_tabs, true ) ) {
		$tab = 'general';
	}

	$base_url = admin_url( 'admin.php?page=custom-log' );

	?>
	<style>
		.shutter-log-tabs { display: flex; gap: 0; margin-bottom: 0; border-bottom: 1px solid #c3c4c7; }
		.shutter-log-tabs a {
			padding: 8px 16px; text-decoration: none; color: #2c3338;
			border: 1px solid transparent; border-bottom: none; margin-bottom: -1px;
			background: #f6f7f7;
		}
		.shutter-log-tabs a.active {
			background: #fff; border-color: #c3c4c7; color: #1d2327; font-weight: 600;
		}
		.shutter-log-tabs a:hover { background: #fff; }
		.shutter-log-tab-content { background: #fff; border: 1px solid #c3c4c7; border-top: none; padding: 20px; }
		.shutter-log-pre { background: #f6f7f7; padding: 12px; border: 1px solid #ddd; overflow: auto; max-width: 80vw; white-space: pre-wrap; word-wrap: break-word; font-size: 12px; max-height: 600px; }
		.shutter-settings-table th { width: 220px; padding: 12px 10px; }
		.shutter-settings-table td { padding: 12px 10px; }
	</style>

	<div class="wrap">
		<h1>Custom Log Viewer</h1>

		<div class="shutter-log-tabs">
			<a href="<?php echo esc_url( $base_url . '&tab=general' ); ?>"
			   class="<?php echo $tab === 'general' ? 'active' : ''; ?>">General Log</a>
			<a href="<?php echo esc_url( $base_url . '&tab=products' ); ?>"
			   class="<?php echo $tab === 'products' ? 'active' : ''; ?>">Products Log</a>
			<a href="<?php echo esc_url( $base_url . '&tab=checkout' ); ?>"
			   class="<?php echo $tab === 'checkout' ? 'active' : ''; ?>">Checkout</a>
			<a href="<?php echo esc_url( $base_url . '&tab=settings' ); ?>"
			   class="<?php echo $tab === 'settings' ? 'active' : ''; ?>">Settings</a>
		</div>

		<div class="shutter-log-tab-content">
		<?php
		switch ( $tab ) {

			// ----- Tab 1: General Log -----
			case 'general':
				echo '<h2>General Log</h2>';

				// Handle clear action.
				if ( isset( $_POST['clear_logs'] ) && check_admin_referer( 'shutter_clear_general_log' ) ) {
					file_put_contents( MY_PLUGIN_LOG_FILE, '' );
					echo '<div class="updated"><p>Logs have been cleared successfully!</p></div>';
				}

				echo '<form method="post">';
				wp_nonce_field( 'shutter_clear_general_log' );
				echo '<input type="submit" name="clear_logs" class="button button-secondary" '
					. 'value="Clear Logs" style="color:#b32d2e;border-color:#b32d2e;" '
					. 'onclick="return confirm(\'Are you sure you want to clear the general log?\');">';
				echo '</form>';

				if ( file_exists( MY_PLUGIN_LOG_FILE ) && filesize( MY_PLUGIN_LOG_FILE ) > 0 ) {
					$log_content = file_get_contents( MY_PLUGIN_LOG_FILE );
					$lines = explode( "\n", trim( $log_content ) );
					$lines = array_reverse( $lines );
					$log_content = implode( "\n", $lines );
					echo '<pre class="shutter-log-pre">' . esc_html( $log_content ) . '</pre>';
				} else {
					echo '<p>No logs found.</p>';
				}

				break;

			// ----- Tab 2: Products / Pricing Log -----
			case 'products':
				echo '<h2>Products / Pricing Log</h2>';

				// Handle clear action.
				if ( isset( $_POST['clear_pricing_log'] ) && check_admin_referer( 'shutter_clear_pricing_log' ) ) {
					file_put_contents( SHUTTER_LOG_PRICING_FILE, '' );
					echo '<div class="updated"><p>Pricing logs have been cleared successfully!</p></div>';
				}

				// Notice when pricing logging is disabled.
				$pricing_enabled = get_option( 'shutter_log_pricing_enabled', 0 );
				if ( ! $pricing_enabled ) {
					echo '<div class="notice notice-info"><p>Pricing logging is currently disabled. '
						. 'Enable it in the <a href="' . esc_url( $base_url . '&tab=settings' ) . '">Settings</a> tab.</p></div>';
				}

				echo '<form method="post">';
				wp_nonce_field( 'shutter_clear_pricing_log' );
				echo '<input type="submit" name="clear_pricing_log" class="button button-secondary" '
					. 'value="Clear Pricing Log" style="color:#b32d2e;border-color:#b32d2e;" '
					. 'onclick="return confirm(\'Are you sure you want to clear the pricing log?\');">';
				echo '</form>';

				if ( file_exists( SHUTTER_LOG_PRICING_FILE ) && filesize( SHUTTER_LOG_PRICING_FILE ) > 0 ) {
					$pricing_content = file_get_contents( SHUTTER_LOG_PRICING_FILE );
					$lines = explode( "\n", trim( $pricing_content ) );
					$lines = array_reverse( $lines );
					$pricing_content = implode( "\n", $lines );
					echo '<pre class="shutter-log-pre">' . esc_html( $pricing_content ) . '</pre>';
				} else {
					echo '<p>No pricing logs found.</p>';
				}
				break;

			// ----- Tab 3: Checkout Debug -----
			case 'checkout':
				echo '<h2>Checkout Debug Log</h2>';
				echo '<p class="description">This log captures cart quantity and price discrepancies at checkout. Enable/disable in the <a href="' . esc_url( $base_url . '&tab=settings' ) . '">Settings</a> tab.</p>';

				$checkout_enabled = get_option( 'shutter_log_checkout_enabled', 0 );
				if ( ! $checkout_enabled ) {
					echo '<div class="notice notice-info"><p>Checkout logging is currently disabled. '
						. 'Enable it in the <a href="' . esc_url( $base_url . '&tab=settings' ) . '">Settings</a> tab.</p></div>';
				}

				// Handle clear action.
				if ( isset( $_POST['clear_checkout_log'] ) && check_admin_referer( 'shutter_clear_checkout_log' ) ) {
					// Clear log file.
					file_put_contents( SHUTTER_LOG_CHECKOUT_FILE, '' );
					// Clear all fingerprint transients.
					global $wpdb;
					$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_chk_log_fp_%' OR option_name LIKE '_transient_timeout_chk_log_fp_%'" );
					echo '<div class="updated"><p>Checkout logs have been cleared successfully!</p></div>';
				}

				echo '<form method="post">';
				wp_nonce_field( 'shutter_clear_checkout_log' );
				echo '<input type="submit" name="clear_checkout_log" class="button button-secondary" '
					. 'value="Clear Checkout Log" style="color:#b32d2e;border-color:#b32d2e;" '
					. 'onclick="return confirm(\'Are you sure you want to clear the checkout log?\');">';
				echo '</form>';

				// Rotate if needed.
				if ( file_exists( SHUTTER_LOG_CHECKOUT_FILE ) && filesize( SHUTTER_LOG_CHECKOUT_FILE ) > shutter_get_log_max_size() ) {
					rename( SHUTTER_LOG_CHECKOUT_FILE, SHUTTER_LOG_CHECKOUT_FILE . '.' . time() . '.bak' );
					shutter_cleanup_old_bak_files();
				}

				if ( file_exists( SHUTTER_LOG_CHECKOUT_FILE ) && filesize( SHUTTER_LOG_CHECKOUT_FILE ) > 0 ) {
					$checkout_content = file_get_contents( SHUTTER_LOG_CHECKOUT_FILE );
					$lines = explode( "\n", trim( $checkout_content ) );
					$lines = array_reverse( $lines );
					$checkout_content = implode( "\n", $lines );
					echo '<pre class="shutter-log-pre">' . esc_html( $checkout_content ) . '</pre>';
				} else {
					echo '<p>No checkout logs found. Visit the checkout page with items in cart to generate logs.</p>';
				}
				break;

			// ----- Tab 4: Settings -----
			case 'settings':
				echo '<h2>Log Settings</h2>';

				// Handle save action.
				if ( isset( $_POST['save_log_settings'] ) && check_admin_referer( 'shutter_save_log_settings' ) ) {
					$max_size_mb    = absint( $_POST['log_max_size_mb'] );
					$max_size_mb    = max( 1, min( 100, $max_size_mb ) );

					$retention_days = absint( $_POST['log_retention_days'] );
					$retention_days = max( 1, min( 365, $retention_days ) );

					$pricing_on     = isset( $_POST['log_pricing_enabled'] ) ? intval( $_POST['log_pricing_enabled'] ) : 0;
					$pricing_on     = ( $pricing_on === 1 ) ? 1 : 0;

					$checkout_on    = isset( $_POST['log_checkout_enabled'] ) ? intval( $_POST['log_checkout_enabled'] ) : 0;
					$checkout_on    = ( $checkout_on === 1 ) ? 1 : 0;

					update_option( 'shutter_log_max_size_mb', $max_size_mb );
					update_option( 'shutter_log_retention_days', $retention_days );
					update_option( 'shutter_log_pricing_enabled', $pricing_on );
					update_option( 'shutter_log_checkout_enabled', $checkout_on );

					echo '<div class="updated"><p>Settings saved.</p></div>';
				}

				$current_max_size  = absint( get_option( 'shutter_log_max_size_mb', 3 ) );
				$current_retention = absint( get_option( 'shutter_log_retention_days', 60 ) );
				$current_pricing   = intval( get_option( 'shutter_log_pricing_enabled', 0 ) );
					$current_checkout  = intval( get_option( 'shutter_log_checkout_enabled', 1 ) );

				echo '<form method="post">';
				wp_nonce_field( 'shutter_save_log_settings' );
				?>
				<table class="form-table shutter-settings-table">
					<tr>
						<th><label for="log_max_size_mb">Max Log Size (MB)</label></th>
						<td>
							<input type="number" id="log_max_size_mb" name="log_max_size_mb"
								min="1" max="100"
								value="<?php echo esc_attr( $current_max_size ); ?>">
							<p class="description">Log files are rotated when they exceed this size. Default: 3 MB.</p>
						</td>
					</tr>
					<tr>
						<th><label for="log_retention_days">Retention Period (Days)</label></th>
						<td>
							<input type="number" id="log_retention_days" name="log_retention_days"
								min="1" max="365"
								value="<?php echo esc_attr( $current_retention ); ?>">
							<p class="description">Backup (.bak) files older than this are deleted automatically. Default: 60 days.</p>
						</td>
					</tr>
					<tr>
						<th>Enable Pricing Calculation Logging</th>
						<td>
							<label>
								<input type="radio" name="log_pricing_enabled" value="1"
									<?php checked( $current_pricing, 1 ); ?>>
								Enabled
							</label>
							<br>
							<label>
								<input type="radio" name="log_pricing_enabled" value="0"
									<?php checked( $current_pricing, 0 ); ?>>
								Disabled
							</label>
							<p class="description">When enabled, every price calculation is written to pricing-log.txt with full phase breakdown.</p>
						</td>
					</tr>
				</table>
				<?php
				echo '<input type="submit" name="save_log_settings" class="button button-primary" value="Save Settings">';
				echo '</form>';

				// ----- Maintenance: wp_custom_orders dedup -----
				if ( current_user_can( 'manage_woocommerce' ) ) {
					global $wpdb;
					$dup_count = (int) $wpdb->get_var(
						"SELECT COUNT(*) FROM (
							SELECT idOrder FROM {$wpdb->prefix}custom_orders
							WHERE idOrder <> ''
							GROUP BY idOrder HAVING COUNT(*) > 1
						) t"
					);
					$last_backup = $wpdb->get_var(
						"SELECT TABLE_NAME FROM information_schema.TABLES
						 WHERE TABLE_SCHEMA = DATABASE()
						   AND TABLE_NAME LIKE '{$wpdb->prefix}custom_orders_backup_%'
						 ORDER BY TABLE_NAME DESC LIMIT 1"
					);

					echo '<hr style="margin:30px 0">';
					echo '<h2>Maintenance: wp_custom_orders Dedup</h2>';
					echo '<table class="form-table shutter-settings-table">';
					echo '<tr><th>idOrder cu duplicate</th><td><strong>' . intval( $dup_count ) . '</strong></td></tr>';
					echo '<tr><th>Ultimul backup</th><td>' . ( $last_backup ? '<code>' . esc_html( $last_backup ) . '</code>' : '—' ) . '</td></tr>';
					echo '</table>';

					if ( $dup_count > 0 ) {
						$dedup_url = wp_nonce_url(
							admin_url( '?matrix_dedup_custom_orders=1' ),
							'matrix_dedup_custom_orders'
						);
						echo '<p><a href="' . esc_url( $dedup_url ) . '" class="button button-primary" '
							. 'onclick="return confirm(\'Backup full + șterge rânduri duplicate (păstrează MAX(id) per idOrder). Continui?\');">'
							. 'Rulează dedup (cu backup auto)</a></p>';
						echo '<p class="description">Creează tabela backup <code>' . esc_html( $wpdb->prefix ) . 'custom_orders_backup_YYYYMMDD_HHMMSS</code> înainte de DELETE. '
							. 'wc-reports SQM total deja de-duplică în query, deci raportarea e corectă fără cleanup. '
							. 'Cleanup elimină rândurile redundante din DB.</p>';
					} else {
						echo '<p style="color:#1d2327"><span class="dashicons dashicons-yes"></span> Nu există rânduri duplicate.</p>';
					}
				}
				break;
		}
		?>
		</div><!-- .shutter-log-tab-content -->
	</div><!-- .wrap -->
	<?php
}
