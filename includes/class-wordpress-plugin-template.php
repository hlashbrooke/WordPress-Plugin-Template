<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WordPress_Plugin_Template {

	/**
	 * The single instance of WordPress_Plugin_Template.
	 *
	 * @var WordPress_Plugin_Template
	 * @since 1.0.0
	 */
	private static $_instance = null;

	/**
	 * Settings class object
	 *
	 * @var WordPress_Plugin_Template_Settings
	 * @since 1.0.0
	 */
	public $settings = null;

	/**
	 * The version number.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $_version;

	/**
	 * The token.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $_token;

	/**
	 * The main plugin file.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for Javascripts.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor function.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $file
	 * @param  string $version
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'wordpress_plugin_template';

		// Load plugin environment variables
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, [ $this, 'install' ] );

		// Load frontend JS & CSS
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 10 );

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ], 10, 1 );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ], 10, 1 );

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new WordPress_Plugin_Template_Admin_API();
		}

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', [ $this, 'load_localisation' ], 0 );
	}

	/**
	 * Load frontend CSS.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', [], $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	}

	/**
	 * Load frontend Javascript.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', [ 'jquery' ], $this->_version );
		wp_enqueue_script( $this->_token . '-frontend' );
	}

	/**
	 * Load admin CSS.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_styles() {
		wp_register_style( $this->_token . '-admin', esc_url( $this->assets_url ) . 'css/admin.css', [], $this->_version );
		wp_enqueue_style( $this->_token . '-admin' );
	}

	/**
	 * Load admin Javascript.
	 *
	 * @since 1.0.0
	 */
	public function admin_enqueue_scripts() {
		wp_register_script( $this->_token . '-admin', esc_url( $this->assets_url ) . 'js/admin' . $this->script_suffix . '.js', [ 'jquery' ], $this->_version );
		wp_enqueue_script( $this->_token . '-admin' );
	}

	/**
	 * Load plugin localisation
	 *
	 * @since 1.0.0
	 */
	public function load_localisation() {
		load_plugin_textdomain( 'wordpress-plugin-template', false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Load plugin textdomain
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain = 'wordpress-plugin-template';

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Main WordPress_Plugin_Template Instance
	 * Ensures only one instance of WordPress_Plugin_Template is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @see   WordPress_Plugin_Template()
	 *
	 * @param string $file
	 * @param string $version
	 *
	 * @return WordPress_Plugin_Template
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->_version );
	}

	/**
	 * Installation. Runs on activation.
	 *
	 * @since 1.0.0
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 *
	 * @since 1.0.0
	 */
	private function _log_version_number() {
		update_option( $this->_token . '_version', $this->_version );
	}
}