<?php
/**
 * QuickBooks Configuration
 * 
 * Centralized configuration for QuickBooks integration
 * Contains all configurable settings, email addresses, and mappings
 * 
 * @package Matrix_QuickBooks
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * QuickBooks Configuration Class
 */
class QuickBooks_Config {
    
    /**
     * Email Configuration
     */
    const BILLING_BCC_EMAIL = 'accounts@lifetimeshutters.com';
    
    /**
     * Special Customer IDs
     */
    const PERFECT_SHUTTER_CUSTOMER_ID = 274;
    
    /**
     * Material to QuickBooks ID Mapping
     * IMPORTANT: Do not modify these mappings without consulting QuickBooks admin
     */
    public static function get_materials_mapping() {
        return array(
            '137' => 24,  // Green
            '138' => 8,   // BiowoodPlus  
            '139' => 13,  // Supreme
            '187' => 71,  // Earth
            '188' => 67,  // Ecowood
            '5' => 93,    // EcowoodPlus
            '6' => 92,    // Biowood
            
            // POS Items
            '3098' => 73, // MultiPanel Display
            '1020' => 57, // Twin Sample Bags
            '1026' => 74, // Spare Parts Box
            '1030' => 76, // Painted Color Swatches
            '1032' => 75, // Stained Color Samples
            '337' => 60,  // Other Color
            '104450' => 90,
            '112380' => 91,
        );
    }
    
    /**
     * POS Items to QuickBooks ID Mapping
     */
    public static function get_pos_items_mapping() {
        return array(
            '104450' => 90,
            '112380' => 91,
            '3098' => 73,   // MultiPanel Display
            '1020' => 57,   // Twin Sample Bags
            '17069' => 72,
            '1026' => 74,   // Spare Parts Box
            '17051' => 81,
            '1032' => 75,   // Stained Color Samples
            '1030' => 76,   // Painted Color Swatches
            '17057' => 79,
            '17061' => 77,
            '17059' => 78,
            '17065' => 82,
            '17063' => 83,
            '17067' => 80,
            '17277' => 58,
        );
    }
    
    /**
     * Material Names for QB Reference
     */
    public static function get_material_names() {
        return array(
            '24' => 'Green',
            '8' => 'Biowood', 
            '13' => 'Supreme',
            '71' => 'Earth',
            '67' => 'Ecowood',
            '93' => 'EcowoodPlus',
            '92' => 'Biowood',
        );
    }
    
    /**
     * Special Color Items
     */
    const OTHER_COLOR_PRODUCT_IDS = array(337, 72951);
    const OTHER_COLOR_UNIT_PRICE = 131.25;
    
    /**
     * Logging Configuration
     */
    public static function get_log_path() {
        $upload_dir = wp_upload_dir();
        $log_path = $upload_dir['basedir'] . '/quickbooks-logs';
        
        if (!file_exists($log_path)) {
            wp_mkdir_p($log_path);
        }
        
        return $log_path;
    }
    
    /**
     * Get customer email for billing
     * Handles special cases like Perfect Shutter
     */
    public static function get_customer_billing_email($customer_id, $user_email) {
        $dealer_id = get_user_meta($customer_id, 'company_parent', true);
        
        // Perfect Shutter special case
        if ($customer_id == self::PERFECT_SHUTTER_CUSTOMER_ID || $dealer_id == self::PERFECT_SHUTTER_CUSTOMER_ID) {
            $user_info = get_userdata(self::PERFECT_SHUTTER_CUSTOMER_ID);
            return $user_info->user_email;
        }
        
        return $user_email;
    }
}