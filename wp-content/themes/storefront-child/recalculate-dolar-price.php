<?php
/**
 * Recalculate dolar_price for BasswoodPlus (material 139) products
 * where dolar_price = 0
 *
 * Uses user price if available, otherwise fallback to post ID 1
 */

require_once('../../../wp-load.php');

if (!is_user_logged_in() || !current_user_can('administrator')) {
    wp_die('Access denied. You must be logged in as administrator.');
}

if (!isset($_GET['run']) || $_GET['run'] !== '1') {
    wp_die('Add ?run=1 to URL to execute the script.');
}

// Functia dolarSum (copiata din ajax-prod.php)
function dolarSum($name_prop, $user_id = 0) {
    $result = 0;
    if ($user_id && (!empty(get_user_meta($user_id, $name_prop, true)) || (get_user_meta($user_id, $name_prop, true) > 0))) {
        $result = get_user_meta($user_id, $name_prop, true);
    } else {
        $result = get_post_meta(1, $name_prop, true);
    }
    return $result;
}

header('Content-Type: text/plain; charset=utf-8');

echo "=== Recalculating dolar_price for BasswoodPlus (material 139) where dolar_price = 0 ===\n\n";

// Show default price
$default_price = get_post_meta(1, 'BasswoodPlus-dolar', true);
echo "Default BasswoodPlus-dolar price (from post ID 1): $default_price\n\n";

// Gaseste toate produsele cu material 139 si dolar_price = 0
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'property_material',
            'value' => '139',
        ),
        array(
            'relation' => 'OR',
            array(
                'key' => 'dolar_price',
                'value' => '0',
            ),
            array(
                'key' => 'dolar_price',
                'value' => '',
            ),
            array(
                'key' => 'dolar_price',
                'compare' => 'NOT EXISTS',
            ),
        ),
    ),
);

$products = get_posts($args);
$updated = 0;
$log = [];

echo "Found " . count($products) . " products with material 139 and dolar_price = 0\n\n";

foreach ($products as $product) {
    $post_id = $product->ID;

    // Try to get user_id from order if exists
    $user_id = 0;
    $order_id = get_post_meta($post_id, 'order_id', true);

    if ($order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            $user_id = $order->get_user_id();
        }
    }

    // Ia valorile
    $sqm_value = floatval(get_post_meta($post_id, 'property_total', true));
    $old_dolar = floatval(get_post_meta($post_id, 'dolar_price', true));

    // Calculeaza noul pret (uses fallback to post ID 1 if no user)
    $price_per_sqm = dolarSum('BasswoodPlus-dolar', $user_id);
    $new_dolar = $sqm_value * $price_per_sqm;

    // Update doar daca difera
    if (abs($old_dolar - $new_dolar) > 0.01) {
        update_post_meta($post_id, 'dolar_price', floatval($new_dolar));
        $source = $user_id ? "User $user_id" : "Default (post 1)";
        $log[] = "Product $post_id: $old_dolar -> $new_dolar (SQM: $sqm_value, Price/SQM: $price_per_sqm, Source: $source)";
        $updated++;
    }
}

echo "=== Results ===\n";
echo "Updated: $updated products\n";
echo "Unchanged: " . (count($products) - $updated) . " products\n\n";

if (!empty($log)) {
    echo "=== Update Log ===\n";
    echo implode("\n", $log) . "\n";
}

echo "\n=== Done! DELETE THIS FILE NOW ===\n";
