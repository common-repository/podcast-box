<?php
$is_pro = true;
?>

<h3 class="tab-content-title">Sidebar Widgets</h3>

<p>The plugin provides two widgets for displaying the podcast player and another one is for displaying the podcasts country list.</p>

<div class="tab-content-section">

    <h4 class="tab-content-section-title">Display the podcast player in sidebar by widget <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?></h4>

    <p>For displaying the podcast player by the widget, you have to navigate to <code>Appearance > Widgets</code>.
        Then select the <b>Podcast Player</b> widget and drag to the sidebar where you want to display the podcast player.</p>

    <img src="<?php echo PODCAST_BOX_ASSETS . '/images/get-started/podcast-player-widget.png' ?>" alt="Add Podcast Player Widget">
    <span class="img-caption"><?php _e( 'Podcast player sidebar widget', 'podcast-box' ); ?></span>


    <p>After selecting the podcast player, you have to enter the widget title and select the podcast that will be played by the player.
        You need to select the podcast by searching in the podcast field.
    </p>
    <p>Then the output of the podcast player widget will be like below.</p>

    <img src="<?php echo PODCAST_BOX_ASSETS . '/images/get-started/podcast-player-widget-output.png' ?>" alt="Podcast Player Widget">
    <span class="img-caption"><?php _e( 'Podcast player sidebar widget output', 'podcast-box' ); ?></span>


</div>

<div class="tab-content-section">

    <h4 class="tab-content-section-title">Display the podcasts country list in sidebar by widget <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?></h4>

    <p>For displaying the podcasts countries in a list by the widget, you have to navigate to <code>Appearance > Widgets</code>.
        Then select the <b>Podcast Country List</b> widget and drag to the sidebar where you want to display the country list.</p>

    <img src="<?php echo PODCAST_BOX_ASSETS . '/images/get-started/country-list-widget.png' ?>" alt="Country List Widget">
    <span class="img-caption"><?php _e('Country list sidebar widget', 'podcast-box'); ?></span>

    <p>You just need to enter the widget tile. The widget will render the country list in the frontend.</p>

    <p>The output of the country list widget will be like below.</p>
    <img src="<?php echo PODCAST_BOX_ASSETS . '/images/get-started/country-list-widget-output.png' ?>" alt="Country List Widget">
    <span class="img-caption"><?php _e('Country list sidebar widget output', 'podcast-box'); ?></span>

</div>

