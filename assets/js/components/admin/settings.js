;(function ($) {

    const page = $('.podcast-box-settings-page');

    const app = {
        init: () => {

            if (!podcastBox.isPro) {
                app.blockSettings();
            } else {
                app.checkListingViewDeps();
                $('.listing_view input', page).on('change', app.checkListingViewDeps);

                app.checkListingThumbDeps();
                $('.listing_thumbnail_size input', page).on('change', app.checkListingThumbDeps);

                app.checkPlayerThumbDeps();
                $('.player_thumbnail_size input', page).on('change', app.checkPlayerThumbDeps);

                //popup player size dependencies
                app.checkPopupPlayerSizeDeps();
                $('.player_type select', page).on('change', app.checkPopupPlayerSizeDeps);
                $('.customize_popup_player input', page).on('change', app.checkPopupPlayerSizeDeps);

                // search style deps
                app.checkSearchStyleDeps();
                $('.show_search input', page).on('change', app.checkSearchStyleDeps);
            }

        },

        blockSettings: () => {

            let elements = $(`.cron,
            .listing_view, 
            .grid_column, 
            .ip_listing, 
            .episode_download,
            .you_may_like,
            .listing_thumbnail_size,
                .listing_thumbnail_width,
                .listing_thumbnail_height,
                .player_thumbnail_size,
                .player_thumbnail_width,
                .player_thumbnail_height,
                .player_type,
                .customize_popup_player,
                .popup_player_width,
                .popup_player_height,
                .listing_bg_color,
                .listing_btn_color,
                .player_bg_color,
                .player_btn_color
            `, page);

            elements.addClass('settings-disabled');

            elements = elements.add('.wp-picker-container');
            elements.click(function (e) {
                e.preventDefault();

                $('.podcast-box-promo').removeClass('hidden');
            });

        },

        checkPopupPlayerSizeDeps: () => {

            const customized = $('.customize_popup_player input').is(':checked');

            if (customized) {
                $('.popup_player_width, .popup_player_height').css('display', 'revert');
            } else {
                $('.popup_player_width, .popup_player_height').css('display', 'none');
            }

            const val = $('.player_type select').val();
            if ('popup' === val && customized) {
                $('.customize_popup_player, .popup_player_width, .popup_player_height').css('display', 'revert');
            } else if ('popup' === val && !customized) {
                $('.customize_popup_player').css('display', 'revert');
                $(' .popup_player_width, .popup_player_height').css('display', 'none');
            } else {
                $('.customize_popup_player, .popup_player_width, .popup_player_height').css('display', 'none');
            }

        },

        checkSearchStyleDeps: () => {

            const val = $('.show_search input:checked').val();

            if ('on' === val) {
                $('.search_style').css('display', 'revert');
            } else {
                $('.search_style').css('display', 'none');
            }
        },

        checkListingViewDeps: () => {

            const val = $('.listing_view input:checked').val();

            if ('grid' === val) {
                $('.grid_column').css('display', 'revert');
                $('.listing_content, .latest_episode').css('display', 'none');
            } else {
                $('.grid_column').css('display', 'none');
                $('.listing_content, .latest_episode').css('display', 'revertsettings');
            }
        },

        checkListingThumbDeps: () => {

            const val = $('.listing_thumbnail_size input:checked').val();

            if ('custom' === val) {
                $('.listing_thumbnail_width, .listing_thumbnail_height').css('display', 'revert');
            } else {
                $('.listing_thumbnail_width, .listing_thumbnail_height').css('display', 'none');
            }
        },

        checkPlayerThumbDeps: () => {

            const val = $('.player_thumbnail_size input:checked').val();

            if ('custom' === val) {
                $('.player_thumbnail_width, .player_thumbnail_height').css('display', 'revert');
            } else {
                $('.player_thumbnail_width, .player_thumbnail_height').css('display', 'none');
            }
        },

    };

    $(document).ready(app.init);
})(jQuery);