<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Podcast_Box_Shortcode' ) ) {
	class Podcast_Box_Shortcode {

		private static $instance = null;

		/* constructor */
		public function __construct() {
			add_shortcode( 'podcast_box_listing', array( $this, 'listing' ) );
			add_shortcode( 'podcast_box_country_list', array( $this, 'country_list' ) );
			add_shortcode( 'podcast_box_player', array( $this, 'player' ) );
		}

		public function player( $atts ) {
			$atts = shortcode_atts( array(
				'podcast_id'  => '',
				'player_type' => 'shortcode',
			), $atts );

			ob_start();
			podcast_box()->get_template( 'player', $atts );

			return ob_get_clean();
		}

		public function listing( $atts ) {

			$atts = shortcode_atts( array(
				'country'  => '',
				'category' => '',
			), $atts );

			ob_start();
			podcast_box()->get_template( 'listing', [ 'shortcode_args' => $atts ] );

			return ob_get_clean();
		}

		public function country_list( $atts ) {

			$atts = shortcode_atts( array(
				'shortcode' => true,
			), $atts );

			ob_start();
			podcast_box()->get_template( 'country-list', $atts );

			return ob_get_clean();
		}


		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

Podcast_Box_Shortcode::instance();