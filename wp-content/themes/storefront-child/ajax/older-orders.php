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
            'fields' => 'ids',
            'post_type' => 'shop_order',
            'post_status' => array('wc-pending', 'wc-inproduction', 'wc-processing', 'wc-on-hold'),
//            'meta_key' => 'container_id',
//            'meta_value' => false,
//            'meta_compare' => 'BOOLEAN',
            'meta_query' => array(
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
            'date_query' => array(
                'before' => date('Y-m-d', strtotime('-4 weeks')),
                'after' => date('Y-m-d', strtotime('-12 months'))
            )
        );
        
        //print_r($user_id);
        $orders = new WP_Query($args);
        ///$orders = wc_get_orders($args);
        $total = array();
        $i = 1;
        // echo '<pre>';
        // 	print_r($orders);
        // echo '</pre>';
        foreach ($orders->posts as $order_id) {
            $order = wc_get_order($order_id);
            $items = $order->get_items();
            $order_data = $order->get_data();
            $order_status = $order_data['status'];
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
                        <a href="/wp-admin/post.php?post=<?php echo $order->get_id(); ?>&action=edit" class="order-view">
                            <?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->get_id(), 'cart_name', true) . '</i>'; ?>
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
                        <?php echo get_post_meta($order->get_id(), 'delivereis_start', true); ?>
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
