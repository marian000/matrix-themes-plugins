<?php
/**
 * Sistem îmbunătățit de procesare comenzi cu AJAX și progres în timp real
 */
class CustomOrdersProcessorAjax {
	private $batch_size = 50;
	private $max_execution_time = 25;

	public function __construct() {
		$this->init_hooks();
	}

	private function init_hooks() {
		// AJAX hooks
		add_action('wp_ajax_process_orders_batch', array($this, 'ajax_process_orders_batch'));
		add_action('wp_ajax_get_orders_stats', array($this, 'ajax_get_orders_stats'));

		// Admin hooks
		add_action('admin_menu', array($this, 'add_admin_menu_page'));
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
	}

	/**
	 * AJAX: Procesează un lot de comenzi
	 */
	public function ajax_process_orders_batch() {
		// Buffer cleanup pentru AJAX
		if (ob_get_level()) {
			ob_clean();
		}

		// Verificări de securitate
		if (!check_ajax_referer('process_orders_nonce', 'nonce', false)) {
			wp_send_json_error('Eroare de securitate');
		}

		if (!current_user_can('manage_options')) {
			wp_send_json_error('Fără permisiuni');
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_orders';

		try {
			// Verifică dacă tabela există
			if (!$this->table_exists()) {
				wp_send_json_error('Tabela nu există');
			}

			// Procesează un lot
			$result = $this->process_single_batch();

			if ($result['success']) {
				wp_send_json_success($result);
			} else {
				wp_send_json_error($result['message']);
			}

		} catch (Exception $e) {
			wp_send_json_error('Eroare: ' . $e->getMessage());
		}
	}

	/**
	 * AJAX: Obține statistici actualizate
	 */
	public function ajax_get_orders_stats() {
		// Buffer cleanup pentru AJAX
		if (ob_get_level()) {
			ob_clean();
		}

		if (!check_ajax_referer('process_orders_nonce', 'nonce', false)) {
			wp_send_json_error('Eroare de securitate');
		}

		$stats = $this->get_processing_stats();
		wp_send_json_success($stats);
	}

	/**
	 * Procesează un singur lot de comenzi
	 */
	private function process_single_batch() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_orders';

		// Obține comenzile care au nevoie de actualizare
		$orders_to_update = $wpdb->get_results($wpdb->prepare(
		  "SELECT id, idOrder 
             FROM {$table_name} 
             WHERE (idUser IS NULL OR idUser = 0) 
             AND idOrder IS NOT NULL 
             AND idOrder != '' 
             AND idOrder REGEXP '^[0-9]+$'
             ORDER BY id ASC 
             LIMIT %d",
		  $this->batch_size
		));

		if (empty($orders_to_update)) {
			return array(
			  'success' => true,
			  'updated' => 0,
			  'errors' => 0,
			  'total' => 0,
			  'completed' => true,
			  'message' => 'Procesarea completă - nu mai sunt comenzi de actualizat'
			);
		}

		// Obține customer IDs într-o singură interogare
		$order_ids = array_map(function($order) {
			return intval($order->idOrder);
		}, $orders_to_update);

		if (empty($order_ids)) {
			return array(
			  'success' => true,
			  'updated' => 0,
			  'errors' => 0,
			  'total' => 0,
			  'completed' => true,
			  'message' => 'Nu există comenzi valide de procesat'
			);
		}

		$order_ids_string = implode(',', $order_ids);
		$customer_users = $wpdb->get_results(
		  "SELECT post_id, meta_value 
             FROM {$wpdb->postmeta} 
             WHERE post_id IN ({$order_ids_string}) 
             AND meta_key = '_customer_user' 
             AND meta_value != '' 
             AND meta_value REGEXP '^[0-9]+$'"
		);

		// Creează mapping
		$customer_mapping = array();
		foreach ($customer_users as $customer_user) {
			$customer_mapping[$customer_user->post_id] = intval($customer_user->meta_value);
		}

		// Procesează actualizările
		$updated_count = 0;
		$error_count = 0;

		foreach ($orders_to_update as $order) {
			$order_id = intval($order->idOrder);

			if (!isset($customer_mapping[$order_id])) {
				$error_count++;
				continue;
			}

			$customer_user_id = $customer_mapping[$order_id];

			$result = $wpdb->update(
			  $table_name,
			  array('idUser' => $customer_user_id),
			  array('id' => intval($order->id)),
			  array('%d'),
			  array('%d')
			);

			if ($result !== false) {
				$updated_count++;
			} else {
				$error_count++;
			}
		}

		return array(
		  'success' => true,
		  'updated' => $updated_count,
		  'errors' => $error_count,
		  'total' => count($orders_to_update),
		  'completed' => false,
		  'message' => "Lot procesat: {$updated_count} actualizate, {$error_count} erori"
		);
	}

	/**
	 * Verifică dacă tabela există
	 */
	private function table_exists() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_orders';
		$query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));
		return $wpdb->get_var($query) === $table_name;
	}

	/**
	 * Obține statistici
	 */
	private function get_processing_stats() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'custom_orders';

		$stats = $wpdb->get_row(
		  "SELECT 
                COUNT(*) as total_orders,
                COUNT(CASE WHEN idUser > 0 THEN 1 END) as orders_with_users,
                COUNT(CASE WHEN idUser = 0 OR idUser IS NULL THEN 1 END) as orders_without_users
             FROM {$table_name}",
		  ARRAY_A
		);

		return $stats ?: array(
		  'total_orders' => 0,
		  'orders_with_users' => 0,
		  'orders_without_users' => 0
		);
	}

	/**
	 * Adaugă pagina admin
	 */
	public function add_admin_menu_page() {
		add_management_page(
		  'Actualizare ID Clienți - AJAX',
		  'Actualizare ID Clienți',
		  'manage_options',
		  'update-customer-ids-ajax',
		  array($this, 'render_admin_page')
		);
	}

	/**
	 * Încarcă scripturile pentru admin
	 */
	public function enqueue_admin_scripts($hook) {
		if ($hook !== 'tools_page_update-customer-ids-ajax') {
			return;
		}

		// Înregistrează scriptul inline în loc de fișier extern
		wp_enqueue_script('jquery');

		wp_localize_script('jquery', 'ordersProcessorAjax', array(
		  'ajaxUrl' => admin_url('admin-ajax.php'),
		  'nonce' => wp_create_nonce('process_orders_nonce'),
		  'batchSize' => $this->batch_size
		));

		// CSS pentru styling
		wp_add_inline_style('wp-admin', '
            .progress-container {
                background: #f1f1f1;
                border-radius: 4px;
                padding: 3px;
                margin: 10px 0;
            }
            .progress-bar {
                background: #0073aa;
                height: 20px;
                border-radius: 2px;
                transition: width 0.3s ease;
                color: white;
                text-align: center;
                line-height: 20px;
                font-size: 12px;
            }
            .processing-status {
                margin: 10px 0;
                padding: 10px;
                border-left: 4px solid #0073aa;
                background: #f9f9f9;
            }
            .status-completed {
                border-left-color: #46b450;
            }
            .status-error {
                border-left-color: #dc3232;
            }
        ');
	}

	/**
	 * Randează pagina admin
	 */
	public function render_admin_page() {
		$stats = $this->get_processing_stats();
		?>
		<div class="wrap">
			<h1>Actualizare ID-uri Clienți - Progres în Timp Real</h1>

			<div class="card" style="max-width: 800px;">
				<h2>Statistici Actuale</h2>
				<table class="widefat" id="stats-table">
					<tr>
						<th>Total comenzi</th>
						<td id="total-orders"><?php echo number_format($stats['total_orders']); ?></td>
					</tr>
					<tr>
						<th>Comenzi cu utilizatori</th>
						<td id="orders-with-users"><?php echo number_format($stats['orders_with_users']); ?></td>
					</tr>
					<tr>
						<th>Comenzi fără utilizatori</th>
						<td id="orders-without-users"><?php echo number_format($stats['orders_without_users']); ?></td>
					</tr>
					<tr>
						<th>Progres</th>
						<td id="progress-percentage">
							<?php
							$progress = $stats['total_orders'] > 0 ?
							  round(($stats['orders_with_users'] / $stats['total_orders']) * 100, 2) : 0;
							echo $progress . '%';
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="card" style="max-width: 800px; margin-top: 20px;">
				<h2>Procesare cu Progres Live</h2>

				<div class="progress-container">
					<div class="progress-bar" id="progress-bar" style="width: 0%;">0%</div>
				</div>

				<div class="processing-status" id="processing-status" style="display: none;">
					<strong>Status:</strong> <span id="status-text">Inactiv</span>
				</div>

				<div id="processing-log" style="margin-top: 20px; max-height: 200px; overflow-y: auto; background: #f9f9f9; padding: 10px; border: 1px solid #ddd; display: none;">
					<h4>Log procesare:</h4>
					<div id="log-content"></div>
				</div>

				<p>
					<button type="button" class="button button-primary" id="start-processing">
						Începe Procesarea
					</button>
					<button type="button" class="button" id="stop-processing" style="display: none;">
						Oprește Procesarea
					</button>
					<button type="button" class="button" id="refresh-stats">
						Reîmprospătează Statistici
					</button>
				</p>

				<p class="description">
					Procesarea se va executa în loturi de <?php echo $this->batch_size; ?> comenzi.
					Progresul va fi afișat în timp real.
				</p>
			</div>
		</div>

		<script>
            jQuery(document).ready(function($) {
                let processing = false;
                let totalToProcess = 0;
                let totalProcessed = 0;

                // Elemente DOM
                const $startBtn = $('#start-processing');
                const $stopBtn = $('#stop-processing');
                const $refreshBtn = $('#refresh-stats');
                const $progressBar = $('#progress-bar');
                const $statusDiv = $('#processing-status');
                const $statusText = $('#status-text');
                const $logDiv = $('#processing-log');
                const $logContent = $('#log-content');

                // Event handlers
                $startBtn.on('click', startProcessing);
                $stopBtn.on('click', stopProcessing);
                $refreshBtn.on('click', refreshStats);

                function startProcessing() {
                    if (processing) return;

                    processing = true;
                    totalProcessed = 0;

                    $startBtn.hide();
                    $stopBtn.show();
                    $statusDiv.show().removeClass('status-completed status-error');
                    $logDiv.show();
                    $logContent.empty();

                    updateStatus('Începe procesarea...', 'info');
                    addToLog('=== Începe procesarea ===');

                    // Obține statistici inițiale
                    refreshStats().then(() => {
                        totalToProcess = parseInt($('#orders-without-users').text().replace(/,/g, ''));
                        if (totalToProcess === 0) {
                            completeProcessing('Nu există comenzi de procesat!');
                            return;
                        }
                        processNextBatch();
                    });
                }

                function stopProcessing() {
                    processing = false;
                    $startBtn.show();
                    $stopBtn.hide();
                    updateStatus('Procesare oprită de utilizator', 'warning');
                    addToLog('=== Procesare oprită ===');
                }

                function processNextBatch() {
                    if (!processing) return;

                    updateStatus('Procesează loturi...', 'info');

                    $.ajax({
                        url: ordersProcessorAjax.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'process_orders_batch',
                            nonce: ordersProcessorAjax.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                const data = response.data;
                                totalProcessed += data.updated;

                                // Actualizează progresul
                                const progress = totalToProcess > 0 ?
                                    Math.min(100, (totalProcessed / totalToProcess) * 100) : 0;
                                updateProgress(progress);

                                // Adaugă în log
                                addToLog(`Lot: ${data.updated} actualizate, ${data.errors} erori din ${data.total} total`);

                                if (data.completed) {
                                    completeProcessing('Procesare completată cu succes!');
                                } else {
                                    // Continuă cu următorul lot după o pauză scurtă
                                    setTimeout(() => {
                                        if (processing) {
                                            processNextBatch();
                                        }
                                    }, 500);
                                }

                                // Reîmprospătează statisticile
                                refreshStats();
                            } else {
                                handleError('Eroare în procesare: ' + response.data);
                            }
                        },
                        error: function(xhr, status, error) {
                            handleError('Eroare AJAX: ' + error);
                        }
                    });
                }

                function completeProcessing(message) {
                    processing = false;
                    $startBtn.show();
                    $stopBtn.hide();
                    $statusDiv.addClass('status-completed');
                    updateStatus(message, 'success');
                    addToLog('=== ' + message + ' ===');
                    updateProgress(100);
                    refreshStats();
                }

                function handleError(message) {
                    processing = false;
                    $startBtn.show();
                    $stopBtn.hide();
                    $statusDiv.addClass('status-error');
                    updateStatus(message, 'error');
                    addToLog('EROARE: ' + message);
                }

                function updateStatus(text, type) {
                    $statusText.text(text);
                    console.log('[Orders Processor] ' + text);
                }

                function updateProgress(percentage) {
                    const roundedPercentage = Math.round(percentage);
                    $progressBar.css('width', percentage + '%')
                        .text(roundedPercentage + '%');
                }

                function addToLog(message) {
                    const timestamp = new Date().toLocaleTimeString();
                    $logContent.append(`<div>[${timestamp}] ${message}</div>`);
                    $logContent.scrollTop($logContent[0].scrollHeight);
                }

                function refreshStats() {
                    return $.ajax({
                        url: ordersProcessorAjax.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'get_orders_stats',
                            nonce: ordersProcessorAjax.nonce
                        },
                        success: function(response) {
                            if (response.success) {
                                const stats = response.data;
                                $('#total-orders').text(parseInt(stats.total_orders).toLocaleString());
                                $('#orders-with-users').text(parseInt(stats.orders_with_users).toLocaleString());
                                $('#orders-without-users').text(parseInt(stats.orders_without_users).toLocaleString());

                                const progress = stats.total_orders > 0 ?
                                    ((stats.orders_with_users / stats.total_orders) * 100).toFixed(2) : 0;
                                $('#progress-percentage').text(progress + '%');
                            }
                        }
                    });
                }

                // Reîmprospătează statisticile la încărcare
                refreshStats();
            });
		</script>
		<?php
	}
}

// Inițializează procesorul AJAX
new CustomOrdersProcessorAjax();