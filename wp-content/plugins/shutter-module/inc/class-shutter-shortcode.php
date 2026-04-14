<?php
/**
 * Shutter Shortcode Class
 * 
 * Handles the shortcode registration and rendering for the shutter configurator.
 * 
 * @package ShutterModule
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Shutter_Shortcode {
    
    /**
     * Configurator instance
     */
    private $configurator;
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_shortcode'));
    }
    
    /**
     * Register the shortcode
     */
    public function register_shortcode() {
        add_shortcode('shutter_configurator', array($this, 'render_configurator'));
    }
    
    /**
     * Render the configurator shortcode
     */
    public function render_configurator($atts) {
        $atts = shortcode_atts(array(
            'mode' => 'full', // full, simple, minimal
            'style' => 'default',
            'show_materials' => 'all', // all, wood, vinyl, composite, aluminum
            'show_styles' => 'all' // all, fullheight, cafe, tier, bay
        ), $atts, 'shutter_configurator');
        
        // Initialize configurator if not already done
        if (!$this->configurator) {
            $this->configurator = new Shutter_Configurator();
        }
        
        // Enqueue assets
        $this->enqueue_configurator_assets();
        
        // Start output buffering
        ob_start();
        
        try {
            $this->render_configurator_form($atts);
        } catch (Exception $e) {
            error_log('Shutter Configurator Shortcode Error: ' . $e->getMessage());
            echo '<div class="shutter-error">' . __('Unable to load shutter configurator.', 'shutter-module') . '</div>';
        }
        
        return ob_get_clean();
    }
    
    /**
     * Render the configurator form
     */
    private function render_configurator_form($atts) {
        $form_values = $this->configurator->get_form_values();
        $material_options = $this->configurator->get_material_options();
        $style_options = $this->configurator->get_style_options();
        
        // Filter options based on shortcode attributes
        if ($atts['show_materials'] !== 'all') {
            $material_options = $this->filter_material_options($material_options, $atts['show_materials']);
        }
        
        if ($atts['show_styles'] !== 'all') {
            $style_options = $this->filter_style_options($style_options, $atts['show_styles']);
        }
        
        // Include the template
        $template_path = plugin_dir_path(dirname(__FILE__)) . 'templates/configurator-form.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="shutter-error">' . __('Template file not found.', 'shutter-module') . '</div>';
        }
    }
    
    /**
     * Filter material options based on shortcode attribute
     */
    private function filter_material_options($options, $filter) {
        $material_map = array(
            'wood' => '138',
            'vinyl' => '139', 
            'composite' => '140',
            'aluminum' => '141'
        );
        
        if (isset($material_map[$filter])) {
            $key = $material_map[$filter];
            return isset($options[$key]) ? array($key => $options[$key]) : array();
        }
        
        return $options;
    }
    
    /**
     * Filter style options based on shortcode attribute
     */
    private function filter_style_options($options, $filter) {
        $style_map = array(
            'fullheight' => array('27', '28', '29'),
            'cafe' => array('30'),
            'tier' => array('31'),
            'bay' => array('32', '146', '147')
        );
        
        if (isset($style_map[$filter])) {
            $filtered = array();
            foreach ($style_map[$filter] as $key) {
                if (isset($options[$key])) {
                    $filtered[$key] = $options[$key];
                }
            }
            return $filtered;
        }
        
        return $options;
    }
    
    /**
     * Enqueue configurator assets
     */
    private function enqueue_configurator_assets() {
        $plugin_url = plugin_dir_url(dirname(__FILE__));
        $plugin_path = plugin_dir_path(dirname(__FILE__));
        
        // Check if files exist before enqueuing
        $css_file = $plugin_path . 'css/configurator.css';
        $js_file = $plugin_path . 'js/configurator.js';
        
        if (file_exists($css_file)) {
            wp_enqueue_style(
                'shutter-configurator-css',
                $plugin_url . 'css/configurator.css',
                array(),
                filemtime($css_file)
            );
        }
        
        if (file_exists($js_file)) {
            wp_enqueue_script(
                'shutter-configurator-js',
                $plugin_url . 'js/configurator.js',
                array('jquery', 'wp-util'),
                filemtime($js_file),
                true
            );
            
            // Localize script with configuration data
            wp_localize_script('shutter-configurator-js', 'shutterAjax', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('shutter_configurator_nonce'),
                'strings' => array(
                    'saving' => __('Saving configuration...', 'shutter-module'),
                    'saved' => __('Configuration saved successfully!', 'shutter-module'),
                    'error' => __('Error saving configuration. Please try again.', 'shutter-module'),
                    'required_field' => __('This field is required.', 'shutter-module'),
                    'invalid_selection' => __('Please make a valid selection.', 'shutter-module')
                )
            ));
        }
        
        // Enqueue drawing canvas dependencies if needed
        if ($this->should_load_canvas_assets()) {
            $this->enqueue_canvas_assets();
        }
    }
    
    /**
     * Check if canvas assets should be loaded
     */
    private function should_load_canvas_assets() {
        // Load canvas assets only when drawing functionality is needed
        return isset($_GET['id']) || isset($_GET['order_id']) || is_page('shutter-configurator');
    }
    
    /**
     * Enqueue canvas drawing assets
     */
    private function enqueue_canvas_assets() {
        // React dependencies for Literally Canvas
        wp_enqueue_script(
            'react-with-addons',
            'https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js',
            array(),
            '0.14.7',
            true
        );
        
        wp_enqueue_script(
            'react-dom',
            'https://cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js',
            array('react-with-addons'),
            '0.14.7',
            true
        );
        
        // Literally Canvas
        $theme_url = get_stylesheet_directory_uri();
        
        wp_enqueue_style(
            'literally-canvas-css',
            $theme_url . '/canvas-demo/_assets/literallycanvas.css',
            array(),
            '1.0.0'
        );
        
        wp_enqueue_script(
            'literally-canvas-js',
            $theme_url . '/canvas-demo/static/js/literallycanvas.js',
            array('react-dom'),
            '1.0.0',
            true
        );
    }
    
    /**
     * Get configurator instance
     */
    public function get_configurator() {
        if (!$this->configurator) {
            $this->configurator = new Shutter_Configurator();
        }
        
        return $this->configurator;
    }
} 