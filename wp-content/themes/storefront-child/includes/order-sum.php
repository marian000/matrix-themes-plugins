<?php
// Order Summary Table


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
                
                <th>Room</th>
				<th>material</th>
                <th>instalation style</th>
				<th>Shape</th>
                <th>qty</th>
                <th>sqm</th>
                <th>width</th>
                <th>height</th>
                <th>Midrail Height </th>
                <th>Midrail Height 2</th>
                <th>Hidden Divider 1 </th>
                <th>Hidden Divider 2 </th>
                <th>Position is Critical</th>
                <th>T-o-T Height</th>
                <th>Horizontal T Post </th>
                <th>louver</th>
                <th>Fit</th>
                <th>frame type</th>
                <th>stile type</th>
                <th>Frame Left</th>
                <th>Frame Right</th>
                <th>Frame Bottom</th>
                <th>Frame Left</th>
                <th>Buildout</th>
                <th>Hinge Colour</th>
                <th>Shutter Colour</th>
                <th>Other Colour</th>
                <th>Control Type</th>
                <th>Control Split Height</th>
                <th>Layout code</th>

            <?php
            foreach($nr_code_prod as $key => $val){ 
                for($i=1; $i < $val+1 ;$i++){
                    if($key == 'b'){
                        echo '<th>'.$key.'p '. $i .'</th>';
                        echo '<th>'.$key.'a '. $i .'</th>';
                    }
                    else{
                        echo '<th>'.$key.' '. $i .'</th>';
                    }
                }
           } ?>

                <th>Include 2 x Spare Louvre</th>
                <th>Ring Pull</th>
                <th>notes</th>
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
                
                <td><?php echo $property_room_other; ?></td>
				<td><?php echo $atributes[$property_material]; ?></td>
                <td><?php echo $atributes[$property_style]; ?></td>
				<td><?php 
				if(!empty($attachment)){	echo '<img src="/wp-content/uploads/2018/06/icons8-checkmark-26-1.png" alt="yes">';		}
				else { echo 'no'; }
				?></td>
                <td><?php echo $item_quantity; ?></td>
                <td><?php echo $property_total; ?></td>
                <td><?php echo $property_width; ?></td>
                <td><?php echo $property_height; ?></td>
                <td><?php echo $property_midrailheight; ?></td>
                <td><?php echo $property_midrailheigh2; ?></td>
                <td><?php echo $property_midraildivider1; ?></td>
                <td><?php echo $property_midraildivider2; ?></td>
                <td><?php echo $atributes[$property_midrailpositioncritical] ?></td>
                <td><?php echo $property_totheight; ?></td>
                <th><?php echo $property_horizontaltpost; ?></th>
                <td><?php echo $atributes[$property_bladesize]; ?></td>
                <td><?php echo $atributes[$property_fit]; ?></td>
                <td><?php echo $atributes[$property_frametype]; ?></td>
                <td><?php echo $atributes[$property_stile]; ?></td>
                <td><?php echo $atributes[$property_frameleft]; ?></td>
                <td><?php echo $atributes[$property_frameright]; ?></td>
                <td><?php echo $atributes[$property_frametop]; ?></td>
                <td><?php echo $atributes[$property_framebottom]; ?></td>
                <td><?php echo $property_builtout; ?></td>
                <td><?php echo $atributes[$property_hingecolour]; ?></td>
                <td><?php echo $atributes[$property_shuttercolour]; ?></td>
                <td><?php echo $atributes[$property_shuttercolour_other]; ?></td>
                <td><?php echo $atributes[$property_controltype]; ?></td>
                <td><?php echo $atributes[$property_controlsplitheight]; ?></td>
                <td style="text-transform: uppercase;"><?php echo $property_layoutcode; ?></td>
                <?php
                    foreach($nr_code_prod as $key => $val){ 
                        for( $i=1 ; $i < $val+1 ; $i++){
                            if($key == 'b'){
                                echo '<td>'.get_post_meta( $product_id, 'property_bp'.$i, true ).'</td>';
                                echo '<td>'.get_post_meta( $product_id, 'property_ba'.$i, true ).'</td>';
                            }
                            else{
                                echo '<td>'.get_post_meta( $product_id, 'property_t'.$i, true ).'</td>';
                            }
                        }
                    } ?>
                <td><?php echo $property_sparelouvres; ?></td>
                <td><?php echo $property_ringpull; ?></td>
                <td><?php echo $comments_customer; ?></td>
            </tr>
        <?php } ?>
			</tbody>
    </table>

</div>
<br>

	  <a href="/cart" class="btn brn-default" > Back </a>
	  <a href="" class="btn brn-default" > Create PDF </a>
	  <a href="/checkout/" class="btn brn-default" > Continue </a>


<br>