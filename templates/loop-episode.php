<?php

defined( 'ABSPATH' ) || exit;
$file = podcast_box_get_meta( $episode_id, 'file' );
$episode_logo = podcast_box_get_meta( $episode_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' );
$link = podcast_box_get_meta( $episode_id, 'link' );
$episode_number = podcast_box_get_meta( $episode_id, 'episode_number' );
$season_number = podcast_box_get_meta( $episode_id, 'season_number' );
$episode_date = get_the_date( get_option( 'date_format' ), $episode_id );
$duration = podcast_box_get_meta( $episode_id, 'duration' );
$duration = ( strpos( $duration, ':' ) === false ? gmdate( "H:i:s", $duration ) : $duration );
$show_desc = 'on' == podcast_box_get_settings( 'episode_description', 'on', 'podcast_box_display_settings' );
$play_btn = podcast_box_get_settings( 'player_type', 'default', 'podcast_box_player_settings' );
?>
<div class="podcast-episode" id="episode-<?php 
echo  $episode_id ;
?>">
	<div class="episode-header">
        <img src="<?php 
echo  esc_url( $episode_logo ) ;
?>" alt="Logo" class="episode-thumbnail">

		<div class="episode-meta">
			<a href="<?php 
echo  get_the_permalink( $episode_id ) ;
?>" class="episode-title"><?php 
echo  get_the_title( $episode_id ) ;
?></a>
            <span class="epsiode-date"><?php 
echo  esc_html( $episode_date ) ;
?></span>

            <div class="episode-play">

                <a href="javascript:;" title="Play" data-episode='<?php 
echo  podcast_box_get_episode_data( $episode_id ) ;
?>' class="podcast-play-pause  <?php 
echo  ( 'popup' == $play_btn ? 'popup-play' : '' ) ;
?>">
                    <span class="podcast-box-loader"></span>
                    <i class="dashicons dashicons-controls-play"></i>
                    <i class="dashicons dashicons-controls-pause"></i>
                    <span><?php 
echo  esc_html( $duration ) ;
?></span>
                </a>

	            <?php 
?>
            </div>

		</div>


	</div>

	<?php 
if ( $show_desc ) {
    printf( '<p class="episode-description">%s</p>', wp_trim_words( get_post_field( 'post_content', $episode_id ), 40 ) );
}
?>

</div>