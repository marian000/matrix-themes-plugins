<!-- *********************************************************************************************
           Start Total user sqm
       *********************************************************************************************	-->

<?php

$i = 1;
// luna selectata
if (isset($_POST['luna_select'])) {
	$selected_month = $_POST['luna_select'];
} else {
	$selected_month = date("m");
}
// an selectat
if (isset($_POST['an_select'])) {
	$selected_year = $_POST['an_select'];
} else {
	$selected_year = date("Y");
}

function order_items_materials_sqm_perfect($order_id)
{
	// Define materials
	$materials = array(187 => 'Earth', 137 => 'Green', 138 => 'BiowoodPlus', 6 => 'Biowood', 139 => 'BasswoodPlus', 147 => 'Basswood', 188 => 'Ecowood', 5 => 'EcowoodPlus');
	$order_material_sqm = array();

	// Get order details
	$order = wc_get_order($order_id);
	$items = $order->get_items();
	$attributes_array = get_post_meta(1, 'attributes_array', true);

	// Iterate over each item in the order
	foreach ($items as $item) {
		// Fetch product details
		$product_id = $item['product_id'];
		$material_id = get_post_meta($product_id, 'property_material', true);
		$property_frametype = get_post_meta($product_id, 'property_frametype', true);
		$shuttercolour = get_post_meta($product_id, 'property_shuttercolour', true);
		$sqm = get_post_meta($product_id, 'property_total', true);

		// Get material name
		$material = $materials[$material_id];

		// Handle Green specific cases
		if ($material_id == 137) {
			// Define the key based on the shuttercolour
			$key = in_array($shuttercolour, [411, 412, 413]) ? 'EcowoodPlusF' : 'EcowoodPlusP';
			$order_material_sqm[$key] = ($order_material_sqm[$key] ?? 0) + $sqm;
		}

		// If the material already exists in our array, add to the existing value. Otherwise, create a new key.
		$order_material_sqm[$material] = ($order_material_sqm[$material] ?? 0) + $sqm;
	}

	return $order_material_sqm;
}


// print_r($group);

// Query for users where the meta_key 'company_parrent' is equal to 274
$user_query = new WP_User_Query(array(
	'meta_key' => 'company_parent',
	'meta_value' => 274,
));

$user_ids = array(274);
// Check if we have users
if (!empty($user_query->get_results())) {
// We have users, let's get their IDs
	foreach ($user_query->get_results() as $user) {
		$user_ids[] = $user->ID;
	}
}

//print_r($user_ids);

// foreach ($group as $key => $user_id) {
$args = array(
	'customer_id' => $user_ids,
	'limit' => -1,
	'type' => 'shop_order',
	'status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
	'orderby' => 'date',
	'date_query' => array(
		'after' => date('Y-m-d', mktime(0, 0, 0, $selected_month, 0, $selected_year)),
//        'before' => date('Y-m-t', mktime(0, 0, 0, $selected_month, 1, $selected_year)),
		'before' => date('Y-m-d', strtotime('+1 month', strtotime($selected_year . '-' . $selected_month . '-1'))),
	),
	'return' => 'ids',
);

//echo ' after: ' . date('Y-m-d', mktime(0, 0, 0, $selected_month, 0, $selected_year));
//echo ' before: ' . date('Y-m-d', strtotime('+1 month', strtotime($selected_year . '-' . $selected_month . '-1')));

$orders = wc_get_orders($args);
// $orders = array_merge($orders, $orders_user);
// }

$total = array();
$totaly = array();
$users_sqm = array();

foreach ($orders as $id_order) {
	$materials = array('Earth' => 0, 'Green' => 0, 'Biowood' => 0, 'BiowoodPlus' => 0, 'BasswoodPlus' => 0, 'Basswood' => 0, 'Ecowood' => 0, 'EcowoodPlus' => 0);

	$order = wc_get_order($id_order);
	$order_data = $order->get_data();
	$order_status = $order_data['status'];

	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';
	$myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $id_order", ARRAY_A);

	$property_total = (float)$myOrder['sqm'];
	$user_id = get_post_meta($id_order, '_customer_user', true);
	if (array_key_exists($user_id, $users_sqm)) {
		$users_sqm[$user_id]['sqm'] = $users_sqm[$user_id]['sqm'] + $property_total;
	} else {
		$users_sqm[$user_id]['sqm'] = $property_total;
	}

	// materials
	$items_material = order_items_materials_sqm_perfect($id_order);
//    echo '<pre>';
//    print_r($items_material);
//    echo '</pre>';
	foreach ($items_material as $material => $material_sqm) {
		$prev_sqm = $materials[$material];
		$materials[$material] = $material_sqm + $prev_sqm;

		$users_sqm[$user_id][$material] = $users_sqm[$user_id][$material] + $material_sqm + $prev_sqm;
	}

	$i++;
}
//print_r($users_sqm);
$months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');
?>
<div class="row">
  <div class="col-md-12">
    <div id="users_month">
      <div class="table-responsive">
      <table id="grup-perfect-table" class="table table-bordered table-striped grup-table">
        <thead>
        <tr>
          <th>Nr.</th>
          <th>User</th>
          <th>Total SQM / <?php echo $months[$selected_month]; ?></th>
          <th style="text-align:right">Earth</th>
          <th style="text-align:right">Ecowood</th>
          <th style="text-align:right">Ecowood Plus</th>
          <th style="text-align:right">Ecowood Plus frozen</th>
          <th style="text-align:right">Ecowood Plus paint</th>
          <th style="text-align:right">Biowood</th>
          <th style="text-align:right">Biowood Plus</th>
          <th style="text-align:right">Basswood Plus</th>
          <th style="text-align:right">Basswood</th>
        </tr>
        </thead>
        <tbody>
				<?php
				$i = 0;
				$total_sqm = 0;
				foreach ($users_sqm as $user => $val) {
					$i++;
					$user_info = get_userdata($user);
					$total_sqm = $total_sqm + $users_sqm[$user]['sqm'];
					?>
          <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
            <td>
							<?php echo number_format($users_sqm[$user]['sqm'], 2); ?> SQM
            </td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['Earth'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['Ecowood'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['EcowoodPlus'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['EcowoodPlusF'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['EcowoodPlusP'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['Biowood'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['BiowoodPlus'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['BasswoodPlus'], 2); ?></td>
            <td style="text-align:right"><?php echo number_format($users_sqm[$user]['Basswood'], 2); ?></td>
          </tr>
				<?php } ?>
        </tbody>
        <tfoot>
        <tr class="fw-bold">
        <td></td>
        <td>Total SQM</td>
        <td><?php echo number_format($total_sqm, 2); ?></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        <td style="text-align:right"></td>
        </tr>
        </tfoot>
      </table>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(document).ready(function(){
    jQuery('#grup-perfect-table').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'print'],
        order: [[2, 'desc']],
        columnDefs: [
            { targets: [2,3,4,5,6,7,8,9,10,11], className: 'text-end' }
        ]
    });
});
</script>

<!-- *********************************************************************************************
End Total user sqm
*********************************************************************************************	-->