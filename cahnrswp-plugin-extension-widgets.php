<?php
/*
Plugin Name: CAHNRS Extension Widgets
Plugin URI:	http://cahnrs.wsu.edu/communications/
Description: A suite of widgets for the Extension site.
Author:	CAHNRS, philcable
Version: 0.1.0
*/

class CAHNRSWP_Plugin_Extension_Widgets {

	/**
	 * Fire necessary hooks when instantiated.
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
	}

	/**
	 * Register widgets.
	 */
	public function widgets_init() {
		include plugin_dir_path( __FILE__ ) . 'widgets/map.php';
		include plugin_dir_path( __FILE__ ) . 'widgets/programs.php';
	}

}

new CAHNRSWP_Plugin_Extension_Widgets();