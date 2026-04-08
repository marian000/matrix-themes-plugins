<?php
/**
 * Admin Order Number LF0 Prefix
 * Replaces WooCommerce default '#' prefix with 'LF0' in admin areas
 * So we don't need to modify WooCommerce core files
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Orders list table — output buffer pe coloana order_number
 *    Replace '#' cu 'LF0' în output-ul generat de WooCommerce
 *
 *    IMPORTANT: regex-ul trebuie sa fie ancorat pe `<strong>#` ca sa NU
 *    afecteze URL-urile din href-uri. `esc_url()` converteste `&` la
 *    `&#038;`, deci un regex /#(\d)/ ar strica URL-ul (ex: &#038; → &LF0038;).
 */
add_action('manage_shop_order_posts_custom_column', 'lf_order_number_ob_start', 1, 1);
function lf_order_number_ob_start($column) {
    if ($column === 'order_number') {
        ob_start();
    }
}

add_action('manage_shop_order_posts_custom_column', 'lf_order_number_ob_end', 999, 1);
function lf_order_number_ob_end($column) {
    if ($column === 'order_number') {
        $output = ob_get_clean();
        // Ancorat pe <strong># ca sa NU afecteze &#038; din URL-uri
        echo preg_replace('/<strong>#(\d)/', '<strong>LF0$1', $output, 1);
    }
}

/**
 * 2. Order edit page heading — gettext filter (doar pe ecranul shop_order)
 */
add_action('current_screen', 'lf_maybe_fix_order_translations');
function lf_maybe_fix_order_translations($screen) {
    if ($screen->id === 'shop_order') {
        add_filter('gettext', 'lf_fix_order_heading_text', 10, 3);
    }
}

function lf_fix_order_heading_text($translated, $text, $domain) {
    if ($domain !== 'woocommerce') {
        return $translated;
    }
    // Order edit page heading: "Order #24516 details" → "Order LF024516 details"
    if ($text === '%1$s #%2$s details') {
        return '%1$s LF0%2$s details';
    }
    // NOTE: Nu filtram '%1$s (#%2$s &ndash; %3$s)' pentru ca acel string e folosit
    // pentru customer dropdown unde %2$s este user ID, nu order number.
    return $translated;
}

/**
 * 3. Order preview modal header — JS fix
 */
add_action('admin_footer', 'lf_fix_order_preview_modal', 99);
function lf_fix_order_preview_modal() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'edit-shop_order') {
        return;
    }
    ?>
    <script>
    jQuery(function($) {
        $(document.body).on('wc_backbone_modal_loaded', function() {
            var $h1 = $('.wc-backbone-modal-header h1');
            if ($h1.length) {
                $h1.text($h1.text().replace(/#(\d)/, 'LF0$1'));
            }
        });
    });
    </script>
    <?php
}

/**
 * 4. Order preview modal template — gettext (doar pe edit-shop_order)
 */
add_action('current_screen', 'lf_maybe_fix_order_list_translations');
function lf_maybe_fix_order_list_translations($screen) {
    if ($screen->id === 'edit-shop_order') {
        add_filter('gettext', 'lf_fix_order_list_text', 10, 3);
    }
}

function lf_fix_order_list_text($translated, $text, $domain) {
    if ($domain !== 'woocommerce') {
        return $translated;
    }
    // Order preview modal template: "Order #%s" → "Order LF0%s"
    if ($text === 'Order #%s') {
        return 'Order LF0%s';
    }
    // NOTE: Nu filtram '%1$s (#%2$s &ndash; %3$s)' pentru customer filter dropdown
    // pentru ca %2$s este user ID, nu order number.
    return $translated;
}
