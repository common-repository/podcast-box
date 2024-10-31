<?php

defined( 'ABSPATH' ) || exit();

global $post_id, $post;

$feed_url        = podcast_box_get_meta( $post_id, 'feed_url' );
$logo            = podcast_box_get_meta( $post_id, 'logo' );
$publisher_name  = podcast_box_get_meta( $post_id, 'publisher_name' );
$publisher_email = podcast_box_get_meta( $post_id, 'publisher_email' );
$website         = podcast_box_get_meta( $post_id, 'website' );
$itunes_url      = podcast_box_get_meta( $post_id, 'itunes_url' );
$type            = podcast_box_get_meta( $post_id, 'type' );
$language        = podcast_box_get_meta( $post_id, 'language' );

?>

<!--------------- General -------------->
<table class="form-table">

    <tbody>

    <!-- feed URL-->
    <tr>
        <th scope="row">
            <label for="feed_url"><?php esc_html_e( 'Feed URL :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="feed_url" type="text" id="feed_url" value="<?php echo esc_url( $feed_url ); ?>" class="regular-text ltr">
            <p class="description">
				<?php _e( 'Enter the URL of the podcast feed.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- podcast logo -->
    <tr>
        <th scope="row">
            <label for="logo"><?php esc_html_e( 'Podcast Logo :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <div class="logo-metabox-actions">
                <input type="text" id="logo" name="logo" class="regular-text ltr" value="<?php echo esc_url( $logo ); ?>" placeholder="<?php _e( 'Enter the logo image url or select an image by clicking the plus icon.', 'podcast-box' ); ?>">
                <a href="#" class="button button-primary podcast_box_select_img"><i class="dashicons dashicons-plus-alt"></i></a>
                <a href="#" class="button button-link-delete podcast_box_delete_img <?php echo ! empty( $logo ) ? '' : 'hidden'; ?> "><i class="dashicons dashicons-trash"></i></a>
            </div>

            <img src="<?php echo esc_url( $logo ); ?>" class="logo-metabox-preview">

            <p class="description">
				<?php _e( 'The podcast logo.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>


    <!-- publisher name-->
    <tr>
        <th scope="row">
            <label for="publisher_name"><?php esc_html_e( 'Publisher Name :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="publisher_name" type="text" id="publisher_name" value="<?php echo esc_attr( $publisher_name ); ?>" class="regular-text ltr">
            <p class="description">
				<?php _e( 'Enter the name of the podcast publisher..', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- publisher email-->
    <tr>
        <th scope="row">
            <label for="publisher_email"><?php esc_html_e( 'Publisher Email :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="publisher_email" type="email" id="publisher_email" value="<?php echo esc_attr( $publisher_email ); ?>" class="regular-text ltr">
            <p class="description">
				<?php _e( 'Enter the email of the podcast publisher..', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- website-->
    <tr>
        <th scope="row">
            <label for="website"><?php esc_html_e( 'Podcast Website :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="website" type="text" id="website" value="<?php echo esc_url( $website ); ?>" class="regular-text ltr">
            <p class="description">
				<?php _e( 'Enter the podcast official website URL.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    <!-- itunes url-->
    <tr>
        <th scope="row">
            <label for="itunes_url"><?php esc_html_e( 'Itunes URL :', 'podcast-box' ); ?></label>
        </th>
        <td>
            <input name="itunes_url" type="text" id="itunes_url" value="<?php echo esc_url( $itunes_url ); ?>" class="regular-text ltr">
            <p class="description">
				<?php _e( 'Enter the podcast official itunes url.', 'podcast-box' ) ?>
            </p>
        </td>
    </tr>

    </tbody>
</table>