<?php
/**
 * Shutter Configurator Class
 * 
 * Handles the main logic for the shutter configurator functionality
 * with proper WordPress/WooCommerce integration, security, and performance.
 * 
 * @package ShutterModule
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Shutter_Configurator {
    
    /**
     * Cache group for transients
     */
    const CACHE_GROUP = 'shutter_configurator';
    
    /**
     * Cache expiration time (1 hour)
     */
    const CACHE_EXPIRATION = 3600;
    
    /**
     * Current user ID
     */
    private $user_id;
    
    /**
     * Current product ID
     */
    private $product_id;
    
    /**
     * Order edit ID
     */
    private $order_edit;
    
    /**
     * Product meta data
     */
    private $product_meta = array();
    
    /**
     * Cart data
     */
    private $cart_data = array();
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialize the configurator
     */
    private function init() {
        $this->set_user_id();
        $this->set_product_id();
        $this->set_order_edit();
        $this->load_product_meta();
        $this->load_cart_data();
        
        // Add WordPress hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_update_shutter_config', array($this, 'handle_ajax_update'));
        add_action('wp_ajax_nopriv_update_shutter_config', array($this, 'handle_ajax_update'));
    }
    
    /**
     * Set the current user ID with validation
     */
    private function set_user_id() {
        if (isset($_GET['cust_id']) && is_numeric($_GET['cust_id'])) {
            $this->user_id = absint($_GET['cust_id']);
        } else {
            $this->user_id = get_current_user_id();
        }
        
        // Validate user exists and has proper permissions
        if (!$this->user_id || !user_can($this->user_id, 'read')) {
            wp_die(__('Unauthorized access.', 'shutter-module'));
        }
    }
    
    /**
     * Set the product ID with validation
     */
    private function set_product_id() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            // Decode the product ID (appears to be encoded)
            $this->product_id = absint($_GET['id']) / 1498765 / 33;
            
            // Validate product exists
            if (!get_post($this->product_id)) {
                wp_die(__('Invalid product ID.', 'shutter-module'));
            }
        }
    }
    
    /**
     * Set the order edit ID with validation
     */
    private function set_order_edit() {
        if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
            $this->order_edit = absint($_GET['order_id']) / 1498765 / 33;
        }
    }
    
    /**
     * Load all product meta data at once for performance
     */
    private function load_product_meta() {
        if (!$this->product_id) {
            return;
        }
        
        // Check cache first
        $cache_key = 'product_meta_' . $this->product_id;
        $cached_meta = get_transient($cache_key);
        
        if (false !== $cached_meta) {
            $this->product_meta = $cached_meta;
            return;
        }
        
        // Load all meta at once instead of multiple get_post_meta calls
        $all_meta = get_post_meta($this->product_id);
        
        // Process and sanitize meta data
        $processed_meta = array();
        foreach ($all_meta as $key => $value) {
            $processed_meta[$key] = is_array($value) && count($value) === 1 ? $value[0] : $value;
        }
        
        $this->product_meta = $processed_meta;
        
        // Cache the result
        set_transient($cache_key, $this->product_meta, self::CACHE_EXPIRATION);
    }
    
    /**
     * Load cart data with proper WooCommerce integration
     */
    private function load_cart_data() {
        if (!function_exists('WC') || !WC()->cart) {
            return;
        }
        
        $cart = WC()->cart->get_cart();
        $this->cart_data = array(
            'items' => array(),
            'total_items' => count($cart),
            'cart_name' => $this->get_cart_name()
        );
        
        foreach ($cart as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $this->cart_data['items'][] = array(
                'product_id' => $product_id,
                'quantity' => $cart_item['quantity'],
                'room_name' => get_post_meta($product_id, 'property_room_other', true)
            );
        }
    }
    
    /**
     * Get cart name from user meta
     */
    private function get_cart_name() {
        $meta_key = '_woocom_multisession';
        $session_data = get_user_meta($this->user_id, $meta_key, true);
        
        if (empty($session_data) || !is_array($session_data)) {
            return '';
        }
        
        $carts = isset($session_data['carts']) ? $session_data['carts'] : array();
        $customer_id = isset($session_data['customer_id']) ? $session_data['customer_id'] : '';
        
        return isset($carts[$customer_id]['name']) ? sanitize_text_field($carts[$customer_id]['name']) : '';
    }
    
    /**
     * Get property value with fallback
     */
    public function get_property($key, $default = '') {
        return isset($this->product_meta[$key]) ? $this->product_meta[$key] : $default;
    }
    
    /**
     * Get all properties for a specific category (g, t, c, b)
     */
    public function get_category_properties($category) {
        $properties = array();
        $counter_key = 'counter_' . $category;
        $count = $this->get_property($counter_key, 0);
        
        for ($i = 1; $i <= $count; $i++) {
            $prop_key = 'property_' . $category . $i;
            $properties[$prop_key] = $this->get_property($prop_key);
        }
        
        return $properties;
    }
    
    /**
     * Get material options
     */
    public function get_material_options() {
        $materials = array(
            '138' => __('Wood', 'shutter-module'),
            '139' => __('Vinyl', 'shutter-module'),
            '140' => __('Composite', 'shutter-module'),
            '141' => __('Aluminum', 'shutter-module')
        );
        
        return apply_filters('shutter_material_options', $materials);
    }
    
    /**
     * Get style options
     */
    public function get_style_options() {
        $styles = array(
            '27' => array(
                'name' => __('ALU Panel Only', 'shutter-module'),
                'code' => 'fullheight',
                'image' => 'alu-panel-only.png',
                'visible' => false
            ),
            '28' => array(
                'name' => __('ALU Fixed Shutter', 'shutter-module'),
                'code' => 'fullheight', 
                'image' => 'alu-fixed-shutter.png',
                'visible' => false
            ),
            '29' => array(
                'name' => __('Full Height', 'shutter-module'),
                'code' => 'fullheight',
                'image' => 'Full-Height.png',
                'visible' => true
            ),
            '30' => array(
                'name' => __('Café Style', 'shutter-module'),
                'code' => 'cafe',
                'image' => 'Cafe-Style.png',
                'visible' => false
            ),
            '31' => array(
                'name' => __('Tier-on-Tier', 'shutter-module'),
                'code' => 'tot',
                'image' => 'Tier-On-Tier.png',
                'visible' => false
            ),
            '32' => array(
                'name' => __('Bay Window', 'shutter-module'),
                'code' => 'bay',
                'image' => 'Bay-Window.png',
                'visible' => false
            )
        );
        
        return apply_filters('shutter_style_options', $styles);
    }
    
    /**
     * Get current selected values for form
     */
    public function get_form_values() {
        $values = array(
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'order_edit' => $this->order_edit,
            'cart_name' => $this->cart_data['cart_name'],
            'material' => $this->get_property('property_material', '138'),
            'style' => $this->get_property('property_style', '29'),
            'room_other' => $this->get_property('property_room_other'),
            'frame_type' => $this->get_property('property_frametype'),
            'frame_left' => $this->get_property('property_frameleft'),
            'frame_right' => $this->get_property('property_frameright'),
            'frame_top' => $this->get_property('property_frametop'),
            'frame_bottom' => $this->get_property('property_framebottom'),
            'built_out' => $this->get_property('property_builtout'),
            'stile' => $this->get_property('property_stile'),
            'attachment' => $this->get_property('attachment'),
            'attachment_draw' => $this->get_property('attachmentDraw'),
            'comments_customer' => $this->get_property('comments_customer')
        );
        
        // Add category properties
        foreach (array('g', 't', 'c', 'b') as $category) {
            $values['properties_' . $category] = $this->get_category_properties($category);
        }
        
        return $values;
    }
    
    /**
     * Validate form data
     */
    public function validate_form_data($data) {
        $errors = array();
        
        // Required fields validation
        if (empty($data['property_room_other'])) {
            $errors[] = __('Room name is required.', 'shutter-module');
        }
        
        if (empty($data['property_material']) || !array_key_exists($data['property_material'], $this->get_material_options())) {
            $errors[] = __('Valid material selection is required.', 'shutter-module');
        }
        
        if (empty($data['property_style']) || !array_key_exists($data['property_style'], $this->get_style_options())) {
            $errors[] = __('Valid style selection is required.', 'shutter-module');
        }
        
        return $errors;
    }
    
    /**
     * Save configurator data
     */
    public function save_configuration($data) {
        // Validate nonce
        if (!wp_verify_nonce($data['_wpnonce'], 'shutter_configurator_save')) {
            return new WP_Error('invalid_nonce', __('Security check failed.', 'shutter-module'));
        }
        
        // Validate form data
        $validation_errors = $this->validate_form_data($data);
        if (!empty($validation_errors)) {
            return new WP_Error('validation_failed', implode('<br>', $validation_errors));
        }
        
        // Save product meta
        foreach ($data as $key => $value) {
            if (strpos($key, 'property_') === 0 || in_array($key, array('attachment', 'attachmentDraw', 'comments_customer'))) {
                update_post_meta($this->product_id, $key, sanitize_text_field($value));
            }
        }
        
        // Clear cache
        delete_transient('product_meta_' . $this->product_id);
        
        // Trigger action for extensions
        do_action('shutter_configuration_saved', $this->product_id, $data);
        
        return true;
    }
    
    /**
     * Handle AJAX updates
     */
    public function handle_ajax_update() {
        try {
            $result = $this->save_configuration($_POST);
            
            if (is_wp_error($result)) {
                wp_send_json_error($result->get_error_message());
            }
            
            wp_send_json_success(__('Configuration updated successfully.', 'shutter-module'));
            
        } catch (Exception $e) {
            error_log('Shutter Configurator Error: ' . $e->getMessage());
            wp_send_json_error(__('An error occurred while saving configuration.', 'shutter-module'));
        }
    }
    
    /**
     * Enqueue required scripts and styles
     */
    public function enqueue_scripts() {
        // Only enqueue on pages that need it
        if (!$this->is_configurator_page()) {
            return;
        }
        
        $plugin_url = plugin_dir_url(dirname(__FILE__));
        $version = filemtime(plugin_dir_path(dirname(__FILE__)) . 'js/configurator.js');
        
        // Enqueue styles
        wp_enqueue_style(
            'shutter-configurator',
            $plugin_url . 'css/configurator.css',
            array(),
            $version
        );
        
        // Enqueue scripts
        wp_enqueue_script(
            'shutter-configurator',
            $plugin_url . 'js/configurator.js',
            array('jquery', 'wp-util'),
            $version,
            true
        );
        
        // Localize script
        wp_localize_script('shutter-configurator', 'shutterConfig', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('shutter_configurator_save'),
            'strings' => array(
                'saving' => __('Saving...', 'shutter-module'),
                'saved' => __('Saved!', 'shutter-module'),
                'error' => __('Error occurred.', 'shutter-module')
            )
        ));
    }
    
    /**
     * Check if current page is a configurator page
     */
    private function is_configurator_page() {
        return isset($_GET['id']) || isset($_GET['order_id']) || is_page('shutter-configurator');
    }
    
    /**
     * Get dealer ID for current user
     */
    public function get_dealer_id() {
        return get_user_meta($this->user_id, 'company_parent', true);
    }
    
    /**
     * Check if user can edit customer orders
     */
    public function can_edit_customer() {
        return isset($_GET['order_edit_customer']) && $_GET['order_edit_customer'] === 'editable';
    }
    
    /**
     * Get product images URL
     */
    public function get_images_url() {
        return plugin_dir_url(dirname(__FILE__)) . 'imgs/';
    }
} 