<?php
?>
<div class="card grup-filter-card mb-4">
  <div class="card-body">
    <h6 class="card-title text-muted mb-3">Filter</h6>
    <form action="" method="POST">
      <div class="row g-3 align-items-end">

        <div class="col-sm-4 col-lg-4">
          <label for="q_status_order_id_eq" class="form-label">Select group to show chart info</label>
          <select id="q_status_order_id_eq"
                  name="group_name"
                  class="form-select">
            <option value="">Please select...</option>
            <?php
            $groups_created = get_post_meta(1, 'groups_created', true);
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
        <div class="col-sm-4 col-lg-3">
          <label for="select-luna" class="form-label">Select month</label>
          <select id="select-luna" name="luna_select" class="form-select">
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

        <div class="col-sm-4 col-lg-3">
          <label for="select-an" class="form-label">Select year</label>
          <select id="select-an" name="an_select" class="form-select">
            <?php
            $currentYear = date("Y");
            $last10Years = [];
            for ($i = 0; $i < 10; $i++) {
              $last10Years[] = $currentYear - $i;
            }
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
            <?php } ?>
          </select>
        </div>

        <div class="col-sm-3 col-lg-2">
          <input type="submit"
                 name="submit"
                 value="Select"
                 class="btn btn-primary w-100">
        </div>

      </div>
    </form>
  </div>
</div>
