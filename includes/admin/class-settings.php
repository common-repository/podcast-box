<?php

defined( 'ABSPATH' ) || exit();

/** if class `Podcast_Box_Settings` not exists yet */
if ( ! class_exists( 'Podcast_Box_Settings' ) ) {

	class Podcast_Box_Settings {

		private static $instance = null;
		private static $settings_api = null;

		public function __construct() {
			add_action( 'admin_init', array( $this, 'settings_fields' ) );
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		}

		/**
		 * Registers settings section and fields
		 */
		public function settings_fields() {
			$is_pro = true;


			$sections = array(
				array(
					'id'    => 'podcast_box_general_settings',
					'title' => sprintf( __( '%s General Settings', 'podcast-box' ), '<i class="dashicons dashicons-admin-generic"></i>' ),
				),
				array(
					'id'    => 'podcast_box_display_settings',
					'title' => sprintf( __( '%s Display Settings', 'podcast-box' ), '<i class="dashicons dashicons-welcome-view-site"></i>' ),
				),
				array(
					'id'    => 'podcast_box_image_settings',
					'title' => sprintf( __( '%s Image Settings', 'podcast-box' ), '<i class="dashicons dashicons-format-image"></i>' ),
				),
				array(
					'id'    => 'podcast_box_player_settings',
					'title' => sprintf( __( '%s Player Settings', 'podcast-box' ), '<i class="dashicons dashicons-controls-play"></i>' ),
				),
				array(
					'id'    => 'podcast_box_color_settings',
					'title' => sprintf( __( '%s Color Settings', 'podcast-box' ), '<i class="dashicons dashicons-admin-appearance"></i>' ),
				),
			);

			$fields = apply_filters( 'podcast_box/settings_fields', array(
				'podcast_box_general_settings' => [
					[
						'name'  => 'listing_page',
						'label' => __( 'Podcast Listing Page :', 'podcast-box' ),
						'desc'  => __( 'Select the page which contains the [podcast_box_listing] shortcode for the podcast listing.',
							'podcast-box' ),
						'type'  => 'pages',
					],

					[
						'name'    => 'cron',
						'label'   => __( 'Continues Import  :', 'podcast-box' ),
						'desc'    => __( 'If ON, the podcasts episodes will be imported continuously.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'off',
					],

					[
						'name'    => 'delete_data',
						'label'   => __( 'Delete Data on Plugin Uninstalling :', 'podcast-box' ),
						'desc'    => __( 'Turn on to delete all the data (podcasts, episodes, settings) on uninstalling of this plugin.',
							'podcast-box' ),
						'type'    => 'switch',
						'default' => 'off',
					],
				],

				'podcast_box_display_settings' => [

					[
						'name'    => 'settings_heading_podcast_listing',
						'type'    => 'heading',
						'default' => __( 'Podcast Listing', 'podcast-box' ),
					],

					[
						'name'    => 'posts_per_page',
						'label'   => __( 'Podcasts Per Page :', 'podcast-box' ),
						'desc'    => __( 'Enter the number of how many podcasts will be displayed in the listing page.', 'podcast-box' ),
						'default' => 10,
						'type'    => 'select',
						'options' => [
							'5'  => __( '5', 'podcast-box' ),
							'10' => __( '10', 'podcast-box' ),
							'15' => __( '15', 'podcast-box' ),
							'20' => __( '20', 'podcast-box' ),
							'30' => __( '30', 'podcast-box' ),
							'40' => __( '40', 'podcast-box' ),
							'50' => __( '50', 'podcast-box' ),
						],
					],

					[
						'name'    => 'listing_view',
						'label'   => __( 'Podcast Listing View :', 'podcast-box' ),
						'desc'    => __( 'Choose the podcast listing view. Left (List View), Right (Grid View)', 'podcast-box' ),
						'type'    => 'image_choose',
						'default' => 'list',
						'options' => [
							'list' => PODCAST_BOX_ASSETS . '/images/list.png',
							'grid' => PODCAST_BOX_ASSETS . '/images/grid.png',
						],
					],

					[
						'name'    => 'grid_column',
						'label'   => __( 'Grid Column :', 'podcast-box' ),
						'desc'    => __( 'Select the column number in the grid view. (Min:2 - Max: 6)', 'podcast-box' ),
						'type'    => 'select',
						'default' => 4,
						'options' => [
							2 => __( '2', 'podcast-box' ),
							3 => __( '3', 'podcast-box' ),
							4 => __( '4', 'podcast-box' ),
							5 => __( '5', 'podcast-box' ),
							6 => __( '6', 'podcast-box' ),
						],
					],

					[
						'name'    => 'listing_content',
						'label'   => __( 'Podcast Description :', 'podcast-box' ),
						'desc'    => __( 'Show / hide the short podcast description in the podcast listing.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'latest_episode',
						'label'   => __( 'Latest Episode :', 'podcast-box' ),
						'desc'    => __( 'Show / hide the latest episode in the podcast listing.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'ip_listing',
						'label'   => __( 'IP Based Listing :', 'podcast-box' ),
						'desc'    => __( 'If ON, visitors will see only their country’s podcasts on the listing page on the first visit <br> Else they will see all the podcasts.',
							'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'show_search',
						'label'   => __( 'Show Podcast Search Bar :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide the podcast search bar in the listing  page.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'search_style',
						'label'   => __( 'Search Form Style :', 'podcast-box' ),
						'desc'    => __( 'Choose the podcast search form style', 'podcast-box' ),
						'type'    => 'image_choose',
						'default' => 1,
						'options' => [
							1 => PODCAST_BOX_ASSETS . '/images/search-1.png',
							2 => PODCAST_BOX_ASSETS . '/images/search-2.png',
						],
					],

					[
						'name'    => 'settings_heading_episode_listing',
						'type'    => 'heading',
						'default' => __( 'Episode Listing', 'podcast-box' ),
					],

					[
						'name'    => 'episodes_per_page',
						'label'   => __( 'Episodes Per Page :', 'podcast-box' ),
						'desc'    => __( 'Enter the number of how many episodes will be displayed in the single podcast page.',
							'podcast-box' ),
						'default' => 10,
						'type'    => 'select',
						'options' => [
							'5'  => __( '5', 'podcast-box' ),
							'10' => __( '10', 'podcast-box' ),
							'15' => __( '15', 'podcast-box' ),
							'20' => __( '20', 'podcast-box' ),
							'30' => __( '30', 'podcast-box' ),
							'40' => __( '40', 'podcast-box' ),
							'50' => __( '50', 'podcast-box' ),
						],
					],

					[
						'name'    => 'episode_download',
						'label'   => __( 'Download Button :', 'podcast-box' ),
						'desc'    => __( 'Show / hide the episode download button in the podcast/ episode listing.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'episode_description',
						'label'   => __( 'Show Episode Description :', 'podcast-box' ),
						'desc'    => __( 'Show / hide the short episode description in the episode listing.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'show_search_episode',
						'label'   => __( 'Episode Search Bar :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide the episodes search bar in the single podcast  page.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'settings_heading_single_page',
						'type'    => 'heading',
						'default' => __( 'Single Podcast/ Episode Page', 'podcast-box' ),
					],

					[
						'name'    => 'single_next_prev',
						'label'   => __( 'Next/ Previous  :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide the next-previous (podcast/ episode) pagination at bottom of the single (podcast/ episode) page.',
							'podcast-box' ),
						'type'    => 'switch',
						'default' => 'off',
					],
					[
						'name'    => 'you_may_like',
						'label'   => __( 'Related (Podcasts/ Episodes) :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide the related (podcasts/ episodes) section in the single (podcast/ episode) page.',
							'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],
				],

				'podcast_box_image_settings' => [
					[
						'name'    => 'listing_thumbnail_size',
						'label'   => __( 'Listing Thumbnail Size :', 'podcast-box' ),
						'desc'    => __( 'Customize the podcast/ episode listing  image size.', 'podcast-box' ),
						'type'    => 'radio',
						'default' => 'default',
						'options' => [
							'default' => __( 'Default', 'podcast-box' ),
							'custom'  => __( 'Custom', 'podcast-box' ),
						],
					],
					[
						'name'    => 'listing_thumbnail_width',
						'label'   => __( 'Listing Thumbnail Width :', 'podcast-box' ),
						'desc'    => __( 'Set the thumbnail width in the podcast/ episode listing (px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 100,
						'min'     => 50,
						'max'     => 500,
						'step'    => 5,
					],
					[
						'name'    => 'listing_thumbnail_height',
						'label'   => __( 'Listing Thumbnail Height :', 'podcast-box' ),
						'desc'    => __( 'Set the thumbnail height in the podcast/ episode listing (px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 100,
						'min'     => 30,
						'max'     => 500,
						'step'    => 5,
					],
					[
						'name'    => 'player_thumbnail_size',
						'label'   => __( 'Player Thumbnail Size :', 'podcast-box' ),
						'desc'    => __( 'Customize the podcast/ episode player image size in the (shortcode/ popup) player.', 'podcast-box' ),
						'type'    => 'radio',
						'default' => 'auto',
						'options' => [
							'auto'   => __( 'Default', 'podcast-box' ),
							'custom' => __( 'Custom', 'podcast-box' ),
						],
					],
					[
						'name'    => 'player_thumbnail_width',
						'label'   => __( 'Player Thumbnail Width :', 'podcast-box' ),
						'desc'    => __( 'Set the thumbnail width in the podcast episode player (px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 120,
						'min'     => 50,
						'max'     => 400,
						'step'    => 5,
					],
					[
						'name'    => 'player_thumbnail_height',
						'label'   => __( 'Player Thumbnail Height :', 'podcast-box' ),
						'desc'    => __( 'Set the thumbnail height in the podcast episode player (px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 100,
						'min'     => 30,
						'max'     => 400,
						'step'    => 5,
					],
				],

				'podcast_box_player_settings'  => [
					[
						'name'    => 'player_type',
						'label'   => __( 'Player Type :', 'podcast-box' ),
						'desc'    => sprintf( '
					Play button behaviour for podcast episodes.
					<hr>
					<p><b>Default</b> - Episodes will be played on the footer full-width player.</p>
					<hr>
					<p><b>Popup</b> - All the episodes will be played on a new popup window.</p>%1$s', $is_pro ? '' : '<p>Popup is only available in the premium version.</p>' ),
						'type'    => 'select',
						'options' => [
							'in_page' => __( 'Default', 'podcast-box' ),
							'popup'   => sprintf( __( 'Popup %s', 'podcast-box' ), $is_pro ? '' : '(Premium)' ),
						]
					],

					[
						'name'    => 'customize_popup_player',
						'label'   => __( 'Customize Popup Size? :', 'podcast-box' ),
						'desc'    => __( 'Want to customize the popup player width/ height.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'off',
					],
					[
						'name'    => 'popup_player_width',
						'label'   => __( 'Popup Player Width :', 'podcast-box' ),
						'desc'    => __( 'Set the popup player width(px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 420,
						'min'     => 100,
						'max'     => 1200,
						'step'    => 5,
					],
					[
						'name'    => 'popup_player_height',
						'label'   => __( 'Popup Player Height :', 'podcast-box' ),
						'desc'    => __( 'Set the popup player height (px).', 'podcast-box' ),
						'type'    => 'slider',
						'default' => 380,
						'min'     => 100,
						'max'     => 1000,
						'step'    => 5,
					],

					//					[
					//						'name'    => 'autoplay',
					//						'label'   => __( 'Autoplay :', 'wp-radio' ),
					//						'desc'    => __( 'Enable/ Disable autoplay in the single podcast/ episode page.', 'wp-radio' ),
					//						'type'    => 'switch',
					//						'default' => 'off',
					//					],

					[
						'name'    => 'player_volume',
						'label'   => __( 'Player Volume :', 'podcast-box' ),
						'desc'    => __( 'Set the player default volume.', 'podcast-box' ),
						'type'    => 'slider',
						'default' => '70',
						'step'    => 5,
					],

					[
						'name'    => 'next_prev',
						'label'   => __( 'Next Prev Buttons :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide next-previous  buttons in the podcast episode player.', 'podcast-box' ),
						'type'    => 'switch',
						'default' => 'on',
					],

					[
						'name'    => 'hide_player',
						'label'   => __( 'Hide Bottom Player :', 'podcast-box' ),
						'desc'    => __( 'Show/ hide the footer bottom fixed player. (You can play the radio station by hiding footer player.)',
							'podcast-box' ),
						'type'    => 'switch',
						'default' => 'off',
					],

				],

				'podcast_box_color_settings' => [
					[
						'name'    => 'settings_heading_listing_color',
						'default' => __( 'Podcast listing color', 'podcast-box' ),
						'type'    => 'heading',
					],

					[
						'name'  => 'listing_bg_color',
						'label' => __( 'Listing background color :', 'podcast-box' ),
						'desc'  => __( 'Customize the podcast listing background color.', 'podcast-box' ),
						'type'  => 'color',
					],

					[
						'name'  => 'listing_btn_color',
						'label' => __( 'Listing button color :', 'podcast-box' ),
						'desc'  => __( 'Customize the listing button color.', 'podcast-box' ),
						'type'  => 'color',
					],

					[
						'name'    => 'settings_heading_footer_player',
						'default' => __( 'Footer Player Color', 'podcast-box' ),
						'type'    => 'heading',
					],

					[
						'name'  => 'player_bg_color',
						'label' => __( 'Footer player background :', 'podcast-box' ),
						'desc'  => __( 'Customize the background color of the footer player.', 'podcast-box' ),
						'type'  => 'color',
					],

					[
						'name'  => 'player_btn_color',
						'label' => __( 'Footer player button color :', 'podcast-box' ),
						'desc'  => __( 'Customize the footer player button color.', 'podcast-box' ),
						'type'  => 'color',
					],
				],

			) );

			self::$settings_api = new WP_Military_Settings_API();

			//set sections and fields
			self::$settings_api->set_sections( $sections );
			self::$settings_api->set_fields( $fields );

			//initialize them
			self::$settings_api->admin_init();
		}

		/**
		 * Register the plugin page
		 */
		public function settings_menu() {
			add_submenu_page( 'edit.php?post_type=podcast', __( 'Podcast Box Settings', 'podcast-box' ), __( 'Settings', 'podcast-box' ),
				'manage_options', 'podcast-box-settings', array( $this, 'settings_page' ) );
		}

		/**
		 * Display the plugin settings options page
		 */
		public function settings_page() {
			echo '<div class="wrap podcast-box-settings-page">';
			settings_errors();

			echo sprintf( "<h2>%s</h2>", __( 'Podcast Box Settings', 'podcast-box' ) );
			self::$settings_api->show_settings();

			echo '</div>';
		}

		/**
		 * @return Podcast_Box_Settings|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

Podcast_Box_Settings::instance();