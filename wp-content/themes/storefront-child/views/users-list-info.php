<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Use Font Awesome Free CDN modified-->
<link rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<script type='text/javascript'
        src='/wp-content/themes/storefront-child/js/highcharts.js'></script>
<style>#container {
        min-width: 310px;
        max-width: 1024px;
        height: 400px;
        margin: 0 auto;
    }</style>
<div id="primary"
     class="content-area container">
    <main id="main"
          class="site-main"
          role="main">

        <h2>Lifetime Shutters Dealers Info</h2>


        <div class="row">
            <div class="col-md-12">
                <div id="users_month">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Shipping Address</th>
                            <!--th>City</th>
                            <th>zip code</th-->
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        $users = get_users();
                        // Array of WP_User objects.
//                        $i = 0;
                        foreach ($users as $user) {
//                            $nrOrders = wc_get_customer_order_count($user->ID);
                            if ($user->ID != 1 && current_user_can('publish_posts') ) {
//                                $i++;
//                                $DBRecord[$i]['role'] = "Dealer";
//                                $DBRecord[$i]['WPId'] = $user->ID;
//                                $DBRecord[$i]['FirstName'] = $user->first_name;
//                                $DBRecord[$i]['LastName'] = $user->last_name;
//                                $DBRecord[$i]['RegisteredDate'] = $user->user_registered;
//                                $DBRecord[$i]['Email'] = $user->user_email;

                                $UserData = get_user_meta($user->ID);
//                                $DBRecord[$i]['Company'] = $UserData['shipping_company'][0];
//                                $DBRecord[$i]['Address'] = $UserData['shipping_address_1'][0];
//                                $DBRecord[$i]['City'] = $UserData['shipping_city'][0];
//                                $DBRecord[$i]['State'] = $UserData['shipping_state'][0];
//                                $DBRecord[$i]['PostCode'] = $UserData['shipping_postcode'][0];
//                                $DBRecord[$i]['Country'] = $UserData['shipping_country'][0];
//                                $DBRecord[$i]['Phone'] = $UserData['shipping_phone'][0];
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $UserData['shipping_company'][0]; ?>
                                    </td>
                                    <td>
                                        <?php echo $user->shipping_first_name . ' ' . $user->shipping_last_name; ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo $user->user_email;
                                        ?>
                                    </td>
                                    <td>
                                        <ul>
                                            <li><?php echo $UserData['shipping_address_1'][0]; ?></li>
                                            <li><?php echo $UserData['shipping_address_2'][0]; ?></li>
                                            <li><?php echo $UserData['shipping_city'][0]; ?></li>
                                            <li><?php echo $UserData['shipping_postcode'][0]; ?></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <?php echo $UserData['billing_phone'][0]; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }

                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>
    <!-- #main -->
</div>
<!-- #primary -->