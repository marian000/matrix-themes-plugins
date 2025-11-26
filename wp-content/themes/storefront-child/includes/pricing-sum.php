

<?php	wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>











<?php


$i = 0;
$atributes = get_post_meta(1,'attributes_array',true);


$nr_code_prod = array();
foreach ( WC()->cart->get_cart() as $item_id => $item_data ) {
    $i++;
   // $product = $item_data->get_product();
    $product_id = $item_data['product_id'];


    $nr_t = get_post_meta($product_id, 'counter_t', true);
    $nr_b = get_post_meta($product_id, 'counter_b', true);
    $nr_c = get_post_meta($product_id, 'counter_c', true);

    if(!empty($nr_code_prod)){
        if( $nr_code_prod['t'] < $nr_t ){
            $nr_code_prod['t'] = $nr_t;
        }
        if( $nr_code_prod['b'] < $nr_b ){
            $nr_code_prod['b'] = $nr_b;
        }
        if( $nr_code_prod['c'] < $nr_c ){
            $nr_code_prod['c'] = $nr_c;
        }
    }
    else{
        $nr_code_prod['t'] = $nr_t;
        $nr_code_prod['b'] = $nr_b;
        $nr_code_prod['c'] = $nr_c;
    }


}

?>

<div class="order-summary-table"  style="overflow: auto;">
<table id="example" style="width:100%" class="table table-striped">
        <thead>
            <tr>
                <!-- <th>item</th> -->
                
                <th>Item</th>
                <th></th>
                <th></th>
				<th></th>
                <th>Quantity</th>
                <th>Total</th>

            </tr>
        </thead>
        <tbody>
        <?php
        $array_att = array();
        foreach ( WC()->cart->get_cart() as $item_id => $item_data ) {
            $j++;
            //$product = $item_data->get_product();
            $product_id = $item_data['product_id'];

            $property_room_other = get_post_meta($product_id,'property_room_other',true);
$property_style = get_post_meta($product_id,'property_style',true); 
$property_frametype = get_post_meta($product_id,'property_frametype',true); 

echo $attachment = get_post_meta( $product_id, 'attachment', true );
$array_att[] = $attachment;

$property_material = get_post_meta($product_id,'property_material',true);
$property_width = get_post_meta($product_id,'property_width',true);
$property_height = get_post_meta($product_id,'property_height',true);
$property_midrailheight = get_post_meta($product_id,'property_midrailheight',true);
$property_midrailheight2 = get_post_meta($product_id,'property_midrailheight2',true);
$property_midraildivider1 = get_post_meta($product_id,'property_midraildivider1',true);
$property_midraildivider2 = get_post_meta($product_id,'property_midraildivider2',true);
$property_midrailpositioncritical = get_post_meta($product_id,'property_midrailpositioncritical',true);
$property_totheight = get_post_meta($product_id,'property_totheight',true);
$property_horizontaltpost = get_post_meta($product_id,'property_horizontaltpost',true);
$property_bladesize = get_post_meta($product_id,'property_bladesize',true);
$property_fit = get_post_meta($product_id,'property_fit',true);
$property_frameleft = get_post_meta($product_id,'property_frameleft',true);
$property_frameright = get_post_meta($product_id,'property_frameright',true);
$property_frametop = get_post_meta($product_id,'property_frametop',true);
$property_framebottom = get_post_meta($product_id,'property_framebottom',true);
$property_builtout = get_post_meta($product_id,'property_builtout',true);
$property_stile = get_post_meta($product_id,'property_stile',true);
$property_hingecolour = get_post_meta($product_id,'property_hingecolour',true);
$property_shuttercolour = get_post_meta($product_id,'property_shuttercolour',true);
$property_shuttercolour_other = get_post_meta($product_id,'property_shuttercolour_other',true);
$property_controltype = get_post_meta($product_id,'property_controltype',true);
$property_controlsplitheight = get_post_meta($product_id,'property_controlsplitheight',true);
$property_controlsplitheight2 = get_post_meta($product_id,'property_controlsplitheight2',true);
$property_layoutcode = get_post_meta($product_id,'property_layoutcode',true);
$property_t1 = get_post_meta($product_id,'property_t1',true);
$property_total = get_post_meta($product_id,'property_total',true);

$property_sparelouvres = get_post_meta($product_id,'property_sparelouvres',true);
$property_ringpull = get_post_meta($product_id,'property_ringpull',true);
$price = get_post_meta($product_id, '_price', true);
$regular_price = get_post_meta($product_id, '_regular_price',true);
$sale_price = get_post_meta($product_id, '_sale_price', true);
$svg_product = get_post_meta($product_id, 'svg_product', true);
$comments_customer = get_post_meta($product_id, 'comments_customer', true);




        ?>
            <tr>
                <!-- <td><?php // echo $j; ?></td> -->
                <td>Room: <?php echo $property_room_other; ?><br>
				material: <?php echo $atributes[$property_material]; ?><br>
                instalation style: <?php echo $atributes[$property_style]; ?><br>
				Shape: <?php 
				if(!empty($attachment)){	echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';		}
				else { echo 'no'; }
				?><br>
                
                Midrail Height: <?php echo $property_midrailheight; ?><br>

                <?php 
				if(!empty($property_midrailheigh2)){echo 'Midrail Height 2: '.$property_midrailheigh2.'<br>';		}
				?>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Midrail Divider 1: '.$property_midraildivider1.'<br>';		}
				?>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Midrail Divider 2: '.$property_midraildivider2.'<br>';		}
				?>
                Position is Critical: <?php echo $atributes[$property_midrailpositioncritical] ?><br>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'T-o-T Height: '.$property_totheight.'<br>';		}
				?>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Horizontal T Post: '.$property_horizontaltpost.'<br>';		}
				?>
                louver: <?php echo $atributes[$property_bladesize]; ?><br>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Fit: '.$atributes[$property_fit];		}
				?></td>
                <td>frame type: <?php echo $atributes[$property_frametype]; ?><br>
                stile type: <?php echo $atributes[$property_stile]; ?><br>
                Frame Left: <?php echo $atributes[$property_frameleft]; ?><br>
                Frame Right: <?php echo $atributes[$property_frameright]; ?><br>
                Frame Bottom: <?php echo $atributes[$property_frametop]; ?><br>
                Frame Top: <?php echo $atributes[$property_framebottom]; ?><br>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Buildout: '.$property_builtout;		}
				?></td>
                <td>Hinge Colour: <?php echo $atributes[$property_hingecolour]; ?><br>
                Shutter Colour: <?php echo $atributes[$property_shuttercolour]; ?><br>
                <?php 
				if(!empty($property_midrailheigh2)){echo 'Other Colour: '.$atributes[$property_shuttercolour_other].'<br>';		}
				?>
                Control Type: <?php echo $atributes[$property_controltype]; ?></div><br>
                Control Split Height: <?php echo $atributes[$property_controlsplitheight]; ?><br>
                Layout code: <?php echo $property_layoutcode; ?><br>
                <?php
                    foreach($nr_code_prod as $key => $val){ 
                        for( $i=1 ; $i < $val+1 ; $i++){
                            if($key == 'b'){
                                echo 'bp'.$i.': '.get_post_meta( $product_id, 'property_bp'.$i, true ).'<br> ';
                                echo 'ba'.$i.': '.get_post_meta( $product_id, 'property_ba'.$i, true ).'<br> ';
                            }
                            if($key == 'c'){
                                echo 'c'.$i.': '.get_post_meta( $product_id, 'property_c'.$i, true ).'<br> ';
                            }
                            else{
                                echo 't'.$i.': '.get_post_meta( $product_id, 'property_t'.$i, true ).'<br> ';
                            }
                        }
                    } ?>
                Include 2 x Spare Louvre: <?php echo $property_sparelouvres; ?><br>
                Ring Pull: <?php echo $property_ringpull; ?><br>
                notes: <?php echo $comments_customer; ?>
                </td>

                
                <td></td>
                <td>
                <strong><?php echo $property_total; ?> sq/m </strong><br>
                width: <?php echo $property_width; ?><br>
                height: <?php echo $property_height; ?><br></td>
                <td>£ <?php echo $price; ?></td>
            </tr>
        <?php } ?>
			</tbody>
    </table>

</div>









<div class="cart-collaterals pricing-summary">
    <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action( 'woocommerce_cart_collaterals' );
    ?>
</div>
<br>

<a href="/order-summary" class="btn brn-default" > Back </a>
<a href="/" class="btn brn-default" > Return to My( Progress) Orders </a>
<a href="/checkout" class="btn brn-default" > Covert to Order </a>

<?php //do_action( 'woocommerce_after_cart' ); 

