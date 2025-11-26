<?php
    $path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
    include($path . 'wp-load.php');
?>
<table class="table data-old">
    <thead style="background-color: #f2dede;">
    <th></th>
    <th>Order ID</th>
    <th>Date</th>
    <th>Deliveries Start</th>
    <th>Total</th>
    <th>Status</th>
    <th>Last Update</th>
    </thead>
    <tbody>
    <?php
        $user_id = get_current_user_id();
        $args = array(
            'post_type' => 'order_repair',
            //  'post_status' => array('wc-pending', 'wc-inproduction', 'wc-processing'),
//            'meta_key' => 'container_id',
//            'meta_value' => false,
//            'meta_compare' => 'BOOLEAN',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'relation' => 'OR',
                    array(
                        'key' => 'container_id',
                        'value' => false,
                        'compare' => 'BOOLEAN',
                    ),
                    array(
                        'key' => 'container_id',
                        'value' => '',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'container_id',
                        'value' => '',
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'container_id',
                        'value' => NULL,
                        'compare' => '=',
                    ),
                ),
                array(
                    'key' => 'order_status',
                    'value' => 'completed',
                    'compare' => '!=',
                ),
            ),
            'date_query' => array(
                'before' => date('Y-m-d', strtotime('-4 weeks')),
                'after' => date('Y-m-d', strtotime('-8 months'))
            )
        );
        
        //print_r($user_id);
        $orders = new WP_Query($args);
        ///$orders = wc_get_orders($args);
        $total = array();
        $i = 1;
        //        echo '<pre>';
        //        print_r($orders->posts);
        //        echo '</pre>';
        foreach ($orders->posts as $key => $order_id) {
            //  echo $key;
            $order_id_original = get_post_meta($order_id->ID, 'order-id-original', true);
            
            $order = wc_get_order($order_id_original);
            
            $items = $order->get_items();
            $order_data = $order->get_data();
            $order_status = get_post_meta($order_id->ID, 'order_status', true);;
            $culori_status = array('pending' => '#ef8181', 'on-hold' => '#f8dda7', 'processing' => '#c6e1c6', 'completed' => '#c8d7e1', 'cancelled' => '#e5e5e5', 'refound' => '#5BC0DE', 'inproduction' => '#7878e2', 'transit' => '#85bb65', 'waiting' => '#3ba000', 'account-on-hold' => 'red');
            
            foreach ($items as $item_id => $item_data) {
                
                $product_id = $item_data['product_id'];
                
                $_product = wc_get_product($product_id);
                $shipclass = $_product->get_shipping_class();
            }
            if ($shipclass != 'air') {
                ?>
                <tr class=" ">
                    <td>
                        <?php echo $i; ?>.
                    </td>
                    <td>
                        <a href="/wp-admin/post.php?post=<?php echo $order_id->ID; ?>&action=edit" class="order-view">
                            <?php echo 'LFR' . $order->get_order_number() . ' - <i>' . get_post_meta($order->get_id(), 'cart_name', true) . '</i>'; ?>
                        </a>
                    </td>
                    <td>
                        <?php
                            echo $order_data['date_created']->date('Y-m-d H:i:s');
                            
                            $text = $order_data['date_created']->date('Y-m-d H:i:s');
                            preg_match('/-(.*?)-/', $text, $match);
                            preg_match('/(.*?)-/', $text, $match_year);
                            $month = $match[1];
                            $year = $match_year[1];
                        ?>
                    </td>
                    <td>
                        <?php echo get_post_meta($order_id->ID, 'delivereis_start', true); ?>
                    </td>
                    <td>
                        <?php
                            
                            echo $order->get_total();
                        ?>
                    </td>
                    <td>
                        <p class="statusOrder" style="<?php
                            foreach ($culori_status as $stat => $cul) {
                                if ($stat == $order_status) {
                                    echo 'background-color:' . $cul . ';';
                                }
                            }
                        ?> color: #fff; text-align:center;">Ordered -
                            <?php
                                if ($order_status == 'pending') {
                                    echo 'pending payment';
                                } //teo>
                                elseif ($order_status == 'waiting') {
                                    echo 'waiting delivery';
                                } //teo<
                                else {
                                    echo $order_status;
                                }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                    </td>
                </tr>
                
                <?php
                $i++;
            }
        }
    
    ?>
    
    </tbody>

</table>
