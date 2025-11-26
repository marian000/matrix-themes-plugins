<?php

if (!empty($_GET['cust_id'])) {
	$user_id = $_GET['cust_id'];
} else {
	$user_id = get_current_user_id();
}
$item_id = (isset($_GET['item_id'])) ? $_GET['item_id'] : '';
$clone_id = !empty($_GET['clone']) ? base64_decode($_GET['clone']) : '';

$dealer_id = get_user_meta($user_id, 'company_parent', true);

$edit_customer = (isset($_GET['order_edit_customer']) && $_GET['order_edit_customer'] == 'editable') ? true : false;
$order_edit = (!empty($_GET['order_id'])) ? $_GET['order_id'] / 1498765 / 33 : '';

$meta_key = 'wc_multiple_shipping_addresses';

global $wpdb;

if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
	$addresses = maybe_unserialize($addresses);
}

$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

$userialize_data = unserialize($addresses[0]->meta_value);

$session_key = $userialize_data['customer_id'];

$session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key");

//print_r($session);

$userialize_session = unserialize($session[0]->session_value);

$cart_name = '';
$attachment = '';
$product_id = '';
$nr_code_prod = array();
$items_name = array();
$first_item = null;
$i = 1;

$carts_sort = $userialize_data['carts'];

ksort($carts_sort);

$total_elements = count($carts_sort) + 1;

$cart_name = '';

foreach ($carts_sort as $key => $carts) {

	if ($userialize_data['customer_id'] == $key) {

		$cart_name = $carts['name'];
	}
}

$property_style = '';

$property_frametype = '';

$property_stile = '';

$property_horizontaltpost = '';

$property_tposttype = '';

$bay_post_type = '';

$attachment = '';

$material = '';

if (!empty($_GET['id'])) {

	$product_id = $_GET['id'] / 1498765 / 33;

	//echo "ID-prod: " . $_GET['id'] / 1498765 / 33;

	// echo "<br>";

	$meta = get_post_meta($_GET['id'] / 1498765 / 33);

	// echo "<pre>";

	// echo print_r($meta);

	// echo "</pre>";

	//echo '  price: ' . number_format(get_post_meta($product_id, '_regular_price', true), 2);

	$property_style = get_post_meta($product_id, 'property_style', true);

	$property_frametype = get_post_meta($product_id, 'property_frametype', true);

	$property_stile = get_post_meta($product_id, 'property_stile', true);

	$property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);

	$property_tposttype = get_post_meta($product_id, 'property_tposttype', true);

	$bay_post_type = get_post_meta($product_id, 'bay-post-type', true);

	$attachment = get_post_meta($product_id, 'attachment', true);

	$material = get_post_meta($product_id, 'property_material', true);
	$t_post_type = get_post_meta($product_id, 't-post-type', true);
	$property_builtout = get_post_meta($product_id, 'property_builtout', true);
	$property_solidtype = get_post_meta($product_id, 'property_solidtype', true);
	$property_trackedtype = get_post_meta($product_id, 'property_trackedtype', true);
	$property_bypasstype = get_post_meta($product_id, 'property_bypasstype', true);
	$property_lightblocks = get_post_meta($product_id, 'property_lightblocks', true);
	$property_tracksnumber = get_post_meta($product_id, 'property_tracksnumber', true);
	$property_colour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);

	$g1 = get_post_meta($product_id, 'property_g1', true);

	$g2 = get_post_meta($product_id, 'property_g2', true);

	$g3 = get_post_meta($product_id, 'property_g3', true);

	$g4 = get_post_meta($product_id, 'property_g4', true);

	$g5 = get_post_meta($product_id, 'property_g5', true);

	$g6 = get_post_meta($product_id, 'property_g6', true);

	$g7 = get_post_meta($product_id, 'property_g7', true);

	$g8 = get_post_meta($product_id, 'property_g8', true);

	$g9 = get_post_meta($product_id, 'property_g9', true);

	$g10 = get_post_meta($product_id, 'property_g10', true);

	$g11 = get_post_meta($product_id, 'property_g11', true);

	$g12 = get_post_meta($product_id, 'property_g12', true);

	$g13 = get_post_meta($product_id, 'property_g13', true);

	$g14 = get_post_meta($product_id, 'property_g14', true);

	$g15 = get_post_meta($product_id, 'property_g15', true);

	$t1 = get_post_meta($product_id, 'property_t1', true);

	$t2 = get_post_meta($product_id, 'property_t2', true);

	$t3 = get_post_meta($product_id, 'property_t3', true);

	$t4 = get_post_meta($product_id, 'property_t4', true);

	$t5 = get_post_meta($product_id, 'property_t5', true);

	$t6 = get_post_meta($product_id, 'property_t6', true);

	$t7 = get_post_meta($product_id, 'property_t7', true);

	$t8 = get_post_meta($product_id, 'property_t8', true);

	$t9 = get_post_meta($product_id, 'property_t9', true);

	$t10 = get_post_meta($product_id, 'property_t10', true);

	$t11 = get_post_meta($product_id, 'property_t11', true);

	$t12 = get_post_meta($product_id, 'property_t12', true);

	$t13 = get_post_meta($product_id, 'property_t13', true);

	$t14 = get_post_meta($product_id, 'property_t14', true);

	$t15 = get_post_meta($product_id, 'property_t15', true);

	$c1 = get_post_meta($product_id, 'property_c1', true);

	$c2 = get_post_meta($product_id, 'property_c2', true);

	$c3 = get_post_meta($product_id, 'property_c3', true);

	$c4 = get_post_meta($product_id, 'property_c4', true);

	$c5 = get_post_meta($product_id, 'property_c5', true);

	$c6 = get_post_meta($product_id, 'property_c6', true);

	$c7 = get_post_meta($product_id, 'property_c7', true);

	$c8 = get_post_meta($product_id, 'property_c8', true);

	$c9 = get_post_meta($product_id, 'property_c9', true);

	$c10 = get_post_meta($product_id, 'property_c10', true);

	$c11 = get_post_meta($product_id, 'property_c11', true);

	$c12 = get_post_meta($product_id, 'property_c12', true);

	$c13 = get_post_meta($product_id, 'property_c13', true);

	$c14 = get_post_meta($product_id, 'property_c14', true);

	$c15 = get_post_meta($product_id, 'property_c15', true);

	$bp1 = get_post_meta($product_id, 'property_bp1', true);

	$bp2 = get_post_meta($product_id, 'property_bp2', true);

	$bp3 = get_post_meta($product_id, 'property_bp3', true);

	$bp4 = get_post_meta($product_id, 'property_bp4', true);

	$bp5 = get_post_meta($product_id, 'property_bp5', true);

	$bp6 = get_post_meta($product_id, 'property_bp6', true);

	$bp7 = get_post_meta($product_id, 'property_bp7', true);

	$bp8 = get_post_meta($product_id, 'property_bp8', true);

	$bp9 = get_post_meta($product_id, 'property_bp9', true);

	$bp10 = get_post_meta($product_id, 'property_bp10', true);

	$bp11 = get_post_meta($product_id, 'property_bp12', true);

	$bp12 = get_post_meta($product_id, 'property_bp13', true);

	$bp14 = get_post_meta($product_id, 'property_bp14', true);

	$bp15 = get_post_meta($product_id, 'property_bp15', true);

	$ba1 = get_post_meta($product_id, 'property_ba1', true);

	$ba2 = get_post_meta($product_id, 'property_ba2', true);

	$ba3 = get_post_meta($product_id, 'property_ba3', true);

	$ba4 = get_post_meta($product_id, 'property_ba4', true);

	$ba5 = get_post_meta($product_id, 'property_ba5', true);

	$ba6 = get_post_meta($product_id, 'property_ba6', true);

	$ba7 = get_post_meta($product_id, 'property_ba7', true);

	$ba8 = get_post_meta($product_id, 'property_ba8', true);

	$ba9 = get_post_meta($product_id, 'property_ba9', true);

	$ba10 = get_post_meta($product_id, 'property_ba10', true);

	$ba11 = get_post_meta($product_id, 'property_ba11', true);

	$ba12 = get_post_meta($product_id, 'property_ba12', true);

	$ba13 = get_post_meta($product_id, 'property_ba13', true);

	$ba14 = get_post_meta($product_id, 'property_ba14', true);

	$ba15 = get_post_meta($product_id, 'property_ba15', true);

	$nr_g = get_post_meta($product_id, 'counter_g', true);

	$nr_t = get_post_meta($product_id, 'counter_t', true);

	$nr_b = get_post_meta($product_id, 'counter_b', true);

	$nr_c = get_post_meta($product_id, 'counter_c', true);

	$nr_code_prod = array();

	$nr_code_prod['g'] = $nr_g;

	$nr_code_prod['t'] = $nr_t;

	$nr_code_prod['b'] = $nr_b;

	$nr_code_prod['c'] = $nr_c;

	global $woocommerce;

	$cart = WC()->cart->get_cart();

//        echo '<pre>'; print_r($cart); echo '</pre>';

	foreach ($cart as $cart_item_key => $cart_item) {

		$products = $cart_item['data']->get_id();

		//print_r($products);

		//echo $cart_item['quantity'];

		if ($products == $product_id) {

			$quant = $cart_item['quantity'];
		}
		// echo '<pre>'; print_r($cart_item); echo '</pre>';

	}
} else {
	$cart = WC()->cart->get_cart();
	if (count($cart) >= 1 && $edit_customer == false) {

		foreach ($cart as $cart_item_key => $cart_item) {
			// get id product
			$product_id_cart = $cart_item['product_id'];
			$property_room_other = get_post_meta($product_id_cart, 'property_room_other', true);
			$items_name[] = str_replace("'", "", $property_room_other);

			// if clone id existsin GET and is equal to prod id from cart then update as principal id
			if (!empty($clone_id) && $clone_id == $product_id_cart) {
				update_post_meta($product_id_cart, 'clone_prod_id', $clone_id);
			} // if clone id existsin GET and is not equal to prod id from cart then update empty
            elseif (!empty($clone_id) && $clone_id != $product_id_cart) {
				update_post_meta($product_id_cart, 'clone_prod_id', '');
			}
		}

		if ($clone_id == '') {
			$clone_id = get_post_meta($product_id_cart, 'clone_prod_id', true);
		}

		$first_item = array_shift($cart);
		$first_prod_id = !empty($clone_id) ? $clone_id : $first_item['product_id'];

		$material = get_post_meta($first_prod_id, 'property_material', true);
		$property_style = get_post_meta($first_prod_id, 'property_style', true);
		$bladesize_first = get_post_meta($first_prod_id, 'property_bladesize', true);
		// Frame Type, Left, Right, Top, Bottom, Ad buildout, Stile
		if (!in_array($property_style, [35, 38, 39, 40, 41])) {
			$property_frametype = get_post_meta($first_prod_id, 'property_frametype', true);
			$frameleft_first = get_post_meta($first_prod_id, 'property_frameleft', true);
			$frameright_first = get_post_meta($first_prod_id, 'property_frameright', true);
			$frametop_first = get_post_meta($first_prod_id, 'property_frametop', true);
			$framebottom_first = get_post_meta($first_prod_id, 'property_framebottom', true);
			$property_builtout = get_post_meta($first_prod_id, 'property_builtout', true);
			$property_stile = get_post_meta($first_prod_id, 'property_stile', true);
		}
		// Hinge Colour, Shutter Colour, Control Type
		$hingecolour_first = get_post_meta($first_prod_id, 'property_hingecolour', true);
		$shuttercolour_first = get_post_meta($first_prod_id, 'property_shuttercolour', true);
		$property_colour_other = get_post_meta($first_prod_id, 'property_shuttercolour_other', true);
		$controltype_first = get_post_meta($first_prod_id, 'property_controltype', true);
	}
}

?>

<!-- Latest compiled and minified CSS -->

<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

<div class="main-content">

    <div class="page-content" style="background-color: #F7F7F7">

        <div class="page-content-area">

            <div class="page-header">

				<?php

				if (!empty($_GET['id'])) {

					?>

                    <h1>Update Shutter & Blackout Blind - <?php echo $cart_name; ?></h1>

					<?php
				} elseif (!empty($_GET['order_id'])) { ?>
                    <h1>Add Shutter & Blackout Blind</h1>
				<?php } else {

					?>

                    <h1>Add Shutter & Blackout Blind - <?php echo $cart_name; ?></h1>

					<?php
				}

				?>

            </div>
			<?php
			// Define an array of all the prefixes used in the input IDs
			$prefixes = ['g', 't', 'c', 'ba', 'bp'];

			// Loop through each prefix
			foreach ($prefixes as $prefix) {
				// For each prefix, loop through numbers 1 to 15
				for ($i = 1; $i <= 15; $i++) {
					// Dynamically construct the variable name based on the prefix and current number
					$varName = $prefix . $i;
					// Dynamically create the input field with the value from the corresponding variable
					// Check if the variable exists and is set to avoid undefined variable notices
					if (isset($$varName)) {
						echo '<input type="hidden" id="' . $varName . '" name="' . $varName . '" value="' . $$varName . '">' . PHP_EOL;
					}
				}
			}
			?>
            <div class="row">

                <form <?php if (!empty($_GET['id'])) {
					echo 'edit="yes"';
				} else {
					echo 'edit="no"';
				} ?> accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                     enctype="multipart/form-data"

                     id="add-product-single-form" method="post">

                    <div class="col-xs-12">

                        <div class="row">

                            <div class="col-sm-9">

                                <strong>Order Reference:</strong> <?php echo $cart_name; ?>

                                <br/>

                                <br/>

                            </div>

                            <div class="col-sm-3 pull-right">

                                Total Square Meters&nbsp;

                                <input class="input-small" id="property_total" name="property_total" type="text"
                                       value=""/>

                            </div>

                        </div>

                        <div style="display:none">

                        </div>

                        <input type="hidden" name="product_id_updated" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="dealer_id" value="<?php echo $dealer_id; ?>">
                        <input type="hidden" name="edit_customer" value="<?php echo $edit_customer; ?>">
                        <input type="hidden" name="order_edit" value="<?php echo $order_edit; ?>">
                        <input type="hidden" name="cart_items_name" value='<?php echo json_encode($items_name); ?>'>

                        <div class="row">

                            <div class="col-sm-12">

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                    <div class="panel panel-default done">

                                        <div class="panel-heading" role="tab" id="headingStyle">

                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseStyle" aria-expanded="true"
                                                   aria-controls="collapseStyle">
                                                    <strong>Shutters Design</strong>

                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <span type="button" class="js-next-step next-step"
                                                          tabindex="2">Next step</span>
                                                </a>
                                            </h4>

                                        </div>

                                        <div id="collapseStyle" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingStyle">

                                            <div class="panel-body">

                                                <div class="row">

                                                    <div class="col-sm-3" style="display:none;">

                                                        Room:

                                                        <br/>

                                                        <input class="property-select" id="property_room"
                                                               name="property_room" type="text" value="94"/>

                                                    </div>

                                                    <div class="col-sm-3" id="room-other" style="display: block">

                                                        Room Name:

                                                        <br/>

                                                        <input class="input-medium required" id="property_room_other"
                                                               name="property_room_other" style="height: 30px"
                                                               type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_room_other', true); ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-3"> Material:
                                                        <br/>
                                                        <input class="property-select" id="property_material"
                                                               name="property_material" type="text"
                                                               value="<?php if (!empty($_GET['id'])) {
															       echo get_post_meta($product_id, 'property_material', true);
														       } elseif ($first_item) {
															       echo $material;
														       } else {
															       echo '138';
														       } ?>"/>
                                                        <input id="product_id" name="product_id" type="hidden"
                                                               value=""/>
                                                    </div>

                                                </div>

                                                <div class="row" style="margin-top:1em;">

                                                    <div class="col-sm-12">

                                                        Installation Style:

                                                        <div id="choose-style">

                                                            <label>

                                                                <br/> Full Height

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="fullheight" data-title="Full Height"
                                                                       value="29" <?php if ($property_style == '29') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Full-Height.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                <br/> Tier-on-Tier

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="tot" data-title="Tier-on-Tier"
                                                                       value="31" <?php if ($property_style == '31') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Tier-On-Tier.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                <br/> Café Style

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="cafe" data-title="Café Style"
                                                                       value="30" <?php if ($property_style == '30') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Cafe-Style.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                <br/> Bay Window

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="bay" data-title="Bay Window"
                                                                       value="32" <?php if ($property_style == '32') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Bay-Window.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                Bay Window<br/>Tier-on-Tier

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="bay-tot"
                                                                       data-title="Bay Window Tier-on-Tier"
                                                                       value="146" <?php if ($property_style == '146') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Tier-On-Tier.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                Bay Window<br/>Café Style

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="cafe-bay"
                                                                       data-title="Café Style Bay Window"
                                                                       value="225" <?php if ($property_style == '225') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Cafe-Style.png">

                                                            </label>

                                                            <label>

                                                                <br/> Solid Full Height

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-flat"
                                                                       data-title="Solid Flat Panel"
                                                                       value="221" <?php if ($property_style == '221') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                Solid

                                                                <br/>Tier-on-Tier

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-flat-tot"
                                                                       data-title="Solid Flat Tier-on-Tier"
                                                                       value="227" <?php if ($property_style == '227') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel-Tier-On-Tier.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                Solid

                                                                <br/>Café Style

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-raised-cafe-style"
                                                                       data-title="Solid Raised Café Style"
                                                                       value="226" <?php if ($property_style == '226') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Raised-Panel-Cafe-Style.png">

                                                            </label>

                                                            <label>

                                                                <br/> Combi Panel

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-combi" data-title="Combi Panel"
                                                                       value="229" <?php if ($property_style == '229') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Combi-Panel.png">

                                                            </label>

                                                            <label>

                                                                Solid Panel Bay Window Full Height

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-flat-bay"
                                                                       data-title="Solid Panel Bay Window Full Height"
                                                                       value="230" <?php if ($property_style == '230') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Bay-Window-Full-Height.png">

                                                            </label>

                                                            <label>

                                                                Solid Panel Bay Window Tier-on-Tier

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-flat-bay-tot"
                                                                       data-title="Solid Panel Bay Window Tier-on-Tier"
                                                                       value="231" <?php if ($property_style == '231') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Bay-Window-Tier-On-Tier.png">

                                                            </label>

                                                            <label>

                                                                Solid Panel Bay Window Cafe Style

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-raised-bay-cafe-style"
                                                                       data-title="Solid Panel Bay Window Cafe Style"
                                                                       value="232" <?php if ($property_style == '232') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Bay-Window-Cafe-Style.png">

                                                            </label>

                                                            <label>

                                                                Combi Panel Bay Window

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="solid-combi-bay"
                                                                       data-title="Solid Combi Panel Bay Window"
                                                                       value="233" <?php if ($property_style == '233') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Combi-Panel-Bay-Window.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                <br/> Arched Shaped

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="shaped" data-title="Arched Shaped"
                                                                       value="36" <?php if ($property_style == '36') {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/Arched.png">

                                                            </label>

                                                            <label style="display:none;">

                                                                <br/> Special Shaped

                                                                <br/>

                                                                <input type="radio" name="property_style"
                                                                       data-code="shaped" data-title="Special Shaped"
                                                                       value="33" <?php if ($property_style == '33') {

																	echo "checked";
																} ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Special-Shape.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> French Door Cut
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="french" data-title="French Door Cut"
                                                                       value="34" <?php if ($property_style == '34') {
																	echo "checked";
																} ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/French-Door-Cut.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Tracked Bifold
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked" data-title="Tracked"
                                                                       value="35" <?php if ($property_style == '35') {
																	echo "checked";
																} ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Tracked-By-Fold.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Tracked By-Pass
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked" data-title="Tracked"
                                                                       value="37" <?php if ($property_style == '37') {
																	echo "checked";
																} ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Tracked-By-Pass.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Solid Tracked By-Pass
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked"
                                                                       data-title="Solid Tracked By-Pass"
                                                                       value="38"
																  <?php if ($property_style == '38') {
																	  echo "checked";
																  } ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Tracked-ByPass.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Solid Tracked By-Fold
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked"
                                                                       data-title="Solid Tracked By-Fold"
                                                                       value="39"
																  <?php if ($property_style == '39') {
																	  echo "checked";
																  } ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Solid-Tracked-BiFold.png">
                                                            </label>

                                                            <label style="display:none;">
                                                                <br/> Combi Tracked By-Pass
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked"
                                                                       data-title="Combi Tracked By-Pass"
                                                                       value="40"
																  <?php if ($property_style == '40') {
																	  echo "checked";
																  } ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Combi-Tracked-ByPass.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Combi Tracked By-Fold
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tracked"
                                                                       data-title="Combi Tracked By-Fold"
                                                                       value="41"
																  <?php if ($property_style == '41') {
																	  echo "checked";
																  } ?> />
                                                                <img src="/wp-content/plugins/shutter-module/imgs/Combi-Tracked-BiFold.png">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5" id="shape-section" style="display: block">
                                                        Shape: (max size 8MB)
                                                        <br/>
                                                        <div id="shape-upload-container">
                                <span id="provided-shape">
                                </span>

                                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->

                                                            <input id="frontend-button" type="button"
                                                                   value="Select File to Upload" class="button"
                                                                   style="position: relative; z-index: 1;">

                                                            <img id="frontend-image" src="<?php echo $attachment; ?>"/>

                                                            <input type="text" id="attachment" name="attachment"
                                                                   style="visibility: hidden;"
                                                                   value="<?php if ($attachment) {

																       echo $attachment;
															       } ?>"/>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row" style="margin-top:1em;  margin-bottom: 10px;">

                                                    <div class="col-sm-2">

                                                        Width (mm):

                                                        <br/>

                                                        <input class="required number input-medium" id="property_width"
                                                               name="property_width" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_width', true); ?>"/>

                                                    </div>

                                                    <div class="col-sm-2">

                                                        Height (mm):

                                                        <br/>

                                                        <input class="required number input-medium" id="property_height"
                                                               name="property_height" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_height', true); ?>"/>

                                                    </div>

                                                    <div class="col-sm-2" id="midrail-height">

                                                        Midrail Height (mm):

                                                        <br/>

                                                        <input class="required number input-medium"
                                                               id="property_midrailheight" name="property_midrailheight"
                                                               type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight', true); ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-2" id="midrail-height2">

                                                        Midrail Height 2 (mm):

                                                        <br/>

                                                        <input class=" number input-medium" id="property_midrailheight2"
                                                               name="property_midrailheight2" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight2', true); ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-2" id="midrail-divider">

                                                        Hidden Divider 1 (mm):

                                                        <br/>

                                                        <input class=" number input-medium"
                                                               id="property_midraildivider1"
                                                               name="property_midraildivider1" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider1', true); ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-2" id="midrail-divider2">

                                                        Hidden Divider 2 (mm):

                                                        <br/>

                                                        <input class=" number input-medium"
                                                               id="property_midraildivider2"
                                                               name="property_midraildivider2" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider2', true); ?>"

                                                        />

                                                    </div>

                                                    <div id="solidtype" class="col-sm-2" style="display: none"> Solid
                                                        Type:
                                                        <br/>
                                                        <input name="property_solidtype"
                                                               type="radio" <?php if ($property_solidtype == 'flat') echo 'checked'; ?>
                                                               value="flat"/> Flat
                                                        <br/>
                                                        <input name="property_solidtype"
                                                               type="radio" <?php if ($property_solidtype == 'Double Raised') echo 'Double Raised'; ?>
                                                               value="raised"/> Double Raised
                                                    </div>

                                                    <div class="col-sm-2" id="solid-panel-height" style="display:none;">

                                                        Solid Panel Height (mm):

                                                        <br/>

                                                        <input class=" number input-medium"
                                                               id="property_solidpanelheight"
                                                               name="property_solidpanelheight" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_solidpanelheight', true); ?>"/>

                                                    </div>

                                                    <div class="col-sm-2" id="midrail-position-critical">

                                                        Position is Critical:

                                                        <br/>

                                                        <div class="input-group-container">

                                                            <div class="input-group">

																<?php

																if (!empty($_GET['id'])) {

																	?>

                                                                    <input class="property-select"
                                                                           id="property_midrailpositioncritical"
                                                                           name="property_midrailpositioncritical"
                                                                           type="text"
                                                                           value="<?php echo get_post_meta($product_id, 'property_midrailpositioncritical', true); ?>"/>

																	<?php
																} else {

																	?>

                                                                    <input class="property-select"
                                                                           id="property_midrailpositioncritical"
                                                                           name="property_midrailpositioncritical"
                                                                           type="text" value="170"/>

																	<?php
																}

																?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-2 tot-height" style="display: none">

                                                        T-o-T Height (mm):

                                                        <br/>

                                                        <input class="required number input-medium"
                                                               id="property_totheight" name="property_totheight"
                                                               type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_totheight', true); ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-2 tot-height horizontal-t-post"
                                                         style="display: none" ata-toggle="tooltip"
                                                         title="The horizontal T Post will be apllied at T-o-T height">

                                                        Horizontal T Post

                                                        <br/>

                                                        <input id="property_horizontaltpost"
                                                               name="property_horizontaltpost" type="checkbox"
                                                               value="Yes" <?php if ($property_horizontaltpost == 'Yes') {

															echo "checked";
														} ?>/>

                                                    </div>

                                                    <div class="col-sm-2">

                                                        Louvre Size:

                                                        <br/>
                                                        <input class="property-select required bladesize_porperty"
                                                               id="property_bladesize" name="property_bladesize"
                                                               type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $bladesize_first;
														       } else {
															       echo get_post_meta($product_id, 'property_bladesize', true);
														       }
														       ?>"/>
                                                    </div>

                                                </div>

                                                <div class="row" style="margin-bottom: 10px;">

                                                    <div class="col-sm-3 property_fit">

                                                        Fit:

                                                        <br/>

                                                        <div class="input-group-container">

                                                            <div class="input-group">

																<?php

																if (!empty($_GET['id'])) {

																	?>

                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text"
                                                                           value="<?php echo get_post_meta($product_id, 'property_fit', true); ?>"/>

																	<?php
																} else {

																	?>

                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text"
                                                                           value="outside"/>

																	<?php
																}

																?>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-12">

                                                        <hr/>

                                                        <button class="btn btn-info show-next-panel">

                                                            Next

                                                            <i class="fa fa-chevron-right"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="panel panel-default inactive">

                                        <div class="panel-heading" role="tab" id="headingOne">

                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                                   aria-controls="collapseOne">
                                                    <strong>Frame &amp; Stile Design</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <span type="button" class="js-next-step next-step"
                                                          tabindex="2">Next step</span>
                                                </a>
                                            </h4>

                                        </div>

                                        <div id="collapseOne" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingOne">

                                            <div class="panel-body">

                                                <div class="row">

                                                    <div class="col-sm-12">

                                                        Frame Type:

                                                        <p id="required-choices-frametype">

                                                            <i>Please select Material &amp; Style in order to view
                                                                available Frame Type choices</i>

                                                        </p>

                                                        <div id="choose-frametype">

                                                            <label>
                                                                PVC

                                                                <br/> P4028X

                                                                <input type="radio" name="property_frametype"
                                                                       data-code="BL90" data-title="BL90"
                                                                       value="171" <?php if ($property_frametype == '171'

																) {

																	echo "checked";
																} ?> />

                                                                <img src="/wp-content/plugins/shutter-module/imgs/P4028X.png">

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row frames" style="margin-top:1em; margin-bottom: 20px;">

                                                    <div class="col-sm-12">

														<?php

														if (!empty($_GET['id'])) {

															?>

                                                            <div class="pull-left" id="frame-left">

                                                                Frame Left

                                                                <i class="fa fa-arrow-left"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frameleft"
                                                                       name="property_frameleft" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frameleft_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frameleft', true);
																       }
																       ?>""

                                                                />

                                                            </div>

                                                            <div class="pull-left" id="frame-right">

                                                                Frame Right

                                                                <i class="fa fa-arrow-right"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frameright"
                                                                       name="property_frameright" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frameright_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frameright', true);
																       }
																       ?>""

                                                                />

                                                            </div>

                                                            <div class="pull-left" id="frame-top">

                                                                Frame Top

                                                                <i class="fa fa-arrow-up"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frametop"
                                                                       name="property_frametop" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $frametop_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_frametop', true);
																       }
																       ?>""

                                                                />

                                                            </div>

                                                            <div class="pull-left" id="frame-bottom">

                                                                Frame Bottom

                                                                <i class="fa fa-arrow-down"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_framebottom"
                                                                       name="property_framebottom" type="text"
                                                                       value="<?php
																       if ($first_item) {
																	       echo $framebottom_first;
																       } else {
																	       echo get_post_meta($product_id, 'property_framebottom', true);
																       }
																       ?>""

                                                                />

                                                            </div>

															<?php
														} else {

															?>

                                                            <div class="pull-left" id="frame-left">

                                                                Frame Left

                                                                <i class="fa fa-arrow-left"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frameleft"
                                                                       name="property_frameleft" type="text"
                                                                       value="70"/>

                                                            </div>

                                                            <div class="pull-left" id="frame-right">

                                                                Frame Right

                                                                <i class="fa fa-arrow-right"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frameright"
                                                                       name="property_frameright" type="text"
                                                                       value="75"/>

                                                            </div>

                                                            <div class="pull-left" id="frame-top">

                                                                Frame Top

                                                                <i class="fa fa-arrow-up"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_frametop"
                                                                       name="property_frametop" type="text" value="80"/>

                                                            </div>

                                                            <div class="pull-left" id="frame-bottom">

                                                                Frame Bottom

                                                                <i class="fa fa-arrow-down"></i>

                                                                <br/>

                                                                <input class="property-select" id="property_framebottom"
                                                                       name="property_framebottom" type="text"
                                                                       value="85"/>

                                                            </div>

															<?php
														}
														$display = (!empty($property_builtout)) ? 'inline' : 'none';
														$add_buildout = (empty($property_builtout)) ? '' : 'display: none';
														?>

                                                        <div class="pull-left">
                                                            <span id="add-buildout"
                                                                  style="<?php echo $add_buildout; ?>">
                                                                <br/>
                                                                <button class="btn btn-info" style="padding: 0 12px">Add buildout</button>
                                                            </span>
                                                            <span id="buildout"
                                                                  style="display: <?php echo $display; ?>">                                                                 Buildout:                                                                 <br/>                                                                 <input
                                                                  class="input-small" id="property_builtout"
                                                                  name="property_builtout"
                                                                  placeholder="Enter buildout"
                                                                  style="height: 30px;" type="text"
                                                                  value="<?php
																  echo $property_builtout;
																  ?>"/>                                                                 <button
                                                                  class="btn btn-danger btn-input"
                                                                  id="remove-buildout">                                                                   <strong>X</strong>                                                                 </button>                                                               </span>
                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12" id="stile-img-earth">
                                                        <div id="choose-stiletype" class="" style="display: block;">
                                                            <label>
                                                                <br/> 60mm A1002B<br/>(Std.beaded stile)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="60mm A1002B (Std.beaded stile)"
                                                                       value="350" <?php if ($property_stile == '350' && $material == 187) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-1" src="/wp-content/plugins/shutter-module/imgs/A1002B.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 60mm A1006D<br/>(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="60mm A1006D (beaded D-mould)"
                                                                       value="354"
																  <?php if ($property_stile == '354' && $material == 187) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/A1006D.png"/>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-ecowood">

                                                        <div id="choose-stiletype" class="" style="display: block;">

                                                            <label>

                                                                <br/> 51mm PVC-P1001B<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm PVC-P1001B(plain butt)"

                                                                       value="380" <?php if ($property_stile == '380' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1001B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1005B<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="51mm PVC-P1005B(plain D-mould)"

                                                                       value="381" <?php if ($property_stile == '381' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1005B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1003E<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm PVC-P1003E(plain rebate)"

                                                                       value="385" <?php if ($property_stile == '385' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1003B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1002B<br/>(beaded butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.8"
                                                                       data-title="51mm PVC-P1002B(beaded butt)"

                                                                       value="382" <?php if ($property_stile == '382' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1002B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1006B<br/>(beaded D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm PVC-P1006B(beaded D-mould)"

                                                                       value="383" <?php if ($property_stile == '383' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1006B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1004E<br/>(beaded rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm PVC-P1004E(beaded rebate)"

                                                                       value="384" <?php if ($property_stile == '384' && $material == 188) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1004B.png"/>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-supreme">

                                                        <div id="choose-stiletype" class="" style="display: block;">
<!-- 35mm stiles removed for Supreme -->

                                                            <label>

                                                                <br/> 41mm 1001M<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="41mm 1001M(plain butt)"

                                                                       value="376" <?php if ($property_stile == '376' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1001M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1005M<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="41mm 1005M(plain D-mould)"

                                                                       value="377" <?php if ($property_stile == '377' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1005M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1003M<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm 1003M(plain rebate)"

                                                                       value="378" <?php if ($property_stile == '378' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1001B<br/>( plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm 1001B( plain butt)"
                                                                       value="355" <?php if ($property_stile == '355' && $material == 139) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1001B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1005B<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="51mm 1005B(plain D-mould)"
                                                                       value="356" <?php if ($property_stile == '356' && $material == 139

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1005B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1003B<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm 1003B(plain rebate)"
                                                                       value="360" <?php if ($property_stile == '360' && $material == 139

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1002B<br/>(beaded butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.8"
                                                                       data-title="51mm 1002B(beaded butt)"
                                                                       value="357" <?php if ($property_stile == '357' && $material == 139

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1002B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1006B<br/>(beaded D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm 1006B(beaded D-mould)"
                                                                       value="358" <?php if ($property_stile == '358' && $material == 139

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1006B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm 1004B<br/>(beaded rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm 1004B(beaded rebate)"
                                                                       value="359" <?php if ($property_stile == '359' && $material == 139

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1004B.png"/>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-biowood">

                                                        <div id="choose-stiletype" class="" style="display: block;">

                                                            <label>

                                                                <br/> 41mm 1001M<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="41mm 1001M(plain butt)"

                                                                       value="376" <?php if ($property_stile == '376' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1001M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1005M<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="41mm 1005M(plain D-mould)"

                                                                       value="377" <?php if ($property_stile == '377' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1005M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1003M<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm 1003M(plain rebate)"

                                                                       value="378" <?php if ($property_stile == '378' && $material == 6) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003M.png"/>

                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1002M<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1002M(beaded butt)"
                                                                       value="445"
																  <?php if ($property_stile == '445' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1006M<br/>(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1006M(beaded D-mould)"
                                                                       value="447"
																  <?php if ($property_stile == '447' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1004M<br/>(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1004M(beaded rebate)"
                                                                       value="446"
																  <?php if ($property_stile == '446' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1004M.png"/>
                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1001K<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm T1001K(plain butt)"
                                                                       value="370" <?php if ($property_stile == '370' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1001K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1005K<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="51mm T1005K(plain D-mould)"
                                                                       value="371" <?php if ($property_stile == '371' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1005K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1003K<br/>(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm T1003K(plain rebate)"
                                                                       value="375" <?php if ($property_stile == '375' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1003K.png"/>

                                                            </label>

                                                            <label>
                                                                <br/> 51mm T1002K<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.81"
                                                                       data-title="51mm T1002K(beaded butt)"
                                                                       value="372" <?php if ($property_stile == '372' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1006K<br/>(beaded D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm T1006K(beaded D-mould)"
                                                                       value="373" <?php if ($property_stile == '373' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1004K<br/>(beaded rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm T1004K(beaded rebate)"
                                                                       value="374" <?php if ($property_stile == '374' && $material == 6

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1004K.png"/>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-biowoodPlus">

                                                        <div id="choose-stiletype" class="" style="display: block;">

                                                            <label>

                                                                <br/> 41mm 1001M<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="41mm 1001M(plain butt)"

                                                                       value="376" <?php if ($property_stile == '376' && $material == 138) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1001M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1005M<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="41mm 1005M(plain D-mould)"

                                                                       value="377" <?php if ($property_stile == '377' && $material == 138) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1005M.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 41mm 1003M<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm 1003M(plain rebate)"

                                                                       value="378" <?php if ($property_stile == '378' && $material == 138) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003M.png"/>

                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1002M<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1002M(beaded butt)"
                                                                       value="445"
																  <?php if ($property_stile == '445' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1006M<br/>(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1006M(beaded D-mould)"
                                                                       value="447"
																  <?php if ($property_stile == '447' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1004M<br/>(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1004M(beaded rebate)"
                                                                       value="446"
																  <?php if ($property_stile == '446' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1004M.png"/>
                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1001K<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm T1001K(plain butt)"
                                                                       value="370" <?php if ($property_stile == '370' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1001K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1005K<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="51mm T1005K(plain D-mould)"
                                                                       value="371" <?php if ($property_stile == '371' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1005K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1003K<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm T1003K(plain rebate)"
                                                                       value="375" <?php if ($property_stile == '375' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1003K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1002K<br/>(beaded butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.81"
                                                                       data-title="51mm T1002K(beaded butt)"
                                                                       value="372" <?php if ($property_stile == '372' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1006K<br/>(beaded D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm T1006K(beaded D-mould)"
                                                                       value="373" <?php if ($property_stile == '373' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006K.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm T1004K<br/>(beaded rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm T1004K(beaded rebate)"
                                                                       value="374" <?php if ($property_stile == '374' && $material == 138

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1004K.png"/>

                                                            </label>

                                                        </div>

                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-ecowoodPlus">

                                                        <div id="choose-stiletype" class="" style="display: block;">

                                                            <label>

                                                                <br/> 51mm PVC-P1001B<br/>(plain butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm PVC-P1001B(plain butt)"
                                                                       value="380" <?php if ($property_stile == '380' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1001B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1005B<br/>(plain D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DFS 50.8"
                                                                       data-title="51mm PVC-P1005B(plain D-mould)"
                                                                       value="381" <?php if ($property_stile == '381' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1005B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1003E<br/>(plain rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm PVC-P1003E(plain rebate)"
                                                                       value="385" <?php if ($property_stile == '385' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1003B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1002B<br/>(beaded butt)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.8"
                                                                       data-title="51mm PVC-P1002B(beaded butt)"
                                                                       value="382" <?php if ($property_stile == '382' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1002B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1006B<br/>(beaded D-mould)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm PVC-P1006B(beaded D-mould)"
                                                                       value="383" <?php if ($property_stile == '383' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1006B.png"/>

                                                            </label>

                                                            <label>

                                                                <br/> 51mm PVC-P1004E<br/>(beaded rebate)

                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm PVC-P1004E(beaded rebate)"
                                                                       value="384" <?php if ($property_stile == '384' && $material == 137

																) {

																	echo "checked";
																} ?> />

                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1004B.png"/>

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-12">

                                                        <hr/>

                                                        <button class="btn btn-info show-next-panel">

                                                            Next

                                                            <i class="fa fa-chevron-right"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="panel panel-default inactive">

                                        <div class="panel-heading" role="tab" id="headingColour">

                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseColour" aria-expanded="true"
                                                   aria-controls="collapseColour">
                                                    <strong>Colour, Hinges, Control &amp;
                                                        Configuration Design</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <span type="button" class="js-next-step next-step"
                                                          tabindex="2">Next step</span>
                                                </a>
                                            </h4>

                                        </div>

                                        <div id="collapseColour" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingColour">

                                            <div class="panel-body">
                                                <div class="row" id="step4-info">
                                                    <div class="col-sm-12">
                                                        <div class="alert alert-info" role="alert"
                                                             style="display: none;">
                                                            Waive the waterproof warranty!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">

                                                    <div class="col-sm-3">

                                                        Hinge Colour:

                                                        <br/>

                                                        <input class="property-select" id="property_hingecolour"
                                                               name="property_hingecolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $hingecolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_hingecolour', true);
														       }
														       ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-3">

                                                        Shutter Colour:

                                                        <br/>

                                                        <input class="property-select" id="property_shuttercolour"
                                                               name="property_shuttercolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $shuttercolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_shuttercolour', true);
														       }
														       ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-3" id="colour-other" style="display: none">

                                                        Other Colour:

                                                        <br/>

                                                        <input id="property_shuttercolour_other"
                                                               name="property_shuttercolour_other" style="height: 30px"
                                                               type="text"
                                                               value="<?php echo $property_colour_other; ?>"

                                                        />

                                                    </div>

                                                    <div class="col-sm-3">

                                                        Blackout Blind Colour:

                                                        <br/>

                                                        <input class="property-select" id="property_blackoutblindcolour"
                                                               name="property_blackoutblindcolour" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_blackoutblindcolour', true); ?>"/>

                                                    </div>

                                                    <div class="col-sm-3">

                                                        Control Type:

                                                        <br/>

                                                        <input class="property-select" id="property_controltype"
                                                               name="property_controltype" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $controltype_first;
														       } else {
															       echo get_post_meta($product_id, 'property_controltype', true);
														       }
														       ?>"

                                                        />

                                                    </div>

                                                </div>

                                                <div class="row layout-row" style="margin-top:1em;">

                                                    <div class="col-sm-3" id="layoutcode-column">

                                                        Layout Code:

                                                        <br/>

                                                        <div class="input-group-container">

                                                            <div class="input-group">

                                                                <input class="required input-medium"
                                                                       id="property_layoutcode"
                                                                       name="property_layoutcode"
                                                                       style="text-transform:uppercase"

                                                                       type="text"
                                                                       value="<?php echo strtoupper(get_post_meta($product_id, 'property_layoutcode', true)); ?>"

                                                                />

                                                            </div>

                                                            <div class="note-ecowood-angle"
                                                                 style="<?php if ($material != 188) {

																     echo 'display: none';
															     } ?>;">Note: this material allows only 90 and 135 bay
                                                                angles

                                                            </div>

                                                        </div>

                                                    </div>
													<?php
													$property_opendoor = get_post_meta($product_id, 'property_opendoor', true);
													$show_dooropen = !empty($property_opendoor) ? 'block' : 'none';
													?>
                                                    <div class="col-sm-3" style="display: <?php echo $show_dooropen; ?>;"> Door to open first:
                                                        <br/>
                                                        <select id="property_opendoor"
                                                                name="property_opendoor">
                                                            <option value=""></option>
                                                            <option value="Right" <?php if ($property_opendoor == 'Right') {
																echo 'selected';
															} ?>>Right
                                                            </option>
                                                            <option value="Left" <?php if ($property_opendoor == 'Left') {
																echo 'selected';
															} ?>>Left
                                                            </option>
                                                        </select>
                                                    </div>

													<?php
													$property_motorized = get_post_meta($product_id, 'property_motorized', true);
													?>
                                                    <div class="col-sm-2" style="display: block"> Motorised:
                                                        <br/>
                                                        <select id="property_motorized"
                                                                name="property_motorized">
                                                            <option value="No" <?php if ($property_motorized == 'No') {
																echo 'selected';
															} ?>>No
                                                            </option>
                                                            <option value="Yes" <?php if ($property_motorized == 'Yes') {
																echo 'selected';
															} ?>>Yes
                                                            </option>
                                                        </select>
                                                    </div>

													<?php
													$property_nrMotors = get_post_meta($product_id, 'property_nrMotors', true);
													$show_nrMotors = ($property_motorized == "Yes") ? 'block' : 'none';
													?>
                                                    <div class="col-sm-2" style="display: <?php echo $show_nrMotors; ?>;"> Number of motors:
                                                        <br/>
                                                        <select id="property_nrMotors"
                                                                name="property_nrMotors">
															<?php
															// for i from 1 to 10 make option
															for ($i = 1; $i < 11; $i++) {
																echo '<option value="' . $i . '" ' . ($property_nrMotors == $i ? 'selected' : '') . '>' . $i . '</option>';
															}
															?>
                                                        </select>
                                                    </div>

													<?php
													$property_nrRemotes = get_post_meta($product_id, 'property_nrRemotes', true);
													$show_nrRemotes = ($property_motorized == "Yes") ? 'block' : 'none';
													?>
                                                    <div class="col-sm-2" style="display: <?php echo $show_nrRemotes; ?>;"> Number of remotes:
                                                        <br/>
                                                        <select id="property_nrRemotes"
                                                                name="property_nrRemotes">
															<?php
															// for i from 1 to 10 make option
															for ($i = 1; $i < 11; $i++) {
																echo '<option value="' . $i . '" ' . ($property_nrRemotes == $i ? 'selected' : '') . '>' . $i . '</option>';
															}
															?>
                                                        </select>
                                                    </div>


                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">

                                                    </div>
                                                </div>

                                                <div class="row extra-columns-row">
                                                    <div class="col-sm-12">
														<?php if ($nr_code_prod) {
															foreach ($nr_code_prod as $key => $val) {
																for ($i = 1; $i < $val + 1; $i++) {
																	if ($key == 'b') { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">Bay Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_bp<?php echo $i; ?>"
                                                                                       id="property_bp<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_bp' . $i, true); ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">Bay Angle <?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">
                                                                                    <input name="property_ba<?php echo $i; ?>"
                                                                                           id="property_ba<?php echo $i; ?>"
                                                                                           class="input-small required"
                                                                                           type="text"
                                                                                           value="<?php echo get_post_meta($product_id, 'property_ba' . $i, true); ?>">                                                                                                                              
                                                                            </div>
                                                                        </div>
																	<?php } elseif ($key == 't') { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">T-Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_t<?php echo $i; ?>"
                                                                                       id="property_t<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_t' . $i, true); ?>">
                                                                            </div>
                                                                        </div>                                                                                                                                                            <!-- disabled by teo --><!-- /disabled by teo -->                                                                                             <?php } elseif ($key == 'g') { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">G-Post<?php echo $i; ?></span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_g<?php echo $i; ?>"
                                                                                       id="property_g<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_g' . $i, true); ?>">
                                                                            </div>
                                                                        </div>                                                                                                                                                            <!-- disabled by teo --><!-- /disabled by teo -->                                                                                                                                                            <?php } elseif ($key == 'c') { ?>
                                                                        <div class="pull-left extra-column">
                                      <span
                                        class="extra-column-label">C-Post                                           <?php echo $i; ?>                                       </span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_c<?php echo $i; ?>"
                                                                                       id="property_c<?php echo $i; ?>"
                                                                                       class="input-small required"
                                                                                       type="text"
                                                                                       value="<?php echo get_post_meta($product_id, 'property_c' . $i, true); ?>">
                                                                            </div>
                                                                        </div>                                                                                                                                                            <?php }
																}
															}
														} ?>
                                                    </div>
                                                </div>

                                                <div class="row extra-columns-buildout-row">
                                                    <div
                                                      class="col-sm-12">                                                                                                                    <?php if ($nr_code_prod) {
															foreach ($nr_code_prod as $key => $val) {
																for ($i = 1; $i < $val + 1; $i++) {
																	if ($key == 'b') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column-buildout <?php if ($material == '188') {
																			echo 'b-angle-select-type';
																		} ?>">
                                                                            <span class="extra-column-label">B-Post Type<?php echo $i; ?></span>
                                                                            <select
                                                                              id="buildout-select"
                                                                              name="bay-post-type">
																				<?php if ($bay_post_type == 'normal') { ?>
                                                                                    <option value="normal" selected>
                                                                                        Normal
                                                                                    </option>
                                                                                    <option value="flexible">Flexible
                                                                                    </option>
																				<?php } elseif ($bay_post_type == 'flexible') { ?>
                                                                                    <option value="normal">Normal
                                                                                    </option>
                                                                                    <option value="flexible"
                                                                                            selected>Flexible
                                                                                    </option>
																				<?php } ?>
                                                                            </select>
                                                                            <br>
                                                                        </div>

																	<?php } ?><?php } elseif ($key == 't') { ?>

																		<?php if ($i == 1) { ?>
                                                                            <div class="pull-left extra-column-buildout <?php if ($material == '188') {
																				echo 't-angle-select-type';
																			} ?>">
                                                                                <span class="extra-column-label">T-Post Style :</span><br>
                                                                                <select
                                                                                  id="buildout-select"
                                                                                  name="t-post-type">
																					<?php if ($t_post_type == 'normal') { ?>
                                                                                        <option value="normal" selected>
                                                                                            Normal
                                                                                        </option>
                                                                                        <option value="adjustable">
                                                                                            Adjustable
                                                                                        </option>
																					<?php } elseif ($t_post_type == 'adjustable') { ?>
                                                                                        <option value="normal">Normal
                                                                                        </option>
                                                                                        <option value="adjustable"
                                                                                                selected>Adjustable
                                                                                        </option>
																					<?php } ?>
                                                                                </select>
                                                                                <br>
                                                                            </div>
                                                                            <div class="pull-left extra-column">
                                                                                <span class="extra-column-label">T-Post Buildout</span>:
                                                                                <br>
                                                                                <div class="input-group">

                                                                                    <input name="property_t_buildout<?php echo $i; ?>"
                                                                                           id="property_t_buildout<?php echo $i; ?>"
                                                                                           class="input-small"
                                                                                           type="checkbox"
                                                                                           value="yes" <?php if (get_post_meta($product_id, 'property_t_buildout' . $i, true) == 'yes') {
																						echo 'checked';
																					} ?>>
                                                                                </div>
                                                                            </div>
																		<?php } ?><?php } elseif ($key == 'g') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">G-Post Buildout</span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_g_buildout<?php echo $i; ?>"
                                                                                       id="property_g_buildout<?php echo $i; ?>"
                                                                                       class="input-small"
                                                                                       type="checkbox"
                                                                                       value="yes" <?php if (get_post_meta($product_id, 'property_g_buildout' . $i, true) == 'yes') {
																					echo 'checked';
																				} ?>>
                                                                            </div>
                                                                        </div>
																	<?php } ?><?php } elseif ($key == 'c') { ?><?php if ($i == 1) { ?>
                                                                        <div class="pull-left extra-column">
                                                                            <span class="extra-column-label">C-Post Buildout</span>:
                                                                            <br>
                                                                            <div class="input-group">

                                                                                <input name="property_c_buildout<?php echo $i; ?>"
                                                                                       id="property_c_buildout<?php echo $i; ?>"
                                                                                       class="input-small"
                                                                                       type="checkbox"
                                                                                       value="yes" <?php if (get_post_meta($product_id, 'property_c_buildout' . $i, true) == 'yes') {
																					echo 'checked';
																				} ?>>
                                                                            </div>
                                                                        </div>
																	<?php } ?><?php }
																}
															}
														} ?>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3 tpost-type"
                                                         style="<?php if (get_post_meta($product_id, 'property_tposttype', true)) {
														     echo 'display: block';
													     } else {
														     echo 'display: none';
													     } ?>"> T-Post Type:
                                                        <br/>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 187) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-earth"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 187 && get_post_meta($product_id, 'property_tposttype', true)) {
															     $t_earth = true;
															     echo 'display: block;';
														     } else {
															     $t_earth = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>
                                                                <br/> <b>A7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="A7001 - Earth Standard T-Post"
                                                                       value="442" <?php if ($property_tposttype == '442' && $t_earth == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/A7001.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 188) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 188 && get_post_meta($product_id, 'property_tposttype', true)) {
															     $t_eco = true;
															     echo 'display: block;';
														     } else {
															     $t_eco = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7030"
                                                                       value="444" <?php if ($property_tposttype == '444' && $t_eco == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post"
                                                                       value="437" <?php if ($property_tposttype == '437' && $t_eco == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439" <?php if ($property_tposttype == '439' && $t_eco == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 139 && get_post_meta($product_id, 'property_tposttype', true)) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-supreme"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 139) {
															     $t_sup = true;
															     echo 'display: block;';
														     } else {
															     $t_sup = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Supreme Standard T-Post"
                                                                       value="438" <?php if ($property_tposttype == '438' && $t_sup == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7011</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7011 - Large T-Post"
                                                                       value="441" <?php if ($property_tposttype == '441' && $t_sup == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7032"
                                                                       value="443" <?php if ($property_tposttype == '443' && $t_sup == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7032.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7201 - T-Post with insert"
                                                                       value="439" <?php if ($property_tposttype == '439' && $t_sup == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 138 && get_post_meta($product_id, 'property_tposttype', true)) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-biowood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 6) {
															     $t_bio = true;
															     echo 'display: block;';
														     } else {
															     $t_bio = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7030"
                                                                       value="444" <?php if ($property_tposttype == '444' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post"
                                                                       value="437" <?php if ($property_tposttype == '437' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439" <?php if ($property_tposttype == '439' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Supreme Standard T-Post"
                                                                       value="438" <?php if ($property_tposttype == '438' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                        <div class="col-sm-12 tpost-img type-img-biowoodPlus"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 138) {
															     $t_bio = true;
															     echo 'display: block;';
														     } else {
															     $t_bio = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7030"
                                                                       value="444" <?php if ($property_tposttype == '444' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post"
                                                                       value="437" <?php if ($property_tposttype == '437' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439" <?php if ($property_tposttype == '439' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>

                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Supreme Standard T-Post"
                                                                       value="438" <?php if ($property_tposttype == '438' && $t_bio == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7001.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 137) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowoodPlus"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 137 && get_post_meta($product_id, 'property_tposttype', true)) {
															     $t_grn = true;
															     echo 'display: block;';
														     } else {
															     $t_grn = false;
															     echo 'display: none;';
														     }
														     ?>">
                                                            <label>PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7030"
                                                                       value="444" <?php if ($property_tposttype == '444' && $t_grn == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post"
                                                                       value="437" <?php if ($property_tposttype == '437' && $t_grn == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7032.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7201</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7201 - T-Post with insert"
                                                                       value="439" <?php if ($property_tposttype == '439' && $t_grn == true) {
																	echo "checked";
																} ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">

                                                    <div class="col-sm-4" id="spare-louvres">

                                                        <div>

                                                            <label>

																<?php $property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true) ?>

                                                                <select id="property_sparelouvres"
                                                                        name="property_sparelouvres">

                                                                    <option value="No" <?php if ($property_sparelouvres == 'No') {

																		echo 'selected';
																	} ?>>No

                                                                    </option>

                                                                    <option value="Yes" <?php if ($property_sparelouvres == 'Yes') {

																		echo 'selected';
																	} ?>>Yes

                                                                    </option>

                                                                </select>

                                                                <!-- <input class="ace" id="property_sparelouvres" name="property_sparelouvres" type="checkbox" <?php if ($property_sparelouvres == "Yes") {

																	echo 'checked';
																} ?> value="Yes"     /> -->

                                                                <span class="lbl"> Include 2 x Spare Louvres (£6 + VAT)</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row" style="margin-top:1em;">

                                                    <div class="col-sm-4" id="ring-pull" style="display:none;">

                                                        <div>

                                                            <label>

																<?php $property_ringpull = get_post_meta($product_id, 'property_ringpull', true) ?>

                                                                <select id="property_ringpull" name="property_ringpull">

                                                                    <option value="No" <?php if ($property_ringpull == 'No') {

																		echo 'selected';
																	} ?>>No

                                                                    </option>

                                                                    <option value="Yes" <?php if ($property_ringpull == 'Yes') {

																		echo 'selected';
																	} ?>>Yes

                                                                    </option>

                                                                </select>

                                                                <!-- <input class="ace" id="property_ringpull" name="property_ringpull" type="checkbox" <?php if ($property_ringpull != '') {

																	echo 'checked';
																} ?> value="yes"

                                  /> -->

                                                                <span class="lbl"> Ring Pull</span>

                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="row">

                                                    <div class="col-sm-12">

                                                        <hr/>

                                                        <button class="btn btn-info show-next-panel">

                                                            Next

                                                            <i class="fa fa-chevron-right"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="panel panel-default inactive">

                                        <div class="panel-heading" role="tab" id="headingLayout">

                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                   data-parent="#accordion" href="#collapseLayout" aria-expanded="true"
                                                   aria-controls="collapseLayout">
                                                    <strong>Confirm Drawing</strong>
                                                    <span type="button" class="icon--edit edit" tabindex="2">
                                <i class="fas fa-pen fa fa-pencil"></i> Edit</span>
                                                    <span type="button" class="js-next-step next-step"
                                                          tabindex="2">Final step</span></a>
                                            </h4>

                                        </div>

                                        <div id="collapseLayout" class="panel-collapse collapse drawing-panel"
                                             role="tabpanel" aria-labelledby="headingLayout">

                                            <div class="panel-body">

                                                <!-- drawing -->

                                                <div class="row">

                                                    <div class="col-lg-8 col-md-11 col-sm-12">

                                                        <div style="display: none">

                                                            <textarea id="drawingConfig"
                                                                      style="width:300px;height:150px;"></textarea>

                                                            <input id="splitHeight" type="number" name="quantity_split"
                                                                   min="0" max="5000" step="10">

                                                            <button id="runButton">Run</button>

                                                            <textarea id="drawingConfigScaled"
                                                                      style="width:300px;height:150px;"></textarea>

                                                            <button id="runButtonScaled">Run</button>

                                                        </div>

                                                        <div id="canvas_container1" class="canvas_container"
                                                             style="min-height: 500px;border: 1px solid #aaa;background-image: url('/wp-content/plugins/shutter-module/imgs//drawing_graph.png');">

                                                        </div>

                                                        <br/>

                                                        <button class="btn btn-info print-drawing" style="z-index: 10;">

                                                            <i class="fa fa-print"></i> Print

                                                        </button>

                                                        <textarea id="shutter_svg" name="shutter_svg"
                                                                  style="display:none"></textarea>

                                                    </div>

                                                    <div class="col-lg-4 col-md-6">

                                                        Comments:

                                                        <br/>

                                                        <textarea id="comments_customer" name="comments_customer"
                                                                  rows="5"
                                                                  style="width: 100%"><?php echo get_post_meta($product_id, 'comments_customer', true); ?></textarea>

                                                        <hr/>

                                                        <div id="nowarranty" style="display:none">

                                                            I accept that there is no warranty for this item

                                                            <br/>

                                                            <input id="property_nowarranty" name="property_nowarranty"
                                                                   type="checkbox" value="Yes"/>

                                                        </div>

                                                        <div class="quantity">

                                                            Number of this:

															<?php

															if (!empty($_GET['id'])) {

																?>

                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity"
                                                                       value="<?php echo get_post_meta($product_id, 'quantity', true) ?>"
                                                                       title="Qty" size="4" pattern="[0-9]*"
                                                                       inputmode="numeric" aria-labelledby=""
                                                                       type="number">

																<?php
															} else {

																?>

                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity" value="1" title="Qty"

                                                                       size="4" pattern="[0-9]*"
                                                                       inputmode="numeric" aria-labelledby=""
                                                                       type="number">

																<?php
															}

															?>

                                                        </div>

														<?php
														if (!empty($_GET['id']) && !empty($_GET['cust_id'] || $edit_customer)) {

															?>

                                                            <input type="hidden" name="order_item_id"
                                                                   value="<?php echo $item_id; ?>">

                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>

                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right"

                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"

                                                            />

                                                            <button class="btn btn-primary update-btn-admin">Update
                                                                Product

                                                                <i class="fa fa-chevron-right"></i>

                                                            </button>

                                                            <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                                 alt="" class="spinner" style="display:none;"/>

															<?php
														} elseif (!empty($_GET['id']) && empty($_GET['cust_id'])) {

															?>

                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>

                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right"

                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"

                                                            />

                                                            <button class="btn btn-primary update-btn">Update Product

                                                                <i class="fa fa-chevron-right"></i>

                                                            </button>

															<?php
														} else {

															?>

                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>

                                                            <input class="" id="panels_left_right"
                                                                   name="panels_left_right" type="hidden" value=""/>

                                                            <button class="btn btn-success">

                                                                Add to Quote

                                                                <i class="fa fa-chevron-right"></i>

                                                            </button>

															<?php
														}

														?>

                                                        <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                             alt="" class="spinner" style="display:none;"/>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- the following is used as a prototype in order to add new fields while adding a layout code -->

                        <div class="col-sm-2" id="extra-column" style="display: none">

                            <span class="extra-column-label">Label</span>:

                            <br/>

                            <div class="input-group">

                                <input type="text" name="property_extra_column" id="property_extra_column"
                                       class="input-small">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-12">

                            </div>

                        </div>

                        <hr/>

                        <style type="text/css">

                            .input-group-addon-container {

                                float: left

                            }


                            .input-group-addon-container .input-group-addon {

                                height: 30px;

                                border-left: 1px solid #cccccc;

                            }


                            #add-product-single-form .input-group {

                                width: 100%

                            }


                            #choose-style label > input {

                                /* HIDE RADIO */

                                /* display: none; */

                            }


                            #choose-style label {

                                display: block;

                                float: left;

                                width: 100px;

                                text-align: center;

                                border: 2px solid gray;

                                margin-right: 1em;

                                font-size: 10px;

                                font-weight: bold;

                                color: black;

                                background-color: #ced3e4;

                                border-radius: 7px;

                                height: 170px;

                            }


                            #choose-style label > input + img {

                                /* IMAGE STYLES */

                                cursor: pointer;

                                border: 4px solid transparent;

                            }


                            #choose-style label > input:checked + img {

                                /* (CHECKED) IMAGE STYLES */

                                border: 4px solid #438EB9;

                            }


                            #choose-frametype label > input, #choose-stiletype label > input {

                                /* HIDE RADIO */

                                /* display: none; */

                            }


                            #choose-frametype label, #choose-stiletype label {

                                display: block;

                                float: left;

                                width: 100px;

                                text-align: center;

                                border: 2px solid gray;

                                background-color: #ced3e4;

                                border-radius: 7px;

                                margin-right: 1em;

                                font-size: 10px;

                                font-weight: bold;

                                color: black;


                            }


                            #choose-frametype label > input + img, #choose-stiletype label > input + img {

                                /* IMAGE STYLES */

                                cursor: pointer;

                                border: 4px solid transparent;

                            }


                            #choose-frametype label > input:checked + img, #choose-stiletype label > input:checked + img {

                                /* (CHECKED) IMAGE STYLES */

                                border: 4px solid #438EB9;

                            }


                            .tpost-type label > input + img {

                                /* IMAGE STYLES */

                                cursor: pointer;

                                border: 4px solid transparent;

                            }


                            .tpost-type label > input:checked + img {

                                /* (CHECKED) IMAGE STYLES */

                                border: 4px solid #438EB9;

                            }


                            .extra-column input {

                                width: 4em;

                            }


                            .extra-column {

                                padding-right: 0.5em;

                                display: table;

                            }


                            /* .extra-columns-row .pull-left.extra-column {

												display: inline-block;

										} */


                            div.error-field {

                                display: block;

                            }


                            .error-field,
                            input.error-field {

                                border: 2px solid red;

                            }


                            .error-text {
                                color: red;
                                font-weight: bold;
                            }

                            .row.layout-row .error-text {
                                width: 500px;
                                display: block;
                                overflow: visible;
                            }

                            .select2-result-label img,
                            .select2-container img {

                                display: inline;

                            }


                            .select2-container#s2id_property_style .select2-choice,
                            #s2id_property_style .select2-result {

                                height: 57px;

                            }


                            .extra-column-label {

                                font-size: 0.8em

                            }


                            input.layout-code {

                                font-size: 0.8em;

                            }


                            input.property-select {

                                /* display: block !important; */

                            }


                            .collapse {

                                /* display: block; */

                            }


                            .btn.btn-info.show-next-panel {

                                /* display: none; */

                            }


                            .modal {

                                top: 20%;

                            }


                            #property_layoutcode {

                                text-transform: uppercase;

                            }

                            b {
                                font-weight: 900 !important;
                            }


                            /*select.b-angle-select {*/

                            /*    display: none !important;*/

                            /*}*/

                        </style>

                </form>

                <div class="show-prod-info">

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Modal Start -->

<div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true"
     style="display: none;">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                <h3 id="errorModalLabel">Configuration errors</h3>

            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">

                <button class="btn btn-close" data-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>

<!-- Modal End -->

</div>

</div>

