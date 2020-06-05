<?php
/*
Plugin Name: Shutter-module
Plugin URI: https://thecon.ro/
description: A plugin to send products to shutter
Version: 0.1
Author: MairanP
Author URI: https://thecon.ro/
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once('inc/frontend-media.php');
require_once('inc/table-functions.php');
require_once('inc/prod-shortcode.php');
// cart page
require_once('inc/cart-interface.php');
// set cart item data on shutter config
require_once('inc/add-cart-item.php');
require_once('inc/single-product.php');


/**
 * Register a custom menu page shutter Module.
 */
function wpdocs_register_shutter_mod()
{
    add_menu_page(
        __('Shutter Module', 'textdomain'),
        'Shutter Module',
        'manage_options',
        'shuttermodule',
        'shutter_menu_page',
        'dashicons-welcome-widgets-menus',
        30
    );
}

add_action('admin_menu', 'wpdocs_register_shutter_mod');

/**
 * Display a custom menu page
 */
function shutter_menu_page()
{
    include_once dirname(__FILE__) . '/views/page-show.php';
}


/**
 * Plugin style and script enqueue
 */
function wpse_load_plugin_css()
{
    $plugin_url = plugin_dir_url(__FILE__);

    $classes = get_body_class();

    wp_enqueue_style('select2c-css', $plugin_url . 'css/jquery.fancybox.min.css');
    wp_enqueue_style('module-style-css', $plugin_url . '/css/module-style.css');
    if (in_array('prod1', $classes) || in_array('prod2', $classes) || in_array('prod3', $classes) || in_array('prod4', $classes) || in_array('prod5', $classes)) {
        wp_enqueue_style('ace-min-css', $plugin_url . 'css/ace.min.css');
        wp_enqueue_style('ace-skins-min-css', $plugin_url . 'css/ace-skins.min.css');
        wp_enqueue_style('application-css', $plugin_url . 'css/application.css');
        wp_enqueue_style('select2c-css', $plugin_url . 'css/select2.min.css');

        wp_enqueue_script('ace-extra', $plugin_url . 'js/ace-extra.min.js', array(), '1.0.1', true);
        wp_enqueue_script('ace-elements', $plugin_url . 'js/ace-elements.min.js', array(), '1.0.1', true);
        wp_enqueue_script('raphael-script', $plugin_url . 'js/raphael.js', array(), '1.0.1', true);
        wp_enqueue_script('application-js', $plugin_url . 'js/application.js', array(), '1.0.1', true);
        wp_enqueue_script('frontend-media-customm-js', $plugin_url . 'js/frontend.js');
        wp_enqueue_media();
    }

    wp_enqueue_script('bootstrap-js', $plugin_url . 'js/bootstrap3-min.js', array(), '3.3.7', true);
    wp_enqueue_script('application-js', $plugin_url . 'js/jquery.fancybox.min.js', array(), '1.0.1', true);
    wp_enqueue_script('functions-script', $plugin_url . 'js/functions.js', array(), '1.0.1', true);

//        wp_enqueue_script('jquery-min', $plugin_url . 'js/jquery-3.3.1.min.js', array(), '1.0.0', true);

    wp_enqueue_script('jquery-nicescrol', $plugin_url . 'js/jquery.nicescroll.min.js', array(), '1.0.1', true);
    wp_enqueue_script('jquery-flexslider', $plugin_url . 'js/jquery.flexslider.min.js', array(), '1.0.1', true);
    wp_enqueue_script('jquery-fancybox', $plugin_url . 'js/jquery.fancybox.min.js', array(), '1.0.1', true);
    wp_enqueue_script('jquery-masonry', $plugin_url . 'js/jquery.masonry.min.js', array(), '1.0.1', true);
    wp_enqueue_script('shutter-modul-custom-scripts-js', $plugin_url . 'js/custom-scripts.js', array(), '1.1.5', true);

    if (in_array('prod1', $classes)) {
        wp_enqueue_script('product-script-custom', $plugin_url . 'js/product-script-custom.js', array(), '1.1.5', true);
    }
    if (in_array('prod2', $classes)) {
        wp_enqueue_script('product2-script-custom', $plugin_url . 'js/product2-script-custom.js', array(), '1.1.5', true);
    }
    if (in_array('prod3', $classes)) {
        wp_enqueue_script('product3-script-custom', $plugin_url . 'js/product3-script-custom.js', array(), '1.1.5', true);
    }
    if (in_array('prod4', $classes)) {
        wp_enqueue_script('product4-script-custom', $plugin_url . 'js/product4-script-custom.js', array(), '1.1.5', true);
    }
    if (in_array('prod5', $classes)) {
        wp_enqueue_script('product5-script-custom', $plugin_url . 'js/product5-script-custom.js', array(), '1.1.5', true);
    }

}

add_action('wp_enqueue_scripts', 'wpse_load_plugin_css');

// Add specific CSS class by filter.
function custom_class_prod($classes)
{
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter1')) {
        $classes[] = 'prod1';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter3')) {
        $classes[] = 'prod3';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter5')) {
        $classes[] = 'prod5';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter1_edit')) {
        $classes[] = 'prod1';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter1_edit_admin')) {
        $classes[] = 'prod1';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter3_edit')) {
        $classes[] = 'prod3';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter3_edit_admin')) {
        $classes[] = 'prod3';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter5')) {
        $classes[] = 'prod5';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter5_edit')) {
        $classes[] = 'prod5';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter5_edit_admin')) {
        $classes[] = 'prod5';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter1_all')) {
        $classes[] = 'prod1';
        return $classes;
    } elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter2_all')) {
        $classes[] = 'prod3';
        return $classes;
    }


    // if is single product and is shutter simgple add class
    if (is_product()) {
        $product_cats_ids = wc_get_product_term_ids(get_the_ID(), 'product_cat');
        $terms_name = array();
        foreach ($product_cats_ids as $cat_id) {
            $term = get_term_by('id', $cat_id, 'product_cat');
            $terms_name[] = $term->name;
        }
        if (in_array('shutter', $terms_name)) {
            $classes[] = 'prod1';
            return $classes;
        } else {
            return;
        }
    }
}

add_filter('body_class', 'custom_class_prod');


/**
 * Override WooCommerce Templates
 */

add_filter('woocommerce_locate_template', 'woo_adon_plugin_template', 1, 3);
function woo_adon_plugin_template($template, $template_name, $template_path)
{
    global $woocommerce;
    $_template = $template;
    if (!$template_path)
        $template_path = $woocommerce->template_url;

    $plugin_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/templates/woocommerce/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
            $template_path . $template_name,
            $template_name
        )
    );

    if (!$template && file_exists($plugin_path . $template_name))
        $template = $plugin_path . $template_name;

    if (!$template)
        $template = $_template;

    return $template;
}