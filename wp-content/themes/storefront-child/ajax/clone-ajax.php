<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.
    'wp-load.php');

print_r($_POST);
$product_id = $_POST['prod_id'];
$name = $_POST['name'];

$product_attributes = array();


$nr_code_prod = array();

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

$property_room_other = get_post_meta($product_id,'property_room_other',true);
$property_style = get_post_meta($product_id,'property_style',true); 
$property_frametype = get_post_meta($product_id,'property_frametype',true); 
$attachment = get_post_meta( $product_id, 'attachment', true );
$property_material = get_post_meta($product_id,'property_material',true);
$property_width = get_post_meta($product_id,'property_width',true);
$property_height = get_post_meta($product_id,'property_height',true);
$property_midrailheight = get_post_meta($product_id,'property_midrailheight',true);
$property_midrailheight2 = get_post_meta($product_id,'property_midrailheight2',true);
$property_midraildivider1 = get_post_meta($product_id,'property_midraildivider1',true);
$property_midraildivider2 = get_post_meta($product_id,'property_midraildivider2',true);
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
$property_sparelouvres = get_post_meta($product_id,'property_sparelouvres',true);
$price = get_post_meta($product_id, '_price', true);
$regular_price = get_post_meta($product_id, '_regular_price',true);
$sale_price = get_post_meta($product_id, '_sale_price', true);
$svg_product = get_post_meta($product_id, 'svg_product', true);
$comments_customer = get_post_meta($product_id, 'comments_customer', true);
$attachment = get_post_meta( $product_id, 'attachment', true );
$property_total = get_post_meta($product_id, 'property_total', true );
$property_volume = get_post_meta($product_id, 'property_volume', true );
$property_quantity = get_post_meta($product_id, 'quantity', true );

$product_attributes['property style'] = $property_style;
$product_attributes['property frametype'] = $property_frametype;
$product_attributes['property material'] = $property_material;
$product_attributes['property width'] = $property_width;
$product_attributes['property height'] = $property_height;
$product_attributes['property midrailheight'] = $property_midrailheight;
$product_attributes['property midrailheight 2'] = $property_midrailheight2;
$product_attributes['property midraildivider'] = $property_midraildivider1;
$product_attributes['property midraildivider 2'] = $property_midraildivider2;
$product_attributes['property totheight'] = $property_totheight;
$product_attributes['property horizontaltpost'] = $property_horizontaltpost;
$product_attributes['property bladesize'] = $property_bladesize;
$product_attributes['property fit'] = $property_fit;
$product_attributes['property frameleft'] = $property_frameleft;
$product_attributes['property frameright'] = $property_frameright;
$product_attributes['property frametop'] = $property_frametop;
$product_attributes['property framebottom'] = $property_framebottom;
$product_attributes['property builtout'] = $property_builtout;
$product_attributes['property hingecolour'] = $property_hingecolour;
$product_attributes['property shuttercolour'] = $property_shuttercolour;
$product_attributes['property shuttercolour other'] = $property_shuttercolour_other;
$product_attributes['property controltype'] = $property_controltype;
$product_attributes['property controlsplitheight'] = $property_controlsplitheight;
$product_attributes['property controlsplitheight2'] = $property_controlsplitheight2;
$product_attributes['property layoutcode'] = $property_layoutcode;
$product_attributes['property t1'] = $property_t1;
$product_attributes['property sparelouvres'] = $property_sparelouvres;





$user_id = get_current_user_id();


$post = array(
    'post_author' => $user_id,
    'post_content' => '',
    'post_status' => "publish",
    'post_title' => 'Shutter-'.$name,
    'post_parent' => '',
    'post_type' => "product",
);

//Create post product

$post_id = wp_insert_post( $post, $wp_error );


wp_set_object_terms($post_id, 'simple', 'product_type');

update_post_meta($post_id, '_visibility', 'visible');
update_post_meta($post_id, '_stock_status', 'instock');

update_post_meta($post_id, '_purchase_note', "");
update_post_meta($post_id, '_featured', "no");
update_post_meta($post_id, '_sku', "");
update_post_meta($post_id, '_sale_price_dates_from', "");
update_post_meta($post_id, '_sale_price_dates_to', "");

update_post_meta($post_id, '_sold_individually', "");
update_post_meta($post_id, '_manage_stock', "no");
update_post_meta($post_id, '_backorders', "no");
update_post_meta($post_id, '_stock', "");

update_post_meta($post_id,'property_room_other',$name);
update_post_meta($post_id,'property_style',$property_style); 
update_post_meta($post_id,'property_frametype',$property_frametype); 
update_post_meta($post_id, 'attachment', $attachment );
update_post_meta($post_id,'property_material',$property_material);
update_post_meta($post_id,'property_width',$property_width);
update_post_meta($post_id,'property_height',$property_height);
update_post_meta($post_id,'property_midrailheight',$property_midrailheight);
update_post_meta($post_id,'property_midrailheight2',$property_midrailheight2);
update_post_meta($post_id,'property_midraildivider1',$property_midraildivider1);
update_post_meta($post_id,'property_midraildivider2',$property_midraildivider2);
update_post_meta($post_id,'property_totheight',$property_totheight);
update_post_meta($post_id,'property_horizontaltpost',$property_horizontaltpost);
update_post_meta($post_id,'property_bladesize',$property_bladesize);
update_post_meta($post_id,'property_fit',$property_fit);
update_post_meta($post_id,'property_frameleft',$property_frameleft);
update_post_meta($post_id,'property_frameright',$property_frameright);
update_post_meta($post_id,'property_frametop',$property_frametop);
update_post_meta($post_id,'property_framebottom',$property_framebottom);
update_post_meta($post_id,'property_builtout',$property_builtout);
update_post_meta($post_id,'property_stile',$property_stile);
update_post_meta($post_id,'property_hingecolour',$property_hingecolour);
update_post_meta($post_id,'property_shuttercolour',$property_shuttercolour);
update_post_meta($post_id,'property_shuttercolour_other',$property_shuttercolour_other);
update_post_meta($post_id,'property_controltype',$property_controltype);
update_post_meta($post_id,'property_controlsplitheight',$property_controlsplitheight);
update_post_meta($post_id,'property_controlsplitheight2',$property_controlsplitheight2);
update_post_meta($post_id,'property_layoutcode',$property_layoutcode);
update_post_meta($post_id,'property_t1',$property_t1);
update_post_meta($post_id,'property_sparelouvres',$property_sparelouvres);
update_post_meta($post_id, 'comments_customer', $comments_customer);
update_post_meta($post_id, 'attachment', $attachment );
update_post_meta($post_id, 'property_volume', $property_volume );



update_post_meta($post_id,'_price',$price);
update_post_meta($post_id,'_regular_price',$regular_price);
update_post_meta($post_id,'_sale_price',$sale_price);
// update_post_meta($post_id,'svg_product',$svg_product);
update_post_meta($post_id, 'counter_c', $nr_c);
update_post_meta($post_id, 'counter_b', $nr_b);
update_post_meta($post_id, 'counter_t', $nr_t);

$unghi = 0;
                    foreach($nr_code_prod as $key => $val){ 
                        for( $i=1 ; $i < $val+1 ; $i++){
                            if($key == 'b'){
                                if(!empty(get_post_meta( $product_id, 'property_bp'.$i, true ))){
                                    $bp = get_post_meta( $product_id, 'property_bp'.$i, true );
                                    $ba = get_post_meta( $product_id, 'property_ba'.$i, true );
                                    update_post_meta($post_id,'property_bp'.$i,$bp);
                                    update_post_meta($post_id,'property_ba'.$i,$ba);
                                }
                            }
                            if($key == 'c'){
                                $c = get_post_meta( $product_id, 'property_c'.$i, true );
                                update_post_meta($post_id,'property_c'.$i,$c);
                            }
                            elseif($key == 't'){
                                $t = get_post_meta( $product_id, 'property_t'.$i, true );
                                update_post_meta($post_id,'property_t'.$i,$t);
                            }
                        }
                    }


update_post_meta($post_id, 'property_total', $property_total );
// update_post_meta($post_id, '_product_attributes', $product_attributes);


global $woocommerce;
$woocommerce->cart->add_to_cart( $post_id );

$prod_unique_id = $woocommerce->cart->generate_cart_id( $post_id );
// Remove it from the cart by un-setting it
unset( $woocommerce->cart->cart_contents[$prod_unique_id] );
$woocommerce->cart->add_to_cart( $post_id, $property_quantity );