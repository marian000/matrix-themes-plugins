<?php
/**
 * Shutter AJAX Handler Class
 * 
 * Handles all AJAX requests for the shutter configurator with proper
 * security, validation, and WordPress integration.
 * 
 * @package ShutterModule
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Shutter_Ajax {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->register_ajax_handlers();
    }
    
    /**
     * Register all AJAX handlers
     */
    private function register_ajax_handlers() {
        // Configuration update handlers
        add_action('wp_ajax_update_shutter_config', array($this, 'update_configuration'));
        add_action('wp_ajax_nopriv_update_shutter_config', array($this, 'update_configuration'));
        
        // Price calculation handlers
        add_action('wp_ajax_calculate_shutter_price', array($this, 'calculate_price'));
        add_action('wp_ajax_nopriv_calculate_shutter_price', array($this, 'calculate_price'));
        
        // File upload handlers
        add_action('wp_ajax_upload_shutter_attachment', array($this, 'handle_file_upload'));
        add_action('wp_ajax_nopriv_upload_shutter_attachment', array($this, 'handle_file_upload'));
        
        // Canvas drawing export
        add_action('wp_ajax_export_canvas_drawing', array($this, 'export_canvas_drawing'));
        add_action('wp_ajax_nopriv_export_canvas_drawing', array($this, 'export_canvas_drawing'));
        
        // Product validation
        add_action('wp_ajax_validate_shutter_config', array($this, 'validate_configuration'));
        add_action('wp_ajax_nopriv_validate_shutter_config', array($this, 'validate_configuration'));
        
        // Add to cart
        add_action('wp_ajax_add_shutter_to_cart', array($this, 'add_to_cart'));
        add_action('wp_ajax_nopriv_add_shutter_to_cart', array($this, 'add_to_cart'));
    }
    
    /**
     * Update shutter configuration
     */
    public function update_configuration() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            // Validate user permissions
            if (!current_user_can('edit_posts')) {
                throw new Exception(__('Insufficient permissions.', 'shutter-module'));
            }
            
            // Get and validate product ID
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
            if (!$product_id || !get_post($product_id)) {
                throw new Exception(__('Invalid product ID.', 'shutter-module'));
            }
            
            // Sanitize and validate form data
            $config_data = $this->sanitize_configuration_data($_POST);
            $validation_errors = $this->validate_configuration_data($config_data);
            
            if (!empty($validation_errors)) {
                throw new Exception(implode('<br>', $validation_errors));
            }
            
            // Save configuration
            $this->save_product_configuration($product_id, $config_data);
            
            // Calculate new price
            $new_price = $this->calculate_product_price($product_id, $config_data);
            
            wp_send_json_success(array(
                'message' => __('Configuration updated successfully.', 'shutter-module'),
                'price' => $new_price,
                'formatted_price' => wc_price($new_price)
            ));
            
        } catch (Exception $e) {
            error_log('Shutter Configuration Update Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Calculate shutter price
     */
    public function calculate_price() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
            $config_data = $this->sanitize_configuration_data($_POST);
            
            $price = $this->calculate_product_price($product_id, $config_data);
            
            wp_send_json_success(array(
                'price' => $price,
                'formatted_price' => wc_price($price),
                'currency_symbol' => get_woocommerce_currency_symbol()
            ));
            
        } catch (Exception $e) {
            error_log('Shutter Price Calculation Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Handle file upload for attachments
     */
    public function handle_file_upload() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            // Check if file was uploaded
            if (!isset($_FILES['attachment']) || $_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception(__('File upload failed.', 'shutter-module'));
            }
            
            // Validate file type
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'application/pdf');
            $file_type = $_FILES['attachment']['type'];
            
            if (!in_array($file_type, $allowed_types)) {
                throw new Exception(__('Invalid file type. Only images and PDF files are allowed.', 'shutter-module'));
            }
            
            // Use WordPress media handling
            $upload = wp_handle_upload($_FILES['attachment'], array('test_form' => false));
            
            if (isset($upload['error'])) {
                throw new Exception($upload['error']);
            }
            
            // Create attachment post
            $attachment_id = wp_insert_attachment(array(
                'post_title' => sanitize_file_name($_FILES['attachment']['name']),
                'post_content' => '',
                'post_status' => 'inherit',
                'post_mime_type' => $upload['type']
            ), $upload['file']);
            
            if (is_wp_error($attachment_id)) {
                throw new Exception(__('Failed to create attachment.', 'shutter-module'));
            }
            
            // Generate metadata
            $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
            wp_update_attachment_metadata($attachment_id, $metadata);
            
            wp_send_json_success(array(
                'attachment_id' => $attachment_id,
                'url' => $upload['url'],
                'filename' => basename($upload['file'])
            ));
            
        } catch (Exception $e) {
            error_log('Shutter File Upload Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Export canvas drawing as image
     */
    public function export_canvas_drawing() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            // Get image data
            $image_data = isset($_POST['imageSrc']) ? $_POST['imageSrc'] : '';
            $room_name = isset($_POST['roomName']) ? sanitize_text_field($_POST['roomName']) : 'drawing';
            
            if (empty($image_data)) {
                throw new Exception(__('No image data provided.', 'shutter-module'));
            }
            
            // Validate base64 image data
            if (strpos($image_data, 'data:image/') !== 0) {
                throw new Exception(__('Invalid image data format.', 'shutter-module'));
            }
            
            // Extract image data
            list($type, $data) = explode(';', $image_data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            
            if ($data === false) {
                throw new Exception(__('Failed to decode image data.', 'shutter-module'));
            }
            
            // Generate filename
            $filename = sprintf('shutter-drawing-%s-%s.png', 
                sanitize_file_name($room_name), 
                date('Y-m-d-H-i-s')
            );
            
            // Upload to WordPress uploads directory
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir['path'] . '/' . $filename;
            $file_url = $upload_dir['url'] . '/' . $filename;
            
            if (file_put_contents($file_path, $data) === false) {
                throw new Exception(__('Failed to save image file.', 'shutter-module'));
            }
            
            // Create attachment
            $attachment_id = wp_insert_attachment(array(
                'post_title' => $room_name . ' Drawing',
                'post_content' => '',
                'post_status' => 'inherit',
                'post_mime_type' => 'image/png'
            ), $file_path);
            
            if (is_wp_error($attachment_id)) {
                // If attachment creation fails, still return the file URL
                wp_send_json_success(array(
                    'url' => $file_url,
                    'filename' => $filename
                ));
            } else {
                // Generate metadata
                $metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
                wp_update_attachment_metadata($attachment_id, $metadata);
                
                wp_send_json_success(array(
                    'attachment_id' => $attachment_id,
                    'url' => $file_url,
                    'filename' => $filename
                ));
            }
            
        } catch (Exception $e) {
            error_log('Canvas Drawing Export Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Validate configuration data
     */
    public function validate_configuration() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            $config_data = $this->sanitize_configuration_data($_POST);
            $validation_errors = $this->validate_configuration_data($config_data);
            
            if (!empty($validation_errors)) {
                wp_send_json_error(array(
                    'errors' => $validation_errors,
                    'message' => __('Please correct the errors below.', 'shutter-module')
                ));
            }
            
            wp_send_json_success(array(
                'message' => __('Configuration is valid.', 'shutter-module')
            ));
            
        } catch (Exception $e) {
            error_log('Shutter Configuration Validation Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Add configured shutter to cart
     */
    public function add_to_cart() {
        try {
            // Verify nonce
            if (!wp_verify_nonce($_POST['nonce'], 'shutter_configurator_nonce')) {
                throw new Exception(__('Security check failed.', 'shutter-module'));
            }
            
            // Validate product ID
            $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
            if (!$product_id || !wc_get_product($product_id)) {
                throw new Exception(__('Invalid product.', 'shutter-module'));
            }
            
            // Get quantity
            $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;
            if ($quantity < 1) {
                $quantity = 1;
            }
            
            // Sanitize configuration data
            $config_data = $this->sanitize_configuration_data($_POST);
            
            // Validate configuration
            $validation_errors = $this->validate_configuration_data($config_data);
            if (!empty($validation_errors)) {
                throw new Exception(implode('<br>', $validation_errors));
            }
            
            // Save configuration to product
            $this->save_product_configuration($product_id, $config_data);
            
            // Add to cart
            $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
            
            if (!$cart_item_key) {
                throw new Exception(__('Failed to add product to cart.', 'shutter-module'));
            }
            
            wp_send_json_success(array(
                'message' => __('Product added to cart successfully.', 'shutter-module'),
                'cart_url' => wc_get_cart_url(),
                'cart_count' => WC()->cart->get_cart_contents_count()
            ));
            
        } catch (Exception $e) {
            error_log('Add to Cart Error: ' . $e->getMessage());
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Sanitize configuration data
     */
    private function sanitize_configuration_data($data) {
        $sanitized = array();
        
        // Define expected fields and their sanitization methods
        $field_map = array(
            'property_room_other' => 'sanitize_text_field',
            'property_material' => 'absint',
            'property_style' => 'absint',
            'property_frametype' => 'absint',
            'property_frameleft' => 'sanitize_text_field',
            'property_frameright' => 'sanitize_text_field',
            'property_frametop' => 'sanitize_text_field',
            'property_framebottom' => 'sanitize_text_field',
            'property_builtout' => 'sanitize_text_field',
            'property_stile' => 'sanitize_text_field',
            'comments_customer' => 'sanitize_textarea_field',
            'attachment' => 'esc_url_raw',
            'attachmentDraw' => 'esc_url_raw'
        );
        
        foreach ($field_map as $field => $sanitize_func) {
            if (isset($data[$field])) {
                $sanitized[$field] = call_user_func($sanitize_func, $data[$field]);
            }
        }
        
        // Handle dynamic properties (g1, g2, t1, t2, etc.)
        foreach ($data as $key => $value) {
            if (preg_match('/^property_[gtcb]\d+$/', $key)) {
                $sanitized[$key] = sanitize_text_field($value);
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Validate configuration data
     */
    private function validate_configuration_data($data) {
        $errors = array();
        
        // Required field validation
        if (empty($data['property_room_other'])) {
            $errors[] = __('Room name is required.', 'shutter-module');
        }
        
        if (empty($data['property_material'])) {
            $errors[] = __('Material selection is required.', 'shutter-module');
        }
        
        if (empty($data['property_style'])) {
            $errors[] = __('Style selection is required.', 'shutter-module');
        }
        
        // Validate material options
        $valid_materials = array('138', '139', '140', '141');
        if (!empty($data['property_material']) && !in_array((string)$data['property_material'], $valid_materials)) {
            $errors[] = __('Invalid material selection.', 'shutter-module');
        }
        
        // Validate style options
        $valid_styles = array('27', '28', '29', '30', '31', '32', '146', '147');
        if (!empty($data['property_style']) && !in_array((string)$data['property_style'], $valid_styles)) {
            $errors[] = __('Invalid style selection.', 'shutter-module');
        }
        
        return apply_filters('shutter_configuration_validation_errors', $errors, $data);
    }
    
    /**
     * Save product configuration
     */
    private function save_product_configuration($product_id, $config_data) {
        foreach ($config_data as $key => $value) {
            update_post_meta($product_id, $key, $value);
        }
        
        // Clear any cached data
        delete_transient('product_meta_' . $product_id);
        
        do_action('shutter_configuration_saved', $product_id, $config_data);
    }
    
    /**
     * Calculate product price based on configuration
     */
    private function calculate_product_price($product_id, $config_data) {
        // Base price
        $base_price = 100.00; // Default base price
        
        if ($product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $base_price = floatval($product->get_regular_price());
            }
        }
        
        $calculated_price = $base_price;
        
        // Material price modifiers
        $material_modifiers = array(
            '138' => 1.0,   // Wood - base price
            '139' => 0.8,   // Vinyl - 20% less
            '140' => 1.2,   // Composite - 20% more
            '141' => 1.5    // Aluminum - 50% more
        );
        
        if (isset($config_data['property_material']) && isset($material_modifiers[$config_data['property_material']])) {
            $calculated_price *= $material_modifiers[$config_data['property_material']];
        }
        
        // Style price modifiers
        $style_modifiers = array(
            '27' => 0.9,    // ALU Panel Only
            '28' => 1.1,    // ALU Fixed Shutter
            '29' => 1.0,    // Full Height - base
            '30' => 0.8,    // Café Style
            '31' => 1.3,    // Tier-on-Tier
            '32' => 1.5     // Bay Window
        );
        
        if (isset($config_data['property_style']) && isset($style_modifiers[$config_data['property_style']])) {
            $calculated_price *= $style_modifiers[$config_data['property_style']];
        }
        
        // Apply filters for custom price calculations
        $calculated_price = apply_filters('shutter_calculated_price', $calculated_price, $config_data, $product_id);
        
        return round($calculated_price, 2);
    }
} 