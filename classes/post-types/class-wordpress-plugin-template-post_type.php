<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WordPress_Plugin_Template_Post_Type {
	private $dir;
	private $file;
	private $assets_dir;
	private $assets_url;
	private $token;

	public function __construct( $file ) {
		$this->dir = dirname( $file );
		$this->file = $file;
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $file ) ) );
		$this->token = 'post_type';

		// Regsiter post type
		add_action( 'init' , array( $this, 'register_post_type' ) );

		// Register taxonomy
		add_action('init', array( $this, 'register_taxonomy' ) );

		if ( is_admin() ) {

			// Handle custom fields for post
			add_action( 'admin_menu', array( $this, 'meta_box_setup' ), 20 );
			add_action( 'save_post', array( $this, 'meta_box_save' ) );

			// Modify text in main title text box
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );

			// Display custom update messages for posts edits
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

			// Handle post columns
			add_filter( 'manage_edit-' . $this->token . '_columns', array( $this, 'register_custom_column_headings' ), 10, 1 );
			add_action( 'manage_pages_custom_column', array( $this, 'register_custom_columns' ), 10, 2 );

		}

	}

	/**
	 * Register new post type
	 * @return void
	 */
	public function register_post_type() {

		$labels = array(
			'name' => _x( 'Post Type', 'post type general name' , 'plugin_textdomain' ),
			'singular_name' => _x( 'Post Type', 'post type singular name' , 'plugin_textdomain' ),
			'add_new' => _x( 'Add New', $this->token , 'plugin_textdomain' ),
			'add_new_item' => sprintf( __( 'Add New %s' , 'plugin_textdomain' ), __( 'Post' , 'plugin_textdomain' ) ),
			'edit_item' => sprintf( __( 'Edit %s' , 'plugin_textdomain' ), __( 'Post' , 'plugin_textdomain' ) ),
			'new_item' => sprintf( __( 'New %s' , 'plugin_textdomain' ), __( 'Post' , 'plugin_textdomain' ) ),
			'all_items' => sprintf( __( 'All %s' , 'plugin_textdomain' ), __( 'Posts' , 'plugin_textdomain' ) ),
			'view_item' => sprintf( __( 'View %s' , 'plugin_textdomain' ), __( 'Post' , 'plugin_textdomain' ) ),
			'search_items' => sprintf( __( 'Search %a' , 'plugin_textdomain' ), __( 'Posts' , 'plugin_textdomain' ) ),
			'not_found' =>  sprintf( __( 'No %s Found' , 'plugin_textdomain' ), __( 'Posts' , 'plugin_textdomain' ) ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash' , 'plugin_textdomain' ), __( 'Posts' , 'plugin_textdomain' ) ),
			'parent_item_colon' => '',
			'menu_name' => __( '*Posts' , 'plugin_textdomain' )
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'query_var' => false,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => true,
			'supports' => array( 'title' , 'editor' , 'excerpt' , 'comments' ),
			'menu_position' => 5,
			'menu_icon' => ''
		);

		register_post_type( $this->token, $args );
	}

	/**
	 * Register new taxonomy
	 * @return void
	 */
	public function register_taxonomy() {

        $labels = array(
            'name' => __( 'Terms' , 'plugin_textdomain' ),
            'singular_name' => __( 'Term', 'plugin_textdomain' ),
            'search_items' =>  __( 'Search Terms' , 'plugin_textdomain' ),
            'all_items' => __( 'All Terms' , 'plugin_textdomain' ),
            'parent_item' => __( 'Parent Term' , 'plugin_textdomain' ),
            'parent_item_colon' => __( 'Parent Term:' , 'plugin_textdomain' ),
            'edit_item' => __( 'Edit Term' , 'plugin_textdomain' ),
            'update_item' => __( 'Update Term' , 'plugin_textdomain' ),
            'add_new_item' => __( 'Add New Term' , 'plugin_textdomain' ),
            'new_item_name' => __( 'New Term Name' , 'plugin_textdomain' ),
            'menu_name' => __( 'Terms' , 'plugin_textdomain' ),
        );

        $args = array(
            'public' => true,
            'hierarchical' => true,
            'rewrite' => true,
            'labels' => $labels
        );

        register_taxonomy( 'post_type_terms' , $this->token , $args );
    }

    /**
     * Regsiter column headings for post type
     * @param  array $defaults Default columns
     * @return array           Modified columns
     */
    public function register_custom_column_headings( $defaults ) {
		$new_columns = array(
			'custom-field' => __( 'Custom Field' , 'plugin_textdomain' )
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
	 * @param  integer $id          Post ID
	 * @return void
	 */
	public function register_custom_columns( $column_name, $id ) {

		switch ( $column_name ) {

			case 'custom-field':
				$data = get_post_meta( $id, '_custom_field', true );
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
	public function updated_messages( $messages ) {
	  global $post, $post_ID;

	  $messages[$this->token] = array(
	    0 => '', // Unused. Messages start at index 1.
	    1 => sprintf( __( 'Post updated. %sView post%s.' , 'plugin_textdomain' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    2 => __( 'Custom field updated.' , 'plugin_textdomain' ),
	    3 => __( 'Custom field deleted.' , 'plugin_textdomain' ),
	    4 => __( 'Post updated.' , 'plugin_textdomain' ),
	    /* translators: %s: date and time of the revision */
	    5 => isset($_GET['revision']) ? sprintf( __( 'Post restored to revision from %s.' , 'plugin_textdomain' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Post published. %sView post%s.' , 'plugin_textdomain' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    7 => __( 'Post saved.' , 'plugin_textdomain' ),
	    8 => sprintf( __( 'Post submitted. %sPreview post%s.' , 'plugin_textdomain' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	    9 => sprintf( __( 'Post scheduled for: %1$s. %2$sPreview post%3$s.' , 'plugin_textdomain' ), '<strong>' . date_i18n( __( 'M j, Y @ G:i' , 'plugin_textdomain' ), strtotime( $post->post_date ) ) . '</strong>', '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    10 => sprintf( __( 'Post draft updated. %sPreview post%s.' , 'plugin_textdomain' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	  );

	  return $messages;
	}

	/**
	 * Add meta box to post type
	 * @return void
	 */
	public function meta_box_setup() {
		add_meta_box( 'post-data', __( 'Post Details' , 'plugin_textdomain' ), array( $this, 'meta_box_content' ), $this->token, 'normal', 'high' );
	}

	/**
	 * Load meta box content
	 * @return void
	 */
	public function meta_box_content() {
		global $post_id;
		$fields = get_post_custom( $post_id );
		$field_data = $this->get_custom_fields_settings();

		$html = '';

		$html .= '<input type="hidden" name="' . $this->token . '_nonce" id="' . $this->token . '_nonce" value="' . wp_create_nonce( plugin_basename( $this->dir ) ) . '" />';

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
					$html .= '</td><tr/>' . "\n";
				} else {
					$html .= '<tr valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" class="regular-text" value="' . esc_attr( $data ) . '" />' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td><tr/>' . "\n";
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
	public function meta_box_save( $post_id ) {
		global $post, $messages;

		// Verify nonce
		if ( ( get_post_type() != $this->token ) || ! wp_verify_nonce( $_POST[ $this->token . '_nonce'], plugin_basename( $this->dir ) ) ) {
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
	public function enter_title_here( $title ) {
		if ( get_post_type() == $this->token ) {
			$title = __( 'Enter the post title here' , 'plugin_textdomain' );
		}
		return $title;
	}

	/**
	 * Load custom fields for post type
	 * @return array Custom fields array
	 */
	public function get_custom_fields_settings() {
		$fields = array();

		$fields['_custom_field'] = array(
		    'name' => __( 'Custom field:' , 'plugin_textdomain' ),
		    'description' => __( 'Description of this custom field.' , 'plugin_textdomain' ),
		    'type' => 'text',
		    'default' => '',
		    'section' => 'plugin-data'
		);

		return $fields;
	}

}