<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.'wp-load.php');

global $wpdb;

$all_atributes = $wpdb->get_results( "SELECT * FROM `wp_property_values`" );

//print_r( json_encode($all_atributes) );
//echo json_encode($all_atributes);     - afisare buna
//print_r( $all_atributes );
$new_attr = array();
$i=0;
foreach($all_atributes as $attrs){
    //print_r($attrs);
    $new_attr[$i]['id'] = json_decode($attrs->id);
    $new_attr[$i]['property_id'] = json_decode($attrs->property_id);
    $new_attr[$i]['value'] = $attrs->value;
    $new_attr[$i]['created_at'] = $attrs->created_at;
    $new_attr[$i]['updated_at'] = $attrs->updated_at;
    $new_attr[$i]['code'] = $attrs->code;
    $new_attr[$i]['uplift'] = $attrs->uplift;
    $new_attr[$i]['color'] = $attrs->color;
    $new_attr[$i]['all_products'] = json_decode($attrs->all_products);
    $new_attr[$i]['selected_products'] = $attrs->selected_products;
    $new_attr[$i]['all_property_values'] = json_decode($attrs->all_property_values);
    $new_attr[$i]['selected_property_values'] = $attrs->selected_property_values;
    $new_attr[$i]['graphic'] = $attrs->graphic;
    $new_attr[$i]['image_file_name'] = $attrs->image_file_name;
    $new_attr[$i]['image_content_type'] = $attrs->image_content_type;
    $new_attr[$i]['image_file_size'] = $attrs->image_file_size;
    $new_attr[$i]['image_updated_at'] = $attrs->image_updated_at;
    $new_attr[$i]['is_active'] = json_decode($attrs->is_active);
    $new_attr[$i]['property'] = json_decode($attrs->property);
    $i++;
}
echo json_encode($new_attr);

//array( 
    //         'id' => $values['id'],
    //         'property_id' => $values['property_id'], 
    //         'value' => $values['value'], 
    //         'created_at' => current_time( 'mysql' ),
    //         'updated_at' => current_time( 'mysql' ),
    //         'code' => $values['code'], 
    //         'uplift' => $values['uplift'], 
    //         'color' => $values['color'], 
    //         'all_products' => $values['all_products'], 
    //         'selected_products' => $values['selected_products'], 
    //         'all_property_values' => $values['all_property_values'], 
    //         'selected_property_values' => $values['selected_property_values'], 
    //         'graphic' => $values['graphic'], 
    //         'image_file_name' => $values['image_file_name'], 
    //         'image_content_type' => $values['image_content_type'], 
    //         'image_file_size' => $values['image_file_size'], 
    //         'image_updated_at' => current_time( 'mysql' ),
    //         'is_active' => $values['is_active'], 
    //         'property' => $prop, 
    //     )