<?php

$plugin_dir = WP_PLUGIN_DIR . '/shutter-module';

// add shutter
function product_shutter()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-1.php';
}

add_shortcode('product_shutter1', 'product_shutter');

// add Individual Bay Shutters - prod2
function product_shutter2()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-2.php';
}

add_shortcode('product_shutter2', 'product_shutter2');

// add Shutter & Blackout Blind - prod3
function product_shutter3()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-3.php';
}

add_shortcode('product_shutter3', 'product_shutter3');

// add Blackout Frame - prod4
function product_shutter4()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-4.php';
}

add_shortcode('product_shutter4', 'product_shutter4');

// add Batten - prod5
function product_shutter5()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-5.php';
}

add_shortcode('product_shutter5', 'product_shutter5');
// add shutter
function product_shutter_edit()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-1-edit.php';

}

add_shortcode('product_shutter1_edit', 'product_shutter_edit');

function product_shutter3_edit()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-3-edit.php';

}

add_shortcode('product_shutter3_edit', 'product_shutter3_edit');

function product_shutter5_edit()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-5-edit.php';

}

add_shortcode('product_shutter5_edit', 'product_shutter5_edit');

//UPDATE ADMIN
// add shutter
function product_shutter_edit_admin()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-1-admin.php';

}

add_shortcode('product_shutter1_edit_admin', 'product_shutter_edit_admin');

function product_shutter3_edit_admin()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-3-admin.php';

}

add_shortcode('product_shutter3_edit_admin', 'product_shutter3_edit_admin');

function product_shutter5_edit_admin()
{

    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-5-admin.php';

}

add_shortcode('product_shutter5_edit_admin', 'product_shutter5_edit_admin');

/*
 *
 *    Shortcode for all shutter configuration prod 1 - add, edit, update
 *
 * */
// add shutter - prod1
function product_shutter_all()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-1-all.php';
}

add_shortcode('product_shutter1_all', 'product_shutter_all');

/*
 *
 *    Shortcode for all shutter configuration prod 1 - add, edit, update
 *
 * */
// add shutter - prod2
function product_shutter2_all()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-2-all.php';
}

add_shortcode('product_shutter2_all', 'product_shutter2_all');

// add shutter - porodIndividual
function product_shutter_individual()
{
    include_once WP_PLUGIN_DIR . '/shutter-module' . '/templates/prod-individual.php';
}

add_shortcode('product_shutter_individual', 'product_shutter_individual');
