<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');

$i = 1;
// luna selectata
if (isset($_POST['luna_select'])) {
	$selected_month = $_POST['luna_select'];
} else {
	$selected_month = date("m");
}

// Get user group
$group = get_user_group();

// Get selected year
$selected_year = isset($_POST['an_select']) ? $_POST['an_select'] : date("Y");

// Fetch orders
$orders = fetch_orders($group, $selected_year);

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
	$items_material = order_items_materials_sqm($id_order);
//    echo '<pre>';
//    print_r($items_material);
//    echo '</pre>';
	foreach ($items_material as $material => $material_sqm) {
		$prev_sqm = isset($materials[$material]) ? floatval($materials[$material]) : 0;
		$materials[$material] = floatval($material_sqm) + $prev_sqm;

		$prev_user_sqm = isset($users_sqm[$user_id][$material]) ? floatval($users_sqm[$user_id][$material]) : 0;
		$users_sqm[$user_id][$material] = $prev_user_sqm + floatval($material_sqm) + $prev_sqm;
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
      <table id="grup-month-table" class="table table-bordered table-striped grup-table">
        <thead>
        <tr>
          <th>Nr.</th>
          <th>User</th>
          <th>Total SQM / <?php echo $months[$selected_month]; ?></th>
          <th style="text-align:right">Earth</th>
          <th style="text-align:right">Ecowood</th>
          <th style="text-align:right">Ecowood Plus</th>
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
				$total_sqm_earth = 0;
				$total_sqm_ecowood = 0;
				$total_sqm_ecowoodPlus = 0;
				$total_sqm_biowood = 0;
				$total_sqm_biowoodPlus = 0;
				$total_sqm_basswoodPlus = 0;
				$total_sqm_basswood = 0;

				foreach ($users_sqm as $user => $val) {
					$i++;
					$user_info = get_userdata($user);
					$total_sqm = $total_sqm + $users_sqm[$user]['sqm'];
					$total_sqm_earth = $total_sqm_earth + $users_sqm[$user]['Earth'];
					$total_sqm_ecowood = $total_sqm_ecowood + $users_sqm[$user]['Ecowood'];
					$total_sqm_ecowoodPlus = $total_sqm_ecowoodPlus + $users_sqm[$user]['EcowoodPlus'];
					$total_sqm_biowood = $total_sqm_biowood + $users_sqm[$user]['Biowood'];
					$total_sqm_biowoodPlus = $total_sqm_biowoodPlus + $users_sqm[$user]['BiowoodPlus'];
					$total_sqm_basswoodPlus = $total_sqm_basswoodPlus + $users_sqm[$user]['BasswoodPlus'];
					$total_sqm_basswood = $total_sqm_basswood + $users_sqm[$user]['Basswood'];
					?>
          <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $user_info->display_name . " - " . $user_info->first_name . " " . $user_info->last_name; ?></td>
            <td data-order="<?php echo $users_sqm[$user]['sqm']; ?>"><?php echo number_format($users_sqm[$user]['sqm'], 2); ?> SQM</td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['Earth'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['Ecowood'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['EcowoodPlus'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['Biowood'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['BiowoodPlus'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['BasswoodPlus'], 2); ?></td>
            <td style="text-align:right">  <?php echo number_format($users_sqm[$user]['Basswood'], 2); ?></td>
          </tr>
				<?php } ?>
        </tbody>
        <tfoot>
        <tr class="fw-bold">
        <td></td>
        <td>Total SQM</td>
        <td><?php echo number_format($total_sqm, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_earth, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_ecowood, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_ecowoodPlus, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_biowood, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_biowoodPlus, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_basswoodPlus, 2); ?></td>
        <td style="text-align:right"><?php echo number_format($total_sqm_basswood, 2); ?></td>
        </tr>
        </tfoot>
      </table>
      </div>
    </div>
  </div>
</div>

<script>
jQuery(document).ready(function(){
    jQuery('#grup-month-table').DataTable({
        paging: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'print'],
        order: [[2, 'desc']],
        columnDefs: [
            { targets: [2,3,4,5,6,7,8,9], className: 'text-end' }
        ]
    });
});
</script>

<!-- *********************************************************************************************
End Total user sqm
*********************************************************************************************	-->