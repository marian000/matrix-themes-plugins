<?php
/*
Plugin Name: Shutter-module
Plugin URI: https://thecon.ro/
description: A plugin to send products to shutter
Version: 1.1.1
Author: MairanP
Author URI: https://thecon.ro/
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


// add api for add new shutter , tablet app
require_once plugin_dir_path(__FILE__) . 'API/add-new-shutter-api.php';
//
//require_once plugin_dir_path(__FILE__) . 'inc/register-users-excel.php';
require_once plugin_dir_path(__FILE__) . 'inc/custom-logs.php';
require_once plugin_dir_path(__FILE__) . 'inc/appCart.php';

require_once('inc/frontend-media.php');
require_once('inc/shortcodes.php');
require_once('inc/ajax.php');

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
		26
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


//Insert table shutter attributes
function shutter_table_attributes()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "shutter_attributes";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    type_prod mediumint(9) NOT NULL,
    attr_id mediumint(9) NOT NULL,
    name varchar(55) DEFAULT '' NOT NULL,
    price varchar(55) DEFAULT '' NOT NULL,
    visibility varchar(55) DEFAULT 'show' NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    PRIMARY KEY  (id)
    ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


add_action('init', 'shutter_table_attributes');

//Insert table shutter attributes names
function shutter_table_names()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "shutter_names";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(55),
        description varchar(55),
        part_number varchar(55),
        is_active varchar(55),
        status_id varchar(55),
        category_id varchar(55),
        promote_category varchar(55),
        promote_front varchar(55),
        price1 varchar(55),
        price2 varchar(55),
        price3 varchar(55),
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        image_file_name varchar(55),
        image_content_type varchar(55),
        image_file_size varchar(55),
        image_updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        old_id varchar(55),
        minimum_quantity varchar(55),
        product_type varchar(55),
        vat_class_id varchar(55),
        PRIMARY KEY  (id)
        ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


add_action('init', 'shutter_table_names');

//Insert table shutter attributes values
function shutter_table_property_values()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "property_values";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        property_id mediumint(9),
        value varchar(55),
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        code varchar(55),
        uplift varchar(55),
        color varchar(55),
        all_products varchar(55),
        selected_products varchar(55),
        all_property_values varchar(55),
        selected_property_values varchar(200),
        graphic varchar(55),
        image_file_name varchar(55),
        image_content_type varchar(55),
        image_file_size varchar(55),
        image_updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        is_active varchar(55),
        property text NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


add_action('init', 'shutter_table_property_values');

//Insert table shutter attributes fields
function shutter_table_property_fields()
{
	global $wpdb;

	$table_name = $wpdb->prefix . "property_fields";

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(55),
        created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        code varchar(55),
        sort varchar(55),
        help_text varchar(55),
        input_type varchar(55),
        PRIMARY KEY  (id)
        ) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}


add_action('init', 'shutter_table_property_fields');

/**
 * Plugin style and script enqueue
 */
function wpse_load_plugin_css()
{
	$plugin_url = plugin_dir_url(__FILE__);

	$classes = get_body_class();

	// Fetch User Meta in PHP
	// Ensure the user is logged in
	if (is_user_logged_in()) {
		$show_biowood = get_user_meta(get_current_user_id(), 'show_biowood', true); // Note: use ID instead of id
	} else {
		$show_biowood = 'no';
	}

	wp_enqueue_style('select2c-css', $plugin_url . 'css/jquery.fancybox.min.css');
	if (in_array('prod1', $classes) || in_array('prod2', $classes) || in_array('prod3', $classes) || in_array('prod4', $classes) || in_array('prod5', $classes) || in_array('prodIndividual', $classes)) {
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
	wp_enqueue_script('shutter-modul-custom-scripts-js', $plugin_url . 'js/custom-scripts.js', array(), '1.4.3', true);
	wp_enqueue_script('update-item-scripts-js', $plugin_url . 'js/update-item-scripts.js', array(), '1.0.1', true);

	if (in_array('prod1', $classes)) {
		wp_enqueue_script('product-script-custom', $plugin_url . 'js/product-script-custom.js', array(), '1.6.1', true);
		// Localize the script with new data
		wp_localize_script('product-script-custom', 'my_showBiowood_object', array(
			'showBiowood' => $show_biowood,
		));
	}
	if (in_array('prodIndividual', $classes)) {
		wp_enqueue_script('product-script-individual', $plugin_url . 'js/product-script-individual.js', array(), '1.6.1', true);
		// Localize the script with new data
		wp_localize_script('product-script-individual', 'my_showBiowood_object', array(
			'showBiowood' => $show_biowood,
		));
	}

	// blackout blind
	if (in_array('prod3', $classes)) {
		wp_enqueue_script('product3-script-custom', $plugin_url . 'js/product3-script-custom.js', array(), '1.6.1', true);
		// Localize the script with new data
		wp_localize_script('product3-script-custom', 'my_showBiowood_object', array(
			'showBiowood' => $show_biowood,
		));
	}

	// batten
	if (in_array('prod5', $classes) && !is_page(534) && !is_page(13685)) {
		wp_enqueue_script('product5-script-custom', $plugin_url . 'js/product5-script-custom.js', array(), '1.6.3', true);
		wp_localize_script('product5-script-custom', 'my_showBiowood_object', array(
			'showBiowood' => $show_biowood,
		));
	}

	if (in_array('prod5', $classes) && is_page(534) || is_page(13685)) {
		wp_enqueue_script('product5-script-custom-edit', $plugin_url . 'js/product5-script-custom-edit.js', array(), '1.6.3', true);
		wp_localize_script('product5-script-custom-edit', 'my_showBiowood_object', array(
			'showBiowood' => $show_biowood,
		));
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
	} elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter_individual')) {
		$classes[] = 'prodIndividual';
		return $classes;
	} elseif (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_shutter2_all')) {
		$classes[] = 'prod3';
		return $classes;
	}
}


add_filter('body_class', 'custom_class_prod');

add_action('wp_ajax_create_product_and_add', 'create_product_and_add_to_cart');
add_action('wp_ajax_nopriv_create_product_and_add', 'create_product_and_add_to_cart');
function create_product_and_add_to_cart()
{

	include_once dirname(__FILE__) . '/ajax/multiple-batten-add.php';
}


function add_type_attribute($tag, $handle, $src)
{
	// The handle should match the one used in wp_enqueue_script
	if ('product3-script-custom' === $handle) {
		$tag = '<script type="module" src="' . esc_url($src) . '"></script>';
	}
	return $tag;
}
//add_filter('script_loader_tag', 'add_type_attribute', 10, 3);