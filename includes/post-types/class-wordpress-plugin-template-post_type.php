<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WordPress_Plugin_Template_Post_Type {

	/**
	 * The single instance of WordPress_Plugin_Template_Post_Type.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * The name for the custom post type.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $post_type;

	/**
	 * The plural name for the custom post type posts.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $plural;

	/**
	 * The singular name for the custom post type posts.
	 * @var 	string
	 * @access  public
	 * @since 	1.0.0
	 */
	public $single;

	public function __construct ( $parent ) {
		$this->parent = $parent;

		// Post type name and labels
		$this->post_type = 'post_type';
		$this->plural = _x( 'Posts', 'post type general name' , 'wordpress-plugin-template' );
		$this->single = _x( 'Post', 'post type singular name' , 'wordpress-plugin-template' );

		// Regsiter post type
		add_action( 'init' , array( $this, 'register_post_type' ) );

		// Register taxonomy
		add_action('init', array( $this, 'register_taxonomies' ) );

		if ( is_admin() ) {

			// Handle custom fields for post
			add_action( 'admin_menu', array( $this, 'meta_box_setup' ), 20 );
			add_action( 'save_post', array( $this, 'meta_box_save' ) );

			// Modify text in main title text box
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Handle post columns
			add_filter( 'manage_edit-' . $this->post_type . '_columns', array( $this, 'register_custom_column_headings' ), 10, 1 );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'register_custom_columns' ), 10, 2 );

		}

	}

	/**
	 * Register new post type
	 * @return void
	 */
	public function register_post_type () {

		$labels = array(
			'name' => $this->plural,
			'singular_name' => $this->single,
			'name_admin_bar' => $this->single,
			'add_new' => _x( 'Add New', $this->post_type , 'wordpress-plugin-template' ),
			'add_new_item' => sprintf( __( 'Add New %s' , 'wordpress-plugin-template' ), $this->single ),
			'edit_item' => sprintf( __( 'Edit %s' , 'wordpress-plugin-template' ), $this->single ),
			'new_item' => sprintf( __( 'New %s' , 'wordpress-plugin-template' ), $this->single ),
			'all_items' => sprintf( __( 'All %s' , 'wordpress-plugin-template' ), $this->plural ),
			'view_item' => sprintf( __( 'View %s' , 'wordpress-plugin-template' ), $this->single ),
			'search_items' => sprintf( __( 'Search %s' , 'wordpress-plugin-template' ), $this->plural ),
			'not_found' =>  sprintf( __( 'No %s Found' , 'wordpress-plugin-template' ), $this->plural ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash' , 'wordpress-plugin-template' ), $this->plural ),
			'parent_item_colon' => sprintf( __( 'Parent %s' ), $this->single ),
			'menu_name' => $this->plural,
		);

		$args = array(
			'labels' => $labels,
			'description' => __( '', 'wordpress-plugin-template' ),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'comments', 'thumbnail' ),
			'menu_position' => 5,
			'menu_icon' => 'dashicons-admin-post'
		);

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register new taxonomy
	 * @return void
	 */
	public function register_taxonomies () {

		$tax_name = 'post_type_terms';
		$tax_plural = __( 'Terms', 'wordpress-plugin-template' );
		$tax_single = __( 'Term', 'wordpress-plugin-template' );

        $labels = array(
            'name' => $tax_plural,
            'singular_name' => $tax_single,
            'menu_name' => $tax_plural,
            'all_items' => sprintf( __( 'All %s' , 'wordpress-plugin-template' ), $tax_plural ),
            'edit_item' => sprintf( __( 'Edit %s' , 'wordpress-plugin-template' ), $tax_single ),
            'view_item' => sprintf( __( 'View %s' , 'wordpress-plugin-template' ), $tax_single ),
            'update_item' => sprintf( __( 'Update %s' , 'wordpress-plugin-template' ), $tax_single ),
            'add_new_item' => sprintf( __( 'Add New %s' , 'wordpress-plugin-template' ), $tax_single ),
            'new_item_name' => sprintf( __( 'New %s Name' , 'wordpress-plugin-template' ), $tax_single ),
            'parent_item' => sprintf( __( 'Parent %s' , 'wordpress-plugin-template' ), $tax_single ),
            'parent_item_colon' => sprintf( __( 'Parent %s:' , 'wordpress-plugin-template' ), $tax_single ),
            'search_items' =>  sprintf( __( 'Search %s' , 'wordpress-plugin-template' ), $tax_plural ),
            'popular_items' =>  sprintf( __( 'Popular %s' , 'wordpress-plugin-template' ), $tax_plural ),
            'separate_items_with_commas' =>  sprintf( __( 'Separate %s with commas' , 'wordpress-plugin-template' ), $tax_plural ),
            'add_or_remove_items' =>  sprintf( __( 'Add or remove %s' , 'wordpress-plugin-template' ), $tax_plural ),
            'choose_from_most_used' =>  sprintf( __( 'Choose from the most used %s' , 'wordpress-plugin-template' ), $tax_plural ),
            'not_found' =>  sprintf( __( 'No %s found' , 'wordpress-plugin-template' ), $tax_plural ),
        );

        $args = array(
        	'label' => $tax_plural,
        	'labels' => $labels,
        	'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'meta_box_cb' => null,
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var' => $tax_name,
            'rewrite' => true,
            'sort' => '',
        );

        register_taxonomy( $tax_name , $this->post_type , $args );
    }

    /**
     * Regsiter column headings for post type
     * @param  array $defaults Default columns
     * @return array           Modified columns
     */
    public function register_custom_column_headings ( $defaults ) {
		$new_columns = array(
			'custom-field' => __( 'Custom Field' , 'wordpress-plugin-template' )
		);

		$last_item = '';

		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

		if ( count( $defaults ) > 2 ) {
			$last_item = array_slice( $defaults, -1 );

			array_pop( $defaults );
		}
		$defaults = array_merge( $defaults, $new_columns );

		if ( $last_item != '' ) {
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}
		}

		return $defaults;
	}

	/**
	 * Load data for post type columns
	 * @param  string  $column_name Name of column
	 * @param  integer $post_id     Post ID
	 * @return void
	 */
	public function register_custom_columns ( $column_name, $post_id ) {

		switch ( $column_name ) {

			case 'custom-field':
				$data = get_post_meta( $post_id, '_custom_field', true );
				echo $data;
			break;

			default:
			break;
		}

	}

	/**
	 * Set up admin messages for post type
	 * @param  array $messages Default message
	 * @return array           Modified messages
	 */
	public function updated_messages ( $messages ) {
	  global $post, $post_ID;

	  $messages[$this->post_type] = array(
	    0 => '', // Unused. Messages start at index 1.
	    1 => sprintf( __( 'Post updated. %sView post%s.' , 'wordpress-plugin-template' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    2 => __( 'Custom field updated.' , 'wordpress-plugin-template' ),
	    3 => __( 'Custom field deleted.' , 'wordpress-plugin-template' ),
	    4 => __( 'Post updated.' , 'wordpress-plugin-template' ),
	    /* translators: %s: date and time of the revision */
	    5 => isset($_GET['revision']) ? sprintf( __( 'Post restored to revision from %s.' , 'wordpress-plugin-template' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Post published. %sView post%s.' , 'wordpress-plugin-template' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    7 => __( 'Post saved.' , 'wordpress-plugin-template' ),
	    8 => sprintf( __( 'Post submitted. %sPreview post%s.' , 'wordpress-plugin-template' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	    9 => sprintf( __( 'Post scheduled for: %1$s. %2$sPreview post%3$s.' , 'wordpress-plugin-template' ), '<strong>' . date_i18n( __( 'M j, Y @ G:i' , 'wordpress-plugin-template' ), strtotime( $post->post_date ) ) . '</strong>', '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    10 => sprintf( __( 'Post draft updated. %sPreview post%s.' , 'wordpress-plugin-template' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	  );

	  return $messages;
	}

	/**
	 * Add meta box to post type
	 * @return void
	 */
	public function meta_box_setup () {
		add_meta_box( 'post-data', __( 'Post Details' , 'wordpress-plugin-template' ), array( $this, 'meta_box_content' ), $this->post_type, 'normal', 'high' );
	}

	/**
	 * Load meta box content
	 * @return void
	 */
	public function meta_box_content () {
		global $post_id;
		$fields = get_post_custom( $post_id );
		$field_data = $this->get_custom_fields_settings();

		$html = '';

		$html .= '<input type="hidden" name="' . $this->post_type . '_nonce" id="' . $this->post_type . '_nonce" value="' . wp_create_nonce( plugin_basename( $this->parent->dir ) ) . '" />';

		if ( 0 < count( $field_data ) ) {
			$html .= '<table class="form-table">' . "\n";
			$html .= '<tbody>' . "\n";

			foreach ( $field_data as $k => $v ) {
				$data = $v['default'];

				if ( isset( $fields[$k] ) && isset( $fields[$k][0] ) ) {
					$data = $fields[$k][0];
				}

				if( $v['type'] == 'checkbox' ) {
					$html .= '<tr valign="top"><th scope="row">' . $v['name'] . '</th><td><input name="' . esc_attr( $k ) . '" type="checkbox" id="' . esc_attr( $k ) . '" ' . checked( 'on' , $data , false ) . ' /> <label for="' . esc_attr( $k ) . '"><span class="description">' . $v['description'] . '</span></label>' . "\n";
					$html .= '</td></tr>' . "\n";
				} else {
					$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				}

			}

			$html .= '</tbody>' . "\n";
			$html .= '</table>' . "\n";
		}

		echo $html;
	}

	/**
	 * Save meta box
	 * @param  integer $post_id Post ID
	 * @return void
	 */
	public function meta_box_save ( $post_id ) {
		global $post, $messages;

		// Verify nonce
		if ( ( get_post_type() != $this->post_type ) || ! wp_verify_nonce( $_POST[ $this->post_type . '_nonce'], plugin_basename( $this->parent->dir ) ) ) {
			return $post_id;
		}

		// Verify user permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		// Handle custom fields
		$field_data = $this->get_custom_fields_settings();
		$fields = array_keys( $field_data );

		foreach ( $fields as $f ) {

			if( isset( $_POST[$f] ) ) {
				${$f} = strip_tags( trim( $_POST[$f] ) );
			}

			// Escape the URLs.
			if ( 'url' == $field_data[$f]['type'] ) {
				${$f} = esc_url( ${$f} );
			}

			if ( ${$f} == '' ) {
				delete_post_meta( $post_id , $f , get_post_meta( $post_id , $f , true ) );
			} else {
				update_post_meta( $post_id , $f , ${$f} );
			}
		}

	}

	/**
	 * Load custom title placeholder text
	 * @param  string $title Default title placeholder
	 * @return string        Modified title placeholder
	 */
	public function enter_title_here ( $title ) {
		if ( get_post_type() == $this->post_type ) {
			$title = __( 'Enter the post title here' , 'wordpress-plugin-template' );
		}
		return $title;
	}

	/**
	 * Load custom fields for post type
	 * @return array Custom fields array
	 */
	public function get_custom_fields_settings () {
		$fields = array();

		$fields['_custom_field'] = array(
		    'name' => __( 'Custom field:' , 'wordpress-plugin-template' ),
		    'description' => __( 'Description of this custom field.' , 'wordpress-plugin-template' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'plugin-data'
		);

		return $fields;
	}

	/**
	 * Main WordPress_Plugin_Template_Post_Type Instance
	 *
	 * Ensures only one instance of WordPress_Plugin_Template_Post_Type is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WordPress_Plugin_Template()
	 * @return Main WordPress_Plugin_Template_Post_Type instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}
