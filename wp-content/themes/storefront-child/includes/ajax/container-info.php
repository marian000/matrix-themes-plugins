<?php
//    1. In GBP din ordere:
//    - suma itemurilor comandate
//    - suma totală pe by train
//    - suma totala pe Delivery local
//    - suma totală pe container(fără tva)
//    - din sumele de mai sus se exclud orderele puse de Mike dar se afiseaza separat
//
//    2. In USD din CSV-ul factură (al 2-lea):
//    - suma itemurilor din factură, inclusiv LFR-uri, dar fără cele puse de Mike
//    - suma orderelor puse de Mike se afiseaza separat
//    - suma LFR-urilor se afiseaza separat


$container_id = $_POST['container_id'];
$info = $_POST;
print_r($_POST);

$container_orders = get_post_meta($container_id, 'container_orders', true);
//print_r($container_orders);
arsort($container_orders);
//print_r($container_orders);
$csv_orders_array = get_post_meta($container_id, 'csv_orders_array', true);
$csv_orders_array_cartons = get_post_meta($container_id, 'csv_orders_array_cartons', true);
$csv_orders_array_repair = get_post_meta($container_id, 'csv_orders_array_repair', true);
$csv_orders_array_cartons_repair = get_post_meta($container_id, 'csv_orders_array_cartons_repair', true);

$csv_orders_array_frames_repair = get_post_meta($container_id, 'csv_orders_array_frames_repair', true);
$csv_orders_array_panels_repair = get_post_meta($container_id, 'csv_orders_array_panels_repair', true);


if ($container_orders) {

    $sum_dolar_conatainer = 0;
    $sum_conatainer = 0;
    $csv_matrix_orders = array();
    $repair_matrix_orders = array();
    // print_r($container_orders);
    foreach ($container_orders as $order_id) {
        if (get_post_type($order_id) == 'order_repair') {
            $repair_matrix_orders[] = $order_id;

            $order_original = get_post_meta($order_id, 'order-id-original', true);
            $order = wc_get_order($order_original);
            if (!empty($csv_orders_array) && !array_key_exists($order_original, $csv_orders_array)) {
                $csv_matrix_orders[$order_original] = '0';
            }
        } elseif(get_post_type($order_id) == 'shop_order') {
            $order = wc_get_order($order_id);
            if (!empty($csv_orders_array) && !array_key_exists($order_id, $csv_orders_array)) {
                $csv_matrix_orders[$order_id] = '0';
            }

            $full_payment = 0;
            $user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
            //echo 'USER ID '.$user_id;

            $i = 0;
            $atributes = get_post_meta(1, 'attributes_array', true);

        }
    }
}
wp_die();