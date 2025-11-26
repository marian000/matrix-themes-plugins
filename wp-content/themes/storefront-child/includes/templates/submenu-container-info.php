<?php

echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
echo '<h2>My Custom Submenu Page Container</h2>';


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


$args = array(
    'posts_per_page' => -1,
    'post_type' => 'container',
    'post_status' => 'any',
    'fields' => 'ids'
);

$containers = get_posts($args);

?>
    <form action="" method="POST" style="display:block;">
        <div class="col-sm-4 col-lg-4 form-group">
            <label for="select-luna">Select Container:</label>
            <br>
            <select id="select-container" name="container">
                <?php
                foreach ($containers as $key => $container_id) { ?>
                    <option value="<?php echo $container_id; ?>" <?php if ($_POST['container'] == $container_id) echo 'selected'; ?>><?php echo get_the_title($container_id); ?></option>
                <?php } ?>
            </select>

            <input type="submit"
                   name="submit"
                   value="Select"
                   class="btn btn-info">
        </div>
    </form>
<?php


if (isset($_POST['container'])) {
    $container_id = $_POST['container'];
    echo '<h5>Selected Container: ' . get_the_title($container_id) . '</h5>';

    $container_orders = get_post_meta($container_id, 'container_orders', true);
//print_r($container_orders);
    arsort($container_orders);
//print_r($container_orders);
    $csv_orders_array = get_post_meta($container_id, 'csv_orders_array', true);
    $csv_orders_invoice_array = get_post_meta($container_id, 'csv_orders_invoice_array', true);

    $lifetime_orders_invoice = get_post_meta($container_id, 'lifetime_orders_invoice', true);
    $customers_orders_invoice = get_post_meta($container_id, 'customers_orders_invoice', true);

//    echo 'customers orders invoice: ';
//    print_r($customers_orders_invoice);
//    echo '<br>';
//
//    echo '$csv_orders_invoice_array: ';
//    echo '<pre>';
//    print_r($csv_orders_invoice_array);
//    echo '</pre><br>';

    $sum_dolar_conatainer = 0;
    $sum_conatainer = 0;
    $csv_matrix_orders = array();
    $csv_matrix_orders_invoice = array();
    $matrix_orders = array();
    $repair_orders = array();

    if ($container_orders) {
        foreach ($container_orders as $key => $order_id) {
            if (get_post_type($order_id) == 'order_repair') {
                $repair_orders[] = $order_id;

                $order_original = get_post_meta($order_id, 'order-id-original', true);
                $order = wc_get_order($order_original);
                // $csv_matrix_orders
                if (!empty($csv_orders_array) && !array_key_exists($order_original, $csv_orders_array)) {
                    $csv_matrix_orders[$order_id] = [$order_original, get_post_type($order_id)];
                } else {
                    $csv_matrix_orders[$order_id] = [$order_original, get_post_type($order_id)];
                }
                // $csv_matrix_orders_invoice
                if (!empty($csv_orders_array) && !array_key_exists($order_original, $csv_orders_array)) {
                    $csv_matrix_orders_invoice[$order_id] = [$order_original, get_post_type($order_id)];
                } else {
                    $csv_matrix_orders_invoice[$order_id] = [$order_original, get_post_type($order_id)];
                }
            } elseif (get_post_type($order_id) == 'shop_order') {
                $order = wc_get_order($order_id);
                $matrix_orders[] = $order_id;

                // $csv_matrix_orders
                if (!empty($csv_orders_array) && !array_key_exists($order_id, $csv_orders_array)) {
                    $csv_matrix_orders[$order_id] = '0';
                } else {
                    $csv_matrix_orders[$order_id] = [$csv_orders_array[$order_id], get_post_type($order_id)];
                }

                // $csv_matrix_orders_invoice
                if (!empty($csv_orders_array) && !array_key_exists($order_id, $csv_orders_array)) {
                    $csv_matrix_orders_invoice[$order_id] = '0';
                } else {
                    $csv_matrix_orders_invoice[$order_id] = [$csv_orders_array[$order_id], get_post_type($order_id)];
                }

                $full_payment = 0;
                $user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
                //echo 'USER ID '.$user_id;

                $i = 0;
                $atributes = get_post_meta(1, 'attributes_array', true);

            }
        }
    }


//    echo 'Matrix Orders container: ' . count($matrix_orders) . '<br>';
//    print_r($matrix_orders);
//    echo '<br>';
//
//
//    echo 'Matrix Repair Orders container: ' . count($repair_matrix_orders) . '<br>';
//    print_r($repair_matrix_orders);
//    echo '<br>';


//
//    echo 'Matrix CSV Orders container: ' . count($csv_matrix_orders) . '<br>';
//    print_r($csv_matrix_orders);
//    echo '<br>';
//    echo 'Matrix CSV container invoice: ' . count($csv_matrix_orders_invoice) . '<br>';
//    print_r($csv_matrix_orders_invoice);
//    echo '<br>';

    ?>

    <h4>Dealers Performance GBP</h4>
    <table>
        <thead>
        <tr>
            <th>Count Items</th>
            <th>Sea/Train GBD</th>
            <th>Local Delivery</th>
            <th>Incasat No VAT</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $lifetime_orders = array();
        $totals = array('nr_items' => 0, 'train_total' => 0, 'delivery_total' => 0, 'no_vat_total' => 0);
        $totals_lifetime = array('nr_items' => 0, 'train_total' => 0, 'delivery_total' => 0, 'no_vat_total' => 0);
        $totals_repairs = array('nr_items' => 0, 'train_total' => 0, 'delivery_total' => 0, 'no_vat_total' => 0);
        foreach ($matrix_orders as $key => $order_id) {
            $company = get_post_meta($order_id, '_billing_company', true);

            $order = wc_get_order($order_id);
            $count_items = $order->get_item_count();
            // $company = get_post_meta($order_id, '_billing_company', true);
            $order_train = get_post_meta($order_id, order_train, true);
            $shipping_order = $order->get_shipping_total();
            $subtotal = $order->get_subtotal();

            if ($company === 'Lifetime Shutters') {
                $lifetime_orders[] = $order_id;

                $totals_lifetime['nr_items'] += $count_items;
                $totals_lifetime['train_total'] += $order_train;
                $totals_lifetime['delivery_total'] += $shipping_order;
                $totals_lifetime['no_vat_total'] += $subtotal;
            } else {
                $totals['nr_items'] += $count_items;
                $totals['train_total'] += $order_train;
                $totals['delivery_total'] += $shipping_order;
                $totals['no_vat_total'] += $subtotal;
            }
        }
        foreach ($repair_orders as $key => $repair_id) {


            $order_original = get_post_meta($repair_id, 'order-id-original', true);
            $order = wc_get_order($order_original);
            $count_items = $order->get_item_count();
            $order_train = get_post_meta($order_original, order_train, true);
            $shipping_order = $order->get_shipping_total();
            $subtotal = $order->get_subtotal();
            $cost_repair = get_post_meta($repair_id, 'cost_repair', true);


            $totals_repairs['nr_items'] += $count_items;
            $totals_repairs['train_total'] += $order_train;
            $totals_repairs['delivery_total'] += $shipping_order;
            $totals_repairs['cost_total'] += $cost_repair;

        }
        ?>
        </tbody>
        <tfoot>
        <?php
        echo '<tr>';
        echo '<td>' . $totals['nr_items'] . '</td>';
        echo '<td>£' . number_format($totals['train_total'], 2) . '</td>';
        echo '<td>£' . $totals['delivery_total'] . '</td>';
        echo '<td>£' . $totals['no_vat_total'] . '</td>';
        echo '</tr>';
        ?>
        </tfoot>
    </table>


    <br>
    <h4>Shutters Repair GBP</h4>
    <table>
        <thead>
        <tr>
            <th>Count Items</th>
            <th style="text-decoration: line-through;">By train Orders</th>
            <th style="text-decoration: line-through;">By Delivery Orders</th>
            <th>LFR UK</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //        foreach ($lifetime_orders as $key => $order_id) {
        //            bodyTable($order_id);
        //        }
        ?>
        </tbody>
        <tfoot>
        <?php
        echo '<tr>';
        echo '<td>' . $totals_repairs['nr_items'] . '</td>';
        echo '<td style="text-decoration: line-through;">£' . number_format($totals_repairs['train_total'], 2) . '</td>';
        echo '<td style="text-decoration: line-through;">£' . $totals_repairs['delivery_total'] . '</td>';
        echo '<td>£' . $totals_repairs['cost_total'] . '</td>';
        echo '</tr>';
        ?>
        </tfoot>
    </table>

    <br>
    <h4>Lifetime Shutters Performance GBP</h4>
    <table>
        <thead>
        <tr>
            <th>Count Items</th>
            <th>Sea+train_LTS</th>
            <th>Local Delivery LTS</th>
            <th>Lifetime UK</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //        foreach ($lifetime_orders as $key => $order_id) {
        //            bodyTable($order_id);
        //        }
        ?>
        </tbody>
        <tfoot>
        <?php
        echo '<tr>';
        echo '<td>' . $totals_lifetime['nr_items'] . '</td>';
        echo '<td>£' . number_format($totals_lifetime['train_total'], 2) . '</td>';
        echo '<td>£' . $totals_lifetime['delivery_total'] . '</td>';
        echo '<td>£' . $totals_lifetime['no_vat_total'] . '</td>';
        echo '</tr>';
        ?>
        </tfoot>
    </table>

    <!---->
    <!--    <br>-->
    <!--    <h4>Customers orders</h4>-->
    <!--    <table>-->
    <!--        <thead>-->
    <!--        <tr>-->
    <!--            <th>Total Dolar</th>-->
    <!--            <th>Invoice Dolar</th>-->
    <!--        </tr>-->
    <!--        </thead>-->
    <!--        <tbody>-->
    <!--        --><?php
//        echo '<tr>';
//        echo '<td>$' . $customers_orders_invoice['total_dolar'] . '</td>';
//        echo '<td>$' . $customers_orders_invoice['csv_total_dolar'] . '</td>';
//        echo '</tr>';
//        ?>
    <!--        </tbody>-->
    <!--    </table>-->
    <!---->
    <!---->
    <!--    <br>-->
    <!--    <h4>Lifetime Shutters</h4>-->
    <!--    <table>-->
    <!--        <thead>-->
    <!--        <tr>-->
    <!--            <th>Total Dolar</th>-->
    <!--            <th>Invoice Dolar</th>-->
    <!--        </tr>-->
    <!--        </thead>-->
    <!--        <tbody>-->
    <!--        --><?php
//        echo '<tr>';
//        echo '<td>$' . $lifetime_orders_invoice['total_dolar'] . '</td>';
//        echo '<td>$' . $lifetime_orders_invoice['csv_total_dolar'] . '</td>';
//        echo '</tr>';
//        ?>
    <!--        </tbody>-->
    <!--    </table>-->


    <?php
    $csv_LFRs_array = get_post_meta($container_id, 'csv_LFRs_array', true);
    $csv_LFs_array = get_post_meta($container_id, 'csv_LFs_array', true);


    $repair_matrix_orders = array();
    $matrix_orders = array();
    $matrix_orders_lifetime = array();
    foreach ($csv_LFs_array as $order_id => $items) {
        if (get_post_type($order_id) == 'shop_order') {
            $company = get_post_meta($order_id, '_billing_company', true);
            if ($company === 'Lifetime Shutters') {
                $matrix_orders_lifetime[$order_id] = $items;
            } else {
                $matrix_orders[$order_id] = $items;
            }
        }
    }

    ?>
    <h4>China Invoice USD</h4>
    <table>
        <thead>
        <tr>
            <th>Valoare USD</th>
            <th>Lifetime CH</th>
            <th>LFR China</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total_customers = 0;
        $total_lifetime = 0;
        $total_repairs = 0;
        foreach ($matrix_orders as $order_id => $items) {
            foreach ($items as $key => $price) {
                $total_customers += $price;
            }
        }
        foreach ($matrix_orders_lifetime as $order_id => $items) {
            foreach ($items as $key => $price) {
                $total_lifetime += $price;
            }
        }
        foreach ($csv_LFRs_array as $order_id => $items) {
            foreach ($items as $key => $price) {
                $total_repairs += $price;
            }
        }
        ?>
        </tbody>
        <tfoot>
        <?php
        echo '<tr>';
        echo '<td>$' . number_format($total_customers, 2) . '</td>';
        echo '<td>$' . number_format($total_lifetime, 2) . '</td>';
        echo '<td>$' . number_format($total_repairs, 2) . '</td>';
        echo '</tr>';
        ?>
        </tfoot>
    </table>


    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 90%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        /*.wrap {*/
        /*    max-width: 80%;*/
        /*}*/
    </style>
    <?php

//    echo '<br><pre>';
//    print_r(get_post_meta($matrix_orders[0]));
//    echo '</pre><br>';

    echo '</div>';


}

function bodyTable($order_id)
{
    $order = wc_get_order($order_id);
    $count_items = $order->get_item_count();
    $company = get_post_meta($order_id, '_billing_company', true);
    $order_train = get_post_meta($order_id, order_train, true);
    $shipping_order = $order->get_shipping_total();
    $subtotal = $order->get_subtotal();

    echo '<tr>';
    echo '<td>' . $order_id . '</td>';
    echo '<td>' . $company . '</td>';
    echo '<td>' . $count_items . '</td>';
    echo '<td>' . number_format($order_train, 2) . '</td>';
    echo '<td>' . $shipping_order . '</td>';
    echo '<td>£' . $subtotal . '</td>';
    echo '<td>' . get_post_type($order_id) . '</td>';
    echo '</tr>';
}