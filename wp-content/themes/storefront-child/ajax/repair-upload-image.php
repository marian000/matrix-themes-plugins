<?php
/**
 * AJAX handler for repair image uploads with client-side compression.
 * Registered via wp_ajax_ hook (logged-in users only).
 */

add_action('wp_ajax_repair_upload_image', 'matrix_repair_upload_image');

function matrix_repair_upload_image() {
	check_ajax_referer('repair_upload_image_nonce', 'nonce');

	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => 'Not authenticated.' ) );
	}

	if ( empty( $_FILES['repair_image'] ) ) {
		wp_send_json_error( array( 'message' => 'No file uploaded.' ) );
	}

	$file = $_FILES['repair_image'];

	// Validate MIME type using WordPress native function
	$filetype = wp_check_filetype( $file['name'], array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png'          => 'image/png',
		'webp'         => 'image/webp',
	) );

	if ( empty( $filetype['type'] ) ) {
		wp_send_json_error( array( 'message' => 'Invalid file type. Only JPEG, PNG, and WebP are allowed.' ) );
	}

	// Size cap: 5MB
	if ( $file['size'] > 5 * 1024 * 1024 ) {
		wp_send_json_error( array( 'message' => 'File too large. Maximum 5MB allowed.' ) );
	}

	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$upload_overrides = array(
		'test_form' => false,
		'mimes'     => array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
			'webp'         => 'image/webp',
		),
	);

	$uploaded_file = wp_handle_upload( $file, $upload_overrides );

	if ( isset( $uploaded_file['error'] ) ) {
		error_log( 'Repair image upload error: ' . $uploaded_file['error'] );
		wp_send_json_error( array( 'message' => $uploaded_file['error'] ) );
	}

	$file_path = $uploaded_file['file'];
	$file_url  = $uploaded_file['url'];
	$file_type = $uploaded_file['type'];

	$attachment_data = array(
		'post_mime_type' => $file_type,
		'post_title'     => sanitize_file_name( basename( $file_path ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attachment_id = wp_insert_attachment( $attachment_data, $file_path );

	if ( is_wp_error( $attachment_id ) ) {
		error_log( 'Repair image attachment error: ' . $attachment_id->get_error_message() );
		wp_send_json_error( array( 'message' => 'Failed to create attachment.' ) );
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $file_path );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	wp_send_json_success( array(
		'url'           => $file_url,
		'attachment_id' => $attachment_id,
	) );
}
