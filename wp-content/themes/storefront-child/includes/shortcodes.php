<?php

// Duplicate product
function duplicate_funct($atts)
{
    $a = shortcode_atts(array(
        'prod_id' => '',
    ), $atts);

    echo '<div class="btn btn-primary blue" onclick="cloneFunction(' . $a['prod_id'] . ')" style="margin-left: 10px;">Duplicate</div>
	<input type="hidden" id="clone-input" class="this-id' . $a['prod_id'] . '" name="clone-name" value="' . $a['prod_id'] . '" id_clone="' . $a['prod_id'] . '" >';

    ?>
    <script>
        function cloneFunction(p) {
            var person = prompt("Please enter clone product name", "Clone Product");
            if (person != null) {
                document.getElementById("clone-input").value = person;
            }

            var id_prod = jQuery('.this-id' + p).attr('id_clone');
            var name = jQuery('#clone-input').val();

            $.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/clone-ajax.php",
                data: {
                    prod_id: id_prod,
                    name: name
                }
            })
                .done(function (msg) {
                    console.log("Data Saved: " + msg);
                    //alert( "Data Saved: " + msg );
                    setTimeout(function () {
                        location.reload();
                    }, 1000);

                });

        }
    </script>
    <?php

}
add_shortcode('duplicate_prod', 'duplicate_funct');

//current cart name
function title_cart_current()
{
    global $wpdb;
    $user_id = get_current_user_id();
    $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");
    $userialize_data = unserialize($addresses[0]->meta_value);

    foreach ($userialize_data['carts'] as $key => $carts) {

        if ($userialize_data['customer_id'] == $key) {
            echo $carts['name'];
        }

    }
}
add_shortcode('current_cart_name', 'title_cart_current');


function order_sum()
{
    include_once(get_stylesheet_directory() . '/includes/order-sum.php');
}
add_shortcode('order_summary', 'order_sum');

function pricing_sum()
{
    include_once(get_stylesheet_directory() . '/includes/pricing-sum.php');
}
add_shortcode('pricing_summery', 'pricing_sum');

/*
 * Make shortcode for template item configuration ex: form-checkout.php, thankyou, vieworder
 */
function table_items($atts)
{
    $attributes = shortcode_atts(array(
        'table_class' => 'table table-striped',
        'table_id' => 'example',
        'order_id' => '',
        'editable' => false,
        'admin' => false,
        'view_price' => false
    ), $atts);
    include_once(get_stylesheet_directory() . '/views/table/items.php');
}
add_shortcode('table_items_shc', 'table_items');


function table_items_order_edit($atts)
{
    $attributes = shortcode_atts(array(
        'table_class' => 'table table-striped',
        'table_id' => 'example-edit',
        'order_id' => '',
        'editable' => false,
        'admin' => false,
        'view_price' => false
    ), $atts);
    include_once(get_stylesheet_directory() . '/views/table/order-edit-items.php');
}
add_shortcode('table_order_edit_shc', 'table_items_order_edit');

/*
* Make shortcode for template item configuration ex: form-checkout.php, thankyou, vieworder
*/
function table_csv($atts)
{
    $attributes = shortcode_atts(array(
        'table_class' => 'table table-striped',
        'table_id' => 'example',
        'order_id' => '',
        'editable' => false,
        'admin' => false
    ), $atts);
    include_once(get_stylesheet_directory() . '/views/table/csv.php');
}
add_shortcode('table_csv_shc', 'table_csv');

/*
* Make shortcode for template tikets factory queries
*/
function show_factory_queries($atts)
{
    $attributes = shortcode_atts(array(
        'order_id' => '',
    ), $atts);
    include dirname(__DIR__) . '/views/order-tickets.php';
}

add_shortcode('display_factory_queries', 'show_factory_queries');