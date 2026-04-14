<?php
/**
 * Custom Login Form Shortcode
 *
 * Usage: [custom_login_form]
 *
 * Displays a styled login form with:
 * - Username/Email field
 * - Password field
 * - Remember Me checkbox
 * - Red "Log In" button
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Custom Login Form Shortcode Handler
 */
function custom_login_form_shortcode() {
    if (is_user_logged_in()) {
        return '<p>You are already logged in. <a href="' . wp_logout_url(home_url()) . '">Logout</a></p>';
    }

    ob_start();
    ?>
    <div class="custom-login-wrapper">
        <form method="post" action="<?php echo esc_url(site_url('wp-login.php', 'login_post')); ?>" class="custom-login-form">
            <div class="form-group">
                <label for="custom_user_login">Username or Email</label>
                <input type="text" name="log" id="custom_user_login" class="form-control" required />
            </div>
            <div class="form-group">
                <label for="custom_user_pass">Password</label>
                <input type="password" name="pwd" id="custom_user_pass" class="form-control" required />
            </div>
            <div class="form-group remember-me">
                <label>
                    <input type="checkbox" name="rememberme" value="forever" /> Remember Me
                </label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn-login">Log In</button>
            </div>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url()); ?>" />
        </form>
    </div>
    <style>
        .custom-login-wrapper {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .custom-login-title {
            text-align: center;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .custom-login-form .form-group {
            margin-bottom: 20px;
        }
        .custom-login-form label {
            display: block;
            margin-bottom: 5px;
            color: #666;
            font-size: 14px;
        }
        .custom-login-form .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .custom-login-form .remember-me label {
            display: inline;
            cursor: pointer;
        }
        .custom-login-form .btn-login {
            width: 100%;
            padding: 12px;
            background: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .custom-login-form .btn-login:hover {
            background: #c0392b;
        }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_login_form', 'custom_login_form_shortcode');
