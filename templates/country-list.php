<?php
/* Block direct access */
defined( 'ABSPATH' ) || exit;

?>

<div class="podcast-box-country-list-toggle">
    <span class="dashicons dashicons-menu-alt"></span> <?php _e( 'Countries', 'podcast-box' ); ?>
</div>

<div class="podcast-box-country-list <?php echo isset( $shortcode ) ? 'shortcode' : ''; ?>">

    <div class="country-list-header <?php echo ! empty( $_REQUEST['keyword'] ) ? 'search' : ''; ?>">
        <div class="title"><?php _e( 'Countries', 'podcast-box' ); ?></div>
    </div>

    <div class="podcast-box-country-search-wrap">
        <i class="dashicons dashicons-search"></i>
        <input class="podcast-box-country-search" placeholder="<?php _e( 'Search country', 'podcast-box' ); ?>" type="text" data-list=".country-list">
    </div>

    <ul class="country-list podcast-box-lazy-load-scroll">
		<?php

		$countries = get_terms( [ 'taxonomy' => 'podcast_country', 'parent' => 0 ] );

		if ( ! empty( $countries ) ) {
			$i = 0;

			foreach ( $countries as $country ) {

				if ( $i < 10 ) {
					$image = podcast_box_get_country_flag($country->slug, 16);
				} else {
					$image = podcast_box_get_country_flag($country->slug, 16, true);
				}

				printf( '<li %s ><a href="%s">%s %s</a></li>', ! empty( $active ) && $country->slug == $active ? 'class="active"' : '', get_term_link( $country->term_id ), $image, $country->name );

				$i ++;

			}//End of the loop

		} else {
			_e( 'No Country added yet!', 'podcast-box' );
		}

		?>
    </ul>

</div>