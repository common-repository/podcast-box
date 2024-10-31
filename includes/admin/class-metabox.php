<?php

/** Block direct access */
defined( 'ABSPATH' ) || exit();

/** check if class `Podcast_Box_Metabox` not exists yet */
if ( ! class_exists( 'Podcast_Box_Metabox' ) ) {
	/**
	 * Class Podcast_Box_Metabox
	 *
	 * Handle metaboxes
	 *
	 * @package Prince\WP_Radio
	 *
	 * @since 1.0.0
	 */
	class Podcast_Box_Metabox {

		/**
		 * @var null
		 */
		private static $instance = null;

		/**
		 * Podcast_Box_Metabox constructor.
		 * Initialize the custom Meta Boxes for prince-options api.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
			add_action( 'do_meta_boxes', [ $this, 'remove_meta_box' ] );

			add_action( 'save_post_podcast', [ $this, 'save_podcast_meta' ] );
			add_action( 'save_post_episode', [ $this, 'save_episode_meta' ] );
		}

		public function save_podcast_meta( $post_id ) {

			$values = [
				'import_interval' => ! empty( $_POST['import_interval'] ) ? sanitize_key( $_POST['import_interval'] ) : '',
				'feed_url'        => ! empty( $_POST['feed_url'] ) ? esc_url( $_POST['feed_url'] ) : '',
				'logo'            => ! empty( $_POST['logo'] ) ? esc_url( $_POST['logo'] ) : '',
				'publisher_name'  => ! empty( $_POST['publisher_name'] ) ? sanitize_text_field( $_POST['publisher_name'] ) : '',
				'publisher_email' => ! empty( $_POST['publisher_email'] ) ? sanitize_email( $_POST['publisher_email'] ) : '',
				'website'         => ! empty( $_POST['website'] ) ? esc_url( $_POST['website'] ) : '',
				'itunes_url'      => ! empty( $_POST['itunes_url'] ) ? esc_url( $_POST['itunes_url'] ) : '',
				'type'            => ! empty( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : '',
				'language'        => ! empty( $_POST['language'] ) ? sanitize_text_field( $_POST['language'] ) : '',
			];

			foreach ( $values as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

		}

		public function save_episode_meta( $post_id ) {
			$values = [
				'podcast'  => ! empty( $_POST['podcast'] ) ? intval( $_POST['podcast'] ) : '',
				'link'     => ! empty( $_POST['link'] ) ? esc_url( $_POST['link'] ) : '',
				'size'     => ! empty( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : '',
				'file'     => ! empty( $_POST['file'] ) ? esc_url( $_POST['file'] ) : '',
				'duration' => ! empty( $_POST['duration'] ) ? sanitize_text_field( $_POST['duration'] ) : '',
				'logo'     => ! empty( $_POST['logo'] ) ? esc_url( $_POST['logo'] ) : '',
				'date'     => ! empty( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '',

				'episode_number' => ! empty( $_POST['episode_number'] ) ? sanitize_text_field( $_POST['episode_number'] ) : '',
				'season_number'  => ! empty( $_POST['season_number'] ) ? sanitize_text_field( $_POST['season_number'] ) : '',
			];

			foreach ( $values as $key => $value ) {
				update_post_meta( $post_id, $key, $value );
			}

			$podcast = ! empty( $_POST['podcast'] ) ? intval( $_POST['podcast'] ) : '';
			podcast_box_insert_podcast_episode_relation( $podcast, $post_id );


		}

		public function remove_meta_box() {
			remove_meta_box( 'postimagediv', ['podcast', 'episode'], 'side' );
			remove_meta_box( 'submitdiv', ['podcast', 'episode'], 'side' );
		}

		/**
		 * register metaboxes
		 */
		public function register_meta_boxes() {

			// Podcast Actions Metabox
			add_meta_box( 'podcast_box_actions', __( 'Actions', 'podcast-box' ), [ $this, 'render_actions_metabox' ],[ 'podcast' ], 'side', 'high' );

			// Episode Actions Metabox
			add_meta_box( 'podcast_box_episode_actions', __( 'Actions', 'podcast-box' ), [ $this, 'render_episode_actions_metabox' ], [ 'episode' ], 'side', 'high' );

			// Podcast Information Metabox
			add_meta_box( 'podcast_box_metabox', __( 'Podcast Information', 'podcast-box' ), [ $this, 'render_metabox' ], [ 'podcast' ],'normal', 'high' );

			// Episode Information Metabox
			add_meta_box( 'podcast_box_episode_metabox', __( 'Episode Information', 'podcast-box' ), [ $this, 'render_episode_metabox' ], [ 'episode' ], 'side', 'high' );

			// Latest episodes metabox
			add_meta_box( 'latest_episodes_metabox', __( 'Latest Episodes', 'podcast-box' ), [ $this, 'render_latest_episode_metabox' ],[ 'podcast' ], 'side', 'high' );
		}

		public function render_actions_metabox($post){
			include_once PODCAST_BOX_INCLUDES . '/admin/views/metabox-actions.php';
		}

		public function render_episode_actions_metabox( $post ) {
			include_once PODCAST_BOX_INCLUDES . '/admin/views/metabox-episode-actions.php';
		}

		public function render_latest_episode_metabox( $post ) {
			include_once PODCAST_BOX_INCLUDES . '/admin/views/metabox-latest-episodes.php';
		}

		/**
		 * render station info metabox content
		 *
		 * @since 1.0.0
		 */
		public function render_metabox() {
			include PODCAST_BOX_INCLUDES . '/admin/views/metabox.php';
		}

		public function render_episode_metabox() {
			include PODCAST_BOX_INCLUDES . '/admin/views/metabox-episode.php';
		}

		/**
		 * @return Podcast_Box_Metabox|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

Podcast_Box_Metabox::instance();
