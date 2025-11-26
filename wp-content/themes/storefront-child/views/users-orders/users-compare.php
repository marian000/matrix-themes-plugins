<?php
// Funcția care grupează comenzile pe companie și calculează totalurile SQM și GBP pentru un anumit an
function getOrdersCompaniesByYear($orders, $year = null)
{
	// Dacă anul nu este specificat, se folosește anul curent
	if (empty($year)) {
		$year = date('Y');
	}
	// Inițializăm array-urile pentru totaluri pe companie
	$company_sqm = array();
	$company_gbp = array();

	global $wpdb;
	$tablename = $wpdb->prefix . 'custom_orders';

	// Parcurgem fiecare comandă
	foreach ($orders as $id_order) {
		// Obținem obiectul comenzii
		$order = wc_get_order($id_order);

		// Preluăm valoarea SQM din tabelul custom
		$myOrder = $wpdb->get_row("SELECT sqm FROM $tablename WHERE idOrder = $id_order", ARRAY_A);
		$property_total = isset($myOrder['sqm']) ? $myOrder['sqm'] : 0;

		// Preluăm prețul în GBP din comanda WooCommerce
		$property_total_gbp = $order->get_subtotal();

// Obținem ID-ul clientului din comandă
		$customer_id = $order->get_customer_id();

// Dacă avem un ID valid, preluăm billing_company din meta datele utilizatorului
		if ($customer_id) {
			$company = get_user_meta($customer_id, 'billing_company', true);
		} else {
			// Dacă nu avem un client asociat (de exemplu, comandă guest), folosim fallback-ul din meta datele comenzii
			$company = get_post_meta($id_order, 'billing_company', true);
		}

		if (empty($company)) {
			$company = "No Company"; // Valoare implicită dacă nu este setată compania
		}

		// Grupăm și sumăm totalurile pe companie
		if (array_key_exists($company, $company_sqm)) {
			$company_sqm[$company] += $property_total;
			$company_gbp[$company] += $property_total_gbp;
		} else {
			$company_sqm[$company] = $property_total;
			$company_gbp[$company] = $property_total_gbp;
		}
	}
	return array($company_sqm, $company_gbp);
}


// Funcția care generează argumentele pentru interogarea comenzilor filtrate pe lună și an
function queryArgsForOrdersFilteredMonth($month = null, $year = null)
{
	// Dacă luna sau anul nu sunt specificate, se folosesc valorile curente
	if (empty($month)) {
		$month = date('m');
	}
	if (empty($year)) {
		$year = date('Y');
	}
	// Stabilim data de început și sfârșit a lunii respective
	$after = date('Y-m-01 00:00:00', strtotime("$year-$month-01"));
	$before = date('Y-m-t 23:59:59', strtotime("$year-$month-01"));

	$args = array(
	  'limit' => -1,
	  'type' => 'shop_order',
	  'status' => array(
		'wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing',
		'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision',
	  ),
	  'orderby' => 'date',
	  'date_query' => array(
		'after' => $after,
		'before' => $before,
	  ),
	  'return' => 'ids',
	);
	return $args;
}


?>

<!-- Formularul pentru selectarea lunii -->
<form action="" method="POST" style="display:block;">
    <div class="row">
        <div class="col-sm-3 col-lg-2 form-group">
            <br>
            <input type="submit" name="submit" value="Select" class="btn btn-info">
        </div>

		<?php
		// Definim lunile pentru selectare
		$luni = array(
		  '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
		  '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
		  '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December',
		);
		// Dacă se selectează o lună, o preluăm din $_POST; altfel, folosim luna curentă
		$selectedMonth = isset($_POST['luna']) ? $_POST['luna'] : date('m');
		?>
        <div class="col-sm-4 col-lg-4 form-group">
            <label for="select-luna">Select Month:</label>
            <br>
            <select id="select-luna" name="luna">
				<?php foreach ($luni as $lunaNr => $luna): ?>
                    <option value="<?php echo $lunaNr; ?>" <?php if ($selectedMonth == $lunaNr) echo 'selected'; ?>>
						<?php echo $luna; ?>
                    </option>
				<?php endforeach; ?>
            </select>
        </div>
    </div>
</form>

<?php
// Stabilim anii pentru care dorim să afișăm datele: anul curent și ultimii 3 ani
$currentYear = date('Y');
$years = array($currentYear - 3, $currentYear - 2, $currentYear - 1, $currentYear);

// Pentru fiecare an, obținem comenzile și grupăm totalurile pe companie
$companiesData = array();
foreach ($years as $year) {
	$args = queryArgsForOrdersFilteredMonth($selectedMonth, $year);
	$orders = wc_get_orders($args);
	// getOrdersCompaniesByYear returnează un array: [0] => company_sqm, [1] => company_gbp
	$companiesData[$year] = getOrdersCompaniesByYear($orders, $year);
}

// Obținem lista unică de companii din toate anii pentru a construi rândurile tabelului
$allCompanies = array();
foreach ($years as $year) {
	if (isset($companiesData[$year][0]) && is_array($companiesData[$year][0])) {
		$allCompanies = array_merge($allCompanies, array_keys($companiesData[$year][0]));
	}
}
$allCompanies = array_unique($allCompanies);
sort($allCompanies);

// Calculăm totalurile pentru fiecare an (SQM și GBP) pentru footer
$footer_totals = array();
foreach ($years as $year) {
	$footer_totals[$year]['sqm'] = 0;
	$footer_totals[$year]['gbp'] = 0;
	if (isset($companiesData[$year][0]) && is_array($companiesData[$year][0])) {
		foreach ($companiesData[$year][0] as $company => $sqm) {
			$footer_totals[$year]['sqm'] += $sqm;
		}
	}
	if (isset($companiesData[$year][1]) && is_array($companiesData[$year][1])) {
		foreach ($companiesData[$year][1] as $company => $gbp) {
			$footer_totals[$year]['gbp'] += $gbp;
		}
	}
}
?>

<br>
<div class="row">
    <div class="col-md-12">
        <div id="companies_comparison">
            <table class="table table-bordered table-striped">
                <thead>
                <!-- Prima linie de header: pentru fiecare an, se alocă două coloane (SQM și GBP) -->
                <tr>
                    <th rowspan="2">Nr.</th>
                    <th rowspan="2">Company</th>
					<?php foreach ($years as $year): ?>
                        <th colspan="2" class="text-center"><?php echo $year; ?></th>
					<?php endforeach; ?>
                </tr>
                <!-- A doua linie de header: numele coloanelor pentru fiecare an -->
                <tr>
					<?php foreach ($years as $year): ?>
                        <th>SQM</th>
                        <th>GBP</th>
					<?php endforeach; ?>
                </tr>
                </thead>
                <tbody>
				<?php $i = 1; ?>
				<?php foreach ($allCompanies as $company): ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $company; ?></td>
						<?php foreach ($years as $year): ?>
							<?php
							// Pentru fiecare an, verificăm dacă există date pentru compania curentă
							$sqm = isset($companiesData[$year][0][$company]) ? number_format($companiesData[$year][0][$company], 2) : '';
							$gbp = isset($companiesData[$year][1][$company]) ? number_format($companiesData[$year][1][$company], 2) : '';
							?>
                            <td><?php echo $sqm; ?></td>
                            <td><?php echo $gbp; ?></td>
						<?php endforeach; ?>
                    </tr>
					<?php $i++; ?>
				<?php endforeach; ?>
                </tbody>
                <!-- Footer-ul tabelului cu totalurile pe coloane -->
                <tfoot>
                <tr>
                    <!-- Coloanele inițiale: "Nr." și "Company" -->
                    <th colspan="2" class="text-center">Total</th>
		            <?php foreach ($years as $year): ?>
                        <th><?php echo number_format($footer_totals[$year]['sqm'], 2); ?></th>
                        <th><?php echo number_format($footer_totals[$year]['gbp'], 2); ?></th>
		            <?php endforeach; ?>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>