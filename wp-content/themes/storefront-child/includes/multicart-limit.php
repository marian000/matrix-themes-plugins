<?php
/**
 * Caps how many unfinished orders (multi-carts) one user can hold.
 *
 * The multisession plugin writes the whole cart list into a single Set-Cookie header on login and on
 * every cart update. Around 390 carts that header passes the 8 KB limit of Apache + PHP-FPM and the
 * request fails: the user cannot log in or add items any more.
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Maximum number of carts per user.
 */
function matrix_multicart_max()
{
	return (int) apply_filters('matrix_multicart_max', 100);
}

/**
 * The cart list normally comes from the multisession plugin (matrix-multicart-store.php).
 * Fallback for when that plugin is not active, so templates calling the helper do not fatal.
 */
if (!function_exists('matrix_multicart_session')) {
	function matrix_multicart_session($user_id)
	{
		$session = get_user_meta((int) $user_id, '_woocom_multisession', true);
		$session = is_array($session) ? $session : array();

		if (!isset($session['carts']) || !is_array($session['carts'])) {
			$session['carts'] = array();
		}
		if (!isset($session['customer_id'])) {
			$session['customer_id'] = 0;
		}

		return $session;
	}
}

/**
 * Number of carts the current user can see on My Orders, read the same way the plugin's AJAX handler reads them.
 *
 * Carts still carrying the automatic name "Order #N" are left out: render_cart_table() in template-multicart.php
 * hides them, so a user could not delete them to get back under the limit.
 */
function matrix_multicart_count()
{
	if (!function_exists('\BMC\WooCommerce\MultiSession\get_carts')) {
		return 0;
	}

	$carts = \BMC\WooCommerce\MultiSession\get_carts();
	if (!is_array($carts)) {
		return 0;
	}

	$count = 0;
	foreach ($carts as $cart) {
		$name = is_array($cart) && isset($cart['name']) ? (string) $cart['name'] : '';
		if (strpos($name, 'Order #') !== 0) {
			$count++;
		}
	}

	return $count;
}

function matrix_multicart_limit_message()
{
	return sprintf(
		'You have reached the maximum of %d unfinished orders. Please complete or delete some of them on the My Orders page before starting a new one.',
		matrix_multicart_max()
	);
}

/**
 * Refuse the plugin commands that create a cart once the user is at the limit.
 * Runs before the plugin's own handler (priority 10) on the same AJAX action.
 */
add_action('wp_ajax_bmc_woocom_multisession', 'matrix_multicart_limit_guard', 1);
function matrix_multicart_limit_guard()
{
	$command = isset($_POST['command']) ? strtolower(sanitize_text_field(wp_unslash($_POST['command']))) : '';
	$creating = array('insert', 'insertpos', 'insertcomponent', 'insertawning', 'insertapporder', 'clone');

	if (!in_array($command, $creating, true)) {
		return;
	}

	if (matrix_multicart_count() < matrix_multicart_max()) {
		return;
	}

	wp_send_json(array(
		'success' => false,
		'error' => 'cart_limit',
		'message' => matrix_multicart_limit_message(),
	));
}

/**
 * Tell the user why nothing happens: the plugin's JS ignores unsuccessful responses silently.
 */
add_action('wp_enqueue_scripts', 'matrix_multicart_limit_script', 20);
function matrix_multicart_limit_script()
{
	if (!is_user_logged_in() || !wp_script_is('bmc-woocom-multisession', 'enqueued')) {
		return;
	}

	$limit = array(
		'count' => matrix_multicart_count(),
		'max' => matrix_multicart_max(),
		'message' => matrix_multicart_limit_message(),
	);

	$script = '(function () {'
		. 'if (typeof BMCWcMs === "undefined" || typeof BMCWcMs.command !== "function") { return; }'
		. 'var limit = ' . wp_json_encode($limit) . ';'
		. 'var original = BMCWcMs.command;'
		. 'BMCWcMs.command = function (command, element) {'
		. 'if (limit.count >= limit.max && /^(insert|clone)/.test(command)) {'
		. 'if (typeof swal === "function") { swal("Order limit reached", limit.message, "warning"); } else { alert(limit.message); }'
		. 'return false;'
		. '}'
		. 'return original.apply(this, arguments);'
		. '};'
		. '})();';

	wp_add_inline_script('bmc-woocom-multisession', $script, 'after');
}
