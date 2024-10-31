;(function ($) {
    $(document).ready(function () {

        /*sidebar widgets*/
        if (!$('body').hasClass('widgets-php')) {
            return;
        }


        $.podcastSelect2($('.podcast_box_player_widget select'));
        $(document).on('widget-updated', () => {
            $.podcastSelect2($('.podcast_box_player_widget select'));
        });
        $(document).on('widget-added', () => {
            $.podcastSelect2($('.podcast_box_player_widget select'));
        });


        $('[id*="_podcast_box_"] .widget-title  h3').prepend(`<i class="dashicons dashicons-microphone" title="WP Radio Country List Widget"/>`)

    });
})(jQuery);