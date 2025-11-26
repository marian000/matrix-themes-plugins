<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path . 'wp-load.php');
$vowels = array("/", "%", ".", "#", "'", "`", "/", "", ":", ";", "|", "?", "*", "<", ">");
$roomName = $_POST['roomName'].'-'.date("m-d-y");
$roomName = str_replace($vowels, "-", $roomName);
if (empty($roomName)) {
    $roomName = 'NoNameProvided'.'-'.date("m-d-y");
}
$imageSrc = $_POST['imageSrc'];

$wp_upload_dir = wp_upload_dir();

// @new
$upload_path = str_replace('/', DIRECTORY_SEPARATOR, $wp_upload_dir['path']) . DIRECTORY_SEPARATOR;
$image_parts = explode(";base64,", $imageSrc);
$decoded = base64_decode($image_parts[1]);
// $decoded = $imageSrc;
$filename = $roomName . '.png';

$hashed_filename = $filename;

// @new
$image_upload = file_put_contents($upload_path . $hashed_filename, $decoded);

//HANDLE UPLOADED FILE
if (!function_exists('wp_handle_sideload')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
}

// Without that I'm getting a debug error!?
if (!function_exists('wp_get_current_user')) {
    require_once(ABSPATH . 'wp-includes/pluggable.php');
}

// @new
$file = array();
$file['error'] = '';
$file['tmp_name'] = $upload_path . $hashed_filename;
$file['name'] = $hashed_filename;
$file['type'] = 'image/png';
$file['size'] = filesize($upload_path . $hashed_filename);

// upload file to server
// @new use $file instead of $image_upload
$file_return = wp_handle_sideload($file, array('test_form' => false));

$filename = $file_return['file'];
$attachment = array(
    'post_mime_type' => $file_return['type'],
    'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
    'post_content' => '',
    'post_status' => 'inherit',
    'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
);
$attach_id = wp_insert_attachment($attachment, $filename);

// $attach_id = wp_insert_attachment($attachment, $filename);
/// generate thumbnails of newly uploaded image


$attachment_meta = wp_generate_attachment_metadata($attach_id, $filename);
wp_update_attachment_metadata($attach_id, $attachment_meta);
echo $attachment_url = wp_get_attachment_url($attach_id);
// echo $urllocal = explode(site_url(), $attachment_url)[1];
