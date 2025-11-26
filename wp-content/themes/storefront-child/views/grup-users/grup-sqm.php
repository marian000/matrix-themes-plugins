<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');


if ($_POST['group_name']) {
	echo '<h3>' . $_POST['group_name'] . ' SQM</h3>';
} else {
	$groups_created = get_post_meta(1, 'groups_created', true);
	echo '<h3>All Groups SQM</h3>';
}
?>

<!-- *********************************************************************************************
                   Start single user sqm
*********************************************************************************************	-->
<hr>
<br>
<div class="row">
  <div class="col-md-12">
    <div id="container"></div>
  </div>
</div>
<?php


// Main processing
$start = microtime(true);

// Initialize variables
$total = $sum_total = $sum_total_train = $total_order_shipping = array();
$total_dolar = $total_gbp = $total_gbp_train = $total_train_calc = $total_truck_calc = $total_materials_sqm = array();
$months = array('01' => 'Jan.', '02' => 'Feb.', '03' => 'Mar.', '04' => 'Apr.', '05' => 'May', '06' => 'Jun.', '07' => 'Jul.', '08' => 'Aug.', '09' => 'Sep.', '10' => 'Oct.', '11' => 'Nov.', '12' => 'Dec.');
$months_sum = array_fill_keys(array_keys($months), 0);

// Get user group
$group = get_user_group();

// Get selected year
$selected_year = isset($_POST['an_select']) ? $_POST['an_select'] : date("Y");

// Fetch orders
$orders = fetch_orders($group, $selected_year);

// Fetch custom order data
$custom_order_data = fetch_custom_order_data($orders);

// Data arrays to initialize
$data_arrays = array(
    &$sum_total, &$sum_total_train, &$total_dolar,
    &$total, &$total_gbp, &$total_gbp_train,
    &$total_train_calc, &$total_truck_calc, &$total_order_shipping
);

// Process each order
foreach ($orders as $order_id) {
    $order = wc_get_order($order_id);
    $order_data = $order->get_data();
    $custom_data = $custom_order_data[$order_id];
    $user_id_customer = get_post_meta($order_id, '_customer_user', true);

    // Extract year and month
    $order_date = $order_data['date_created']->date('Y-m');
    list($year, $month) = explode('-', $order_date);

    // Initialize arrays for the year if not already initialized
    if (!array_key_exists($year, $sum_total)) {
        initialize_yearly_data($data_arrays, $year);
    }

    // Calculate train price if applicable
    $property_total = $custom_data['sqm'];
    $train_price = 0;
    if ($property_total > 0 && $year >= 2021) {
        $train_price = get_post_meta($order_id, 'order_train', true);
        if (empty($train_price) || $train_price < 10) {
            $train_price = 10 * $property_total;
        }
    }

    // Update arrays with order data
    $sum_total_train[$year][$month] += $train_price;
    $sum_total[$year][$month] += floatval($order->get_total());

    // Update materials
    $items_material = order_items_materials_sqm($order_id);
    foreach ($items_material as $material => $material_sqm) {
        if (!isset($total_materials_sqm[$year][$month][$material])) {
            $total_materials_sqm[$year][$month][$material] = 0;
        }
        $total_materials_sqm[$year][$month][$material] += floatval($material_sqm);
    }

    // Update totals
    $total_dolar[$year][$month] += $custom_data['usd_price'];
    $total_gbp[$year][$month] += $order->get_subtotal() - $train_price;
    $total_gbp_train[$year][$month] += $order->get_subtotal();
    $total[$year][$month] += $property_total;

    // Calculate and update transport costs
    $average_train = (float)get_post_meta($order_id, 'average_train', true) ?: 0;
    $average_truck = (float)get_post_meta($order_id, 'average_truck', true) ?: 0;
    $total_train_calc[$year][$month] += $average_train * $property_total;
    $total_truck_calc[$year][$month] += $average_truck * $property_total;

    // Update shipping totals
    $order_shipping_total = floatval($order_data['shipping_total']) ?: 0;
    $total_order_shipping[$year][$month] += $order_shipping_total;
}

// Generate the data for hidden input
$new = array();
foreach (range(11, 0) as $i) {
    $date = new DateTime("-$i months");
    $year = $date->format('Y');
    $month = $date->format('m');

    $new[$month] = isset($total[$year][$month]) ? round($total[$year][$month], 0) : 0;
}
?>
<input type="hidden" name="totalcart" value="<?php echo htmlspecialchars(json_encode($total), ENT_QUOTES, 'UTF-8'); ?>">

<?php

// End of the script
$end = microtime(true);
$execution_time = ($end - $start);
echo 'Total Execution Time: ' . $execution_time . ' seconds';

//$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$newlunian = array();
$new = array();
$news = array();
for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
	$newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
}

$current_year = date("y");
foreach ($newlunian as $k => $anluna) {
	$pieces = explode("-", $anluna);
	$an = $pieces[0]; // piece1
	$luna = $pieces[1]; // piece2
	if (!empty($total[$an][$luna])) {
		$new[$luna] = round($total[$an][$luna], 0);
		//print_r($t);
	} else {
		if ($new[$luna] != 0) {
		} else {
			$new[$luna] = 0;
			//print_r($new[$luna]);
		}
	}
}

//echo '<pre>';
//print_r($sum_totaly_train);
//echo '</pre>';

?>

<br>
<div class="row">
  <div class="col-sm-8">
    <table class="table table-bordered table-striped">
      <tbody>

      <tr>
        <th>Year</th>
        <th>Month</th>
        <th style="text-align:right">m2</th>
        <th style="text-align:right">USD</th>
        <th style="text-align:right">GBP</th>
        <th style="text-align:right">Delivery</th>
        <th style="text-align:right">Train</th>
        <th style="text-align:right">GBP Total</th>
        <th style="text-align:right">Earth</th>
        <th style="text-align:right">Ecowood</th>
        <th style="text-align:right">EcowoodPlus</th>
        <th style="text-align:right">Biowood</th>
        <th style="text-align:right">BiowoodPlus</th>
        <th style="text-align:right">Supreme</th>
      </tr>
			<?php
			$current_year = date("Y");

			$sum_tot_sqm = 0;
			$sum_tot_dolar = 0;
			$sum_tot_gbp = 0;
			$sum_tot_train = 0;
			$sum_tot_prices = 0;
			$sum_tot_dolar = 0;
			$sum_tot_dolar = 0;
			$sum_shipping = 0;
            $sum_tot_earth = 0;
            $sum_tot_ecowood = 0;
            $sum_tot_ecowoodPlus = 0;
            $sum_tot_green = 0;
            $sum_tot_biowood = 0;
            $sum_tot_biowoodPlus = 0;
            $sum_tot_supreme = 0;
			foreach ($sum_total as $year => $sum_ty) {
				foreach ($luni as $luna) {
					if ($sum_ty[$luna]) {
						$sqm = $total[$year][$luna];
						$new_price = 0;
						if ($sqm > 0 && $year >= 2021) {
							$train_price = 10;
							$new_price = floatval($sqm) * floatval($train_price);
						}
						echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right">' . number_format($total[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_dolar[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($sum_total_train[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($sum_total[$year][$luna], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Earth'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Ecowood'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['EcowoodPlus'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Biowood'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['BiowoodPlus'], 2) . '</th>
                            <th style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Supreme'], 2) . '</th>
                        </tr>';
						$sum_tot_sqm = $sum_tot_sqm + $total[$year][$luna];
						$sum_tot_dolar = $sum_tot_dolar + $total_dolar[$year][$luna];
						$sum_tot_gbp = $sum_tot_gbp + $total_gbp[$year][$luna];
						$sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
						$sum_shipping = $sum_shipping + $total_order_shipping[$year][$luna];
						$sum_tot_train = $sum_tot_train + $sum_total_train[$year][$luna];
                        $sum_tot_earth = $sum_tot_earth + $total_materials_sqm[$year][$luna]['Earth'];
                        $sum_tot_ecowood = $sum_tot_ecowood + $total_materials_sqm[$year][$luna]['Ecowood'];
                        $sum_tot_ecowoodPlus = $sum_tot_ecowoodPlus + $total_materials_sqm[$year][$luna]['EcowoodPlus'];
                        $sum_tot_biowood = $sum_tot_biowood + $total_materials_sqm[$year][$luna]['Biowood'];
                        $sum_tot_biowoodPlus = $sum_tot_biowoodPlus + $total_materials_sqm[$year][$luna]['BiowoodPlus'];
                        $sum_tot_supreme = $sum_tot_supreme + $total_materials_sqm[$year][$luna]['Supreme'];
					} else {
						echo '<tr>
                            <th>' . $year . '</th>
                            <th>' . $months[$luna] . '</th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th> <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                            <th style="text-align:right"></th>
                        </tr>';
					}
				}
			}
			?>

      <tr>
        <td colspan="2"
            style="text-align:right">
          <strong>Totals</strong>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_tot_sqm, 2); ?>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_tot_dolar, 2); ?>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_tot_gbp, 2); ?>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_shipping, 2); ?>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_tot_train, 2); ?>
        </td>
        <td style="text-align:right">
					<?php echo number_format($sum_tot_prices, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_earth, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_ecowood, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_ecowoodPlus, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_green, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_biowood, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_biowoodPlus, 2); ?>
        </td>
        <td style="text-align:right">
                                  <?php echo number_format($sum_tot_supreme, 2); ?>

      </tr>
      </tbody>
    </table>
  </div>
</div>

<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
	$month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
	$months_cart[] = $month;
}?>

<script>

  jQuery(document).ready(function(){

      var sqm = jQuery('input[name="totalcart"]').val();
      var js_array = [<?php echo '"' . implode('","', $new) . '"' ?>];
      console.log(sqm);
      console.log(js_array);
      var ints = js_array.map(parseFloat);

      Highcharts.chart('container', {
          "chart": {
              "type": 'column'
          },
          "title": {
              "text": null
          },
          "legend": {
              "align": "right",
              "verticalAlign": "top",
              "y": 75,
              "x": -50,
              "layout": "vertical"
          },
          "xAxis": {
              "categories": <?php echo json_encode($months_cart); ?>
          },
          "yAxis": [{
              "title": {
                  "text": "m2",
                  "margin": 70
              },
              "min": 0
          }],
          "tooltip": {
              "enabled": true,
              "valueDecimals": 0,
              "valueSuffix": " sqm"
          },

          "plotOptions": {
              "column": {
                  "dataLabels": {
                      "enabled": true,
                      "valueDecimals": 0
                  },
                  enableMouseTracking: true
              }
          },

          "credits": {
              "enabled": true
          },

          "subtitle": {},
          "series": [{
              "name": "m2",
              "yAxis": 0,
              "data": ints
          }]
      });
  });

</script>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php
$stop = microtime(true);
print_r($stop - $start);
?>
<!-- *********************************************************************************************
End single user sqm
*********************************************************************************************	-->

