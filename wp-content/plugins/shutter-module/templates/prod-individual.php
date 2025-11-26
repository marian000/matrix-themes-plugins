<?php if (!empty($_GET['cust_id'])) {
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
$session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key ");
$userialize_session = unserialize($session[0]->session_value);
$i = 1;
$carts_sort = $userialize_data['carts'];
ksort($carts_sort);
$total_elements = count($carts_sort) + 1;
$cart_name = '';
$attachment = '';
$product_id = '';
$property_style = '';
$property_frametype = '';
$nr_code_prod = array();
$items_name = array();
$first_item = null;
foreach ($carts_sort as $key => $carts) {
	if ($userialize_data['customer_id'] == $key) {
		$cart_name = $carts['name'];
	}
}
if (!empty($_GET['id'])) {
	$product_id = $_GET['id'] / 1498765 / 33;
	echo "<input type='hidden' id='pod_item_id' val='" . $product_id . "'>";
	$meta = get_post_meta($_GET['id'] / 1498765 / 33);
	//echo '  price: ' . number_format(get_post_meta($product_id, '_regular_price', true), 2);
	// echo '  stile:  ' . get_post_meta($product_id, 'property_stile', true);
	//echo '  tposty: ' . get_post_meta($product_id, 'property_tposttype', true);
	$property_style = get_post_meta($product_id, 'property_style', true);
	$property_order_edit = get_post_meta($product_id, 'order_edit', true);
	if (empty($order_edit)) {
		$order_edit = $property_order_edit;
	}
	$attachment = get_post_meta($product_id, 'attachment', true);
	$attachmentDraw = get_post_meta($product_id, 'attachmentDraw', true);
	$property_frametype = get_post_meta($product_id, 'property_frametype', true);
	$property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);
	$property_stile = get_post_meta($product_id, 'property_stile', true);
	$bay_post_type = get_post_meta($product_id, 'bay-post-type', true);
	$t_post_type = get_post_meta($product_id, 't-post-type', true);
	$material = get_post_meta($product_id, 'property_material', true);
	$property_builtout = get_post_meta($product_id, 'property_builtout', true);
	$property_solidtype = get_post_meta($product_id, 'property_solidtype', true);
	$property_trackedtype = get_post_meta($product_id, 'property_trackedtype', true);
	$property_bypasstype = get_post_meta($product_id, 'property_bypasstype', true);
	$property_colour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
	$property_lightblocks = get_post_meta($product_id, 'property_lightblocks', true);
	$property_tracksnumber = get_post_meta($product_id, 'property_tracksnumber', true);
	$property_tposttype = get_post_meta($product_id, 'property_tposttype', true);
	$comments_customer = get_post_meta($product_id, 'comments_customer', true);
	$property_frametype = get_post_meta($product_id, 'property_frametype', true);
	$frameleft_first = get_post_meta($product_id, 'property_frameleft', true);
	$frameright_first = get_post_meta($product_id, 'property_frameright', true);
	$frametop_first = get_post_meta($product_id, 'property_frametop', true);
	$framebottom_first = get_post_meta($product_id, 'property_framebottom', true);
	$property_builtout = get_post_meta($product_id, 'property_builtout', true);

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

	$nr_code_prod = array();
	$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
	if ($property_nr_sections) {
		$individual_counter = get_post_meta($product_id, 'individual_counter', true);
		for ($sec = 1; $sec <= $property_nr_sections; $sec++) {
			$nr_b = $individual_counter[$sec]['counter_b'];
			$nr_t = $individual_counter[$sec]['counter_t'];
			$nr_c = $individual_counter[$sec]['counter_c'];
			$nr_g = $individual_counter[$sec]['counter_g'];

			$nr_code_prod[$sec]['g'] = $nr_g;
			$nr_code_prod[$sec]['t'] = $nr_t;
			$nr_code_prod[$sec]['b'] = $nr_b;
			$nr_code_prod[$sec]['c'] = $nr_c;
		}
		// print_r($individual_counter);
	}

	global $woocommerce;
	$cart = WC()->cart->get_cart();
	foreach ($cart as $cart_item_key => $cart_item) {
		$products = $cart_item['data']->get_id();
		if ($products == $product_id) {
			//echo ' QTY:' . $quant = $cart_item['quantity'];
		}
	}
} else {
	$cart = WC()->cart->get_cart();
	if (count($cart) >= 1 && $edit_customer == false) {

		foreach ($cart as $cart_item_key => $cart_item) {
			// get id product
			$product_id_cart = $cart_item['product_id'];
			$property_room_other = get_post_meta($product_id_cart, 'property_room_other', true);
			$items_name[] = $property_room_other;
			// if clone id existsin GET and is equal to prod id from cart then update as principal id
			if (!empty($clone_id) && $clone_id == $product_id_cart) {
				update_post_meta($product_id_cart, 'clone_prod_id', $clone_id);
			} // if clone id existsin GET and is not equal to prod id from cart then update empty
            elseif (!empty($clone_id) && $clone_id != $product_id_cart) {
				update_post_meta($product_id_cart, 'clone_prod_id', '');
			}
		}

		$first_item = array_shift($cart);
		$first_prod_id = $first_item['product_id'];

		$material = get_post_meta($first_prod_id, 'property_material', true);
		$bladesize_first = get_post_meta($first_prod_id, 'property_bladesize', true);
		// Frame Type, Left, Right, Top, Bottom, Ad buildout, Stile
		$property_frametype = get_post_meta($first_prod_id, 'property_frametype', true);
		$frameleft_first = get_post_meta($first_prod_id, 'property_frameleft', true);
		$frameright_first = get_post_meta($first_prod_id, 'property_frameright', true);
		$frametop_first = get_post_meta($first_prod_id, 'property_frametop', true);
		$framebottom_first = get_post_meta($first_prod_id, 'property_framebottom', true);
		$property_builtout = get_post_meta($first_prod_id, 'property_builtout', true);
		$property_stile = get_post_meta($first_prod_id, 'property_stile', true);
		// Hinge Colour, Shutter Colour, Control Type
		$hingecolour_first = get_post_meta($first_prod_id, 'property_hingecolour', true);
		$shuttercolour_first = get_post_meta($first_prod_id, 'property_shuttercolour', true);
		$property_colour_other = get_post_meta($first_prod_id, 'property_shuttercolour_other', true);
		$controltype_first = get_post_meta($first_prod_id, 'property_controltype', true);
	}
}

?>


<!-- stylesheet -->
<link href="/wp-content/themes/storefront-child/canvas-demo/_assets/literallycanvas.css" rel="stylesheet">

<!-- dependency: React.js -->
<script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-with-addons.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/react/0.14.7/react-dom.js"></script>

<!-- Literally Canvas -->
<script src="/wp-content/themes/storefront-child/canvas-demo/static/js/literallycanvas.js"></script>


<!-- you really ought to include react-dom, but for react 0.14 you don't strictly have to. -->
<script src="/wp-content/themes/storefront-child/canvas-demo/_js_libs/react-0.14.3.js"></script>
<script src="/wp-content/themes/storefront-child/canvas-demo/_js_libs/literallycanvas.js"></script>


<div class="main-content">
    <div class="page-content" style="background-color: #F7F7F7">
        <div class="page-content-area">
            <div class="page-header">
				<?php if (!empty($_GET['id'])) { ?>
                    <h1>Update Individual Bay Shutter
                        - <?php echo $cart_name; ?></h1> <?php } elseif (!empty($_GET['order_id'])) { ?>
                    <h1>Add Individual Bay Shutter</h1>
				<?php } else { ?>
                    <h1>Add Individual Bay Shutter
                        - <?php echo $cart_name; ?></h1> <?php } ?>
            </div>
            <input type="hidden" id="g1" name="g1" value="<?php echo $g1; ?>">
            <input type="hidden" id="g2" name="g2" value="<?php echo $g2; ?>">
            <input type="hidden" id="g3" name="g3" value="<?php echo $g3; ?>">
            <input type="hidden" id="g4" name="g4" value="<?php echo $g4; ?>">
            <input type="hidden" id="g5" name="g5" value="<?php echo $g5; ?>">
            <input type="hidden" id="g6" name="g6" value="<?php echo $g6; ?>">
            <input type="hidden" id="g7" name="g7" value="<?php echo $g7; ?>">
            <input type="hidden" id="g8" name="g8" value="<?php echo $g8; ?>">
            <input type="hidden" id="g9" name="g9" value="<?php echo $g9; ?>">
            <input type="hidden" id="g10" name="g10" value="<?php echo $g10; ?>">
            <input type="hidden" id="g11" name="g11" value="<?php echo $g11; ?>">
            <input type="hidden" id="g12" name="g12" value="<?php echo $g12; ?>">
            <input type="hidden" id="g13" name="g13" value="<?php echo $g13; ?>">
            <input type="hidden" id="g14" name="g14" value="<?php echo $g14; ?>">
            <input type="hidden" id="g15" name="g15" value="<?php echo $g15; ?>">
            <input type="hidden" id="t1" name="t1" value="<?php echo $t1; ?>">
            <input type="hidden" id="t2" name="t2" value="<?php echo $t2; ?>">
            <input type="hidden" id="t3" name="t3" value="<?php echo $t3; ?>">
            <input type="hidden" id="t4" name="t4" value="<?php echo $t4; ?>">
            <input type="hidden" id="t5" name="t5" value="<?php echo $t5; ?>">
            <input type="hidden" id="t6" name="t6" value="<?php echo $t6; ?>">
            <input type="hidden" id="t7" name="t7" value="<?php echo $t7; ?>">
            <input type="hidden" id="t8" name="t8" value="<?php echo $t8; ?>">
            <input type="hidden" id="t9" name="t9" value="<?php echo $t9; ?>">
            <input type="hidden" id="t10" name="t10" value="<?php echo $t10; ?>">
            <input type="hidden" id="t11" name="t11" value="<?php echo $t11; ?>">
            <input type="hidden" id="t12" name="t12" value="<?php echo $t12; ?>">
            <input type="hidden" id="t13" name="t13" value="<?php echo $t13; ?>">
            <input type="hidden" id="t14" name="t14" value="<?php echo $t14; ?>">
            <input type="hidden" id="t15" name="t15" value="<?php echo $t15; ?>">
            <input type="hidden" id="c1" name="c1" value="<?php echo $c1; ?>">
            <input type="hidden" id="c2" name="c2" value="<?php echo $c2; ?>">
            <input type="hidden" id="c3" name="c3" value="<?php echo $c3; ?>">
            <input type="hidden" id="c4" name="c4" value="<?php echo $c4; ?>">
            <input type="hidden" id="c5" name="c5" value="<?php echo $c5; ?>">
            <input type="hidden" id="c6" name="c6" value="<?php echo $c6; ?>">
            <input type="hidden" id="c7" name="c7" value="<?php echo $c7; ?>">
            <input type="hidden" id="c8" name="c8" value="<?php echo $c8; ?>">
            <input type="hidden" id="c9" name="c9" value="<?php echo $c9; ?>">
            <input type="hidden" id="c10" name="c10" value="<?php echo $c10; ?>">
            <input type="hidden" id="c11" name="c11" value="<?php echo $c11; ?>">
            <input type="hidden" id="c12" name="c12" value="<?php echo $c12; ?>">
            <input type="hidden" id="c13" name="c13" value="<?php echo $c13; ?>">
            <input type="hidden" id="c14" name="c14" value="<?php echo $c14; ?>">
            <input type="hidden" id="c15" name="c15" value="<?php echo $c15; ?>">
            <input type="hidden" id="ba1" name="ba1" value="<?php echo $ba1; ?>">
            <input type="hidden" id="ba2" name="ba2" value="<?php echo $ba2; ?>">
            <input type="hidden" id="ba3" name="ba3" value="<?php echo $ba3; ?>">
            <input type="hidden" id="ba4" name="ba4" value="<?php echo $ba4; ?>">
            <input type="hidden" id="ba5" name="ba5" value="<?php echo $ba5; ?>">
            <input type="hidden" id="ba6" name="ba6" value="<?php echo $ba6; ?>">
            <input type="hidden" id="ba7" name="ba7" value="<?php echo $ba7; ?>">
            <input type="hidden" id="ba8" name="ba8" value="<?php echo $ba8; ?>">
            <input type="hidden" id="ba9" name="ba9" value="<?php echo $ba9; ?>">
            <input type="hidden" id="ba10" name="ba10" value="<?php echo $ba10; ?>">
            <input type="hidden" id="ba11" name="ba11" value="<?php echo $ba11; ?>">
            <input type="hidden" id="ba12" name="ba12" value="<?php echo $ba12; ?>">
            <input type="hidden" id="ba13" name="ba13" value="<?php echo $ba13; ?>">
            <input type="hidden" id="ba14" name="ba14" value="<?php echo $ba14; ?>">
            <input type="hidden" id="ba15" name="ba15" value="<?php echo $ba15; ?>">
            <input type="hidden" id="bp1" name="bp1" value="<?php echo $bp1; ?>">
            <input type="hidden" id="bp2" name="bp2" value="<?php echo $bp2; ?>">
            <input type="hidden" id="bp3" name="bp3" value="<?php echo $bp3; ?>">
            <input type="hidden" id="bp4" name="bp4" value="<?php echo $bp4; ?>">
            <input type="hidden" id="bp5" name="bp5" value="<?php echo $bp5; ?>">
            <input type="hidden" id="bp6" name="bp6" value="<?php echo $bp6; ?>">
            <input type="hidden" id="bp7" name="bp7" value="<?php echo $bp7; ?>">
            <input type="hidden" id="bp8" name="bp8" value="<?php echo $bp8; ?>">
            <input type="hidden" id="bp9" name="bp9" value="<?php echo $bp9; ?>">
            <input type="hidden" id="bp10" name="bp10" value="<?php echo $bp10; ?>">
            <input type="hidden" id="bp11" name="bp11" value="<?php echo $bp11; ?>">
            <input type="hidden" id="bp12" name="bp12" value="<?php echo $bp12; ?>">
            <input type="hidden" id="bp14" name="bp14" value="<?php echo $bp14; ?>">
            <input type="hidden" id="bp15" name="bp15" value="<?php echo $bp15; ?>">
            <input type="hidden" id="property_frametype" name="property_frametype_hidden"
                   value="<?php echo $property_frametype; ?>">
            <div class="row">
                <form edit="yes" accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                      enctype="multipart/form-data" id="add-product-single-form" method="post">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-9">
                                <strong>Order Reference:</strong> <?php echo $cart_name; ?>
                                <br/>
                                <br/>
                            </div>
                            <div class="col-sm-3 pull-right"> Total Square Meters&nbsp;
                                <input class="input-small" id="property_total" name="property_total" type="text"
                                       value=""/>
                            </div>
                        </div>
                        <div style="display:none"></div>
                        <input type="hidden" name="product_id_updated" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="edit_customer" value="<?php echo $edit_customer; ?>">
                        <input type="hidden" name="order_edit" value="<?php echo $order_edit; ?>">
                        <input type="hidden" name="cart_items_name" value='<?php echo json_encode($items_name); ?>'>


                        <input type="hidden" id="property_frametype_hidden" name="property_frametype"
                               value="<?php echo $property_frametype; ?>">
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
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseStyle" class="panel-collapse collapse in" role="tabpanel"
                                             aria-labelledby="headingStyle" aria-expanded="true" style="">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3" style="display:none;"> Room:
                                                        <br/>
                                                        <input class="property-select" id="property_room"
                                                               name="property_room" type="text" value="94"/>
                                                    </div>
                                                    <div class="col-sm-3" id="room-other" style="display: block"> Room
                                                        Name:
                                                        <br/>
                                                        <input required class="input-medium required"
                                                               id="property_room_other" name="property_room_other"
                                                               style="height: 30px" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_room_other', true); ?>"/>
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
                                                    <div class="col-sm-12"> Installation Style:
                                                        <div id="choose-style">
                                                            <label>
                                                                <br/> Full Height
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="fullheight" data-title="Full Height"
                                                                       value="29"
																  <?php if ($property_style == '29') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/Bay-Window.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Tier-on-Tier
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="tot" data-title="Tier-on-Tier" value="31"
																  <?php if ($property_style == '31') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br/> Café Style
                                                                <br/>
                                                                <input type="radio" name="property_style"
                                                                       data-code="cafe" data-title="Café Style" value="30"
																  <?php if ($property_style == '30') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Cafe-Style.png">
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-2" id="shape-section-draw" style="display: none">
                                                        Draw Shape
                                                        <br/>
                                                        <button id="btnDrawModal" type="button" class="btn btn-primary"
                                                                data-toggle="modal" data-target="#drawModal"
                                                                style="display: block;">
                                                            Open Drawing Pad
                                                        </button>
                                                        <img id="frontend-image-draw"
                                                             src="<?php echo $attachmentDraw; ?>"/>
                                                        <input type="text" id="attachmentDraw" name="attachmentDraw"
                                                               style="visibility: hidden;"
                                                               value="<?php if ($attachmentDraw) {
															       echo $attachmentDraw;
														       } ?>"/>

                                                    </div>

                                                    <div class="col-sm-3" id="shape-section" style="display: block">
                                                        Attach shape drawing
                                                        <br/>
                                                        <div id="shape-upload-container">
                                                            <span id="provided-shape"> </span>
                                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                                            <button id="frontend-button" type="button"
                                                                    value="Select File to Upload"
                                                                    class="button btn btn-primary"
                                                                    style="position: relative; z-index: 1;">Select File
                                                                to Upload
                                                            </button>
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
                                                    <div class="col-sm-2"> Number of sections:
                                                        <br/>
                                                        <input class="required number input-medium" max="10" min="1"
                                                               id="property_nr_sections" name="property_nr_sections"
                                                               type="number"
                                                               value="<?php echo get_post_meta($product_id, 'property_nr_sections', true); ?>"/>
                                                    </div>

                                                    <div class="col-sm-2"> Height (mm):
                                                        <br/>
                                                        <input class="required number input-medium" id="property_height"
                                                               name="property_height" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_height', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-height"> Midrail Height (mm):
                                                        <br/>
                                                        <input class="number input-medium" id="property_midrailheight"
                                                               name="property_midrailheight" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-height2"> Midrail Height 2 (mm):
                                                        <br/>
                                                        <input class="number input-medium" id="property_midrailheight2"
                                                               name="property_midrailheight2" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midrailheight2', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider"> Hidden Divider 1 (mm):
                                                        <br/>
                                                        <input class=" number input-medium"
                                                               id="property_midraildivider1"
                                                               name="property_midraildivider1" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider1', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider2"> Hidden Divider 2 (mm):
                                                        <br/>
                                                        <input class=" number input-medium"
                                                               id="property_midraildivider2"
                                                               name="property_midraildivider2" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_midraildivider2', true); ?>"/>
                                                    </div>
                                                    <div id="solidtype" class="col-sm-2" style="display: none"> Solid
                                                        Type:
                                                        <br/>
                                                        <input name="property_solidtype" type="radio"
														  <?php if ($property_solidtype == 'flat') echo 'checked'; ?>
                                                               value="flat"/> Flat
                                                        <br/>
                                                        <input name="property_solidtype" type="radio"
														  <?php if ($property_solidtype == 'Double Raised') echo 'Double Raised'; ?>
                                                               value="raised"/> Double Raised
                                                    </div>
                                                    <div class="col-sm-2" id="solid-panel-height" style="display:none;">
                                                        Solid Panel Height (mm):
                                                        <br/>
                                                        <input class="number input-medium"
                                                               id="property_solidpanelheight"
                                                               name="property_solidpanelheight" type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_solidpanelheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-position-critical"> Position is
                                                        Critical:
                                                        <br/>
                                                        <div class="input-group-container">
                                                            <div class="input-group">
																<?php if (!empty($_GET['id'])) { ?>
                                                                    <input class="property-select"
                                                                           id="property_midrailpositioncritical"
                                                                           name="property_midrailpositioncritical" type="text"
                                                                           value="<?php echo get_post_meta($product_id, 'property_midrailpositioncritical', true); ?>"/>
																<?php } else { ?>
                                                                    <input class="property-select"
                                                                           id="property_midrailpositioncritical"
                                                                           name="property_midrailpositioncritical" type="text"
                                                                           value="170"/> <?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 tot-height" style="display: none"> T-o-T Height
                                                        (mm):
                                                        <br/>
                                                        <input class="required number input-medium"
                                                               id="property_totheight" name="property_totheight"
                                                               type="text"
                                                               value="<?php echo get_post_meta($product_id, 'property_totheight', true); ?>"/>
                                                    </div>
                                                    <div class="col-sm-2 tot-height horizontal-t-post"
                                                         style="display: none">
                                                        Horizontal T Post
                                                        <br/>
                                                        <input id="property_horizontaltpost"
                                                               name="property_horizontaltpost" type="checkbox" value="Yes"
														  <?php if ($property_horizontaltpost == 'Yes') {
															  echo "checked";
														  } ?> />
                                                    </div>
                                                    <div class="col-sm-2"> Louvre Size:
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
                                                    <div id="trackedtype" class="col-sm-2" style="display: none"> Track
                                                        Installation type:
                                                        <br/>
                                                        <input name="property_trackedtype" type="radio"
														  <?php if ($property_trackedtype == 'inside mount') echo 'checked'; ?>
                                                               value="inside mount"/> Inside mount
                                                        <br/>
                                                        <input name="property_trackedtype" type="radio"
														  <?php if ($property_trackedtype == 'outside mount') echo 'checked'; ?>
                                                               value="outside mount"/> Outside mount
                                                    </div>
                                                    <!--                                                    <div id="trackedtyperecess" class="col-sm-2" style="display: none">-->
                                                    <!--                                                        Track Installation type:-->
                                                    <!--                                                        <br />-->
                                                    <!--                                                        <input name="property_trackedtype" type="radio"-->
                                                    <!--                                                            --><?php //if ($property_trackedtype == 'inside recess') echo 'checked'; ?>
                                                    <!--                                                            value="inside recess" /> Inside recess-->
                                                    <!--                                                        <br />-->
                                                    <!--                                                        <input name="property_trackedtype" type="radio"-->
                                                    <!--                                                            --><?php //if ($property_trackedtype == 'outside recess') echo 'checked'; ?>
                                                    <!--                                                            value="outside recess" /> Outside recess-->
                                                    <!--                                                    </div>-->
                                                    <div id="bypasstype" class="col-sm-2" style="display: none"> By-pass
                                                        Type:
                                                        <br/>
                                                        <input name="property_bypasstype" type="radio"
														  <?php if ($property_bypasstype == 'closed') echo 'checked'; ?>
                                                               value="closed"/> Closed
                                                        <br/>
                                                        <input name="property_bypasstype" type="radio"
														  <?php if ($property_bypasstype == 'open') echo 'checked'; ?>
                                                               value="open"/> Open
                                                    </div>
                                                    <div id="lightblocks" class="col-sm-2" style="display: none"> Light
                                                        Blocks:
                                                        <br/>
                                                        <input name="property_lightblocks" type="radio"
														  <?php if ($property_lightblocks == 'Yes') echo 'checked'; ?>
                                                               value="Yes"/> Yes
                                                        <br/>
                                                        <input name="property_lightblocks" type="radio"
														  <?php if ($property_lightblocks == 'No') echo 'checked'; ?>
                                                               value="No"/> No
                                                    </div>

                                                    <div class="col-sm-4" id="doubleClosingLouvres"
                                                         style="">
                                                        <div>
                                                            <label>
                                                                Double closing louvres:
                                                                <br/>
                                                                <select name="property_double_closing_louvres">
																	<?php
																	$property_doubleClosingLouvres = get_post_meta($product_id, 'property_double_closing_louvres', true);
																	$select = ($property_doubleClosingLouvres == 'Yes') ? 'selected' : ''; ?>
                                                                    <option value="No">No
                                                                    </option>
                                                                    <option value="Yes" <?php echo $select; ?>>Yes
                                                                    </option>
                                                                </select>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom: 10px;">
                                                    <div class="col-sm-3 property_fit"> Measure Type:
                                                        <br/>
                                                        <div class="input-group-container">
                                                            <div class="input-group">
																<?php if (!empty($_GET['id'])) { ?>
                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text"
                                                                           value="<?php echo get_post_meta($product_id, 'property_fit', true); ?>"/>
																<?php } else { ?>
                                                                    <input class="property-select" id="property_fit"
                                                                           name="property_fit" type="text" value="outside"/>
																<?php } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingOne"> Next
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
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse " role="tabpanel"
                                             aria-labelledby="headingOne">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-12"> Frame Type:
                                                        <p id="required-choices-frametype">
                                                            <i>Please select Material &amp; Style in order to view
                                                                available Frame Type choices</i>
                                                        </p>
                                                        <div id="choose-frametype">
                                                            <label>Basswood
                                                                <br/> 4008A
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F50" data-title="4008A" value="307"
																  <?php if ($property_frametype == '307') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4008A.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4008B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4008B" value="310"
																  <?php if ($property_frametype == '310') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4008B.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4008C
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4008C" value="313"
																  <?php if ($property_frametype == '313') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4008C.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4108C
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4108C" value="420"
																  <?php if ($property_frametype == '420') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4108C.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4008T
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4008T" value="353"
																  <?php if ($property_frametype == '353') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4008T.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4028B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4028B" value="333"
																  <?php if ($property_frametype == '333') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4028B.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4007A
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L70" data-title="4007A" value="306"
																  <?php if ($property_frametype == '306') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4007A.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4007B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L90" data-title="4007B" value="309"
																  <?php if ($property_frametype == '309') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4007B.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4007C
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L90" data-title="4007C" value="312"
																  <?php if ($property_frametype == '312') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4007C.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4001A
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L50" data-title="4001A" value="305"
																  <?php if ($property_frametype == '305') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4001A.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4001B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L70" data-title="4001B" value="308"
																  <?php if ($property_frametype == '308') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4001B.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4011B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z3CS" data-title="4011B" value="290"
																  <?php if ($property_frametype == '290') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4011B.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4001C
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L70" data-title="4001C" value="311"
																  <?php if ($property_frametype == '311') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4001C.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4022B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="4022B" value="332"
																  <?php if ($property_frametype == '332') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4022B.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4009
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="D50" data-title="4009" value="142"
																  <?php if ($property_frametype == '142') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4009.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4013
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="4013" value="316"
																  <?php if ($property_frametype == '316') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4013.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4014
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="4014" value="317"
																  <?php if ($property_frametype == '317') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4014.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br/> 4024
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="4024" value="352"
																  <?php if ($property_frametype == '352') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4024.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4003
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z40" data-title="4003" value="314"
																  <?php if ($property_frametype == '314') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4003.png">
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> 4004
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z3CS" data-title="4004" value="315"
																  <?php if ($property_frametype == '315') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/4004.png">
                                                            </label>


                                                            <label>
                                                                PVC
                                                                <br/> P4022B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z40" data-title="P4022B" value="351"
																  <?php if ($property_frametype == '351') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4022B.png">
                                                            </label>
                                                            <!--                                                            <label>-->
                                                            <!--                                                                <br/> P4008K-->
                                                            <!--                                                                <input type="radio" name="property_frametype"-->
                                                            <!--                                                                       data-code="F50" data-title="P4008K"-->
                                                            <!--                                                                       value="323" -->
															<?php //if ($property_frametype == '323') {
															//                                                                    echo "checked";
															//                                                                }
															?>
                                                            <!-- />-->
                                                            <!--                                                                <img src="/wp-content/plugins/shutter-module/imgs/P4008K.png">-->
                                                            <!--                                                            </label>-->
                                                            <label>
                                                                PVC
                                                                <br/> P4008H
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F50" data-title="P4008H" value="321"
																  <?php if ($property_frametype == '321') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4008H.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4028B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F90" data-title="P4028B" value="318"
																  <?php if ($property_frametype == '318') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4028B.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4028B
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F90" data-title="P4028B" value="318"
																  <?php if ($property_frametype == '318') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4028B.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4008S
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F70" data-title="P4008S" value="330"
																  <?php if ($property_frametype == '330') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4008S.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4008T
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F90" data-title="P4008T" value="322"
																  <?php if ($property_frametype == '322') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4008T.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4008W
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F90" data-title="P4008W" value="319"
																  <?php if ($property_frametype == '319') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4008W.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4007A
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="F50" data-title="P4007A" value="331"
																  <?php if ($property_frametype == '331') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4007A.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4001N
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L50" data-title="P4001N" value="320"
																  <?php if ($property_frametype == '320') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4001N.png">
                                                            </label>
                                                            <label>
                                                                <br/> A4001
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L50" data-title="A4001A" value="300"
																  <?php if ($property_frametype == '300') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/A4001.png">
                                                            </label>
                                                            <label>
                                                                <br/> A4002
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="A4002" value="301"
																  <?php if ($property_frametype == '301') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/A4002.png">
                                                            </label>

                                                            <label>
                                                                PVC
                                                                <br/> P4013
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z40" data-title="P4013" value="325"
																  <?php if ($property_frametype == '325') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4013.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4033
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="P4033" value="327"
																  <?php if ($property_frametype == '327') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4033.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4043
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z40" data-title="P4043" value="328"
																  <?php if ($property_frametype == '328') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4043.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4073
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="P4073" value="324"
																  <?php if ($property_frametype == '324') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4073.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4014
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z3CS" data-title="P4014" value="329"
																  <?php if ($property_frametype == '329') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4014.png">
                                                            </label>
                                                            <label>
                                                                PVC
                                                                <br/> P4009
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z3CS" data-title="P4009" value="303"
																  <?php if ($property_frametype == '303') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/P4009.png">
                                                            </label>
                                                            <label>
                                                                <br/> A4027
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="A4027" value="302"
																  <?php if ($property_frametype == '302') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/A4027.png">
                                                            </label>
                                                            <label>
                                                                <br/> A4028
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="Z50" data-title="A4028" value="301"
																  <?php if ($property_frametype == '301') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/A4028.png">
                                                            </label>
                                                            <label>
                                                                <br/> Bottom M Track
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L50" data-title="Bottom M Track"
                                                                       value="144"
																  <?php if ($property_frametype == '144') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/Bottom_M_Track.png">
                                                            </label>
                                                            <label>
                                                                <br/> Track in Board
                                                                <input type="radio" name="property_frametype"
                                                                       data-code="L50" data-title="Track in Board"
                                                                       value="143"
																  <?php if ($property_frametype == '143') {
																	  echo "checked";
																  } ?> />
                                                                <img
                                                                  src="/wp-content/plugins/shutter-module/imgs/Track_in_Board.png">
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row frames" style="margin-top:1em; margin-bottom: 20px;">
                                                    <div class="col-sm-12">
														<?php if (!empty($_GET['id']) || $first_item) { ?>
                                                            <div class="pull-left" id="frame-left"> Frame Left
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
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-right"> Frame Right
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
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-top"> Frame Top
																<?php echo $frametop_first; ?>
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
																       ?>"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-bottom"> Frame Bottom
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
																       ?>"/>
                                                            </div> <?php } else { ?>
                                                            <div class="pull-left" id="frame-left"> Frame Left
                                                                <i class="fa fa-arrow-left"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameleft"
                                                                       name="property_frameleft" type="text" value="70"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-right"> Frame Right
                                                                <i class="fa fa-arrow-right"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frameright"
                                                                       name="property_frameright" type="text" value="75"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-top"> Frame Top
                                                                <i class="fa fa-arrow-up"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_frametop"
                                                                       name="property_frametop" type="text" value="80"/>
                                                            </div>
                                                            <div class="pull-left" id="frame-bottom"> Frame Bottom
                                                                <i class="fa fa-arrow-down"></i>
                                                                <br/>
                                                                <input class="property-select" id="property_framebottom"
                                                                       name="property_framebottom" type="text" value="85"/>
                                                            </div> <?php }
														$display = (!empty($property_builtout)) ? 'inline' : 'none';
														$add_buildout = (empty($property_builtout)) ? '' : 'display: none';
														?>
                                                        <div class="pull-left">
                                                            <span id="add-buildout"
                                                                  style="<?php echo $add_buildout; ?>">
                                                                <br/>
                                                                <button class="btn btn-info" style="padding: 0 12px">Add
                                                                    buildout</button>
                                                            </span>
                                                            <span id="buildout"
                                                                  style="display: <?php echo $display; ?>"> Buildout:
                                                                <br/> <input class="input-small" id="property_builtout"
                                                                             name="property_builtout"
                                                                             placeholder="Enter buildout"
                                                                             style="height: 30px;"
                                                                             type="text"
                                                                             value="<?php
                                                                             echo $property_builtout;
                                                                             ?>"/>
                                                                <button class="btn btn-danger btn-input"
                                                                        id="remove-buildout"> <strong>X</strong> </button>
                                                            </span>
                                                        </div>
                                                        <!-- <div class="pull-left">                                   Stile:                                   <br/>                                   <input class="property-select" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>"                                   />                                 </div> -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12" id="stile-img-earth">
                                                        <div id="choose-stiletype" class="" style="display: block;">
                                                            <label>
                                                                <br/> 60mm A1002D<br/>(Std.beaded stile)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="60mm A1002D (Std.beaded stile)"
                                                                       value="350"
																  <?php if ($property_stile == '350' && $material == 187) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-1"
                                                                     src="/wp-content/plugins/shutter-module/imgs/A1002D.png"/>
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
                                                                       value="380"
																  <?php if ($property_stile == '380' && $material == 188) {
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
                                                                       value="381"
																  <?php if ($property_stile == '381' && $material == 188) {
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
                                                                       value="385"
																  <?php if ($property_stile == '385' && $material == 188) {
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
                                                                       value="382"
																  <?php if ($property_stile == '382' && $material == 188) {
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
                                                                       value="383"
																  <?php if ($property_stile == '383' && $material == 188) {
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
                                                                       value="384"
																  <?php if ($property_stile == '384' && $material == 188) {
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
                                                                       data-title="41mm 1001M(plain butt)" value="376"
																  <?php if ($property_stile == '376' && $material == 6) {
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
                                                                       value="377"
																  <?php if ($property_stile == '377' && $material == 6) {
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
                                                                       value="378"
																  <?php if ($property_stile == '378' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm 1001B<br/>(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm 1001B( plain butt)" value="355"
																  <?php if ($property_stile == '355' && $material == 139) {
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
                                                                       value="356"
																  <?php if ($property_stile == '356' && $material == 139) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-2"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1005B.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm 1003B<br/>(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="51mm 1003B(plain rebate)" value="360"
																  <?php if ($property_stile == '360' && $material == 139) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/1003B.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm 1002B<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.8"
                                                                       data-title="51mm 1002B(beaded butt)" value="357"
																  <?php if ($property_stile == '357' && $material == 139) {
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
                                                                       value="358"
																  <?php if ($property_stile == '358' && $material == 139) {
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
                                                                       value="359"
																  <?php if ($property_stile == '359' && $material == 139) {
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
                                                                       data-title="41mm 1001M(plain butt)" value="376"
																  <?php if ($property_stile == '376' && $material == 6) {
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
                                                                       value="377"
																  <?php if ($property_stile == '377' && $material == 6) {
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
                                                                       value="378"
																  <?php if ($property_stile == '378' && $material == 6) {
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
                                                                       data-title="51mm T1001K(plain butt)" value="370"
																  <?php if ($property_stile == '370' && $material == 6) {
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
                                                                       value="371"
																  <?php if ($property_stile == '371' && $material == 138) {
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
                                                                       value="375"
																  <?php if ($property_stile == '375' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1003K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1002K<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.81"
                                                                       data-title="51mm T1002K (beaded butt)" value="372"
																  <?php if ($property_stile == '372' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1006K<br/>(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm T1006K (beaded D-mould)"
                                                                       value="373"
																  <?php if ($property_stile == '373' && $material == 6) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1004K<br/>(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm T1004K (beaded rebate)"
                                                                       value="374"
																  <?php if ($property_stile == '374' && $material == 6) {
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
                                                                       data-title="41mm 1001M(plain butt)" value="376"
																  <?php if ($property_stile == '376' && $material == 138) {
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
                                                                       value="377"
																  <?php if ($property_stile == '377' && $material == 138) {
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
                                                                       value="378"
																  <?php if ($property_stile == '378' && $material == 138) {
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
																  <?php if ($property_stile == '445' && $material == 138) {
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
																  <?php if ($property_stile == '447' && $material == 138) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 41mm T1004M<br/>(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RFS 50.8"
                                                                       data-title="41mm T1006M(beaded rebate)"
                                                                       value="446"
																  <?php if ($property_stile == '446' && $material == 138) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1004M.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1001K<br/>(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="FS 50.8"
                                                                       data-title="51mm T1001K(plain butt)" value="370"
																  <?php if ($property_stile == '370' && $material == 138) {
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
                                                                       value="371"
																  <?php if ($property_stile == '371' && $material == 138) {
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
                                                                       value="375"
																  <?php if ($property_stile == '375' && $material == 138) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-3"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1003K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1002K<br/>(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="BS 50.81"
                                                                       data-title="51mm T1002K (beaded butt)" value="372"
																  <?php if ($property_stile == '372' && $material == 138) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-4"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1002K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1006K<br/>(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="DBS 50.8"
                                                                       data-title="51mm T1006K (beaded D-mould)"
                                                                       value="373"
																  <?php if ($property_stile == '373' && $material == 138) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-5"
                                                                     src="/wp-content/plugins/shutter-module/imgs/T1006K.png"/>
                                                            </label>
                                                            <label>
                                                                <br/> 51mm T1004K<br/>(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                       data-code="RBS 50.8"
                                                                       data-title="51mm T1004K (beaded rebate)"
                                                                       value="374"
																  <?php if ($property_stile == '374' && $material == 138) {
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
                                                                       value="380"
																  <?php if ($property_stile == '380' && $material == 137) {
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
                                                                       value="381"
																  <?php if ($property_stile == '381' && $material == 137) {
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
                                                                       value="385"
																  <?php if ($property_stile == '385' && $material == 137) {
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
                                                                       value="382"
																  <?php if ($property_stile == '382' && $material == 137) {
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
                                                                       value="383"
																  <?php if ($property_stile == '383' && $material == 137) {
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
                                                                       value="384"
																  <?php if ($property_stile == '384' && $material == 137) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P1004B.png"/>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="row">                               <div class="col-sm-4">                                     <div class="">                                       Stile:                                       <br/>                                       <input class="property-select required" id="property_stile" name="property_stile" type="text" value="<?php echo get_post_meta($product_id, 'property_stile', true); ?>" />                                     </div>                                     <br/>                               </div>                             </div> -->
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingColour"> Next
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
                                                    <!--                                                    <span type="button" class="js-next-step next-step"-->
                                                    <!--                                                          tabindex="2">Next step</span>-->
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
                                                    <div class="col-sm-3"> Hinge Colour:
                                                        <br/>
                                                        <input class="property-select" id="property_hingecolour"
                                                               name="property_hingecolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $hingecolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_hingecolour', true);
														       }
														       ?>"/>
                                                    </div>
                                                    <div class="col-sm-3"> Shutter Colour:
                                                        <br/>
                                                        <input class="property-select" id="property_shuttercolour"
                                                               name="property_shuttercolour" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $shuttercolour_first;
														       } else {
															       echo get_post_meta($product_id, 'property_shuttercolour', true);
														       }
														       ?>"/>
                                                    </div>
                                                    <div class="col-sm-3" id="colour-other" style="display: none"> Other
                                                        Colour:
                                                        <br/>
                                                        <input id="property_shuttercolour_other"
                                                               name="property_shuttercolour_other" style="height: 30px"
                                                               type="text" value="<?php echo $property_colour_other; ?>"/>
                                                    </div>
                                                    <div class="col-sm-3"> Control Type:
                                                        <br/>
                                                        <input class="property-select" id="property_controltype"
                                                               name="property_controltype" type="text"
                                                               value="<?php
														       if ($first_item) {
															       echo $controltype_first;
														       } else {
															       echo get_post_meta($product_id, 'property_controltype', true);
														       }
														       ?>"/>
                                                    </div>
                                                </div>
                                                <div class="row layout-row" style="margin-top:1em;">

													<?php
													$property_nr_sections = get_post_meta($product_id, 'property_nr_sections', true);
													for ($sec = 1; $sec <= $property_nr_sections; $sec++) {
														$property_layoutcode = get_post_meta($product_id, 'property_layoutcode' . $sec, true);
														$property_width = get_post_meta($product_id, 'property_width' . $sec, true);
														$property_opendoor = get_post_meta($product_id, 'property_opendoor' . $sec, true);
														$property_total_section = get_post_meta($product_id, 'property_total_section' . $sec, true);
														$show_dooropen = !empty($property_opendoor) ? 'block' : 'none';

														?>
                                                        <div class="col-sm-4" id="layoutcode-column">
                                                            Layout Configuration Section <?php echo $sec; ?> : <br>
                                                            <div class="input-group-container">
                                                                <div class="input-group">

                                                                    <input class="required input-medium layoutcode"
                                                                           id="property_layoutcode<?php echo $sec; ?>"
                                                                           name="property_layoutcode<?php echo $sec; ?>"
                                                                           style="text-transform:uppercase" type="text"
                                                                           value="<?php echo $property_layoutcode; ?>">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4"> Width (mm):
                                                            <br>
                                                            <input class="required number input-medium p_width"
                                                                   id="property_width<?php echo $sec; ?>"
                                                                   name="property_width<?php echo $sec; ?>" type="text"
                                                                   value="<?php echo $property_width; ?>">
                                                        </div>

                                                        <div class="col-sm-4"
                                                             style="display: <?php echo $show_dooropen; ?>;"> Door to
                                                            open first: <br> <select
                                                              id="property_opendoor<?php echo $sec; ?>"
                                                              name="property_opendoor<?php echo $sec; ?>">
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
                                                            <input class="required input-medium"
                                                                   id="property_total_section<?php echo $sec; ?>"
                                                                   name="property_total_section<?php echo $sec; ?>"
                                                                   style="text-transform:uppercase" type="hidden"
                                                                   value="<?php echo $property_total_section; ?>">
                                                        </div>
                                                        <div class="clearfix"></div>

                                                        <div class="extra-columns-row<?php echo $sec; ?>">
                                                            <div class="col-sm-12">
																<?php if ($nr_code_prod) {
																	foreach ($nr_code_prod[$sec] as $key => $val) {
																		for ($i = 1; $i < $val + 1; $i++) {
																			if ($key == 't') { ?>
                                                                                <div class="pull-left extra-column<?php echo $sec; ?>">
                                                                <span
                                                                  class="extra-column-label<?php echo $sec; ?>">T-Post<?php echo $i; ?></span>:
                                                                                    <br>
                                                                                    <div class="input-group">

                                                                                        <input
                                                                                          name="property_t<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          id="property_t<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          class="input-small required" type="text"
                                                                                          value="<?php echo get_post_meta($product_id, 'property_t' . $i . '_' . $sec, true); ?>">
                                                                                    </div>
                                                                                </div>
                                                                                <!-- disabled by teo -->
                                                                                <!-- /disabled by teo -->
																			<?php } elseif ($key == 'g') { ?>
                                                                                <div class="pull-left extra-column<?php echo $sec; ?>">
                                                                <span
                                                                  class="extra-column-label<?php echo $sec; ?>">G-Post<?php echo $i; ?></span>:
                                                                                    <br>
                                                                                    <div class="input-group">
                                                                                        <input
                                                                                          name="property_g<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          id="property_g<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          class="input-small required" type="text"
                                                                                          value="<?php echo get_post_meta($product_id, 'property_g' . $i . '_' . $sec, true); ?>">
                                                                                    </div>
                                                                                </div>
																			<?php } elseif ($key == 'c') { ?>
                                                                                <div class="pull-left extra-column<?php echo $sec; ?>">
                                                                <span
                                                                  class="extra-column-label<?php echo $sec; ?>">C-Post<?php echo $i; ?>
                                                                </span>:
                                                                                    <br>
                                                                                    <div class="input-group">

                                                                                        <input
                                                                                          name="property_c<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          id="property_c<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                          class="input-small required" type="text"
                                                                                          value="<?php echo get_post_meta($product_id, 'property_c' . $i . '_' . $sec, true); ?>">
                                                                                    </div>
                                                                                </div>
																			<?php }
																		}
																	}
																} ?>
                                                            </div>
                                                        </div>
                                                        <div class="extra-columns-buildout-row<?php echo $sec; ?>">
                                                            <div class="col-sm-12">
																<?php if ($nr_code_prod) {
																	foreach ($nr_code_prod[$sec] as $key => $val) {
																		for ($i = 1; $i < $val + 1; $i++) {
																			if ($key == 't') {
																				$tposttypeSec = get_post_meta($product_id, 't-post-type' . $sec, true);
																				if ($i == 1) { ?>
                                                                                    <div
                                                                                      class="pull-left extra-column-buildout<?php echo $sec; ?> <?php if ($material == '188') {
																						  echo 't-angle-select-type';
																					  } ?>">
                                                                <span
                                                                  class="extra-column-label<?php echo $sec; ?>">T-Post
                                                                    Style :</span><br>
                                                                                        <select id="buildout-select"
                                                                                                name="t-post-type<?php echo $sec; ?>">
																							<?php if ($tposttypeSec == 'normal') { ?>
                                                                                                <option value="normal" selected>
                                                                                                    Normal
                                                                                                </option>
                                                                                                <option value="adjustable">
                                                                                                    Adjustable
                                                                                                </option>
																							<?php } elseif ($tposttypeSec == 'adjustable') { ?>
                                                                                                <option value="normal">
                                                                                                    Normal
                                                                                                </option>
                                                                                                <option value="adjustable" selected>
                                                                                                    Adjustable
                                                                                                </option>
																							<?php } ?>
                                                                                        </select>
                                                                                        <br>
                                                                                    </div>
                                                                                    <div class="pull-left extra-column<?php echo $sec; ?>">
                                                                <span
                                                                  class="extra-column-label<?php echo $sec; ?>">T-Post
                                                                    Buildout</span>:
                                                                                        <br>
                                                                                        <div class="input-group">

                                                                                            <input
                                                                                              name="property_t_buildout<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                              id="property_t_buildout<?php echo $i; ?>_<?php echo $sec; ?>"
                                                                                              class="input-small" type="checkbox" value="yes"
																							  <?php if (get_post_meta($product_id, 'property_t_buildout' . $i . '_' . $sec, true) == 'yes') {
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
														<?php
													}
													?>

                                                </div>


                                                <div class="row">
                                                    <div class="col-sm-3 tpost-type"
                                                         style="<?php if ($containT) {
														     echo 'display: block';
													     } else {
														     echo 'display: none';
													     } ?>"> T-Post Type:
                                                        <br/>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													if (!empty($material) && $material == 187 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-earth" style="<?php
														if ($material == 187 && !empty($containT)) {
															$t_earth = true;
															echo 'display: block;';
														} else {
															$t_earth = false;
															echo 'display: none;';
														}
														?>">
															<?php echo $property_tposttype; ?>
                                                            <label>
                                                                <br/> <b>A7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="A7001 - Earth Standard T-Post"
                                                                       value="442"
																  <?php if ($property_tposttype == '442' && $t_earth == true) {
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
													if ($material == 188 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowood"
                                                             style="<?php
														     $material = get_post_meta($product_id, 'property_material', true);
														     if (!empty($material) && $material == 188 && $containT) {
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
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_eco == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_eco == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_eco == true) {
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
													if (!empty($material) && $material == 139 && $containT) {
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
                                                                       value="438"
																  <?php if ($property_tposttype == '438' && $t_sup == true) {
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
                                                                       data-title="7011 - Large T-Post" value="441"
																  <?php if ($property_tposttype == '441' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="7032"
                                                                       value="443"
																  <?php if ($property_tposttype == '443' && $t_sup == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_sup == true) {
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
													if (!empty($material) && $material == 138 && $containT) {
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
                                                            <!-- here was basswood t-posts -->
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_bio == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
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
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7001</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="7001 - Supreme Standard T-Post"
                                                                       value="438"
																  <?php if ($property_tposttype == '438' && $t_bio == true) {
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
                                                                       data-title="7011 - Large T-Post" value="441"
																  <?php if ($property_tposttype == '441' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7011.png"/>
                                                            </label>
                                                            <label>
                                                                Basswood
                                                                <br/> <b>7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="7032"
                                                                       value="443"
																  <?php if ($property_tposttype == '443' && $t_sup == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_sup == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/7201.png"/>
                                                            </label>
                                                            <label style="display:block;visibility: visible;">PVC
                                                                <br/> <b>P7030</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_bio == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_bio == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7201.png"/>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="tpost-type" style="<?php
													$material = get_post_meta($product_id, 'property_material', true);
													if (!empty($material) && $material == 137 && $containT) {
														echo 'display: block;';
													} else {
														echo 'display: none;';
													}
													?>">
                                                        <div class="col-sm-12 tpost-img type-img-ecowoodPlus" style="<?php
														$material = get_post_meta($product_id, 'property_material', true);
														if (!empty($material) && $material == 137 && $containT) {
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
                                                                       data-code="RBS 50.8" data-title="P7030"
                                                                       value="444"
																  <?php if ($property_tposttype == '444' && $t_grn == true) {
																	  echo "checked";
																  } ?> />
                                                                <img class="stile-6"
                                                                     src="/wp-content/plugins/shutter-module/imgs/P7030.png"/>
                                                            </label>
                                                            <label>PVC
                                                                <br/> <b>P7032</b><br/>
                                                                <input type="radio" name="property_tposttype"
                                                                       data-code="RBS 50.8"
                                                                       data-title="P7032 - Standard T-Post" value="437"
																  <?php if ($property_tposttype == '437' && $t_grn == true) {
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
                                                                       value="439"
																  <?php if ($property_tposttype == '439' && $t_grn == true) {
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
                                                                <span class="lbl"> Include 2 x Spare Louvres (£6 +
                                                                    VAT)</span>
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
																} ?> value="yes"                                     /> -->
                                                                <span class="lbl"> Ring Pull</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
																<?php $property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true) ?>
                                                                <input type="number" id="property_ringpull_volume"
                                                                       name="property_ringpull_volume"
                                                                       value="<?php echo $property_ringpull_volume; ?>">
                                                                <span class="lbl"> How Many? (please specify position on
                                                                    the comment field)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-6" id="locks" style="display:block">
                                                        <div>
                                                            <label>
																<?php $property_locks = get_post_meta($product_id, 'property_locks', true) ?>
                                                                <select id="property_locks" name="property_locks">
                                                                    <option value="No" <?php if ($property_locks == 'No') {
																		echo 'selected';
																	} ?>>No
                                                                    </option>
                                                                    <option value="Yes" <?php if ($property_locks == 'Yes') {
																		echo 'selected';
																	} ?>>Yes
                                                                    </option>
                                                                </select>
                                                                <span class="lbl"> Locks</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
																<?php $property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true) ?>

                                                                <input type="number" id="property_locks_volume"
                                                                       name="property_locks_volume"
                                                                       value="<?php echo $property_locks_volume; ?>"><span
                                                                  class="lbl">How Many sets? (please specify position
                                                                    on the comments field)</span>

                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="central_lock_position" style="display: none;">
                                                        <label>
															<?php
															$property_central_lock = get_post_meta($product_id, 'property_central_lock', true);
															?>

                                                            <select id="property_central_lock" name="property_central_lock">
                                                                <option value="No" <?php if ($property_central_lock == 'No') {
																	echo 'selected';
																} ?>>No
                                                                </option>
                                                                <option value="Yes" <?php if ($property_central_lock == 'Yes') {
																	echo 'selected';
																} ?>>Yes
                                                                </option>
                                                            </select>
                                                            <span class="lbl"> Central Lock</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr/>
                                                        <button class="btn btn-info show-next-panel"
                                                                next-panel="#headingLayout"> Next
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
                                                        <div c;ass="before-canvas" style="display: none">
                                                            <textarea id="drawingConfig"
                                                                      style="width:300px;height:150px;"></textarea>
                                                            <input id="splitHeight" type="number" name="quantity_split"
                                                                   min="0" max="5000" step="10">
                                                            <button id="runButton">Run</button>
                                                            <textarea id="drawingConfigScaled"
                                                                      style="width:300px;height:150px;"></textarea>
                                                            <button id="runButtonScaled">Run</button>
                                                        </div>
                                                        <div id="outside_canvas"></div>
                                                        <!--                                                        <div id="canvas_container1" class="canvas_container"-->
                                                        <!--                                                             style="min-height: 500px;border: 1px solid #aaa;background-image: url('/wp-content/plugins/shutter-module/imgs/drawing_graph.png');"></div>-->
                                                        <br/>
                                                        <button class="btn btn-info print-drawing" style="z-index: 10;">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        <!--                            <textarea id="shutter_svg" name="shutter_svg"-->
                                                        <!--                                      style="display:none"></textarea>-->
                                                    </div>
                                                    <div class="col-lg-4 col-md-6"> Comments:
                                                        <br/>
														<?php
														$comments_customer = get_post_meta($product_id, 'comments_customer', true);
														// print_r($product_id);
														if (!empty($product_id)) {
															?>
                                                            <textarea id="comments_customer" name="comments_customer"
                                                                      rows="5"
                                                                      style="width: 100%"><?php
																echo get_post_meta($product_id, 'comments_customer', true); ?></textarea>
															<?php
														} else {
															echo ' <textarea id="comments_customer" name="comments_customer" rows="5" style="width: 100%"></textarea>';
														}
														?>
                                                        <hr/>
                                                        <div id="nowarranty" style="display:none"> I accept that there
                                                            is no warranty for this item
                                                            <br/>
                                                            <input id="property_nowarranty" name="property_nowarranty"
                                                                   type="checkbox" value="Yes"/>
                                                        </div>
                                                        <div class="quantity" style="visibility:hidden" ;>
                                                            Number of this:
															<?php
															if (!empty($_GET['id'])) {
																?>
                                                                <!--                                                                    <input id="quantity" class="input-text qty text" min="1" max="" name="quantity" value="--><?php //echo $quant
																?>
                                                                <!--" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="" type="number">-->
                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity"
                                                                       value="<?php echo get_post_meta($product_id, 'quantity', true) ?>"
                                                                       title="Qty" size="4" pattern="[0-9]*"
                                                                       inputmode="numeric" aria-labelledby="" type="number">

																<?php
															} else {
																?>
                                                                <input id="quantity" class="input-text qty text" min="1"
                                                                       max="" name="quantity" value="1" title="Qty" size="4"
                                                                       pattern="[0-9]*" inputmode="numeric" aria-labelledby=""
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
                                                            <input class="" id="panels_left_right" name="panels_left_right"
                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"/>
                                                            <button class="btn btn-primary update-btn-admin">Update
                                                                Product
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button>
                                                            <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                                 alt="" class="spinner" style="display:none;"/>
															<?php
														} elseif (!empty($_GET['id']) && empty($_GET['cust_id'])) { ?>
                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>
                                                            <input class="" id="panels_left_right" name="panels_left_right"
                                                                   type="hidden"
                                                                   value="<?php echo strtoupper(get_post_meta($product_id, 'panels_left_right', true)); ?>"/>
                                                            <button class="btn btn-primary update-btn">Update Product
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button> <?php
														} else {
															?>
                                                            <input id="page_title" name="page_title" type="hidden"
                                                                   value="<?php echo get_the_title(); ?>"/>
                                                            <input class="" id="panels_left_right" name="panels_left_right"
                                                                   type="hidden" value=""/>
                                                            <button class="btn btn-success"> Add to Quote
                                                                <i class="fa fa-chevron-right"></i>
                                                            </button> <?php
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
                            <div class="col-md-12"></div>
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

                            #choose-frametype label > input,
                            #choose-stiletype label > input {
                                /* HIDE RADIO */
                                /* display: none; */
                            }

                            #choose-frametype label,
                            #choose-stiletype label {
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

                            #choose-frametype label > input + img,
                            #choose-stiletype label > input + img {
                                /* IMAGE STYLES */
                                cursor: pointer;
                                border: 4px solid transparent;
                            }

                            #choose-frametype label > input:checked + img,
                            #choose-stiletype label > input:checked + img {
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

                            /* .extra-columns-row .pull-left.extra-column {                                 display: inline-block;                             } */
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

                            div.select2-container.b-angle-select {
                                display: none !important;
                            }

                            b {
                                font-weight: 900 !important;
                            }

                            .row.layout-row > .col-sm-4 {
                                min-height: 80px;
                            }

                            /* Chrome, Safari, Edge, Opera */
                            input::-webkit-outer-spin-button,
                            input::-webkit-inner-spin-button {
                                -webkit-appearance: none;
                                margin: 0;
                            }

                            /* Firefox */
                            input[type=number] {
                                -moz-appearance: textfield;
                            }

                            .pull-left {
                                padding-right: 0.5em;
                                display: table;
                                width: auto !important;
                                float: left !important;
                                padding-right: 0.5em;
                            }
                        </style>
                        <div class="show-prod-info"></div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- Modal Start -->
    <div id="errorModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="errorModalLabel">Configuration errors</h3>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button class="btn btn-close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Modal Start Draw -->
    <div id="drawModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="drawModalLabel"
         aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title inline"><b>Draw</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">

                    <div class="col-sm-12" id="shape-section-draw" style="display: block">
                        <br/>

                        <div class="fs-container col-xs-8">
                            <div id="lc" style='height: 700px;'></div>
                        </div>
                        <div class="images col-xs-4">
                            <p>Select Type: </p>
                            <button class="btn btn-small clear-shape" style="display: none;">Clear Shape</button>
                            <div class="arched shapes" style="display:none;">

                                <label style="">
                                    <br> Arched 1
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 1" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched1.png">
                                </label>

                                <label style="">
                                    <br> Arched 2
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 2" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched2.png">
                                </label>

                                <label style="">
                                    <br> Arched 3
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped 3" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/Arched3.png">
                                </label>

                                <label style="">
                                    <br> CircleR
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/CircleR.png">
                                </label>

                                <label style="">
                                    <br> CircleS
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/CircleS.png">
                                </label>

                                <label>
                                    <br> SemiR
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-title="No Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/SemiR.png">
                                </label>

                                <label>
                                    <br> SemiS
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-title="No Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/Arched/SemiS.png">
                                </label>

                            </div>
                            <div class="special shapes" style="display:none;">

                                <label style="">
                                    <br> Hexa
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Hexa.png">
                                </label>

                                <label style="">
                                    <br> Octo
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Arched Shaped" value="36">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Octo.png">
                                </label>

                                <label style="">
                                    <br> Rake1
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Rake1.png">
                                </label>

                                <label style="">
                                    <br> Rake2
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/SpecialShapes/Rake2.png">
                                </label>

                            </div>
                            <div class="french shapes" style="display:none;">

                                <label style="">
                                    <br> FrDoorCurvedLeft
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="tracked"
                                           data-title="Tracked By-Pass" value="37">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorCurvedLeft.png">
                                </label>

                                <label style="">
                                    <br> FrDoorCurvedRight
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorCurvedRight.png">
                                </label>

                                <label style="">
                                    <br> FrDoorSquareLeft
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorSquareLeft.png">
                                </label>

                                <label style="">
                                    <br> FrDoorSquareRight
                                    <br>
                                    <input type="radio" name="property_shape_draw" data-code="shaped"
                                           data-title="Special Shaped" value="33">
                                    <img src="/wp-content/plugins/shutter-module/imgs/FrDoors/FrDoorSquareRight.png">
                                </label>

                            </div>


                            <div class="col-xs-12">
                                <br>
                                <button class="btn btn-default export-canvas" data-action="export-as-png"
                                        value="Export as PNG">Attach drawing
                                </button>
                                <img class="exported-image" src="" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-close" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <script type="text/javascript">
        jQuery(document).ready(function () {

            var litCanv;


            LC.defineShape('Semicircle', {
                constructor: function (args) {
                    if (args == null) {
                        args = {};
                    }
                    this.x = args.x || 0;
                    this.y = args.y || 0;
                    this.width = args.width || 0;
                    this.height = args.height || 0;
                    this.strokeWidth = args.strokeWidth || 1;
                    this.strokeColor = args.strokeColor || 'black';
                    return this.fillColor = args.fillColor || 'transparent';
                },
                getBoundingRect: function () {
                    return {
                        x: this.x - this.strokeWidth / 2,
                        y: this.y - this.strokeWidth / 2,
                        width: this.width + this.strokeWidth,
                        height: this.height + this.strokeWidth
                    };
                },
                toJSON: function () {
                    return {
                        x: this.x,
                        y: this.y,
                        width: this.width,
                        height: this.height,
                        strokeWidth: this.strokeWidth,
                        strokeColor: this.strokeColor,
                        fillColor: this.fillColor
                    };
                },
                fromJSON: function (data) {
                    return LC.createShape('Semicircle', data);
                },
                move: function (moveInfo) {
                    if (moveInfo == null) {
                        moveInfo = {};
                    }
                    this.x = this.x - moveInfo.xDiff;
                    return this.y = this.y - moveInfo.yDiff;
                },
                setUpperLeft: function (upperLeft) {
                    if (upperLeft == null) {
                        upperLeft = {};
                    }
                    this.x = upperLeft.x;
                    return this.y = upperLeft.y;
                }
            });

            /* Define canvas and SVG renderers */

            LC.defineCanvasRenderer('Semicircle', function (ctx, shape) {
                var centerX, centerY, halfHeight, halfWidth;
                ctx.save();
                halfWidth = Math.floor(shape.width / 2);
                halfHeight = Math.floor(shape.height / 2);
                centerX = shape.x + halfWidth;
                centerY = shape.y + halfHeight;
                ctx.translate(centerX, shape.y);
                ctx.scale(1, Math.abs(shape.height / shape.width));
                ctx.beginPath();
                ctx.arc(0, 0, Math.abs(halfWidth), Math.PI, 0, false);
                ctx.closePath();
                ctx.restore();
                ctx.fillStyle = shape.fillColor;
                ctx.fill();
                ctx.lineWidth = shape.strokeWidth;
                ctx.strokeStyle = shape.strokeColor;
                return ctx.stroke();
            });


            var MyTool = function (lc) { // take lc as constructor arg
                var self = this;

                return {
                    name: 'MyTool',
                    iconName: 'semicircle',
                    strokeWidth: lc.opts.defaultStrokeWidth,
                    optionsStyle: 'stroke-width',

                    didBecomeActive: function (lc) {
                        var onPointerDown = function (pt) {
                            self.currentShape = LC.createShape('Semicircle', {
                                x: pt.x,
                                y: pt.y,
                                strokeWidth: 10,
                                strokeColor: 'rgba(0, 0, 31, 1)',
                                fillColor: 'transparent'
                            });
                            lc.setShapesInProgress([self.currentShape]);
                            lc.repaintLayer('main');
                        };

                        var onPointerDrag = function (pt) {
                            self.currentShape.width = pt.x - self.currentShape.x;
                            self.currentShape.height = pt.y - self.currentShape.y;
                            lc.setShapesInProgress([self.currentShape]);
                            lc.repaintLayer('main');
                        };

                        var onPointerUp = function (pt) {
                            self.currentShape.width = pt.x - self.currentShape.x;
                            self.currentShape.height = pt.y - self.currentShape.y;
                            lc.setShapesInProgress([]);
                            lc.saveShape(self.currentShape);
                        };

                        var onPointerMove = function (pt) {
                            console.log("Mouse moved to", pt);
                        };

                        // lc.on() returns a function that unsubscribes us. capture it.
                        self.unsubscribeFuncs = [
                            lc.on('lc-pointerdown', onPointerDown),
                            lc.on('lc-pointerdrag', onPointerDrag),
                            lc.on('lc-pointerup', onPointerUp),
                            lc.on('lc-pointermove', onPointerMove)
                        ];
                    },

                    willBecomeInactive: function (lc) {
                        // call all the unsubscribe functions
                        self.unsubscribeFuncs.map(function (f) {
                            f()
                        });
                    }
                }

            };

            LC.defaultTools = [
                LC.tools.Line,
                LC.tools.Text,
                LC.tools.Ellipse,
                LC.tools.Rectangle,
                LC.tools.Pencil,
                LC.tools.Eraser,
                //LC.tools.Polygon,
                //LC.tools.Eyedropper
            ];


            // on click make draw pad show
            jQuery('#btnDrawModal').click(function () {
                // on select shape show clear shape button

                // config draw
                var backgroundImage = new Image();
                backgroundImage.src = '';

                console.log('draw modal open');
                // config draw
                // var backgroundImage = new Image()
                // backgroundImage.src = '';

                setTimeout(function () {

                    var lcsec = LC.init(document.getElementById("lc"), {
                        imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                        primaryColor: '#000',
                        secondaryColor: 'transparent',
                        backgroundColor: '#fff',
                        toolbarPosition: 'bottom',
                        defaultStrokeWidth: 2,
                        strokeWidths: [2],
                        tools: LC.defaultTools.concat([MyTool]),
                        backgroundShapes: [
                            LC.createShape(
                                'Image', {
                                    x: 250,
                                    y: 75,
                                    image: backgroundImage,
                                    scale: 1
                                }),
                        ],
                        imageSize: {
                            width: null,
                            height: null
                        }
                    });

                    litCanv = lcsec;

                }, 500);

            });

            // on click make draw pad show
            jQuery('#shape-section-draw .images > .shapes > label').click(function () {
                // on select shape show clear shape button
                jQuery('#shape-section-draw .images .clear-shape').show();

                var itemImage = jQuery(this).find('img').attr('src');
                console.log(this);
                console.log(itemImage);

                // config draw
                var backgroundImage = new Image();
                // backgroundImage.src = 'http://local-matrix-demo/wp-content/plugins/shutter-module/imgs/drawing_graph.png';
                backgroundImage.src = itemImage;

                var lc = LC.init(document.getElementById("lc"), {
                    imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                    primaryColor: '#000',
                    secondaryColor: 'transparent',
                    backgroundColor: '#fff',
                    toolbarPosition: 'bottom',
                    defaultStrokeWidth: 2,
                    strokeWidths: [2],
                    tools: LC.defaultTools.concat([MyTool]),
                    backgroundShapes: [
                        LC.createShape(
                            'Image', {
                                x: 250,
                                y: 75,
                                image: backgroundImage,
                                scale: 1
                            }),
                    ],
                    imageSize: {
                        width: null,
                        height: null
                    }
                });

                litCanv = lc;

                // var img = new Image();
                // img.src = 'http://placekitten.com/200/300';
                // lc.saveShape(LC.createShape('EllipseDemo', {x: 100, y: 100, image: img}));


                jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();

                jQuery('.lc-pick-tool.toolbar-button.thin-button[title="Line"]').click(function () {
                    if (!jQuery('.lc-options.horz-toolbar > div > div:first-child').hasClass(
                        'selected')) {
                        jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                    }
                    if (!jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').hasClass(
                        'selected')) {
                        jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();
                    }
                });

                jQuery('#shape-section-draw .images .clear-shape').click(function () {
                    jQuery('#shape-section-draw .images > .shapes > label input').prop('checked',
                        false);

                    var backgroundImage = new Image()
                    backgroundImage.src = '';

                    var lc = LC.init(document.getElementById("lc"), {
                        imageURLPrefix: '/wp-content/themes/storefront-child/canvas-demo/_assets/lc-images',
                        primaryColor: '#000',
                        secondaryColor: 'transparent',
                        backgroundColor: '#fff',
                        toolbarPosition: 'bottom',
                        defaultStrokeWidth: 2,
                        strokeWidths: [2],
                        tools: LC.defaultTools.concat([MyTool]),
                        backgroundShapes: [
                            LC.createShape(
                                'Image', {
                                    x: 250,
                                    y: 75,
                                    image: backgroundImage,
                                    scale: 1
                                }),
                        ],
                        imageSize: {
                            width: null,
                            height: null
                        }
                    });

                    litCanv = lc;

                    jQuery('.lc-options.horz-toolbar > div > div:first-child').click();
                    jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)').click();

                    jQuery('.lc-pick-tool.toolbar-button.thin-button[title="Line"]').click(
                        function () {
                            if (!jQuery('.lc-options.horz-toolbar > div > div:first-child')
                                .hasClass('selected')) {
                                jQuery('.lc-options.horz-toolbar > div > div:first-child')
                                    .click();
                            }
                            if (!jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)')
                                .hasClass('selected')) {
                                jQuery('.lc-options.horz-toolbar > div > div:nth-child(2)')
                                    .click();
                            }
                        });
                });

            });


            // export image
            var imageExport = new Image();
            $('.export-canvas').on('click', function () {

                imageExport.src = litCanv.getImage().toDataURL();
                console.log(imageExport.src);
                $('.exported-image').attr('src', imageExport.src);
                var roomName = jQuery('input#property_room_other').val();

                $.ajax({
                    method: "POST",
                    url: "/wp-content/plugins/shutter-module/ajax/ajax-export-draw-shape.php",
                    data: {
                        imageSrc: imageExport.src,
                        roomName: roomName
                    }
                })
                    .done(function (data) {

                        console.log(data);
                        var imageUrl = data;
                        $('.exported-image').attr('src', imageUrl);
                        $('input[name="attachmentDraw"]').val(imageUrl);
                        $('img#frontend-image-draw').attr('src', imageUrl);

                        alert('Image Uploaded!');
                        // alert(data);

                    });
            });

        });
    </script>
</div>
</div>