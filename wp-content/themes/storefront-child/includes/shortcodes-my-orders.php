<?php

function show_my_orders($atts)
{
	include dirname(__DIR__) . '/templates/my-orders.php';
}
add_shortcode('display_my_orders', 'show_my_orders');

function show_my_placed_orders($atts)
{
	include dirname(__DIR__) . '/templates/my-placed-orders.php';
}
add_shortcode('display_my_placed_orders', 'show_my_placed_orders');

function show_my_transformed_orders($atts)
{
	$attributes = shortcode_atts(array(
		'order_id' => '',
	), $atts);
	include dirname(__DIR__) . '/templates/my-transformed-orders.php';
}
add_shortcode('display_my_transformed_orders', 'show_my_transformed_orders');


function show_my_app_orders($atts)
{
	include dirname(__DIR__) . '/templates/my-app-orders.php';
}
add_shortcode('display_my_app_orders', 'show_my_app_orders');