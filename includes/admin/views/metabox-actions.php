<?php
global $post_id;

$import_interval        = podcast_box_get_meta( $post_id, 'import_interval' );

?>


<div class="submitbox podcast_box_actions_metabox" id="submitpost">

    <div class="form-field text-input">
        <label for="import_interval">Import Interval:</label>
        <select name="import_interval" id="import_interval">
            <option value="daily" <?php selected('daily', $import_interval); ?>>Daily</option>
            <option value="weekly" <?php selected('weekly', $import_interval); ?>>Weekly</option>
        </select>

        <p class="description">Run the episodes importer for this podcast in Daily/ Weekly base.</b></p>
    </div>

    <!--    <div class="form-field text-input">-->
    <!--        <label for="frequency_interval">Import Frequency:</label>-->
    <!---->
    <!--        <div class="wpmilitary-slider frequency_interval_slider">-->
    <!--            <input type="hidden" id="frequency_interval" name="frequency_interval" value="3"/>-->
    <!--            <div class="wpmilitary-slider-handle ui-slider-handle"></div>-->
    <!--        </div>-->
    <!--    </div>-->

    <div id="major-publishing-actions">
		<?php
		$post_id = (int) $post->ID;

		do_action( 'post_submitbox_start', $post );
		?>
        <div id="delete-action">
			<?php
			if ( current_user_can( 'delete_post', $post_id ) ) {
				if ( ! EMPTY_TRASH_DAYS ) {
					$delete_text = __( 'Delete' );
				} else {
					$delete_text = __( 'Trash' );
				}
				?>
                <a class="submitdelete deletion" href="<?php echo get_delete_post_link( $post_id ); ?>"><?php echo esc_html($delete_text); ?></a>
				<?php
			}
			?>
        </div>

        <div id="run-action">
		    <?php
		    if ( ! empty( podcast_box_get_meta( $post_id, 'feed_url' ) ) ) { ?>
                <a class="button button-secondary" href="<?php echo add_query_arg( [ 'action'  => 'podcast_box_import_now',
			                                                                         'post_id' => $post_id,
			    ], admin_url() ); ?>">
                    Import Now
                </a>
		    <?php }
		    ?>
        </div>

        <div id="publishing-action">
            <span class="spinner"></span>

            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ); ?>"/>
			<?php submit_button( __( 'Update' ), 'primary large', 'save', false, array( 'id' => 'publish' ) ); ?>

        </div>


        <div class="clear"></div>
    </div>
</div>