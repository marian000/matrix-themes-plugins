<?php

if (!empty($_GET['cust_id'])) {
	$user_id = $_GET['cust_id'];
} else {
	$user_id = get_current_user_id();
}
$edit_customer = ($_GET['order_edit_customer'] == 'editable') ? true : false;
$clone_id = !empty($_GET['clone']) ? base64_decode($_GET['clone']) : '';

$dealer_id = get_user_meta($user_id, 'company_parent', true);

$meta_key = 'wc_multiple_shipping_addresses';

global $wpdb;
if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
	$addresses = maybe_unserialize($addresses);
}

$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

$userialize_data = unserialize($addresses[0]->meta_value);
$session_key = $userialize_data['customer_id'];

$session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key ");
//print_r($session);
$userialize_session = unserialize($session[0]->session_value);

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

$product_id = $_GET['id'] / 1498765 / 33;
echo "ID-prod: " . $_GET['id'] / 1498765 / 33;
// echo "<br>";
$meta = get_post_meta($_GET['id'] / 1498765 / 33);
// echo "<pre>";
// echo "<pre>";
// echo print_r($meta);
// echo "</pre>";

$batten_type = get_post_meta($product_id, 'batten_type', true);
$attachment = get_post_meta($product_id, 'attachment', true);

global $woocommerce;
$cart = WC()->cart->get_cart();

foreach ($cart as $cart_item_key => $cart_item) {
	$products = $cart_item['data']->get_id();
//        echo '<pre>';
//        print_r($cart_item);
//        echo '</pre>';
	// echo $cart_item['quantity'];
	if ($products == $product_id) {
		$quant = $cart_item['quantity'];
		echo ' QTY:' . $quant;
	}
}

$cart_item = $woocommerce->cart->get_cart();
//print_r($cart_item);

?>

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

<div class="main-content">
  <div class="page-content" style="background-color: #F7F7F7">
    <div class="page-content-area">

      <div class="page-header">
        <h1>Update Batten</h1>
      </div>

      <div class="row">
        <form accept-charset="UTF-8" data-type="json" enctype="multipart/form-data"
              id="add-product-single-form" method="post">
          <div class="col-xs-12">

            <div class="row">
              <div class="col-sm-12">
                <strong>Order Reference:</strong> <?php echo $cart_name; ?>
                <input type="hidden" name="product_id_updated" value="<?php echo $product_id; ?>">

                <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="dealer_id" value="<?php echo $dealer_id; ?>">
                <input type="hidden" name="edit_customer" value="<?php echo $edit_customer; ?>">
                <input type="hidden" name="order_edit" value="<?php echo $order_edit; ?>">
                <br/>
                <br/>
              </div>
            </div>

            <div class="row" style="margin-top:1em;">
              <div class="col-sm-2">
                <div id="choose-product-shutter">
                  <label>
                    Custom Batten
                    <br/>
                    <input type="radio" name="product_id" value="40"/>
                    <img src="/wp-content/plugins/shutter-module/imgs/thumb_batten.jpg?1443615516">
                  </label>
                </div>
              </div>

              <!--                            <div class="col-sm-2">-->
              <!--                                Batten type:-->
              <!--                                <br>-->
              <!--                                <label>-->
              <!--                                    <input type="radio" name="batten_type" value="custom" -->
							<?php //if ($batten_type == 'custom') {
							//                                        echo 'checked';
							//                                    } ?><!-- >-->
              <!--                                    Custom-->
              <!--                                </label>-->
              <!--                                <label>-->
              <!--                                    <input type="radio" name="batten_type" value="standard" -->
							<?php //if ($batten_type == 'standard') {
							//                                        echo 'checked';
							//                                    } ?><!-- > -->
              <!--                                    Standard-->
              <!--                                </label>-->
              <!--                            </div>-->

              <div class="col-sm-8" id="shape-section" style="display: block">
                Image: (max size 8MB)
                <br/>

                <div id="shape-upload-container">
                                <span id="provided-shape">
                                </span>
                  <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                  <input id="frontend-button" type="button" value="Select File to Upload"
                         class="button" style="position: relative; z-index: 1;">
                  <img id="frontend-image" src="<?php echo $attachment; ?>"/>
                  <input type="text" id="attachment" name="attachment" style="visibility: hidden;"
                         value="<?php echo $attachment; ?>"/>
                </div>
                <p>NOTE: The minimum size of the batten can not be less than 3mm for Green and Ecowood
                  and 5mm for all others.</p>
              </div>
            </div>

            <div class="row" style="margin-top:1em;">

              <div class="col-sm-2">
                Width (mm):
                <input class="required" id="property_width" name="property_width" type="number"
                       value="<?php echo get_post_meta($product_id, 'property_width', true); ?>"/>
              </div>
              <div class="col-sm-2">
                Height (mm):
                <input class="required" id="property_height" name="property_height" type="number"
                       value="<?php echo get_post_meta($product_id, 'property_height', true); ?>"
									<?php if ($batten_type == 'standard') {
										echo 'readonly';
									} ?>
                />
              </div>
              <div class="col-sm-2">
                Depth (mm):
                <input class="required" id="property_depth" name="property_depth" type="number"
                       value="<?php echo get_post_meta($product_id, 'property_depth', true); ?>"
									<?php if ($batten_type == 'standard') {
										echo 'readonly';
									} ?>
                />
              </div>
              <div class="col-sm-2">
                Total (cb/m):
                  <input name="property_total" type="text" style="pointer-events: none;"
                         value="<?php echo get_post_meta($product_id, 'property_volume', true); ?>"
                />
              </div>
            </div>

            <div class="row" style="margin-top:1em;">
              <div class="col-sm-2" style="display:none;">
                Room:
                <br/>
                <input class="property-select" id="property_room" name="property_room" type="text"
                       value="94"/>
              </div>
              <div class="col-sm-3">
                Material:
                <br/>
                <input class="property-select required" id="property_material" name="property_material"
                       type="hidden"
                       value="<?php echo get_post_meta($product_id, 'property_material', true); ?>"/>
                <input id="product_id" name="product_id" type="hidden" value=""/>
              </div>
              <div class="col-sm-2" id="room-other" style="display: block">
                Room name:
                <br/>
                <input id="property_room_other" name="property_room_other" style="height: 30px"
                       type="text"
                       value="<?php echo get_post_meta($product_id, 'property_room_other', true); ?>"/>
              </div>
              <div class="col-sm-2">
                Colour:
                <br/>
                <input class="property-select" id="property_shuttercolour" name="property_shuttercolour"
                       type="hidden"
                       value="<?php echo get_post_meta($product_id, 'property_shuttercolour', true); ?>"
                />
              </div>
              <div class="col-sm-2" id="colour-other" style="display: none">
                Other Colour:
                <br/>
                <input id="property_shuttercolour_other" name="property_shuttercolour_other"
                       style="height: 30px" type="text"
                       value="<?php echo get_post_meta($product_id, 'property_shuttercolour_other', true); ?>"/>
              </div>
              <div class="col-sm-2">
                Quantity:
                <input id="quantity" class="input-text qty text" min="1" max="" name="quantity"
                       value="<?php echo get_post_meta($product_id, 'quantity', true); ?>" title="Qty"
                       size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="" type="number">
              </div>
            </div>
            <hr/>
            <div class="row" style="">
              <input id="page_title" name="page_title" type="hidden"
                     value="<?php echo get_the_title(); ?>"/>
              <div class="col-lg-4 col-md-6">
                Comments:
                <br/>
                <textarea id="comments_customer" name="comments_customer" rows="5"
                          style="width: 100%"><?php echo get_post_meta($product_id, 'comments_customer', true); ?></textarea>
                <hr/>
              </div>
              <div class="col-sm-2">
                <button class="btn btn-primary update-batten">Update to Quote
                  <i class="fa fa-chevron-right"></i>
                </button>
              </div>
            </div>
        </form>
        <style type="text/css">
            #choose-product-shutter label > input {
                /* HIDE RADIO */
                display: none;
            }

            #choose-product-shutter label {
                display: block;
                float: left;
                width: 100px;
                text-align: center;
                border: 2px solid gray;
                margin-right: 1em;
                font-size: 10px
            }

            #choose-product-shutter label > input + img {
                /* IMAGE STYLES */
                cursor: pointer;
                border: 4px solid transparent;
            }

            .extra-column input {
                width: 4em;
            }

            div.error-field {
                display: block
            }

            .error-field,
            input.error-field {
                border: 2px solid red;
            }

            .error-text {
                color: red;
                font-weight: bold;
            }

            #choose-product-shutter label > input:checked + img {
                /* (CHECKED) IMAGE STYLES */
                border: 4px solid #438EB9;
            }

            .select2-result-label img,
            .select2-container img {
                display: inline;
            }

            .select2-container#s2id_property_style .select2-choice,
            #s2id_property_style .select2-result {
                height: 57px;
            }

            input.property-select {
                display: block !important;
            }

        </style>

        <!-- <div class="show-prod-info">

				</div> -->

      </div>
    </div>
  </div>
</div>
</div>
</div>

<script>

    jQuery(document).ready(function () {
        jQuery('input[name="batten_type"]').click(function (e) {
            var type = jQuery(this).val();
            if (type === 'standard') {
                console.log('standard');
                jQuery('input#property_height').attr("readonly", true);
                jQuery('input#property_height').val(25);
                jQuery('input#property_depth').attr("readonly", true);
                jQuery('input#property_depth').val(5);
                jQuery('#shape-section').hide();


                var property_width = jQuery('input#property_width').val();

                var total = (parseFloat(property_width) / parseFloat(1000)) * ((parseFloat(25) / parseFloat(1000))) * ((parseFloat(5) / parseFloat(1000)));
                total = total.toFixed(8);
                jQuery('input#property_total').val(parseFloat(total));

            } else if (type === 'custom') {
                console.log('custom');
                jQuery('input#property_height').removeAttr("readonly");
                jQuery('input#property_height').val('');
                jQuery('input#property_depth').removeAttr("readonly");
                jQuery('input#property_depth').val('');
                jQuery('#shape-section').show();

                var height = jQuery('input#property_height').val();
                var width = jQuery('input#property_width').val();
                var depth = jQuery('input#property_depth').val();

                if (parseFloat(height) > parseFloat(width) && parseFloat(height) > parseFloat(depth)) {
                    console.log('height');
                    if (((parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000))) < 0.0006) {
                        var total = (parseFloat(height) / parseFloat(1000)) * parseFloat(0.0006);
                    } else {
                        var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                    }
                } else if (parseFloat(width) > parseFloat(height) && parseFloat(width) > parseFloat(depth)) {
                    console.log('width');
                    if (((parseFloat(height) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000))) < 0.0006) {
                        var total = (parseFloat(width) / parseFloat(1000)) * parseFloat(0.0006);
                    } else {
                        var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                    }
                } else if (parseFloat(depth) > parseFloat(height) && parseFloat(depth) > parseFloat(width)) {
                    console.log('depth');
                    if (((parseFloat(width) / parseFloat(1000)) * (parseFloat(height) / parseFloat(1000))) < 0.0006) {
                        var total = (parseFloat(depth) / parseFloat(1000)) * parseFloat(0.0006);
                    } else {
                        var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                    }
                }

                // var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                total = total.toFixed(8);
                jQuery('input#property_total').val(parseFloat(total));
            }
        });
    });

</script>

<script>

    // Batten update form

    jQuery('#add-product-single-form .btn.btn-primary.update-batten').on('click', function (e) {
        e.preventDefault();
        resetErrors();

        var error = 0;
        var id_material = jQuery("input#property_material").val();
        //biowood-138, basswood-139, earth-187, ecowood-188, green-137
        if ((parseFloat(jQuery("#property_height").val()) < 5 || jQuery("#property_height").val() == "") && (id_material == 138 || id_material == 139 || id_material == 187)) {
            error_text = 'The minimum size of the batten can not be less than 5mm.';
            addError("property_height", error_text);
            modalShowError(error_text);

            error++;
            console.log('step 1', jQuery("#property_height").val());

        } else if ((parseFloat(jQuery("#property_width").val()) < 5 || jQuery("#property_width").val() == "") && (id_material == 138 || id_material == 139 || id_material == 187)) {
            error_text = 'The minimum size of the batten can not be less than 5mm.';
            addError("property_width", error_text);
            modalShowError(error_text);

            error++;
            console.log('step 1', jQuery("#property_width").val());

        } else if ((parseFloat(jQuery("#property_depth").val()) < 5 || jQuery("#property_depth").val() == "") && (id_material == 138 || id_material == 139 || id_material == 187)) {
            error_text = 'The minimum size of the batten can not be less than 5mm.';
            addError("property_depth", error_text);
            modalShowError(error_text);
            console.log('step 1', jQuery("#property_depth").val());

            error++;
            cosnsole.log('step 1', jQuery("#property_depth").val());

        } else if ((parseFloat(jQuery("#property_height").val()) < 3 || jQuery("#property_height").val() == "") && (id_material == 188 || id_material == 137)) {
            error_text = 'The minimum size of the batten can not be less than 3mm.';
            addError("property_height", error_text);
            modalShowError(error_text);

            error++;
            console.log('step 2', jQuery("#property_height").val());

        } else if ((parseFloat(jQuery("#property_width").val()) < 3 || jQuery("#property_width").val() == "") && (id_material == 188 || id_material == 137)) {
            error_text = 'The minimum size of the batten can not be less than 3mm.';
            addError("property_width", error_text);
            modalShowError(error_text);

            error++;
            console.log('step 2', jQuery("#property_width").val());

        } else if ((Number(jQuery("#property_depth").val()) < 3 || jQuery("#property_depth").val() == "") && (id_material == 188 || id_material == 137)) {
            error_text = 'The minimum size of the batten can not be less than 3mm.';
            addError("property_depth", error_text);
            modalShowError(error_text);
            console.log('step 2', jQuery("#property_depth").val());

            error++;
            console.log('step 2', jQuery("#property_depth").val());

        }
        console.log('error test ', error);

        if (error === 0) {
            var formser = jQuery('#add-product-single-form').serialize();
            //var svg = jQuery('#canvas_container1').html();

            console.log(formser);
            //alert(formser);


            $.ajax({
                method: "POST",
                url: "/wp-content/plugins/shutter-module/ajax/ajax-batten-update.php",
                data: {
                    prod: formser
                }
            })
                .done(function (data) {
                    console.log(data);
                    alert('Batten Updated!');
                    //jQuery('.show-prod-info').html(data);
                    setTimeout(function () {
                        window.location.replace("/checkout");
                    }, 500);
                });
        }
    });

</script>
