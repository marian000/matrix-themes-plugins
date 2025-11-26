<?php
/**
 * QuickBooks Token Refresh Script
 * 
 * Updated to use QuickBooks V3 PHP SDK 6.2.0 and centralized Token Manager
 * This script can be called manually or via AJAX to refresh expired tokens
 * 
 * @package Matrix_QuickBooks
 * @version 2.0
 */

$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

// Use the new QuickBooks V3 PHP SDK 6.2.0
require_once(__DIR__ . '/vendor/QuickBooks-V3-PHP-SDK-6.2.0/src/config.php');

// Include QuickBooks constants, configuration and Token Manager
require_once(__DIR__ . '/config/quickbooks-constants.php');
require_once(__DIR__ . '/config/quickbooks-config.php');
require_once(__DIR__ . '/includes/class-token-manager.php');

use QuickBooksOnline\API\DataService\DataService;

session_start();

// Create SDK instance with updated configuration
$config = include('config.php');

try {
    // Get current token using Token Manager
    $currentToken = QuickBooks_Token_Manager::get_access_token();
    
    if (empty($currentToken)) {
        throw new Exception('No current access token available for refresh');
    }
    
    // Initialize DataService
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $config['client_id'],
        'ClientSecret' => $config['client_secret'],
        'RedirectURI' => $config['oauth_redirect_uri'],
        'scope' => $config['oauth_scope'],
        'baseUrl' => "production", // Updated to production
    ));
    
    // Set current token for refresh operation
    $dataService->updateOAuth2Token($currentToken);
    
    // Set log location using centralized config
    $dataService->setLogLocation(QuickBooks_Config::get_log_path());
    
    // Perform token refresh using the new Token Manager
    $refreshedToken = QuickBooks_Token_Manager::refresh_token();
    
    if ($refreshedToken) {
        echo json_encode([
            'success' => true,
            'message' => 'Token refreshed successfully',
            'token_type' => get_class($refreshedToken),
            'expires_at' => method_exists($refreshedToken, 'getTokenExpiresAt') 
                ? $refreshedToken->getTokenExpiresAt() 
                : 'Not available'
        ]);
        
        // Log success
        error_log('QuickBooks: Manual token refresh completed successfully');
        
    } else {
        throw new Exception('Token refresh failed - no token returned');
    }
    
} catch (Exception $e) {
    // Handle errors gracefully
    $error_message = 'QuickBooks Token Refresh Error: ' . $e->getMessage();
    error_log($error_message);
    
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'message' => 'Token refresh failed. Check logs for details.'
    ]);
    
    http_response_code(500);
}

/**
 * Alternative method: Use Token Manager directly
 * This bypasses DataService configuration and uses stored refresh token
 */
function refresh_token_alternative() {
    try {
        $refreshedToken = QuickBooks_Token_Manager::refresh_token();
        
        if ($refreshedToken) {
            return [
                'success' => true,
                'token' => $refreshedToken,
                'message' => 'Token refreshed using Token Manager'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Token Manager refresh failed'
            ];
        }
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

// If called with ?method=alternative, use the alternative method
if (isset($_GET['method']) && $_GET['method'] === 'alternative') {
    $result = refresh_token_alternative();
    echo json_encode($result);
}
   
