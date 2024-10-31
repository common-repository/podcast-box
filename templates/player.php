<?php

/* Block Direct Access */
defined( 'ABSPATH' ) || exit();

//initialize values

$player_type = ! empty( $player_type ) ? $player_type : 'fixed';

$is_fixed = 'fixed' == $player_type;
$is_popup = 'popup' == $player_type;

$is_shortcode = in_array( $player_type, [ 'shortcode', 'popup' ] );

$episode_title     = '';
$podcast_title     = '';
$episode_permalink = '#';
$podcast_permalink = '#';
$episode_logo      = PODCAST_BOX_ASSETS . '/images/placeholder.png';
$podcast_logo      = PODCAST_BOX_ASSETS . '/images/placeholder.png';
$episode           = '';
$duration          = '';

$player_attrs = [
	'class' => 'podcast-box-player',
];


//Type fixed player attributes
if ( $is_fixed ) {
	$player_attrs['id']    = 'podcast-box-player';
	$player_attrs['class'] .= ' full-width ';

	$is_hidden = 'on' == podcast_box_get_settings( 'hide_player', '', 'podcast_box_player_settings' ) || ( ! empty( $_GET['podcast_player'] ) );

	$player_attrs['class'] .= $is_hidden ? ' hidden ' : ' show ';
}

//Type shortcode player attributes
if ( $is_shortcode ) {

	$player_attrs['class'] .= ' shortcode ';

	if ( 'popup' == $player_type ) {
		$player_attrs['class'] .= ' popup';
	}

	if ( ! empty( $episode_id ) ) {
		$podcast_id = podcast_box_get_episode_podcast( $episode_id );
	} else {

		if ( empty( $podcast_id ) || ! get_post( $podcast_id ) ) {
			global $podcast_box_args;

			$podcast_box_args = [ 'posts_per_page' => 1 ];
			$query            = podcast_box_get_podcasts_by_country();

			if ( $query->have_posts() ) {
				$podcast_id = $query->posts[0]->ID;
			}
		}

		$episode_ids = podcast_box_get_episode_ids( $podcast_id );

		if ( $episode_ids ) {
			$episode_id = $episode_ids[0];
		}
	}

	if ( empty( $episode_id ) ) {
		_e( 'No episodes found for the podcast.', 'podcast-box' );

		return;
	}

	$episode           = podcast_box_get_episode_data( $episode_id );
	$src               = podcast_box_get_meta( $episode_id, 'file' );
	$episode_title     = get_the_title( $episode_id );
	$podcast_title     = get_the_title( $podcast_id );
	$episode_permalink = get_the_permalink( $episode_id );
	$podcast_permalink = get_the_permalink( $podcast_id );
	$episode_logo      = podcast_box_get_meta( $episode_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.png' );
	$podcast_logo      = podcast_box_get_meta( $podcast_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.png' );

	$duration = podcast_box_get_meta( $episode_id, 'duration', '' );
	$duration = ( strpos( $duration, ':' ) === false && ! empty( $duration ) ) ? gmdate( "H:i:s", $duration ) : $duration;


	$player_attrs['id'] = 'podcast-box-shortcode-player-' . $podcast_id;
}

?>

<div <?php
//set player attributes
foreach ( $player_attrs as $name => $value ) {
	printf( ' %1$s="%2$s" ', $name, $value );
}; ?> >

    <!--Details-->
    <div class="podcast-box-player-details">

        <!-- episode logo -->
		<?php
		printf( '<a class="podcast-box-player-url podcast-box-player-thumbnail-wrap %1$s" href="%2$s">
            <img src="%3$s" class="podcast-box-player-thumbnail" alt="%4$s"/>
        </a>', $is_popup ? 'open-in-parent' : '', $episode_permalink, $episode_logo, $episode_title );
		?>

        <div class="podcast-box-player-episode">
            <!-- episode url-->
			<?php
			printf( '<a href="%1$s" class="podcast-box-player-url podcast-box-player-title %2$s">%3$s</a>', $episode_permalink,
				$is_popup ? 'open-in-parent' : '', $episode_title );
			?>

            <!-- episode podcast-->
			<?php
			printf( '<div class="episode-podcast">
                <i class="dashicons dashicons-microphone"></i>
                <a href="%1$s" class="episode-podcast-url episode-podcast-title %2$s">%3$s</a>
            </div>', $podcast_permalink, $is_popup ? 'open-in-parent' : '', $podcast_title );
			?>

        </div>
    </div>

    <!--Controls-->
    <div class="podcast-box-player-controls">

        <!--Play Pause Control-->
        <div class="podcast-box-player-controls-tools">

			<?php
			//skip back
			$next_prev = 'on' == podcast_box_get_settings( 'next_prev', 'on', 'podcast_box_player_settings' );
			$next_prev
			&& printf( '<span class="podcast-box-player-prev dashicons dashicons-controls-skipback" title="%1$s"> </span>',
				__( 'Previous Episode', 'podcast-box' ) );

			?>


	        <?php
	        // back
	        $rewind_forward = 'on' == podcast_box_get_settings( 'rewind_forward', 'on', 'podcast_box_player_settings' );
	        $rewind_forward
	        && printf( '<span class="podcast-box-player-rewind dashicons dashicons-controls-back" title="%1$s"> </span>',
		        __( 'Rewind 10 seconds', 'podcast-box' ) );

			?>

            <!-- play-pause -->
	        <?php
	        $play_btn = podcast_box_get_settings( 'player_type', 'default', 'podcast_box_player_settings' );
	        ?>

            <div id="<?php echo $is_shortcode ? '' : 'podcast-play-pause'; ?>" class="podcast-play-pause <?php echo !$is_popup && 'popup' == $play_btn ? 'popup-play' : ''; ?>" data-episode='<?php echo ! empty( $episode ) ? $episode : ''; ?>'>
                <span class="podcast-box-loader"> </span>
                <span class="dashicons dashicons-controls-play"> </span>
                <span class="dashicons dashicons-controls-pause"> </span>
            </div>

	        <?php
	        // forward
	        $rewind_forward = 'on' == podcast_box_get_settings( 'rewind_forward', 'on', 'podcast_box_player_settings' );
	        $rewind_forward
	        && printf( '<span class="podcast-box-player-forward dashicons dashicons-controls-forward" title="%1$s"> </span>',
		        __( 'Forward 15 seconds', 'podcast-box' ) );

	        ?>

			<?php

			//skip forward
			$next_prev = 'on' == podcast_box_get_settings( 'next_prev', 'on', 'podcast_box_player_settings' );

			$next_prev
			&& printf( '<span class="podcast-box-player-next dashicons dashicons-controls-skipforward %1$s" title="%2$s"> </span>',
				$is_popup ? 'open-in-parent' : '', __( 'Next Episode', 'podcast-box' ) );
			?>

            <!-- volume control -->
            <div class="podcast-box-player-volume">

                <div class="volume-icon">
                    <i class="dashicons dashicons-controls-volumeon"></i>
                    <i class="dashicons dashicons-controls-volumeoff"></i>
                </div>

                <div class="volume-bar">
                    <input class="volume-bar-seek" type="range" min="0" max="1" step=".05" value="0">
                    <progress class="volume-bar-slide" max="1" value="0" role="progressbar"></progress>
                </div>
            </div>

        </div>

    </div>

    <!-- Progress -->
    <div class="podcast-box-player-progress-wrap">
        <span class="podcast-box-player-time">00:00</span>

        <div class="podcast-box-player-progress" title="Progress">
            <input class="podcast-box-player-progress-seek" type="range" min="0" max="100" step="0.01" value="0">
            <progress class="podcast-box-player-progress-bar" max="100" value="0" role="progressbar"></progress>
        </div>

        <span class="podcast-box-player-duration"><?php echo ! empty( $duration ) ? esc_html( $duration ) : '00:00'; ?></span>
    </div>

	<?php

	/* player toggle */
	if ( 'fixed' == $player_type ) { ?>
        <!-- player toggle -->
        <div class="podcast-box-player-toggle dashicons dashicons-arrow-down-alt2"></div>

        <audio id="podcast_player_media"></audio>
	<?php } ?>

</div>