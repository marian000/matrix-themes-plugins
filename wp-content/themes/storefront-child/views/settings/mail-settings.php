<?php
if (isset($_POST['submit'])) {
  update_option('mail_matrix_radio', $_POST['mail_matrix_radio']);
  echo '<div class="notice notice-success is-dismissible">
                <p>' . __('Email address updated successfully.', 'textdomain') . '</p>
            </div>';

  $test_mails_matrix = str_replace(array("\'", "\\"), "", $_POST['test_mails_matrix']);
  update_option('test_mails_matrix', sanitize_text_field($test_mails_matrix));
  echo '<div class="notice notice-success is-dismissible">
                <p>' . __('Test Email addresses updated successfully.', 'textdomain') . '</p>
            </div>';

  $china_mails_matrix = str_replace(array("\'", "\\"), "", $_POST['china_mails_matrix']);
  update_option('china_mails_matrix', sanitize_text_field($china_mails_matrix));
  echo '<div class="notice notice-success is-dismissible">
                <p>' . __('China Email addresses updated successfully.', 'textdomain') . '</p>
            </div>';

  $warranty_yes_mails_matrix = str_replace(array("\'", "\\"), "", $_POST['warranty_yes_mails_matrix']);
  update_option('warranty_yes_mails_matrix', sanitize_text_field($warranty_yes_mails_matrix));
  echo '<div class="notice notice-success is-dismissible">
                <p>' . __('Warranty Yes Email addresses updated successfully.', 'textdomain') . '</p>
            </div>';

  $warranty_no_mails_matrix = str_replace(array("\'", "\\"), "", $_POST['warranty_no_mails_matrix']);
  update_option('warranty_no_mails_matrix', sanitize_text_field($warranty_no_mails_matrix));
  echo '<div class="notice notice-success is-dismissible">
                <p>' . __('Warranty No Email addresses updated successfully.', 'textdomain') . '</p>
            </div>';
}
$send_to = get_option('mail_matrix_radio');


function generate_mail_inputs($inputs) {
  $output = '';
  foreach ($inputs as $input) {
    $output .= '<br>
    <div class="row">
      <div class="form-check">
        <label for="mail_' . $input['id'] . '_field">' . __($input['label'], 'textdomain') . ' :</label>
        <input style="width: 100%" type="text" id="mail_' . $input['id'] . '_field" name="' . $input['name'] . '" value="' . esc_attr(get_option($input['name'])) . '"/>
        <p>separated by ,</p>
      </div>
    </div>';
  }
  return $output;
}

// Example usage:
$mail_inputs = array(
  array(
    'id' => 'test',
    'label' => 'Test Email Addresses',
    'name' => 'test_mails_matrix'
  ),
  array(
    'id' => 'china',
    'label' => 'China Email Addresses',
    'name' => 'china_mails_matrix'
  ),
  array(
    'id' => 'repair_yes',
    'label' => 'Repair warranty -yes- mails',
    'name' => 'warranty_yes_mails_matrix'
  ),
  array(
    'id' => 'repair_no',
    'label' => 'Repair warranty -no- mails',
    'name' => 'warranty_no_mails_matrix'
  ),
);


?>

<br>
<div class="container border rounded mt-4 p-5" style="background-color: #F0F0F1">
  <h2>Mail Settings</h2>

  <form class="mt-5" method="post">
    <div class="row">
      <div class="col-2">
        <label><?php _e('Send To', 'textdomain'); ?> :</label>
      </div>
      <div class="col">
        <div class="form-check">
          <input type="radio" id="mail_matrix_radio_test" name="mail_matrix_radio"
                 value="send_test" <?php echo $send_to == 'send_test' ? 'checked' : '' ?> />
          <label for="mail_matrix_radio_test"><?php _e('Send Test Mails', 'textdomain'); ?></label>
          <br/>
          <input type="radio" id="mail_matrix_radio_dealer" name="mail_matrix_radio"
                 value="send_dealers" <?php echo $send_to == 'send_dealers' ? 'checked' : '' ?> />
          <label for="mail_matrix_radio_dealer"><?php _e('Send Production Mails', 'textdomain'); ?></label>
        </div>
      </div>
    </div>

    <?php
    echo generate_mail_inputs($mail_inputs);

    submit_button();
    ?>
  </form>

</div>
