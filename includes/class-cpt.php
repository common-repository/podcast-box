<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Podcast_Box_CPT' ) ) {
	class Podcast_Box_CPT {
		private static $instance = null;

		/**
		 * Post_Types constructor.
		 */
		function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'init', array( $this, 'register_taxonomies' ) );
			add_action( 'init', array( $this, 'flush_rewrite_rules' ), 99 );
		}

		/**
		 * register custom post types
		 *
		 * @since 1.0.0
		 */
		public function register_post_types() {
			register_post_type( 'podcast', array(
				'labels'              => $this->get_posts_labels( 'Podcasts', 'Podcast', 'Podcasts' ),
				'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
				'supports'            => apply_filters( 'wp_podcasts/podcast_post_supports', array(
					'title',
					'editor',
					'thumbnail',
					'comments'
				) ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-microphone',
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => true,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array( 'slug' => apply_filters( 'wp_podcasts/podcast_slug', 'podcast' ) ),
				'capability_type'     => 'post',
			) );

			//Episode
			register_post_type( 'episode', array(
				'labels'              => $this->get_posts_labels( 'Episodes', 'Episode', 'Episodes' ),
				'hierarchical'        => false, //Hierarchical causes memory issues - WP Loads all records
				'supports'            => apply_filters( 'wp_podcasts/episode_post_supports', array(
					'title',
					'editor',
					'thumbnail',
					'comments'
				) ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => false,
				'show_in_nav_menus'   => false,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'has_archive'         => false,
				'query_var'           => true,
				'can_export'          => true,
				'rewrite'             => array( 'slug' => apply_filters( 'wp_podcasts/episode_slug', 'episode' ) ),
				'capability_type'     => 'post',
			) );
		}

		/**
		 * Register custom taxonomies
		 *
		 * @since 1.0.0
		 */
		public function register_taxonomies() {

			//Podcast category
			register_taxonomy( 'podcast_category', array( 'podcast' ), array(
				'hierarchical'      => true,
				'labels'            => $this->get_posts_labels( __( 'Categories', 'podcast-box' ), __( 'Category', 'podcast-box' ),
					__( 'Categories', 'podcast-box' ), 'singular' ),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => apply_filters( 'wp_podcasts/category_slug', [ 'slug' => 'podcast-category' ] ),
			) );

			//Podcast tag
//			register_taxonomy( 'podcast_tag', array( 'podcast', 'episode' ), array(
//				'hierarchical'      => false,
//				'labels'            => $this->get_posts_labels( __( 'Tags', 'wp-radio' ), __( 'Tag', 'wp-radio' ),
//					__( 'Tags', 'wp-radio' ) ),
//				'show_ui'           => true,
//				'show_admin_column' => true,
//				'rewrite'           => apply_filters( 'wp_podcasts/category_tag', [ 'slug' => 'podcast-tag' ] ),
//				'query_var'         => true,
//			) );

			//Podcast country
			register_taxonomy( 'podcast_country', array( 'podcast' ), array(
				'hierarchical'      => true,
				'labels'            => $this->get_posts_labels( __( 'Countries', 'podcast-box' ), __( 'Country', 'podcast-box' ), __( 'Countries', 'podcast-box' ) ),
				'show_ui'           => true,
				'show_admin_column' => true,
				'rewrite'           => apply_filters( 'wp_podcasts/podcast_country', [ 'slug' => 'podcast-country' ] ),
				'query_var'         => true,
			) );

		}

		/**
		 * Get all labels from post types
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_posts_labels( $menu_name, $singular, $plural, $type = 'plural' ) {
			$labels = array(
				'name'               => 'plural' == $type ? $plural : $singular,
				'all_items'          => sprintf( __( "All %s", 'podcast-box' ), $plural ),
				'singular_name'      => $singular,
				'add_new'            => sprintf( __( 'Add New %s', 'podcast-box' ), $singular ),
				'add_new_item'       => sprintf( __( 'Add New %s', 'podcast-box' ), $singular ),
				'edit_item'          => sprintf( __( 'Edit %s', 'podcast-box' ), $singular ),
				'new_item'           => sprintf( __( 'New %s', 'podcast-box' ), $singular ),
				'view_item'          => sprintf( __( 'View %s', 'podcast-box' ), $singular ),
				'search_items'       => sprintf( __( 'Search %s', 'podcast-box' ), $plural ),
				'not_found'          => sprintf( __( 'No %s found', 'podcast-box' ), $plural ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'podcast-box' ), $plural ),
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'podcast-box' ), $singular ),
				'menu_name'          => $menu_name,
			);

			return $labels;
		}


		/**
		 * Get all labels from taxonomies
		 *
		 * @param $menu_name
		 * @param $singular
		 * @param $plural
		 *
		 * @return array
		 * @since 1.0.0
		 */
		protected static function get_taxonomy_label( $menu_name, $singular, $plural ) {
			$labels = array(
				'name'              => sprintf( _x( '%s', 'taxonomy general name', 'podcast-box' ), $plural ),
				'singular_name'     => sprintf( _x( '%s', 'taxonomy singular name', 'podcast-box' ), $singular ),
				'search_items'      => sprintf( __( 'Search %s', 'podcast-box' ), $plural ),
				'all_items'         => sprintf( __( 'All %s', 'podcast-box' ), $plural ),
				'parent_item'       => sprintf( __( 'Parent %s', 'podcast-box' ), $singular ),
				'parent_item_colon' => sprintf( __( 'Parent %s:', 'podcast-box' ), $singular ),
				'edit_item'         => sprintf( __( 'Edit %s', 'podcast-box' ), $singular ),
				'update_item'       => sprintf( __( 'Update %s', 'podcast-box' ), $singular ),
				'add_new_item'      => sprintf( __( 'Add New %s', 'podcast-box' ), $singular ),
				'new_item_name'     => sprintf( __( 'New %s Name', 'podcast-box' ), $singular ),
				'menu_name'         => __( $menu_name, 'podcast-box' ),
			);

			return $labels;
		}

		/**
		 * Flash The Rewrite Rules
		 *
		 * @since 2.0.2
		 */
		public function flush_rewrite_rules() {
			if ( get_option( 'podcast_box_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				delete_option( 'podcast_box_flush_rewrite_rules' );
			}
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

Podcast_Box_CPT::instance();

