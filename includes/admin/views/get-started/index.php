<div class="wrap">

    <div class="tab-wrap podcast-box-get-started">

        <div class="tab-links">

            <a href="#" data-target="setup" class="tab-link active">
                <i class="dashicons dashicons-admin-tools"></i>
	            <?php _e( 'Plugin Setup', 'podcast-box' ); ?></a>

            <a href="#" data-target="import" class="tab-link">
                <i class="dashicons dashicons-database-import"></i>
	            <?php _e( 'Import Podcasts', 'podcast-box' ); ?></a>

            <a href="#" data-target="shortcodes" class="tab-link">
                <i class="dashicons dashicons-shortcode"></i>
	            <?php _e( 'Shortcodes', 'podcast-box' ); ?></a>

            <a href="#" data-target="widget" class="tab-link">
                <i class="dashicons dashicons-align-wide"></i>
	            <?php _e( 'Sidebar Widgets', 'podcast-box' ); ?></a>


        </div>

        <div id="setup" class="tab-content active">
	        <?php include_once PODCAST_BOX_INCLUDES . '/admin/views/get-started/setup.php'; ?>
        </div>

        <div id="import" class="tab-content">
		    <?php include_once PODCAST_BOX_INCLUDES . '/admin/views/get-started/import.php'; ?>
        </div>

        <div id="shortcodes" class="tab-content">
	        <?php include_once PODCAST_BOX_INCLUDES . '/admin/views/get-started/shortcodes.php'; ?>
        </div>


        <div id="widget" class="tab-content">
		    <?php include_once PODCAST_BOX_INCLUDES . '/admin/views/get-started/widget.php'; ?>
        </div>

    </div>

</div>

<?php if ( isset( $_GET['tab'] ) ) { ?>
    <script>
        ;(function ($) {
            $(document).ready(function () {
                localStorage.setItem('podcast_box_get_started_tab', '<?php echo sanitize_text_field( $_GET['tab'] ); ?>');
            });
        })(jQuery);
    </script>
<?php } ?>