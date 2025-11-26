<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

/*
 *
 *  Basic functionality
 * - ger csv orders from china
 * - get orders id from csv and put in an array with structure arrau( ordr_id => sqm_order )
 * - after the CSV read we will make a verification with matrix platform container orders and csv_orders_array to see if SQM is equal or there are differences
 *
*/
$csv_path = $_POST['csv_path'];
$container_id = $_POST['container_id'];
$container_id_reset = $_POST['container_id_reset'];

$csv_orders_array = array();
$row = 0;
$total_sqm = 0;
$sqm_order = 0;
$cartons = 0;
$panels = 0;
$frames = 0;
$new_line_order = false;
$order_name = '';
$order_id = 0;

if (!empty($container_id_reset)) {
    delete_post_meta($container_id_reset, 'csv_upload_path');
}

if (!empty($csv_path)) {
    // ------------------------------------------
    // cand idenifica prima coloana cu denumire plina calculeaza sqm_order = sqm_order + data[4]
    // ------------------------------------------
    if (($handle = fopen($csv_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            print_r($data);
            if ($row != 0) {
                echo '======================================';
//                $num = count($data);
//                echo "<p> $num fields in line $row: <br /></p>\n";
                // ------------------------------------------
                // if line have column with sqm number and not text add total sqm
                // ------------------------------------------
                if ($data[4] != 'sqm') {
                    if ($data[0] != '') {
                        echo $order_name = $data[0];
                        // ------------------------------------------
                        // get order id from database
                        // with php explode function get order number by order reference meta_key (LF03530-PAUL RYAN-Castlecomer Blinds Factory Lt)
                        // ------------------------------------------
                        $pieces = explode("LF", $order_name);
                        $pieces[0]; // piece1
                        $order_ref = $pieces[1]; // piece2
                        $type_repair = false;

                        if (substr($order_ref, 0, 1) === 'R') {
                            $pieces = explode("R", $order_ref);
                            $order_ref = $pieces[1]; // piece2
                            $type_repair = true;
                        } elseif (substr($order_ref, 0, 1) === '0') {
                            $pieces = substr($order_ref, 1);
                            $order_ref = $pieces; // piece2
                            $type_repair = false;
                        }

                        $pieces = explode("-", $order_ref);
                        $customer_order = $pieces[0];

                        // Test if string contains the word
                        if(strpos($customer_order, ' ') !== false){
                            //echo "Word Found!";
                            $piecess = explode(" ", $customer_order);
                            $customer_order = $piecess[0];
                        } else{
                            //echo "Word Not Found!";
                        }

                        global $wpdb;
                        $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_order_number' AND meta_value = $customer_order ", ARRAY_A);

                        $order_id = $results[0]['post_id'];
//                        echo ' <pre> ORDER ID: ';
//                        print_r($results[0]['post_id']);
//                        echo '</pre>';

//                    for ($c = 0; $c < $num; $c++) {
//                        echo $data[$c] . "<br />\n";
//                    }
//                        echo '<pre>';
//                        print_r($data);
//                        echo '</pre>';
                        if (empty($data[4])) {
                            $sqm_order = 0;
                        } else {
                            $sqm_order = $data[4];
                        }
                        //echo "<p>$order_name have $sqm_order SQM</p>";
                        // calculate total cartoons
                        $cartons = $data[7] + $data[6];
                        $panels = $data[6];
                        $frames = $data[7];

                        if($type_repair){
                            $csv_orders_array_repair[$order_id] = number_format($sqm_order, 2);
                            $csv_orders_array_cartons_repair[$order_id] = $cartons;
                            $csv_orders_array_panels_repair[$order_id] = $panels;
                            $csv_orders_array_frames_repair[$order_id] = $frames;
                        }else{
                            $csv_orders_array[$order_id] = number_format($sqm_order, 2);
                            $csv_orders_array_cartons[$order_id] = $cartons;
                            $csv_orders_array_panels[$order_id] = $panels;
                            $csv_orders_array_frames[$order_id] = $frames;
                        }
//                        print_r($order_id);
//                        print_r($csv_orders_array_cartons);
                    } else {
                        $sqm_order = $sqm_order + $data[4];
                        //echo "<p>$order_name have $sqm_order SQM</p>";
                        // calculate total cartoons
                        $cartons = $cartons + $data[7] + $data[6];
                        $panels = $panels + $data[6];
                        $frames = $frames + $data[7];

                        if($type_repair){
                            $csv_orders_array_repair[$order_id] = number_format($sqm_order, 2);
                            $csv_orders_array_cartons_repair[$order_id] = $cartons;
                            $csv_orders_array_frames_repair[$order_id] = $frames;
                            $csv_orders_array_panels_repair[$order_id] = $panels;
                        }else{
                            $csv_orders_array[$order_id] = number_format($sqm_order, 2);
                            $csv_orders_array_cartons[$order_id] = $cartons;
                            $csv_orders_array_frames[$order_id] = $frames;
                            $csv_orders_array_panels[$order_id] = $panels;
                        }
                    }
                }
                echo '======================================';
            }
            $row++;
            update_post_meta($container_id, 'csv_orders_array', $csv_orders_array);
            update_post_meta($container_id, 'csv_orders_array_cartons', $csv_orders_array_cartons);
            update_post_meta($container_id, 'csv_orders_array_repair', $csv_orders_array_repair);
            update_post_meta($container_id, 'csv_orders_array_cartons_repair', $csv_orders_array_cartons_repair);

            update_post_meta($container_id, 'csv_orders_array_panels', $csv_orders_array_panels);
            update_post_meta($container_id, 'csv_orders_array_frames', $csv_orders_array_frames);
            update_post_meta($container_id, 'csv_orders_array_panels_repair', $csv_orders_array_panels_repair);
            update_post_meta($container_id, 'csv_orders_array_frames_repair', $csv_orders_array_frames_repair);
        }
        update_post_meta($container_id, 'csv_pack_read', 'yes');
        fclose($handle);
    }
}