<?php

defined( 'ABSPATH' ) || exit();


if ( ! class_exists( 'Podcast_Box_Player_Widget' ) ) {
	class Podcast_Box_Player_Widget extends WP_Widget {

		private static $instance = null;

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'podcast_box_player_widget',
				'description' => esc_html__( 'Display podcast episode player.', 'podcast-box' ),
			);

			parent::__construct( 'podcast_box_player', __( 'Podcast Player', 'podcast-box' ), $widget_ops );

			add_action( 'widgets_init', [ $this, 'register_widget' ] );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param   array  $args
		 * @param   array  $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			podcast_box()->get_template( 'player', [ 'player_type' => 'shortcode', 'podcast_id' => $instance['podcast'] ] );

			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param   array  $instance  The widget options
		 */
		public function form( $instance ) {
			$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
			$podcast_id = ! empty( $instance['podcast'] ) ? $instance['podcast'] : '';
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'podcast-box' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                        value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p class="podcast_box_player_widget">
                <label for="<?php echo esc_attr( $this->get_field_id( 'podcast' ) ); ?>"><?php esc_attr_e( 'Podcast:', 'podcast-box' ); ?></label>

                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'podcast' ) ); ?>"
                        name="<?php echo esc_attr( $this->get_field_name( 'podcast' ) ); ?>">
					<?php

					if ( ! empty( $podcast_id ) ) {
						printf( '<option value="%1$s">%2$s</option>', $podcast_id, get_the_title( $podcast_id ) );
					} ?>
                </select>
                <span><?php _e( 'Search podcasts by entering minimum 1 characters.', 'podcast-box' ); ?></span>
            </p>
			<?php
		}

		/**
		 * Processing widget options on save
		 *
		 * @param   array  $new_instance  The new options
		 * @param   array  $old_instance  The previous options
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance               = array();
			$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
			$instance['podcast'] = ( ! empty( $new_instance['podcast'] ) ) ? sanitize_text_field( $new_instance['podcast'] ) : '';

			return $instance;
		}

		public function register_widget() {
			register_widget( __CLASS__ );
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

Podcast_Box_Player_Widget::instance();

if ( ! class_exists( 'Podcast_Box_Country_List_Widget' ) ) {
	class Podcast_Box_Country_List_Widget extends WP_Widget {

		private static $instance = null;

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'podcast_box_country_list_widget',
				'description' => esc_html__( 'List all the radio station countries.', 'podcast-box' ),
			);

			parent::__construct( 'podcast_box_country_list', __( 'Podcast Country List', 'podcast-box' ), $widget_ops );

			add_action( 'widgets_init', [ $this, 'register_widget' ] );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			echo do_shortcode('[podcast_box_country_list]');
			echo $args['after_widget'];
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			$title      = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'podcast-box' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                       value="<?php echo esc_attr( $title ); ?>">
            </p>

            <p>
                Display country list of all the radio stations.
            </p>

			<?php
		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance               = array();
			$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

			return $instance;
		}

		public function register_widget() {
			register_widget( __CLASS__ );
		}

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

	}
}

Podcast_Box_Country_List_Widget::instance();