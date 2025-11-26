<?php
//teo - Function to change some Order Status Colors in Admin
add_action('admin_head', 'my_custom_order_colors');
// Culori status dashboard admin orders
function my_custom_order_colors()
{

	if (get_current_user_id() !== 1) {
		echo '<style>
       .status-inproduction {background-color: #7878e2; color: #ffffff;}
       .status-pending {background-color: #ef8181; color: #ffffff;}
       .status-transit {background-color: #85bb65; color: #efef58;}
       .status-account-on-hold {background-color: red; color: yellow; font-weight: bold;}
       .status-cancelled {background-color: red; color: yellow; font-weight: bold;}
       .status-cancelled ~ p { background-color: red; color: black !important; font-weight: bolder;}
       .status-inrevision  {background-color: #8B4513; color: yellow;}
       .status-revised  {background-color: #8B4513; color: yellow;}
       #titlediv div.inside {display: none;}
        td.order_status.column-order_status mark {display: none;}
        li#toplevel_page_wc-admin-path--analytics-overview {display: none;}
        a.button.wc-action-button.default-action-icon-selector {display: none !important;}
        .table-responsive {overflow-x: unset;}
      </style>';
	} else {
		echo '<style>
       .status-inproduction {background-color: #7878e2; color: #ffffff;}
       .status-pending {background-color: #ef8181; color: #ffffff;}
       .status-transit {background-color: #85bb65; color: #efef58;}
       .status-account-on-hold {background-color: red; color: yellow; font-weight: bold;}
       .status-cancelled {background-color: red; color: yellow; font-weight: bold;}
       .status-cancelled ~ p { background-color: red; color: black !important; font-weight: bolder;}
       .status-inrevision  {background-color: #8B4513; color: yellow;}
       .status-revised  {background-color: #8B4513; color: yellow;}
       #titlediv div.inside {display: none;}
       td.order_status.column-order_status mark {display: none;}
       .table-responsive {overflow-x: unset;}
      </style>';
	}

	if (current_user_can('china_admin')) {
		echo '<style>
      th#order_total, td.order_total.column-order_total {
        display: none !important;
      }
      </style>';
	}

	if (is_admin()) {
		$screen = get_current_screen();

		if ($screen->id == "dashboard") {
			// This is the admin Dashboard screen
		}
	}
}


//teo

/**
 * Adds a submenu page under a custom post type parent.
 */
add_action('admin_menu', 'matrix_dashboard_menu_control', 700);
function matrix_dashboard_menu_control()
{
	$current_user = wp_get_current_user();
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	if (in_array('china_admin', $current_user->roles) || in_array('outsider', $current_user->roles) && get_current_user_id() !== 192) {
		remove_menu_page('index.php');
		remove_menu_page('tools.php');
		remove_menu_page('themes.php');
		remove_menu_page('options-general.php');
		remove_menu_page('plugins.php');
		remove_menu_page('users.php');
		remove_menu_page('edit-comments.php');
		remove_menu_page('page.php');
		remove_menu_page('upload.php');
		remove_menu_page('edit.php?post_type=page');
//		remove_menu_page( 'edit.php?post_type=videos' );
//      remove_menu_page( 'edit.php?post_type=container' );
		remove_menu_page('edit.php?post_type=product');
		remove_menu_page('edit.php');
		remove_submenu_page('woocommerce', 'wc-admin');
		remove_submenu_page('woocommerce', 'wc-settings');
		remove_submenu_page('woocommerce', 'wc-status');
		remove_submenu_page('woocommerce', 'wc-addons');
		remove_submenu_page('woocommerce', 'wc-reports');
		remove_menu_page('wc-admin&path=/analytics/revenue');
	}

	if (in_array('employe', $current_user->roles)) {
//        if (get_current_user_id() !== 183) { // acces only to Teddington
		remove_menu_page('users.php');
//        }
		remove_menu_page('index.php');
		remove_menu_page('tools.php');
		remove_menu_page('themes.php');
		remove_menu_page('options-general.php');
		remove_menu_page('plugins.php');
		remove_menu_page('edit-comments.php');
		remove_menu_page('page.php');
		remove_menu_page('upload.php');
		remove_menu_page('edit.php?post_type=page');
		remove_menu_page('edit.php?post_type=product');
		remove_menu_page('edit.php?post_type=stgh_ticket');
		if (get_current_user_id() !== 183) { // acces only to Teddington
			remove_menu_page('edit.php?post_type=container');
		}
		remove_menu_page('edit.php');
		remove_menu_page('profile-builder');
		remove_menu_page('duplicator');
		remove_submenu_page('woocommerce', 'wc-admin');
		remove_submenu_page('woocommerce', 'wc-settings');
		remove_submenu_page('woocommerce', 'wc-status');
		remove_submenu_page('woocommerce', 'wc-addons');
		remove_submenu_page('woocommerce', 'wc-reports');
	}
	if (in_array('emplimited', $current_user->roles)) {
		remove_menu_page('users.php');
		remove_menu_page('index.php');
		remove_menu_page('tools.php');
		remove_menu_page('themes.php');
		remove_menu_page('options-general.php');
		remove_menu_page('plugins.php');
		remove_menu_page('edit-comments.php');
		remove_menu_page('page.php');
		remove_menu_page('upload.php');
		remove_menu_page('edit.php?post_type=page');
		remove_menu_page('edit.php?post_type=product');
		remove_menu_page('edit.php?post_type=stgh_ticket');
		remove_menu_page('edit.php?post_type=container');
		remove_menu_page('edit.php');
		remove_menu_page('profile-builder');
		remove_menu_page('duplicator');
		remove_submenu_page('woocommerce', 'wc-admin');
		remove_submenu_page('woocommerce', 'wc-settings');
		remove_submenu_page('woocommerce', 'wc-status');
		remove_submenu_page('woocommerce', 'wc-addons');
		remove_submenu_page('woocommerce', 'wc-reports');
	}
	if (get_current_user_id() !== 1) {
		//Hide "WooCommerce → Home".
		remove_submenu_page('woocommerce', 'wc-admin');
		//Hide "Analytics".
		remove_menu_page('wc-admin&path=/analytics/overview');
		//Hide "Analytics → Overview".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/overview');
		//Hide "Analytics → Products".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/products');
		//Hide "Analytics → Revenue".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/revenue');
		//Hide "Analytics → Orders".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/orders');
		//Hide "Analytics → Variations".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/variations');
		//Hide "Analytics → Categories".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/categories');
		//Hide "Analytics → Coupons".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/coupons');
		//Hide "Analytics → Taxes".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/taxes');
		//Hide "Analytics → Downloads".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/downloads');
		//Hide "Analytics → Stock".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/stock');
		//Hide "Analytics → Settings".
		remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/settings');

		//Hide "Marketing".
		remove_menu_page('woocommerce-marketing');
		//Hide "Marketing → Overview".
		remove_submenu_page('woocommerce-marketing', 'admin.php?page=wc-admin&path=/marketing');
		//Hide "Marketing → Coupons".
		remove_submenu_page('woocommerce-marketing', 'edit.php?post_type=shop_coupon');
	}

	/**
	 *    ADDING SUBMENU PAGES IN DASHBOARD
	 */
	add_submenu_page(
		'shuttermodule',
		__('Mail Settings', 'textdomain'),
		__('Mail Settings', 'textdomain'),
		'manage_options',
		'mail-matrix-ref',
		'mail_matrix_callback'
	);

	add_submenu_page(
		'shuttermodule',
		__('Users Orders', 'textdomain'),
		__('Users Orders', 'textdomain'),
		'manage_options',
		'users-orders-ref',
		'users_orders_callback'
	);

	add_submenu_page(
		'shuttermodule',
		__('Help Info', 'textdomain'),
		__('Help Info', 'textdomain'),
		'manage_options',
		'help-info',
		'help_info_callback'
	);

	add_submenu_page(
		'shuttermodule',
		__('Add Notification', 'textdomain'),
		__('Add Notification', 'textdomain'),
		'manage_options',
		'notify_users',
		'notify_users_callback'
	);

	add_submenu_page(
		'woocommerce',
		__('Users Grafic', 'textdomain'),
		__('Users Grafic', 'textdomain'),
		'manage_options',
		'users-orders-grafic',
		'users_grafics_callback'
	);

	add_submenu_page(
		'woocommerce',
		__('Users Group Grafic', 'textdomain'),
		__('Users Group Grafic', 'textdomain'),
		'manage_options',
		'users-group-grafic',
		'users_group_grafics_callback'
	);

	add_submenu_page(
		'woocommerce',
		__('Dealers List Info', 'textdomain'),
		__('Dealers List Info', 'textdomain'),
		'read',
		'dealers-info',
		'users_list_info_callback'
	);
}


/**
 * Display callback for users_orders.
 */
function mail_matrix_callback()
{
	get_template_part('views/settings/mail', 'settings');
}


/**
 * Display callback for users_orders.
 */
function users_orders_callback()
{
	get_template_part('views/user', 'orders');
}


/**
 * Display callback for help_info.
 */
function help_info_callback()
{
	get_template_part('views/help', 'info');
}


/**
 * Display callback for notify_users.
 */
function notify_users_callback()
{
	get_template_part('views/notify', 'users');
}


/**
 * Display callback for the users_grafics.
 */
function users_grafics_callback()
{
	get_template_part('views/users', 'grafic');
}


/**
 * Display callback for users_group_grafics
 */
function users_group_grafics_callback()
{
	get_template_part('views/users', 'group');
}


/**
 * Display callback for the submenu page.
 */
function users_list_info_callback()
{
	get_template_part('views/users-list', 'info');
}


// ADD COLOR TO IN PROGRESS BUTTON
add_action('admin_head', 'styling_admin_order_list');
function styling_admin_order_list()
{
	global $pagenow, $post;

	if ($pagenow != 'edit.php') return; // Exit
	if (get_post_type($post->ID) != 'shop_order') return; // Exit

	// HERE below set your custom status
	$order_status = 'Pending Payment'; // <==== HERE
	?>
  <style>
      .order-status.status-<?php echo sanitize_title( $order_status ); ?> {
          background: #cc0099;
          color: #ffffff;
      }

      button.btn.btn-primary.insert_orders.hide.hidden {
          display: none !important;
      }

      button.send_qcuickbooks_invoice.btn.btn-primary {
          font-size: 12px !important;
          width: 90px;
          padding: 5px 0;
      }
  </style>
	<?php
}


//teo Function to hide update notifications
function hide_update_notice_to_all_but_admin_users()
{
	if (!current_user_can('update_core')) {
		remove_action('admin_notices', 'update_nag', 3);
	}
}


add_action('admin_head', 'hide_update_notice_to_all_but_admin_users', 1);

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}


//add a link to the WP Toolbar
function wpb_custom_toolbar_link($wp_admin_bar)
{
	$user_id = get_current_user_id();
	$shipping_company = get_user_meta($user_id, 'shipping_company', true);
	$shipping_last_name = get_user_meta($user_id, 'shipping_last_name', true);
	$shipping_first_name = get_user_meta($user_id, 'shipping_first_name', true);

	if (!current_user_can('administrator')) {

		$screen = get_current_screen();
		if ($screen->id == "dashboard") {
			// This is the admin Dashboard screen
		} else {
			$args = array(
				'id' => 'top_menu_current_user',
				'title' => $shipping_company . ' (' . $shipping_first_name . ' ' . $shipping_last_name . ')<img class="carret-down" src="/wp-content/uploads/2018/06/caret-down.png">',
				'meta' => array(
					'class' => 'wpbeginner',
					'title' => $shipping_company . ' (' . $shipping_first_name . ' ' . $shipping_last_name . ')',
					'html' => '<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close"><li class="profile-ui"><a href="/profile"><img src="/wp-content/uploads/2018/06/profile.png" alt="user_pic" >Profile</a></li><li class="divider"></li><li class="logout-ui"><a data-method="delete" href="/wp-login.php?action=logout" rel="nofollow"><img src="/wp-content/uploads/2018/06/power-button-off.png" alt="logout_pic" >Logout</a></li></ul><style>.wp-core-ui #wp-admin-bar-top_menu_current_user { display:none }</style>',
				),
			);
			$wp_admin_bar->add_node($args);
		}
	}
}


add_action('admin_bar_menu', 'wpb_custom_toolbar_link', 999);

// Start - Custom submenu for Container
add_action('admin_menu', 'matrix_container_my_custom_submenu_page');
function matrix_container_my_custom_submenu_page()
{
	$allowed_user_ids = [1, 2, 18, 192, 354, 422]; // Array of allowed user IDs. Change these to the actual IDs you want to allow.
	$current_user_id = get_current_user_id();
	$user = wp_get_current_user();
	$allowed_roles = array('administrator', 'Emplimited');

	add_submenu_page(
		'edit.php?post_type=container',
		__('Containers Info', 'menu-container-info'),
		__('Containers Info', 'menu-container-info'),
		'manage_options',
		'container-info',
		'matrix_container_info_submenu_page_callback'
	);

	// Check if the current user is in the allowed list
	if (in_array($current_user_id, $allowed_user_ids) || array_intersect($allowed_roles, $user->roles)) {
		add_menu_page(
			__('Groups Portfolio', 'textdomain'), // Page title
			__('Groups Portfolio', 'textdomain'), // Menu title
			'manage_groups_portfolio', // Capability required to see this option
			'groups-portfolio', // Unique slug for your menu page
			'render_groups_portfolio_page', // The function that renders the menu page
			'dashicons-groups', // Icon URL (use a Dashicon or custom URL)
			50 // Position; just after WooCommerce's usual position
		);
		
		add_menu_page(
    		__('Frame Type Statistics', 'textdomain'), // Page title
    		__('Frame Type Stats', 'textdomain'), // Menu title
    		'manage_options', // Capability required to see this option (administrators only)
    		'frametype-statistics', // Unique slug for your menu page
    		'render_frametype_statistics_page', // The function that renders the menu page
    		'dashicons-chart-bar', // Icon URL (use a Dashicon or custom URL)
    		51 // Position; just after Groups Portfolio
    	);
	}
}

function add_groups_portfolio_capability()
{
	$role = get_role('outsider'); // Or any other role like 'administrator'
	$role->add_cap('manage_groups_portfolio', true);
}


add_action('admin_init', 'add_groups_portfolio_capability');


function matrix_container_info_submenu_page_callback()
{
	include 'templates/submenu-container-info.php';
}


function render_groups_portfolio_page()
{
	include 'templates/groups-portfolio.php';
}

function render_frametype_statistics_page()
{
	include 'templates/frametype-statistics.php';
}

// End - Custom submenu for Container