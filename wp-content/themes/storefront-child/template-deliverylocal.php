<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: DeliveryLocal template
 *
 */

get_header(); ?>




	<div id="primary" class="content-area" >
		<main id="main" class="site-main" role="main">

       <?php if( current_user_can('administrator') || get_current_user_id() == 83 ) {  ?>


<form action="" method="GET">
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 form-group">
                            <label for="order_select">Show orders from container</label>
                            <br>
                          
<select name="order_id" id="order_select" class="form-control">
    <?php
    $rand_posts = get_posts( array(
        'post_type' => 'container',
        'posts_per_page' => 15,
    ) );
     
    if ( $rand_posts ) {
    foreach ( $rand_posts as $post ) : 
       ?>
        <option value="<?php echo get_the_id(); ?>" <?php if( $_GET['order_id'] == get_the_id() ){ echo 'selected'; } ?> ><?php the_title(); ?></option>
        <?php
    endforeach; 
    wp_reset_postdata();
    }
    ?>
</select>
                        </div>
                        <div class="col-sm-3 col-lg-3 form-group">

                        </div>
                        <div class="col-sm-3 col-lg-3 form-group">
                        <br>
                            <input type="submit" name="submit" value="Search"  class="btn btn-info" >
                            
                        </div>
                    </div>
                    
                    
                </form>

<?php

if(!empty($_GET['order_id'])){

    $order_id = $_GET['order_id']; 

}
else{ 
    
    $args = array(
        'post_type' =>'container',
        'posts_per_page' => 1,
        'orderby'=>'post_date',
        'order' => 'DESC',
    );

    $image_posts = get_posts($args);
    foreach ( $image_posts  as $post )
    {
        $order_id = $post->ID; 
    }

}

$container_orders = get_post_meta( $order_id, 'container_orders', true);
//print_r($container_orders);
arsort($container_orders);
//print_r($container_orders);
?>
<div class="clearfix"></div>
<div class="row container-order">
    <div class="col-md-12">
        <table id="example"
               style="width:100%; display:table;"
               class="table table-striped">
            <thead>
            <tr>
                <th>
                    Order
                </th>
                <th>
                    Order
                    Ref
                </th>
                <th>
                    Date
                </th>
                <th>
                    Phone
                </th>
                <th>
                    Company Name
                </th>
                <th>
                    Delivery Add
                </th>
                <th>
                    SQM
                </th>
                <th>
                    Weight
                </th>
                <th>
                    Delivery cost
                </th>
               

            </tr>
            </thead>
            <tbody>

            <?php
            if ($container_orders) {
                foreach ($container_orders as $order_id) {

                    if ( get_post_type( $order_id ) == 'order_repair' ) {
                        $order_original = get_post_meta($order_id,'order-id-original',true);
                        $order = wc_get_order($order_original);
                        $order_data = $order->get_data();
                    }
                    else{
                        $order = wc_get_order($order_id);
                        $order_data = $order->get_data();
                    }
                    // echo '<pre>';
                    // print_r($order_data);
                    // echo '</pre>';

                    // $order = wc_get_order($order);
                    // $order_data = $order->get_data();
                    $full_payment = 0;
                    //$user_id_customer = get_post_meta(get_the_id(), '_customer_user', true);
                    //echo 'USER ID '.$user_id;

                    $i = 0;
                    $atributes = get_post_meta(1, 'attributes_array', true);
                    $items = $order->get_items();

                    $nr_code_prod = array();
                   
                    if( $order_data['shipping_total'] == 0 && $order_data['billing']['company'] !== 'Lifetime Shutters' ){
                    ?>
                    <tr>
                        <td>
                           
                            <?php  if ( get_post_type( $order_id ) == 'order_repair' ) { ?>
                                LFR0<?php echo $order->get_order_number(); ?>
                            <?php }
                            else { ?>
                                LF0<?php echo $order->get_order_number(); ?>
                            <?php } ?>
                           
                        </td>
                        <td><?php echo get_post_meta($order->ID, 'cart_name', true); ?></td>
                        <td><?php echo $order_data['date_modified']->date('Y-m-d H:i:s'); ?></td>
                        <td><?php echo $order_data['billing']['phone']; ?></td>
                        <td><?php echo $order_data['billing']['company']; ?></td>
                        <td><?php echo $order_data['billing']['address_1'].' - '.$order_data['billing']['postcode'].' - '.$order_data['billing']['city'].' - '.$order_data['billing']['country']; ?></td>
                        <td><?php $sqm_total = 0;
                            foreach ($items as $item_id => $item_data) {
                                $product_id = $item_data['product_id'];
                                $prod_qty = get_post_meta($product_id, 'quantity',true);
                                $property_total = get_post_meta($product_id, 'property_total', true);
                                $sqm_total = $sqm_total + $property_total * $prod_qty;
                            }
                            echo number_format($sqm_total, 2); ?>
                        </td>
                        <td>Weight</td>
                        <td>
                            <?php echo $order_data['shipping_total']; ?>
                        </td>
                    </tr>


                <?php }
                }
            } ?>

                <tfoot>
                    <tr>
                        <th>
                            Order
                        </th>
                        <th>
                            Order
                            Ref
                        </th>
                        <th>
                            Date
                        </th>
                        <th>
                            Phone
                        </th>
                        <th>
                            Company Name
                        </th>
                        <th>
                            Delivery Add
                        </th>
                        <th>
                            SQM
                        </th>
                        <th>
                            Weight
                        </th>
                        <th>
                            Delyvery cost
                            $
                        </th>
                    </tr>
                </tfoot>

            </tbody>
        </table>
        <hr>
    </div>
</div>

<div class="clearfix"></div>

<style>
    .row.container-order {
        margin-bottom: 50px;
    }
</style>

<? } ?>

</main>
</div>


<script>

jQuery(document).ready(function() {
    jQuery('#example').DataTable({
        lengthMenu: [100, 200, 500 ],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]

    });
} );

</script>







<?php
get_footer();
