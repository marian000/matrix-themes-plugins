<?php
/**
 * QuickBooks Constants
 * 
 * Defines all necessary constants for QuickBooks API integration
 * These constants are required for account creation functions in InvoiceAndBilling.php
 * 
 * @package Matrix_QuickBooks
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Income Account Constants
 * Used for creating income accounts in QuickBooks
 */
define('INCOME_ACCOUNT_TYPE', 'Income');
define('INCOME_ACCOUNT_SUBTYPE', 'SalesOfProductIncome');

/**
 * Expense Account Constants  
 * Used for creating expense accounts in QuickBooks
 */
define('EXPENSE_ACCOUNT_TYPE', 'Expense');
define('EXPENSE_ACCOUNT_SUBTYPE', 'SuppliesMaterials');

/**
 * Asset Account Constants
 * Used for creating asset accounts in QuickBooks
 */
define('ASSET_ACCOUNT_TYPE', 'Asset');
define('ASSET_ACCOUNT_SUBTYPE', 'Inventory');

/**
 * Additional QuickBooks Configuration Constants
 */
// Tax rates by country
define('UK_TAX_RATE', 15);
define('OTHER_COUNTRY_TAX_RATE', 26);

// Default department for invoices
define('DEFAULT_DEPARTMENT_REF', 6);

// Default sales terms
define('DEFAULT_SALES_TERMS_REF', 8);

// Special category item IDs
define('BATTEN_ITEM_ID', 85);
define('OTHER_COLOR_ITEM_ID', 60);

// Delivery types
define('AIR_DELIVERY_ITEM_ID', 51);
define('STANDARD_DELIVERY_ITEM_ID', 16);