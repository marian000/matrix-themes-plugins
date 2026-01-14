# Storefront Child Theme - Matrix UK

Custom child theme for The Matrix UK B2B ordering system.

## Overview

This theme extends the Storefront theme with custom functionality for:
- Order management and tracking
- Container shipping reconciliation
- QuickBooks integration
- Multi-dealer pricing system
- Repair order management

## Key Directories

| Directory | Description |
|-----------|-------------|
| `/ajax/` | AJAX handlers for orders, repairs, shipping |
| `/includes/` | Core PHP classes and functions |
| `/views/` | Template partials and table views |
| `/quickBooks/` | QuickBooks OAuth integration |

## Changelog

### 2026-01-15
- **Fix container orders price display and PHP warnings** (`8a8438b`)
  - Fix Unit Price column not showing values for Plus materials (BiowoodPlus, BasswoodPlus, EcowoodPlus)
  - Fix `number_format()` with comma separator used in arithmetic operations
  - Fix incorrect `!empty()` syntax with `&&` inside parentheses
  - Add `floatval()` wrappers to prevent non-numeric value warnings
  - Fix container-orders.php non-numeric value warnings

### 2026-01-14
- **Fix PHP warnings in container-orders-prices.php** (`1f13494`)
  - Fix `array_key_exists()` warning by saving order ID before converting to WC_Order object
  - Add `floatval()` for arithmetic operations with post_meta values

### 2026-01-08
- **Fix Products Total not including Individual Bay Window sections** (`741f77a`)
  - Add `$calculated_subtotal` update for section prices in items.php and order-edit-items.php

### 2025-12-24
- **Fix price/sqm display for BasswoodPlus material** (`5087c42`)
  - Initialize `$material_price` before foreach loops
  - Remove overwrite lines that replaced valid prices with empty product meta

### 2025-11-26
- **Fix undefined variable notices in shutter templates** (`3ed4173`)
- **Fix cart-empty.php errors preventing shutter buttons from showing** (`6389ce6`)
- **Fix cart total calculation - train_delivery was counted twice** (`7b555f6`)
- **Fix decimal formatting in checkout totals** (`bf948f7`)

## Configuration

Key settings are managed through:
- `wp-config.php` - Database and memory settings
- Post ID 1 meta - Global default prices
- User meta - Dealer-specific pricing

## Support

For issues, contact the development team or create a ticket in the support system.
