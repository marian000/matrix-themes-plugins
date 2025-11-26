<?php
$user_id = get_current_user_id();
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

?>

<!-- Latest compiled and minified CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->


<div class="main-content">
    <div class="page-content" style="background-color: #F7F7F7">
        <div class="page-content-area">
            <div class="page-header">
                <h1>Add Shutter - <?php echo $cart_name; ?></h1>
            </div>

            <div class="row">
                <form edit="no" accept-charset="UTF-8" action="/order_products/add_single_product" data-type="json"
                    enctype="multipart/form-data" id="add-product-single-form" method="post">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-sm-9">
                                <strong>Order Reference:</strong> <?php echo $cart_name; ?>
                                <br />
                                <br />
                            </div>
                            <div class="col-sm-3 pull-right">
                                Total Square Meters&nbsp;
                                <input class="input-small" id="property_total" name="property_total" type="text"
                                    value="" />
                            </div>
                        </div>

                        <div style="display:none">

                        </div>
                        <input id="order_product_id" name="order_product_id" type="hidden" value="" />

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingStyle">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseStyle" aria-expanded="true"
                                                    aria-controls="collapseStyle">
                                                    Shutters Design
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseStyle" class="panel-collapse collapse in" role="tabpanel"
                                            aria-labelledby="headingStyle">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3" style="display:none;">
                                                        Room:
                                                        <br />
                                                        <input class="property-select" id="property_room"
                                                            name="property_room" type="text" value="94" />
                                                    </div>
                                                    <div class="col-sm-3" id="room-other" style="display: block">
                                                        Room Name:
                                                        <br />
                                                        <input required class="input-medium required"
                                                            id="property_room_other" name="property_room_other"
                                                            style="height: 30px" type="text" value="">
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Material:
                                                        <br />
                                                        <input class="property-select required" id="property_material"
                                                            name="property_material" type="text" value="138" />
                                                        <input id="product_id" name="product_id" type="hidden"
                                                            value="" />
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-12">
                                                        Installation Style:
                                                        <div id="choose-style">
                                                            <label>
                                                                <br /> Full Height
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="fullheight" data-title="Full Height"
                                                                    value="29" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Full-Height.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="tot" data-title="Tier-on-Tier"
                                                                    value="31" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="cafe" data-title="Café Style"
                                                                    value="30" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Cafe-Style.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Bay Window
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="bay" data-title="Bay Window"
                                                                    value="32" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Bay Window<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="bay-tot"
                                                                    data-title="Bay Window Tier-on-Tier" value="146" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Bay Window<br />Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="cafe-bay"
                                                                    data-title="Café Style Bay Window" value="225" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bay-Window-Cafe-Style.png">
                                                            </label>
                                                            <label>
                                                                <br /> Solid Flat Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-flat" data-title="Solid Flat Panel"
                                                                    value="221" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Flat<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-flat-tot"
                                                                    data-title="Solid Flat Tier-on-Tier" value="227" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Flat-Panel-Tier-On-Tier.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised"
                                                                    data-title="Solid Raised Panel" value="222" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Raised-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Tier-on-Tier
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised-tot"
                                                                    data-title="Solid Raised Tier-on-Tier"
                                                                    value="228" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Rised-Panel-Tier-On-Tier.png">
                                                            </label>
                                                            <label>
                                                                <br /> Solid Combi Panel
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-combi" data-title="Combi Panel"
                                                                    value="229" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Combi-Panel.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                Solid Raised<br />Café Style
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="solid-raised-cafe-style"
                                                                    data-title="Solid Raised Café Style" value="226" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Solid-Raised-Panel-Cafe-Style.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Arched Shaped
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="shaped" data-title="Arched Shaped"
                                                                    value="36" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Arched.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Special Shaped
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="shaped" data-title="Special Shaped"
                                                                    value="33" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Special-Shape.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> French Door Cut
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="french" data-title="French Door Cut"
                                                                    value="34" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/French-Door-Cut.png">
                                                            </label>
                                                            <label style="display:none;">
                                                                <br /> Tracked Bifold
                                                                <br />
                                                                <input type="radio" name="property_style"
                                                                    data-code="tracked" data-title="Tracked"
                                                                    value="35" />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/BiFold-Tracked.png">
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-5" id="shape-section" style="display: block">
                                                        Shape: (max size 8MB)
                                                        <br />

                                                        <div id="shape-upload-container">
                                                            <span id="provided-shape">
                                                            </span>
                                                            <!-- <input id="attachment" name="wp_custom_attachment" type="file" /> -->
                                                            <input id="frontend-button" type="button"
                                                                value="Select File to Upload" class="button"
                                                                style="position: relative; z-index: 1;">
                                                            <img id="frontend-image" src="" />
                                                            <input type="text" id="attachment" name="attachment"
                                                                style="visibility: hidden;" value="" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;margin-bottom: 20px;">
                                                    <div class="col-sm-2">
                                                        Width (mm):
                                                        <br />
                                                        <input class="required number input-medium" id="property_width"
                                                            name="property_width" type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-2">
                                                        Height (mm):
                                                        <br />
                                                        <input class="required number input-medium" id="property_height"
                                                            name="property_height" type="text" value="" />
                                                    </div>

                                                    <div class="col-sm-2" id="midrail-height">
                                                        Midrail Height (mm):
                                                        <br />
                                                        <input class=" number input-medium" id="property_midrailheight"
                                                            name="property_midrailheight" type="text" value="" />
                                                    </div>

                                                    <div class="col-sm-2" id="solid-panel-height" style="display:none;">
                                                        Solid Panel Height (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_solidpanelheight"
                                                            name="property_solidpanelheight" type="text" value="" />
                                                    </div>

                                                    <div class="col-sm-2" id="midrail-height2">
                                                        Midrail Height 2 (mm):
                                                        <br />
                                                        <input class=" number input-medium" id="property_midrailheight2"
                                                            name="property_midrailheight2" type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider">
                                                        Hidden Divider 1 (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_midraildivider1"
                                                            name="property_midraildivider1" type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-divider2">
                                                        Hidden Divider 2 (mm):
                                                        <br />
                                                        <input class=" number input-medium"
                                                            id="property_midraildivider2"
                                                            name="property_midraildivider2" type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-2" id="midrail-position-critical">
                                                        Position is Critical:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"
                                                                    title="Please note, if mid rail is selected as critical, top and bottom rails will be different sizes. If not, they will be manufactured evenly but the mid rail location may change by +/- 25.4mm"
                                                                    data-toggle="tooltip" data-placement="top">?</span>
                                                                <input class="property-select"
                                                                    id="property_midrailpositioncritical"
                                                                    name="property_midrailpositioncritical" type="text"
                                                                    value="170" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-2 tot-height" style="display: none">
                                                        T-o-T Height (mm):
                                                        <br />
                                                        <input class="required number input-medium"
                                                            id="property_totheight" name="property_totheight"
                                                            type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-2 tot-height horizontal-t-post"
                                                        style="display: none" ata-toggle="tooltip"
                                                        title="The horizontal T Post will be apllied at T-o-T height">
                                                        Horizontal T Post
                                                        <br />
                                                        <input id="property_horizontaltpost"
                                                            name="property_horizontaltpost" type="checkbox"
                                                            value="Yes" />
                                                    </div>
                                                    <div class="col-sm-2">
                                                        Louvre Size:
                                                        <br />
                                                        <input class="property-select required" id="property_bladesize"
                                                            name="property_bladesize" type="text" value="" />
                                                    </div>
                                                    <div id="trackedtype" class="col-sm-2" style="display: none">
                                                        Track Installation type:
                                                        <br />
                                                        <input name="property_trackedtype" type="radio"
                                                            value="inside mount" />
                                                        Inside mount
                                                        <br />
                                                        <input name="property_trackedtype" type="radio"
                                                            value="outside mount" />
                                                        outside mount
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-3 property_fit top10">
                                                        Fit:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"
                                                                    title="IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. "
                                                                    data-toggle="tooltip" data-placement="top">?</span>
                                                                <input class="property-select" id="property_fit"
                                                                    name="property_fit" type="text" value="outside" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Frames &amp; Stiles Design
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
                                                                <br /> 4008A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="4008A" value="307"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '307'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008A.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4008B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008B" value="310"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '310'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4008C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008C" value="313"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '313'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008C.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4008T
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4008T" value="353"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '353'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4008T.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4028B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4028B" value="333"
                                                                    <?php if ($property_frametype == '333') {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4028B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4007A" value="306"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '306'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007A.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L90" data-title="4007B" value="309"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '309'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4007C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L90" data-title="4007C" value="312"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '312'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4007C.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="4001A" value="305"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '305'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001A.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4001B" value="308"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '308'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001B.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4001C
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L70" data-title="4001C" value="311"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '311'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4001C.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4022B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="4022B" value="332"
                                                                    <?php if ($property_frametype == '332') {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4022B.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008K
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4008K" value="323"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '323'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008K.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008H
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4008H" value="321"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '321'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008H.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4028B
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4028B" value="318"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '318'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4028B.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008S
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F70" data-title="P4008S" value="330"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '330'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008S.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008T
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4008T" value="322"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '322'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008T.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4008W
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F90" data-title="P4008W" value="319"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '319'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4008W.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4007A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="F50" data-title="P4007A" value="331"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '331'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4007A.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4001N
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="P4001N" value="320"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '320'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4001N.png">
                                                            </label>
                                                            <label>
                                                                <br /> A4001A
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="A4001A" value="300"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '300'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4001A.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4013
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="P4013" value="325"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '325'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4013.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4033
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="P4033" value="327"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '327'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4033.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4043
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="P4043" value="328"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '328'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4043.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4073
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="P4073" value="324"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '324'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4073.png">
                                                            </label>
                                                            <label>
                                                                <br /> P4014
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z3CS" data-title="P4014" value="329"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '329'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/P4014.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4003
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z40" data-title="4003" value="314"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '314'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4003.png">
                                                            </label>
                                                            <label>
                                                                <br /> 4004
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z3CS" data-title="4004" value="315"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '315'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4004.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4009
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="D50" data-title="4009" value="142"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '142'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4009.png">
                                                            </label>

                                                            <label>Basswood
                                                                <br /> 4013
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="4013" value="316"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '316'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4013.png">
                                                            </label>
                                                            <label>Basswood
                                                                <br /> 4014
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="4014" value="317"
                                                                    <?php if (
                                                                                                                                                                $property_frametype == '317'
                                                                                                                                                            ) {
                                                                                                                                                                echo "checked";
                                                                                                                                                            } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/4014.png">
                                                            </label>

                                                            <label>
                                                                <br /> A4027
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="A4027" value="302"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '302'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4027.png">
                                                            </label>
                                                            <label>
                                                                <br /> A4028
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="Z50" data-title="A4028" value="301"
                                                                    <?php if (
                                                                                                                                                                    $property_frametype == '301'
                                                                                                                                                                ) {
                                                                                                                                                                    echo "checked";
                                                                                                                                                                } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/A4028.png">
                                                            </label>

                                                            <label style="display: block;">
                                                                <br>
                                                                Bottom M Track
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="Bottom M Track"
                                                                    value="144"
                                                                    <?php if (
                                                                                                                                                                            $property_frametype == '144'
                                                                                                                                                                        ) {
                                                                                                                                                                            echo "checked";
                                                                                                                                                                        } ?> />
                                                                <img
                                                                    src="/wp-content/plugins/shutter-module/imgs/Bottom_M_Track.png">
                                                            </label>

                                                            <label style="display: block;">
                                                                <br>
                                                                Track in Board
                                                                <input type="radio" name="property_frametype"
                                                                    data-code="L50" data-title="Track in Board"
                                                                    value="143"
                                                                    <?php if (
                                                                                                                                                                            $property_frametype == '143'
                                                                                                                                                                        ) {
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
                                                        <div class="pull-left" id="frame-left">
                                                            Frame Left
                                                            <i class="fa fa-arrow-left"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frameleft"
                                                                name="property_frameleft" type="text" value="70" />
                                                        </div>
                                                        <div class="pull-left" id="frame-right">
                                                            Frame Right
                                                            <i class="fa fa-arrow-right"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frameright"
                                                                name="property_frameright" type="text" value="75" />
                                                        </div>
                                                        <div class="pull-left" id="frame-top">
                                                            Frame Top
                                                            <i class="fa fa-arrow-up"></i>
                                                            <br />
                                                            <input class="property-select" id="property_frametop"
                                                                name="property_frametop" type="text" value="80" />
                                                        </div>
                                                        <div class="pull-left" id="frame-bottom">
                                                            Frame Bottom
                                                            <i class="fa fa-arrow-down"></i>
                                                            <br />
                                                            <input class="property-select" id="property_framebottom"
                                                                name="property_framebottom" type="text" value="85" />
                                                        </div>

                                                        <div class="pull-left">
                                                            <span id="add-buildout">
                                                                <br />
                                                                <button class="btn btn-info" style="padding: 0 12px">Add
                                                                    buildout</button>
                                                            </span>
                                                            <span id="buildout" style="display: none">
                                                                Buildout:
                                                                <br />
                                                                <input class="input-small" id="property_builtout"
                                                                    name="property_builtout"
                                                                    placeholder="Enter buildout" style="height: 30px;"
                                                                    type="text" value="" />
                                                                <button class="btn btn-danger btn-input"
                                                                    id="remove-buildout">
                                                                    <strong>X</strong>
                                                                </button>
                                                            </span>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12" id="stile-img-earth">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm A1002B (Std.beaded stile)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm A1002B (Std.beaded stile)"
                                                                    value="350"
                                                                    <?php if ($property_stile == '350') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/A1002B.png" />
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12" id="stile-img-ecowood">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm PVC-P1001B(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm PVC-P1001B(plain butt)" value="380"
                                                                    <?php if ($property_stile == '380') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm PVC-P1005B(plain D-mould)"
                                                                    value="381"
                                                                    <?php if ($property_stile == '381') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1003E(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm PVC-P1003E(plain rebate)"
                                                                    value="385"
                                                                    <?php if ($property_stile == '385') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm PVC-P1002B(beaded butt)"
                                                                    value="382"
                                                                    <?php if ($property_stile == '382') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm PVC-P1006B(beaded D-mould)"
                                                                    value="383"
                                                                    <?php if ($property_stile == '383') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1004E(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm PVC-P1004E(beaded rebate)"
                                                                    value="384"
                                                                    <?php if ($property_stile == '384') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1004B.png" />
                                                            </label>

                                                        </div>
                                                    </div>



                                                    <div class="col-sm-12" id="stile-img-supreme">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm 1001B( plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm 1001B( plain butt)" value="355"
                                                                    <?php if ($property_stile == '355') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm 1005B(plain D-mould)" value="356"
                                                                    <?php if ($property_stile == '356') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1003B(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm 1003B(plain rebate)" value="360"
                                                                    <?php if ($property_stile == '360') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm 1002B(beaded butt)" value="357"
                                                                    <?php if ($property_stile == '357') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm 1006B(beaded D-mould)" value="358"
                                                                    <?php if ($property_stile == '358') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm 1004B(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm 1004B(beaded rebate)" value="359"
                                                                    <?php if ($property_stile == '359') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1004B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1001A(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 38.1"
                                                                    data-title="35mm 1001A(plain butt)" value="361"
                                                                    <?php if ($property_stile == '361') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-7"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1001A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1005A(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 38.1"
                                                                    data-title="35mm 1005A(plain D-mould)" value="362"
                                                                    <?php if ($property_stile == '362') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-8"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1005A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1003A(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 38.1"
                                                                    data-title="35mm 1003A(plain rebate)" value="366"
                                                                    <?php if ($property_stile == '366') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-9"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1003A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1002A(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 38.1"
                                                                    data-title="35mm 1002A(beaded butt)" value="363"
                                                                    <?php if ($property_stile == '363') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-10"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1002A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1006A(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 38.1"
                                                                    data-title="35mm 1006A(beaded D-mould)" value="364"
                                                                    <?php if ($property_stile == '364') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-11"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1006A.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 35mm 1004A(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 38.1"
                                                                    data-title="35mm 1004A(beaded rebate)" value="365"
                                                                    <?php if ($property_stile == '365') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-12"
                                                                    src="/wp-content/plugins/shutter-module/imgs/1004A.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" id="stile-img-biowood">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 41mm T1001M(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="41mm T1001M(plain butt)" value="376"
                                                                    <?php if ($property_stile == '376') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1001M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 41mm T1005M(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="41mm T1005M(plain D-mould)" value="377"
                                                                    <?php if ($property_stile == '377') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1005M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 41mm T1003M(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="41mm T1003M(plain rebate)" value="378"
                                                                    <?php if ($property_stile == '378') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1003M.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1001K(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm T1001K(plain butt)" value="370"
                                                                    <?php if ($property_stile == '370') {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1001K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1005K(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm T1005K(plain D-mould)" value="371"
                                                                    <?php if ($property_stile == '371') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1005K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1003K(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm T1003K(plain rebate)" value="375"
                                                                    <?php if ($property_stile == '375') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1003K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1002K(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.81"
                                                                    data-title="51mm T1002K(beaded butt)" value="372"
                                                                    <?php if ($property_stile == '372') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1002K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1006K(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm T1006K(beaded D-mould)" value="373"
                                                                    <?php if ($property_stile == '373') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1006K.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm T1004K(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm T1004K(beaded rebate)" value="374"
                                                                    <?php if ($property_stile == '374') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/T1004K.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12" id="stile-img-green">
                                                        <div id="choose-frametype" class="col-sm-12 "
                                                            style="display: block;">

                                                            <label>
                                                                <br /> 51mm PVC-P1001B(plain butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="FS 50.8"
                                                                    data-title="51mm PVC-P1001B(plain butt)" value="380"
                                                                    <?php if ($property_stile == '380') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-1"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1001B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1005B(plain D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DFS 50.8"
                                                                    data-title="51mm PVC-P1005B(plain D-mould)"
                                                                    value="381"
                                                                    <?php if ($property_stile == '381') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-2"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1005B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1003E(plain rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RFS 50.8"
                                                                    data-title="51mm PVC-P1003E(plain rebate)"
                                                                    value="385"
                                                                    <?php if ($property_stile == '385') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-3"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1003B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1002B(beaded butt)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="BS 50.8"
                                                                    data-title="51mm PVC-P1002B(beaded butt)"
                                                                    value="382"
                                                                    <?php if ($property_stile == '382') {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-4"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1002B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1006B(beaded D-mould)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="DBS 50.8"
                                                                    data-title="51mm PVC-P1006B(beaded D-mould)"
                                                                    value="383"
                                                                    <?php if ($property_stile == '383') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-5"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1006B.png" />
                                                            </label>

                                                            <label>
                                                                <br /> 51mm PVC-P1004E(beaded rebate)
                                                                <input type="radio" name="property_stile"
                                                                    data-code="RBS 50.8"
                                                                    data-title="51mm PVC-P1004E(beaded rebate)"
                                                                    value="384"
                                                                    <?php if ($property_stile == '384') {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P1004B.png" />
                                                            </label>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- style select -->
                                                <!-- <div class="row">
                                                    <div class="col-sm-4">
                                                          <div class="">
                                                            Stile:
                                                            <br/>
                                                            <input class="property-select required" id="property_stile" name="property_stile" type="text" value="" />
                                                          </div>
                                                          <br/>
                                                    </div>
                                                  </div> -->

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingColour">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseColour" aria-expanded="true"
                                                    aria-controls="collapseColour">
                                                    Colour, Hinges, Control &amp; Configuration Design
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseColour" class="panel-collapse collapse " role="tabpanel"
                                            aria-labelledby="headingColour">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-3">
                                                        Hinge Colour:
                                                        <br />
                                                        <input class="property-select required"
                                                            id="property_hingecolour" name="property_hingecolour"
                                                            type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Shutter Colour:
                                                        <br />
                                                        <input class="property-select required"
                                                            id="property_shuttercolour" name="property_shuttercolour"
                                                            type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-3" id="colour-other" style="display: none">
                                                        Other Colour:
                                                        <br />
                                                        <input id="property_shuttercolour_other"
                                                            name="property_shuttercolour_other" style="height: 30px"
                                                            type="text" value="" />
                                                    </div>
                                                    <div class="col-sm-3">
                                                        Control Type:
                                                        <br />
                                                        <input class="property-select required"
                                                            id="property_controltype" name="property_controltype"
                                                            type="text" value="" />
                                                    </div>

                                                </div>
                                                <div class="row layout-row"
                                                    style="margin-top:1em; margin-bottom: 20px;">
                                                    <div class="col-sm-6" id="layoutcode-column">
                                                        Layout Configuration:
                                                        <br />
                                                        <div class="input-group-container">
                                                            <div class="input-group">
                                                                <span class="input-group-addon"
                                                                    title="Type in the layout code"
                                                                    data-toggle="tooltip" data-placement="top">?</span>
                                                                <input class="required input-medium"
                                                                    id="property_layoutcode"
                                                                    style="text-transform:uppercase"
                                                                    name="property_layoutcode" type="text" value="" />
                                                            </div>
                                                            <div class="note-ecowood-angle" style="display: none;">Note:
                                                                this material allows only 90 and 135 bay angles</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row extra-columns-row">
                                                    <div class="col-sm-12">

                                                    </div>
                                                </div>

                                                <div class="row extra-columns-buildout-row">
                                                    <div class="col-sm-12">

                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-sm-3 tpost-type" style="<?php if (get_post_meta($product_id, 'property_tposttype', true)) {
                                                                                                echo 'display: block';
                                                                                            } else {
                                                                                                echo 'display: none';
                                                                                            } ?>" data-toggle="tooltip"
                                                        title="">
                                                        T-Post Type:
                                                        <br />
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-earth"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> A7001 - Earth Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="A7001 - Earth Standard T-Post"
                                                                    value="442"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '442'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/A7001.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-ecowood"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> P7032 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-supreme"
                                                            style="display: none;">
                                                            <label>
                                                                <br /> 7001 - Supreme Std. T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7001 - Supreme Standard T-Post"
                                                                    value="438"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '438'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7001.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7201 - T-Post with insert
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7201 - T-Post with insert" value="439"
                                                                    <?php if (
                                                                                                                                                                                            $property_tposttype == '439'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7021.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7011 - Large T-Post<br />
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7011 - Large T-Post" value="441"
                                                                    <?php if (
                                                                                                                                                                                    $property_tposttype == '441'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7011.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-biowood"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> P7032 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7001 - Supreme Std. T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7001 - Supreme Standard T-Post"
                                                                    value="438"
                                                                    <?php if (
                                                                                                                                                                                                $property_tposttype == '438'
                                                                                                                                                                                            ) {
                                                                                                                                                                                                echo "checked";
                                                                                                                                                                                            } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7001.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7011 - Large T-Post<br />
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7011 - Large T-Post" value="441"
                                                                    <?php if (
                                                                                                                                                                                    $property_tposttype == '441'
                                                                                                                                                                                ) {
                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7011.png" />
                                                            </label>
                                                            <label>
                                                                <br /> 7201 - T-Post with insert
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="7201 - T-Post with insert" value="439"
                                                                    <?php if (
                                                                                                                                                                                            $property_tposttype == '439'
                                                                                                                                                                                        ) {
                                                                                                                                                                                            echo "checked";
                                                                                                                                                                                        } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/7021.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                    <div class="tpost-type" style="display: none;">
                                                        <div class="col-sm-12 tpost-img type-img-green"
                                                            style="display: none;">

                                                            <label>
                                                                <br /> P7032 - Standard T-Post
                                                                <input type="radio" name="property_tposttype"
                                                                    data-code="RBS 50.8"
                                                                    data-title="P7032 - Standard T-Post" value="437"
                                                                    <?php if (
                                                                                                                                                                                        $property_tposttype == '437'
                                                                                                                                                                                    ) {
                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                    } ?> />
                                                                <img class="stile-6"
                                                                    src="/wp-content/plugins/shutter-module/imgs/P7032.png" />
                                                            </label>
                                                            <!-- <img src="/wp-content/plugins/shutter-module/imgs/T-PostTypes.png" /> -->
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="spare-louvres">
                                                        <div>
                                                            <label>
                                                                <select id="property_sparelouvres"
                                                                    name="property_sparelouvres">
                                                                    <option value="No">No</option>
                                                                    <option value="Yes">Yes</option>
                                                                </select>
                                                                <!-- <input class="ace" id="property_sparelouvres" name="property_sparelouvres" type="checkbox" value="Yes" /> -->
                                                                <span class="lbl"> Include 2 x Spare Louvres (£6 +
                                                                    VAT)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="ring-pull" style="display:none">
                                                        <div>
                                                            <label>

                                                                <select id="property_ringpull" name="property_ringpull">
                                                                    <option value="No">No</option>
                                                                    <option value="Yes">Yes</option>

                                                                </select>
                                                                <span class="lbl"> Ring Pull</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
                                                                <input type="number" id="property_ringpull_volume"
                                                                    name="property_ringpull_volume" value="1">
                                                                <span class="lbl"> How Many? (please specify position on
                                                                    the comment field)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-top:1em;">
                                                    <div class="col-sm-4" id="locks" style="display:none">
                                                        <div>
                                                            <label>

                                                                <select id="property_locks" name="property_locks">
                                                                    <option value="No">No</option>
                                                                    <option value="Yes">Yes</option>

                                                                </select>
                                                                <span class="lbl"> Locks</span>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <label>
                                                                <input type="number" id="property_locks_volume"
                                                                    name="property_locks_volume" value="1">
                                                                <span class="lbl"> How Many? (please specify position on
                                                                    the comment field)</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <hr />
                                                        <button class="btn btn-info show-next-panel">
                                                            Next
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingLayout">
                                            <h4 class="panel-title">
                                                <a role="button" class="" data-toggle="collapse"
                                                    data-parent="#accordion" href="#collapseLayout" aria-expanded="true"
                                                    aria-controls="collapseLayout">
                                                    Confirm Drawing
                                                </a>
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
                                                            style="min-height: 500px;border: 1px solid #aaa;background-image: url('/wp-content/plugins/shutter-module/imgs/drawing_graph.png');">
                                                            <!-- <svg height="819" version="1.1" width="683" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                              style="overflow: hidden; position: relative; top: -0.333333px;">
                                                              <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.4</desc>
                                                              <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs>
                                                            </svg> -->
                                                        </div>
                                                        <br />
                                                        <button class="btn btn-info print-drawing" style="z-index: 10;">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                        <textarea id="shutter_svg" name="shutter_svg"
                                                            style="display:none"></textarea>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        Comments:
                                                        <br />
                                                        <textarea id="comments_customer" name="comments_customer"
                                                            rows="5" style="width: 100%"></textarea>
                                                        <hr />
                                                        <div id="nowarranty" style="display:none">
                                                            I accept that there is no warranty for this item
                                                            <br />
                                                            <input id="property_nowarranty" name="property_nowarranty"
                                                                type="checkbox" value="Yes" />

                                                        </div>
                                                        <div class="quantity">
                                                            Number of this
                                                            <input id="quantity" class="input-text qty text" min="1"
                                                                max="" name="quantity" value="1" title="Qty" size="4"
                                                                pattern="[0-9]*" readonly inputmode="numeric"
                                                                aria-labelledby="" type="number">
                                                        </div>
                                                        <input id="page_title" name="page_title" type="hidden"
                                                            value="<?php echo get_the_title(); ?>" />
                                                        <input class="" id="panels_left_right" name="panels_left_right"
                                                            type="hidden" value="" />
                                                        <button class="btn btn-success">
                                                            Add to Quote
                                                            <i class="fa fa-chevron-right"></i>
                                                        </button>
                                                        <img src="/wp-content/uploads/2018/06/Spinner-1s-200px.gif"
                                                            alt="" class="spinner" style="display:none;" />
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
                            <br />

                            <div class="input-group">
                                <span data-placement="top" data-toggle="tooltip" title="" class="input-group-addon"
                                    data-original-title="Type in the layout code">?</span>
                                <input type="text" name="property_extra_column" id="property_extra_column"
                                    class="input-small">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                        <hr />

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

                        #choose-style label>input {
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
                            background-color: #ced3e4;
                            border-radius: 7px;
                            height: 170px;
                        }

                        #choose-style label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        #choose-style label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        #choose-frametype label>input {
                            /* HIDE RADIO */
                            /* display: none; */
                        }

                        #choose-frametype label {
                            display: block;
                            float: left;
                            width: 100px;
                            text-align: center;
                            border: 2px solid gray;
                            background-color: #ced3e4;
                            border-radius: 7px;
                            margin-right: 1em;
                            font-size: 10px
                        }

                        #choose-frametype label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        #choose-frametype label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        .tpost-type label>input+img {
                            /* IMAGE STYLES */
                            cursor: pointer;
                            border: 4px solid transparent;
                        }

                        .tpost-type label>input:checked+img {
                            /* (CHECKED) IMAGE STYLES */
                            border: 4px solid #438EB9;
                        }

                        .extra-column input {
                            width: 4em;
                        }

                        .extra-column {
                            padding-right: 0.5em;
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

                        /* .extra-columns-row .pull-left.extra-column {
                                display: inline-block;
                            } */

                        input.layout-code {
                            font-size: 0.8em;
                        }

                        input.property-select {
                            /* display: block !important;  */
                        }

                        .collapse {
                            /* display: block;  */
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
                        </style>

                        <div class="show-prod-info">

                        </div>
                </form>

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