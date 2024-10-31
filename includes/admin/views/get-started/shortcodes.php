<?php
$is_pro = true;
?>

<h3 class="tab-content-title"><?php _e( 'Shortcodes', 'podcast-box' ) ?></h3>

<p><?php _e( 'This plugin provides the following shortcodes:', 'podcast-box' ) ?></p>

<div class="tab-content-section">
    <h4 class="tab-content-section-title">Podcast box listing - <code>[podcast_box_listing]</code>
        <i class="dashicons dashicons-plus-alt"></i>
    </h4>

    <p><code>[podcast_box_listing]</code> - For displaying the podcasts listing use
        <strong>[podcast_box_listing]</strong> shortcode. This shortcode supports two filter attributes, those are <code>country</code>
        & <code>categoiry</code>. Both are optional.
        <br>
        <br>
        In the <b>country</b> attribute, you can pass comma separated country codes to filter the listing.
        <br>
        <br>
        In the <b>category</b> attribute, you can pass comma separated category name (slug) to filter the listing.
        <br>
        <br>
        <b>Example: </b> <code>[podcast_box_listing country="us, ru, in, bd" category="music,news,reality"]</code>

    </p>

</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title">Podcast player shortcode - <code>[podcast_box_player]</code>
        <i class="dashicons dashicons-plus-alt"></i></h4>

    <p><code>[podcast_box_player]</code> – For displaying the podcast player use <b>[podcast_box_player]</b> shortcode.
        <br>
        <br>
        This shortcode supports an optional <code>id</code> attribute where you have to pass the id of a podcast as
        default podcast of the player.
        <br>
        <br>
        <b>Example:</b> <code>[podcast_box_player id="11"]</code>
    </p>

</div>

<div class="tab-content-section">
    <h4 class="tab-content-section-title">Country list shortcode - <code>[podcast_box_country_list]</code> <?php echo ! $is_pro
			? '<span class="badge">PRO</span>' : ''; ?> <i class="dashicons dashicons-plus-alt"></i></h4>
    <p><code>[podcast_box_country_list]</code> – Use this short code for displaying the all country list of the podcasts.
        <br>
        <br>
        This shortcode has no attribute attribute.
        <br>
        <br>
        <b>Example:</b> <code>[podcast_box_country_list]</code>
    </p>
</div>

