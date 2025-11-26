<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.'wp-load.php');


//print_r($_POST);
parse_str($_POST['attributes'], $attributes);
print_r($attributes); // Only for print array




foreach( $attributes as $key => $val ){
    if( $key != 'shutter-table-settings_length' ){
    //echo 'key: '.$key;

    $part = explode("-", $key);
    $part[0]; // dolar_price
    $part[1]; // property

    update_post_meta( 1,$part[1].'-dolar',$val );


    }
}

update_post_meta(1, 'dolar_price-panel_width_price', $attributes['dolar_price-panel_width_price']);




