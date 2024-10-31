<?php

defined( 'ABSPATH' ) || exit;

$total = $query->found_posts;

if ( ! empty( $query->query['tax_query'] ) ) {
	$tax_query = $query->query['tax_query'];

	//check if tax_query has multiple level
	if ( ! empty( $tax_query[0] ) && ! empty( $tax_query[0]['taxonomy'] ) && $tax_query[0]['taxonomy'] == 'podcast_country' ) {

		$term  = is_array( $tax_query[0]['terms'] ) ? $tax_query[0]['terms'][0] : $tax_query[0]['terms'];
		$field = $tax_query[0]['field'];
	}

	if ( ! empty( $tax_query[0][0] ) && ! empty( $tax_query[0][0]['taxonomy'] ) && $tax_query[0][0]['taxonomy'] == 'podcast_country' ) {

		$term  = is_array( $tax_query[0][0]['terms'] ) ? $tax_query[0][0]['terms'][0] : $tax_query[0][0]['terms'];
		$field = $tax_query[0][0]['field'];
	}

	if ( ! empty( $term ) ) {

		if ( 'slug' == $field ) {
			$country_term = get_term_by( 'slug', $term, 'podcast_country', OBJECT );
		} else {
			$country_term = get_term( $term, 'podcast_country', OBJECT );
		}

	}
}

?>

<div class="podcast-box-listing-top">

    <div class="country-info">
		<?php
		if ( ! empty( $country_term ) ) {
			printf( 'Total <span class="count">%s</span> podcasts from %s <span class="country-name">%s</span>', $total,
				podcast_box_get_country_flag( $country_term->slug ), $country_term->name );
		} else {
			printf( 'Total <span class="count">%s</span> podcasts found.', $total );
		}
		?>
    </div>

    <div>
        <label for="podcast_box_change_country"><?php _e( 'Change country', 'podcast-box' ); ?></label>

        <select id="podcast_box_change_country" class="change_country"
                data-placeholder="<?php _e( 'Change Country', 'podcast-box' ); ?>">
            <option value=""><?php _e( 'Select Country', 'podcast-box' ); ?></option>
			<?php

			$country_id = ! empty( $country_term->term_id ) ? $country_term->term_id : '';

			$countries = get_terms( [ 'taxonomy' => 'podcast_country', 'parent' => 0 ] );

			if ( ! empty( $countries ) ) {
				$countries = wp_list_pluck( $countries, 'name', 'term_id' );

				foreach ( $countries as $id => $name ) {
					printf( '<option value="%s" %s >%s</option>', $id, selected( $id, $country_id, false ), $name );
				}
			}

			?>
        </select>

    </div>

</div>