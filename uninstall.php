<?php

/**
 * 
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 */

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Do something here if plugin is being uninstalled.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//change your plugin prefix here
delete_options_prefixed( 'test_' );

function delete_options_prefixed( $prefix ) {
    global $wpdb;
    $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '{$prefix}%'" );
}
