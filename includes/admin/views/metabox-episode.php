<?php

defined( 'ABSPATH' ) || exit();

global $post_id, $post;

$podcast = podcast_box_get_episode_podcast( $post_id );

$file           = podcast_box_get_meta( $post_id, 'file' );
$duration       = podcast_box_get_meta( $post_id, 'duration' );
$logo           = podcast_box_get_meta( $post_id, 'logo' );
$link           = podcast_box_get_meta( $post_id, 'link' );
$episode_number = podcast_box_get_meta( $post_id, 'episode_number' );
$season_number  = podcast_box_get_meta( $post_id, 'season_number' );

?>

<!--------------- General -------------->
<table class="form-table">

    <tbody>

    <!-- podcast -->
    <tr>
        <th scope="row">
            <label for="podcast"><?php esc_html_e( 'Podcast Show :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <p>
            <select class="regular-text" name="podcast" id="podcast" data-placeholder="<?php echo esc_attr($podcast); ?>">
	            <?php
	            if ( ! empty( $podcast ) ) {
		            printf( '<option value="%s" selected>%s</option>', $podcast, get_the_title( $podcast ) );
	            }
	            ?>
            </select>

		        <?php
		        if ( $podcast ) {
			        printf( '<a href="%s" id="podcast_link" target="_blank" class="button-primary button-small"><i class="dashicons dashicons-external"></i></a>',
				        get_the_permalink( $podcast ) );
		        }
		        ?>

            </p>

            <p class="description">
		        <?php _e( 'Select the podcast show.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- episode file -->
    <tr>
        <th scope="row">
            <label for="file"><?php esc_html_e( 'Episode File :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <div class="logo-metabox-actions">
                <input name="file" type="text" id="file" value="<?php echo esc_url( $file ); ?>" class="regular-text ltr">

                <a href="#" class="button button-primary podcast_box_select_file"><i class="dashicons dashicons-plus-alt"></i></a>
                <a href="#" class="button button-link-delete podcast_box_delete_file <?php echo ! empty( $file ) ? ''
			        : 'hidden'; ?> "><i class="dashicons dashicons-trash"></i></a>
            </div>

            <p class="description">
	            <?php _e( 'Enter the episode audio URL or select an audio file.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- episode duration -->
    <tr>
        <th scope="row">
            <label for="duration"><?php esc_html_e( 'Duration :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="duration" type="number" id="duration" value="<?php echo  esc_attr($duration); ?>" class="ltr">
            <p class="description">
			    <?php _e( 'Enter the episode duration in seconds. This field will be automatically generated once the episode file is selected.',
				    'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- episode logo -->
    <tr>
        <th scope="row">
            <label for="logo"><?php esc_html_e( 'Episode Thumbnail :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <div class="logo-metabox-actions">
                <input type="text" id="logo" name="logo" class="regular-text ltr" value="<?php echo esc_url( $logo ); ?>" placeholder="<?php _e( 'Enter the logo image url or select an image by clicking the plus icon.',
	                'podcast-box' ); ?>">
                <a href="#" class="button button-primary podcast_box_select_img"><i class="dashicons dashicons-plus-alt"></i></a>
                <a href="#" class="button button-link-delete podcast_box_delete_img <?php echo ! empty( $logo ) ? ''
		            : 'hidden'; ?> "><i class="dashicons dashicons-trash"></i></a>
            </div>

            <img src="<?php echo esc_url( $logo ); ?>" class="logo-metabox-preview">

            <p class="description">
	            <?php _e( 'The Episode Thumbnail Image.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- link -->
    <tr>
        <th scope="row">
            <label for="link"><?php esc_html_e( 'Episode Link :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="link" type="text" id="link" value="<?php echo esc_url( $link ); ?>" class="regular-text ltr">
            <p class="description">
	            <?php _e( 'Enter the source URL of the episode.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- episode number -->
    <tr>
        <th scope="row">
            <label for="episode_number"><?php esc_html_e( 'Episode Number :', 'podcast-box' ); ?></label>
        </th>
        <td>

            <input type="number" name="episode_number" id="episode_number" value="<?php echo esc_attr($episode_number); ?>"/>

            <p class="description">
			    <?php _e( 'Enter the episode number.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- season number -->
    <tr>
        <th scope="row">
            <label for="season_number"><?php esc_html_e( 'Season Number :', 'podcast-box' ); ?></label>
        </th>
        <td>

            <input type="number" name="season_number" id="season_number" value="<?php echo esc_attr($season_number); ?>"/>

            <p class="description">
	            <?php _e( 'Enter the season number.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    </tbody>
</table>