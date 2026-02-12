<!-- *********************************************************************************************
           Start Total user sqm
       *********************************************************************************************	-->

<?php
if (isset($_POST['group_name']) && $_POST['group_name']) {
    if($_POST['group_name'] === 'all_users'){
        $users = get_users( array( 'fields' => array( 'ID' ) ) );
        $group = array();
        foreach($users as $user){
            $group[] = $user->ID;
        }
    }else{
        $group = get_post_meta(1, $_POST['group_name'], true);
    }
} else {
    $groups_created = get_post_meta(1, 'groups_created', true);
    $group = array();
    for ($i = 0; $i < count($groups_created); $i++) {
        $group_single = get_post_meta(1, $groups_created[$i], true);
        $group = array_merge($group, $group_single);
    }
}

$i = 1;
// luna selectata
if (isset($_POST['luna_select'])) {
    $selected_month = $_POST['luna_select'];
    // luna selectata
} else {
    $selected_month = date("m");
}
// an selectat
if (isset($_POST['an_select'])) {
    $selected_year = $_POST['an_select'];
    // an selectat
} else {
    $selected_year = date("Y");
}

$orders = array();
// foreach ($group as $key => $user_id) {
$args = array(
    'customer_id' => $group,
    'limit' => -1,
    'type' => 'shop_order',
    'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
    'orderby' => 'date',
    'date_query' => array(
        'after' => date('Y-m-d', mktime(0, 0, 0, $selected_month, 1, $selected_year)),
        'before' => date('Y-m-t', mktime(0, 0, 0, $selected_month, 1, $selected_year)),
    ),
    'return' => 'ids',
);

// Debug removed

$orders = wc_get_orders($args);
// $orders = array_merge($orders, $orders_user);
// }

$total = array();
$totaly = array();
$users_sqm = array();
// print_r($orders);

foreach ($orders as $id_order) {
    $order = wc_get_order($id_order);
    $order_data = $order->get_data();
    $order_status = $order_data['status'];

    global $wpdb;
    $tablename = $wpdb->prefix . 'custom_orders';
    $myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

    $property_total = (float)$myOrder['sqm'];
    $user_id = get_post_meta($id_order, '_customer_user', true);
    if (array_key_exists($user_id, $users_sqm)) {
        $users_sqm[$user_id] = $users_sqm[$user_id] + $property_total;
    } else {
        $users_sqm[$user_id] = $property_total;
    }

    $i++;
}
//print_r($users_sqm);
$months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
?>
<br>

<div class="row">
    <div class="col-md-12">
        <div id="users_month">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Nr.</th>
                    <th>User</th>
                    <th>Total SQM / <?php echo $months[$selected_month]; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                foreach ($users_sqm as $user => $sqm) {
                    $user_info = get_userdata($user);

                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
                        <td>
                            <?php echo number_format($users_sqm[$user], 2); ?> SQM
                        </td>
                    </tr>
                    <?php $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- *********************************************************************************************
End Total user sqm
*********************************************************************************************	-->