<?php
/*
 * Plugin Name: WordPress Plugin Template
 * Version: 1.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: Basic template for creating a WordPress plugin.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 3.0
 * Tested up to: 3.7.1
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Include plugin class files
require_once( 'classes/class-wordpress-plugin-template.php' );
require_once( 'classes/class-wordpress-plugin-template-settings.php' );
require_once( 'classes/post-types/class-wordpress-plugin-template-post_type.php' );

// Instantiate necessary classes
global $plugin_obj;
$plugin_obj = new WordPress_Plugin_Template( __FILE__ );
$plugin_settings_obj = new WordPress_Plugin_Template_Settings( __FILE__ );
$plugin_post_type_obj = new WordPress_Plugin_Template_Post_Type( __FILE__ );