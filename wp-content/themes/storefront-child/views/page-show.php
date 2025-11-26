<!-- Bootstrap4 -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4"
    crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
    crossorigin="anonymous"></script> -->
<!-- END Bootstrap4 -->

<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css"
/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js"></script>

<?php

include_once '/public_html/wp-content/plugins/shutter-module/ajax/atributes_array.php';

global $wpdb;

//$all_atributes = $wpdb->get_results( "SELECT * FROM `wp_shutter_attributes`" );
//print_r($all_atributes);
$properties = array(3 => 'BattenStandard', 4 => 'BattenCustom', 52 => 'Flat Louver-%', 56 => 'Inside-%', 187 => 'Earth', 188 => 'Ecowood', 137 => 'Green', 138 => 'Biowood', 139 => 'Supreme', 221 => 'Solid-%', 229 => 'Combi-%', 33 => 'Shaped-%', 34 => 'French Door-+', 35 => 'Tracked-+', 37 => 'TrackedByPass-+', 36 => 'Arched-%', 100 => 'Buildout-%', 93 => 'Stainless Steel-%', 93 => 'Stainless Steel-%', 403 => 'Concealed Rod-%', 101 => 'Bay Angle-%', 264 => 'Colors-%', 102 => 'Ringpull-+', 103 => 'Spare Louvres-+', 186 => 'Hidden-%', 171 => 'P4028X-%', 319 => 'P4008W-%', 322 => 'P4008T-%', 275 => 'T_Buildout-%', 276 => 'B_Buildout-%', 277 => 'C_Buildout-%', 278 => 'Lock-+', 500 => 'G_post-%', 501 => 'blackoutblind-+', 2 => 'B_typeFlexible-%', 5 => 'T_typeAdjustable-%', 502 => 'tposttype_blackout-%', 73 => 'Light_block-%');

$propertie_price = array(3 => get_post_meta(1, 'BattenStandard', true), 4 => get_post_meta(1, 'BattenCustom', true), 52 => get_post_meta(1, 'Flat_Louver', true), 56 => get_post_meta(1, 'Inside', true), 187 => get_post_meta(1, 'Earth', true), 188 => get_post_meta(1, 'Ecowood', true), 137 => get_post_meta(1, 'Green', true), 138 => get_post_meta(1, 'Biowood', true), 139 => get_post_meta(1, 'Supreme', true), 221 => get_post_meta(1, 'Solid', true), 229 => get_post_meta(1, 'Combi', true), 171 => get_post_meta(1, 'P4028X', true), 319 => get_post_meta(1, 'P4008W', true), 322 => get_post_meta(1, 'P4008T', true), 33 => get_post_meta(1, 'Shaped', true), 34 => get_post_meta(1, 'French_Door', true), 35 => get_post_meta(1, 'Tracked', true), 37 => get_post_meta(1, 'TrackedByPass', true), 36 => get_post_meta(1, 'Arched', true), 100 => get_post_meta(1, 'Buildout', true), 93 => get_post_meta(1, 'Stainless_Steel', true), 403 => get_post_meta(1, 'Concealed_Rod', true), 101 => get_post_meta(1, 'Bay_Angle', true), 264 => get_post_meta(1, 'Colors', true), 102 => get_post_meta(1, 'Ringpull', true), 103 => get_post_meta(1, 'Spare_Louvres', true), 186 => get_post_meta(1, 'Hidden', true), 275 => get_post_meta(1, 'T_Buildout', true), 276 => get_post_meta(1, 'B_Buildout', true), 277 => get_post_meta(1, 'C_Buildout', true), 278 => get_post_meta(1, 'Lock', true), 500 => get_post_meta(1, 'G_post', true), 501 => get_post_meta(1, 'blackoutblind', true), 2 => get_post_meta(1, 'B_typeFlexible', true), 5 => get_post_meta(1, 'T_typeAdjustable', true), 502 => get_post_meta(1, 'tposttype_blackout', true), 73 => get_post_meta(1, 'Light_block', true));

$propertie_price_dolar = array(3 => get_post_meta(1, 'BattenStandard-dolar', true), 4 => get_post_meta(1, 'BattenCustom-dolar', true), 52 => get_post_meta(1, 'Flat_Louver-dolar', true), 56 => get_post_meta(1, 'Inside-dolar', true), 187 => get_post_meta(1, 'Earth-dolar', true), 188 => get_post_meta(1, 'Ecowood-dolar', true), 137 => get_post_meta(1, 'Green-dolar', true), 138 => get_post_meta(1, 'Biowood-dolar', true), 139 => get_post_meta(1, 'Supreme-dolar', true), 171 => get_post_meta(1, 'P4028X-dolar', true), 319 => get_post_meta(1, 'P4008W-dolar', true), 322 => get_post_meta(1, 'P4008T-dolar', true), 221 => get_post_meta(1, 'Solid-dolar', true), 229 => get_post_meta(1, 'Combi-dolar', true), 33 => get_post_meta(1, 'Shaped-dolar', true), 36 => get_post_meta(1, 'Arched-dolar', true), 34 => get_post_meta(1, 'French_Door-dolar', true), 35 => get_post_meta(1, 'Tracked-dolar', true), 37 => get_post_meta(1, 'TrackedByPass-dolar', true), 100 => get_post_meta(1, 'Buildout-dolar', true), 93 => get_post_meta(1, 'Stainless_Steel-dolar', true), 403 => get_post_meta(1, 'Concealed_Rod-dolar', true), 101 => get_post_meta(1, 'Bay_Angle-dolar', true), 264 => get_post_meta(1, 'Colors-dolar', true), 102 => get_post_meta(1, 'Ringpull-dolar', true), 186 => get_post_meta(1, 'Hidden-dolar', true), 103 => get_post_meta(1, 'Spare_Louvres-dolar', true), 275 => get_post_meta(1, 'T_Buildout-dolar', true), 276 => get_post_meta(1, 'B_Buildout-dolar', true), 277 => get_post_meta(1, 'C_Buildout-dolar', true), 278 => get_post_meta(1, 'Lock-dolar', true), 501 => get_post_meta(1, 'blackoutblind-dolar', true), 2 => get_post_meta(1, 'B_typeFlexible-dolar', true), 5 => get_post_meta(1, 'T_typeAdjustable-dolar', true), 502 => get_post_meta(1, 'tposttype_blackout-dolar', true), 73 => get_post_meta(1, 'Light_block-dolar', true));

?>

<div class="container-fluid">
    <h3>Shutter Settings</h3>

    <div class="container-fluid">

        <div class="jumbotron">

            <!--            <h3>Shortcodes:</h3>-->
            <!--            <ul>-->
            <!--                <li>- Shutter shortcode: [product_shutter1]</li>-->
            <!--                <li>- Individual Bay Shutters shortcode: [product_shutter2]</li>-->
            <!--                <li>- Shutter and Blackout Blind shortcode: [product_shutter3]</li>-->
            <!--                <li>- Blackout Frame shortcode: [product_shutter4]</li>-->
            <!--                <li>- Batten shortcode: [product_shutter5]</li>-->
            <!--            </ul>-->

            <h4>Customize Shutter Attributes:</h4>

            <br>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active">
                    <a href="#home" aria-controls="home" role="tab" data-toggle="tab"> Pound </a>
                </li>
                <li role="presentation">
                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"> Dolar </a>
                </li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="home">

                    <form action="" id="form-table-attributes" class="form-horizontal">
                        <table id="shutter-table-settings" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($properties as $id => $propertie) {
                                $i++;

                                $part = explode("-", $propertie);
                                $part[0]; // name
                                $part[1]; // ecuation

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $propertie; ?>
                                    </td>
                                    <td>
                                                <span class="hidden">
                                                    <?php echo $id; ?>
                                                </span>
                                        <input type="text" class="form-control" style="width: 100%;"
                                               placeholder="Enter price" name="price-<?php echo $part[0]; ?>"
                                               value="<?php echo $propertie_price[$id]; ?>">
                                    </td>

                                </tr>
                                <?php
                            }
                            ?>

                            <tr>
                                <td class="text-center">
                                    <?php echo $i++; ?>
                                </td>
                                <td>
                                    <label for="train_price">Train price sq/m</label>
                                </td>
                                <td>
                                    <input name="train_price" type="text" id="train_price"
                                           value="<?php echo get_post_meta(1, 'train_price', true); ?>" placeholder="Enter price">
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </tfoot>
                        </table>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-lg">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="profile">
                    <form action="" id="form-table-attributes-dolar" class="form-horizontal">
                        <table id="shutter-table-settings-dolar" class="table table-striped">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $i = 0;
                            foreach ($properties as $id => $propertie) {
                                $i++;

                                $part = explode("-", $propertie);
                                $part[0]; // name
                                $part[1]; // ecuation

                                ?>
                                <tr>
                                    <td class="text-center">
                                        <?php echo $i; ?>
                                    </td>
                                    <td>
                                        <?php echo $propertie; ?>
                                    </td>
                                    <td>
                                                <span class="hidden">
                                                    <?php echo $id; ?>
                                                </span>
                                        <input type="text" class="form-control" style="width: 100%;"
                                               placeholder="Enter price" name="dolar_price-<?php echo $part[0]; ?>"
                                               value="<?php echo $propertie_price_dolar[$id]; ?>">
                                    </td>

                                </tr>
                                <?php

                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center">ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <!-- <th>Show/Hide</th> -->
                            </tr>
                            </tfoot>
                        </table>

                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-info btn-lg submit-dolar">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="show-attributes">
        </div>
        <div class="show-attributes-dolar">
        </div>

    </div>

</div>

<script>
    jQuery.noConflict();
    (function ($) {
        $(function () {
            jQuery(document).ready(function () {

                // Set price for normal atributes
                jQuery("#form-table-attributes").submit(function (event) {
                    event.preventDefault();
                    console.log('lire');
                    var attributes = jQuery('#form-table-attributes').serialize();
                    //console.log(attributes);
                    $.ajax({
                        method: "POST",
                        url: "/wp-content/plugins/shutter-module/ajax/ajax-update-price.php",
                        data: {
                            attributes: attributes
                        }
                    })
                        .done(function (data) {
                            jQuery('.show-attributes').html(data);
                            alert("Data Saved: " + data);
                            console.log(data);

                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        });
                });

                // Set price for dolar attributes
                jQuery("#form-table-attributes-dolar").submit(function (event) {
                    event.preventDefault();
                    console.log('dolar');
                    var attributes = jQuery('#form-table-attributes-dolar').serialize();
                    //console.log(attributes);
                    $.ajax({
                        method: "POST",
                        url: "/wp-content/plugins/shutter-module/ajax/ajax-update-price-dolar.php",
                        data: {
                            attributes: attributes
                        }
                    })
                        .done(function (data) {
                            jQuery('.show-attributes-dolar').html(data);
                            alert("Data Saved: " + data);
                            console.log(data);

                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        });
                });

            });

        });
    })(jQuery)
</script>
