<?php
/*
 * Plugin Name: WordPress Plugin Template
 * Version: 1.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: Basic template for creating a WordPress plugin
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 3.0
 * Tested up to: 3.5.1
 * 
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( 'classes/class-wordpress-plugin-template.php' );

global $wc_ohd;
$wc_ohd = new WordPress_Plugin_Template( __FILE__ );