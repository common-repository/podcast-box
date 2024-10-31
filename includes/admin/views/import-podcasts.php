<?php

defined( 'ABSPATH' ) || exit;
$selected = get_option( 'podcast_box_imported_countries' );
$selected = array_filter( (array) $selected );
?>

<div class="wrap podcast-box-import-page">

    <div class="page-heading">
        <h1 class="wp-heading-inline"><?php 
_e( 'Import Podcasts', 'podcast-box' );
?></h1>
    </div>

    <div class="import-instructions">
        <h4>Import Instructions:</h4>
        <p>❈ Select the countries from the left country list, which you want to import.</p>
        <p>❈ After selecting any country, the country will move to the right box where all the imported countries will be listed.</p>
        <p>❈ If any error occurred during import, Please reload the page and try again with the previous selected countries.</p>

	    <?php 
//show update instruction
if ( count( (array) get_option( 'wp_radio_update_countries' ) ) > 1 ) {
    printf( '<p><strong>❈ To update the imported countries, click the update button of the country from the imported countries box.</strong></p>' );
}
?>

    </div>

    <div class="podcast-box-importer">
        <select multiple="multiple" id="import-country-select" name="import-country-select[]">
		    <?php 
$i = 0;
foreach ( podcast_box_podcast_map() as $key => $option ) {
    printf(
        '<option value="%1$s" data-country="%1$s" data-count="%2$s" %4$s>%3$s</option>',
        $key,
        $option['count'],
        $option['label'],
        ( in_array( $key, $selected ) ? 'selected' : '' )
    );
    $i++;
}
?>
        </select>

        <div class="import-actions">
            <a href="javascript:void(0)" class="run-import button button-primary button-hero" id="run-import">
                    <i class="dashicons dashicons-database-import"></i> <?php 
_e( 'Start Import', 'podcast-box' );
?>
                </a>
            </div>

        <div class="import-progress" id="import-progress">
            <div class="import-progress-content">
                <h3> Please wait, This may take several minutes.</h3>
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress" style="width:0.2%">
                    </div>
                </div>
                <div class="progress-status">
                    <span class="progress-percentage">0%</span>
                    <span class="progress-count">
                        <span class="progress-count-number">0</span>/
                        <span class="progress-count-all">0</span>
                    </span>

                    <div class="new-added"><strong>New Added:</strong> <span class="new-added-count">0</span> Podcasts</div>
                    <span class="updated"><strong>Updated:</strong> <span class="updated-count">0</span> Podcasts</span>
                </div>
                <div class="progress-actions">

                    <a href="<?php 
echo  admin_url( 'edit.php?post_type=podcast' ) ;
?>"
                       class="button button-primary"
                       id="import-done"><?php 
_e( 'See all Podcasts', 'podcast-box' );
?></a>

                    <a href="<?php 
echo  admin_url( 'edit.php?post_type=podcast&page=import-podcasts' ) ;
?>"
                       class="button button-secondary"
                       id="import-more"><?php 
_e( 'Import More', 'podcast-box' );
?></a>

                    <a href="<?php 
echo  admin_url( 'edit.php?post_type=podcast&page=import-podcasts' ) ;
?>"
                       class="button button-large button-link-delete" id="cancel-import">Cancel Import</a>

                </div>
            </div>
        </div>

    </div>

	<?php 
$is_hidden = false;
include_once PODCAST_BOX_INCLUDES . '/admin/views/promo.php';
?>

</div>
