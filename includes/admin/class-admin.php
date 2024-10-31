<?php

if ( ! class_exists( 'Podcast_Box_Admin' ) ) {
	class Podcast_Box_Admin {
		/** @var null */
		private static $instance = null;

		/**
		 * WP_Podcasts_Admin constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', [ $this, 'admin_menu' ], 99 );

			// Episode post columns
			add_filter( 'manage_edit-episode_columns', [ $this, 'episode_post_columns' ] );
			add_filter( 'manage_episode_posts_custom_column', [ $this, 'episode_columns_data' ], 10, 2 );

			// Podcast post columns
			add_filter( 'manage_edit-podcast_columns', [ $this, 'podcast_post_columns' ] );
			add_filter( 'manage_podcast_posts_custom_column', [ $this, 'podcast_columns_data' ], 10, 2 );

			// Filter admin podcast listing
			add_action( 'pre_get_posts', [ $this, 'pre_get_posts' ] );

			//add country filtering
			add_action( 'restrict_manage_posts', [ $this, 'add_posts_filter_field' ] );

			add_filter( 'display_post_states', [ $this, 'listing_page_status' ], 10, 2 );

		}

		public function listing_page_status( $states, $post ) {

			if ( podcast_box_get_settings( 'listing_page' ) == $post->ID ) {

				$states[] = __( 'Podcasts listing', 'podcast-box' );

			}

			return $states;
		}

		/**
		 * Add post filter field
		 *
		 * @return void
		 */
		public function add_posts_filter_field() {
			$type = ! empty( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : '';

			if ( 'podcast' == $type ) { ?>
				<select name="country">
					<option value=""><?php _e( 'All Countries', 'podcast-box' ); ?></option>
					<?php
					$countries = get_terms( [ 'taxonomy' => 'podcast_country', 'parent' => 0 ] );

					if ( ! empty( $countries ) ) {

						foreach ( $countries as $country ) {
							printf( '<option value="%1$s" %2$s >%3$s</option>', $country->slug,
								selected( $country->slug, ! empty( $_GET['country'] ) ? $_GET['country'] : '' ), $country->name );
						}
					}
					?>
				</select>
				<?php
			}
		}

		public function pre_get_posts( $query ) {

			if ( ! is_admin() ) {
				return;
			}

			global $pagenow;
			$is_edit       = 'edit.php' == $pagenow;
			$post_type     = ! empty( $query->query_vars['post_type'] ) ? $query->query_vars['post_type'] : '';
			$is_main_query = $query->is_main_query();

			if ( $is_edit && $is_main_query ) {

				if ( 'podcast' == $post_type ) {
					if ( ! empty( $_GET['country'] ) ) {
						$query->query_vars['tax_query'] = [
							'relation' => 'AND',
							[
								'taxonomy' => 'podcast_country',
								'field'    => 'slug',
								'terms'    => sanitize_text_field( $_GET['country'] ),
							],
						];
					}
				}

				if ( 'episode' == $post_type ) {

					if ( ! empty( $_GET['podcast_id'] ) ) {

						$episode_ids = podcast_box_get_episode_ids( intval( $_GET['podcast_id'] ) );
						$episode_ids = empty( $episode_ids ) ? [ 0 ] : $episode_ids;

						$query->set( 'post__in', $episode_ids );
					}
				}

			}

		}

		public function podcast_post_columns( $columns ) {
			return array(
				'cb'                        => '<input type="checkbox" />',
				'id'                        => __( 'ID' ),
				'title'                     => __( 'Title' ),
				'taxonomy-podcast_category' => __( 'Category' ),
				'taxonomy-podcast_country' => __( 'Category' ),
				'episode_count'                   => __( 'Episodes count' ),
				'date'                      => __( 'Date' ),
			);
		}

		public function episode_post_columns( $columns ) {
			return array(
				'cb'      => '<input type="checkbox" />',
				'id'      => __( 'ID' ),
				'title'   => __( 'Title' ),
				'podcast' => __( 'Podcast' ),
				'date'    => __( 'Date' ),
			);
		}

		public function podcast_columns_data( $column, $post_id ) {

			if ( 'episode_count' == $column ) {
				$count = count( podcast_box_get_episode_ids( $post_id ) );
				printf( '<a href="%s">%s %s</a>', add_query_arg( 'podcast_id', $post_id, admin_url( 'edit.php?post_type=episode' ) ),
					$count, _n( 'Episode', 'Episodes', $count ) );
			}

			if ('podcast' == get_post_type($post_id) && $column == 'id' ) {
				echo $post_id;
			}

		}

		public function episode_columns_data( $column, $post_id ) {

			if ( 'podcast' == $column ) {
				$podcast = podcast_box_get_episode_podcast( $post_id );

				if ( $podcast ) {
					printf( '<a target="_blank" href="%s">%s</a>', get_the_permalink( $podcast ), get_the_title( $podcast ) );
				}
			}

			if ('episode' == get_post_type($post_id) && $column == 'id' ) {
				echo $post_id;
			}
		}

		public function admin_menu() {
			//All episodes menu
			add_submenu_page( 'edit.php?post_type=podcast', 'Episodes', 'All Episodes', 'manage_options', 'edit.php?post_type=episode', '',
				2 );

			//Add new episode
			add_submenu_page( 'edit.php?post_type=podcast', 'Add New Episode', 'Add New Episode', 'manage_options',
				'post-new.php?post_type=episode', '', 3 );

			//Import Podcasts
			add_submenu_page( 'edit.php?post_type=podcast', __( 'Import Podcasts', 'podcast-box' ), __( 'Import Podcasts', 'podcast-box' ),
				'manage_options', 'import-podcasts', [ $this, 'import_menu_page', ], 6 );


			add_submenu_page( 'edit.php?post_type=podcast', __( 'Get Started', 'podcast-box' ), __( 'Get Started', 'podcast-box' ),
				'manage_options', 'podcast-box-get-started', [
					$this,
					'get_started_page',
				] );

		}

		public function get_started_page() {
			include PODCAST_BOX_INCLUDES . '/admin/views/get-started/index.php';
		}

		/**
		 * Import Page
		 */
		public function import_menu_page() {
			include PODCAST_BOX_INCLUDES . '/admin/views/import-podcasts.php';
		}


		/**
		 * @return Podcast_Box_Admin|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

Podcast_Box_Admin::instance();