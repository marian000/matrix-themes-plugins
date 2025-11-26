# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress/WooCommerce-based B2B ordering system for The Matrix UK, a shutter and window covering manufacturer. The system includes:
- Custom product configurator (Shutter Module plugin)
- Order management with repair tracking
- QuickBooks integration for invoicing
- Mobile app API support (appCart system)
- Multi-dealer/customer pricing system

**Focus Directories:**
- `/wp-content/plugins/shutter-module/` - Product configuration plugin
- `/wp-content/themes/storefront-child/` - Custom theme with business logic

## Architecture

### Shutter Module Plugin (`/wp-content/plugins/shutter-module/`)

The core product configurator plugin with these components:

**Database Tables (created on init):**
- `wp_shutter_attributes` - Product attribute definitions
- `wp_shutter_names` - Shutter product names
- `wp_property_values` - Attribute value options
- `wp_property_fields` - Field definitions

**Product Types (via shortcodes):**
- `[product_shutter1]` - Standard shutters (templates/prod-1.php)
- `[product_shutter3]` - Shutter & Blackout Blind (templates/prod-3.php)
- `[product_shutter5]` - Battens (templates/prod-5.php)
- `[product_shutter_individual]` - Individual bay shutters

Each product type has variants:
- `*_edit` - Customer edit mode
- `*_edit_admin` - Admin edit mode
- `*_all` - Combined add/edit functionality

**JavaScript Controllers:**
- `js/product-script-custom.js` - Shutter configurator (prod1)
- `js/product3-script-custom.js` - Blackout blind configurator
- `js/product5-script-custom.js` - Batten configurator
- `js/custom-scripts.js` - Shared functionality
- `js/update-item-scripts.js` - Cart update logic

**REST API Endpoint:**
- `POST /wp-json/custom/v1/add-matrix-shutter` - Mobile app product creation
- Creates WooCommerce products with pricing calculations based on SQM and material type

### Storefront Child Theme (`/wp-content/themes/storefront-child/`)

**Core Include Files (`/includes/`):**
- `custom-orders-functions.php` - AJAX processor for batch order updates
- `class-orders.php` - `OrdersCustom` class for `wp_custom_orders` table
- `class_quickbooks.php` - QuickBooks table creation and admin hooks
- `woocommerce-functions.php` - WC customizations
- `awning-functions.php` - Checkout field modifications
- `grup-functions.php` - Dealer group management
- `user-functions.php` - User role handling
- `shortcodes.php` / `shortcodes-my-orders.php` - Frontend shortcodes

**AJAX Handlers (`/ajax/`):**
- `insert-orders.php` - New order creation
- `quickbooks-invoice.php` - QB invoice sync
- `repair-ajax.php` - Repair order management
- `van-ajax.php` / `vaninfo-ajax.php` - Delivery scheduling
- `csv-container-compare.php` - Container shipping reconciliation
- `shipping-change.php` - Shipping method updates
- `frametype-stats-ajax.php` - Order analytics

**Custom Database Tables:**
- `wp_custom_orders` - Denormalized order data (USD/GBP prices, SQM, shipping)
- `wp_quickbooks_items` - QuickBooks product mapping
- `wp_quickbooks_customers` - QuickBooks customer mapping

### Pricing System

Prices are calculated per-SQM with these material types stored in user meta:
- `Earth`, `Ecowood`, `Green`, `Biowood` - Base prices per SQM
- `*_tax` variants for tax-inclusive pricing
- `train_price` - Train delivery surcharge per SQM
- `discount_custom` - User-specific discount rate

Price calculation flow (in `ajax-prod.php`):
1. Get SQM from dimensions
2. Look up user's material price (fallback to post ID 1 for defaults)
3. Apply style discounts (Cafe Style: 5.45%, Tier-on-Tier: 3.45%)
4. Add uplifts for buildout, Z-frame, special shapes
5. Store both GBP and USD prices

### Custom Post Types

- `order_repair` - Repair orders with status tracking
- `container` - Shipping container management
- `appcart` - Mobile app cart sessions
- `stgh_ticket` - Support tickets (Catchers Helpdesk)

### Page Templates

- `template-orderedit.php` - Order editing interface
- `template-repairs.php` - Repair management dashboard
- `template-vieworder.php` - Order detail view
- `template-multicart.php` - Multi-cart functionality
- `template-reports.php` - Analytics/reporting
- `template-prod-awning.php` - Awning configuration

## Key Patterns

### Body Class Detection
The shutter module adds body classes (`prod1`, `prod3`, `prod5`, `prodIndividual`) based on shortcode presence, which controls JS/CSS loading.

### User Meta for Pricing
All dealer-specific pricing is stored in user meta, with post ID 1 serving as the global default source.

### Order Recalculation
The `matrix_recalculate_order_totals_and_update_custom_table()` function in `functions.php` handles:
- Line item price recalculation with train delivery
- Tax calculation (20% for GB/IE)
- `wp_custom_orders` table synchronization
- Skips POS category products (IDs: 20, 34, 26)

### AJAX Security
Most AJAX endpoints use WordPress nonce verification via `check_ajax_referer()`.

## Important Files

| Purpose | Location |
|---------|----------|
| Theme entry point | `storefront-child/functions.php` |
| Plugin entry point | `shutter-module/shutter-module.php` |
| Order calculations | `storefront-child/functions.php:1042-1300` |
| Price calculations | `shutter-module/ajax/ajax-prod.php` |
| REST API | `shutter-module/API/add-new-shutter-api.php` |
| QuickBooks OAuth | `storefront-child/quickBooks/callback.php` |
| Batch order processor | `storefront-child/includes/custom-orders-functions.php` |

## Configuration

Key settings stored in `wp-config.php`:
- Database: `matrixli_fetsw` with prefix `wp_`
- Memory limits: 4096M/5120M (resource-intensive)
- Debug mode: Conditional for admin user ID 1 only
