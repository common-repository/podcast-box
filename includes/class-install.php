<?php

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class Podcast_Box_Install {

	public static function activate() {
		self::create_default_data();
		self::create_pages();
		self::create_tables();
	}

	public static function deactivate(){
		self::clear_cron();
	}

	private static function clear_cron(){
		wp_clear_scheduled_hook('podcast_box/automatic_import_hook');
	}

	private static function create_pages() {
		if ( get_page_by_title( 'Podcasts' ) ) {
			return;
		}

		$post_id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_title'   => esc_html__( 'Podcasts', 'podcast-box' ),
			'post_content' => '[podcast_box_listing]',
			'post_status'  => 'publish',
		) );

		$general_settings                 = (array) get_option( 'podcast_box_general_settings' );
		$general_settings['listing_page'] = $post_id;

		update_option( 'podcast_box_general_settings', $general_settings );

	}

	/**
	 * create default data
	 *
	 * @since 2.0.8
	 */
	private static function create_default_data() {

		$version      = get_option( 'podcast_box_version', '0' );
		$install_time = get_option( 'podcast_box_install_time', '' );

		if ( empty( $version ) ) {
			update_option( 'podcast_box_version', PODCAST_BOX_VERSION );
		}

		if ( ! empty( $install_time ) ) {
			$date_format = get_option( 'date_format' );
			$time_format = get_option( 'time_format' );
			update_option( 'podcast_box_install_time', date( $date_format . ' ' . $time_format ) );
		}

		update_option( 'podcast_box_flush_rewrite_rules', true );

	}


	private static function create_tables() {
		global $wpdb;
		$wpdb->hide_errors();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$tables = [

			//ip table
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}podcast_box_visitors(
         	id bigint(20) NOT NULL AUTO_INCREMENT,
			ip varchar(128)  NOT NULL DEFAULT '',
			country_code varchar(128) NOT NULL DEFAULT '',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id),
			UNIQUE KEY `ip` (`ip`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

			//podcast-episdoe relation table
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}podcast_episode_relation(
			podcast_id bigint(20) NOT NULL,
			episode_id bigint(20) NOT NULL,
			UNIQUE KEY `episode_id` (`episode_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;",

		];

		foreach ( $tables as $table ) {
			dbDelta( $table );
		}
	}

}