<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WordPress_Plugin_Template {
	private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;

	public function __construct( $file ) {
		$this->dir = dirname( $file );
		$this->file = $file;
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $file ) ) );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( &$this, 'load_localisation' ), 0 );
	}
	
	public function load_localisation () {
		load_plugin_textdomain( 'plugin_textdomain' , false , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}
	
	public function load_plugin_textdomain () {
	    $domain = 'plugin_textdomain';
	    
	    $locale = apply_filters( 'plugin_locale' , get_locale() , $domain );
	 
	    load_textdomain( $domain , WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain , FALSE , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}
	
}