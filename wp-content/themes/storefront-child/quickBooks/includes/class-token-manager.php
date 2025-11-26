<?php
/**
 * QuickBooks Token Manager
 * 
 * Handles OAuth token storage, refresh, and validation
 * 
 * @package Matrix_QuickBooks
 * @version 1.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Token Manager Class
 */
class QuickBooks_Token_Manager {
    
    const TOKEN_META_KEY = 'sessionAccessToken';
    const TOKEN_EXPIRY_META_KEY = 'qb_token_expiry';
    const REFRESH_TOKEN_META_KEY = 'qb_refresh_token';
    
    /**
     * Get stored access token
     */
    public static function get_access_token() {
        // Try to get from post meta first (persistent storage)
        $stored_token = get_post_meta(1, self::TOKEN_META_KEY, true);
        
        if (!empty($stored_token) && isset($stored_token['session'])) {
            $token = $stored_token['session'];
            
            // Check if token is expired
            if (self::is_token_expired()) {
                return self::refresh_token();
            }
            
            return $token;
        }
        
        // Fallback to session
        if (!empty($_SESSION['sessionAccessToken'])) {
            return $_SESSION['sessionAccessToken'];
        }
        
        return null;
    }
    
    /**
     * Store access token
     */
    public static function store_access_token($token) {
        // Store in session for immediate use
        $_SESSION['sessionAccessToken'] = $token;
        
        // Store in WordPress meta for persistence
        $token_data = array('session' => $token);
        update_post_meta(1, self::TOKEN_META_KEY, $token_data);
        
        // Store token expiry if available
        if (method_exists($token, 'getTokenExpiresAt')) {
            $expiry = $token->getTokenExpiresAt();
            update_post_meta(1, self::TOKEN_EXPIRY_META_KEY, $expiry);
        }
        
        // Store refresh token if available
        if (method_exists($token, 'getRefreshToken')) {
            $refresh_token = $token->getRefreshToken();
            update_post_meta(1, self::REFRESH_TOKEN_META_KEY, $refresh_token);
        }
        
        return true;
    }
    
    /**
     * Check if token is expired
     */
    public static function is_token_expired() {
        $expiry = get_post_meta(1, self::TOKEN_EXPIRY_META_KEY, true);
        
        if (empty($expiry)) {
            return false; // If no expiry stored, assume token is valid
        }
        
        // Check if current time is past expiry (with 5 minute buffer)
        $current_time = time();
        $expiry_time = strtotime($expiry);
        $buffer = 5 * 60; // 5 minutes
        
        return ($current_time + $buffer) >= $expiry_time;
    }
    
    /**
     * Refresh expired token
     */
    public static function refresh_token() {
        try {
            global $dataService;
            
            // If no global dataService, create one for refresh
            if (empty($dataService)) {
                $dataService = self::create_data_service_for_refresh();
            }
            
            if (empty($dataService)) {
                error_log('QuickBooks: Could not create DataService for token refresh');
                return null;
            }
            
            $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
            $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
            
            if ($refreshedAccessTokenObj) {
                // Update the dataService with new token
                $dataService->updateOAuth2Token($refreshedAccessTokenObj);
                
                // Store the new token
                self::store_access_token($refreshedAccessTokenObj);
                
                error_log('QuickBooks: Token refreshed successfully');
                return $refreshedAccessTokenObj;
            }
            
        } catch (Exception $e) {
            error_log('QuickBooks: Token refresh failed: ' . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Create DataService instance for token refresh
     */
    private static function create_data_service_for_refresh() {
        try {
            // Get current token for refresh operation
            $currentToken = self::get_stored_token_raw();
            
            if (empty($currentToken)) {
                return null;
            }
            
            // Load config
            $config_path = dirname(__DIR__) . '/config.php';
            if (!file_exists($config_path)) {
                error_log('QuickBooks: Config file not found for token refresh');
                return null;
            }
            
            $config = include($config_path);
            
            // Create DataService for refresh
            $dataService = \QuickBooksOnline\API\DataService\DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $config['client_id'],
                'ClientSecret' => $config['client_secret'],
                'RedirectURI' => $config['oauth_redirect_uri'],
                'scope' => $config['oauth_scope'],
                'baseUrl' => "production",
            ));
            
            // Set current token
            $dataService->updateOAuth2Token($currentToken);
            
            return $dataService;
            
        } catch (Exception $e) {
            error_log('QuickBooks: Failed to create DataService for refresh: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get raw stored token without validation
     */
    private static function get_stored_token_raw() {
        $stored_token = get_post_meta(1, self::TOKEN_META_KEY, true);
        
        if (!empty($stored_token) && isset($stored_token['session'])) {
            return $stored_token['session'];
        }
        
        // Fallback to session
        if (!empty($_SESSION['sessionAccessToken'])) {
            return $_SESSION['sessionAccessToken'];
        }
        
        return null;
    }
    
    /**
     * Validate and prepare token for use
     */
    public static function get_valid_token() {
        $token = self::get_access_token();
        
        if (empty($token)) {
            error_log('QuickBooks: No valid token available');
            return null;
        }
        
        return $token;
    }
    
    /**
     * Clear stored tokens (for logout/reset)
     */
    public static function clear_tokens() {
        delete_post_meta(1, self::TOKEN_META_KEY);
        delete_post_meta(1, self::TOKEN_EXPIRY_META_KEY);
        delete_post_meta(1, self::REFRESH_TOKEN_META_KEY);
        
        if (isset($_SESSION['sessionAccessToken'])) {
            unset($_SESSION['sessionAccessToken']);
        }
        
        return true;
    }
}