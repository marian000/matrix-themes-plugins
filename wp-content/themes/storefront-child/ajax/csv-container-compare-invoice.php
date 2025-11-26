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
$csv_LFs_array = array();
$csv_LFRs_array = array();
$row = 0;
$total_sqm = 0;
$sqm_order = 0;
$new_line_order = false;
$order_name = '';
$order_id = 0;
$i = 0;
$extra_price = 0;
$customer_order = '';
$blackout_index = 0;
$blackout_nr = 0;

if (!empty($container_id_reset)) {
    delete_post_meta($container_id_reset, 'csv_upload_path_invoice');
}

if (!empty($csv_path)) {
    // ------------------------------------------
    // cand idenifica prima coloana cu denumire plina calculeaza sqm_order = sqm_order + data[4]
    // ------------------------------------------
    if (($handle = fopen($csv_path, "r")) !== FALSE) {
        // cand idenifica prima coloana cu denumire plina calculeaza amount_order = amount_order + data[4]
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row != 0) {
                echo '======================================';
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                // ------------------------------------------
                // if line have column with amount number and not text add total amount
                // ------------------------------------------

                if ($data[10] != 'amount' && $data[2] != 'Total') {
                    if ($data[0] != '') {
                        $i = 0;
                        $order_name = $data[0];
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
                        if (strpos($customer_order, ' ') !== false) {
                            echo "Word Found!";
                            $piecess = explode(" ", $customer_order);
                            $customer_order = $piecess[0];
                        } else {
                            echo "Word Not Found!";
                        }

                        global $wpdb;
                        $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_order_number' AND meta_value = $customer_order ", ARRAY_A);

                        $order_id = $results[0]['post_id'];
                        echo ' <pre> ORDER ID: ';
                        print_r($results[0]['post_id']);
                        echo '</pre>';

//                    for ($c = 0; $c < $num; $c++) {
//                        echo $data[$c] . "<br />\n";
//                    }
                        echo '<pre>';
                        print_r($data);
                        echo '</pre>';
                        if (empty($data[10])) {
                            $amount_order = 0;
                        } else {
                            $amount_order = $data[10];
                        }
                        // daca order are row fara numar item atunci e batten sau cofigurare
                        // cand data 1 este gol atunci dacem suma de extra_price
                        echo "<p>$order_name have $amount_order amount</p>";
                        if ($data[2] == 'Batten') {
                            $i++;
                            $csv_orders_array[$order_id]['batten-' . $i] = floatval($amount_order);

                        }
                        if ($data[2] == 'Bo blinds') {
                            $blackout_nr++;
//                                $csv_orders_array[$order_id]['Bo_blinds-'.$blackout_nr] = number_format($amount_order, 2);
                            $ammount = $csv_orders_array[$order_id][$blackout_index];
                            $csv_orders_array[$order_id][$blackout_index] = number_format($amount_order, 2) + $ammount;

                        } else {
                            $blackout_nr = 0;
                            $blackout_index = $data[1];
                            $csv_orders_array[$order_id][$data[1]] = floatval($amount_order);
                        }

                        if ($type_repair) {
                            $csv_LFRs_array[$order_id]['total'] += $amount_order;
                        } else {
                            $csv_LFs_array[$order_id]['total'] += $amount_order;
                        }
                    } else {

                        $amount_order = $data[10];
                        echo "<p>$order_name have $amount_order amount</p>";
//                            if($data[2] === 'Batten'){
//                                $csv_orders_array[$order_id]['Batten'][] = number_format($amount_order, 2);
//                            }else{
//                                $csv_orders_array[$order_id][$data[1]] = number_format($amount_order, 2);
//                            }
                        if ($data[2] == 'Batten') {
                            $i++;
                            $csv_orders_array[$order_id]['batten-' . $i] = floatval($amount_order);

                        }
                        if ($data[2] == 'Bo blinds') {
                            $blackout_nr++;
//                                $csv_orders_array[$order_id]['Bo_blinds-'.$blackout_nr] = number_format($amount_order, 2);
                            $ammount = $csv_orders_array[$order_id][$blackout_index];
                            $csv_orders_array[$order_id][$blackout_index] = floatval($amount_order) + $ammount;

                        } else {
                            $blackout_nr = 0;
                            $blackout_index = $data[1];
                            $csv_orders_array[$order_id][$data[1]] = floatval($amount_order);
                        }

                        if ($type_repair) {
                            $csv_LFRs_array[$order_id]['total'] += $amount_order;
                        } else {
                            $csv_LFs_array[$order_id]['total'] += $amount_order;
                        }

                        echo '<pre>';
                        print_r($data);
                        echo '</pre>';
                    }
                }
                echo '======================================';
            }
            $row++;
        }
        update_post_meta($container_id, 'csv_orders_invoice_array', $csv_orders_array);
        update_post_meta($container_id, 'csv_invoice_read', 'yes');

        update_post_meta($container_id, 'csv_LFRs_array', $csv_LFRs_array);
        update_post_meta($container_id, 'csv_LFs_array', $csv_LFs_array);
        fclose($handle);
    }

}
print_r($csv_orders_array);
