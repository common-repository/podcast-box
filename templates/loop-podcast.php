<?php

/* Block direct access */
defined( 'ABSPATH' ) || exit();

$post_id = $podcast->ID;

$logo  = podcast_box_get_meta( $post_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' );
$url   = get_the_permalink( $post_id );
$title = get_the_title( $post_id );

$publisher_name = podcast_box_get_meta( $post_id, 'publisher_name' );


$show_desc      = 'on' == podcast_box_get_settings( 'listing_content', 'on', 'podcast_box_display_settings' );
$latest_episode = 'on' == podcast_box_get_settings( 'latest_episode', 'on', 'podcast_box_display_settings' );

$play_btn = podcast_box_get_settings( 'player_type', 'default', 'podcast_box_player_settings' );


?>

<div class="podcast-box-listing <?php echo $show_desc ? '' : 'hide_desc'; ?>">

    <a class="listing-thumbnail" href="<?php echo esc_url($url); ?>">
        <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($title); ?>">
    </a>

    <div class="listing-details">

        <a href="<?php echo esc_url($url); ?>" class="listing-heading">
            <i class="dashicons dashicons-microphone"></i>
            <span class="podcast-name"><?php echo esc_html($title); ?></span>
        </a>

        <a href="#" class="podcast-byline">
            <i class="dashicons dashicons-admin-users"></i><?php echo esc_html($publisher_name); ?>
        </a>

	    <?php

	    //country
	    if ( podcast_box_get_country( $post_id ) ) {
		    $country = podcast_box_get_country( $post_id );

		    printf( '<a href="%s" class="podcast-country">%s <span>%s</span></a>', get_term_link( $country->term_id ),
			    podcast_box_get_country_flag( $country->slug ), $country->name );
	    }

		?>

	    <?php if ( ! $latest_episode ) { ?>
            <a href="<?php echo get_the_permalink( $post_id ); ?>" class="start-listening">
                <i class="dashicons dashicons-controls-play"></i>
			    <?php _e( 'Start listening' ); ?></a>
	    <?php } ?>

    </div>

	<?php
	if ( $show_desc ) {
		printf( '<p class="listing-desc">%s</p>', wp_trim_words( get_post_field( 'post_content', $post_id ), 25 ) );
	}

	if ( $latest_episode ) {
		$episode_id = podcast_box_get_episode_ids( $post_id, 'asc', 0, 1 );

		if ( $episode_id ) {
			$episode_date = podcast_box_get_meta( $episode_id, 'date' );
			$duration     = podcast_box_get_meta( $episode_id, 'duration', 0 );
			?>
            <div class="listing-episode" id="episode-<?php echo $episode_id; ?>">

                <h4 class="listing-episode-title"><?php _e( 'Latest Episode', 'podcast-box' ); ?></h4>

                <div class="listing-episode-header">

                    <a href="<?php echo get_the_permalink( $episode_id ); ?>" class="episode-title">
                        <i class="dashicons dashicons-format-video"></i>
                        <span><?php echo get_the_title( $episode_id ); ?></span>
                    </a>
                    <span class="episode-date"><?php echo $episode_date; ?></span>
                </div>

                <div class="listing-episode-footer">
                    <a href="#" title="Play" id="episode-play-<?php echo $episode_id; ?>" data-episode='<?php echo podcast_box_get_episode_data( $episode_id ); ?>' class="podcast-play-pause <?php echo 'popup' == $play_btn ? 'popup-play' : ''; ?>">
                        <span class="podcast-box-loader"> </span>
                        <i class="dashicons dashicons-controls-play"></i>
                        <i class="dashicons dashicons-controls-pause"></i>
                        <span><?php echo gmdate( "H:i:s", $duration ); ?></span>
                    </a>

                    <a href="<?php echo $url; ?>" class="view-episode">
                        <i class="dashicons dashicons-migrate"></i>
                        <span><?php _e( 'View All', 'podcast-box' ); ?></span>
                    </a>
                </div>
            </div>
		<?php }
	} ?>

</div>