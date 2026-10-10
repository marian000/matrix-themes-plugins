# Shutter Module — Changelog

All notable changes to the Shutter Module plugin are documented here.

---

## [2026-10-10]

### Fixed

- **Missing 41mm beaded stiles for Basswood/BasswoodPlus on the Shutter & Blackout Blind configurator** (`templates/prod-2-all.php`)
  - Basswood (material ID 147) and BasswoodPlus (material ID 139) listed 9 stiles on the
    Shutter & Blackout Blind page while the regular Add Shutter page (`templates/prod-1-all.php`)
    listed 12. The hand-written `#stile-img-basswood` and `#stile-img-basswoodPlus` blocks were
    never updated when the stiles were added to `prod-1-all.php`.
  - Added stiles 459 (41mm 1002M beaded butt), 460 (41mm 1004M beaded rebate) and
    461 (41mm 1006M beaded D-mould) to both blocks, after 378 and before 355, with the same
    `data-title` and `data-code` values as `prod-1-all.php`.

- **Wrong `checked` conditions in the Basswood/BasswoodPlus stile blocks** (`templates/prod-2-all.php`)
  - Stiles 376, 377 and 378 tested `$material == 6` (copied from the Biowood block), so no radio
    was pre-selected when editing a Basswood or BasswoodPlus product with one of these stiles.
  - Stiles 355, 356, 360, 357, 358 and 359 tested `($material == 139 || $material == 147)` in
    both blocks, so the radio was also checked in the hidden block of the other material.
  - Each block now tests only its own material (147 in Basswood, 139 in BasswoodPlus).

- **Stiles 459/460/461 missing from the inline option data** (`js/product3-script-custom.js`)
  - The local `property_values` list had no entries for 459, 460 and 461. The `.property-select`
    change handler hides and unchecks every `property_stile` radio whose value is not in that list
    for the selected material, so the new radios would have been hidden on every material change.
    `getStileCode()` also returned an empty stile code for the shutter drawing.
    Added the three entries after 447.
  - The template and script changes must be deployed together: with the old script cached, the
    new stiles stay hidden.
  - Bumped the `product3-script-custom` script version from `1.6.3` to `1.6.4` in
    `wpse_load_plugin_css()` (`shutter-module.php`).

---

## [2026-03-31]

### Fixed

- **Basswood/BasswoodPlus USD pricing crash in REST API** (`API/add-new-shutter-api.php`)
  - BasswoodPlus (material ID 139) called `dolarSum()` which does not exist in the API context,
    causing a PHP Fatal Error. Replaced with `dolarSumApi()`.
  - Basswood (material ID 147) had a duplicate USD pricing block (also using `dolarSum()`) that
    overwrote the correct calculation from the earlier block. Removed the duplicate.
  - Both blocks had unguarded `echo` statements that corrupted the REST JSON response.
  - **Impact:** Products submitted from the tablet app with Basswood or BasswoodPlus material
    were created but never attached to the appcart, resulting in an empty order table.

---

## [Unreleased] — 2026-02-22

### Fixed

- **403 Invalid security token on product update** (`js/custom-scripts.js`, `js/custom-scripts-new.js`)
  - All AJAX calls targeting `ajax-prod-update.php` and `ajax-prod.php` were missing the
    `pricing_nonce` field in the POST data.
  - Added `pricing_nonce: shutter_ajax_obj.pricing_nonce` to all 6 AJAX blocks in each file.

- **PHP Warning corrupting JSON response** (`includes/class-product-handler.php:170`)
  - `array_key_exists($id, $atribute)` threw a PHP 8.x Warning when `$id` was `null` or `float`,
    causing the warning text to appear before the JSON output and preventing jQuery `.done()` from firing.
  - Added type guard: `( is_string( $id ) || is_int( $id ) )` before the `array_key_exists()` call.

- **SonarQube S1066 — Merge if statement** (`includes/class-pricing-calculator.php`)
  - Four nested `if` blocks inside `calculateUsd()` were flagged by SonarQube.
  - All four merged with `&&` into single conditions (Basswood blade size check + three
    colour surcharge checks for Basswood, BiowoodPlus, and Earth).

### Added

- **UPDATE action type in pricing logs** (`inc/class-pricing-log-writer.php`, `ajax/ajax-prod-update.php`, `ajax/ajax-prod-update-individual.php`)
  - `PricingLogWriter::write()` now accepts a `string $action = 'CREATE'` parameter.
  - `ajax-prod-update.php` and `ajax-prod-update-individual.php` pass `'UPDATE'` explicitly.
  - Log header now includes the action type:
    ```
    === [2026-02-22 14:05:11] [UPDATE] User:160 Post:62453 Order:1234 Cart:John Smith Material:Earth SQM:1.08 GBP:300.00 USD:270.00 ===
    ```

- **Order ID in pricing log header** (`inc/class-pricing-log-writer.php`)
  - Reads `$products['order_edit']` and includes it as `Order:XXXX` in the log header.
  - Shows `Order:0` when no order is open (direct cart addition).

- **Cart name in pricing log header** (`inc/class-pricing-log-writer.php`)
  - Reads the active cart name from the `bmc_woocom_multisession` plugin via
    `\BMC\WooCommerce\MultiSession\customer_id()` and `get_carts()`.
  - Shows `Cart:CartName` when a named multi-session cart is active, or `Cart:-` as fallback.

- **Per-option prices in pricing logs** (`includes/class-pricing-calculator.php`)
  - Added private `addSurcharge( string $phase, string $label, float $amount, float $rate = 0.0 )` helper.
  - All ~51 surcharge patterns across phases 4–12 now log individual option contributions:
    ```
    [phase4] Inside buildout: rate=10.00% +£8.75 total=£96.25
    [phase5] Stainless Steel uplift: rate=10.00% +£9.63 total=£105.88
    [phase7] Ring pull: +£12.00 total=£247.63
    ```

---

## [1.0.0] — Previous releases

### Added

- Configurable pricing log system with admin UI (`/wp-admin/admin.php?page=custom-log&tab=settings`)
- `PricingLogWriter` class (`inc/class-pricing-log-writer.php`) — writes to `pricing-log.txt` with
  automatic rotation when the file exceeds the configured max size.
- `PricingCalculator` class (`includes/class-pricing-calculator.php`) — extracted from monolithic
  AJAX files; handles all 13 pricing phases including SQM calculation, material pricing, buildout
  rules, style discounts, and USD conversion.
- `ProductHandler` class (`includes/class-product-handler.php`) — handles product creation,
  update, meta saving, stale meta cleanup, and shipping class assignment.
- `OrderHandler` class (`includes/class-order-handler.php`) — handles order recalculation,
  colour product management, and cart updates.
- `PricingConfig` class (`includes/class-pricing-config.php`) — loads user-specific and global
  pricing rates from user meta and post meta (post ID 1 as fallback).
- `PricingResult` class (`includes/class-pricing-result.php`) — value object holding all
  calculated prices and debug log entries.
- Thin AJAX wrapper files (under 110 lines each): `ajax-prod.php`, `ajax-prod-individual.php`,
  `ajax-prod-update.php`, `ajax-prod-update-individual.php`.
- Material constants and pricing maps extracted to `includes/pricing-maps.php`.
- Input sanitization centralized in `includes/sanitize-input.php`.
- `Supreme` → `Basswood` / `SupremePlus` → `BasswoodPlus` migration support.
