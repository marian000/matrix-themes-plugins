<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path .
    'wp-load.php');

//$user_id = $_POST['id_user'];

$orders_ids = $_POST['orders'];

$count = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$edit_placed_order_param = '';

$nr_orders = count($orders_ids);

foreach ($orders_ids as $key => $order_id) {
    $count++;
    // $order_id = $order->get_id();
    $order = wc_get_order($order_id);
    $items = $order->get_items();
    $user_id_customer = get_post_meta($order_id, '_customer_user', true);
    $user_id = $user_id_customer;

    foreach ($items as $item_id => $item_data) {
        $product_id = $item_data['product_id'];
        $svg_product = get_post_meta($product_id, 'svg_product', true);
        if (!empty($svg_product)) {

            $room_name = get_post_meta($product_id, 'property_room_other', true);
            $vowels = array(" ", "/", "%", ".", "#", "'", "_", "`", "", ":", ";", "|", "?", "*", "<", ">", "+");
            $roomName = $room_name . '-' . $product_id . '-' . date("m-d-y-H-m");
            $roomName = str_replace($vowels, "-", $roomName);
            if (empty($roomName)) {
                $roomName = 'NoNameProvided' . '-' . $product_id . '-' . date("m-d-y-H-m");
            }
            $filename = $roomName . '.svg';


            if (strlen($svg_product) > 250 || !is_array($svg_product)) {

                $imageSrc = $svg_product;

                $wp_upload_dir = wp_upload_dir();
// @new
                $upload_path = str_replace('/', DIRECTORY_SEPARATOR, $wp_upload_dir['path']) . DIRECTORY_SEPARATOR;
                $image_parts = $imageSrc;
                // $decoded = base64_decode($image_parts[1]);
                $decoded = $imageSrc;


                $hashed_filename = $filename;

                // print_r('hashed_filename: ' . $hashed_filename);

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
                $file['type'] = 'image/svg+xml';
                $file['size'] = filesize($upload_path . $hashed_filename);

// upload file to server
// @new use $file instead of $image_upload
                $file_return = wp_handle_sideload($file, array('test_form' => false));

                $filename = $file_return['file'];

                $attachment = array(
                    'post_mime_type' => $file_return['type'],
                    'post_title' => str_replace($vowels, "-", $hashed_filename),
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
                );
                $attach_id = wp_insert_attachment($attachment, $filename);

// $attach_id = wp_insert_attachment($attachment, $filename);
/// generate thumbnails of newly uploaded image


                $attachment_meta = wp_generate_attachment_metadata($attach_id, $filename);
                wp_update_attachment_metadata($attach_id, $attachment_meta);
                $attachment_url = wp_get_attachment_url($attach_id);


                $attachment_info = array(
                    'id' => $attach_id,
                    'url' => str_replace(get_site_url(), "", $attachment_url),
                    'name' => $roomName . '.svg',
                );

//                update_post_meta($product_id, 'svg_product', $attachment_info);
//                update_post_meta($product_id, 'shutter_svg', $attachment_info);
                delete_post_meta($product_id, '_product_attributes');
                if ($count == 19) {
                    print_r($attachment_info);
                }
            } else {
//                update_post_meta($product_id, 'shutter_svg', $svg_product);
                echo '<br> Svg transformed <br>';
                delete_post_meta($product_id, '_product_attributes');
            }
        }
    }
}
if ($nr_orders == $count || $nr_orders < $count) {
    echo 'User Updated Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
    // update_user_meta($user_id, 'orders_svg_user_updated', 'updated');
} else {
    echo 'User Updated Not All Orders! Nr Orders: ' . $nr_orders . ' - counter orders: ' . $count;
}
