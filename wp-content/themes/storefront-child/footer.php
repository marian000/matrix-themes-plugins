<?php
    /**
     * The template for displaying the footer.
     *
     * Contains the closing of the #content div and all content after
     *
     * @package storefront
     */

?>

</div><!-- .col-full -->
</div><!-- #content -->

<?php do_action('storefront_before_footer'); ?>

<div style="display: none;">
    <?php
//        $users = get_users(array(
//            'meta_key'     => 'app_access',
//            'meta_value'   => 'yes',
//            'meta_compare' => '=',
//        ));
//
//        //        echo '<pre>';
//        //        print_r($users);
//        //        echo '</pre>';
//        echo '<ol>';
//        $n = 0;
//        foreach($users as $user){
//            $n++;
//            echo '<li><br>'.$n.'. ';
//            echo ' Username: '.$user->user_nicename;
//            echo '<br> Email: '.$user->user_email;
////            print_r($user);
//            echo '</li>';
//        }
//        echo '</ol>';
    ?>
</div>

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="col-full">
        
        <?php
            /**
             * Functions hooked in to storefront_footer action
             *
             * @hooked storefront_footer_widgets - 10
             * @hooked storefront_credit         - 20
             */
            do_action('storefront_footer'); ?>
    
    </div><!-- .col-full -->
</footer><!-- #colophon -->

<style>
    .loader {
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .loader-spinner.loader {
        max-width: 200px;
        position: absolute;
        left: 0;
        right: 0;
        margin: 0 auto;
        top: 40vh;
    }

    /* Center the loader */
    #loader-bkg {
        position: fixed;
        left: 17%;
        top: 0;
        z-index: 999999;
        width: 83%;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.97);
    }

    #loader-bkg > h1, #loader-bkg > h2 {
        position: absolute;
        top: 41%;
        z-index: 1;
        margin: -75px 0 0 -75px;
        display: block;
        width: 100%;
        text-align: center;
    }

    #loader-bkg > h2 {
        top: 35%;
    }

    #loader {
        position: absolute;
        left: calc(50% - 60px);
        top: 50%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
    }
</style>

<div id="loader-bkg" class="update-cart" style="display: none;">
    <h1>Please Wait!!!</h1>
    <br>
    <h2>We update cart items to new version!</h2>
    <div id="loader"></div>
</div>

<div class="loader-spinner loader" style="display: none;"></div>

<?php do_action('storefront_after_footer'); ?>

</div><!-- #page -->

<script>

    jQuery(document).ready(function () {

        setTimeout(function () {
            if (jQuery('.woocommerce-error').length) {
                console.log('gasit');
                jQuery('.order-summary-table').hide();
            }
        }, 2000);


        jQuery('a.btn.btn-primary[href="#new"]').on('click', function () {

            // setTimeout(function () {
            //     //window.location.reload();
            //     window.location.replace("/checkout");
            // }, 1000);

        });


        jQuery('a.btn.btn-primary[href="#newpos"]').on('click', function () {
            // setTimeout(function () {
            //     //window.location.reload();
            //     window.location.replace("/order-pos");
            // }, 1300);
        });

        jQuery('a.btn.btn-primary[href="#newcomponent"]').on('click', function () {
            // setTimeout(function () {
            //     //window.location.reload();
            //     window.location.replace("/order-components");
            // }, 1300);
        });


        jQuery('.btn.btn-primary[href="#edit"]').on('click', function () {

            setTimeout(function () {
                //window.location.reload();
                window.location.replace("/");
            }, 500);

        });


        jQuery(".woocommerce-order .btn.btn-danger.delete-btn.hidden").trigger('click');


        jQuery('.table .delete-btn').on('click', function () {

            setTimeout(function () {
                //window.location.reload();
                window.location.replace("/");
            }, 500);

        });


        if (jQuery(".tr-selected").length == 0) {

            jQuery(".btn-primary.ab-item").click();
            //alert('click select');

        }

        jQuery('.btn.btn-danger.delete-btn').on('click', function (e) {
            var id_current = jQuery(this).attr('target');
            var position = jQuery(this).attr('indice');
            var newpost = position;
            //alert('click delete '+newpost);


            $.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/select-delete.php",
                data: {newpost: newpost}
            })
                .done(function (msg) {
                    //alert( "Data Saved: " + msg );
                    jQuery('show-table2').html(msg);

                    setTimeout(function () {
                        //window.location.reload();
                        window.location.replace("/");
                    }, 300);

                });


        });


        // function for side menu - Add New POS ORDER
        jQuery('a[title="pos-order-page"]').attr("onclick", "return BMCWcMs.command('insertpos', this);");


        // Hideo or show menu on responsive

        if (jQuery(window).width() < 768) {
            console.log('Less than 960');

            jQuery(".menu-toggle").before('<form style="margin: 0px;" class="navbar-form navbar-left col-lg-6 col-md-6 col-xs-8" method="GET" action="/order-history"><div class="input-group" style="padding-top: 5px;"><input class="form-control" placeholder="Search" name="customer_order" type="text"><div class="input-group-btn"><button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button></div></div></form>');

        } else {
            console.log('More than 960');
        }


    });

</script>

<script>

</script>

<?php if (!is_front_page()) : ?>
    <script>
        jQuery(function ($) {
            jQuery('li.order-pos-menu').remove();
        });
    </script>
<?php endif;
    
    $args = array(
        'meta_key' => 'current_selected_group',
        'meta_value' => 'SBG_group',
        'meta_compare' => '=',
    );
    $users_cl = get_users($args);
    
    foreach ($users_cl as $user) {
        $earth = get_user_meta($user->ID, 'Earth', true);
        $supreme = get_user_meta($user->ID, 'Supreme', true);
        $biowood = get_user_meta($user->ID, 'Biowood', true);
        $green = get_user_meta($user->ID, 'Green', true);
        $ecowood = get_user_meta($user->ID, 'Ecowood', true);

//        if ($earth > 0 && !empty($earth)) {
//            $earth_new = (($earth * 5) / 100) + $earth;
//            update_user_meta($user->ID, 'Earth', number_format((float)$earth_new, 2, '.', ''));
//        }
//        if ($supreme > 0 && !empty($supreme)) {
//            $supreme_new = (($supreme * 5) / 100) + $supreme;
//            update_user_meta($user->ID, 'Supreme', number_format((float)$supreme_new, 2, '.', ''));
//        }
//        if ($biowood > 0 && !empty($biowood)) {
//            $biowood_new = (($biowood * 5) / 100) + $biowood;
//            update_user_meta($user->ID, 'Biowood', number_format((float)$biowood_new, 2, '.', ''));
//        }
//        if ($green > 0 && !empty($green)) {
//            $green_new = (($green * 5) / 100) + $green;
//            update_user_meta($user->ID, 'Green', number_format((float)$green_new, 2, '.', ''));
//        }
//        if ($ecowood > 0 && !empty($ecowood)) {
//            $ecowood_new = (($ecowood * 5) / 100) + $ecowood;
//            update_user_meta($user->ID, 'Ecowood', number_format((float)$ecowood_new, 2, '.', ''));
//        }
    }
    
    /*
     * Get most recent order in date descending order.
     */
    //    $query = new WC_Order_Query( array(
    //        'limit' => -1,
    //        'orderby' => 'date',
    //        'order' => 'DESC',
    //        'return' => 'ids',
    //    ) );
    //    $orders = $query->get_orders();
    //
    //    foreach ( $orders as $order_id ){
    //        update_post_meta($order_id, 'QB_invoice', true);
    //    }

    
    wp_footer();
    ?>

</body>
</html>
