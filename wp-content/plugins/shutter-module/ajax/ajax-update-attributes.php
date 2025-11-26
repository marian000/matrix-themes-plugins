<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.'wp-load.php');


//print_r($_POST);
parse_str($_POST['attributes'], $attributes);
//print_r($attributes); // Only for print array

global $wpdb;
$table_name = $wpdb->prefix . "shutter_attributes"; 

foreach( $attributes as $key => $val ){
    if( $key != 'shutter-table-settings_length' ){
    //echo 'key: '.$key;

    $part = explode("-", $key);
     $part[0]; // col name
     $part[1]; // id


    //echo ' - val: '.$val; 

    if(!empty($val)){
        $wpdb->update( 
            $table_name, 
            array( 
                $part[0] => $val
            ), 
            array( 'ID' => $part[1] )
        );

        echo ' Insert: '.$part[0].' - '.$part[1];
    }
    else { echo ' no insert '; }

    }
}

$all_atributes = $wpdb->get_results( "SELECT `id`, `name` FROM `wp_shutter_attributes` WHERE `name` != '' " );


//$array = get_object_vars($all_atributes);
$array = json_decode(json_encode($all_atributes), true);
//print_r($array);   

$new_attrs = array();
foreach( $array as $val ){
    $new_attrs[$val['id']] = $val['name'];
}
echo "<br><hr>";
print_r($new_attrs);
// save in meta new attrs for edit product
update_post_meta(1,'attributes_array',$new_attrs);

