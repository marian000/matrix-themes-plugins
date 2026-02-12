<?php

$app_access = get_user_meta($user->id, 'app_access', true);
$suspended = get_user_meta($user->id, 'suspended_user', true);
$view_price = get_user_meta($user->id, 'view_price', true);
$show_biowood = get_user_meta($user->id, 'show_biowood', true);
$show_basswood = get_user_meta($user->id, 'show_basswood', true);
$favorite = get_user_meta($user->id, 'favorite_user', true);
$vat_number = get_user_meta($user->id, 'vat_number_custom', true);
$email_contabil = get_user_meta($user->id, 'email_contabil', true);
$discount_custom = get_user_meta($user->id, 'discount_custom', true);
$train_price = get_user_meta($user->id, 'train_price', true);
$group_added = get_user_meta($user->id, 'group_added', true);
$roles = $user->roles;

$properties = array(3 => 'BattenStandard', 4 => 'BattenCustom', 188 => 'Ecowood', 5 => 'EcowoodPlus', 138 => 'BiowoodPlus', 6 => 'Biowood', 137 => 'Green', 139 => 'BasswoodPlus', 147 => 'Basswood', 187 => 'Earth', 221 => 'Solid-%', 229 => 'Combi-%', 33 => 'Shaped-%', 34 => 'French Door-+', 35 => 'Tracked-+', 37 => 'TrackedByPass-+', 36 => 'Arched-%', 56 => 'Inside-%', 100 => 'Buildout-%', 186 => 'Hidden-%', 93 => 'Stainless Steel-%', 403 => 'Concealed Rod-%', 101 => 'Bay Angle-%', 264 => 'Colors-%', 102 => 'Ringpull-+', 103 => 'Spare_Louvres-+', 171 => 'P4028X-%', 319 => 'P4008W-%', 322 => 'P4008T-%', 353 => '4008T-%', 275 => 'T_Buildout-%', 276 => 'B_Buildout-%', 277 => 'C_Buildout-%', 278 => 'Lock-+', 500 => 'G_post-%', 501 => 'blackoutblind-+', 52 => 'Flat Louver-%', 2 => 'B_typeFlexible-%', 1 => 'T_typeAdjustable-%', 502 => 'tposttype_blackout-%', 503 => 'bposttype_blackout-%');

$propertie_price = array(3 => get_user_meta($user->id, 'BattenStandard', true), 4 => get_user_meta($user->id, 'BattenCustom', true), 188 => get_user_meta($user->id, 'Ecowood', true), 5 => get_user_meta($user->id, 'EcowoodPlus', true), 138 => get_user_meta($user->id, 'BiowoodPlus', true), 6 => get_user_meta($user->id, 'Biowood', true), 137 => get_user_meta($user->id, 'Green', true), 139 => get_user_meta($user->id, 'BasswoodPlus', true), 147 => get_user_meta($user->id, 'Basswood', true), 187 => get_user_meta($user->id, 'Earth', true), 221 => get_user_meta($user->id, 'Solid', true), 229 => get_user_meta($user->id, 'Combi', true), 33 => get_user_meta($user->id, 'Shaped', true), 34 => get_user_meta($user->id, 'French_Door', true), 35 => get_user_meta($user->id, 'Tracked', true), 37 => get_user_meta($user->id, 'TrackedByPass', true), 36 => get_user_meta($user->id, 'Arched', true), 56 => get_user_meta($user->id, 'Inside', true), 171 => get_user_meta($user->id, 'P4028X', true), 319 => get_user_meta($user->id, 'P4008W', true), 322 => get_user_meta($user->id, 'P4008T', true), 353 => get_user_meta($user->id, '4008T', true), 100 => get_user_meta($user->id, 'Buildout', true), 93 => get_user_meta($user->id, 'Stainless_Steel', true), 186 => get_user_meta($user->id, 'Hidden', true), 403 => get_user_meta($user->id, 'Concealed_Rod', true), 101 => get_user_meta($user->id, 'Bay_Angle', true), 264 => get_user_meta($user->id, 'Colors', true), 102 => get_user_meta($user->id, 'Ringpull', true), 103 => get_user_meta($user->id, 'Spare_Louvres', true), 275 => get_user_meta($user->id, 'T_Buildout', true), 276 => get_user_meta($user->id, 'B_Buildout', true), 277 => get_user_meta($user->id, 'C_Buildout', true), 278 => get_user_meta($user->id, 'Lock', true), 500 => get_user_meta($user->id, 'G_post', true), 501 => get_user_meta($user->id, 'blackoutblind', true), 52 => get_user_meta($user->id, 'Flat_Louver', true), 2 => get_user_meta($user->id, 'B_typeFlexible', true), 1 => get_user_meta($user->id, 'T_typeAdjustable', true), 502 => get_user_meta($user->id, 'tposttype_blackout', true), 503 => get_user_meta($user->id, 'bposttype_blackout', true));

$propertie_price_dolar = array(3 => get_user_meta($user->id, 'BattenStandard-dolar', true), 4 => get_user_meta($user->id, 'BattenCustom-dolar', true), 188 => get_user_meta($user->id, 'Ecowood-dolar', true), 5 => get_user_meta($user->id, 'EcowoodPlus-dolar', true), 138 => get_user_meta($user->id, 'BiowoodPlus-dolar', true), 6 => get_user_meta($user->id, 'Biowood-dolar', true), 137 => get_user_meta($user->id, 'Green-dolar', true), 139 => get_user_meta($user->id, 'BasswoodPlus-dolar', true), 139 => get_user_meta($user->id, 'Basswood-dolar', true), 187 => get_user_meta($user->id, 'Earth-dolar', true), 221 => get_user_meta($user->id, 'Solid-dolar', true), 229 => get_user_meta($user->id, 'Combi-dolar', true), 33 => get_user_meta($user->id, 'Shaped-dolar', true), 34 => get_user_meta($user->id, 'French_Door-dolar', true), 35 => get_user_meta($user->id, 'Tracked-dolar', true), 37 => get_user_meta($user->id, 'TrackedByPass-dolar', true), 36 => get_user_meta($user->id, 'Arched-dolar', true), 56 => get_user_meta($user->id, 'Inside-dolar', true), 171 => get_user_meta($user->id, 'P4028X-dolar', true), 319 => get_user_meta($user->id, 'P4008W-dolar', true), 322 => get_user_meta($user->id, 'P4008T-dolar', true), 353 => get_user_meta($user->id, '4008T-dolar', true), 100 => get_user_meta($user->id, 'Buildout-dolar', true), 93 => get_user_meta($user->id, 'Stainless_Steel-dolar', true), 186 => get_user_meta($user->id, 'Hidden-dolar', true), 403 => get_user_meta($user->id, 'Concealed_Rod-dolar', true), 101 => get_user_meta($user->id, 'Bay_Angle-dolar', true), 264 => get_user_meta($user->id, 'Colors-dolar', true), 102 => get_user_meta($user->id, 'Ringpull-dolar', true), 103 => get_user_meta($user->id, 'Spare_Louvres-dolar', true), 275 => get_user_meta($user->id, 'T_Buildout-dolar', true), 276 => get_user_meta($user->id, 'B_Buildout-dolar', true), 277 => get_user_meta($user->id, 'C_Buildout-dolar', true), 278 => get_user_meta($user->id, 'Lock-dolar', true), 500 => get_user_meta($user->id, 'G_post-dolar', true), 501 => get_user_meta($user->id, 'blackoutblind-dolar', true), 52 => get_user_meta($user->id, 'Flat_Louver-dolar', true), 2 => get_user_meta($user->id, 'B_typeFlexible-dolar', true), 1 => get_user_meta($user->id, 'T_typeAdjustable-dolar', true), 502 => get_user_meta($user->id, 'tposttype_blackout-dolar', true), 503 => get_user_meta($user->id, 'bposttype_blackout-dolar', true));



$properties_tax = array(3 => 'BattenStandard_tax', 4 => 'BattenCustom_tax', 187 => 'Earth_tax', 188 => 'Ecowood_tax', 137 => 'Green_tax', 138 => 'Biowood_tax', 139 => 'Supreme_tax', 444 => 'SeaDelivery-+');

$propertie_price_tax = array(3 => get_user_meta($user->id, 'BattenStandard_tax', true), 4 => get_user_meta($user->id, 'BattenCustom_tax', true), 187 => get_user_meta($user->id, 'Earth_tax', true), 188 => get_user_meta($user->id, 'Ecowood_tax', true), 5 => get_user_meta($user->id, 'EcowoodPlus_tax', true), 137 => get_user_meta($user->id, 'Green_tax', true), 138 => get_user_meta($user->id, 'BiowoodPlus_tax', true), 6 => get_user_meta($user->id, 'Biowood_tax', true), 139 => get_user_meta($user->id, 'Supreme_tax', true), 444 => get_user_meta($user->id, 'SeaDelivery', true));


$propertie_price_placeholder = array(3 => get_post_meta(1, 'BattenStandard', true), 4 => get_post_meta(1, 'BattenCustom', true), 52 => get_post_meta(1, 'Flat_Louver', true), 56 => get_post_meta(1, 'Inside', true), 187 => get_post_meta(1, 'Earth', true), 188 => get_post_meta(1, 'Ecowood', true), 5 => get_post_meta(1, 'EcowoodPlus', true), 137 => get_post_meta(1, 'Green', true), 138 => get_post_meta(1, 'BiowoodPlus', true), 6 => get_post_meta(1, 'Biowood', true), 139 => get_post_meta(1, 'Supreme', true), 221 => get_post_meta(1, 'Solid', true), 229 => get_post_meta(1, 'Combi', true), 171 => get_post_meta(1, 'P4028X', true), 319 => get_post_meta(1, 'P4008W', true), 322 => get_post_meta(1, 'P4008T', true), 353 => get_post_meta(1, '4008T', true), 33 => get_post_meta(1, 'Shaped', true), 34 => get_post_meta(1, 'French_Door', true), 35 => get_post_meta(1, 'Tracked', true), 37 => get_post_meta(1, 'TrackedByPass', true), 36 => get_post_meta(1, 'Arched', true), 100 => get_post_meta(1, 'Buildout', true), 93 => get_post_meta(1, 'Stainless_Steel', true), 403 => get_post_meta(1, 'Concealed_Rod', true), 101 => get_post_meta(1, 'Bay_Angle', true), 264 => get_post_meta(1, 'Colors', true), 102 => get_post_meta(1, 'Ringpull', true), 103 => get_post_meta(1, 'Spare_Louvres', true), 186 => get_post_meta(1, 'Hidden', true), 275 => get_post_meta(1, 'T_Buildout', true), 276 => get_post_meta(1, 'B_Buildout', true), 277 => get_post_meta(1, 'C_Buildout', true), 278 => get_post_meta(1, 'Lock', true), 279 => get_post_meta(1, 'Louver_lock', true), 280 => get_post_meta(1, 'Central_Lock', true), 281 => get_post_meta(1, 'Top_Bottom_Lock', true), 500 => get_post_meta(1, 'G_post', true), 501 => get_post_meta(1, 'blackoutblind', true), 2 => get_post_meta(1, 'B_typeFlexible', true), 1 => get_post_meta(1, 'T_typeAdjustable', true), 502 => get_post_meta(1, 'tposttype_blackout-dolar', true), 503 => get_post_meta(1, 'bposttype_blackout-dolar', true));


?>
<h3>User Info</h3>
<table class="form-table" id="fieldset-custom">
  <tbody>

    <?php
    if (in_array('employe', $roles) || in_array('senior_salesman', $roles) || in_array('salesman', $roles)) {
    ?>

      <tr>
        <th>
          <p>Employe for company?</p>
        </th>
        <td>
          <?php
          $deler_id = get_user_meta($user->id, 'company_parent', true);
          ?>
          <select name="company_parent">
            <option value="0">None</option>
            <?php
            $args = array(
              'role' => 'dealer',
              'orderby' => 'date',
              'order' => 'ASC'
            );
            $dealers = get_users($args);
            foreach ($dealers as $dealer) {
            ?>
              <option value="<?php echo $dealer->ID; ?>" <?php
                                                          if ($deler_id == $dealer->ID) {
                                                            echo 'selected';
                                                          }
                                                          ?>>
                <?php echo $dealer->first_name . ' ' . $dealer->last_name; ?>
              </option>
            <?php
            }
            ?>
          </select>
        </td>
      </tr>

    <?php }

    if (in_array('dealer', $roles)) {

      $employees = get_user_meta($user->id, 'employees', true);

    ?>
      <tr id="employeesSection">
        <th>
          Employees:
        </th>
        <td>
          <ul>
            <?php
            foreach ($employees as $user_emp_id) {

              $user_emp = get_userdata($user_emp_id);

              if ($user_emp) {
                echo '<li>';
                echo $user_emp->display_name;
            ?> -
                <a href="/wp-admin/user-edit.php?user_id=<?php echo $user_emp_id; ?>" class=" btn
                       btn-default">Edit</a>
            <?php
                echo '</li>';
              }
            }
            ?>
          </ul>
        </td>
      </tr>

      <tr>
        <th>
          <p><label for="employee_nr"> Max Nr of Employees</label></p>
        </th>
        <td>
          <input id="employee_nr" type="number" name="max_nr_employees" value="3">
        </td>
      </tr>
    <?php
    }
    ?>

    <tr>
      <th>
        <p>Suspended Dealer? (no add orders)</p>
      </th>
      <td>
        <input name="suspended_user" type="radio" id="suspended_yes" value="yes" <?php if ($suspended == 'yes') {
                                                                                    echo 'checked';
                                                                                  } ?>><label
          for="suspended_yes">Yes</label>
        <input name="suspended_user" type="radio" id="suspended_no" value="no" <?php if ($suspended == 'no') {
                                                                                  echo 'checked';
                                                                                } ?>><label
          for="suspended_no">No</label>
      </td>
    </tr>

    <tr>
      <th>
        <p>Favorite Dealer?</p>
      </th>
      <td>
        <input name="favorite_user" type="radio" id="favorite_yes" value="yes" <?php if ($favorite == 'yes') {
                                                                                  echo 'checked';
                                                                                } ?>><label
          for="favorite_yes">Yes</label>
        <input name="favorite_user" type="radio" id="favorite_no" value="no" <?php if ($favorite == 'no') {
                                                                                echo 'checked';
                                                                              } ?>><label
          for="favorite_no">No</label>
      </td>
    </tr>
    <tr>
      <th>
        <p>Access to App?</p>
      </th>
      <td>
        <input name="app_access" type="radio" id="app_access_yes" value="yes" <?php if ($app_access == 'yes') {
                                                                                echo 'checked';
                                                                              } ?>><label
          for="app_access_yes">Yes</label>
        <input name="app_access" type="radio" id="app_access_no" value="no" <?php if ($app_access == 'no') {
                                                                              echo 'checked';
                                                                            } ?>><label
          for="app_access_no">No</label>
      </td>
    </tr>
    <tr>
      <th>
        <label for="group_added">Add to Group</label>
      </th>
      <td>
        <p>Add to Group?</p>
        <?php $group_added = get_user_meta($user->id, 'group_added', true); ?>
        <input name="group_added" type="radio" id="group_added_yes" value="yes" <?php if ($group_added == 'yes') {
                                                                                  echo 'checked';
                                                                                } ?>><label
          for="group_added_yes">Yes</label>
        <input name="group_added" type="radio" id="group_added_no" value="no" <?php if ($group_added == 'no') {
                                                                                echo 'checked';
                                                                              } ?>><label
          for="group_added_no">No</label>
        <br>
        <label for="selected_group">Select Group:</label>
        <select name="selected_group" id="selected_group">
          <?php
          $selected_group = get_user_meta($user->id, 'current_selected_group', true);
          $groups_created = get_post_meta(1, 'groups_created', true);
          foreach ($groups_created as $key => $group) {
            if ($selected_group == $group) {
              echo '<option value="' . $group . '" selected >' . $group . '</option>';
            } else {
              echo '<option value="' . $group . '" >' . $group . '</option>';
            }
          }
          ?>
        </select>

      </td>
    </tr>
    <tr>
      <th>
        <p>Hide prices for this user</p>
      </th>
      <td>
        <input name="view_price" type="radio" id="view_price_yes" value="yes" <?php if ($view_price == 'yes') {
                                                                                echo 'checked';
                                                                              } ?>><label
          for="view_price_yes">Yes</label>
        <input name="view_price" type="radio" id="view_price_no" value="no" <?php if ($view_price == 'no') {
                                                                              echo 'checked';
                                                                            } ?>><label
          for="view_price_no">No</label>
      </td>
    </tr>


    <?php
    // Setăm valoarea default dacă nu există sau este goală
    $valueBass = !empty($show_biowood) ? $show_biowood : 'yes';
    ?>

    <tr>
      <th>
        <label>Show biowood</label>
      </th>
      <td>
        <label for="show_biowood_yes">
          <input
            type="radio"
            name="show_biowood"
            id="show_biowood_yes"
            value="yes"
            <?php checked($valueBass, 'yes'); ?>>
          Yes
        </label>

        <label for="show_biowood_no">
          <input
            type="radio"
            name="show_biowood"
            id="show_biowood_no"
            value="no"
            <?php checked($valueBass, 'no'); ?>>
          No
        </label>
      </td>
    </tr>


    <tr>
      <th>
        <p>Show basswood</p>
      </th>
      <td>
        <input name="show_basswood" type="radio" id="show_basswood_yes" value="yes"
          <?php echo ($show_basswood === 'yes') ? 'checked' : ''; ?>>
        <label for="show_basswood_yes">Yes</label>

        <input name="show_basswood" type="radio" id="show_basswood_no" value="no"
          <?php echo ($show_basswood === 'no' || empty($show_basswood)) ? 'checked' : ''; ?>>
        <label for="show_basswood_no">No</label>
      </td>
    </tr>


    <tr>
      <th>
        <label for="vat_number">VAT Number</label>
      </th>
      <td>
        <input name="vat_number_custom" type="text" id="vat_number" value="<?php echo $vat_number; ?>">
      </td>
    </tr>
    <tr>
      <th>
        <label for="email_contabil">Email Contabil</label>
      </th>
      <td>
        <input name="email_contabil" type="text" id="email_contabil" value="<?php echo $email_contabil; ?>">
      </td>
    </tr>
    <tr>
      <th>
        <label for="discount_custom">Discount user ( % )</label>
      </th>
      <td>
        <input name="discount_custom" type="text" id="discount_custom" value="<?php echo $discount_custom; ?>">
      </td>
    </tr>
    <tr>
      <th>
        <label for="train_price">Sea price sq/m</label>
      </th>
      <td>
        <?php
        $train_price_placeholder = get_post_meta(1, 'train_price', true);
        ?>
        <input name="train_price" type="text" id="train_price" value="<?php echo $train_price; ?>" placeholder="<?php echo $train_price_placeholder; ?>">(default price: <?php echo $train_price_placeholder; ?>)
      </td>
    </tr>
  </tbody>
</table>
<hr>
<br>
<?php
if ($user->id == 18 || in_array('employe', $roles)) { ?>

  <table id="shutter-table-settings" class="table table-striped">
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th>Name</th>
        <th>Tax</th>
        <!-- <th>Show/Hide</th> -->
      </tr>
    </thead>
    <tbody>
      <?php

      $i = 0;
      foreach ($properties_tax as $id => $propertie) {
        $i++;

        $part = explode("-", $propertie);
        $part[0]; // name
        $part[1]; // ecuation

      ?>
        <tr>
          <td class="text-center">
            <?php echo $i; ?>
          </td>
          <td>
            <?php echo $propertie; ?>
          </td>
          <td>
            <span class="hidden">
              <?php echo $id; ?>
            </span>
            <input type="text" class="form-control" style="width: 100%;" placeholder="Enter price"
              name="price-<?php echo $part[0]; ?>" value="<?php echo $propertie_price_tax[$id]; ?>">
          </td>

        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
  <hr>
  <br>

<?php }
?>
<div class="prices-user row flex" style="display: flex;flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-around;
    align-items: flex-start;">

  <div class="col-sm-6">
    <table id="shutter-table-settings" class="table table-striped">
      <thead>
        <tr>
          <th class="text-center">ID</th>
          <th>Name</th>
          <th>Price £</th>
          <!-- <th>Show/Hide</th> -->
        </tr>
      </thead>
      <tbody>
        <?php

        $i = 0;
        foreach ($properties as $id => $propertie) {
          $i++;

          $part = explode("-", $propertie);
          $part[0]; // name
          $part[1]; // ecuation

        ?>
          <tr>
            <td class="text-center">
              <?php echo $i; ?>
            </td>
            <td>
              <?php echo $propertie; ?>
            </td>
            <td>
              <span class="hidden">
                <?php echo $id; ?>
              </span>
              <input type="text" class="form-control" style="width: 100%;"
                placeholder="<?php echo $propertie_price_placeholder[$id]; ?>"
                name="price-<?php echo $part[0]; ?>" value="<?php echo $propertie_price[$id]; ?>">
            </td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="col-sm-6">
    <table id="shutter-table-settings" class="table table-striped">
      <thead>
        <tr>
          <th class="text-center">ID</th>
          <th>Name</th>
          <th>Price $</th>
          <!-- <th>Show/Hide</th> -->
        </tr>
      </thead>
      <tbody>
        <?php

        $i = 0;
        foreach ($properties as $id => $propertie) {
          $i++;

          $part = explode("-", $propertie);
          $part[0]; // name
          $part[1]; // ecuation

        ?>
          <tr>
            <td class="text-center">
              <?php echo $i; ?>
            </td>
            <td>
              <?php echo $propertie; ?>
            </td>
            <td>
              <span class="hidden">
                <?php echo $id; ?>
              </span>
              <input type="text" class="form-control" style="width: 100%;" placeholder="Enter price"
                name="price-dolar-<?php echo $part[0]; ?>" value="<?php echo $propertie_price_dolar[$id]; ?>">
            </td>

          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

</div>

<?php //teo
//echo 'UserId='.$user->id.'<br/>';
//print_r($propertie_price);
?>

<hr>
<br>