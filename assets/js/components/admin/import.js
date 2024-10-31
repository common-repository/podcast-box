;(function ($) {
    const page = $('.podcast-box-import-page');
    const app = {

        init: () => {

            app.initMultiselect();

            $('.remove-country', page).on('click', app.handleRemove);
            $('.update-country', page).on('click', app.handleUpdate);

            $('#run-import', page).on('click', app.initImporter);

            $('#cancel-import', page).on('click', app.cancelImport);

            page.on('click', '.ms-list li.disabled', app.showPopup);

        },

        showPopup: () => {
            $('.podcast-box-promo').removeClass('hidden');
        },

        cancelImport: function (e) {
            const conf = confirm('Are you sure to cancel the import?');

            if (!conf) {
                e.preventDefault();
            }
        },

        /**
         * Initialize The Importer
         */
        initImporter: function () {

            if ($(this).hasClass('import-done')) {
                window.location.reload();

                return;
            }

            let $countries = [];
            let $total = 0;

            const li = $('.ms-selection .ms-list>.ms-selected').not('.disabled');

            if (null === $countries || li.length < 1) {
                alert(podcastBox.i18n.alert_no_country);
                return;
            }

            $('#run-import').text(podcastBox.i18n.running);

            li.each(function () {
                $total += parseInt($(this).attr('data-count'));
                $countries.push($(this).attr('data-country'));
            });


            $('#import-progress').css('display', 'flex');
            $('.progress-count-all').text($total);

            app.handleImport($countries, $total);

        },

        handleImport: function (countries, $total) {

            wp.ajax.send('podcast_box_import_podcasts', {
                data: {
                    countries,
                },

                success: response => {

                    console.log(response)

                    /**
                     * calculate total
                     * if is update then get the total from found posts
                     * else get the total from country count
                     */
                    const total = $total;

                    // Get imported count from response
                    const $imported = response.imported;

                    // Update the progress bar
                    $('#progress').css('width', (100 / total) * $imported + '%');

                    const percentage = $imported / total * 100;
                    $('.progress-percentage').text(Math.ceil(percentage) + '%');
                    $('.progress-count-number').text($imported);


                    // Import is done
                    if (response.done) {
                        $('.import-progress-content>h3').text('Hooray! Import is Complete.');
                        $('.import-progress-content').addClass('done');
                        $('#progress').removeClass('progress-bar-animated progress-bar-striped');

                        return;
                    } else if (response.error) {
                        $('.import-progress-content>h3').text(':( No Podcast found!.');
                        return;
                    }

                    // Recursive import
                    app.handleImport(countries, $total);

                },

                error: error => console.log(error)

            });
        },

        /**
         * Handle the update country
         * @param e
         */
        handleUpdate: function (e) {
            e.preventDefault();

            $('#import-progress').addClass('update').css('display', 'flex');

            const country = $(this).closest('li').data('country');

            // Handle update country ajax
            app.handleImport([country], 0);

        },

        initMultiselect: function () {

            let imported_countries = podcastBox.imported_countries;

            $('.podcast-box-importer #import-country-select').multiSelect({
                selectableHeader: app.selectableHeader,

                selectableFooter: app.selectableFooter,

                selectionHeader: app.selectionHeader,

                selectionFooter: app.selectableFooter,

                afterSelect: app.handeSelectionCount,

                afterDeselect: app.handeSelectionCount,

                afterInit: () => {

                    app.appendMeta();

                    $('.podcast-box-importer .ms-selectable>.country-search').hideseek({
                        noData: podcastBox.i18n.no_country_found,
                        highlight: true
                    });

                    if (imported_countries.length !== '') {

                        $('.podcast-box-importer .ms-selection .ms-list>.ms-selected').each(function () {
                            const country = $(this).data('country');
                            const needs_update = podcastBox.isPro && podcastBox.needs_update && Object.values(podcastBox.update_countries).indexOf(country) > -1;

                            $(this).addClass('disabled')
                                .append(needs_update && `<a href="#" class="button update-country button-primary"> <i class="dashicons dashicons-update-alt"></i> Update</a>`)
                                .append(`<a href="#" class="button remove-country button-link-delete"> <i class="dashicons dashicons-trash"></i> Delete</a>`);
                        });

                        app.handeSelectionCount();
                    }

                }
            });

        },

        handeSelectionCount: function (elem) {
            const selected = $('.ms-selection .ms-list>.ms-selected', page);

            let $total = 0;
            selected.each(function () {
                $total += parseInt($(this).attr('data-count'));
            });

            $('.selected-count').text(selected.length);

            const $allowed_country = 68;

            $('.remain-count').text($allowed_country - selected.length);
            $('.total-selection-count').text($total);

        },

        /**
         * Append station number to the right
         */
        appendMeta: function () {

            const li = $('.podcast-box-importer .ms-list>li');

            li.each(function () {

                let $meta = `<span class="count" title="${podcastBox.i18n.count_title}">${$(this).attr('data-count')}</span>`;

                const country = $(this).data('country');

                if ($(this).hasClass('disabled')) {
                    $meta = `<span class="premium"> <span class="dashicons dashicons-star-filled"></span> <span class="pro-badge">PRO</span> </span> ${$meta}`;
                }

                $(this).prepend(`<img src="${podcastBox.pluginUrl}/assets/images/flags/${country}.svg" />`);

                $(this).append(`<span class="country-meta">${$meta}</span>`);

            });

            $('.podcast-box-importer .ms-selectable .ms-list>li')
                .append('<a href="javascript:;" class="button select-country" title="Select Country"><i class="dashicons dashicons-database-add"></i> </a>');

        },

        selectableHeader: () => {
            return `<input class="country-search" placeholder="${podcastBox.i18n.select_add_country}" type="text" data-list=".ms-selectable .ms-list" >
            <div class="ms-selectable-header">Available Countries <span>( 68 ) | Total Podcasts: 5200+</span></div>`;
        },

        selectableFooter: () => {
            return podcastBox.isPro ? false : `<div class="ms-selectable-footer"><a href="${podcastBox.pricingPage}" class="button"> ${podcastBox.i18n.get_premium} </a>${podcastBox.i18n.premium_promo}</div>`;
        },

        selectionHeader: () => {
            const $remain = 68;

            return `<div class="ms-selected-header">${podcastBox.i18n.selected_countries} <span> ( ${podcastBox.i18n.selected} <span class="selected-count">0</span> | ${podcastBox.i18n.remaining} <span class="remain-count">${$remain}</span> ) | <span>Total Podcasts <span class="total-selection-count">0</span> </span> </span></div>`;
        },

        handleRemove: function (e) {
            e.preventDefault();

            const $this = $(this);
            const country = $this.parent().attr('data-country');
            const nonce = podcastBox.nonce;

            let foo = confirm('Are you sure you want to remove all the stations of this country?');
            if (foo) {

                $this.text('Deleting...');

                wp.ajax.send('podcast_box_remove_country', {
                    data: {
                        country,
                        nonce
                    },
                    complete: () => $this.parent().remove(),
                    error: error => console.log(error)
                })
            }

        }

    };

    $(document).ready(app.init);

    return app;

})(jQuery);