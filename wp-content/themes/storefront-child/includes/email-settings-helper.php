<?php
/**
 * Email Settings Helper Functions
 *
 * Centralizează toate email-urile configurabile din WordPress options
 * cu fallback la valori default pentru compatibilitate backward.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Valori default pentru email-uri
 */
if (!defined('MATRIX_EMAIL_DEFAULTS')) {
    define('MATRIX_EMAIL_DEFAULTS', array(
        'admin'        => 'tudor@lifetimeshutters.com',
        'factory'      => 'mike@lifetimeshutters.com',
        'accounts_bcc' => 'accounts@lifetimeshutters.com',
        'from_order'   => 'order@lifetimeshutters.com',
        'from_service' => 'service@lifetimeshutters.co.uk',
    ));
}

/**
 * Obține un email din setări cu fallback la default
 *
 * @param string $type Tipul email-ului: admin, factory, accounts_bcc, from_order, from_service
 * @return string Email address
 */
if (!function_exists('get_matrix_email')) {
    function get_matrix_email($type) {
        $option_name = 'matrix_email_' . $type;
        $email = get_option($option_name);

        if (!empty($email)) {
            return sanitize_email($email);
        }

        $defaults = MATRIX_EMAIL_DEFAULTS;
        return isset($defaults[$type]) ? $defaults[$type] : '';
    }
}

/**
 * Obține From address formatat pentru wp_mail headers
 *
 * @param string $type Tipul: order sau service
 * @return string Format: "Name <email@domain.com>"
 */
if (!function_exists('get_matrix_from_address')) {
    function get_matrix_from_address($type) {
        $email = get_matrix_email('from_' . $type);

        $names = array(
            'order'   => 'Matrix-LifetimeShutters',
            'service' => 'Service LifetimeShutters',
        );

        $name = isset($names[$type]) ? $names[$type] : 'Matrix';
        return $name . ' <' . $email . '>';
    }
}

/**
 * Obține array de recipienți admin (admin + factory)
 *
 * @return array Lista de email-uri
 */
if (!function_exists('get_matrix_admin_recipients')) {
    function get_matrix_admin_recipients() {
        return array(
            get_matrix_email('admin'),
            get_matrix_email('factory'),
        );
    }
}

/**
 * Obține recipienți pentru China/Factory orders
 * Respectă modul Test/Production din setări
 *
 * @return array Lista de email-uri
 */
if (!function_exists('get_matrix_china_recipients')) {
    function get_matrix_china_recipients() {
        $send_to = get_option('mail_matrix_radio');

        if ($send_to == 'send_test') {
            $test_mails = get_option('test_mails_matrix');
            return array_filter(array_map('trim', explode(',', $test_mails)));
        }

        $china_mails = get_option('china_mails_matrix');
        return array_filter(array_map('trim', explode(',', $china_mails)));
    }
}

/**
 * Verifică dacă suntem în modul test
 *
 * @return bool
 */
if (!function_exists('is_matrix_test_mode')) {
    function is_matrix_test_mode() {
        return get_option('mail_matrix_radio') === 'send_test';
    }
}

/**
 * Obține recipienți respectând modul test/production
 *
 * @param array $production_recipients Email-uri pentru production
 * @return array Email-uri finale
 */
if (!function_exists('get_matrix_recipients')) {
    function get_matrix_recipients($production_recipients = array()) {
        if (is_matrix_test_mode()) {
            $test_mails = get_option('test_mails_matrix');
            return array_filter(array_map('trim', explode(',', $test_mails)));
        }

        return $production_recipients;
    }
}
