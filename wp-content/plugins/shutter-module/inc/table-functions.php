<?php

// Table functions action
add_action('init', 'shutter_table_attributes');
add_action('init', 'shutter_table_names');
add_action('init', 'shutter_table_property_values');
add_action('init', 'shutter_table_property_fields');

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

