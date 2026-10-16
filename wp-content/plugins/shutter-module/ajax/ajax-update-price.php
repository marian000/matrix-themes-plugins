<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

// Security: only administrators may change the global (post ID 1) prices.
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized', '', array('response' => 403));
}

if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'matrix_global_pricing_nonce')) {
    wp_die('Invalid nonce', '', array('response' => 403));
}

if (!isset($_POST['attributes'])) {
    wp_die('No data', '', array('response' => 400));
}

// Key lists and price validation live in the theme (loaded by wp-load.php through functions.php).
if (!function_exists('matrix_get_global_price_form_keys') || !function_exists('matrix_normalize_price_input')) {
    foreach (array('/includes/material-pricing-keys.php', '/includes/pricing-meta-keys.php') as $matrix_keys_file) {
        if (file_exists(get_stylesheet_directory() . $matrix_keys_file)) {
            require_once get_stylesheet_directory() . $matrix_keys_file;
        }
    }
}
if (!function_exists('matrix_get_global_price_form_keys') || !function_exists('matrix_normalize_price_input')) {
    wp_die('Pricing key list not available', '', array('response' => 500));
}

//print_r($_POST);
parse_str($_POST['attributes'], $attributes);

// Only generic price keys may be written here (no mat_* keys, no attributes_array).
$allowed_keys = matrix_get_global_price_form_keys();
$saved = 0;
$rejected = array();
$ignored = array();

foreach ($attributes as $key => $val) {
    if ($key != 'shutter-table-settings_length') {
        //echo 'key: '.$key;

        $part = explode("-", $key, 2);
        // $part[0] price, $part[1] property
        if (count($part) < 2) {
            continue; // train_price / panel_width_price are saved below
        }

        if (!in_array($part[1], $allowed_keys, true)) {
            $ignored[] = $part[1];
            continue;
        }

        $val = matrix_normalize_price_input($val);
        if ($val === null) {
            $rejected[] = $part[1];
            continue;
        }

        update_post_meta(1, $part[1], $val);
        $saved++;
    }
}

foreach (array('train_price', 'panel_width_price') as $single_key) {
    if (isset($attributes[$single_key])) {
        $val = matrix_normalize_price_input($attributes[$single_key]);
        if ($val === null) {
            $rejected[] = $single_key;
        } else {
            update_post_meta(1, $single_key, $val);
            $saved++;
        }
    }
}

echo 'Saved ' . $saved . ' prices.';
if (!empty($rejected)) {
    echo ' Not saved (must be a number of 0 or more): ' . implode(', ', $rejected) . '.';
}
if (!empty($ignored)) {
    echo ' Ignored (not a global price key): ' . count($ignored) . '.';
}



