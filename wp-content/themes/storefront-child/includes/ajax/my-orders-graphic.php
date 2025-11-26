<?php

// Handler AJAX pentru datele raportului
add_action('wp_ajax_get_reports_data', 'handle_get_reports_data_ajax');
add_action('wp_ajax_nopriv_get_reports_data', 'handle_get_reports_data_ajax');

function handle_get_reports_data_ajax() {
    // Verificare nonce
    if (!wp_verify_nonce($_POST['security'], 'reports_data_nonce')) {
        wp_die('Security check failed');
    }

    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in');
    }

    try {
        $user_id = get_current_user_id();
        $user_info = get_userdata($user_id);
        
        // Determine user orders based on role (exact logic from original)
        $users_orders = get_user_orders_by_role_ajax($user_id, $user_info->roles);
        
        // Get optimized monthly data
        $monthly_data = calculate_monthly_data_optimized_ajax($users_orders);
        
        // Get table data
        $table_data = get_reports_table_data_ajax($users_orders);
        
        // Generate categories for charts
        $categories = array();
        for ($i = 11; $i >= 0; $i--) {
            $categories[] = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
        }
        
        // Return data
        wp_send_json_success(array(
            'sqm_data' => $monthly_data['sqm'],
            'total_data' => $monthly_data['total'],
            'categories' => $categories,
            'table_html' => $table_data
        ));
        
    } catch (Exception $e) {
        error_log('Reports AJAX Error: ' . $e->getMessage());
        wp_send_json_error('Internal server error');
    }
}

// Helper function pentru determinarea users_orders
function get_user_orders_by_role_ajax($user_id, $roles) {
    if (in_array('salesman', $roles) || in_array('subscriber', $roles) || 
        (in_array('emplimited', $roles) && !in_array('dealer', $roles))) {
        return array($user_id);
    }
    
    if (in_array('employe', $roles) || in_array('senior_salesman', $roles)) {
        $dealer_id = get_user_meta($user_id, 'company_parent', true);
        if (!empty($dealer_id)) {
            $users_orders = get_user_meta($dealer_id, 'employees', true) ?: array();
            $users_orders[] = $dealer_id;
            return array_reverse($users_orders);
        }
    }
    
    if (in_array('dealer', $roles)) {
        $users_orders = get_user_meta($user_id, 'employees', true) ?: array();
        $users_orders[] = $user_id;
        return array_reverse($users_orders);
    }
    
    return array($user_id);
}

// Funcția optimizată pentru datele lunare
function calculate_monthly_data_optimized_ajax($users_orders) {
    if (empty($users_orders)) {
        return array(
            'sqm' => array_fill(0, 12, 0),
            'total' => array_fill(0, 12, 0)
        );
    }

    // Cache
    $cache_key = 'monthly_reports_' . md5(serialize($users_orders)) . '_' . date('Y-m-d-H');
    $cached_result = get_transient($cache_key);
    
    if ($cached_result !== false) {
        return $cached_result;
    }

    global $wpdb;
    $users_list = implode(',', array_map('intval', $users_orders));
    
    // Query optimizat
    $results = $wpdb->get_results("
        SELECT 
            DATE_FORMAT(p.post_date, '%Y-%m') as year_month,
            SUM(COALESCE(co.sqm, 0)) as total_sqm,
            SUM(COALESCE(co.subtotal_price, 0)) as total_amount
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->prefix}custom_orders co ON p.ID = co.idOrder
        WHERE p.post_author IN ({$users_list})
        AND p.post_type = 'shop_order'
        AND p.post_status IN ('wc-completed', 'wc-processing', 'wc-on-hold', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision')
        AND p.post_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(p.post_date, '%Y-%m')
        ORDER BY year_month
    ", ARRAY_A);

    // Procesare rezultate
    $sqm_map = array();
    $total_map = array();
    
    foreach ($results as $row) {
        $sqm_map[$row['year_month']] = (float)$row['total_sqm'];
        $total_map[$row['year_month']] = (float)$row['total_amount'];
    }
    
    // Construire arrays finale
    $sqm_data = array();
    $total_data = array();
    
    for ($i = 11; $i >= 0; $i--) {
        $month_key = date('Y-m', strtotime("-{$i} months"));
        $sqm_data[] = $sqm_map[$month_key] ?? 0;
        $total_data[] = $total_map[$month_key] ?? 0;
    }
    
    $result = array(
        'sqm' => $sqm_data,
        'total' => $total_data
    );
    
    // Cache pentru 1 oră
    set_transient($cache_key, $result, 3600);
    
    return $result;
}

// Funcție pentru tabelul cu date
function get_reports_table_data_ajax($users_orders) {
    // Implementează logica pentru tabel similar cu cea din template
    // Dar optimizată pentru AJAX
    
    // Pentru acum, returnez un placeholder
    ob_start();
    ?>
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th style="text-align:right">m2</th>
                <th style="text-align:right">Amount</th>
            </tr>
            <!-- Datele vor fi generate dinamic -->
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}