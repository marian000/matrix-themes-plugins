<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if (!defined('ABSPATH')) {
	exit;
}

wc_print_notices();


$cart_id = BMC\WooCommerce\MultiSession\customer_id();
//print_r($cart_id);

$my_session = BMC\WooCommerce\MultiSession\get_session($cart_id, get_current_user_id());

//echo '<pre>';
//print_r($my_session['carts'][$cart_id]);
//echo '</pre>';

$cart_type = $my_session['carts'][$cart_id]['type'];

do_action('woocommerce_before_cart'); ?>



<?php

$i = 0;
$atributes = get_post_meta(1, 'attributes_array', true);

$nr_code_prod = array();
foreach (WC()->cart->get_cart() as $item_id => $item_data) {
	$i++;
	// $product = $item_data->get_product();
	$product_id = $item_data['product_id'];

	$nr_t = get_post_meta($product_id, 'counter_t', true);
	$nr_b = get_post_meta($product_id, 'counter_b', true);
	$nr_c = get_post_meta($product_id, 'counter_c', true);
	$nr_g = get_post_meta($product_id, 'counter_g', true);

	if (!empty($nr_code_prod)) {
		if ($nr_code_prod['g'] < $nr_g) {
			$nr_code_prod['g'] = $nr_g;
		}
		if ($nr_code_prod['t'] < $nr_t) {
			$nr_code_prod['t'] = $nr_t;
		}
		if ($nr_code_prod['b'] < $nr_b) {
			$nr_code_prod['b'] = $nr_b;
		}
		if ($nr_code_prod['c'] < $nr_c) {
			$nr_code_prod['c'] = $nr_c;
		}
	} else {
		$nr_code_prod['g'] = $nr_g;
		$nr_code_prod['t'] = $nr_t;
		$nr_code_prod['b'] = $nr_b;
		$nr_code_prod['c'] = $nr_c;
	}

}

?>

<div class="alert alert-warning">
	<?php
	// Get the saved notification message
	$notification_message = get_post_meta(1, 'notification_users_message', true);
	echo wp_kses_post($notification_message); // Safely display the HTML content
	?>
</div>

<br>

<?php

/* ***********************************************
//  Show POS order items
*********************************************** */

$mystring = get_the_title();
$findme = '-pos';
$findmeCompoenent = 'component';
$pos = strpos($mystring, $findme);

if (strpos($mystring, $findme) !== false) { ?>

<a href="/order-pos/" class="btn btn-primary blue"> Order POS</a>
<br>

<div class="order-summary-table" style="overflow: auto;">
  <table id="example" style="width:100%" class="table table-striped">
    <thead>
    <tr>
      <!-- <th>item</th> -->

      <th>Item</th>
      <th></th>
      <th></th>
      <th>Price</th>
      <th></th>
      <th>Quantity</th>
      <th>Total</th>
      <th></th>

    </tr>
    </thead>
    <tbody>

		<?php
		$array_att = array();
		foreach (WC()->cart->get_cart() as $item_id => $item_data) {

			$j++;
			//$product = $item_data->get_product();
			$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
			//print_r($item_id);
			$product_id = $item_data['product_id'];

			$price = get_post_meta($product_id, '_price', true);
			$regular_price = get_post_meta($product_id, '_regular_price', true);
			$sale_price = get_post_meta($product_id, '_sale_price', true);

			$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

			if ($term_list[0]->slug == 'pos' || $term_list[0]->slug == 'components' || $term_list[0]->slug == 'components-fob') {
				?>
        <tr>
          <td>
            <a href="<?php the_permalink($product_id); ?>">
							<?php echo get_the_title($product_id); ?>
            </a>
          </td>
          <td></td>
          <td></td>
          <td>£
						<?php echo number_format($price, 2); ?>
          </td>
          <td></td>
          <td><?php echo $item_data['quantity']; ?></td>
          <td>£
						<?php echo number_format($price, 2); ?>
          </td>
          <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							'<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
							esc_url(wc_get_cart_remove_url($item_id)),
							__('Remove this item', 'woocommerce'),
							esc_attr($product_id),
							esc_attr($_product->get_sku())
						), $item_id);

						?></td>
        </tr>
				<?php
			}
		}
		?>

    </tbody>
  </table>

	<?php
	}
  elseif (strpos($mystring, $findmeCompoenent) !== false) {

	$first_element_cart = reset(WC()->cart->get_cart());

	$product_id = $first_element_cart['product_id'];
	$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

	$user = wp_get_current_user();
	$roles = $user->roles;
	if (in_array('china_admin', $roles)) {
		// echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
		echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
	} else {
		if ($term_list[0]->slug == 'components') {
			echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
			// echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components FOB</a>';
		} elseif ($term_list[0]->slug == 'components-fob') {
			echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
		} else {
			// echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
			echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
			echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components FOB</a>';
		}
	}

	?>
<br>

  <div class="order-summary-table" style="overflow: auto;">
    <table id="example" style="width:100%" class="table table-striped">
      <thead>
      <tr>
        <!-- <th>item</th> -->

        <th>Item</th>
        <th></th>
        <th></th>
        <th>Price</th>
        <th></th>
        <th>Quantity</th>
        <th>Total</th>
        <th></th>

      </tr>
      </thead>
      <tbody>

			<?php
			$array_att = array();
			foreach (WC()->cart->get_cart() as $item_id => $item_data) {

				$j++;
				//$product = $item_data->get_product();
				$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
				//print_r($item_id);
				$product_id = $item_data['product_id'];
				$attribute_pa_colors = $item_data['variation']['attribute_pa_colors'];
				$attribute_pa_covered = $item_data['variation']['attribute_pa_covered'];

				$price = $item_data['data']->get_price();
				$regular_price = get_post_meta($product_id, '_regular_price', true);
				$sale_price = get_post_meta($product_id, '_sale_price', true);

				$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

				if ($term_list[0]->slug == 'pos' || $term_list[0]->slug == 'components' || $term_list[0]->slug == 'components-fob') {
					?>
          <tr>
            <td>
              <a href="<?php the_permalink($product_id); ?>">
								<?php echo get_the_title($product_id); ?>
              </a>
            </td>
            <td><?php
							if (!empty($attribute_pa_colors)) echo '<strong>Color:</strong> ' . $attribute_pa_colors . '<br>';
							if (!empty($attribute_pa_covered)) echo '<strong>Covered:</strong> ' . $attribute_pa_covered . '<br>';
							if (!empty($item_data['comp_note'])) echo '<strong>Your Note:</strong> ' . $item_data['comp_note'];
							?>
            </td>
            <td></td>
            <td><?php echo $fob_components ? '$' : '£'; ?>
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
            <td><?php echo $item_data['quantity']; ?></td>
            <td>£
							<?php echo number_format($price * $item_data['quantity'], 2); ?>
            </td>
            <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
								esc_url(wc_get_cart_remove_url($item_id)),
								__('Remove this item', 'woocommerce'),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							), $item_id);

							?></td>
          </tr>
					<?php
				}
			}
			?>

      </tbody>
    </table>

		<?php
		}
        elseif ($cart_type == 'Awning') {
			?>

            <div class="row">
                <div class="col-md-8">
                    <a href="/prod-awning/" class="btn btn-primary blue"> + Add New Awning</a>
                </div>
            </div>

            <input type="hidden" id="my_field_name" value="<?php echo $cart_name; ?>">

            <br><br>
			<?php

			echo do_shortcode('[awning_table_items]');
		}
		/* ***********************************************
		//  Show Shutters order items
		*********************************************** */
		else {

		?>

    <a href="/prod1-all/" class="btn btn-primary blue"> + Add New Shutter</a>
    <a href="/prod-individual/" class="btn btn-primary blue"> + Add Individual Bay Shutter</a>
    <a href="/prod2-all/" class="btn btn-primary blue"> + Add New Shutter & Blackout Blind</a>
    <a href="/prod5/" class="btn btn-danger blue"> + Add Batten</a>
    <br>

    <div class="order-summary-table" style="overflow: auto;">
      <table id="example" style="width:100%" class="table table-striped">
        <thead>
        <tr>
          <!-- <th>item</th> -->

          <th>Item</th>
          <th></th>
          <th></th>
          <th>Price</th>
          <th></th>
          <th>Quantity</th>
          <th>Total</th>
          <th></th>

        </tr>
        </thead>
        <tbody>
				<?php

				$array_att = array();
				foreach (WC()->cart->get_cart() as $item_id => $item_data) {

				$j++;
				//$product = $item_data->get_product();
				$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
				//print_r($item_id);
				$product_id = $item_data['product_id'];

				$title = get_the_title($product_id);

				$property_room_other = get_post_meta($product_id, 'property_room_other', true);
				$property_style = get_post_meta($product_id, 'property_style', true);
				$property_frametype = get_post_meta($product_id, 'property_frametype', true);

				echo $attachment = get_post_meta($product_id, 'attachment', true);
				$array_att[] = $attachment;

				$property_category = get_post_meta($product_id, 'shutter_category', true);
				$property_material = get_post_meta($product_id, 'property_material', true);
				$property_width = get_post_meta($product_id, 'property_width', true);
				$property_height = get_post_meta($product_id, 'property_height', true);
				$property_midrailheight = get_post_meta($product_id, 'property_midrailheight', true);
				$property_midrailheight2 = get_post_meta($product_id, 'property_midrailheight2', true);
				$property_midraildivider1 = get_post_meta($product_id, 'property_midraildivider1', true);
				$property_midraildivider2 = get_post_meta($product_id, 'property_midraildivider2', true);
				$property_midrailpositioncritical = get_post_meta($product_id, 'property_midrailpositioncritical', true);
				$property_totheight = get_post_meta($product_id, 'property_totheight', true);
				$property_horizontaltpost = get_post_meta($product_id, 'property_horizontaltpost', true);
				$property_bladesize = get_post_meta($product_id, 'property_bladesize', true);
				$property_fit = get_post_meta($product_id, 'property_fit', true);
				$property_frameleft = get_post_meta($product_id, 'property_frameleft', true);
				$property_frameright = get_post_meta($product_id, 'property_frameright', true);
				$property_frametop = get_post_meta($product_id, 'property_frametop', true);
				$property_framebottom = get_post_meta($product_id, 'property_framebottom', true);
				$bayposttype = get_post_meta($product_id, 'bay-post-type', true);
				$property_builtout = get_post_meta($product_id, 'property_builtout', true);
				$property_stile = get_post_meta($product_id, 'property_stile', true);
				$property_hingecolour = get_post_meta($product_id, 'property_hingecolour', true);
				$property_shuttercolour = get_post_meta($product_id, 'property_shuttercolour', true);
				$property_shuttercolour_other = get_post_meta($product_id, 'property_shuttercolour_other', true);
				$property_controltype = get_post_meta($product_id, 'property_controltype', true);
				$property_controlsplitheight = get_post_meta($product_id, 'property_controlsplitheight', true);
				$property_controlsplitheight2 = get_post_meta($product_id, 'property_controlsplitheight2', true);
				$property_layoutcode = get_post_meta($product_id, 'property_layoutcode', true);
				$property_t1 = get_post_meta($product_id, 'property_t1', true);
				$property_total = get_post_meta($product_id, 'property_total', true);
				$property_depth = get_post_meta($product_id, 'property_depth', true);
				$property_volume = get_post_meta($product_id, 'property_volume', true);
				$property_blackoutblindcolour = get_post_meta($product_id, 'property_blackoutblindcolour', true);

				$property_sparelouvres = get_post_meta($product_id, 'property_sparelouvres', true);
				$property_ringpull = get_post_meta($product_id, 'property_ringpull', true);
				$property_locks = get_post_meta($product_id, 'property_locks', true);
				$property_ringpull_volume = get_post_meta($product_id, 'property_ringpull_volume', true);
				$property_locks_volume = get_post_meta($product_id, 'property_locks_volume', true);
				$price = get_post_meta($product_id, '_price', true);
				$regular_price = get_post_meta($product_id, '_regular_price', true);
				$sale_price = get_post_meta($product_id, '_sale_price', true);
				$svg_product = get_post_meta($product_id, 'svg_product', true);
				$comments_customer = get_post_meta($product_id, 'comments_customer', true);

				//Returns All Term Items for "product_cat"
				$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
				// echo '<pre>';
				// print_r($term_list->slug);
				// echo '</pre>';

				if ($product_id == 337 || $product_id == 72951) { ?>
          <tr>
            <td>
							<?php echo get_the_title($product_id); ?>
            </td>
            <td></td>
            <td></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
            <td></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
          </tr>
					<?php
				} elseif ($product_id == 1183) { ?>
          <tr>
            <td>
							<?php echo get_the_title($product_id); ?>
            </td>
            <td></td>
            <td></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
            <td></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
          </tr>
					<?php
				} elseif ($term_list[0]->slug == 'pos' || $term_list[0]->slug == 'components' || $term_list[0]->slug == 'components-fob') {
					?>
          <tr>
            <td>
							<?php echo get_the_title($product_id); ?>
            </td>
            <td></td>
            <td></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td></td>
            <td><?php echo $item_data['quantity']; ?></td>
            <td>£
							<?php echo number_format($price, 2); ?>
            </td>
            <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
								esc_url(wc_get_cart_remove_url($item_id)),
								__('Remove this item', 'woocommerce'),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							), $item_id);

							?></td>
          </tr>
					<?php
				} elseif ($property_category === 'Batten') {
					?>
          <tr>
            <td>
              <a class="nameshutter"
                 href="/product5-edit/?id=<?php echo $product_id * 1498765 * 33; ?>"><?php echo $property_category; ?></a><?php
							?>
              <br> Room:
              <strong>
								<?php echo $property_room_other; ?></strong>

            </td>
            <td></td>
            <td>Shutter Colour:<strong>
								<?php echo $atributes[$property_shuttercolour]; ?></strong>
              <br>
            </td>
            <td><!-- £ 5000 -->

            </td>
            <td></td>
            <td>
              <strong>
								<?php echo number_format((double)$property_volume, 5); ?> q/m </strong>
              <br>
							<?php echo 'Qty:  <strong>' . $item_data['quantity']; ?></strong>
              <br> Width:<strong>
								<?php echo $property_width; ?></strong>
              <br> Height:<strong>
								<?php echo $property_height; ?></strong>
              <br> Depth :<strong>
								<?php echo $property_depth; ?></strong>
              <br>
            </td>
            <td>£
							<?php echo number_format($price, 2) * $item_data['quantity']; ?>
            </td>
            <td>

							<?php
							$pieces = explode("-", $title);
							$property_category;
							if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
								?>
              <a class="btn btn-primary transparent"
                 href="/product-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">Edit</a><?php
							} elseif ($property_category === 'Shutter & Blackout Blind') {
								?>
              <a class="btn btn-primary transparent"
                 href="/product3-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">Edit</a><?php
							} elseif ($property_category === 'Batten') {
								?>
              <a class="btn btn-primary transparent"
                 href="/product5-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">Edit</a><?php
							}

							?>

							<?php echo do_shortcode('[duplicate_prod prod_id=' . $product_id . ']'); ?>
              <br>
							<?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
								esc_url(wc_get_cart_remove_url($item_id)),
								__('Remove this item', 'woocommerce'),
								esc_attr($product_id),
								esc_attr($_product->get_sku())
							), $item_id);

							// echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
							// 	'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">X</a>',
							// 	esc_url( wc_get_cart_remove_url( $item_id ) ),
							// 	__( 'Remove this item', 'woocommerce' ),
							// 	esc_attr( $product_id ),
							// 	esc_attr( $_product->get_sku() )
							// ), $cart_item_key );
							?>
            </td>
          </tr>
					<?php
				}
                elseif ($cart_type == 'Awning') {
					?>

                    <div class="row">
                        <div class="col-md-8">
                            <a href="/prod-awning/" class="btn btn-primary blue"> + Add New Awning</a>
                        </div>
                    </div>

                    <br><br>

					<?php
				}
				else {

				?>
        <tr>
          <!-- <td><?php // echo $j;
					?></td> -->
          <td>
						<?php
						$pieces = explode("-", $title);
						$property_category;
						if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
							?>
              <a class="nameshutter"
                 href="/product-edit/?id=<?php echo $product_id * 1498765 * 33; ?>"><?php echo $atributes[$property_material]; ?></a><?php
						} elseif ($property_category === 'Shutter & Blackout Blind') {
							?>
              <a class="nameshutter"
                 href="/product3-edit/?id=<?php echo $product_id * 1498765 * 33; ?>"><?php echo $atributes[$property_material] . ' (' . $property_category . ')'; ?></a><?php
						} elseif ($property_category === 'Batten') {
							?>
              <a class="nameshutter"
                 href="/product5-edit/?id=<?php echo $product_id * 1498765 * 33; ?>"><?php echo $property_category; ?></a><?php
						} ?>
            <br> Room:
            <strong>
							<?php echo $property_room_other; ?></strong>
            <br> Installation style:<strong>
							<?php echo $atributes[$property_style]; ?></strong>
            <br> Shape:
						<?php
						if (!empty($attachment)) {
							echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';
						} else {
							echo '<strong>no</strong>';
						}
						?>
            <br> Midrail Height:<strong>
							<?php echo $property_midrailheight; ?></strong>
            <br>

						<?php
						if (!empty($property_midrailheight2)) {
							echo 'Midrail Height 2: <strong>' . $property_midrailheight2 . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_midraildivider1)) {
							echo 'Hidden Divider 1: <strong>' . $property_midraildivider1 . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_midraildivider2)) {
							echo 'Hidden Divider 2: <strong>' . $property_midraildivider2 . '</strong><br>';
						}
						?> Position is Critical:<strong>
							<?php echo $atributes[$property_midrailpositioncritical] ?></strong>
            <br>
						<?php
						if (!empty($property_totheight)) {
							echo 'T-o-T Height: <strong>' . $property_totheight . '</strong><br>';
						}
						?>
						<?php
						if (!empty($property_horizontaltpost)) {
							echo 'Horizontal T Post: <strong>' . $property_horizontaltpost . '</strong><br>';
						}
						?> Louvre size:<strong>
							<?php echo $atributes[$property_bladesize]; ?></strong>
            <br>
						<?php
						echo 'Fit: <strong>' . $atributes[$property_fit];
						?></strong>
          </td>
          <td>Frame type:<strong>
							<?php echo $atributes[$property_frametype]; ?></strong>
            <br> Stile type:<strong>
							<?php echo $atributes[$property_stile]; ?></strong>
            <br> Frame Left:<strong>
							<?php echo $atributes[$property_frameleft]; ?></strong>
            <br> Frame Right:<strong>
							<?php echo $atributes[$property_frameright]; ?></strong>
            <br> Frame Top:<strong>
							<?php echo $atributes[$property_frametop]; ?></strong>
            <br> Frame Bottom:<strong>
							<?php echo $atributes[$property_framebottom]; ?></strong>
            <br>
						<?php
						if (!empty($property_builtout)) {
							echo 'Buildout: <strong>' . $property_builtout . '</strong> (+10%)';
						}
						?>
          </td>
          <td>Hinge Colour:<strong>
							<?php echo $atributes[$property_hingecolour]; ?></strong>
            <br> Shutter Colour:<strong>
							<?php echo $atributes[$property_shuttercolour]; ?></strong>
            <br>
						<?php
						if (!empty($property_shuttercolour_other)) {
							echo 'Other Colour: <strong>' . $property_shuttercolour_other . '</strong><br>';
						}

						if ($property_category == 'Shutter & Blackout Blind') {
							?>
              Blackout Blind Colour:
              <strong>
								<?php echo $atributes[$property_blackoutblindcolour]; ?></strong>
              <br>
							<?php
						}
						?>
            Control Type:<strong>
							<?php echo $atributes[$property_controltype]; ?></strong> <?php
						if ($atributes[$property_controltype] == 'Concealed rod') {
							echo ' (+10%)';
						}
						?>
    </div>

	<?php if (!empty($atributes[$property_controlsplitheight])) { ?>
    <br> Control Split Height:
    <strong>
		<?php echo $atributes[$property_controlsplitheight]; ?></strong><?php } ?>
  <br> Layout code:
    <strong style="text-transform:uppercase;">
			<?php echo $property_layoutcode; ?></strong>
  <br>
	<?php

	$tbuilout = 0;
	$cbuilout = 0;
	$bbuilout = 0;
	$unghi = 0;
	foreach ($nr_code_prod as $key => $val) {
		for ($i = 1; $i < $val + 1; $i++) {
			if ($key == 'b') {
				if (!empty(get_post_meta($product_id, 'property_bp' . $i, true))) {
					echo 'BPost' . $i . ': <strong>' . get_post_meta($product_id, 'property_bp' . $i, true) . '/' . get_post_meta($product_id, 'property_ba' . $i, true) . '</strong>';
					if ((get_post_meta($product_id, 'property_ba' . $i, true) == 90) || (get_post_meta($product_id, 'property_ba' . $i, true) == 135)) {
						echo '<br>';
						if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
							echo 'BPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>';
							$bbuilout++;
							if ($bbuilout == 1) {
								echo '(+7%)';
							}
						}
					} else {
						$unghi++;
						if ($unghi == 1 && $bayposttype == 'normal') {
							echo ' (+10%)';
						}
						echo '<br>';
						if (!empty(get_post_meta($product_id, 'property_b_buildout' . $i, true))) {
							echo 'BPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_b_buildout' . $i, true) . '</strong>';
						}
					}

					echo '<br>';
				}
				echo 'B-post Type: <strong>' . $bayposttype;
				if ($bayposttype == 'flexible') {
					if (!empty(get_user_meta($user_id_customer, 'B_typeFlexible', true)) || (get_user_meta($user_id_customer, 'B_typeFlexible', true) > 0)) {
						echo '(+' . get_user_meta($user_id_customer, 'B_typeFlexible', true) . '%)';
					} else {
						echo '(+' . get_post_meta(1, 'B_typeFlexible', true) . '%)';
					}
				}
				echo '</strong>';
				echo '<br>';
			}
			if ($key == 'c') {
				if (!empty(get_post_meta($product_id, 'property_c' . $i, true))) {
					echo 'CPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_c' . $i, true) . '</strong><br>';
				}
				if (!empty(get_post_meta($product_id, 'property_c_buildout' . $i, true))) {
					echo 'CPost Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_c_buildout' . $i, true) . '</strong>';
					$cbuilout++;
					if ($cbuilout == 1) {
						echo '(+7%)';
					}
				}
				//echo 'c'.$i.': '.get_post_meta( $product_id, 'property_c'.$i, true ).'<br> ';
			} elseif ($key == 't') {
				if (!empty(get_post_meta($product_id, 'property_t' . $i, true))) {
					echo 'TPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_t' . $i, true) . '</strong><br>';
				}
				if (!empty(get_post_meta($product_id, 'property_t_buildout' . $i, true))) {
					echo 'TPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_t_buildout' . $i, true) . '</strong>';
					$tbuilout++;
					if ($tbuilout == 1) {
						echo '(+7%)';
					}
					echo "<br>";
				}

				//echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
			} elseif ($key == 'g') {
				if (!empty(get_post_meta($product_id, 'property_g' . $i, true))) {
					echo 'GPosts' . $i . ': <strong>' . get_post_meta($product_id, 'property_g' . $i, true) . '</strong><br>';
				}
				if (!empty(get_post_meta($product_id, 'property_g_buildout' . $i, true))) {
					echo 'GPosts Buildout' . $i . ': <strong>' . get_post_meta($product_id, 'property_g_buildout' . $i, true) . '</strong>';
					$tbuilout++;
					if ($tbuilout == 1) {
						echo '(+7%)';
					}
					echo "<br>";
				}

				//echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
			}
		}
	} ?>
	<?php if ($property_sparelouvres == 'Yes') { ?> Include 2 x Spare Louvre:
    <strong>
			<?php echo $property_sparelouvres; ?></strong> (+2£)
    <br>
	<?php } ?>
	<?php if ($property_ringpull == 'Yes') { ?> Ring Pull:
    <strong>
			<?php echo $property_ringpull; ?></strong> (+35£)
    <br>
    How many rings?:
    <strong>
			<?php echo $property_ringpull_volume; ?></strong>
    <br>
	<?php } ?>
	<?php if ($property_locks == 'Yes') { ?> Locks:
    <strong>
			<?php echo $property_locks; ?></strong> (+35£)
    <br>
    How many locks?:
    <strong>
			<?php echo $property_locks_volume; ?></strong>
    <br>
	<?php } ?>
    Notes:
    <strong>
			<?php echo $comments_customer; ?></strong>
    </td>

    <td>£
			<?php

			// Calculate price

			if ($property_material == 187) {
				echo get_post_meta(1, 'Earth', true); /*teo Earth price*/
			}
			if ($property_material == 137) {
				echo get_post_meta(1, 'Green', true); /*teo Green price*/
			}
			if ($property_material == 138) {
				echo get_post_meta(1, 'BiowoodPlus', true); /*teo Biowood price*/
			}if ($property_material == 6) {
				echo get_post_meta(1, 'Biowood', true); /*teo Biowood price*/
			}
			if ($property_material == 139) {
				echo get_post_meta(1, 'Supreme', true); /*teo Supreme price*/
			}
			if ($property_material == 188) {
				echo get_post_meta(1, 'Ecowood', true); /*teo Supreme price*/
			}if ($property_material == 5) {
				echo get_post_meta(1, 'EcowoodPlus', true); /*teo Supreme price*/
			}
			?>

    </td>
    <td></td>
    <td>
      <strong>
				<?php echo number_format((double)$property_total, 2); ?> sq/m </strong>
      <br>
			<?php echo 'Qty:  <strong>' . $item_data['quantity']; ?></strong>
      <br> Width:<strong>
				<?php echo $property_width; ?></strong>
      <br> Height:<strong>
				<?php echo $property_height; ?></strong>
      <br>
    </td>
    <td>£
			<?php echo number_format($price, 2) * $item_data['quantity']; ?>
    </td>
    <td>

			<?php
			$pieces = explode("-", $title);
			$property_category;
			if (($property_category === 'Shutter') || ($pieces[0] == 'Shutter')) {
				?>
      <a class="btn btn-primary transparent" href="/product-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">
          Edit</a><?php
			} elseif ($property_category === 'Shutter & Blackout Blind') {
				?>
      <a class="btn btn-primary transparent" href="/product3-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">
          Edit</a><?php
			} elseif ($property_category === 'Batten') {
				?>
      <a class="btn btn-primary transparent" href="/product5-edit/?id=<?php echo $product_id * 1498765 * 33; ?>">
          Edit</a><?php
			}

			?>

			<?php echo do_shortcode('[duplicate_prod prod_id="' . $product_id . '"]'); ?>
      <br>
			<?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
				'<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
				esc_url(wc_get_cart_remove_url($item_id)),
				__('Remove this item', 'woocommerce'),
				esc_attr($product_id),
				esc_attr($_product->get_sku())
			), $item_id);

			// echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
			// 	'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">X</a>',
			// 	esc_url( wc_get_cart_remove_url( $item_id ) ),
			// 	__( 'Remove this item', 'woocommerce' ),
			// 	esc_attr( $product_id ),
			// 	esc_attr( $_product->get_sku() )
			// ), $cart_item_key );
			?>
    </td>
    </tr>
	<?php }
	} ?>
    </tbody>
    </table>

  </div>

<?php

}

?>

  <br>

  <a href="/" class="btn btn-primary transparent"> Save for Later </a>
  <!--button class="btn btn-primary blue download"> Create CSV </button>
	< <a href="/checkout/" class="btn brn-default"> Continue </a> -->

  <div class="cart-collaterals">
		<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action('woocommerce_cart_collaterals');
		?>
  </div>

	<?php do_action('woocommerce_after_cart'); ?>

  <script>
      function download_csv(csv, filename) {
          var csvFile;
          var downloadLink;

          // CSV FILE
          csvFile = new Blob([csv], {
              type: "text/csv"
          });

          // Download link
          downloadLink = document.createElement("a");

          // File name
          downloadLink.download = filename;

          // We have to create a link to the file
          downloadLink.href = window.URL.createObjectURL(csvFile);

          //console.log(downloadLink.href);
          //jQuery('.url_down').attr('href',downloadLink.href);
          // Make sure that the link is not displayed
          downloadLink.style.display = "none";

          // Add the link to your DOM
          document.body.appendChild(downloadLink);

          // Lanzamos
          downloadLink.click();
      }

      function export_table_to_csv(html, filename) {
          var csv = [];
          /*teo
													var rows = document.querySelectorAll("table#example tr");

													for (var i = 0; i < rows.length; i++) {
															var row = [],
																	cols = rows[i].querySelectorAll("td, th");

															for (var j = 0; j < cols.length; j++)
																	row.push(cols[j].innerText);

															csv.push(row.join(","));
													}
					*/
          // Download CSV
          download_csv(csv.join("\n"), filename);

          console.log(csv);
          console.log(filename);
      }


      document.querySelector("button.download").addEventListener("click", function (e) {
          e.preventDefault();
          var html = document.querySelector("table#example").outerHTML;
          export_table_to_csv(html, "table-order.csv");

          //console.log(html);

      });
  </script>
