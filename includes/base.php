<?php

defined( 'ABSPATH' ) || exit();

final class Podcast_Box {

	/**
	 * Minimum PHP version required
	 *
	 * @var string
	 */
	private $min_php = '5.6.0';

	/**
	 * The single instance of the class.
	 *
	 * @var Podcast_Box
	 * @since 1.0.0
	 */
	protected static $instance = null;

	public function __construct() {
		$this->check_environment();
		$this->includes();
		$this->init_hooks();

		do_action( 'wp_podcasts_loaded' );
	}

	/**
	 * Ensure theme and server variable compatibility
	 */
	public function check_environment() {

		if ( version_compare( PHP_VERSION, $this->min_php, '<=' ) ) {
			deactivate_plugins( plugin_basename( PODCAST_BOX_FILE ) );

			wp_die( "Unsupported PHP version Min required PHP Version:{$this->min_php}" );
		}

	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {

		//core includes
		include_once PODCAST_BOX_INCLUDES . '/functions.php';
		include_once PODCAST_BOX_INCLUDES . '/class-cpt.php';
		include_once PODCAST_BOX_INCLUDES . '/class-enqueue.php';
		include_once PODCAST_BOX_INCLUDES . '/class-form-handler.php';
		include_once PODCAST_BOX_INCLUDES . '/class-hooks.php';
		include_once PODCAST_BOX_INCLUDES . '/class-cron.php';
		include_once PODCAST_BOX_INCLUDES . '/class-shortcode.php';
		include_once PODCAST_BOX_INCLUDES . '/class-episode-importer.php';
		include_once PODCAST_BOX_INCLUDES . '/class-widget.php';

		//admin includes
		if ( is_admin() ) {
			include_once PODCAST_BOX_INCLUDES . '/admin/class-admin.php';
			include_once PODCAST_BOX_INCLUDES . '/admin/class-metabox.php';
			include_once PODCAST_BOX_INCLUDES . '/admin/class-settings-api.php';
			include_once PODCAST_BOX_INCLUDES . '/admin/class-settings.php';
		}

	}

	/**
	 * Hook into actions and filters.
	 *
	 * @since 2.3
	 */
	private function init_hooks() {

		add_action( 'admin_notices', [ $this, 'print_notices' ], 15 );

		//Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );
	}


	/**
	 * Initialize plugin for localization
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'podcast-box', false, PODCAST_BOX_PATH . '/languages/' );
	}


	public function add_notice( $class, $message ) {

		$notices = get_option( sanitize_key( 'wp_radio_notification' ), [] );
		if ( is_string( $message ) && is_string( $class ) && ! wp_list_filter( $notices, array( 'message' => $message ) ) ) {

			$notices[] = array(
				'message'    => $message,
				'class'      => $class,
			);

			update_option( sanitize_key( 'wp_radio_notification' ), $notices );
		}

	}

	public function print_notices() {
		$notices = get_option( sanitize_key( 'wp_radio_notification' ), [] );
		foreach ( $notices as $notice ) {?>
            <div class="notice notice-large is-dismissible wp-radio-player-notice notice-<?php echo $notice['class']; ?>">
				<?php echo $notice['message']; ?>
            </div>
			<?php
			update_option( sanitize_key( 'wp_radio_notification' ), [] );
		}
	}

	/**
	 * Get template files
	 *
	 * since 1.0.0
	 *
	 * @param        $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return void
	 */
	public function get_template( $template_name, $args = array(), $template_path = 'podcast-box', $default_path = '' ) {

		/* Add php file extension to the template name */
		$template_name = $template_name . '.php';

		/* Extract the args to variables */
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		/* Look within passed path within the theme - this is high priority. */
		$template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );

		/* Get default template. */
		if ( ! $template ) {
			$default_path = $default_path ? $default_path : PODCAST_BOX_TEMPLATES;
			if ( file_exists( trailingslashit( $default_path ) . $template_name ) ) {
				$template = trailingslashit( $default_path ) . $template_name;
			}
		}

		// Return what we found.
		include( apply_filters( 'podcast_box/locate_template', $template, $template_name, $template_path ) );

	}


	/**
	 * Main Podcast_Box Instance.
	 *
	 * Ensures only one instance of Podcast_Box is loaded or can be loaded.
	 *
	 * @return Podcast_Box - Main instance.
	 * @since 1.0.0
	 * @static
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

if ( ! function_exists( 'podcast_box' ) ) {
	function podcast_box() {
		return Podcast_Box::instance();
	}
}

podcast_box();