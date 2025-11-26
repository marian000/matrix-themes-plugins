<!-- *********************************************************************************************
    Start Total user sqm
*********************************************************************************************	-->

<br>

<form action="" method="POST" style="display:block;">
    <div class="row">

        <div class="col-sm-3 col-lg-2 form-group">
            <br>
            <input type="submit"
                   name="submit"
                   value="Select"
                   class="btn btn-info">
        </div>

        <div class="col-sm-4 col-lg-4 form-group">
            <label for="select-luna">Select Year:</label>
            <br>
			<?php
			$theDate = new DateTime('Y');
			$current_year = $theDate->format('Y');
			?>
            <select id="select-luna" name="an">
				<?php
				for ($an = $current_year; $an >= $current_year - 3; $an--) {
					?>
                    <option value="<?php echo $an; ?>" <?php if ($_POST['an'] == $an) echo 'selected'; ?>>
						<?php echo $an; ?>
                    </option>
				<?php } ?>
            </select>
        </div>
    </div>
</form>

<?php
$start = microtime(true);

$year = date('Y');
if (isset($_POST['an'])) $year = $_POST['an'];
$after = $year . '-01-1 00:00:00';
$before = $year . '-12-31 23:59:59';

global $wpdb;
$tablename = $wpdb->prefix . 'custom_orders';
//$sum_total[date('Y')] = $months_sum;
$myOrders = array();
$myResult = $wpdb->get_results("SELECT idOrder, sqm, gbp_price, subtotal_price FROM $tablename WHERE createTime BETWEEN '" . $after . "' AND '" . $before . "'", ARRAY_A);

$users = get_users(array('fields' => array('ID')));
$companies_year = array();
foreach ($myResult as $line) {

	$user_id = get_post_meta($line['idOrder'], '_customer_user', true);
	$company = get_user_meta($user_id, 'billing_company', true);

	if (array_key_exists($company, $companies_year)) {
		$companies_year[$company]['users_sqm'] = $companies_year[$company]['users_sqm'] + $line['sqm'];
		$companies_year[$company]['users_gbp'] = $companies_year[$company]['users_gbp'] + $line['subtotal_price'];
	} else {
		$companies_year[$company]['users_sqm'] = $line['sqm'];
		$companies_year[$company]['users_gbp'] = $line['subtotal_price'];
	}
	// if(in_array($user_id,$users_ids)) unset();
}



echo 'Useri cu activitate: ';
print_r(count($companies_year));

asort($companies_year);

?>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nr.</th>
        <th>User</th>
        <th>Phone</th>
        <th>SQM</th>
        <th>GBP</th>
    </tr>
    </thead>
    <tbody>
	<?php
	// Calculăm totalurile pentru toate companiile
	$total_sqm = 0;
	$total_gbp = 0;
	$i = 1;
	foreach ($companies_year as $company => $values) {
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

		$total_sqm += $values['users_sqm'];
		$total_gbp += $values['users_gbp'];

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
				<?php echo number_format($values['users_sqm'], 2); ?>
            </td>
            <td>
				<?php echo number_format($values['users_gbp'], 2); ?>
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
        <td><strong><?php echo number_format($total_sqm, 2); ?></strong></td>
        <td><strong><?php echo number_format($total_gbp, 2); ?></strong></td>
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