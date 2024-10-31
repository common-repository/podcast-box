<?php

global  $post ;
$post_id = $post->ID;
$podcast = podcast_box_get_episode_podcast( $post_id );
$file = podcast_box_get_meta( $post_id, 'file' );
$logo = podcast_box_get_meta( $post_id, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' );
$link = podcast_box_get_meta( $post_id, 'link' );
$date = get_the_date( get_option( 'date_format' ), $post_id );
$episode_number = podcast_box_get_meta( $post_id, 'episode_number' );
$season_number = podcast_box_get_meta( $post_id, 'season_number' );
$feed_url = podcast_box_get_meta( $podcast, 'feed_url' );
$itunes_url = podcast_box_get_meta( $podcast, 'itunes_url' );
$podcast_logo = podcast_box_get_meta( $podcast, 'logo' );
$duration = podcast_box_get_meta( $post_id, 'duration' );
$duration = ( strpos( $duration, ':' ) === false ? gmdate( "H:i:s", $duration ) : $duration );
$is_popup = 'popup' == podcast_box_get_settings( 'player_type', 'default', 'podcast_box_player_settings' );
?>


<div class="podcast-box-single podcast-epsiode-single" id="episode-<?php 
echo  $post_id ;
?>">

    <!-- podcast-header -->
    <div class="podcast-header">

        <!--podcast thumbnail-->
        <img class="podcast-thumbnail" src="<?php 
echo  esc_url( $logo ) ;
?>" alt="Logo">

        <div class="episode-details">

                <div class="episode-podcast">
                    <i class="dashicons dashicons-microphone"></i>
                    <a href="<?php 
echo  get_the_permalink( $podcast ) ;
?>"><?php 
echo  get_the_title( $podcast ) ;
?></a>
                </div>

            <!--podcast referrence-->
            <div class="podcast-referrence">

			    <?php 
$podcast_links = [
    'itunes' => [
    'label' => 'Itunes',
    'value' => $itunes_url,
    'icon'  => 'admin-links',
],
    'rss'    => [
    'label' => 'RSS',
    'value' => $feed_url,
    'icon'  => 'rss',
],
    'link'   => [
    'label' => 'Link',
    'value' => $link,
    'icon'  => 'admin-links',
],
];
?>

                <div class="podcast-links">

                <span>
                    <i class="dashicons dashicons-calendar"></i>

                    <?php 
echo  esc_html( $date ) ;
?>
                </span>

				    <?php 
foreach ( $podcast_links as $podcast_link ) {
    if ( empty($podcast_link['value']) ) {
        continue;
    }
    printf(
        '<a href="%1$s" target="_blank"><i class="dashicons dashicons-%2$s"></i> %3$s</a>',
        $podcast_link['value'],
        $podcast_link['icon'],
        $podcast_link['label']
    );
}
?>

                </div>

            </div>
        </div>

        <div class="episode-actions">

            <a href="javascript:;" title="Play" data-episode='<?php 
echo  podcast_box_get_episode_data( $post_id ) ;
?>'
                    class="podcast-play-pause <?php 
echo  ( $is_popup ? 'popup-play' : '' ) ;
?>">
                <i class="dashicons dashicons-controls-play"></i>
                <span class="podcast-box-loader"></span>
                <i class="dashicons dashicons-controls-pause"></i>
                <span><?php 
echo  esc_html( $duration ) ;
?></span>
            </a>

            <?php 
?>

        </div>

    </div>

    <!-- podcast-description -->
    <div class="podcast-description">

        <div class="podcast-content">
            <p><?php 
echo  get_the_content() ;
?></p>
        </div>

    </div>

    <?php 
//related episodes
if ( 'on' == podcast_box_get_settings( 'you_may_like', 'on', 'podcast_box_display_settings' ) ) {
    podcast_box()->get_template( 'related', [
        'post_id' => $post_id,
        'type'    => 'episode',
    ] );
}
//next-previous episodes

if ( 'on' == podcast_box_get_settings( 'single_next_prev', 'off', 'podcast_box_display_settings' ) ) {
    echo  '<div class="podcast-box-post-pagination">' ;
    // Previous/next post navigation.
    ob_start();
    the_post_navigation( array(
        'prev_text'          => '<span class="post-title"><i class="dashicons dashicons-arrow-left-alt"></i> %title </span>',
        'next_text'          => '<span class="post-title">%title <i class="dashicons dashicons-arrow-right-alt"></i></span>',
        'screen_reader_text' => false,
    ) );
    $html = ob_get_clean();
    echo  str_replace( 'post-navigation', '', $html ) ;
    echo  '</div>' ;
}

?>

</div>