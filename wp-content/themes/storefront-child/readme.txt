=== Storefront Child - Matrix UK ===
Contributors: Matrix UK Development Team
Requires at least: WordPress 5.0
Tested up to: WordPress 6.4
Requires PHP: 7.4
License: Proprietary

== Description ==

Custom child theme for The Matrix UK B2B shutter ordering system.

== Changelog ==

= 1.2.5 - 2026-01-15 =
* Fix: Unit Price column not displaying values for Plus materials (BiowoodPlus, BasswoodPlus, EcowoodPlus)
* Fix: number_format() with comma separator breaking arithmetic calculations
* Fix: Incorrect !empty() syntax with && inside parentheses
* Fix: Non-numeric value PHP warnings in container-orders.php
* Fix: Added floatval() wrappers for safe arithmetic operations

= 1.2.4 - 2026-01-14 =
* Fix: PHP warning array_key_exists() expects string/integer in container-orders-prices.php
* Fix: Non-numeric value warnings when multiplying post_meta values

= 1.2.3 - 2026-01-08 =
* Fix: Products Total not including Individual Bay Window sections price
* Fix: $calculated_subtotal not updated for section items

= 1.2.2 - 2025-12-24 =
* Fix: Price/sqm not displaying for BasswoodPlus material in order tables
* Fix: $material_price variable not initialized before foreach loops
* Fix: Removed overwrite lines replacing valid prices with empty product meta

= 1.2.1 - 2025-11-26 =
* Fix: Undefined variable notices in shutter templates
* Fix: cart-empty.php errors preventing shutter buttons from showing
* Fix: Cart total calculation - train_delivery was counted twice
* Fix: Decimal formatting issues in checkout totals

= 1.2.0 - 2025-11-01 =
* Feature: Container orders price reconciliation with CSV invoices
* Feature: Individual Bay Window sections support
* Improvement: Enhanced order table displays

= 1.1.0 - 2025-10-01 =
* Feature: QuickBooks integration for invoicing
* Feature: Multi-dealer pricing system
* Feature: Repair order management

= 1.0.0 - 2025-09-01 =
* Initial release
* Custom order management system
* WooCommerce integration for B2B ordering
