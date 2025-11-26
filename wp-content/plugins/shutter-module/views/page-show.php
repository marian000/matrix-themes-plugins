<?php
include_once WP_PLUGIN_DIR . '/shutter-module/ajax/atributes_array.php';

global $wpdb;

//$all_atributes = $wpdb->get_results( "SELECT * FROM `wp_shutter_attributes`" );
// print_r($atributezzz);

$post_meta_props = array(
	5 => array('EcowoodPlus', ''),
	188 => array('Ecowood', ''),
	137 => array('Green', ''),
	138 => array('BiowoodPlus', ''),
	6 => array('Biowood', ''),
	139 => array('Supreme', ''),
	3 => array('BattenStandard', ''),
	4 => array('BattenCustom', ''),
	52 => array('Flat Louver', '%'),
	56 => array('Inside', '%'),
	187 => array('Earth', ''),
	221 => array('Solid', '%'),
	229 => array('Combi', '%'),
	33 => array('Shaped', '%'),
	34 => array('French Door', '+'),
	35 => array('Tracked', '+'),
	37 => array('TrackedByPass', '+'),
	36 => array('Arched', '%'),
	100 => array('Buildout', '%'),
	93 => array('Stainless Steel', '%'),
	180 => array('Security S/S Hinge', '%'),
	403 => array('Concealed Rod', '%'),
	387 => array('Hidden Tilt with Louver Lock', '%'),
	101 => array('Bay Angle', '%'),
	264 => array('Colors', '%'),
	102 => array('Ringpull', '+'),
	103 => array('Spare Louvres', '+'),
	186 => array('Hidden', '%'),
	171 => array('P4028X', '%'),
	319 => array('P4008W', '%'),
	322 => array('P4008T', '%'),
	353 => array('4008T', '%'),
	275 => array('T_Buildout', '%'),
	276 => array('B_Buildout', '%'),
	277 => array('C_Buildout', '%'),
	278 => array('Lock', '+'),
	279 => array('Louver_lock', '+'),
	280 => array('Central_Lock', '+'),
	281 => array('Top_Bottom_Lock', '+'),
	500 => array('G_post', '%'),
	501 => array('blackoutblind', '+'),
	2 => array('B_typeFlexible', '%'),
	1 => array('T_typeAdjustable', '%'),
	502 => array('tposttype_blackout', '%'),
	503 => array('bposttype_blackout', '%'),
	73 => array('Light_block', '%'),
	1001 => array('motor', '+'),
	1002 => array('remote', '+'),
);

$propertie_price = array();
$propertie_price_dolar = array();

foreach ($post_meta_props as $key => $prop) {
	$name_with_underscore = str_replace(' ', '_', $prop[0]);

	$propertie_price[$key] = get_post_meta(1, $name_with_underscore, true);
	$propertie_price_dolar[$key] = get_post_meta(1, $name_with_underscore . '-dolar', true);
}

?>

<br>
<div class="container border rounded mt-4 p-5" style="background-color: #F0F0F1">
  <h3>Shutter Settings</h3>

  <div class="mt-5">

		<?php

		update_post_meta(1, 'attributes_array', $atributezzz);
		update_post_meta(1, 'attributes_array_csv', $attributes_csv);
		//        print_r($atributezzz);
		?>

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
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li role="presentation" class="nav-item active">
        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home"
                aria-selected="true">Pound
        </button>
      </li>
      <li role="presentation" class="nav-item">
        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                aria-selected="false">Dolar
        </button>
      </li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">

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
						foreach ($post_meta_props as $id => $propertie) {
							$i++;

							$name = $propertie[0];
							$operator = $propertie[1];
							?>
              <tr>
                <td class="text-center">
									<?php echo $i; ?>
                </td>
                <td>
									<?php echo $name . ' ' . $operator; ?>
                </td>
                <td>
                                        <span class="hidden">
                                            <?php echo $id; ?>
                                        </span>
                  <input type="text" class="form-control" style="width: 100%;"
                         placeholder="Enter price" name="price-<?php echo $name; ?>"
                         value="<?php echo $propertie_price[$id]; ?>">
                </td>

              </tr>
							<?php
						}
						?>

            <tr>
              <td class="text-center">
								<?php echo $i + 1; ?>
              </td>
              <td>
                <label for="train_price">Train price sq/m</label>
              </td>
              <td>
                <input name="train_price" type="text" id="train_price"
                       value="<?php echo get_post_meta(1, 'train_price', true); ?>"
                       placeholder="Enter price">
              </td>
            </tr>
            <tr>
              <td class="text-center">
								<?php echo $i + 1; ?>
              </td>
              <td>
                <label for="train_price">Panel width < 200</label>
              </td>
              <td>
                <input name="panel_width_price" type="text" id="panel_width_price"
                       value="<?php echo get_post_meta(1, 'panel_width_price', true); ?>"
                       placeholder="Enter price">
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
      <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
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
						foreach ($post_meta_props as $id => $propertie) {
							$i++;

							$name = $propertie[0];
							$operator = $propertie[1];
							?>
              <tr>
                <td class="text-center">
									<?php echo $i; ?>
                </td>
                <td>
									<?php echo $name . ' ' . $operator; ?>
                </td>
                <td>
                                        <span class="hidden">
                                            <?php echo $id; ?>
                                        </span>
                  <input type="text" class="form-control" style="width: 100%;"
                         placeholder="Enter price" name="dolar_price-<?php echo $name; ?>"
                         value="<?php echo $propertie_price_dolar[$id]; ?>">
                </td>

              </tr>

							<?php
						}
						?>
            <tr>
              <td class="text-center">
								<?php echo $i + 1; ?>
              </td>
              <td>
                <label for="train_price">Panel width < 200</label>
              </td>
              <td>
                <input name="dolar_price-panel_width_price" type="text" id="dolar_price-panel_width_price"
                       value="<?php echo get_post_meta(1, 'dolar_price-panel_width_price', true); ?>"
                       placeholder="Enter price">
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

<script>

    jQuery.noConflict();
    (function ($) {
        $(function () {
            jQuery(document).ready(function () {
                // var firstTabEl = document.querySelector('#myTab li:last-child a')
                // var firstTab = new bootstrap.Tab(firstTabEl)
                //
                // firstTab.show()

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