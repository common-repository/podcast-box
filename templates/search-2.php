<?php

$keyword     = ! empty( $_REQUEST['keyword'] ) ? esc_attr( $_REQUEST['keyword'] ) : '';
$country_id  = ! empty( $_REQUEST['country'] ) ? intval( $_REQUEST['country'] ) : '';
$category_id = ! empty( $_REQUEST['category'] ) ? intval( $_REQUEST['category'] ) : '';

if ( is_tax( 'podcast_country' ) ) {
	$country_id = get_queried_object()->term_id;
} elseif ( is_tax( 'podcast_category' ) ) {
	$category_id = get_queried_object()->term_id;
}

//country
if ( ! empty( $country_id ) ) {
	$country_name = get_term_field( 'name', $country_id, 'podcast_country' );
}


//category
if ( ! empty( $category_id ) ) {
	$category_name = get_term_field( 'name', $category_id, 'podcast_category' );
}

?>

<div class="podcast-box-search search-2">

    <h4 class="podcast-box-search-title"><?php _e( 'Search podcasts', 'podcast-box' ) ?></h4>

    <div class="search_toggle">
        <button type="button" class="button-primary">
            <i class="dashicons dashicons-menu"></i> <?php esc_html_e( 'Search podcasts', 'podcast-box' ); ?>
        </button>
    </div>

    <form action="<?php echo get_permalink( podcast_box_get_settings( 'listing_page' ) ); ?>" method="get" id="podcast_search">

        <!--keyword-->
        <input name="keyword" type="text" id="podcast_box_keyword_search_field" value="<?php echo $keyword; ?>" placeholder="<?php _e( 'Enter podcast title',
			'podcast-box' ); ?>">

        <!--country-->
        <select name="country" id="podcast_box_country_search_field" data-placeholder="<?php _e( 'Select country', 'podcast-box' ); ?>">
            <option value=""><?php _e( 'Select Country', 'podcast-box' ); ?></option>
			<?php

			$countries = get_terms( [ 'taxonomy' => 'podcast_country', 'parent' => 0 ] );

			if ( ! empty( $countries ) ) {
				$countries = wp_list_pluck( $countries, 'name', 'term_id' );
				foreach ( $countries as $id => $name ) {
					printf( '<option value="%s" %s >%s</option>', $id, selected( $id, $country_id, false ), $name );
				}
			}

			?>
        </select>

        <!--category-->
        <select name="category" id="podcast_box_category_search_field" data-placeholder="<?php _e( 'Select category', 'podcast-box' ); ?>">
            <option value=""><?php _e( 'Select category', 'podcast-box' ); ?></option>

			<?php
			$categories = get_terms( [ 'taxonomy' => 'podcast_category' ] );

			if ( ! empty( $categories ) ) {
				$categories = wp_list_pluck( $categories, 'name', 'term_id' );
				foreach ( $categories as $id => $name ) {
					printf( '<option value="%1$s" %2$s >%3$s</option>', $id, selected( $id, $category_id, false ), $name );
				}
			}

			?>
        </select>


        <!-- order -->
        <input type="hidden" name="listing_order" id="search_order" value="<?php
		$order = ! empty( $_GET['listing_order'] ) ? sanitize_key( $_GET['listing_order'] ) : 'asc';

		echo $order;
		?>">

        <!--submit-->
        <button type="submit" id="podcast_search_submit" class="station_search">
            <span><?php _e( 'Search', 'podcast-box' ); ?></span>
        </button>

    </form>
</div>