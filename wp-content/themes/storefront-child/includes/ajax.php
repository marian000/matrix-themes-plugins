<?php
add_action('wp_ajax_demo-pagination-load-posts', 'cvf_demo_pagination_load_posts');
add_action('wp_ajax_nopriv_demo-pagination-load-posts', 'cvf_demo_pagination_load_posts');

function cvf_demo_pagination_load_posts()
{

    global $wpdb;
    // Set default variables
    $msg = '';

    if (isset($_POST['page'])) {

        // Sanitize the received page
        $page = sanitize_text_field($_POST['page']);
        $cur_page = $page;
        $page -= 1;
        // Set the number of results to display
        $per_page = 300;
        $previous_btn = true;
        $next_btn = true;
        $first_btn = true;
        $last_btn = true;
        $start = $page * $per_page;

        $orders = get_posts(array(
            'meta_key' => '_customer_user',
            'meta_value' => get_current_user_id(),
            'post_type' => 'shop_order',
            'posts_per_page' => $per_page,
            'offset' => $start,
            'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
            'fields' => 'ids'
        ));


        $count = get_posts(
            array(
                'meta_key' => '_customer_user',
                'meta_value' => get_current_user_id(),
                'post_type' => 'shop_order',
                'posts_per_page' => 700,
                'post_status' => array('wc-on-hold', 'wc-completed', 'wc-pending', 'wc-processing', 'wc-inproduction', 'wc-paid', 'wc-waiting', 'wc-revised', 'wc-inrevision'),
                'fields' => 'ids'
            )
        );

        // Loop into all the posts


        // Optional, wrap the output into a container
        $msg = "<div class='cvf-universal-content'>" . $msg . "</div><br class = 'clear' />";

        // This is where the magic happens
        $no_of_paginations = ceil(count($count) / $per_page);

        if ($cur_page >= 7) {
            $start_loop = $cur_page - 3;
            if ($no_of_paginations > $cur_page + 3)
                $end_loop = $cur_page + 3;
            else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                $start_loop = $no_of_paginations - 6;
                $end_loop = $no_of_paginations;
            } else {
                $end_loop = $no_of_paginations;
            }
        } else {
            $start_loop = 1;
            if ($no_of_paginations > 7)
                $end_loop = 7;
            else
                $end_loop = $no_of_paginations;
        }

        // Pagination Buttons logic
        $pag_container = "
        <div class='cvf-universal-pagination'>
            <ul>";

        if ($first_btn && $cur_page > 1) {
            $pag_container .= "<li p='1' class='active'>First</li>";
        } else if ($first_btn) {
            $pag_container .= "<li p='1' class='active'>First</li>";
        }

        if ($previous_btn && $cur_page > 1) {
            $pre = $cur_page - 1;
            $pag_container .= "<li p='$pre' class='active'>Previous</li>";
        } else if ($previous_btn) {
            $pag_container .= "<li class='active'>Previous</li>";
        }
        for ($i = $start_loop; $i <= $end_loop; $i++) {

            if ($cur_page == $i)
                $pag_container .= "<li p='$i' class = 'selected' >{$i}</li>";
            else
                $pag_container .= "<li p='$i' class='active'>{$i}</li>";
        }

        if ($next_btn && $cur_page < $no_of_paginations) {
            $nex = $cur_page + 1;
            $pag_container .= "<li p='$nex' class='active'>Next</li>";
        } else if ($next_btn) {
            $pag_container .= "<li class='active'>Next</li>";
        }

        if ($last_btn && $cur_page < $no_of_paginations) {
            $pag_container .= "<li p='$no_of_paginations' class='active'>Last</li>";
        } else if ($last_btn) {
            $pag_container .= "<li p='$no_of_paginations' class='active'>Last</li>";
        }

        $pag_container = $pag_container . "
            </ul>
        </div>";

        // We echo the final output
        echo
            '<div class = "cvf-pagination-content">' . $msg . '</div>' .
            '<div class = "cvf-pagination-nav">' . $pag_container . '</div>';
        ?>

        <table class="table">
            <thead style="background-color: #f2dede;">
            <th></th>
            <th>Order ID</th>
            <th>Date</th>
            <th>Deliveries Start</th>
            <th>Total</th>
            <th>Status</th>
            <th>Added by</th>
            <th>Last Update</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            $user_id = get_current_user_id();
            $user = get_userdata($user_id);
            if (in_array('dealer', $user->roles)) {
                // echo 'dealer master';
                $users_orders = get_user_meta($user_id, 'employees', true);
                $users_orders[] = $user_id;
                $users_orders = array_reverse($users_orders);
            }
            // if dealer if is not empty and current user have employe role and not dealer_employe add dealer orders
            if (in_array('employe', $user->roles) || in_array('senior_salesman', $user->roles)) {
                // get user company_parent id from meta
                $deler_id = get_user_meta($user_id, 'company_parent', true);
                // initiate users_orders array with logged user id
                $users_orders = array($user_id);

                if (!empty($deler_id)) {
                    $users_orders[] = $deler_id;
                }
            }

            if (in_array('salesman', $user->roles) || in_array('subscriber', $user->roles) || in_array('emplimited', $user->roles) && !in_array('dealer', $user->roles)) {
                // echo 'dealer simplu';
                $users_orders = array($user_id);
            }

            $i = $start + 1;

            $order_statuses = wc_get_order_statuses();

            foreach ($orders as $order_id) {
                $order = wc_get_order($order_id);
                $order_data = $order->get_data();
                $order_status = $order_data['status'];
                $status_name = $order_statuses['wc-' . $order_status];

                $culori_status = array('pending' => 'background-color:#ef8181; color: #fff', 'on-hold' => 'background-color:#f8dda7; color: #fff', 'processing' => 'background-color:#c6e1c6; color: #fff', 'completed' => 'background-color:#c8d7e1; color: #000', 'cancelled' => 'background-color:#e5e5e5; color: #000', 'refound' => 'background-color:#5BC0DE; color: #fff', 'inproduction' => 'background-color:#7878e2; color: #fff', 'transit' => 'background-color:#85bb65; color: #fff', 'waiting' => 'background-color:#3ba000; color: #fff', 'account-on-hold' => 'background-color:red; color: #fff', 'inrevision' => 'background-color:#8B4513; color: #fff', 'revised' => 'background-color:#8B4513; color: #fff');

                ?>
                <tr class=" ">
                    <td>
                        <?php echo $i; ?>.
                    </td>
                    <td>
                        <?php echo 'LF0' . $order->get_order_number() . ' - <i>' . get_post_meta($order->get_id(), 'cart_name', true) . '</i>';
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $order_data['date_created']->date('Y-m-d H:i:s');

                        $text = $order_data['date_created']->date('Y-m-d H:i:s');
                        preg_match('/-(.*?)-/', $text, $match);
                        preg_match('/(.*?)-/', $text, $match_year);
                        $month = $match[1];
                        $year = $match_year[1];
                        ?>
                    </td>
                    <td>
                        <?php echo get_post_meta($order->get_id(), 'delivereis_start', true); ?>
                    </td>
                    <td>
                        <?php

                        echo $order->get_total();
                        ?>
                    </td>
                    <td>
                        <p class="statusOrder" style="<?php
                        foreach ($culori_status as $stat => $cul) {
                            if ($stat == $order_status) {
                                echo $cul . ';';
                            }
                        }
                        ?> text-align:center;">Ordered -
                            <?php
                            if ($order_status == 'pending') {
                                echo 'Pending Payment';
                            } //teo>
                            elseif ($order_status == 'waiting') {
                                echo 'Waiting Delivery';
                            } //teo<
                            else {
                                echo $status_name;
                            }
                            ?>
                        </p>
                    </td>
                    <td>
                        <?php
                        echo $order->billing_first_name . ' ' . $order->billing_last_name;
                        ?>
                    </td>
                    <td>
                        <?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?>
                    </td>
                    <td>
                        <a href="/view-order/?id=<?php echo base64_encode($order->get_id()) ?>"
                           class="btn btn-primary">View</a>
                    </td>
                </tr>

                <?php
                $i++;
            }
            // }

            ?>

            </tbody>

        </table>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                // Handle the clicks
                jQuery('.cvf-universal-pagination li.active').on('click', function () {
                    console.log('clicked page');
                    var page = jQuery(this).attr('p');

                    // This is required for AJAX to work on our page
                    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

                    // Start the transition
                    jQuery(".cvf_pag_loading").fadeIn().css('background', 'rgba(204,204,204,0.3)');

                    // Data to receive from our server
                    // the value in 'action' is the key that will be identified by the 'wp_ajax_' hook
                    var data = {
                        page: page,
                        action: "demo-pagination-load-posts"
                    };

                    // Send the data
                    jQuery.post(ajaxurl, data, function (response) {

                        console.log(page);
                        // If successful Append the data into our html container
                        jQuery(".cvf_universal_container").empty().append(response);
                        // End the transition
                        jQuery(".cvf_pag_loading").css({
                            'background': 'none',
                            'transition': 'all 1s ease-out'
                        });
                    });

                });

            });
        </script>

        <?php

    }
    // Always exit to avoid further execution
    exit();
}


add_action("wp_ajax_home_sales_per_month", "grafic_sales_per_month");
add_action("wp_ajax_nopriv_home_sales_per_month", "grafic_sales_per_month");

function grafic_sales_per_month()
{

    $orders = wc_get_orders(
        array(
            'customer_id' => get_current_user_id(),
            'date_query' => array(
                array(
                    'after' => '-365 days',
                    'column' => 'post_date',
                ),
            ),
        )
    );

    foreach ($orders as $order) {
        // $order = wc_get_order($order_id);
        $order_data = $order->get_data();
        $order_status = $order_data['status'];
        $items = $order->get_items();

        $text = $order_data['date_created']->date('Y-m-d H:i:s');
        preg_match('/-(.*?)-/', $text, $match);
        preg_match('/(.*?)-/', $text, $match_year);
        $month = $match[1];
        $year = $match_year[1];

        foreach ($items as $item_id => $item_data) {
            if ($order_status != 'cancelled') {
                $property_total = wc_get_order_item_meta($item_id, 'sqm', true);
                $total[$year][$month] = $total[$year][$month] + floatval($property_total);
                $total2[$year][$month] = $total2[$year][$month] + floatval($property_total);
            }
        }

    }

    $all_time_sqm = array();

    foreach ($total as $key => $value) {
        foreach ($value as $month => $t) {
            $all_time_sqm[$key . '-' . $month] = $t;
        }
    }

    //$new = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
    $luni = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

    $newlunian = array();
    for ($i = 11; $i >= 0; $i--) {
//teo				$newlunian[] = date('Y-m', strtotime('-'.$i.' months'));
        $newlunian[] = date('Y-m', mktime(0, 0, 0, date('m') - $i, 1, date('Y')));
    }

    //print_r($newlunian);
    $current_year = date("y");
    //print_r($total);

    //for( $i=11; $i>=0; $i-- ){
    foreach ($newlunian as $k => $anluna) {
        $pieces = explode("-", $anluna);
        $an = $pieces[0]; // piece1
        $luna = $pieces[1]; // piece2
        foreach ($total as $key => $value) {
            if ($key == $an) {
                foreach ($total[$key] as $m => $t) {
                    if ($luna == $m) {
                        $new[$luna] = round($t, 0);
                        //print_r($t);
                    } else {
                        if ($new[$luna] != 0) {

                        } else {
                            $new[$luna] = 0;
                            //print_r($new[$luna]);
                        }

                    }
                }
            } else {
                if (empty($new[$luna])) {
                    $new[$luna] = 0;
                } else {
                }
            }
        }

    }

    $only_sqm = array();
    foreach ($new as $lune => $sqm) {
        $only_sqm[] = $sqm;
    }

    // Always HTTP responses will be of string data type. You need to parse the JSON before you can use
    echo json_encode($only_sqm);

    die();
}


add_action('wp_ajax_get_container_sqm', 'get_container_sqm_callback');

function get_container_sqm_callback()
{
    global $wpdb; // this is how you get access to the database
    $orders_sqm = array();
    $containers = $_POST['conatiner_orders'];


    foreach ($containers as $post_id) {
        $csv_orders_array = get_post_meta($post_id, 'csv_orders_array', true);
        $container_orders = get_post_meta($post_id, 'container_orders', true);

        $total_sqm = 0;
        $sqm_total = 0;
        if ($csv_orders_array) {
            $csv_orders_array_repair_sqm = get_post_meta($post_id, 'csv_orders_array_repair', true);
            foreach ($container_orders as $order) {
                $order_id = $order;

                if (get_post_type($order_id) == 'order_repair') {
                    $order_is_repair = true;
                    $repair_id = $order_id;
                    $order_id = get_post_meta($order_id, 'order-id-original', true);
                } else {
                    $repair_id = '';
                }

                if (get_post_type($repair_id) == 'order_repair') {
                    $sqm_total = $csv_orders_array_repair_sqm[$order_id];
                    if ($order_is_repair) {
                        $total_sqm = $total_sqm + $sqm_total;
                    }
                } else {
                    $sqm_total = $csv_orders_array[$order_id];
                    $total_sqm = $total_sqm + $sqm_total;
                }
            }
            // echo number_format($total_sqm, 3);
            $orders_sqm[$post_id] = number_format($total_sqm, 3);

        } else if ($container_orders) {

            $total_orders = 0;
            $tablename = $wpdb->prefix . 'custom_orders';
            $sqm_ord = $wpdb->get_results($wpdb->prepare("SELECT sqm, idOrder FROM $tablename WHERE idOrder IN (" . implode(',', $container_orders) . ") ", ARRAY_A));

            if ($sqm_ord) {
                foreach ($sqm_ord as $order_res) {
                    if (get_post_type($order_res->idOrder) == 'order_repair') {

                    } elseif (get_post_type($order_res->idOrder) == 'shop_order') {
                        $sqm_total = floatval($sqm_total) + floatval($order_res->sqm);
                    }
                }
                $orders_sqm[$post_id] = number_format($sqm_total, 3);
            }

//            foreach ($container_orders as $order) {
//                $order_id = $order;
//                if (get_post_type($order) == 'shop_order') {
//
//                    $tablename = $wpdb->prefix . 'custom_orders';
//                    $order_sqm = $wpdb->get_var($wpdb->prepare("SELECT sqm FROM $tablename WHERE idOrder = %s ", $order_id));
//                    $sqm_total = floatval($sqm_total) + floatval($order_sqm);
//                }
//                if (get_post_type($order) == 'order_repair') {
//
//                }
//            }
//            $orders_sqm[$post_id] = number_format($sqm_total, 3);

        } else {
            // echo 'No Orders';
        }
    }
    $js_array = json_encode($orders_sqm);
    echo $js_array;

    wp_die(); // this is required to terminate immediately and return a proper response
}

add_action('wp_ajax_get_container_price', 'get_container_price_callback');

function get_container_price_callback()
{
    global $wpdb; // this is how you get access to the database
    $orders_price = array();
    $containers = $_POST['conatiner_orders'];

    foreach ($containers as $post_id) {
        $total_orders = 0;
        $shop_orders = array();
        $orders = get_post_meta($post_id, 'container_orders', true);
        foreach ($orders as $order_id) {
            if (get_post_type($order_id) == 'order_repair') {
            } elseif (get_post_type($order_id) == 'shop_order') {
                $shop_orders[] = $order_id;
            }

        }

        $tablename = $wpdb->prefix . 'custom_orders';
        $usd_price_ord = $wpdb->get_results($wpdb->prepare("SELECT usd_price FROM $tablename WHERE idOrder IN (" . implode(',', $shop_orders) . ") ", ARRAY_A));

        if ($usd_price_ord) {
            foreach ($usd_price_ord as $order_price) {
                $total_orders = floatval($total_orders) + floatval($order_price->usd_price);
            }
            $orders_price[$post_id] = '$' . number_format($total_orders, 2);
        }


//        if ($orders) {
//            foreach ($orders as $order_id) {
//                if (get_post_type($order_id) == 'shop_order') {
//                    $tablename = $wpdb->prefix . 'custom_orders';
//                    $usd_price = $wpdb->get_var($wpdb->prepare("SELECT usd_price FROM $tablename WHERE idOrder = %s ", $order_id));
//                    $total_orders = floatval($total_orders) + floatval($usd_price);
//                }
//            }
//            $orders_price[$post_id] = '$' . number_format($total_orders, 2);
//        }
    }
    $js_array = json_encode($orders_price);
    echo $js_array;

    wp_die(); // this is required to terminate immediately and return a proper response
}



add_action("wp_ajax_average_per_month_train", "average_per_month_train");
add_action("wp_ajax_nopriv_average_per_month_train", "average_per_month_train");

function average_per_month_train()
{

    $avgs = $_POST['avgs'];
    $container_id = $_POST['container_id'];
    // print_r($_POST);

    $container_orders = get_post_meta($container_id, 'container_orders', true);

    if ($container_orders) {
        // print_r($container_orders);
        foreach ($container_orders as $order_id) {
            // if csv order is not in matrix then create an array with orders outside matrix
            if (get_post_type($order_id) != 'order_repair') {
                update_post_meta($order_id, 'average_train', $avgs['avg-train']);
                update_post_meta($order_id, 'average_truck', $avgs['avg-truck']);
            }
        }
    }
//    print_r($average_train);
//    print_r($average_truck);

    update_post_meta($container_id, 'average_train', $avgs['avg-train']);
    update_post_meta($container_id, 'average_truck', $avgs['avg-truck']);

    wp_die(); // this is required to terminate immediately and return a proper response
}



add_action("wp_ajax_container_info_sync", "container_info_sync");
add_action("wp_ajax_nopriv_container_info_sync", "container_info_sync");

function container_info_sync()
{
   include 'ajax/container-info.php';
}


add_action("wp_ajax_container_insert_list", "container_list");
add_action("wp_ajax_nopriv_container_insert_list", "container_list");

function container_list()
{
    include 'ajax/container-list.php';
}

add_action("wp_ajax_container_insert_list_status", "container_list_status");
add_action("wp_ajax_nopriv_container_insert_list_status", "container_list_status");

function container_list_status()
{
	include 'ajax/container-list-status.php';
}



add_action('wp_ajax_save_user_message', 'save_user_message_callback');

function save_user_message_callback() {
	$user_id = $_POST['user_id'];
	$message = sanitize_text_field($_POST['message']);

	// Get the current array of messages or initialize a new one
	$messages = get_user_meta($user_id, 'user_messages', true);
	if (empty($messages)) {
		$messages = [];
	}

	// Append new message
	$messages[] = date('Y-m-d H:i') . ' - ' .$message;

	// Save the updated array back to user meta
	update_user_meta($user_id, 'user_messages', $messages);

	$data = array(
		'user_id' => $user_id,
		'message' => $message,
		'date' => date('Y-m-d H:i:s')
	);

	wp_send_json_success($data);
}

add_action('wp_ajax_get_user_messages', 'get_user_messages_callback');

function get_user_messages_callback() {
	$user_id = intval($_POST['user_id']);
	// Get messages from user meta or wherever they are stored
	$messages = get_user_meta($user_id, 'user_messages', true) ?: [];

	wp_send_json_success($messages);
}