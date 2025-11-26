<!-- Bootstrap3 -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php

// Handle form submission
if (isset($_POST['submit'])) {
    if (!empty($_POST['notification'])) {
        // Save the notification message, allowing only safe HTML with wp_kses_post()
        update_post_meta(1, 'notification_users_message', wp_kses_post($_POST['notification']));
    } else { ?>
        <div class="alert alert-warning">
		    <?php
		    // Get the saved notification message
		    $notification_message = get_post_meta(1, 'notification_users_message', true);
		    echo wp_kses_post($notification_message); // Safely display the HTML content
		    ?>
        </div>
    <?php }
}

// Get the saved notification message
$notification_message = get_post_meta(1, 'notification_users_message', true);

?>

<div class="container">
    <h3>Notification Users</h3>

    <div class="container-fluid">
        <form method="POST">
            <div class="row">
                <label for="add_not">Add Notification Alert</label>
                <!-- Use textarea to allow HTML content -->
                <textarea class="col-md-12" name="notification" id="add_not" rows="6"><?php echo esc_textarea($notification_message); ?></textarea>
                <div class="clearfix clear-fix"></div>
                <br>
                <input type="submit" class="btn btn-primary" value="Save" name="submit">
            </div>
        </form>

        <br>
        <div class="alert alert-warning">
		    <?php
		    // Get the saved notification message
		    $notification_message = get_post_meta(1, 'notification_users_message', true);
		    echo wp_kses_post($notification_message); // Safely display the HTML content
		    ?>
        </div>
    </div>
</div>