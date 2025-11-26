<?php

add_action('template_redirect', 'redirect_to_specific_page');
function redirect_to_specific_page()
{
	//if (!isset($_GET['id'])) {
	if (!is_page('Login') && !is_user_logged_in()) {

		wp_redirect('/login', 301);
		exit;
	}
	// }
}


function my_body_classes($classes)
{
	$id_page = get_the_id();
	$queried_object = get_queried_object();
	if (is_object($queried_object)) {
		$slug = $queried_object->post_name;
	} else {
		// Handle the case where no object is returned
		$slug = '';
	}

	$classes[] = $id_page;
	$classes[] = $slug;

	if (is_page_template('template-multicart.php')) {
		$classes[] = 'multicart-page';
	}

	return $classes;
}


add_filter('body_class', 'my_body_classes');

// redirect for delivery
add_action('login_redirect', 'wpse_290317_redirect_user_to_page', 999, 3);
function wpse_290317_redirect_user_to_page($redirect_to, $request, $user)
{
	$role = $user->roles[0];
	if ($role == 'no_access') {
		wp_logout();
	}

	if ('DeliveryService' == $user->user_login) {
		$redirect_to = home_url('/delivery-service/');
		return $redirect_to;
	} else {
		return home_url();
	}
}


add_action('wp_head', 'logout_no_access');

function logout_no_access()
{
	$user = wp_get_current_user();
	if (in_array('no_access', (array)$user->roles)) {
		wp_logout();
	}
}


//teo Remove "— WordPress" from admin title
add_filter('admin_title', 'my_admin_title', 10, 2);
function my_admin_title($admin_title, $title)
{
	return get_bloginfo('name') . ' &bull; ' . $title;
}


//teo Custom login page title
function custom_login_title($login_title)
{
	return str_replace(array(' &lsaquo;', ' &#8212; WordPress'), array(' &bull;', ' Ordering System'), $login_title);
}


add_filter('login_title', 'custom_login_title');

