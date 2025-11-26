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
foreach ($customers as $user) {
    $args = array(
        'customer_id' => $user->id,
        'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
        'date_query' => array(
            'after' => date('Y-m-d', strtotime($from2)),
            'before' => date('Y-m-d', strtotime($to2))
        )
    );

    $orders = wc_get_orders($args);

    $total_price_battens = 0;

    foreach ($orders as $order) {

        $id_order = $order->get_id();

        $items = $order->get_items();

        foreach ($items as $item_id => $item_data) {
            $product_id = $item_data->get_product_id();

            $product_cats_ids = wc_get_product_term_ids($product_id, 'product_cat');
            $terms_name = array();
            foreach ($product_cats_ids as $cat_id) {
                $term = get_term_by('id', $cat_id, 'product_cat');
                $terms_name[] = $term->name;
                $terms_slug[] = $term->slug;
            }
            if (in_array('batten', $terms_slug)) {
                print_r($item_id);
                $price = number_format($item_data['total_price']) * number_format($item_data['quantity']);
                $total_price_battens += $price;
            }
        }
    }


    $user_info = get_userdata($user->id);
    $user_name = $user_info->first_name . ' ' . $user_info->last_name;
//    echo '<h2>User: ' . $user_name . ' have Battens price: ' . $total_price_battens . 'GBP between ' . $from2 . ' - ' . $to2 . '<h2>';

    if ($total_price_battens > 0) {
        $users_total_batten[$user_name] = $total_price_battens;
    }
}
arsort($users_total_batten);
$total_users_price = 0;
foreach ($users_total_batten as $user_name => $total_price_battens) {
    echo '<p>User: <b>' . $user_name . '</b> have Battens price: <b>' . $total_price_battens . ' GBP</b> between ' . $from2 . ' - ' . $to2 . '</p>';
    $total_users_price += $total_price_battens;

}
echo '<hr><p>Total Users: <b>'.$total_users_price.' GBP</b></p>';

?>


