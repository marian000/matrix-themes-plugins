<?php
/**
 * QuickBooks OAuth Callback Handler
 * 
 * Updated to use QuickBooks V3 PHP SDK 6.2.0 and centralized Token Manager
 * Handles the OAuth callback from QuickBooks and stores tokens securely
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

function processCode()
{
    try {
        // Create SDK instance with updated configuration
        $config = include('config.php');
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => $config['client_id'],
            'ClientSecret' => $config['client_secret'],
            'RedirectURI' => $config['oauth_redirect_uri'],
            'scope' => $config['oauth_scope'],
            'baseUrl' => "production" // Updated to production
        ));

        // Set log location using centralized config
        $dataService->setLogLocation(QuickBooks_Config::get_log_path());

        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $parseUrl = parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

        if (empty($parseUrl['code']) || empty($parseUrl['realmId'])) {
            throw new Exception('Missing authorization code or realm ID');
        }

        /*
         * Exchange authorization code for access token
         */
        $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken(
            $parseUrl['code'], 
            $parseUrl['realmId']
        );

        if (empty($accessToken)) {
            throw new Exception('Failed to obtain access token from QuickBooks');
        }

        // Update DataService with new token
        $dataService->updateOAuth2Token($accessToken);

        /*
         * Store the access token using the new Token Manager
         */
        $stored = QuickBooks_Token_Manager::store_access_token($accessToken);

        if ($stored) {
            // Store realm ID for future reference
            update_post_meta(1, 'qb_realm_id', $parseUrl['realmId']);
            
            // Log successful authentication
            error_log('QuickBooks: OAuth callback successful, tokens stored');
            
            // Return success response
            return [
                'success' => true,
                'message' => 'QuickBooks authentication successful',
                'realm_id' => $parseUrl['realmId'],
                'token_type' => get_class($accessToken)
            ];
        } else {
            throw new Exception('Failed to store access token');
        }

    } catch (Exception $e) {
        error_log('QuickBooks OAuth Callback Error: ' . $e->getMessage());
        
        return [
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'QuickBooks authentication failed'
        ];
    }
}

function parseAuthRedirectUrl($url)
{
    parse_str($url, $qsArray);
    return array(
        'code' => isset($qsArray['code']) ? $qsArray['code'] : null,
        'realmId' => isset($qsArray['realmId']) ? $qsArray['realmId'] : null,
        'error' => isset($qsArray['error']) ? $qsArray['error'] : null,
        'error_description' => isset($qsArray['error_description']) ? $qsArray['error_description'] : null
    );
}

// Process the OAuth callback
$result = processCode();

// Handle the response
if ($result['success']) {
    // Success - redirect to success page or show success message
    echo '<h2>QuickBooks Authentication Successful!</h2>';
    echo '<p>Your QuickBooks account has been successfully connected.</p>';
    echo '<p>Realm ID: ' . htmlspecialchars($result['realm_id']) . '</p>';
    echo '<p><a href="' . admin_url() . '">Return to WordPress Admin</a></p>';
    
} else {
    // Error - show error message
    echo '<h2>QuickBooks Authentication Failed</h2>';
    echo '<p>Error: ' . htmlspecialchars($result['error']) . '</p>';
    echo '<p>' . htmlspecialchars($result['message']) . '</p>';
    echo '<p><a href="' . admin_url() . '">Return to WordPress Admin</a></p>';
}

?>
