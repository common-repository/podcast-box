<?php

defined('ABSPATH') || exit();

global $post;

$post_id = $post->ID;

$feed_url        = podcast_box_get_meta( $post_id, 'feed_url' );
$logo            = podcast_box_get_meta( $post_id, 'logo' );
$publisher_name  = podcast_box_get_meta( $post_id, 'publisher_name' );
$publisher_email = podcast_box_get_meta( $post_id, 'publisher_email' );
$website         = podcast_box_get_meta( $post_id, 'website' );
$itunes_url      = podcast_box_get_meta( $post_id, 'itunes_url' );
$type            = podcast_box_get_meta( $post_id, 'type' );
$language        = podcast_box_get_meta( $post_id, 'language' );

$paged        = ! empty( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : 1;
$episode_sort = ! empty( $_GET['sort'] ) ? sanitize_key( $_GET['sort'] ) : 'asc';

$episode_ids   = podcast_box_get_episode_ids( $post_id, $episode_sort );
$episode_count = count( $episode_ids );


?>


<div class="podcast-box-single">

    <!-- podcast-header -->
    <div class="podcast-header">

        <!--podcast thumbnail-->
        <img class="podcast-thumbnail" src="<?php echo esc_url($logo); ?>" alt="Logo">

        <!--podcast referrence-->
        <div class="podcast-referrence">
            <div class="podcast-byline">
                By <a href="#"><?php echo esc_html($publisher_name); ?></a>
            </div>

			<?php

			// podcast links
			$podcast_links = [
				'itunes'  => [
					'label' => 'Itunes',
					'value' => $itunes_url,
					'icon'  => 'admin-links',
				],
				'rss'     => [
					'label' => 'RSS',
					'value' => $feed_url,
					'icon'  => 'rss',
				],
				'website' => [
					'label' => 'Website',
					'value' => $website,
					'icon'  => 'admin-links',
				],
				'email'   => [
					'label' => 'Email',
					'value' => 'mailto:' . $publisher_email,
					'icon'  => 'email',
				],
			];

			?>

            <div class="podcast-links">
				<?php

				foreach ( $podcast_links as $podcast_link ) {

					if ( empty( $podcast_link['value'] ) ) {
						continue;
					}

					printf( '<a href="%1$s" target="_blank"><i class="dashicons dashicons-%2$s"></i> %3$s</a>', $podcast_link['value'],
						$podcast_link['icon'], $podcast_link['label'] );
				}
				?>

            </div>

        </div>

    </div>

    <!-- podcast-description -->
    <div class="podcast-description">
        <h3><?php _e( 'ABOUT THIS PODCAST', 'podcast-box' ); ?></h3>

        <div class="podcast-content">
            <p><?php echo get_the_content(); ?></p>
        </div>

		<?php

		//categories
		$categories = wp_get_post_terms( $post_id, 'podcast_category' );
		if ( ! empty( $categories ) ) { ?>
            <div class="podcast-categories">
                <span class="label">Categories:</span>
				<?php
				foreach ( $categories as $category ) {
					printf( '<a href="%s">%s</a>', get_term_link( $category->term_id ), $category->name );
				}
				?>
            </div>
		<?php } ?>

        <div class="podcast-info">

			<?php

			//country
			if ( podcast_box_get_country( $post->ID ) ) {
				$country = podcast_box_get_country( $post->ID );

				printf( '<a href="%s" class="podcast-country">%s <span>%s</span></a>', get_term_link( $country->term_id ),
					podcast_box_get_country_flag( $country->slug ), $country->name );
			}

			//language
			if ( ! empty( $language ) ) {
				printf( '<span class="podcast-language"><i class="dashicons dashicons-translation"></i>%s</span>', $language );
			}

			//episode count
			printf( '<span class="podcast-episode-count"><i class="dashicons dashicons-playlist-audio"></i> %s Episodes</span>',
				$episode_count );

			?>
        </div>

    </div>

    <!-- podcast episodes -->
    <div class="podcast-box-listings">

	    <?php
	    if ( 'on' == podcast_box_get_settings( 'show_search_episode', 'on', 'podcast_box_display_settings' ) ) {
		    podcast_box()->get_template( "search-1", [ 'type' => 'episode' ] );
	    }
	    ?>

        <div class="podcast-box-listings-header">
            <h4><?php _e( 'Latest Episodes', 'podcast-box' ) ?></h4>

            <div class="podcast-box-listings-sort">
                <span class="podcast-box-loader"></span>

                <label for="episode_sort"><?php _e( 'Sort', 'podcast-box' ); ?></label>
                <select name="episode_sort" id="episode_sort" class="episode_sort">
                    <option value="asc" <?php selected( 'asc', $episode_sort ); ?>><?php _e( 'Newer First', 'podcast-box' ); ?></option>
                    <option value="desc" <?php selected( 'desc', $episode_sort ); ?>><?php _e( 'Older First', 'podcast-box' ); ?></option>
                </select>
            </div>

        </div>

        <div class="podcast-box-listing-wrapper">
	        <?php

	        $length = podcast_box_get_settings( 'episodes_per_page', '10', 'podcast_box_display_settings' );
	        $offset = ( $paged - 1 ) * $length;

	        if ( ! empty( $episode_ids ) ) {

		        $episode_ids = array_slice( $episode_ids, $offset, $length );

		        foreach ( $episode_ids as $episode_id ) {
			        podcast_box()->get_template( 'loop-episode', [ 'episode_id' => $episode_id ] );
		        }


	        } else {
		        podcast_box()->get_template( 'not-found', [ 'item' => 'Episode' ] );
	        }

	        ?>
        </div>

	    <?php
	    //pagination
	    $total_pages = ceil( $episode_count / $length );

	    podcast_box_pagination( $paged, $total_pages, 'episode', $post_id );
	    ?>

    </div>

	<?php

	//related podcasts
	if ( 'on' == podcast_box_get_settings( 'you_may_like', 'on', 'podcast_box_display_settings' ) ) {
		podcast_box()->get_template( 'related', [ 'post_id' => $post_id ] );
	}

	//next-previous podcasts
	if ( 'on' == podcast_box_get_settings( 'single_next_prev', 'off', 'podcast_box_display_settings' ) ) {

		echo '<div class="podcast-box-post-pagination">';
		// Previous/next post navigation.
		ob_start();
		the_post_navigation( array(
			'in_same_term'       => true,
			'taxonomy'           => 'podcast_country',
			'prev_text'          => '<span class="post-title"><i class="dashicons dashicons-arrow-left-alt"></i> %title </span>',
			'next_text'          => '<span class="post-title">%title <i class="dashicons dashicons-arrow-right-alt"></i></span>',
			'screen_reader_text' => false,
		) );
		$html = ob_get_clean();

		echo str_replace( 'post-navigation', '', $html );
		echo '</div>';
	}

	?>

</div>