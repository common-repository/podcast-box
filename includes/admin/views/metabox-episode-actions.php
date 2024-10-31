<div class="submitbox podcast_box_actions_metabox" id="submitpost">


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

        <div id="publishing-action">
            <span class="spinner"></span>

            <input name="original_publish" type="hidden" id="original_publish" value="<?php esc_attr_e( 'Update' ); ?>"/>
			<?php submit_button( __( 'Update' ), 'primary large', 'save', false, array( 'id' => 'publish' ) ); ?>

        </div>
        <div class="clear"></div>
    </div>
</div>