<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');


if ($_POST['group_name']) {
    $page_title = esc_html($_POST['group_name']) . ' SQM';
} else {
    $groups_created = get_post_meta(1, 'groups_created', true);
    $page_title = 'All Groups SQM';
}
?>

<style>
    .container {
        max-width: 90vw;
    }
    .grup-section-header {
        display: flex;
        align-items: baseline;
        gap: 12px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid #1A6DB2;
    }

    .grup-section-header h3 {
        margin: 0;
        font-size: 1.35rem;
        font-weight: 700;
        color: #212529;
    }

    .grup-section-subtitle {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .grup-chart-card {
        background: #fff;
        border: 1px solid rgba(26, 54, 126, 0.1);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        padding: 16px 8px;
        margin-bottom: 20px;
    }

    .grup-chart-card #container {
        min-width: 310px;
        height: 380px;
        margin: 0 auto;
    }

    .grup-chart-card .highcharts-container {
        box-shadow: none;
        border: none;
        border-radius: 0;
        margin: 0;
    }

    .grup-table-card {
        background: #fff;
        border: 1px solid rgba(26, 54, 126, 0.1);
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .grup-table-card table {
        box-shadow: none;
        border: none;
        border-radius: 0;
        margin: 0;
    }

    .grup-table thead th {
        position: sticky;
        top: 0;
        background: #12447E;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 12px;
        border-color: rgba(255, 255, 255, 0.15);
    }

    .grup-table tbody td {
        font-size: 0.85rem;
        padding: 8px 12px;
        color: #495057;
        vertical-align: middle;
    }

    .grup-table tbody tr:hover td {
        background-color: rgba(26, 109, 178, 0.05);
    }

    .grup-table td[style*="text-align:right"],
    .grup-table th[style*="text-align:right"] {
        font-variant-numeric: tabular-nums;
    }

    .card.grup-filter-card.mb-4 {
        max-width: 100% !important;
    }

    .grup-row-empty td {
        color: #adb5bd;
    }

    .grup-table-totals td {
        background: #f0f4f8 !important;
        font-weight: 700 !important;
        color: #12447E !important;
        font-size: 0.85rem;
        border-top: 2px solid #1A6DB2;
        padding: 10px 12px;
    }

    .grup-table-card .dataTables_wrapper {
        padding: 12px 16px 16px;
        overflow: scroll;
    }

    .grup-table-card .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 0.85rem;
        outline: none;
        transition: border-color 0.2s;
    }

    .grup-table-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #1A6DB2;
        box-shadow: 0 0 0 2px rgba(26, 109, 178, 0.15);
    }

    .grup-table-card .dataTables_info {
        font-size: 0.8rem;
        color: #6c757d;
        padding: 8px 0 0;
    }

    .dt-buttons {
        margin-bottom: 10px;
    }

    .dt-buttons .dt-button,
    .dt-buttons button,
    .dt-buttons a {
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 5px !important;
        padding: 6px 16px !important;
        font-size: 0.8rem !important;
        font-weight: 500 !important;
        color: #495057 !important;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 4px;
        display: inline-block;
    }

    .dt-buttons .dt-button:hover,
    .dt-buttons button:hover,
    .dt-buttons a:hover {
        background: #1A6DB2 !important;
        border-color: #1A6DB2 !important;
        color: #fff !important;
    }
</style>

<!-- Start single user sqm -->
<div class="grup-section-header">
    <h3><?php echo $page_title; ?></h3>
    <span class="grup-section-subtitle">Monthly breakdown &middot; Last 12 months</span>
</div>

<div class="grup-chart-card">
    <div id="container"></div>
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
    &$sum_total,
    &$sum_total_train,
    &$total_dolar,
    &$total,
    &$total_gbp,
    &$total_gbp_train,
    &$total_train_calc,
    &$total_truck_calc,
    &$total_order_shipping
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
if (defined('WP_DEBUG') && WP_DEBUG) {
    echo 'Total Execution Time: ' . $execution_time . ' seconds';
}

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

<div class="grup-table-card">
    <div class="table-responsive">
        <table id="grup-sqm-table" class="table table-bordered table-hover grup-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th style="text-align:right">m&sup2;</th>
                    <th style="text-align:right">USD</th>
                    <th style="text-align:right">GBP</th>
                    <th style="text-align:right">Delivery</th>
                    <th style="text-align:right">Train</th>
                    <th style="text-align:right">GBP Total</th>
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
                $sum_tot_biowood = 0;
                $sum_tot_biowoodPlus = 0;
                $sum_tot_basswoodPlus = 0;
                $sum_tot_basswood = 0;
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
                            <td>' . $year . '</td>
                            <td data-order="' . $luna . '">' . $months[$luna] . '</td>
                            <td style="text-align:right">' . number_format($total[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_dolar[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_gbp[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_order_shipping[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total_train[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($sum_total[$year][$luna], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Earth'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Ecowood'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['EcowoodPlus'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Biowood'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['BiowoodPlus'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['BasswoodPlus'], 2) . '</td>
                            <td style="text-align:right">' . number_format($total_materials_sqm[$year][$luna]['Basswood'], 2) . '</td>
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
                            $sum_tot_basswoodPlus = $sum_tot_basswoodPlus + $total_materials_sqm[$year][$luna]['BasswoodPlus'];
                            $sum_tot_basswood = $sum_tot_basswood + $total_materials_sqm[$year][$luna]['Basswood'];
                        } else {
                            echo '<tr class="grup-row-empty">
                            <td>' . $year . '</td>
                            <td data-order="' . $luna . '">' . $months[$luna] . '</td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
                            <td style="text-align:right"></td>
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

            </tbody>
            <tfoot>
                <tr class="grup-table-totals">
                    <td colspan="2"
                        style="text-align:right">
                        Totals
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
                        <?php echo number_format($sum_tot_biowood, 2); ?>
                    </td>
                    <td style="text-align:right">
                        <?php echo number_format($sum_tot_biowoodPlus, 2); ?>
                    </td>
                    <td style="text-align:right">
                        <?php echo number_format($sum_tot_basswoodPlus, 2); ?>
                    </td>
                    <td style="text-align:right">
                        <?php echo number_format($sum_tot_basswood, 2); ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
    $month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
    $months_cart[] = $month;
} ?>

<script>
    jQuery(document).ready(function() {

        var sqm = jQuery('input[name="totalcart"]').val();
        var js_array = [<?php echo '"' . implode('","', $new) . '"' ?>];
        console.log(sqm);
        console.log(js_array);
        var ints = js_array.map(parseFloat);

        Highcharts.chart('container', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent',
                style: {
                    fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                },
                spacing: [20, 20, 20, 20]
            },
            title: {
                text: null
            },
            legend: {
                enabled: false
            },
            xAxis: {
                categories: <?php echo json_encode($months_cart); ?>,
                labels: {
                    style: {
                        color: '#6c757d',
                        fontSize: '12px'
                    }
                },
                lineColor: '#dee2e6',
                tickColor: '#dee2e6'
            },
            yAxis: [{
                title: {
                    text: 'm\u00B2',
                    style: {
                        color: '#6c757d',
                        fontSize: '13px',
                        fontWeight: '600'
                    }
                },
                min: 0,
                gridLineColor: '#f0f0f0',
                gridLineDashStyle: 'Dash',
                labels: {
                    style: {
                        color: '#6c757d',
                        fontSize: '11px'
                    }
                }
            }],
            tooltip: {
                backgroundColor: '#fff',
                borderColor: '#dee2e6',
                borderRadius: 8,
                shadow: true,
                style: {
                    fontSize: '13px'
                },
                headerFormat: '<span style="font-size:12px;color:#6c757d">{point.key}</span><br/>',
                pointFormat: '<span style="font-size:15px;font-weight:600;color:#1A6DB2">{point.y:,.0f}</span> <span style="color:#6c757d">m\u00B2</span>'
            },
            plotOptions: {
                column: {
                    borderRadius: 5,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:,.0f}',
                        style: {
                            fontSize: '11px',
                            fontWeight: '600',
                            color: '#495057',
                            textOutline: 'none'
                        },
                        y: -5
                    },
                    states: {
                        hover: {
                            brightness: -0.08
                        }
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'm\u00B2',
                data: ints.map(function(val) {
                    return {
                        y: val,
                        color: val > 0 ? {
                            linearGradient: {
                                x1: 0,
                                y1: 0,
                                x2: 0,
                                y2: 1
                            },
                            stops: [
                                [0, '#1A6DB2'],
                                [1, '#4A9FE5']
                            ]
                        } : '#dee2e6'
                    };
                })
            }]
        });

        jQuery('#grup-sqm-table').DataTable({
            paging: false,
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'print'],
            order: [
                [0, 'desc'],
                [1, 'asc']
            ],
            columnDefs: [{
                targets: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                className: 'text-end'
            }]
        });
    });
</script>

<!-- *********************************************************************************************
    End Total user sqm
*********************************************************************************************	-->

<?php
$stop = microtime(true);
if (defined('WP_DEBUG') && WP_DEBUG) {
    print_r($stop - $start);
}
?>
<!-- *********************************************************************************************
End single user sqm
*********************************************************************************************	-->