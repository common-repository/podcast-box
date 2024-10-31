;(function ($) {

    const app = {

        init: () => {

            //order
            $(document).on('change', '#episode_sort', app.handlePagination);

            //pagination
            $(document).on('click', '.podcast-box-pagination a, .podcast-box-pagination-form-submit', app.handlePagination);

            // change country
            $(document).on('change', '#podcast_box_change_country', app.handlePagination);

            //handle got-to-page submit
            $(document).on('keydown', '#podcast-box-pagination-form-input', app.handleSubmit);


        },

        handlePagination: function (e) {
            e.preventDefault();

            //listing container
            const listing = $(this).parents('.podcast-box-listings');
            listing.addClass('loading');

            // args
            let args = $('.podcast-box-pagination', listing).attr('data-args');
            args = args ? JSON.parse(args) : {};


            // type
            const type = $('.podcast-box-pagination', listing).attr('data-type');

            //podcast_id
            const podcast_id = $('.podcast-box-pagination', listing).attr('data-podcast_id');

            //order
            const sort = $('#episode_sort', listing).val();

            //country
            let country = $('#podcast_box_change_country', listing).val();
            country = country ? country : '';

            let page = 1;
            if ($(this).hasClass('page-numbers')) {
                const link = $(this).attr('href');
                const match = /paginate=(?<page>\d+)/.exec(link);
                page = match ? match.groups.page : 1;
            } else if ($(this).hasClass('episode_sort')) {
                app.updateURL('sort', sort);
            } else if ($(this).hasClass('change_country')) {
                app.updateURL('country', country);

                if (args.tax_query) {
                    if (typeof args.tax_query[0][0] !== 'undefined') {
                        args.tax_query[0][0].terms = [country];
                    } else if (typeof args.tax_query[0] !== 'undefined') {
                        args.tax_query[0].field = 'term_id';
                        args.tax_query[0].terms = [country];
                    }
                } else {
                    args.tax_query = [];
                    args.tax_query[0] = {
                        taxonomy: 'podcast_country',
                        field: 'term_id',
                        terms: [country],
                    };
                }
            } else {
                page = $('.podcast-box-pagination-form-input').val();
            }

            //update paginate param
            app.updateURL('paginate', page);

            wp.ajax.send('podcast_box_pagination', {

                data: {
                    args,
                    podcast_id,
                    sort,
                    page,
                    type,
                },

                success: (data) => {

                    $('.podcast-box-listing-wrapper', listing).html(data.html);
                    $('.podcast-box-pagination', listing).replaceWith(data.pagination);

                    $('.podcast-box-listing-top', listing).replaceWith(data.listing_top);

                    if (country) {

                        $('#podcast_box_change_country').val(country).select2({
                            allowClear: true,
                            placeholder: function () {
                                $(this).data('placeholder')
                            },

                        });

                    }

                },

                error: error => console.log(error),

                complete: () => {
                    listing.removeClass('loading');

                    $('html,body').animate({scrollTop: listing.offset().top - 50}, 'slow');
                },
            });

        },

        updateURL: (param, value) => {

            //update search action url
            $(`#search_${param}`).val(value);

            function addOrReplaceOrderBy(param, value) {
                const url = window.location.href;

                const stringToAdd = `${param}=` + value;

                const has_param = url.match(/\?./);

                if (window.location.search === "") {
                    return `${url}${has_param ? '&' : '?'}${stringToAdd}`;
                }

                if (window.location.search.indexOf(`${param}=`) === -1) {
                    return `${url}${has_param ? '&' : '?'}${stringToAdd}`;
                }

                const searchParams = window.location.search.substring(1).split("&");

                for (let i = 0; i < searchParams.length; i++) {
                    if (searchParams[i].indexOf(`${param}=`) > -1) {
                        searchParams[i] = `${param}=` + value;
                        break;
                    }
                }

                return url.split("?")[0] + "?" + searchParams.join("&");
            }

            history.pushState('', '', addOrReplaceOrderBy(param, value));

        },

        handleSubmit: (e) => {
            // Enter is pressed
            if (e.keyCode === 13) {
                $('.podcast-box-pagination-form-submit').trigger('click');
            }
        },

    };

    $(document).ready(app.init);

})(jQuery);