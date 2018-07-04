<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WordPress_Plugin_Template_Settings {

	/**
	 * The single instance of WordPress_Plugin_Template_Settings.
	 *
	 * @var object
	 * @since 1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 *
	 * @var object
	 * @since 1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 *
	 * @var string
	 * @since 1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $settings = [];

	/**
	 * WordPress_Plugin_Template_Settings constructor.
	 *
	 * @param WordPress_Plugin_Template $parent
	 */
	public function __construct( $parent ) {
		$this->parent = $parent;
		$this->base = 'wpt_';

		// Initialise settings
		add_action( 'init', [ $this, 'init_settings' ], 11 );

		// Register plugin settings
		add_action( 'admin_init', [ $this, 'register_settings' ] );

		// Add settings page to menu
		add_action( 'admin_menu', [ $this, 'add_menu_item' ] );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ), [ $this, 'add_settings_link' ] );
	}

	/**
	 * Initialise settings
	 */
	public function init_settings() {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 */
	public function add_menu_item() {
		$page = add_options_page(
			__( 'Plugin Settings', 'wordpress-plugin-template' ),
			__( 'Plugin Settings', 'wordpress-plugin-template' ),
			'manage_options',
			$this->parent->_token . '_settings',
			[  $this, 'settings_page' ]
		);

		add_action( 'admin_print_styles-' . $page, [ $this, 'settings_assets' ] );
	}

	/**
	 * Load settings JS & CSS
	 */
	public function settings_assets() {

		// We're including the farbtastic script & styles here because they're needed for the colour picker
		// If you're not including a colour picker field then you can leave these calls out as well as the farbtastic dependency for the wpt-admin-js script below
		// wp_enqueue_style( 'farbtastic' );
		// wp_enqueue_script( 'farbtastic' );

		// We're including the WP media scripts here because they're needed for the image upload field
		// If you're not including an image upload then you can leave this function call out
		wp_enqueue_media();

		wp_register_script(
			$this->parent->_token . '-settings-js',
			$this->parent->assets_url . 'js/settings' . $this->parent->script_suffix . '.js',
			[ 'jquery' ], // [ 'farbtastic', 'jquery' ],
			'1.0.0'
		);
		wp_enqueue_script( $this->parent->_token . '-settings-js' );
	}

	/**
	 * Add settings link to plugin list table
	 *
	 * @param  array $links Existing links
	 *
	 * @return array        Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings', 'wordpress-plugin-template' ) . '</a>';
		array_push( $links, $settings_link );

		return $links;
	}

	/**
	 * Build settings fields
	 *
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {

		$settings['standard'] = [
			'title'       => __( 'Standard', 'wordpress-plugin-template' ),
			'description' => __( 'These are fairly standard form input fields.', 'wordpress-plugin-template' ),
			'fields'      => [
				[
					'id'          => 'text_field',
					'label'       => __( 'Some Text', 'wordpress-plugin-template' ),
					'description' => __( 'This is a standard text field.', 'wordpress-plugin-template' ),
					'type'        => 'text',
					'default'     => '',
					'placeholder' => __( 'Placeholder text', 'wordpress-plugin-template' )
				],
				[
					'id'          => 'password_field',
					'label'       => __( 'A Password', 'wordpress-plugin-template' ),
					'description' => __( 'This is a standard password field.', 'wordpress-plugin-template' ),
					'type'        => 'password',
					'default'     => '',
					'placeholder' => __( 'Placeholder text', 'wordpress-plugin-template' )
				],
				[
					'id'          => 'secret_text_field',
					'label'       => __( 'Some Secret Text', 'wordpress-plugin-template' ),
					'description' => __( 'This is a secret text field - any data saved here will not be displayed after the page has reloaded, but it will be saved.', 'wordpress-plugin-template' ),
					'type'        => 'text_secret',
					'default'     => '',
					'placeholder' => __( 'Placeholder text', 'wordpress-plugin-template' )
				],
				[
					'id'          => 'text_block',
					'label'       => __( 'A Text Block', 'wordpress-plugin-template' ),
					'description' => __( 'This is a standard text area.', 'wordpress-plugin-template' ),
					'type'        => 'textarea',
					'default'     => '',
					'placeholder' => __( 'Placeholder text for this textarea', 'wordpress-plugin-template' )
				],
				[
					'id'          => 'single_checkbox',
					'label'       => __( 'An Option', 'wordpress-plugin-template' ),
					'description' => __( 'A standard checkbox - if you save this option as checked then it will store the option as \'on\', otherwise it will be an empty string.', 'wordpress-plugin-template' ),
					'type'        => 'checkbox',
					'default'     => ''
				],
				[
					'id'          => 'select_box',
					'label'       => __( 'A Select Box', 'wordpress-plugin-template' ),
					'description' => __( 'A standard select box.', 'wordpress-plugin-template' ),
					'type'        => 'select',
					'options'     => [ 'drupal' => 'Drupal', 'joomla' => 'Joomla', 'wordpress' => 'WordPress' ],
					'default'     => 'wordpress'
				],
				[
					'id'          => 'radio_buttons',
					'label'       => __( 'Some Options', 'wordpress-plugin-template' ),
					'description' => __( 'A standard set of radio buttons.', 'wordpress-plugin-template' ),
					'type'        => 'radio',
					'options'     => [ 'superman' => 'Superman', 'batman' => 'Batman', 'ironman' => 'Iron Man' ],
					'default'     => 'batman'
				],
				[
					'id'          => 'multiple_checkboxes',
					'label'       => __( 'Some Items', 'wordpress-plugin-template' ),
					'description' => __( 'You can select multiple items and they will be stored as an array.', 'wordpress-plugin-template' ),
					'type'        => 'checkbox_multi',
					'options'     => [
						'square'    => 'Square',
						'circle'    => 'Circle',
						'rectangle' => 'Rectangle',
						'triangle'  => 'Triangle'
					],
					'default'     => [ 'circle', 'triangle' ]
				]
			]
		];

		$settings['extra'] = [
			'title'       => __( 'Extra', 'wordpress-plugin-template' ),
			'description' => __( 'These are some extra input fields that maybe aren\'t as common as the others.', 'wordpress-plugin-template' ),
			'fields'      => [
				[
					'id'          => 'number_field',
					'label'       => __( 'A Number', 'wordpress-plugin-template' ),
					'description' => __( 'This is a standard number field - if this field contains anything other than numbers then the form will not be submitted.', 'wordpress-plugin-template' ),
					'type'        => 'number',
					'default'     => '',
					'placeholder' => __( '42', 'wordpress-plugin-template' )
				],
				[
					'id'          => 'colour_picker',
					'label'       => __( 'Pick a colour', 'wordpress-plugin-template' ),
					'description' => __( 'This uses WordPress\' built-in colour picker - the option is stored as the colour\'s hex code.', 'wordpress-plugin-template' ),
					'type'        => 'color',
					'default'     => '#21759B'
				],
				[
					'id'          => 'an_image',
					'label'       => __( 'An Image', 'wordpress-plugin-template' ),
					'description' => __( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an imge the thumbnail will display above these buttons.', 'wordpress-plugin-template' ),
					'type'        => 'image',
					'default'     => '',
					'placeholder' => ''
				],
				[
					'id'          => 'multi_select_box',
					'label'       => __( 'A Multi-Select Box', 'wordpress-plugin-template' ),
					'description' => __( 'A standard multi-select box - the saved data is stored as an array.', 'wordpress-plugin-template' ),
					'type'        => 'select_multi',
					'options'     => [ 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ],
					'default'     => [ 'linux' ]
				]
			]
		];

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

			foreach ( $this->settings as $section => $data ) {

				if ( $current_section && $current_section != $section ) {
					continue;
				}

				// Add section to page
				add_settings_section( $section, $data['title'], [
					$this,
					'settings_section'
				], $this->parent->_token . '_settings' );

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], [
						$this->parent->admin,
						'display_field'
					], $this->parent->_token . '_settings', $section, [
						'field'  => $field,
						'prefix' => $this->base
					] );
				}

				if ( ! $current_section ) {
					break;
				}
			}
		}
	}

	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 */
	public function settings_page() {

		// Build page HTML
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";
		$html .= '<h2>' . __( 'Plugin Settings', 'wordpress-plugin-template' ) . '</h2>' . "\n";

		$tab = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$tab .= $_GET['tab'];
		}

		// Show page tabs
		if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

			$html .= '<h2 class="nav-tab-wrapper">' . "\n";

			$c = 0;
			foreach ( $this->settings as $section => $data ) {

				// Set tab class
				$class = 'nav-tab';
				if ( ! isset( $_GET['tab'] ) ) {
					if ( 0 == $c ) {
						$class .= ' nav-tab-active';
					}
				} else {
					if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
						$class .= ' nav-tab-active';
					}
				}

				// Set tab link
				$tab_link = add_query_arg( [ 'tab' => $section ] );
				if ( isset( $_GET['settings-updated'] ) ) {
					$tab_link = remove_query_arg( 'settings-updated', $tab_link );
				}

				// Output tab
				$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

				++ $c;
			}

			$html .= '</h2>' . "\n";
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

		// Get settings fields
		ob_start();
		settings_fields( $this->parent->_token . '_settings' );
		do_settings_sections( $this->parent->_token . '_settings' );
		$html .= ob_get_clean();

		$html .= '<p class="submit">' . "\n";
		$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
		$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings', 'wordpress-plugin-template' ) ) . '" />' . "\n";
		$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main WordPress_Plugin_Template_Settings Instance
	 *
	 * Ensures only one instance of WordPress_Plugin_Template_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @see   WordPress_Plugin_Template()
	 *
	 * @param WordPress_Plugin_Template $parent
	 *
	 * @return WordPress_Plugin_Template_Settings
	 */
	public static function instance( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	}
}