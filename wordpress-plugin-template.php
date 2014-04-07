<?php
/*
 * Plugin Name: WordPress Plugin Template
 * Version: 1.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: 
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 3.8
 * Tested up to: 3.8.1
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Include plugin class files
require_once( 'includes/class-wordpress-plugin-template.php' );
require_once( 'includes/class-wordpress-plugin-template-settings.php' );

/**
 * Returns the main instance of WordPress_Plugin_Template to prevent the need to use globals.
 *
 * @since  2.0.0
 * @return object WordPress_Plugin_Template
 */
function WordPress_Plugin_Template () {
	$instance = WordPress_Plugin_Template::instance( __FILE__, '2.0.0' );
	if( is_null( $instance->settings ) ) {
		$instance->settings = WordPress_Plugin_Template_Settings::instance( $instance );
	}
	return $instance;
}

WordPress_Plugin_Template();