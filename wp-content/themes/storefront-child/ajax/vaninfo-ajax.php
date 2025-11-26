<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');


$id_selected_container = $_POST['container_id'];
$van_select = $_POST['van_select'];

$van_orders_dealers = get_post_meta($id_selected_container, $van_select . '_orders', true);

?>


<table id="example"
       style="width:100%; display:table;"
       class="table table-striped">
    <thead>
    <tr>
        <th>
        </th>
        <th>
            Van
        </th>
        <th>
            COMPANY NAME
        </th>
        <th>
            PHONE
        </th>
        <th>
            DELIVERY ADD
        </th>
        <th>
            SQM
        </th>
        <th>
            PANELS
        </th>
        <th>
            FRAMES
        </th>
        <th>
            CARTONS
        </th>
        <th>
            WEIGHT
        </th>
<!--        <th>-->
<!--            DELIVERY COST-->
<!--        </th>-->

    </tr>
    </thead>
    <tbody>

    <?php

    $sqm_total_van = 0;
    $weight_total_van = 0;
    $price_total_van = 0;

    $total_sqm = 0;
    $total_weight = 0;
    $total_cost = 0;

    $container_orders_dealer = array();
    $data = array();
    $total_panels = 0;
    $total_frames = 0;
    $total_cartons = 0;
    $old_user = 0;

    $container_dealer_orders_van = get_post_meta($container_id, 'container_dealer_orders_' . $van_select, true);

    $csv_orders_array_cartons = get_post_meta($container_id, 'csv_orders_array_cartons', true);
    $csv_orders_array_cartons_repair = get_post_meta($container_id, 'csv_orders_array_cartons_repair', true);

    $csv_orders_array_panels = get_post_meta($container_id, 'csv_orders_array_panels', true);
    $csv_orders_array_frames = get_post_meta($container_id, 'csv_orders_array_frames', true);
    $csv_orders_array_panels_repair = get_post_meta($container_id, 'csv_orders_array_panels_repair', true);
    $csv_orders_array_frames_repair = get_post_meta($container_id, 'csv_orders_array_frames_repair', true);

    if ($van_orders_dealers) {

        foreach ($van_orders_dealers as $order_id) {


            if (get_post_type($order_id) == 'order_repair') {
                $order_id = get_post_meta($order_id, 'order-id-original', true);
                $order = wc_get_order($order_id);
                $order_data = $order->get_data();
            } else {
                $order = wc_get_order($order_id);
                $order_data = $order->get_data();
            }
            $user_id_customer = get_post_meta($order_id, '_customer_user', true);

            if (!array_key_exists ($user_id_customer,$container_orders_dealer)) {
                $container_orders_dealer[$user_id_customer] = array();
                $data = array();
            } else {
                $data = $container_orders_dealer[$user_id_customer];
            }
            $data['orders'][] = $order_id;
            foreach ($order->get_items('tax') as $item_id => $item_tax) {
                $tax_shipping_total = $item_tax->get_shipping_tax_total();
            }
            if (empty($data['tax_shipping_total'])) {
                $data['tax_shipping_total'] = 0 + $tax_shipping_total;
            } else {
                $data['tax_shipping_total'] = $data['tax_shipping_total'] + $tax_shipping_total;
            }
            $full_payment = 0;

            $i = 0;
            $atributes = get_post_meta(1, 'attributes_array', true);
            $items = $order->get_items();

            // get items and verfy shipping method
            $item_nr = 0;
            if ($item_nr < 1) {
                foreach ($items as $item_id => $item_data) {
                    $product_id = $item_data['product_id'];
                    $_product = wc_get_product($product_id);

                    $shipclass = $_product->get_shipping_class();
                    $item_nr++;
                }
            }

            if ($order_data['shipping_total'] >= 0) {
                $sqm_total = 0;
                $weight_final = 0;
                foreach ($items as $item_id => $item_data) {
                    $product_id = $item_data['product_id'];
                    $property_total = get_post_meta($product_id, 'property_total', true);
                    $sqm_total = $sqm_total + $property_total * $item_data['quantity'];

                    $property_material = get_post_meta($product_id, 'property_material', true);

                    if ($property_material == 138) {
//                                                                $weight_final = $weight_final + $sqm_total*11;
                        $weight_final = $weight_final + ($property_total * $item_data['quantity'] * 11);
                    } elseif ($property_material == 139) {
//                                                                $weight_final = $weight_final + $sqm_total*12;
                        $weight_final = $weight_final + ($property_total * $item_data['quantity'] * 12);
                    } elseif ($property_material == 137) {
//                                                                $weight_final = $weight_final + $sqm_total*13;
                        $weight_final = $weight_final + ($property_total * $item_data['quantity'] * 13);
                    } elseif ($property_material == 187) {
//                                                                $weight_final = $weight_final + $sqm_total*15;
                        $weight_final = $weight_final + ($property_total * $item_data['quantity'] * 14);
                    } elseif ($property_material == 188) {
//                                                                $weight_final = $weight_final + $sqm_total*15;
                        $weight_final = $weight_final + ($property_total * $item_data['quantity'] * 13);
                    }
                }
                $total_sqm = $total_sqm + $sqm_total;

                if (empty($data['sqm_total'])) {
                    $data['sqm_total'] = 0 + $sqm_total;
                } else {
                    $data['sqm_total'] = $data['sqm_total'] + $sqm_total;
                }


                if (get_post_type($order_id) == 'order_repair') {
                    $order_original = get_post_meta($order_id, 'order-id-original', true);
                    $cartons = $csv_orders_array_cartons_repair[$order_original];
                    $panels = $csv_orders_array_panels_repair[$order_original];
                    $frames = $csv_orders_array_frames_repair[$order_original];
                } else {
                    $cartons = $csv_orders_array_cartons[$order_id];
                    $panels = $csv_orders_array_panels[$order_id];
                    $frames = $csv_orders_array_frames[$order_id];
                }

                $total_cartons = $total_cartons + $cartons;
                $total_panels = $total_panels + $panels;
                $total_frames = $total_frames + $frames;
                //  $data['cartons'] = $data['cartons'] + $cartons;
                if (empty($data['cartons'])) {
                    $data['cartons'] = 0 + $cartons;
                    $data['panels'] = 0 + $panels;
                    $data['frames'] = 0 + $frames;
                } else {
                    $data['cartons'] = $data['cartons'] + $cartons;
                    $data['panels'] = $data['panels'] + $panels;
                    $data['frames'] = $data['frames'] + $frames;
                }

                if (empty($data['weight_final'])) {
                    $data['weight_final'] = 0 + $weight_final;
                } else {
                    $data['weight_final'] = $data['weight_final'] + $weight_final;
                }
                $total_weight = $total_weight + number_format($weight_final, 2);
                $price = number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', '');
                $total_cost = $total_cost + number_format($order_data['shipping_total'] + $tax_shipping_total, 2, '.', '');

                if (empty($data['price'])) {
                    $data['price'] = 0 + $price;
                } else {
                    $data['price'] = $data['price'] + $price;
                }
                $container_orders_dealer[$user_id_customer] = $data;

            }
        }
    }

    foreach ($container_orders_dealer as $dealer_id => $data) {
    $name = get_user_meta($dealer_id, 'first_name', true) . ' ' . get_user_meta($dealer_id, 'last_name', true);
    $company = get_user_meta($dealer_id, 'billing_company', true);
    $phone = get_user_meta($dealer_id, 'billing_phone', true);
    $address = get_user_meta($dealer_id, 'billing_address_1', true);
    $postcode = get_user_meta($dealer_id, 'billing_postcode', true);
    $city = get_user_meta($dealer_id, 'billing_city', true);
    $country = get_user_meta($dealer_id, 'billing_country', true);
    ?>
    <tr>
        <td>
            <!-- se  cumuleaza sqm, weight si price -->
            <?php
            if (empty(get_user_meta($dealer_id, 'van_nr_' . $id_selected_container, true))) {
                ?>
                <input type="checkbox" value="<?php echo $dealer_id; ?>"
                       name="container-dealer">
                <?php
            }
            ?>
        </td>
        <td>
            <?php echo get_user_meta($dealer_id, 'van_nr_' . $id_selected_container, true); ?>
        </td>
        <td>
            <a href="/delivery-info/?id_container=<?php echo base64_encode($id_selected_container); ?>&dealer_id=<?php echo base64_encode($dealer_id); ?>"
               class="">
                <?php
                echo (empty($company)) ? $name : $company;
                ?>
            </a>
        </td>
        <td><?php echo $phone; ?></td>
        <td><?php echo $address . ' - ' . $postcode . ' - ' . $city . ' - ' . $country; ?></td>
        <td><?php echo $data['sqm_total']; ?></td>
        <td><?php echo $data['panels']; ?></td>
        <td><?php echo $data['frames']; ?></td>
        <td><?php echo $data['cartons']; ?></td>
        <td><?php echo $data['weight_final']; ?></td>
<!--        <td>--><?php //echo $data['price']; ?><!--</td>-->
        <?php
        }

        ?>
    </tbody>
    <tfoot>
    <tr>
        <th>
        </th>
        <th>
            Van
        </th>
        <th>
            Company Name
        </th>
        <th>
            Phone
        </th>
        <th>
            Delivery Add
        </th>
        <th>Total SQM<br/>
            <?php echo $total_sqm; ?>
        </th>
        <th>Total Panels<br/>
            <?php echo $total_panels; ?>
        </th>
        <th>Total Frames<br/>
            <?php echo $total_frames; ?>
        </th>
        <th>Total Cartons<br/>
            <?php echo $total_cartons; ?>
        </th>
        <th>Total Weight<br/>
            <?php echo $total_weight; ?>
        </th>
<!--        <th>Total delivery<br/>-->
<!--            --><?php //echo $total_cost; ?>
<!--        </th>-->
    </tr>
    </tfoot>

    </tbody>
</table>


<br>
<!-- se  cumuleaza sqm, weight si price -->
<!--<table id="example3" style="width:100%; display:table;" class="table table-striped">-->
<!--    <thead>-->
<!--    <tr>-->
<!--        <th>-->
<!--            Total SQM Van-->
<!--        </th>-->
<!--        <th>-->
<!--            Total Weight Van-->
<!--        </th>-->
<!--        <th>-->
<!--            Total Price Van-->
<!--        </th>-->
<!---->
<!--    </tr>-->
<!--    </thead>-->
<!--    <tbody>-->
<!--    <tr>-->
<!--        <th>-->
<!--            --><?php //echo number_format($sqm_total_van, 2); ?>
<!--        </th>-->
<!--        <th>-->
<!--            --><?php //echo number_format($weight_total_van, 2); ?>
<!--        </th>-->
<!--        <th>-->
<!--            --><?php //echo number_format($price_total_van, 2); ?>
<!--        </th>-->
<!--    </tr>-->
<!--    </tbody>-->
<!--</table>-->
