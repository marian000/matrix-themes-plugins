<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<hr>
<h3>Total Users SQM</h3>
<br>
<div class="row">
    <div class="col-md-12">
        <div id="container"></div>
    </div>
    <!--div class="col-md-6">
        <div id="container2"></div>
    </div-->
</div>

<?php

// Main processing
$start = microtime(true);

// Initialize arrays
$total = $sum_total = $sum_total_train = $total_order_shipping = array();
$total_dolar = $total_gbp = $total_gbp_train = $total_train_calc = $total_truck_calc = array();

$data_arrays = array(
	&$sum_total, &$sum_total_train, &$total_dolar,
	&$total, &$total_gbp, &$total_gbp_train,
	&$total_train_calc, &$total_truck_calc, &$total_order_shipping
);

// Fetch order IDs
$orders = fetch_order_ids();

// Fetch custom order data
$custom_order_data = fetch_custom_order_data($orders);

foreach ($orders as $order_id) {
	$order = wc_get_order($order_id);
	$order_data = $order->get_data();
	$custom_data = $custom_order_data[$order_id];

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
	$sum_total[$year][$month] += $order->get_total();
	$total_dolar[$year][$month] += $custom_data['usd_price'];
	$total_gbp[$year][$month] += $order->get_subtotal() - $train_price;
	$total_gbp_train[$year][$month] += $order->get_subtotal();
	$total[$year][$month] += number_format($property_total, 2);

	// Calculate transport costs
	$average_train = (float)get_post_meta($order_id, 'average_train', true) ?: 0;
	$average_truck = (float)get_post_meta($order_id, 'average_truck', true) ?: 0;

	$total_train_calc[$year][$month] += $average_train * $property_total;
	$total_truck_calc[$year][$month] += $average_truck * $property_total;

	// Update shipping total
	$order_shipping_total = (float)$order_data['shipping_total'] ?: 0;
	$total_order_shipping[$year][$month] += $order_shipping_total;
}

// Generate the data for hidden input
$new = array();
foreach (range(11, 0) as $i) {
	$date = new DateTime("-$i months");
	$year = $date->format('Y');
	$month = $date->format('m');

	$new[$month] = $total[$year][$month] ?? 0;
}

?>
<input type="hidden" name="totalcart" value="<?php echo htmlspecialchars(json_encode($total), ENT_QUOTES, 'UTF-8'); ?>">

<?php

// End of the script
$end = microtime(true);
$execution_time = ($end - $start);
echo 'Total Execution Time: ' . $execution_time . ' seconds';
//echo '<pre>';
//print_r($sum_total_train);
//echo '</pre>';


$months = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December');


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


?>

<br>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-striped avg-table">
            <tbody>

            <tr>
                <th>Year</th>
                <th>Month</th>
                <th style="text-align:right">m2</th>
                <th style="text-align:right">USD</th>
                <th style="text-align:right">Total.Train $/m2</th>
                <th style="text-align:right">Total.Truck €/m2</th>
                <th style="text-align:right"></th>
                <th style="text-align:right">GBP</th>
                <th style="text-align:right">Delivery</th>
                <th style="text-align:right">Train</th>
                <th style="text-align:right">GBP Total</th>
            </tr>
            <?php
            $current_year = date("Y");
            $sum_tot_sqm = 0;
            $sum_tot_dolar = 0;
            $sum_tot_gbp = 0;
            $sum_tot_train = 0;
            $sum_tot_prices = 0;
            $sum_shipping = 0;
            foreach ($sum_total as $year => $sum_ty) {
                foreach ($luni as $luna) {
                    if ($sum_ty[$luna]) {
                        $sqm = $total[$year][$luna];
                        $new_price = 0;
                        if ($sqm > 0 && $year >= 2021) {
                            $train_price = 10;
                            $new_price = (float)$sqm * (float)$train_price;
                        }

                        echo '<tr>
                            <td>' . $year . '</td>
                            <td>' . $months[$luna] . '</td>
                            <td style="text-align:right">' . number_format($total[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_dolar[$year][$luna], 2) . '</td>
                            <td style="text-align:right">'.number_format($total_train_calc[$year][$luna], 2).'</td>
                            <td style="text-align:right">'.number_format($total_truck_calc[$year][$luna], 2).'</td>
                            <td></td>
                            <td style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total_train[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total[$year][$luna]/1.2, 2) . '</td>
                        </tr>';
                        $sum_tot_sqm = $sum_tot_sqm + $total[$year][$luna];
                        $sum_tot_dolar = $sum_tot_dolar + $total_dolar[$year][$luna];
                        $sum_tot_gbp = $sum_tot_gbp + $total_gbp[$year][$luna];
                        $sum_tot_prices = $sum_tot_prices + $sum_ty[$luna];
                        $sum_shipping = $sum_shipping + $total_order_shipping[$year][$luna];
                        $sum_tot_train = $sum_tot_train + $sum_total_train[$year][$luna];
                    } else {
                        echo '<tr>
                            <td>' . $year . '</td>
                            <td>' . $months[$luna] . '</td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                             <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
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
                <td style="text-align:right"></td>
                <td style="text-align:right"></td>
                <td style="text-align:right"></td>
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
                    <?php echo number_format($sum_tot_prices/1.2, 2); ?>
                </td>
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
}
?>

<script>
    jQuery(document).ready(function () {
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


    var timer = null;
    jQuery('input.avg').keyup(function(){
        clearTimeout(timer);
        timer = setTimeout(sendData, 2000)
    });
    function sendData() {
        var values = {};
        jQuery('input.avg').each(function() {
            if(this.value > 0) { values[this.name] = parseFloat(this.value); }
            else { values[this.name] = parseFloat(0); }

        });

        console.log(values);
        var data = {
            'action': 'average_per_month_train',
            'avgs': values
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
            console.log(response);
        });
    }


</script>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php
$stop = microtime(true);
print_r($stop - $start);
?>