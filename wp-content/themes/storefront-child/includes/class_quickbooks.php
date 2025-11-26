<?php
    
    class quickBooksCustom
    {
        function quickbooksTables()
        {
            global $wpdb;
            global $jal_db_version;
            $table_name = $wpdb->prefix . 'quickbooks_items';
            $charset_collate = $wpdb->get_charset_collate();
            
            // table import quickbooks items
            // create table wp_quickbooks_items to insert items from quickbooks platform
            if ($wpdb->get_var("SHOW TABLE LIKE " . $table_name) != $table_name) {
                $sql_items = "CREATE TABLE $table_name (
                    id INT NOT NULL AUTO_INCREMENT,
                    idQB varchar(10) DEFAULT '' NOT NULL,
                    Name varchar(100) DEFAULT '' NOT NULL,
                    Description varchar(200) DEFAULT '' NOT NULL,
                    UnitPrice int(20) DEFAULT '0' NOT NULL,
                    Type varchar(50) DEFAULT '' NOT NULL,
                    IncomeAccountRef longtext DEFAULT '' NOT NULL,
                    SalesTaxCodeRef longtext DEFAULT '' NOT NULL,
                    CreateTime timestamp NOT NULL,
                    status ENUM('0', '1') NOT NULL default '1',
                    PRIMARY KEY (id) )";
            }
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql_items);
            
            $table_name2 = $wpdb->prefix . 'quickbooks_customers';
            
            // table import quickbooks customers
            // create table wp_quickbooks_items to insert items from quickbooks platform
            if ($wpdb->get_var("SHOW TABLE LIKE " . $table_name2) != $table_name2) {
                $sql_customers = "CREATE TABLE $table_name2 (
                    id INT NOT NULL AUTO_INCREMENT,
                    idQB varchar(10) DEFAULT '' NOT NULL,
                    GivenName varchar(100) DEFAULT '' NOT NULL,
                    CompanyName varchar(100) DEFAULT '' NOT NULL,
                    DisplayName varchar(100) DEFAULT '' NOT NULL,
                    PrimaryPhone longtext DEFAULT '' NOT NULL,
                    Mobile longtext DEFAULT '' NOT NULL,
                    PrimaryEmailAddr longtext DEFAULT '' NOT NULL,
                    BillAddr longtext DEFAULT '' NOT NULL,
                    ShipAddr longtext DEFAULT '' NOT NULL,
                    SalesTermRef longtext DEFAULT '' NOT NULL,
                    PaymentMethodRef longtext DEFAULT '' NOT NULL,
                    CurrencyRef longtext DEFAULT '' NOT NULL,
                    CreateTime timestamp NOT NULL,
                    PRIMARY KEY (id) )";
            }
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql_customers);
            
            add_option('jal_db_version', $jal_db_version);
        }
    }
    
    # Called only in /wp-admin/edit.php pages
    add_action('load-edit.php', function () {
        add_filter('views_edit-shop_order', 'quickbooksSectionConnect'); // talk is my custom post type
    });

# echo the tabs
    function quickbooksSectionConnect($views)
    {
        include get_theme_file_path( 'quickBooks/index.php' );
        
        include get_theme_file_path('includes/old-orders.php');
        
        echo '<br><br>';

        return $views;
    }
