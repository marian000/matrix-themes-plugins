<?php
defined('ABSPATH') or die();

/**
 * AJAX Handler for Frame Type Statistics
 * Returns usage count for each frame type within a specified time period
 */

add_action('wp_ajax_get_frametype_statistics', 'get_frametype_statistics_callback');

function get_frametype_statistics_callback() {
    global $wpdb;

    // Get time period from AJAX request
    $period = isset($_POST['period']) ? sanitize_text_field($_POST['period']) : '1_month';

    // Calculate start date based on period
    $date_start = '';
    switch ($period) {
        case '1_month':
            $date_start = date('Y-m-d', strtotime('-1 month'));
            break;
        case '3_months':
            $date_start = date('Y-m-d', strtotime('-3 months'));
            break;
        case '6_months':
            $date_start = date('Y-m-d', strtotime('-6 months'));
            break;
        case '12_months':
            $date_start = date('Y-m-d', strtotime('-12 months'));
            break;
        case 'current_year':
            $date_start = date('Y-01-01');
            break;
        default:
            $date_start = date('Y-m-d', strtotime('-1 month'));
    }

    // Frame types mapping from info.md
    $frametypes_map = array(
        '291' => array('name' => 'U-Channel', 'category' => 'Special'),
        '171' => array('name' => 'P4028X', 'category' => 'PVC'),
        '307' => array('name' => '4008A', 'category' => 'Basswood'),
        '310' => array('name' => '4008B', 'category' => 'Basswood'),
        '313' => array('name' => '4008C', 'category' => 'Basswood'),
        '420' => array('name' => '4108C', 'category' => 'Basswood'),
        '353' => array('name' => '4008T', 'category' => 'Basswood'),
        '333' => array('name' => '4028B', 'category' => 'Basswood'),
        '306' => array('name' => '4007A', 'category' => 'Basswood'),
        '309' => array('name' => '4007B', 'category' => 'Basswood'),
        '312' => array('name' => '4007C', 'category' => 'Basswood'),
        '305' => array('name' => '4001A', 'category' => 'Basswood'),
        '308' => array('name' => '4001B', 'category' => 'Basswood'),
        '290' => array('name' => '4011B', 'category' => 'Basswood'),
        '311' => array('name' => '4001C', 'category' => 'Basswood'),
        '332' => array('name' => '4022B', 'category' => 'Basswood'),
        '142' => array('name' => '4009', 'category' => 'Basswood'),
        '316' => array('name' => '4013', 'category' => 'Basswood'),
        '317' => array('name' => '4014', 'category' => 'Basswood'),
        '352' => array('name' => '4024', 'category' => 'Basswood'),
        '314' => array('name' => '4003', 'category' => 'Basswood'),
        '315' => array('name' => '4004', 'category' => 'Basswood'),
        '351' => array('name' => 'P4022B', 'category' => 'PVC'),
        '321' => array('name' => 'P4008H', 'category' => 'PVC'),
        '318' => array('name' => 'P4028B', 'category' => 'PVC'),
        '330' => array('name' => 'P4008S', 'category' => 'PVC'),
        '322' => array('name' => 'P4008T', 'category' => 'PVC'),
        '319' => array('name' => 'P4008W', 'category' => 'PVC'),
        '331' => array('name' => 'P4007A', 'category' => 'PVC'),
        '320' => array('name' => 'P4001N', 'category' => 'PVC'),
        '300' => array('name' => 'A4001', 'category' => 'Aluminium'),
        '325' => array('name' => 'P4013', 'category' => 'PVC'),
        '327' => array('name' => 'P4033', 'category' => 'PVC'),
        '289' => array('name' => 'P4023B', 'category' => 'PVC'),
        '328' => array('name' => 'P4043', 'category' => 'PVC'),
        '324' => array('name' => 'P4073', 'category' => 'PVC'),
        '304' => array('name' => 'P4083', 'category' => 'PVC'),
        '329' => array('name' => 'P4014', 'category' => 'PVC'),
        '303' => array('name' => 'P4009', 'category' => 'PVC'),
        '302' => array('name' => 'A4027', 'category' => 'Aluminium'),
        '301' => array('name' => 'A4002', 'category' => 'Aluminium'),
        '144' => array('name' => 'Bottom M Track', 'category' => 'Track'),
        '143' => array('name' => 'Track in Board', 'category' => 'Track')
    );

    // Query to get frametype usage from order items
    // WooCommerce stores order items in wp_woocommerce_order_items table
    // Each order item references a product_id via wp_woocommerce_order_itemmeta
    // The property_frametype is stored in wp_postmeta for the product_id
    $query = $wpdb->prepare(
        "SELECT
            pm.meta_value as frametype_id,
            COUNT(*) as usage_count
        FROM {$wpdb->prefix}woocommerce_order_items oi
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta oim ON oi.order_item_id = oim.order_item_id
        INNER JOIN {$wpdb->posts} p ON oi.order_id = p.ID
        INNER JOIN {$wpdb->postmeta} pm ON oim.meta_value = pm.post_id
        WHERE oi.order_item_type = 'line_item'
            AND oim.meta_key = '_product_id'
            AND p.post_type = 'shop_order'
            AND p.post_status NOT IN ('trash', 'wc-cancelled', 'wc-failed')
            AND p.post_date >= %s
            AND pm.meta_key = 'property_frametype'
            AND pm.meta_value != ''
        GROUP BY pm.meta_value
        ORDER BY usage_count DESC",
        $date_start
    );

    $results = $wpdb->get_results($query);

    // Debug: Log the query and results
    error_log('Frame Type Stats Query: ' . $query);
    error_log('Frame Type Stats Results Count: ' . count($results));
    if ($wpdb->last_error) {
        error_log('Frame Type Stats SQL Error: ' . $wpdb->last_error);
    }

    // Prepare response data
    $statistics = array();
    $total_count = 0;
    $category_totals = array(
        'Basswood' => 0,
        'PVC' => 0,
        'Aluminium' => 0,
        'Track' => 0,
        'Special' => 0
    );

    foreach ($results as $row) {
        $frametype_id = $row->frametype_id;
        $usage_count = (int) $row->usage_count;

        if (isset($frametypes_map[$frametype_id])) {
            $frametype_data = $frametypes_map[$frametype_id];

            $statistics[] = array(
                'id' => $frametype_id,
                'name' => $frametype_data['name'],
                'category' => $frametype_data['category'],
                'usage_count' => $usage_count
            );

            $total_count += $usage_count;
            $category_totals[$frametype_data['category']] += $usage_count;
        } else {
            // Unknown frametype
            $statistics[] = array(
                'id' => $frametype_id,
                'name' => 'Unknown (' . $frametype_id . ')',
                'category' => 'Unknown',
                'usage_count' => $usage_count
            );

            $total_count += $usage_count;
        }
    }

    // Prepare response
    $response = array(
        'success' => true,
        'period' => $period,
        'date_start' => $date_start,
        'total_count' => $total_count,
        'category_totals' => $category_totals,
        'statistics' => $statistics,
        'debug' => array(
            'query' => $query,
            'raw_results_count' => count($results),
            'last_error' => $wpdb->last_error
        )
    );

    wp_send_json($response);
}
