<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Podcast_Box_Enqueue' ) ) {
	class Podcast_Box_Enqueue {

		private static $instance = null;

		public function __construct() {
			add_action( 'wp_enqueue_scripts', [ $this, 'frontend_scripts' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue' ] );
		}

		/**
		 * Frontend Scripts
		 *
		 * @param $hook
		 */
		public function frontend_scripts( $hook ) {

			/* enqueue frontend styles */
			wp_enqueue_style( 'select2', PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.css', false, '4.0.11' );
			wp_enqueue_style( 'podcast-box', PODCAST_BOX_ASSETS . '/css/frontend.css', [ 'dashicons' ], PODCAST_BOX_VERSION );

			/* enqueue frontend script */
			wp_enqueue_script( 'jquery.hideseek', PODCAST_BOX_ASSETS . '/vendor/jquery.hideseek.min.js', [ 'jquery' ], PODCAST_BOX_VERSION, true );
			wp_enqueue_script( 'select2', PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.js', [ 'jquery' ], '4.0.11', true );
			wp_enqueue_script( 'lazy-js', PODCAST_BOX_ASSETS . '/vendor/jquery.lazy.min.js', [ 'jquery' ], '1.7.10', true );
			wp_enqueue_script( 'podcast-box', PODCAST_BOX_ASSETS . '/js/frontend.min.js', [
				'jquery',
				'jquery-migrate',
				'wp-util',
				'wp-mediaelement',
			], PODCAST_BOX_VERSION, true );


			/* localized script attached to 'wp-radio' */
			wp_localize_script( 'podcast-box', 'podcastBox',  podcast_box_localize_array());


			$listing_bg_color     = podcast_box_get_settings( 'listing_bg_color', '', 'podcast_box_color_settings' );
			$listing_btn_color    = podcast_box_get_settings( 'listing_btn_color', '', 'podcast_box_color_settings' );
			$listing_selector     = '.podcast-box-listing';
			$listing_btn_selector = '.podcast-box-listing-wrapper .podcast-box-listing .listing-episode-footer > a';

			$player_bg_color     = podcast_box_get_settings( 'player_bg_color', '#212838', 'podcast_box_color_settings' );
			$player_btn_color    = podcast_box_get_settings( 'player_btn_color', '#fff', 'podcast_box_color_settings' );
			$player_selector     = '.podcast-box-player.full-width';
			$player_btn_selector = '.podcast-box-player.full-width .dashicons';


			$listing_thumb_size     = podcast_box_get_settings( 'listing_thumbnail_size', 'auto', 'podcast_box_image_settings' );
			$listing_thumb_width    = podcast_box_get_settings( 'listing_thumbnail_width', '100', 'podcast_box_image_settings' );
			$listing_thumb_height   = podcast_box_get_settings( 'listing_thumbnail_height', '', 'podcast_box_image_settings' );
			$listing_thumb_selector = '.podcast-box-listing-wrapper .podcast-box-listing .listing-thumbnail img,
			 .podcast-box-single .podcast-box-listings .podcast-episode .episode-header .episode-thumbnail';

			$player_thumb_size     = podcast_box_get_settings( 'player_thumbnail_size', 'auto', 'podcast_box_image_settings' );
			$player_thumb_width    = podcast_box_get_settings( 'player_thumbnail_width', '120', 'podcast_box_image_settings' );
			$player_thumb_height   = podcast_box_get_settings( 'player_thumbnail_height', '', 'podcast_box_image_settings' );
			$player_thumb_selector = '.podcast-box-player.shortcode .podcast-box-player-thumbnail';

			ob_start();

			if ( ! empty( $listing_bg_color ) ) {
				printf( '%s {background-color:%s !important;}', $listing_selector, $listing_bg_color );
			}

			if ( ! empty( $listing_btn_color ) ) {
				printf( '%s {background-color:%s !important;}', $listing_btn_selector, $listing_btn_color );
			}

			if ( ! empty( $player_bg_color ) ) {
				printf( '%s {background-color:%s !important;}', $player_selector, $player_bg_color );
			}

			if ( ! empty( $player_btn_color ) ) {
				printf( '%s {color:%s !important;}', $player_btn_selector, $player_btn_color );
			}

			if ( 'custom' == $listing_thumb_size ) {
				printf( '%s {width: %spx; height: %spx}', $listing_thumb_selector, $listing_thumb_width, $listing_thumb_height );
			}

			if ( 'custom' == $player_thumb_size ) {
				printf( '%s {width: %spx; height: %spx}', $player_thumb_selector, $player_thumb_width, $player_thumb_height );
			}
			$custom_css = ob_get_clean();

			wp_add_inline_style( 'podcast-box', $custom_css );
		}

		/**
		 * Admin Scripts
		 *
		 * @param $hook
		 */
		public function admin_enqueue( $hook ) {

			wp_enqueue_style( 'select2', PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.css', false, '4.0.11' );
			wp_enqueue_style( 'jquery.multi-select', PODCAST_BOX_ASSETS . '/vendor/lou-multi-select/css/multi-select.dist.css', false,
				'0.9.12' );
			wp_enqueue_style( 'podcast-box-admin', PODCAST_BOX_ASSETS . '/css/admin.css', false, PODCAST_BOX_VERSION );

			wp_enqueue_script( 'select2', PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.js', [ 'jquery' ], '4.0.11', true );
			wp_enqueue_script( 'jquery.multi-select', PODCAST_BOX_ASSETS . '/vendor/lou-multi-select/js/jquery.multi-select.js',
				[ 'jquery' ], '0.9.12', true );
			wp_enqueue_script( 'jquery.hideseek', PODCAST_BOX_ASSETS . '/vendor/jquery.hideseek.min.js', [ 'jquery' ], PODCAST_BOX_VERSION,
				true );
			wp_enqueue_script( 'jquery.syotimer', PODCAST_BOX_ASSETS . '/vendor/jquery.syotimer.min.js', [ 'jquery' ], '', true );
			wp_enqueue_script( 'wp-color-picker-alpha', PODCAST_BOX_ASSETS . '/vendor/wp-color-picker-alpha.js',
				[ 'jquery', 'wp-color-picker' ], '', true );

			wp_enqueue_script( 'podcast-box-admin', PODCAST_BOX_ASSETS . '/js/admin.min.js', [
				'jquery',
				'jquery-ui-slider',
				'jquery-ui-datepicker',
				'wp-util',
			], PODCAST_BOX_VERSION, true );

			$localize_array = array(
				'isPro'          => pb_fs()->can_use_premium_code__premium_only(),
				'pricingPage'        => '',
				'pluginUrl'          => PODCAST_BOX_URL,
				'adminUrl'           => admin_url(),
				'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'podcast-box' ),
				'volume'             => podcast_box_get_settings( 'player_volume', 70, 'wp_radio_player_settings' ),
				'imported_countries' => get_option( 'podcast_box_imported_countries' ),
				'update_countries'   => (array) get_option( 'wp_radio_update_countries', [] ),
				'i18n'               => array(
					'alert_no_country'   => __( 'You need to select countries, before run the import', 'podcast-box' ),
					'running'            => __( 'Please wait, Import is running...', 'podcast-box' ),
					'no_country_found'   => __( 'No country found.', 'podcast-box' ),
					'update'             => __( 'Update', 'podcast-box' ),
					'updating'           => __( 'Updating', 'podcast-box' ),
					'imported'           => __( 'Imported: ', 'podcast-box' ),
					'count_title'        => __( 'Total station of the country', 'podcast-box' ),
					'premium'            => __( 'Premium', 'podcast-box' ),
					'select_add_country' => __( 'Search country to import', 'podcast-box' ),
					'get_premium'        => __( 'Upgrade to PRO', 'podcast-box' ),
					'premium_promo'      => __( 'to access total 5000+ podcasts from 68+ countries.', 'podcast-box' ),
					'selected_countries' => __( 'Selected Countries', 'podcast-box' ),
					'selected'           => __( 'Selected:', 'podcast-box' ),
					'remaining'          => __( 'Remaining:', 'podcast-box' ),
					'total_station'      => __( 'Total Station:', 'podcast-box' ),
					'import_more'        => __( 'Import More', 'podcast-box' ),
					'run_import'         => __( 'Run Importer', 'podcast-box' ),
					'hide_player'        => __( 'Hide Radio Player', 'podcast-box' ),
					'chartTitle'         => __( 'Play Count - How many times stations are played in a day.', 'podcast-box' ),
				),
			);

			wp_localize_script( 'podcast-box-admin', 'podcastBox', $localize_array );

		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

Podcast_Box_Enqueue::instance();




