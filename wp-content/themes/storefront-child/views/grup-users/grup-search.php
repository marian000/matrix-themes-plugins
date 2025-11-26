<?php
?>
<br>
<h2>Search Group Info</h2>

<form action="" method="POST" style="display:block;">
  <div class="row">

    <div class="col-sm-3 col-lg-2 form-group">
      <br>
      <input type="submit"
             name="submit"
             value="Select"
             class="btn btn-info">
    </div>

    <div class="col-sm-4 col-lg-4 form-grup">
      <label for="q_Status">Select group to show chart info</label>
      <br>
      <select id="q_status_order_id_eq"
              name="group_name"
              class="form-control">
        <option value="">Please select...</option>
				<?php
				$groups_created = get_post_meta(1, 'groups_created', true);
				// Array of WP_User objects.
				foreach ($groups_created as $key => $group_name) {
					if ($_POST['group_name'] == $group_name) {
						echo '<option value="' . $group_name . '" selected>' . $group_name . '</option>';
					} else {
						echo '<option value="' . $group_name . '">' . $group_name . '</option>';
					}
				} ?>
        <option value="all_users">All Users</option>
      </select>
    </div>

		<?php
		$month = date("m");
		$luni = array('01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'); ?>
    <div class="col-sm-4 col-lg-3 form-group">
      <label for="select-luna">Select month:</label>
      <br>
      <select id="select-luna" name="luna_select">
				<?php foreach ($luni as $luna => $name_month) {
					if (!empty($_POST['luna_select'])) {
						$selected = ($_POST['luna_select'] == $luna) ? 'selected' : '';
					} else {
						$selected = ($month == $luna && empty($_POST['luna_select'])) ? 'selected' : '';
					} ?>
          <option value="<?php echo $luna; ?>" <?php echo $selected; ?>>
						<?php echo $name_month; ?>
          </option>
				<?php } ?>
      </select>
    </div>
    <div class="col-sm-4 col-lg-3 form-group">
      <label for="select-luna">Select year:</label>
      <br>
      <select id="select-an" name="an_select">
				<?php
				// Get the current year
				$currentYear = date("Y");

				// Initialize an empty array to hold the years
				$last10Years = [];

				// Loop to get the last 10 years
				for ($i = 0; $i < 10; $i++) {
					$last10Years[] = $currentYear - $i;
				}

				// Reverse the array to have the oldest year first
				$ani = array_reverse($last10Years);

				$current_an = date("Y");
				foreach (array_reverse($ani) as $an) {
					if (!empty($_POST['an_select'])) {
						$selected = ($_POST['an_select'] == $an) ? 'selected' : '';
					} else {
						$selected = ($current_an == $an) ? 'selected' : '';
					} ?>
          <option value="<?php echo $an; ?>" <?php echo $selected; ?>>
						<?php echo $an; ?>
          </option>
					<?php
				} ?>
      </select>
    </div>
  </div>
</form>