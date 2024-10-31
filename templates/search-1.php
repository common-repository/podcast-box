<?php

defined( 'ABSPATH' ) || exit();

$keyword = ! empty( $_REQUEST['keyword'] ) ? esc_attr( $_REQUEST['keyword'] ) : '';

$type = isset( $type ) ? $type : 'podcast';

$placeholder = 'podcast' == $type ? __( 'Search Podcast', 'podcast-box' ) : __( 'Search episodes.', 'podcast-box' );

$form_action_page = podcast_box_get_settings( 'listing_page' );

if ( 'episode' == $type ) {
	global $post;
	$podcast_id = $post->ID;

	$form_action_page = get_the_permalink( $podcast_id );
}

?>

<div id="podcast_search" class="podcast-box-search" data-type="<?php echo esc_attr($type); ?>" data-podcast_id="<?php echo isset( $podcast_id )
	? intval($podcast_id) : ''; ?>">

    <div class="search_toggle">
        <button type="button" class="button-primary">
            <i class="dashicons dashicons-menu"></i> <?php esc_html_e( 'Search', 'podcast-box' ); ?>
        </button>
    </div>

    <form action="<?php echo get_permalink( $form_action_page ); ?>" method="get" id="podcast_search_form">

        <i class="dashicons dashicons-search"></i>

        <!--keyword-->
        <select name="keyword" id="keyword"
                data-placeholder="<?php echo ! empty( $keyword ) ? esc_attr($keyword) : esc_attr($placeholder); ?>">
            <option value=""></option>
        </select>

        <!--submit-->
        <button type="submit">
            <span><?php _e( 'Search', 'podcast-box' ); ?></span>
        </button>

    </form>
</div>