<?php
$user_id = get_current_user_id();
$meta_key = 'wc_multiple_shipping_addresses';

$edit_customer = ($_GET['order_edit_customer'] == 'editable') ? true : false;
$order_edit = (!empty($_GET['order_id'])) ? $_GET['order_id'] / 1498765 / 33 : '';

$clone_id = !empty($_GET['clone']) ? base64_decode($_GET['clone']) : '';

$dealer_id = get_user_meta($user_id, 'company_parent', true);

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

if (is_array($carts_sort)) {
	foreach ($carts_sort as $key => $carts) {

		if ($userialize_data['customer_id'] == $key) {

			$cart_name = $carts['name'];
		}
	}
}

?>

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->

<div class="main-content">
    <div class="page-content" style="background-color: #F7F7F7">
        <div class="page-content-area">

            <div class="page-header">
                <h1>Add Batten</h1>
            </div>

            <div id="forms-container">
                <div class="form-wrapper" id="form-1">

                    <div class="row">
                        <!--                        <form id="form-batten" class="form-instance" accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"-->
                        <!--                              enctype="multipart/form-data"-->
                        <!--                              method="post">-->
                        <form accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                              enctype="multipart/form-data"
                              id="add-product-single-form" method="post">
                            <div class="col-xs-12">

                                <div class="row">
                                    <div class="col-sm-12">
										<?php
										if (empty($_GET['order_id'])) { ?>
                                            <strong>Order Reference:</strong> <?php echo $cart_name;
										} ?>
                                        <br/>
                                        <br/>
                                        <input type="hidden" name="customer_id" value="<?php echo $user_id; ?>">
                                        <input type="hidden" name="dealer_id" value="<?php echo $dealer_id; ?>">
                                        <input type="hidden" name="edit_customer" value="<?php echo $edit_customer; ?>">
                                        <input type="hidden" name="order_edit" value="<?php echo $order_edit; ?>">
                                        <input type="hidden" name="sections" value="1">
                                    </div>
                                </div>

                                <div class="row" style="margin-top:1em;">
                                    <div class="col-sm-2">
                                        <div id="choose-product-shutter">
                                            <label>
                                                Batten
                                                <br/>
                                                <input type="radio" name="product_id" value="40"/>
                                                <img src="/wp-content/plugins/shutter-module/imgs/thumb_batten.jpg">
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-8" id="shape-section" style="display: block">
                                        Image: (max size 8MB)
                                        <br/>

                                        <div id="shape-upload-container">
                                <span id="provided-shape">
                                </span>
                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                            <input id="frontend-button" type="button" value="Select File to Upload"
                                                   class="button" style="position: relative; z-index: 1;">
                                            <img id="frontend-image" src=""/>
                                            <input type="text" name="attachment" style="visibility: hidden;"
                                                   value=""/>
                                        </div>
                                        <p>NOTE: The minimum size of the batten can not be less than 3mm for Green and Ecowood and 5mm for all others.</p>
                                    </div>
                                </div>

                                <div class="row" style="margin-top:1em;">

                                    <div class="col-sm-2">
                                        Width (mm):
                                        <input class="required" name="property_width" type="number"
                                               value=""/>
                                    </div>
                                    <div class="col-sm-2">
                                        Height (mm):
                                        <input class="required" name="property_height" type="number"
                                               value=""/>
                                    </div>
                                    <div class="col-sm-2">
                                        Depth (mm):
                                        <input class="required" name="property_depth" type="number"
                                               value=""/>
                                    </div>
                                    <div class="col-sm-2">
                                        Total (cb/m):
                                        <input name="property_total" type="text" value="" style="pointer-events: none;" />
                                    </div>
                                </div>

                                <div class="row" style="margin-top:1em;">
                                    <div class="col-sm-2" style="display:none;">
                                        Room:
                                        <br/>
                                        <input class="property-select" name="property_room" type="text"
                                               value="94"/>
                                    </div>
                                    <div class="col-sm-3 material-section">
                                        Material:
                                        <br/>
                                        <input id="property_material" class="property-select required" name="property_material"
                                               type="hidden" value="139"/>
                                        <input name="product_id" type="hidden" value=""/>
                                    </div>
                                    <div class="col-sm-2" style="display: block">
                                        Room name:
                                        <br/>
                                        <input name="property_room_other" style="height: 30px"
                                               type="text" value=""/>
                                    </div>
                                    <div class="col-sm-2 shuttercolour-section">
                                        Colour:
                                        <br/>
                                        <input class="property-select" id="property_shuttercolour" name="property_shuttercolour" type="hidden"
                                               value=""/>
                                    </div>
                                    <div class="col-sm-2" style="display: none">
                                        Other Colour:
                                        <br/>
                                        <input name="property_shuttercolour_other"
                                               style="height: 30px" type="text"
                                               value=""/>
                                    </div>
                                    <div class="col-sm-2">
                                        Quantity:
                                        <input class="input-text qty text" min="1" max="" name="quantity"
                                               value="1" title="Qty"
                                               size="4" pattern="[0-9]*" inputmode="numeric" aria-labelledby="" type="number">
                                    </div>
                                </div>
                                <hr/>
                                <div class="row" style="">
                                    <input name="page_title" type="hidden"
                                           value="<?php echo get_the_title(); ?>"/>
                                    <div class="col-lg-4 col-md-6">
                                        Comments:
                                        <br/>
                                        <textarea name="comments_customer" rows="5"
                                                  style="width: 100%"></textarea>
                                        <hr/>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-10">

                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary submit-batten">Add to Quote
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- <div class="show-prod-info">
						</div> -->
            </div>
<!--            <div class="row">-->
<!--                <div class="col-sm-10">-->
<!--                              <button id="add-batten">Add new batten</button>-->
<!--                </div>-->
<!--                <div class="col-sm-2">-->
<!--                                        <button class="btn btn-primary submit-batten-multiple">Add to Quote-->
<!--                                            <i class="fa fa-chevron-right"></i>-->
<!--                                        </button>-->
<!--                </div>-->
<!--            </div>-->

            <style type="text/css">
                form input[type=number] {
                    border: 1px solid rgba(0, 0, 0, 0.1) !important;
                }

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

                /*input.property-select {*/
                /*    display: block !important;*/
                /*}*/

            </style>

            <script>

                // jQuery(document).ready(function () {
                //     // Run when the 'Add Batten' button is clicked
                //     jQuery('#add-batten').click(function () {
                //         var idNumber = jQuery('.form-wrapper').length + 1;
                //         var formClone = jQuery('.form-wrapper').first().clone();
                //         var newFormId = 'form-' + idNumber;
                //         console.log('#form-' + idNumber);
                //         formClone.attr('id', newFormId);
                //
                //
                //         // Find all inputs and update their id
                //         formClone.find("input, select, textarea").each(function () {
                //             var input = jQuery(this);
                //             var id = input.attr('id');
                //             var name = input.attr('name');
                //
                //             if (id) {
                //                 // If the id ends with a number, replace it
                //                 var updatedId = id.replace(/_\d+$/, '_' + idNumber);
                //                 input.attr('id', updatedId);
                //             }
                //
                //             // Append the number to name if it's set
                //             if (name) {
                //                 // If the name ends with a number, replace it
                //                 var updatedName = name.replace(/_\d+$/, '_' + idNumber);
                //                 input.attr('name', updatedName);
                //             }
                //         });
                //
                //         formClone.find("input[type='number']:not([name^='property_material_']):not([name^='property_shuttercolour_']), input[type='text']:not([name^='property_material_']):not([name^='property_shuttercolour_']):not([name^='property_shuttercolour_other_']), select, textarea").val("");
                //
                //         jQuery('#forms-container').append(formClone);
                //         jQuery('input[name="sections"]').val(idNumber);
                //         jQuery('#form-' + idNumber + ' .shuttercolour-section, #form-' + idNumber + ' .material-section').removeAttr("id").addClass("hide hidden");
                //
                //     });
                // });

                // jQuery(document).ready(function() {
                //     jQuery('#add-batten').click(function() {
                //         var currentForm = jQuery(this).closest('.form-instance'); // Get the form that contains the clicked button
                //         var clonedForm = currentForm.clone(true); // Clone the current form
                //         clonedForm.find("input[type='number'], input[type='text']").val(""); // Clear the input fields in the cloned form
                //         clonedForm.insertAfter(currentForm); // Add the cloned form after the current form
                //     });
                // });

                // jQuery(document).ready(function () {
                //     jQuery('input[name="batten_type"]').click(function (e) {
                //         var type = jQuery(this).val();
                //         if (type === 'standard') {
                //             $(".form-wrapper").each(function (index) {
                //                 console.log('standard');
                //                 jQuery('input#property_height').attr("readonly", true);
                //                 jQuery('input#property_height').val(25);
                //                 jQuery('input#property_depth').attr("readonly", true);
                //                 jQuery('input#property_depth').val(5);
                //                 jQuery('#shape-section-' + (index + 1)).hide();
                //
                //
                //                 var property_width = jQuery('input#property_width').val();
                //
                //                 var total = (parseFloat(property_width) / parseFloat(1000)) * ((parseFloat(25) / parseFloat(1000))) * ((parseFloat(5) / parseFloat(1000)));
                //                 total = total.toFixed(8);
                //                 console.log('total:' + total);
                //                 jQuery('input#property_total').val(parseFloat(total));
                //             });
                //
                //         } else if (type === 'custom') {
                //             console.log('custom');
                //             jQuery('input#property_height').removeAttr("readonly");
                //             jQuery('input#property_height').val('');
                //             jQuery('input#property_depth').removeAttr("readonly");
                //             jQuery('input#property_depth').val('');
                //             jQuery('#shape-section').show();
                //
                //             var height = jQuery('input#property_height').val();
                //             var width = jQuery('input#property_width').val();
                //             var depth = jQuery('input#property_depth').val();
                //
                //             if (parseFloat(height) > parseFloat(width) && parseFloat(height) > parseFloat(depth)) {
                //                 console.log('height');
                //                 if (((parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000))) < 0.0006) {
                //                     var total = (parseFloat(height) / parseFloat(1000)) * parseFloat(0.0006);
                //                 } else {
                //                     var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                //                 }
                //             } else if (parseFloat(width) > parseFloat(height) && parseFloat(width) > parseFloat(depth)) {
                //                 console.log('width');
                //                 if (((parseFloat(height) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000))) < 0.0006) {
                //                     var total = (parseFloat(width) / parseFloat(1000)) * parseFloat(0.0006);
                //                 } else {
                //                     var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                //                 }
                //             } else if (parseFloat(depth) > parseFloat(height) && parseFloat(depth) > parseFloat(width)) {
                //                 console.log('depth');
                //                 if (((parseFloat(width) / parseFloat(1000)) * (parseFloat(height) / parseFloat(1000))) < 0.0006) {
                //                     var total = (parseFloat(depth) / parseFloat(1000)) * parseFloat(0.0006);
                //                 } else {
                //                     var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                //                 }
                //             }
                //
                //             // var total = (parseFloat(height) / parseFloat(1000)) * (parseFloat(width) / parseFloat(1000)) * (parseFloat(depth) / parseFloat(1000));
                //             if (total) {
                //                 total = total.toFixed(8);
                //             }
                //             jQuery('input#property_total').val(parseFloat(total));
                //         }
                //     });
                //
                //
                //     jQuery("#property_material").on('change', function () {
                //         var id_material = jQuery(this).val();
                //         jQuery('input[name^="property_material"]').val(id_material);
                //     });
                //     jQuery("#property_shuttercolour").on('change', function () {
                //         var id_shuttercolour = jQuery(this).val();
                //         jQuery('input[name^="property_shuttercolour"]').val(id_shuttercolour);
                //         jQuery('input[name^="property_shuttercolour_other"]').val('');
                //     });
                //
                //     var sectionsForm = [];
                //
                //     // Batten submit form
                //
                //     jQuery('button.btn.btn-primary.submit-batten-multiple').on('click', function (e) {
                //         e.preventDefault();
                //         var sections = jQuery('input[name="sections"]').val();
                //
                //         var errors = 0;
                //
                //         // // Loop over each set of inputs
                //         // for (var index = 1; index <= sections; index++) {
                //         resetErrors();
                //         console.log('index: ' + index);
                //
                //
                //         var property_width = jQuery('input[name="property_height"]').val();
                //         var property_height = jQuery('input[name="property_width"]').val();
                //         var property_depth = jQuery('input[name="property_depth"]').val();
                //         var property_room_other = jQuery('input[name="property_room_other"]').val();
                //         var id_material = jQuery("#property_material").val();
                //         var id_shuttercolour = jQuery("#property_shuttercolour").val();
                //         jQuery('input[name="property_material"]').val(id_material);
                //         jQuery('input[name="property_shuttercolour"]').val(id_shuttercolour);
                //         console.log('id_material: ' + id_material);
                //
                //         //biowood-138, supreme-139, earth-187, ecowood-188, green-137
                //         if (parseFloat(property_depth) < 3 && (id_material == 188 || id_material == 137)) {
                //             error_text = 'The minimum size of the batten can not be less than 3mm.';
                //             addError("property_depth", error_text);
                //             modalShowError(error_text);
                //
                //             errors++;
                //
                //         } else if (parseFloat(property_height) < 3 && (id_material == 188 || id_material == 137)) {
                //             error_text = 'The minimum size of the batten can not be less than 3mm.';
                //             addError("property_height", error_text);
                //             modalShowError(error_text);
                //
                //             errors++;
                //
                //
                //         } else if (parseFloat(property_width) < 3 && (id_material == 188 || id_material == 137)) {
                //             error_text = 'The minimum size of the batten can not be less than 3mm.';
                //             addError("property_width", error_text);
                //             modalShowError(error_text);
                //
                //             errors++;
                //
                //         } else if (parseFloat(property_height) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
                //             error_text = 'The minimum size of the batten can not be less than 5mm.';
                //             addError("property_height", error_text);
                //             modalShowError(error_text);
                //             console.log('property_height The minimum size of the batten can not be less than 5mm.');
                //
                //             errors++;
                //
                //         } else if (parseFloat(property_width) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
                //             error_text = 'The minimum size of the batten can not be less than 5mm.';
                //             addError("property_width", error_text);
                //             modalShowError(error_text);
                //             console.log('property_width The minimum size of the batten can not be less than 5mm.');
                //
                //             errors++;
                //
                //         } else if (parseFloat(property_depth) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
                //             error_text = 'The minimum size of the batten can not be less than 5mm.';
                //             addError("property_depth", error_text);
                //             modalShowError(error_text);
                //             console.log('property_depth The minimum size of the batten can not be less than 5mm.');
                //
                //             errors++;
                //
                //         } else if (property_width === '' || property_height === '' || property_depth === '' || property_room_other === '') {
                //             errors++;
                //             alert('Please compplete all the properties fields');
                //
                //         } else {
                //
                //             // Now serialize the form
                //             var formser = jQuery('#form-batten').serialize();
                //             //var svg = jQuery('#canvas_container1').html();
                //
                //             console.log('formser:', formser);
                //             sectionsForm.push(formser);
                //             console.log('sectionsForm:', sectionsForm);
                //
                //             console.log('errors ', errors);
                //
                //             // alert(formser);
                //             // console.log('submit 4');
                //         }
                //         // }
                //         console.log('errors ', errors);
                //
                //         if (errors === 0) {
                //             jQuery.ajax({
                //                 method: "POST",
                //                 url: '/wp-admin/admin-ajax.php',
                //                 data: {
                //                     action: 'create_product_and_add',
                //                     prod: formser
                //                 }
                //             })
                //                 .done(function (data) {
                //
                //                     console.log(data);
                //                     alert('Batten added!');
                //                     jQuery('.show-prod-info').html(data);
                //
                //                     var edit_customer = jQuery('input[name="edit_customer"]').val();
                //                     var order_edit = jQuery('input[name="order_edit"]').val();
                //                     setTimeout(function () {
                //                         if (edit_customer.length !== 0 && order_edit.length !== 0) {
                //                             window.location.replace(document.referrer);
                //                         } else {
                //                             window.location.replace("/checkout");
                //                         }
                //                     }, 200);
                //                 });
                //         }
                //     });
                //
                // });

            </script>

        </div>
    </div>
</div>
