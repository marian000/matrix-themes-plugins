<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    
    <meta http-equiv="cache-control" content="no-cache"/>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- Use Font Awesome Free CDN modified-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <?php wp_head(); ?>
    
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!--    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
</head>

<body <?php body_class(); ?>>

<?php do_action('storefront_before_site'); ?>

<div id="page" class="hfeed site">
    <?php do_action('storefront_before_header'); ?>
    
    <header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
        <div class="col-full">
            
            <?php
                /**
                 * Functions hooked into storefront_header action
                 *
                 * @hooked storefront_skip_links                       - 0
                 * @hooked storefront_social_icons                     - 10
                 * @hooked storefront_site_branding                    - 20
                 * @hooked storefront_secondary_navigation             - 30
                 * @hooked storefront_product_search                   - 40
                 * @hooked storefront_primary_navigation_wrapper       - 42
                 * @hooked storefront_primary_navigation               - 50
                 * @hooked storefront_header_cart                      - 60
                 * @hooked storefront_primary_navigation_wrapper_close - 68
                 */
                do_action('storefront_header'); ?>
        
        </div>
    </header><!-- #masthead -->
    
    <?php
        /**
         * Functions hooked in to storefront_before_content
         *
         * @hooked storefront_header_widget_region - 10
         */
        do_action('storefront_before_content'); ?>
    
    
    <?php
        if (current_user_can('administrator') || current_user_can('china_admin') || current_user_can('shop_manager') || current_user_can('employe') || current_user_can('emplimited') || current_user_can('outsider')) { ?>
            <style>
                .navbar.navbar-default.navbar-top-blue-bkg.navbar-fixed-top {
                    margin-top: 32px;
                }
                
                @media only screen and (max-width: 900px) {
                    .main-navigation ul.menu, .main-navigation ul.nav-menu {
                        margin-top: 25px;
                    }
                }
            </style>
        <?php } else { ?>
            <style>
                #wpadminbar {
                    display: none;
                }
                
                html {
                    margin-top: 0px !important;
                }
            </style>
            <?php
        }
        if (!is_user_logged_in() && is_page('Login')) { ?>
            
            <style>
                #content .col-full {
                    background-color: #ced3e4;
                    background-image: url('/wp-content/uploads/2018/Matrix2.gif');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: left;
                    margin: 0px;
                    width: 100%;
                    max-width: 100%;
                    padding-top: 20vh;
                    padding-bottom: 15vh;
                }
            </style>
        
        <?php }
    ?>
    
    <div id="content" class="site-content" tabindex="-1">
        <nav class="navbar navbar-default navbar-top-blue-bkg navbar-fixed-top">
            <div class="container-fluid row">
                <div class="navbar-header col-lg-4 col-md-4" style="width: 24vw;">
                    <div class="site-branding">
                        <div class="beta site-title">
                            <a href="/" rel="home">MATRIX</a>
                        </div>
                    </div>
                </div>
                
                <?php
                    $classes = get_body_class();
                    if (get_the_id() == '11563' || in_array('search-tickets',$classes) || is_singular( 'stgh_ticket' ) ) { ?>
                <form role="search" class="navbar-form navbar-left col-lg-6 col-md-6" action="<?php echo site_url('/search-tickets/'); ?>" method="get" id="searchform">
                    <div class="input-group" style="padding-top: 5px;">
                        <input type="text" class="form-control" name="s" placeholder="Search ticket"/>
                        <input type="hidden" name="post_type" value="stgh_ticket" /> <!-- // hidden 'products' value -->
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                    <?php } else {
                        ?>
                        <form class="navbar-form navbar-left col-lg-6 col-md-6" method="GET" action="/order-history">
                            <div class="input-group" style="padding-top: 5px;">
                                <input type="text" class="form-control" placeholder="Search" name="customer_order">
                                <div class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php
                    }
                ?>
                
                <?php
                    if (is_user_logged_in()) { ?>
                        
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="/profile"><span class="glyphicon glyphicon-user"></span>
                                    <?php
                                        $current_user = wp_get_current_user();
                                        echo $current_user->user_firstname . ' ' . $current_user->user_lastname;
                                    ?>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo wp_logout_url(home_url()); ?>"><span class="glyphicon glyphicon-log-in"></span> Log Out</a>
                            </li>
                        </ul>
                    
                    <?php }
                ?>
            
            </div>
        </nav>
        
        <div class="col-full menu-head">

<?php
    /**
     * Functions hooked in to storefront_content_top
     *
     * @hooked woocommerce_breadcrumb - 10
     */
    do_action('storefront_content_top');
