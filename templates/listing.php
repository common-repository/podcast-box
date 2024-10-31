<?php

defined( 'ABSPATH' ) || exit();

global $post, $wp_query;

$queried_object = get_queried_object();

$view = podcast_box_get_settings( 'listing_view', 'list', 'podcast_box_display_settings' );
$col  = podcast_box_get_settings( 'grid_column', 4, 'podcast_box_display_settings' );


$is_search  = ! empty( $_GET['country'] ) || ! empty( $_GET['category'] ) || ! empty( $_GET['keyword'] );
$is_tax     = is_tax( 'podcast_country' ) || is_tax( 'podcast_category' );
$ip_listing = 'on' == podcast_box_get_settings( 'ip_listing', 'on', 'podcast_box_display_settings' );


//Check if the shortcode has filter attributes
$shortcode_filter = ! empty( $shortcode_args['country'] ) || ! empty( $shortcode_args['category'] );

$is_custom = $shortcode_filter || $is_search || $is_tax;

?>
<div class="podcast-box-listings">

    <div class="podcast-box-listings-main">
		<?php

		// search bar
		if ( 'on' == podcast_box_get_settings( 'show_search', 'on', 'podcast_box_display_settings' ) ) {
			$search_style = podcast_box_get_settings( 'search_style', 1, 'podcast_box_display_settings' );
			podcast_box()->get_template( "search-$search_style" );
		}

		if ( ! $is_custom && $ip_listing ) {
			$query = podcast_box_get_podcasts_by_country();
		} else {

			$args     = [];
			$country  = [];
			$category = [];

			if ( ! empty( $_REQUEST['keyword'] ) ) {
				$args['s'] = wp_unslash( $_REQUEST['keyword'] );
			}

			//country search
			if ( ! empty( $_REQUEST['country'] ) ) {
				$country[] = intval( $_REQUEST['country'] );
			}

			//country archive taxonomy
			if ( is_tax( 'podcast_country' ) ) {
				$country[] = intval( $queried_object->term_id );
			}

			//category search
			if ( ! empty( $_REQUEST['category'] ) ) {
				$category[] = intval( $_REQUEST['category'] );
			}

			//category taxonomy archive
			if ( is_tax( 'podcast_category' ) ) {
				$category[] = intval( $queried_object->term_id );
			}

			$countries = ! empty( $shortcode_args['country'] ) ? array_filter( explode( ',', $shortcode_args['country'] ) ) : '';

			if ( ! empty( $countries ) ) {
				foreach ( $countries as $c ) {
					$country_term = get_term_by( 'slug', $c, 'podcast_country' );
					if ( $country_term ) {
						$country[] = $country_term->term_id;
					}
				}
			}

			$categories = ! empty( $shortcode_args['category'] ) ? array_filter( explode( ',', $shortcode_args['category'] ) ) : '';

			if ( ! empty( $categories ) ) {
				foreach ( $categories as $g ) {
					$category_term = get_term_by( 'slug', $g, 'podcast_category' );
					if ( $category_term ) {
						$category[] = $category_term->term_id;
					}
				}
			}

			if ( ! empty( $country ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'podcast_country',
						'field'    => 'term_id',
						'terms'    => $country,
					),
				);
			}

			if ( ! empty( $category ) ) {
				$args['tax_query'][] = array(
					'relation' => 'AND',
					'taxonomy' => 'podcast_category',
					'field'    => 'term_id',
					'terms'    => $category,
				);
			}

			$query = podcast_box_get_podcasts( $args, true );

		}

		if ( $query->have_posts() ) {

			//listing - top stations from country
			podcast_box()->get_template( 'listing-top', [ 'query' => $query ] );

			?>

            <div class="podcast-box-listing-wrapper <?php echo esc_attr($view) ?> <?php echo 'col_' . $col; ?>">
				<?php
				foreach ( $query->posts as $podcast ) {
					podcast_box()->get_template( 'loop-podcast', [ 'podcast' => $podcast ] );
				}
				?>
            </div>
			<?php
		} else {
			podcast_box()->get_template( 'not-found', ['item' => 'Podcast'] );
		}


		//pagination
		$paged = ! empty( $_REQUEST['paginate'] ) ? intval( $_REQUEST['paginate'] ) : '';
		$total = $query->max_num_pages;

		podcast_box_pagination( $paged, $total );

		?>

    </div>
</div>