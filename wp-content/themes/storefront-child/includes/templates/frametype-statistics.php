<?php
defined('ABSPATH') or die();

/**
 * Frame Type Statistics Admin Page
 * Displays usage statistics for all frame types across different time periods
 */
?>

<div class="wrap">
    <h1>Frame Type Statistics</h1>
    <p>View usage statistics for all frame types across different time periods.</p>

    <div style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ccc; border-radius: 4px;">
        <div style="margin-bottom: 20px;">
            <label for="period-selector" style="font-weight: bold; margin-right: 10px;">Select Time Period:</label>
            <select id="period-selector" style="padding: 5px 10px; font-size: 14px;">
                <option value="1_month">Last 1 Month</option>
                <option value="3_months">Last 3 Months</option>
                <option value="6_months">Last 6 Months</option>
                <option value="12_months">Last 12 Months</option>
                <option value="current_year">Current Year</option>
            </select>
            <button id="refresh-stats" class="button button-primary" style="margin-left: 10px;">Refresh Statistics</button>
        </div>

        <div id="statistics-summary" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-left: 4px solid #2271b1;">
            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                <div>
                    <strong>Total Frame Types Used:</strong>
                    <span id="total-count" style="font-size: 24px; color: #2271b1; margin-left: 10px;">-</span>
                </div>
                <div>
                    <strong>Period:</strong>
                    <span id="period-display" style="margin-left: 10px;">-</span>
                </div>
                <div>
                    <strong>From Date:</strong>
                    <span id="date-start" style="margin-left: 10px;">-</span>
                </div>
            </div>
        </div>

        <div id="category-breakdown" style="margin: 20px 0;">
            <h3>Category Breakdown</h3>
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div style="padding: 10px; background: #e3f2fd; border-radius: 4px; min-width: 150px;">
                    <strong>Basswood:</strong> <span id="cat-basswood" style="font-size: 20px; color: #1976d2;">0</span>
                </div>
                <div style="padding: 10px; background: #f3e5f5; border-radius: 4px; min-width: 150px;">
                    <strong>PVC:</strong> <span id="cat-pvc" style="font-size: 20px; color: #7b1fa2;">0</span>
                </div>
                <div style="padding: 10px; background: #e8f5e9; border-radius: 4px; min-width: 150px;">
                    <strong>Aluminium:</strong> <span id="cat-aluminium" style="font-size: 20px; color: #388e3c;">0</span>
                </div>
                <div style="padding: 10px; background: #fff3e0; border-radius: 4px; min-width: 150px;">
                    <strong>Track:</strong> <span id="cat-track" style="font-size: 20px; color: #f57c00;">0</span>
                </div>
                <div style="padding: 10px; background: #fce4ec; border-radius: 4px; min-width: 150px;">
                    <strong>Special:</strong> <span id="cat-special" style="font-size: 20px; color: #c2185b;">0</span>
                </div>
            </div>
        </div>

        <div id="loading-indicator" style="display: none; text-align: center; padding: 20px;">
            <span class="spinner is-active" style="float: none;"></span>
            <p>Loading statistics...</p>
        </div>

        <div id="statistics-table-container" style="margin-top: 20px;">
            <table id="frametype-statistics-table" class="wp-list-table widefat fixed striped" style="display: none;">
                <thead>
                    <tr>
                        <th>Frame Type ID</th>
                        <th>Frame Type Name</th>
                        <th>Category</th>
                        <th>Usage Count</th>
                    </tr>
                </thead>
                <tbody id="statistics-tbody">
                    <!-- Data will be populated via AJAX -->
                </tbody>
                <tfoot>
                    <tr>
                        <th>Frame Type ID</th>
                        <th>Frame Type Name</th>
                        <th>Category</th>
                        <th>Usage Count</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div id="chart-container" style="margin-top: 30px; display: none;">
            <h3>Top 10 Frame Types</h3>
            <div id="frametype-chart" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    var dataTable = null;

    function loadStatistics() {
        var period = $('#period-selector').val();

        // Show loading indicator
        $('#loading-indicator').show();
        $('#statistics-table-container table').hide();
        $('#chart-container').hide();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'get_frametype_statistics',
                period: period
            },
            success: function(response) {
                $('#loading-indicator').hide();

                if (response.success) {
                    // Update summary
                    $('#total-count').text(response.total_count);
                    $('#period-display').text(period.replace('_', ' '));
                    $('#date-start').text(response.date_start);

                    // Update category breakdown
                    $('#cat-basswood').text(response.category_totals.Basswood || 0);
                    $('#cat-pvc').text(response.category_totals.PVC || 0);
                    $('#cat-aluminium').text(response.category_totals.Aluminium || 0);
                    $('#cat-track').text(response.category_totals.Track || 0);
                    $('#cat-special').text(response.category_totals.Special || 0);

                    // Populate table
                    var tbody = $('#statistics-tbody');
                    tbody.empty();

                    if (response.statistics.length > 0) {
                        $.each(response.statistics, function(index, item) {
                            var categoryColor = '';
                            switch(item.category) {
                                case 'Basswood': categoryColor = '#1976d2'; break;
                                case 'PVC': categoryColor = '#7b1fa2'; break;
                                case 'Aluminium': categoryColor = '#388e3c'; break;
                                case 'Track': categoryColor = '#f57c00'; break;
                                case 'Special': categoryColor = '#c2185b'; break;
                            }

                            tbody.append(
                                '<tr>' +
                                '<td>' + item.id + '</td>' +
                                '<td><strong>' + item.name + '</strong></td>' +
                                '<td style="color: ' + categoryColor + '; font-weight: bold;">' + item.category + '</td>' +
                                '<td><strong>' + item.usage_count + '</strong></td>' +
                                '</tr>'
                            );
                        });

                        $('#statistics-table-container table').show();

                        // Initialize or reinitialize DataTable
                        if (dataTable !== null) {
                            dataTable.destroy();
                        }

                        dataTable = $('#frametype-statistics-table').DataTable({
                            "order": [[3, "desc"]],
                            "pageLength": 25,
                            "dom": 'Bfrtip',
                            "buttons": [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                            ]
                        });

                        // Create chart with top 10
                        createChart(response.statistics.slice(0, 10));
                    } else {
                        tbody.append('<tr><td colspan="4" style="text-align: center;">No data available for selected period</td></tr>');
                        $('#statistics-table-container table').show();
                    }
                } else {
                    alert('Error loading statistics');
                }
            },
            error: function() {
                $('#loading-indicator').hide();
                alert('Error loading statistics. Please try again.');
            }
        });
    }

    function createChart(data) {
        if (data.length === 0) {
            return;
        }

        var categories = [];
        var counts = [];

        $.each(data, function(index, item) {
            categories.push(item.name);
            counts.push(item.usage_count);
        });

        $('#chart-container').show();

        Highcharts.chart('frametype-chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Top 10 Most Used Frame Types'
            },
            xAxis: {
                categories: categories,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Usage Count'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">Usage: </td>' +
                    '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Frame Types',
                data: counts,
                colorByPoint: true
            }],
            credits: {
                enabled: false
            }
        });
    }

    // Load statistics on page load
    loadStatistics();

    // Refresh button click handler
    $('#refresh-stats').on('click', function() {
        loadStatistics();
    });

    // Period selector change handler
    $('#period-selector').on('change', function() {
        loadStatistics();
    });
});
</script>

<style>
.wrap {
    margin: 20px 20px 0 2px;
}

#frametype-statistics-table {
    width: 100%;
}

#frametype-statistics-table th {
    font-weight: bold;
    background: #f0f0f1;
}

#frametype-statistics-table td {
    padding: 8px;
}

.dt-buttons {
    margin-bottom: 10px;
}

.dt-button {
    background: #2271b1 !important;
    color: white !important;
    border: none !important;
    padding: 6px 12px !important;
    margin-right: 5px !important;
    border-radius: 3px !important;
    cursor: pointer !important;
}

.dt-button:hover {
    background: #135e96 !important;
}
</style>
