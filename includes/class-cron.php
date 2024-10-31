<?php

defined( 'ABSPATH' ) || exit();

if ( ! class_exists( 'Podcast_Box_Cron' ) ) {
	class Podcast_Box_Cron {
		/** @var null */
		private static $instance = null;

		/**
		 * Podcast_Box_Cron constructor.
		 */
		public function __construct() {
			//add_filter( 'cron_schedules', [ $this, 'add_cron_schedule' ] );

			add_action( 'init', [ $this, 'init_cron' ] );
		}

		public function init_cron() {

			$is_cron = 'on' == podcast_box_get_settings( 'cron', 'off' );
			$hook    = 'podcast_box/automatic_import_hook';

			if ( $is_cron ) {
				if ( ! wp_next_scheduled( $hook ) ) {
					wp_schedule_event( time(), 'hourly', $hook );
				}
			} else {
				wp_clear_scheduled_hook( $hook );
			}
		}

		public function add_cron_schedule( $schedules ) {

			// Adds once weekly to the existing schedules.
			$schedules ['once_a_minute'] = array(
				'interval' => 60,
				'display'  => __( 'Once a minute' ),
			);

			return $schedules;
		}

		/**
		 * @return Podcast_Box_Cron|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

Podcast_Box_Cron::instance();