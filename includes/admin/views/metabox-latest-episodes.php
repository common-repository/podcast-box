<?php

global $post_id;

$episode_ids   = podcast_box_get_episode_ids( $post_id );
$episode_count = count( $episode_ids );

?>
    <div class="latest-episodes">
		<?php
		$episode_ids = array_slice( $episode_ids, 0, 10 );

		if ( ! empty( $episode_ids ) ) {
			foreach ( $episode_ids as $episode_id ) {
				printf( '<a href="%s">%s</a>', get_permalink( $episode_id ), get_the_title( $episode_id ) );
			}
		} else {
			printf( '<h4>No Episodes Found.</h4>' );
		}

		?>
    </div>

<?php if ( ! empty( $episode_ids ) ) { ?>
    <div class="latest-episodes-footer">
        <span class="total-count"><b>Total Episodes: </b> <?php echo $episode_count; ?></span>

        <span class="delete-episodes button button-link-delete" data-podcast="<?php echo $post_id ?>">
	    <i class="dashicons dashicons-trash"></i>
	    Delete All</span>
    </div>
<?php } ?>