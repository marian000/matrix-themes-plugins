<?php
include_once WP_PLUGIN_DIR . '/shutter-module/ajax/atributes_array.php';
require_once get_stylesheet_directory() . '/includes/material-pricing-keys.php';

global $wpdb;

//$all_atributes = $wpdb->get_results( "SELECT * FROM `wp_shutter_attributes`" );
// print_r($atributezzz);

$post_meta_props = array(
	188 => array('Ecowood', ''),
	5 => array('EcowoodPlus', ''),
	6 => array('Biowood', ''),
	138 => array('BiowoodPlus', ''),
	147 => array('Basswood', ''),
	139 => array('BasswoodPlus', ''),
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

$matrix_l3_nonce = wp_create_nonce( 'matrix_l3_pricing_nonce' );

?>

<br>
<div class="container border rounded mt-4 p-5" style="background-color: #F0F0F1">
  <h3>Shutter Settings</h3>

  <div class="mt-5">

		<?php

		$stored = get_post_meta(1, 'attributes_array', true);
		if ($stored !== $atributezzz) {
			update_post_meta(1, 'attributes_array', $atributezzz);
		}
		$stored_csv = get_post_meta(1, 'attributes_array_csv', true);
		if ($stored_csv !== $attributes_csv) {
			update_post_meta(1, 'attributes_array_csv', $attributes_csv);
		}
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
      <li role="presentation" class="nav-item">
        <button class="nav-link" id="per-material-tab" data-bs-toggle="tab" data-bs-target="#per-material" type="button" role="tab" aria-controls="per-material"
                aria-selected="false">Per Material
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
      <div class="tab-pane" id="per-material" role="tabpanel" aria-labelledby="per-material-tab">

          <h3>Per-Material Global Pricing (Level 3)</h3>
          <p class="description">
              Set a default surcharge rate per material. These values apply when a dealer has no
              personal override (Level 1 or Level 2). Placeholder shows the current general global price (Level 4).
              Leave blank to remove the Level 3 override and fall back to the global price.
          </p>

          <?php
          $l3_materials    = matrix_get_material_ids();
          $l3_option_keys  = matrix_get_per_material_option_keys();
          $l3_no_usd_keys  = array( '4008T', 'T_typeAdjustable', 'tposttype_blackout', 'bposttype_blackout' );

          $l3_option_labels = array(
              'Solid'              => 'Solid %',
              'Shaped'             => 'Shaped %',
              'Arched'             => 'Arched %',
              'Combi'              => 'Combi %',
              'French_Door'        => 'French Door +',
              'Tracked'            => 'Tracked +',
              'TrackedByPass'      => 'TrackedByPass +',
              'Inside'             => 'Inside Fit %',
              'Buildout'           => 'Buildout %',
              'Bay_Angle'          => 'Bay Angle %',
              'Colors'             => 'Colors %',
              'Stainless_Steel'    => 'Stainless Steel %',
              'Hidden'             => 'Hidden Hinge %',
              'Concealed_Rod'      => 'Concealed Rod %',
              'P4028X'             => 'P4028X %',
              'P4008T'             => 'P4008T %',
              'P4008W'             => 'P4008W %',
              '4008T'              => '4008T %',
              'T_Buildout'         => 'T_Buildout %',
              'B_Buildout'         => 'B_Buildout %',
              'C_Buildout'         => 'C_Buildout %',
              'G_post'             => 'G_post %',
              'B_typeFlexible'     => 'B_typeFlexible %',
              'T_typeAdjustable'   => 'T_typeAdjustable %',
              'Flat_Louver'        => 'Flat Louver %',
              'Lock'               => 'Lock +',
              'Ringpull'           => 'Ringpull +',
              'Spare_Louvres'      => 'Spare Louvres +',
              'blackoutblind'      => 'Blackout Blind +',
              'tposttype_blackout' => 'T-Post Blackout %',
              'bposttype_blackout' => 'B-Post Blackout %',
          );
          ?>

          <style>
              .matrix-l3-pricing details summary {
                  cursor: pointer;
                  padding: 8px 0;
                  font-weight: 600;
                  font-size: 14px;
              }
              .matrix-l3-pricing details summary:hover {
                  color: #0073aa;
              }
              .matrix-l3-pricing .form-table th {
                  width: 200px;
                  padding: 8px 10px;
              }
              .matrix-l3-pricing .form-table td {
                  padding: 8px 10px;
              }
              .matrix-l3-pricing .form-table thead th {
                  font-weight: 600;
                  border-bottom: 1px solid #c3c4c7;
              }
          </style>

          <form id="per-material-global-form">
          <div class="matrix-l3-pricing">

          <?php foreach ( $l3_materials as $l3_mat_id => $l3_mat_name ) : ?>
              <details>
                  <summary>
                      <strong><?php echo esc_html( $l3_mat_name . ' (ID: ' . $l3_mat_id . ')' ); ?></strong>
                  </summary>
                  <table class="form-table">
                      <thead>
                          <tr>
                              <th><?php esc_html_e( 'Option', 'storefront-child' ); ?></th>
                              <th><?php esc_html_e( 'GBP Price', 'storefront-child' ); ?> (&pound;)</th>
                              <th><?php esc_html_e( 'USD Price', 'storefront-child' ); ?> ($)</th>
                          </tr>
                      </thead>
                      <tbody>
                      <?php foreach ( $l3_option_keys as $l3_opt_key ) :

                          // Field names (sent to ajax-update-price-per-material.php).
                          $l3_gbp_field = 'l3_gbp-' . intval( $l3_mat_id ) . '-' . $l3_opt_key;
                          $l3_usd_field = 'l3_usd-' . intval( $l3_mat_id ) . '-' . $l3_opt_key;

                          // Level 3 current values (post_meta 1 with mat_ prefix).
                          $l3_gbp_meta  = 'mat_' . intval( $l3_mat_id ) . '_' . $l3_opt_key;
                          $l3_usd_meta  = $l3_gbp_meta . '-dolar';
                          $l3_gbp_val   = get_post_meta( 1, $l3_gbp_meta, true );
                          $l3_usd_val   = get_post_meta( 1, $l3_usd_meta, true );

                          // Level 4 placeholders (post_meta 1 without mat_ prefix).
                          $l3_gbp_ph    = get_post_meta( 1, $l3_opt_key, true );
                          $l3_usd_ph    = get_post_meta( 1, $l3_opt_key . '-dolar', true );

                          // Human-readable label.
                          $l3_label     = isset( $l3_option_labels[ $l3_opt_key ] ) ? $l3_option_labels[ $l3_opt_key ] : $l3_opt_key;

                          // USD availability.
                          $l3_has_usd   = ! in_array( $l3_opt_key, $l3_no_usd_keys, true );
                      ?>
                          <tr>
                              <th>
                                  <label for="<?php echo esc_attr( $l3_gbp_field ); ?>">
                                      <?php echo esc_html( $l3_label ); ?>
                                  </label>
                              </th>
                              <td>
                                  <input type="number"
                                         step="0.01"
                                         min="0"
                                         id="<?php echo esc_attr( $l3_gbp_field ); ?>"
                                         name="<?php echo esc_attr( $l3_gbp_field ); ?>"
                                         value="<?php echo esc_attr( $l3_gbp_val ); ?>"
                                         placeholder="<?php echo esc_attr( $l3_gbp_ph ); ?>"
                                         class="small-text" />
                              </td>
                              <td>
                                  <?php if ( $l3_has_usd ) : ?>
                                  <input type="number"
                                         step="0.01"
                                         min="0"
                                         id="<?php echo esc_attr( $l3_usd_field ); ?>"
                                         name="<?php echo esc_attr( $l3_usd_field ); ?>"
                                         value="<?php echo esc_attr( $l3_usd_val ); ?>"
                                         placeholder="<?php echo esc_attr( $l3_usd_ph ); ?>"
                                         class="small-text" />
                                  <?php else : ?>
                                  <span class="description">&mdash;</span>
                                  <?php endif; ?>
                              </td>
                          </tr>
                      <?php endforeach; ?>
                      </tbody>
                  </table>
              </details>
          <?php endforeach; ?>

          </div><!-- /.matrix-l3-pricing -->
          </form>

          <p>
              <button type="button" id="save-per-material-global" class="button button-primary" style="margin-top:10px;">
                  Save Per-Material Global Prices
              </button>
              <span id="per-material-save-status" style="margin-left:12px; display:none;"></span>
          </p>

      </div><!-- /#per-material -->
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

                // Level 3 per-material global pricing save handler.
                (function() {
                    var l3Nonce = '<?php echo esc_js( $matrix_l3_nonce ); ?>';

                    jQuery('#save-per-material-global').on('click', function() {
                        var $btn    = jQuery(this);
                        var $status = jQuery('#per-material-save-status');
                        var formData = jQuery('#per-material-global-form').serialize();

                        $btn.prop('disabled', true).text('Saving...');
                        $status.hide();

                        $.ajax({
                            url: '/wp-content/plugins/shutter-module/ajax/ajax-update-price-per-material.php',
                            type: 'POST',
                            data: {
                                attributes: formData,
                                nonce: l3Nonce
                            },
                            success: function(response) {
                                $status.text('Saved successfully.').css('color', 'green').show();
                            },
                            error: function(xhr) {
                                $status.text('Error: ' + xhr.status + ' ' + xhr.statusText).css('color', 'red').show();
                            },
                            complete: function() {
                                $btn.prop('disabled', false).text('Save Per-Material Global Prices');
                            }
                        });
                    });
                })();

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