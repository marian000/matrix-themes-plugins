<?php

// Define the path to the log file in the plugin directory
define('MY_PLUGIN_LOG_FILE', plugin_dir_path(__DIR__) . 'custom-log.txt');

// Maximum log file size in bytes (3MB in this example)
define('MY_PLUGIN_LOG_MAX_SIZE', 3 * 1024 * 1024);

/**
 * Logs custom events to a file in the plugin directory.
 *
 * @param string $title The title of the log entry, providing a brief description.
 * @param string $content The detailed content of the log entry, explaining the event.
 */
function my_custom_log($title, $content)
{
	// Get the current date and time, formatted for logging
	$date = current_time('Y-m-d H:i:s');

	// Construct the log entry with date, title, and content
	$log_entry = sprintf("[%s] %s: %s%s", $date, $title, $content, PHP_EOL);

	// Check the file size before appending the log entry
	if (file_exists(MY_PLUGIN_LOG_FILE) && filesize(MY_PLUGIN_LOG_FILE) > MY_PLUGIN_LOG_MAX_SIZE) {
		// Rename the current log file to archive it
		rename(MY_PLUGIN_LOG_FILE, MY_PLUGIN_LOG_FILE . '.' . time() . '.bak');
	}

	// Append the log entry to the log file
	// Use FILE_APPEND to add to the file, and LOCK_EX to prevent simultaneous writes
	file_put_contents(MY_PLUGIN_LOG_FILE, $log_entry, FILE_APPEND | LOCK_EX);
}


// Example usage: Log an event manually
//add_action('init', function () {
//	my_custom_log('Init Hook Triggered', 'The init hook was triggered.');
//});

add_action('admin_menu', function () {
	add_menu_page('Custom Log', 'Custom Log', 'manage_options', 'custom-log', 'my_custom_log_viewer');
});

/**
 * Displays the log content on the custom admin page and provides a button to clear the logs.
 */
function my_custom_log_viewer()
{
	// Check if the user has the necessary permissions
	if (!current_user_can('manage_options')) {
		wp_die('You do not have sufficient permissions to access this page.');
	}

	// Check if the 'Clear Logs' button was pressed
	if (isset($_POST['clear_logs'])) {
		// Clear the log file by truncating it to zero length
		file_put_contents(MY_PLUGIN_LOG_FILE, '');

		// Display a success message
		echo '<div class="updated"><p>Logs have been cleared successfully!</p></div>';
	}

	echo '<div class="wrap">';
	echo '<h1>Custom Log Viewer</h1>';

	// Check if the log file exists and read its contents
	if (file_exists(MY_PLUGIN_LOG_FILE)) {
		// Get the content of the log file
		$log_content = file_get_contents(MY_PLUGIN_LOG_FILE);

		// Escape the log content for safe HTML output

		echo '<form method="post">';
		echo '<input type="submit" name="clear_logs" class="button button-primary" value="Clear Logs">';
		echo '</form>';
		echo '<pre style="background: #f9f9f9; padding: 15px; border: 1px solid #ddd; overflow: auto; margin-top: 20px; max-width: 80vw; white-space: pre-wrap; word-wrap: break-word;">
' . esc_html($log_content) . '</pre>';
		echo do_shortcode('[file_upload_form]');
	} else {
		// Display a message if no logs are found

		echo '<p>No logs found.</p>';
		echo do_shortcode('[file_upload_form]');
	}

	echo '</div>';
}
