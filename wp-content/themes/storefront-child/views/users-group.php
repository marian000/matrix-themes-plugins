<!-- Latest compiled and minified CSS -->
<!--<link rel="stylesheet"-->
<!--      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
<!---->
<!-- Latest compiled JavaScript -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<!-- Use Font Awesome Free CDN modified-->
<!--<link rel="stylesheet"-->
<!--      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">-->

<script type='text/javascript'
        src='/wp-content/themes/storefront-child/js/highcharts.js'></script>
<style>#container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }</style>
<div id="primary"
     class="content-area container">
  <main id="main"
        class="site-main"
        role="main">

    <h2>Users Group</h2>

    <!-- Multi Cart Template -->
		<?php if (is_active_sidebar('multicart_widgett')) : ?>
      <div id="bmc-woocom-multisession-2"
           class="widget bmc-woocom-multisession-widget">
				<?php //dynamic_sidebar( 'multicart_widgett' ); ?>
      </div>
      <!-- #primary-sidebar -->
		<?php endif;

		$user_id = get_current_user_id();
		$meta_key = 'wc_multiple_shipping_addresses';

		global $wpdb;
		if ($addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key))) {
			$addresses = maybe_unserialize($addresses);
		}

		$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");

		/*
				 * Create Group
				 * Delete Group
				 * Rename Group
				 */

		?>
    <div id="add_grup_section" style="display: none;">
			<?php
			include_once(get_stylesheet_directory() . '/views/grup-users/grup-malipulation.php');
			?>
    </div>
		<?php
		/*
				 * Search Group
				 */
		include_once(get_stylesheet_directory() . '/views/grup-users/grup-search.php');

		?>


    <!--
		**
		**
		Old display chart from user grafic
		**
		**
		-->

    <!--    <ul class="nav nav-pills nav-fill">-->
    <!--    <li class="nav-item"  role="presentation">-->
    <!--        <a class="nav-link active" data-toggle="tab" href="#grup-sqm">Year SQM</a>-->
    <!--      </li>-->
    <!--      <li  class="nav-item" role="presentation">-->
    <!--        <a class="nav-link" data-toggle="tab" href="#grup-month">Month SQM</a>-->
    <!--      </li>-->
    <!--      <li  class="nav-item" role="presentation">-->
    <!--        <a class="nav-link" data-toggle="tab" href="#grup-perfect-shutter">Perfect Shutter</a>-->
    <!--      </li>-->
    <!--    </ul>-->

    <ul class="nav nav-pills nav-fill mt-5" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <a class="nav-link active" id="grup-sqm-tab" data-bs-toggle="tab" data-bs-target="#grup-sqm" type="button" role="tab" aria-controls="grup-sqm"
           aria-selected="true">Year SQM
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="grup-month-tab" data-bs-toggle="tab" data-bs-target="#grup-month" type="button" role="tab" aria-controls="grup-month"
           aria-selected="false">Month SQM
        </a>
      </li>
      <li class="nav-item" role="presentation">
        <a class="nav-link" id="grup-perfect-shutter-tab" data-bs-toggle="tab" data-bs-target="#grup-perfect-shutter" type="button" role="tab"
           aria-controls="grup-perfect-shutter" aria-selected="false">Perfect Shutter
        </a>
      </li>
    </ul>

    <div class="tab-content" style="display: block;">

      <div id="grup-sqm" class="tab-pane active" role="tabpanel" aria-labelledby="grup-sqm">
        <div id="content-grup-sqm" class="mt-3">
          <button class="btn btn-primary" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
          </button>
        </div>
				<?php
				/*
							 * SQM Group Tab
							 */
				//				include_once(get_stylesheet_directory() . '/views/grup-users/grup-sqm.php');
				?>
      </div>
      <div id="grup-month" class="tab-pane" role="tabpanel" aria-labelledby="grup-month">
        <div id="content-grup-month" class="mt-3">
          <button class="btn btn-primary" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
          </button>
        </div>
				<?php
				/*
							 * SQM Group Tab
							 */
				//				include_once(get_stylesheet_directory() . '/views/grup-users/grup-month.php');
				?>
      </div>
      <div id="grup-perfect-shutter" class="tab-pane" role="tabpanel" aria-labelledby="grup-perfect-shutter">
        <div id="content-grup-perfect-shutter" class="mt-3"></div>
				<?php
				/*
							 * SQM Group Tab
							 */
				include_once(get_stylesheet_directory() . '/views/grup-users/grup-perfect.php');
				?>
      </div>
      <!--            <div id="grup-all" class="tab-pane fade">-->
      <!--                --><?php
			//                /*
			//               * SQM Group Tab
			//               */
			//                include_once(get_stylesheet_directory() . '/views/grup-users/grup-allusers.php');
			//                ?>
      <!--            </div>-->
    </div>

    <table id="orders-table" class="table table-bordered table-striped hide hidden">
      <thead>
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
        <th style="text-align:right">Green</th>
        <th style="text-align:right">Biowood</th>
        <th style="text-align:right">BiowoodPlus</th>
        <th style="text-align:right">Supreme</th>
      </tr>
      </thead>
      <tbody>
      <!-- Rows will be inserted here by jQuery -->
      </tbody>
    </table>

  </main>
  <!-- #main -->
</div>
<!-- #primary -->


<?php
$months_cart = array();
for ($i = 11; $i >= 0; $i--) {
	$month = date('M y', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
	$months_cart[] = $month;
}
?>

<script>


    jQuery(document).ready(function ($) {
        var currentPage = 1;
        var perPage = 100;
        var isLoading = false;
        var allData = [];
        var totalSQMbyMonths = [];
        console.log(ajaxurl);

        function loadOrderBatch(page) {
            console.log('page, perPage: ', page, perPage);

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'process_order_batch',
                    page: page,
                    per_page: perPage,
                    an_select: jQuery('#select-an').val() // Assuming this is the selected year
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Batch processed successfully.', response);

                        // Merge the new data with allData
                        mergeData(response.data);

                        // Update the table with the response data
                        updateTable(allData);

                        // Check if there are more orders to load
                        if (response.data.count >= perPage) {
                            currentPage++;
                            console.log('Loading next batch...', currentPage);
                            loadOrderBatch(currentPage);
                        } else {
                            // All batches processed, compile final results
                            compileFinalResults(allData);
                        }
                    } else {
                        console.log('Failed to process batch');
                    }
                    isLoading = false;
                },
                error: function (xhr, status, error) {
                    console.log('AJAX error:', error);
                    isLoading = false;
                }
            });
        }

        function updateTable(data) {
            console.log('Updating table with data... ', data);
            var rows = '';
            var totals = {
                sqm: 0,
                dolar: 0,
                gbp: 0,
                shipping: 0,
                train: 0,
                prices: 0,
                earth: 0,
                ecowood: 0,
                ecowoodPlus: 0,
                green: 0,
                biowood: 0,
                biowoodPlus: 0,
                supreme: 0
            };
            // parse object data

            Object.entries(data).forEach(function ([key, item], idx, arr) {
                // console.log('item: ', item);
                if (key == 'count') return;

                rows += '<tr>';
                rows += '<td>' + item.year + '</td>';
                rows += '<td>' + item.month + '</td>';
                rows += '<td style="text-align:right">' + item.sqm + '</td>';
                rows += '<td style="text-align:right">' + item.dolar + '</td>';
                rows += '<td style="text-align:right">' + item.gbp + '</td>';
                rows += '<td style="text-align:right">' + item.shipping + '</td>';
                rows += '<td style="text-align:right">' + item.train + '</td>';
                rows += '<td style="text-align:right">' + item.prices + '</td>';
                rows += '<td style="text-align:right">' + item.earth + '</td>';
                rows += '<td style="text-align:right">' + item.ecowood + '</td>';
                rows += '<td style="text-align:right">' + item.ecowoodPlus + '</td>';
                rows += '<td style="text-align:right">' + item.green + '</td>';
                rows += '<td style="text-align:right">' + item.biowood + '</td>';
                rows += '<td style="text-align:right">' + item.biowoodPlus + '</td>';
                rows += '<td style="text-align:right">' + item.supreme + '</td>';
                rows += '</tr>';

                // Update totals
                totals.sqm += parseFloat(item.sqm);
                totals.dolar += parseFloat(item.dolar);
                totals.gbp += parseFloat(item.gbp);
                totals.shipping += parseFloat(item.shipping);
                totals.train += parseFloat(item.train);
                totals.prices += parseFloat(item.prices);
                totals.earth += parseFloat(item.earth);
                totals.ecowood += parseFloat(item.ecowood);
                totals.ecowoodPlus += parseFloat(item.ecowoodPlus);
                totals.green += parseFloat(item.green);
                totals.biowood += parseFloat(item.biowood);
                totals.biowoodPlus += parseFloat(item.biowoodPlus);
                totals.supreme += parseFloat(item.supreme);

                // Update totalSQMbyMonths
                totalSQMbyMonths.push({ month: item.month, totalSQM: totals.sqm });
            });


            // Add totals row
            rows += '<tr>';
            rows += '<td colspan="2" style="text-align:right"><strong>Totals</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.sqm.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.dolar.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.gbp.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.shipping.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.train.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.prices.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.earth.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.ecowood.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.ecowoodPlus.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.green.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.biowood.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.biowoodPlus.toFixed(2) + '</strong></td>';
            rows += '<td style="text-align:right"><strong>' + totals.supreme.toFixed(2) + '</strong></td>';
            rows += '</tr>';
            $('#orders-table tbody').html(rows);
        }

        function mergeData(newData) {
            Object.entries(newData).forEach(function ([key, newItem]) {
                if (key == 'count') return;

                // Check if the item already exists in allData
                if (allData[key]) {
                    var existingItem = allData[key];

                    // Update existing item with new data
                    existingItem.sqm = (parseFloat(existingItem.sqm) + parseFloat(newItem.sqm)).toFixed(2);
                    existingItem.dolar = (parseFloat(existingItem.dolar) + parseFloat(newItem.dolar)).toFixed(2);
                    existingItem.gbp = (parseFloat(existingItem.gbp) + parseFloat(newItem.gbp)).toFixed(2);
                    existingItem.shipping = (parseFloat(existingItem.shipping) + parseFloat(newItem.shipping)).toFixed(2);
                    existingItem.train = (parseFloat(existingItem.train) + parseFloat(newItem.train)).toFixed(2);
                    existingItem.prices = (parseFloat(existingItem.prices) + parseFloat(newItem.prices)).toFixed(2);
                    existingItem.earth = (parseFloat(existingItem.earth) + parseFloat(newItem.earth)).toFixed(2);
                    existingItem.ecowood = (parseFloat(existingItem.ecowood) + parseFloat(newItem.ecowood)).toFixed(2);
                    existingItem.ecowoodPlus = (parseFloat(existingItem.ecowoodPlus) + parseFloat(newItem.ecowoodPlus)).toFixed(2);
                    existingItem.green = (parseFloat(existingItem.green) + parseFloat(newItem.green)).toFixed(2);
                    existingItem.biowood = (parseFloat(existingItem.biowood) + parseFloat(newItem.biowood)).toFixed(2);
                    existingItem.biowoodPlus = (parseFloat(existingItem.biowoodPlus) + parseFloat(newItem.biowoodPlus)).toFixed(2);
                    existingItem.supreme = (parseFloat(existingItem.supreme) + parseFloat(newItem.supreme)).toFixed(2);
                } else {
                    // Add new item to allData
                    allData[key] = newItem;
                }
            });
        }

        function compileFinalResults() {
            // Perform final processing of results if needed
            // This function will be called once all batches are processed
            // You can retrieve the accumulated results from session or transient
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'compile_final_results',
                },
                success: function (response) {
                    if (response.success) {
                        console.log('Final results compiled successfully.');
                        // Handle the final results
                    } else {
                        console.log('Failed to compile final results');
                    }
                },
                error: function (xhr, status, error) {
                    console.log('AJAX error:', error);
                }
            });
        }

        // Start loading orders
        // loadOrderBatch(currentPage);
        //
        // // Example button click to reload data
        // jQuery('.btn.btn-info').on('click', function () {
        //     currentPage = 1;
        //     jQuery('#orders-table tbody').empty(); // Clear the table
        //     loadOrderBatch(currentPage);
        // });
    });


    jQuery(document).ready(function () {


        // Function to handle AJAX request
        function loadContent(url, contentDiv, timeDiv) {
            var startTime = new Date().getTime();
            console.log(contentDiv + ' loading...');

            jQuery.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    var endTime = new Date().getTime();
                    var loadingTime = (endTime - startTime) / 1000; // Convert to seconds

                    jQuery(contentDiv).html(response);
                    console.log('Loaded in ' + loadingTime + ' seconds')
                },
                error: function (xhr, status, error) {
                    console.log('AJAX error:', error);
                    var endTime = new Date().getTime();
                    var loadingTime = (endTime - startTime) / 1000; // Convert to seconds
                    console.log('Loaded in ' + loadingTime + ' seconds')

                }
            });
        }

        // URLs for the AJAX requests
        var urlGrupSqm = "/wp-content/themes/storefront-child/views/grup-users/grup-sqm.php";
        var urlGrupMonth = "/wp-content/themes/storefront-child/views/grup-users/grup-month.php";

        // Load the content
        loadContent(urlGrupSqm, '#content-grup-sqm', '#loading-time-sqm');
        loadContent(urlGrupMonth, '#content-grup-month', '#loading-time-month');


        var firstTabEl = document.querySelector('#myTab li:first-child a')
        var firstTab = new bootstrap.Tab(firstTabEl)

        firstTab.show()


    });


</script>
