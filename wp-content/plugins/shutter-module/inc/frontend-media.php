<?php
/*--------------------------------------------------
Author Dashboard Custom field [image]
--------------------------------------------------*/
  




/**
 * Class wrapper for Front End Media example
 */
class Front_End_Media {
	/**
	 * A simple call to init when constructed
	 */
	function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}
	function init() {
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'ajax_query_attachments_args', array( $this, 'filter_media' ) );
		add_shortcode( 'frontend-button', array( $this, 'frontend_shortcode' ) );
	}
	/**
	 * Call wp_enqueue_media() to load up all the scripts we need for media uploader
	 */
	// function enqueue_scripts() {
	// 	wp_enqueue_script( 'frontend-media-customm-js',plugin_dir_url( __FILE__ ) . '/js/frontend.js');
	// 	wp_enqueue_media();
	// }
	/**
	 * This filter insures users only see their own media
	 */
	function filter_media( $query ) {
		// admins get to see everything
		if ( ! current_user_can( 'manage_options' ) )
			$query['author'] = get_current_user_id();
		return $query;
	}
	function frontend_shortcode( $args ) {
	    $current_user = wp_get_current_user();
		// $user_pic_custom = get_the_author_meta( 'user_pic_custom', $current_user->ID); 
		var_dump(get_the_author_meta( 'user_pic_custom', $current_user->ID ));
		// check if user can upload files
		if ( current_user_can( 'upload_files' ) ) {
			$str = __( 'Select File', 'frontend-media' );
			return '<input id="frontend-button" type="button" value="' . $str . '" class="button" style="position: relative; z-index: 1;">
					<img id="frontend-image" src="'.get_the_author_meta( 'user_pic_custom', $current_user->ID ).'" />
					<input type="text" id="user_pic_custom" name="user_pic_custom" value="" />
					';
		}
		return __( 'Please Login To Upload', 'frontend-media' );
	}
}
new Front_End_Media();


?>