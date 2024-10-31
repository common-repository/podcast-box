<?php

$type = ! empty( $type ) ? $type : 'podcast';

$is_grid = 'grid' == podcast_box_get_settings( 'listing_view', '', 'podcast_box_display_settings' );

$args = [
	'orderby'      => 'rand',
	'post__not_in' => [ $post_id ],
];

if ( 'podcast' == $type ) {

	$args['tax_query']      = [ 'relation' => 'AND' ];
	$args['posts_per_page'] = $is_grid ? 4 : 3;

	$country = podcast_box_get_country( $post_id );

	if ( ! empty( $country->term_id ) ) {

		$args['tax_query'][] = [
			'taxonomy' => 'podcast_country',
			'field'    => 'term_id',
			'terms'    => $country->term_id,
		];
	}

	$categories = wp_get_post_terms( $post_id, 'podcast_category' );
	$categories = wp_list_pluck( $categories, 'term_id' );

	if ( ! empty( $categories ) ) {
		$args['tax_query'][] = [
			'taxonomy' => 'podcast_category',
			'field'    => 'term_id',
			'terms'    => $categories,
		];
	}

	$related = podcast_box_get_podcasts( $args );

} else {
    $podcast_id = podcast_box_get_episode_podcast($post_id);

	$args['posts_per_page'] = 3;
	$args['meta_key']       = 'podcast';
	$args['meta_value']     = $podcast_id;

	$related = podcast_box_get_episodes( $args );
}


if ( ! empty( $related ) ) { ?>
    <div class="podcast-box-related podcast-box-listings">
        <h3><?php esc_html_e( 'Related', 'podcast-box' ); ?> - <?php echo 'podcast' == $type ? __( 'Podcasts', 'podcast-box' )
			    : __( 'Episodes', 'podcast-box' ); ?></h3>

        <div class="podcast-box-listings-main">
            <div class="podcast-box-listing-wrapper <?php echo 'podcast' == $type && $is_grid ? 'grid' : ''; ?>">
				<?php
				foreach ( $related as $item ) {
					if ( 'podcast' == $type ) {
						podcast_box()->get_template( 'loop-podcast', [ 'podcast' => $item ] );
					} else {
						$episode_id = $item->ID;
						podcast_box()->get_template( 'loop-episode', [ 'episode_id' => $episode_id ] );
					}
				}
				?>
            </div>
        </div>
    </div>
<?php } ?>