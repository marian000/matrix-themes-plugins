<?php
/**
 * One-time migration script: Copy Supreme prices to Basswood for all users
 *
 * Run this script once by visiting:
 * /wp-content/themes/storefront-child/migrate-supreme-to-basswood.php?run=1
 *
 * After running, DELETE this file for security.
 */

// Load WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Security check - only admins can run this
if (!current_user_can('administrator')) {
    die('Access denied. You must be an administrator to run this script.');
}

// Check if we should run
if (!isset($_GET['run']) || $_GET['run'] !== '1') {
    echo '<h1>Supreme to Basswood Migration Script</h1>';
    echo '<p>This script will copy Supreme prices to Basswood for all users who have Supreme but not Basswood.</p>';
    echo '<p><strong>Meta fields to migrate:</strong></p>';
    echo '<ul>';
    echo '<li>Supreme → Basswood</li>';
    echo '<li>Supreme_tax → Basswood_tax</li>';
    echo '<li>Supreme-dolar → Basswood-dolar</li>';
    echo '<li>SupremePlus → BasswoodPlus</li>';
    echo '<li>SupremePlus_tax → BasswoodPlus_tax</li>';
    echo '<li>SupremePlus-dolar → BasswoodPlus-dolar</li>';
    echo '<li>show_supreme → show_basswood</li>';
    echo '</ul>';
    echo '<p><a href="?run=1" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none; border-radius: 3px;">Run Migration</a></p>';
    echo '<p style="color: red;"><strong>Warning:</strong> Delete this file after running!</p>';
    exit;
}

// Get all users
$users = get_users(array('fields' => 'ID'));

$migrated_count = 0;
$skipped_count = 0;
$results = array();

foreach ($users as $user_id) {
    $user_data = get_userdata($user_id);
    $user_login = $user_data->user_login;

    $user_migrated = false;
    $user_results = array();

    // Migration 1: Supreme -> Basswood
    $supreme = get_user_meta($user_id, 'Supreme', true);
    $basswood = get_user_meta($user_id, 'Basswood', true);

    if (!empty($supreme) && empty($basswood)) {
        update_user_meta($user_id, 'Basswood', $supreme);
        $user_results[] = "Basswood: {$supreme}";
        $user_migrated = true;
    }

    // Migration 2: Supreme_tax -> Basswood_tax
    $supreme_tax = get_user_meta($user_id, 'Supreme_tax', true);
    $basswood_tax = get_user_meta($user_id, 'Basswood_tax', true);

    if (!empty($supreme_tax) && empty($basswood_tax)) {
        update_user_meta($user_id, 'Basswood_tax', $supreme_tax);
        $user_results[] = "Basswood_tax: {$supreme_tax}";
        $user_migrated = true;
    }

    // Migration 3: Supreme-dolar -> Basswood-dolar
    $supreme_dolar = get_user_meta($user_id, 'Supreme-dolar', true);
    $basswood_dolar = get_user_meta($user_id, 'Basswood-dolar', true);

    if (!empty($supreme_dolar) && empty($basswood_dolar)) {
        update_user_meta($user_id, 'Basswood-dolar', $supreme_dolar);
        $user_results[] = "Basswood-dolar: {$supreme_dolar}";
        $user_migrated = true;
    }

    // Migration 4: SupremePlus -> BasswoodPlus
    $supremeplus = get_user_meta($user_id, 'SupremePlus', true);
    $basswoodplus = get_user_meta($user_id, 'BasswoodPlus', true);

    if (!empty($supremeplus) && empty($basswoodplus)) {
        update_user_meta($user_id, 'BasswoodPlus', $supremeplus);
        $user_results[] = "BasswoodPlus: {$supremeplus}";
        $user_migrated = true;
    }

    // Migration 5: SupremePlus_tax -> BasswoodPlus_tax
    $supremeplus_tax = get_user_meta($user_id, 'SupremePlus_tax', true);
    $basswoodplus_tax = get_user_meta($user_id, 'BasswoodPlus_tax', true);

    if (!empty($supremeplus_tax) && empty($basswoodplus_tax)) {
        update_user_meta($user_id, 'BasswoodPlus_tax', $supremeplus_tax);
        $user_results[] = "BasswoodPlus_tax: {$supremeplus_tax}";
        $user_migrated = true;
    }

    // Migration 6: SupremePlus-dolar -> BasswoodPlus-dolar
    $supremeplus_dolar = get_user_meta($user_id, 'SupremePlus-dolar', true);
    $basswoodplus_dolar = get_user_meta($user_id, 'BasswoodPlus-dolar', true);

    if (!empty($supremeplus_dolar) && empty($basswoodplus_dolar)) {
        update_user_meta($user_id, 'BasswoodPlus-dolar', $supremeplus_dolar);
        $user_results[] = "BasswoodPlus-dolar: {$supremeplus_dolar}";
        $user_migrated = true;
    }

    // Migration 7: show_supreme -> show_basswood
    $show_supreme = get_user_meta($user_id, 'show_supreme', true);
    $show_basswood = get_user_meta($user_id, 'show_basswood', true);

    if (!empty($show_supreme) && empty($show_basswood)) {
        update_user_meta($user_id, 'show_basswood', $show_supreme);
        $user_results[] = "show_basswood: {$show_supreme}";
        $user_migrated = true;
    }

    if ($user_migrated) {
        $migrated_count++;
        $results[] = array(
            'user_id' => $user_id,
            'user_login' => $user_login,
            'fields' => $user_results
        );
    } else {
        $skipped_count++;
    }
}

// Output results
echo '<h1>Migration Complete</h1>';
echo '<p><strong>Users migrated:</strong> ' . $migrated_count . '</p>';
echo '<p><strong>Users skipped:</strong> ' . $skipped_count . ' (no Supreme data or Basswood already set)</p>';

if (!empty($results)) {
    echo '<h2>Migration Details:</h2>';
    echo '<table border="1" cellpadding="5" cellspacing="0">';
    echo '<tr><th>User ID</th><th>Username</th><th>Fields Updated</th></tr>';

    foreach ($results as $result) {
        echo '<tr>';
        echo '<td>' . $result['user_id'] . '</td>';
        echo '<td>' . esc_html($result['user_login']) . '</td>';
        echo '<td>' . implode('<br>', $result['fields']) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
}

echo '<p style="color: red; margin-top: 20px;"><strong>IMPORTANT:</strong> Delete this file now for security!</p>';
echo '<p>File location: <code>/wp-content/themes/storefront-child/migrate-supreme-to-basswood.php</code></p>';
