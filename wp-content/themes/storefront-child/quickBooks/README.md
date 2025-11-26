# QuickBooks Integration - Matrix Live

## 📋 Overview

This directory contains the QuickBooks Online integration for the Matrix Live e-commerce platform. The integration has been updated to use QuickBooks V3 PHP SDK 6.2.0 with improved error handling and centralized configuration.

## 🚀 Quick Start

### Basic Usage
The main invoice creation is handled by `InvoiceAndBilling.php` which is called via AJAX from the WordPress admin.

### Requirements
- WordPress 5.x+
- WooCommerce
- QuickBooks Online account with API access
- PHP 7.0+

## 📂 Directory Structure

```
quickBooks/
├── config/
│   ├── quickbooks-constants.php     # Required constants
│   └── quickbooks-config.php        # Centralized configuration
├── includes/
│   └── class-token-manager.php      # OAuth token management
├── vendor/
│   └── QuickBooks-V3-PHP-SDK-6.2.0/ # Official QB SDK
├── InvoiceAndBilling.php            # Main invoice creation script
├── callback.php                     # OAuth callback handler
├── config.php                       # QB API credentials
└── README.md                        # This file
```

## ⚙️ Configuration

### API Credentials
Edit `config.php` with your QuickBooks app credentials:
```php
'client_id' => 'YOUR_QB_CLIENT_ID',
'client_secret' => 'YOUR_QB_CLIENT_SECRET',
'oauth_redirect_uri' => 'YOUR_CALLBACK_URL',
```

### Email Settings
Modify in `config/quickbooks-config.php`:
```php
const BILLING_BCC_EMAIL = 'your-accounting@domain.com';
```

### Material Mappings
**⚠️ IMPORTANT**: Do not modify material mappings without consulting QuickBooks admin:
```php
// These map WooCommerce material IDs to QuickBooks item IDs
'137' => 24,  // Green
'138' => 8,   // BiowoodPlus
// etc...
```

## 🔧 How It Works

### Invoice Creation Process
1. **Order Processing**: WooCommerce order data is retrieved
2. **Product Grouping**: Products are grouped by material type
3. **Price Calculation**: Prices calculated using existing `train_price` logic
4. **QB Mapping**: Products mapped to QuickBooks items using centralized config
5. **Invoice Creation**: Invoice sent to QuickBooks via API
6. **Email Delivery**: Invoice emailed to customer with BCC to accounting

### Token Management
- Tokens are automatically refreshed when expired
- Stored persistently in WordPress database
- Fallback to session storage for compatibility

## 🛠️ Troubleshooting

### Common Issues

**"QuickBooks authentication required"**
- Check if OAuth tokens are valid
- Re-authenticate via QuickBooks connection flow

**"Undefined constant" errors**
- Ensure `config/quickbooks-constants.php` is included
- Check that all required constants are defined

**Material mapping errors**
- Verify material IDs in `QuickBooks_Config::get_materials_mapping()`
- Check QuickBooks for correct item IDs

### Debug Logging
Logs are automatically saved to: `wp-content/uploads/quickbooks-logs/`

Enable WordPress debug logging in `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 📝 Maintenance

### Updating Material Mappings
1. Edit `config/quickbooks-config.php`
2. Modify the `get_materials_mapping()` method
3. Test with a sample order before deploying

### Adding New Product Categories
1. Add mapping in `QuickBooks_Config::get_materials_mapping()`
2. Update logic in `InvoiceAndBilling.php` if special handling needed
3. Test thoroughly

### Changing Email Recipients
Update constants in `config/quickbooks-config.php`:
```php
const BILLING_BCC_EMAIL = 'new-email@domain.com';
```

## 🔒 Security Notes

- OAuth tokens are stored securely in WordPress database
- All file access is protected with WordPress security checks
- API credentials should never be committed to version control
- Use environment variables for sensitive configuration in production

## 📊 Business Logic Preservation

The following business logic is preserved from the original implementation:

### Price Calculation
- Uses existing `train_price` multiplication formula
- Maintains SQM (square meter) calculations
- Preserves user-specific pricing overrides

### Category Handling
- **Shutter & Blackout Blind**: Special handling maintained
- **Batten**: Uses dedicated QuickBooks item ID (85)
- **Standard Products**: Default material-based mapping

### Customer Management
- Perfect Shutter (ID: 274) special email routing
- Dealer relationship handling preserved
- Customer creation logic maintained

## 🚨 Critical Notes

### DO NOT MODIFY:
- Material to QuickBooks ID mappings without QB admin approval
- Price calculation formulas
- Customer email routing logic for Perfect Shutter
- Product categorization logic

### SAFE TO MODIFY:
- Email addresses in config
- Logging configuration
- Tax rates (via constants)
- Delivery item IDs

## 📞 Support

For technical issues:
1. Check logs in `wp-content/uploads/quickbooks-logs/`
2. Verify QuickBooks connection status
3. Test with sandbox QuickBooks first
4. Contact QuickBooks admin for mapping changes

For business logic questions:
1. Review preserved logic documentation above
2. Test changes in development environment
3. Consult with accounting team before modifying financial calculations