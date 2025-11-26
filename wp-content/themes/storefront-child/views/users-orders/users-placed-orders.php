<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<?php
$start = microtime(true);

$users = get_users();
$orders_by_company = array();
foreach ($users as $key => $user) {
	$company = get_user_meta($user->ID, 'billing_company', true);
    $numorders = wc_get_customer_order_count($user->ID);
    $orders_by_company[$company] += $numorders;
}

// Sort the array in descending order by number of orders
arsort($orders_by_company);

//$keys = array_column($users_year, 'users_sqm');
//array_multisort($keys, SORT_ASC, $users_year);
//echo '<pre>';
//print_r($users_year);
//echo '</pre>';

?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nr.</th>
        <th>User</th>
        <th>Phone</th>
        <th>Placed Orders All time</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $total_orders = 0;
    $i = 1;
    foreach ($orders_by_company as $company => $nr_orders) {
        $user_info = get_userdata($user);
        $phone = get_user_meta($user, 'billing_phone', true);
        if($company == "") continue;

	    $args = array(
	      'role' => 'subscriber',
	      'meta_query' => array(
		    array(
		      'key' => 'billing_company',
		      'value' => $company,
		      'compare' => '='
		    )
	      )
	    );

	    $total_orders += $nr_orders;

// Create a new WP_User_Query instance
	    $user_query = new WP_User_Query($args);
// Retrieve the results
	    $users = $user_query->get_results();
	    $user_id = null;
	    if ( ! empty( $users ) ) {
		    // For example, get the first matching user's ID
		    $user_id = $users[0]->ID;
	    }
	    $phone = get_user_meta($user_id, 'billing_phone', true);

        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td>
                <?php
                echo $company;
                ?>
            </td>
            <td>
                <?php echo $phone; ?>
            </td>
            <td>
                <?php echo $nr_orders; ?>
            </td>
        </tr>
        <?php $i++;
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <!-- Coloanele "Nr." și "User" și "Phone" sunt combinate folosind colspan -->
        <td colspan="3" class="text-center"><strong>Total</strong></td>
        <td><strong><?php echo number_format($total_orders, 2); ?></strong></td>
    </tr>
    </tfoot>
</table>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php

$stop = microtime(true);
print_r($stop - $start);
?>