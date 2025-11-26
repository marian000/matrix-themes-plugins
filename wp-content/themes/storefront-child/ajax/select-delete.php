<?php
$path = preg_replace('/wp-content(?!.*wp-content).*/', '', __DIR__);
include($path.
    'wp-load.php');


    $user_id = get_current_user_id();
    $meta_key = 'wc_multiple_shipping_addresses';
    
    global $wpdb;
    if ( $addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key)) ) {
        $addresses = maybe_unserialize($addresses);
    }

    $addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");


?>
<input type="hidden" name="index" value="<?php echo $_POST['newpost']; ?>" >

    <table class="table home2">
    <thead style="background-color: #f2dede;">
        <th></th>
        <th>Order Reference</th>
        <th>Date Created</th>
        <th>Is selected?</th>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    <tbody>
        <?php

$userialize_data = unserialize($addresses[0]->meta_value);
$session_key = $userialize_data['customer_id'];

$session = $wpdb->get_results("SELECT session_value FROM `wp_woocommerce_sessions` WHERE `session_key` LIKE $session_key ");
//print_r($session);
$userialize_session = unserialize($session[0]->session_value);
// echo '<pre>';
// print_r($userialize_session);
// print_r($userialize_data);
// echo '</pre>';

$i = 1;
$carts_sort = $userialize_data['carts'];
ksort($carts_sort);
$total_elements = count($carts_sort)+1;
foreach($carts_sort as $key => $carts ){ 
// echo '<pre>';
// print_r($userialize_session);
// echo '</pre>';
if($carts['name'] == 'Order #1' ){

}
else{
?>				
            <tr class="<?php if($userialize_data['customer_id'] == $key){ echo " tr-selected "; } ?> hidden" position="<?php echo $i;?>" >
                <td>
                    <?php echo $total_elements - $i; //echo ' - '.$key; ?>
                </td>
                <td>
                    <?php if($userialize_data['customer_id'] == $key){ ?>
                    <a href="/checkout/">
                        <strong>
                            <?php echo $carts['name']; ?>
                        </strong>
                    </a>
                    <?php }else { echo $carts['name']; } ?>
                </td>
                <td>
                    <?php echo $newDate = date("d-m-Y", $carts['time']); ?>
                </td>
                <td>
                    <?php if($userialize_data['customer_id'] == $key){ echo "Current Order"; }else{ echo "Not Selected"; } ?>
                </td>
                <td>
                    <?php if($userialize_data['customer_id'] != $key){ ?>
                    <a class="btn btn-primary ab-item" aria-haspopup="true" href="#select" target="<?php echo $key; ?>" onclick="return BMCWcMs.command('select', this);"
                        title="Select Cart">Select</a>

                    <?php }
                    else{
                        echo "Selected";
                    } ?>
                    <?php if($userialize_data['customer_id'] != $key){ ?>
                    <a class="btn btn-primary" href="#edit" target="<?php echo $key; ?>" onclick="return BMCWcMs.command('update', this);">Edit Name</a>
                    <?php } ?>
                </td>
                <td>

                    <?php if($userialize_data['customer_id'] != $key){ ?>
                    <!-- <a class="btn btn-primary" href="#clone" target="<?php echo $key; ?>" onclick="return BMCWcMs.command('clone', this);" title="Create a new cart with the items and addresses of this cart.">Clone Order</a> -->
                    <?php } ?>
                </td>
                <td>
                    <?php //if($userialize_data['customer_id'] != $key){ ?>
                    <a class="btn btn-danger delete-btn" indice="<?php echo $i; ?>" href="#delete" target="<?php echo $key; ?>" onclick="return BMCWcMs.command('delete', this);">Delete Order</a>
                    <?php //} ?>
                </td>
            </tr>

            <?php
            }
$i++;
}
?>

    </tbody>

</table>


<script>

jQuery(document).ready(function(){
    var position = jQuery('input[name="index"]').val();
	jQuery('.home2 tr[position="'+position+'"] td.ab-item').click();
    alert('clicked');
});

</script>