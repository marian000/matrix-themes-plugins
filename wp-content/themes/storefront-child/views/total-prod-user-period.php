<form action="" method="POST" style="display:block;">
    <div class="row">


        <div class="col-sm-3 col-lg-3 form-group">
            <br>
            <?php
            $from2 = (isset($_POST['mishaDateFrom'])) ? $_POST['mishaDateFrom'] : '';
            $to2 = (isset($_POST['mishaDateTo'])) ? $_POST['mishaDateTo'] : '';
            ?>
            <input type="text" name="mishaDateFrom" placeholder="Date From"
                   value="<?php echo $from; ?>"/>
            <input type="text" name="mishaDateTo" placeholder="Date To"
                   value="<?php echo $to; ?>"/>
            <style>
                input[name="mishaDateFrom"], input[name="mishaDateTo"] {
                    line-height: 28px;
                    height: 28px;
                    margin: 0;
                    width: 125px;
                }
            </style>
        </div>
        <div class="col-sm-3 col-lg-3 form-group">
            <br>
            <input type="submit" name="interval" value="Interval Selection" class="btn btn-info">
        </div>
    </div>

</form>

<?php
$users_total_batten = array();
$orders_prices = array();

//foreach ($customers as $user) {
$args = array(
    // 'customer_id' => $user->id,
//    'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
    'date_query' => array(
        'after' => date('Y-m-d', strtotime($from2)),
        'before' => date('Y-m-d', strtotime($to2))
    ),
);

$orders = wc_get_orders($args);

$total_price_battens = 0;

$total_orders = 0;
foreach ($orders as $order) {

    $id_order = $order->get_id();
    $order_data = $order->get_data();
    $order_status = $order_data['status'];

    $items = $order->get_items();

//        $total_regulat_price = 0;
//
//        foreach ($items as $item_id => $item_data) {
//            $product_id = $item_data['product_id'];
//            $property_category = get_post_meta($product_id, 'shutter_category', true);
//            if ($property_category == 'Batten') {
//                $price = get_post_meta($product_id, '_price', true);
//                $total_price_battens += $price;
//            }
//
//            $product = wc_get_product( $product_id );
//
//            $total_regulat_price =+ $product->get_regular_price();
//        }

    $orders_prices[$id_order] = array('price_gbp' => $order->get_total(), 'status' => $order_status, 'shipping' => $order_data['shipping_total']);


    echo '<p><b>'. $total_orders . ' +  ' . $order->get_total() . '</b></p>';
    $total_orders = $total_orders + $order->get_total();
    echo '<p>Wp table order Price: <b>'. $total_orders . ' GBP - ' . $id_order . '</b></p>';
}


$user_info = get_userdata($user->id);
$user_name = $user_info->first_name . ' ' . $user_info->last_name;
//    echo '<h2>User: ' . $user_name . ' have Battens price: ' . $total_price_battens . 'GBP between ' . $from2 . ' - ' . $to2 . '<h2>';

if ($total_price_battens > 0) {
    $users_total_batten[$user_name] = $total_price_battens;
}
//}
arsort($users_total_batten);
$total_users_price = 0;
//foreach ($users_total_batten as $user_name => $total_price_battens) {
//    echo '<p>User: <b>' . $user_name . '</b> have Battens price: <b>' . $total_price_battens . ' GBP</b> between ' . $from2 . ' - ' . $to2 . '</p>';
//    $total_users_price += $total_price_battens;
//
//}
//echo '<hr><p>Total Users: <b>'.$total_users_price.' GBP</b></p>';


global $wpdb;
$tablename = $wpdb->prefix . 'custom_orders';
$myOrders = $wpdb->get_results("SELECT idOrder, reference, gbp_price, subtotal_price, status, shipping_cost FROM $tablename WHERE createTime >= '2020-11-01 00:00:01' AND createTime <= '2020-11-30 23:59:00' ORDER by id DESC", ARRAY_A);


//echo '<pre>';
//print_r($myOrders);
//echo '</pre>';
//foreach($orders_prices as){
$total_ordesr_custom = 0;

$sql_orders = array();
foreach ($myOrders as $i => $order) {
    $sql_orders[$order['idOrder']] = $order;
}
echo '<hr>Count Sql orders ';
print_r(count($sql_orders));
echo '<hr>Count WP orders ';
print_r(count($orders_prices));
echo '<hr>';

foreach ($orders_prices as $order_id => $order_data) {
    if (array_key_exists($order_id, $sql_orders)) {

    }
    else {
        echo '<hr><p>Orderul nu este existent in tabela custom: <b>' . $order_id . '  -  ' . $order_data['price_gbp'] . 'GBP</b></p><hr>';
    }
}

foreach ($myOrders as $i => $order) {
    if (array_key_exists($order['idOrder'], $orders_prices)) {
        $order_o = $orders_prices[$order['idOrder']];
//        echo '<p>Wp table order Id: <b>' . $order['idOrder'] . '</b> have total price: <b>' . $order_o['price_gbp'] . ' GBP</b> and custom table: ' . $order['gbp_price'] . ' GBP - ' . $order['idOrder'] . '</p>';
//        echo '<p>Wp table order Id: <b>' . $order['idOrder'] . '</b> have shipping price: <b>' . $order_o['shipping'] . ' GBP</b> and custom table: ' . $order['shipping_cost'] . ' GBP - ' . '</p>';
//        if ($order_o['price_gbp'] != $order['gbp_price']) {
//            $diff = $order_o['price_gbp'] - $order['gbp_price'];
//            echo '<hr><p>S-AU GASIT DIFERENTE: <b>' . $diff . ' GBP - ' . $order['idOrder'] . '</b></p>';
//        }
//        if ($order_o['status'] != $order['status']) {
//            echo '<hr><p>S-AU GASIT DIFERENTE STATUS: <b>' . $order_o['status'] . '</b> status - <b>' . $order['status'] . '</b> orderId - <b>' . $order['idOrder'] . '</b></p>';
//        }
//
//        if ($order_o['shipping'] != $order['shipping_cost']) {
//            echo '<hr><p>S-AU GASIT DIFERENTE Shipping Suma: <b>' . $order_o['shipping'] . '</b> status - <b>' . $order['shipping_cost'] . '</b> orderId - <b>' . $order['idOrder'] . '</b></p>';
//        }


//        if (!empty($order_o['price_gbp'])) {
//            $total_orders = $total_orders + $order_o['price_gbp'];
//        }
    } else {
        echo '<hr><p>Orderul nu este existent: <b>' . $order['idOrder'] . '  -  ' . $order['reference'] . '  -  ' . $order['gbp_price'] . 'GBP</b></p><hr>';
    }


    echo '<p><b>'. $total_ordesr_custom . ' +  ' . floatval($order['gbp_price']) . '</b></p>';
    $total_ordesr_custom = $total_ordesr_custom + floatval($order['gbp_price']);
    echo '<p>Wp table order Price: <b>'. $total_ordesr_custom . ' GBP - ' . $order['idOrder'] . '</b></p>';
}

echo '<hr><p>Total Orders WP: <b>' . $total_orders . '  - Total Orders Custom Table ' . $total_ordesr_custom . '</b></p><hr>';
//}

//foreach ($orders_prices as $id_order => $total_regulat_price){
//
//    global $wpdb;
//    $tablename = $wpdb->prefix . 'custom_orders';
//    $myOrder = $wpdb->get_row("SELECT idOrder, gbp_price, subtotal_price FROM $tablename WHERE idOrder = $id_order");
//    echo '<p>Wp table order Id: <b>' . $id_order . '</b> have total price: <b>' . $total_regulat_price . ' GBP</b> and custom table: ' . $myOrder->gbp_price . ' GBP - ' . $myOrder->idOrder . '</p>';
//    if($total_regulat_price != $myOrder->gbp_price){
//        $diff = $total_regulat_price - $myOrder->gbp_price;
//        echo '<hr><p>S-AU GASIT DIFERENTE: <b>'. $diff .' GBP - '. $id_order .'</b></p>';
//    }
//}
?>


