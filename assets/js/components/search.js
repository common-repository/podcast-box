;(function ($) {
    $.podcastSelect2 = element => {
        element.select2({
            allowClear: true,
            minimumInputLength: 1,
            closeOnSelect: true,
            placeholder: () => $(this).data('placeholder'),
            tags: [],
            ajax: {
                url: podcastBox.ajaxUrl,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,

                data: term => ({
                    term: term,
                    podcast_id: $('#podcast_search').data('podcast_id'),
                    action: `${$('#podcast_search').data('type') ? $('#podcast_search').data('type') : 'podcast'}_search`,
                }),

                processResults: data => {
                    return {results: data}
                },

                cache: true

            },

            templateResult: (data) => {
                const {image, text, country, link} = data;

                return $(`
                        <div class="search-result"  data-link="${typeof link !== 'undefined' ? link : ''}">
                            
                            ${typeof image !== 'undefined' ? '<div class="search-img"><img src="' + image + '" alt="${text}"></div>' : ''}
                            
                                <div class="search-info">
                                <h4>${text}</h4>
                                
                                <span class="country">${typeof country !== 'undefined' ? country : ''}</span>
                                
                            </div>
                        </div>
                     `);
            },

        });
    };

    $(document).ready(function () {

        //Open search result
        $(document).on('click', '.search-result[data-link]', function (e) {
            e.preventDefault();

            if ($('#podcast_search').length) {
                const link = $(this).data('link');
                if (link) {
                    window.location = link;
                }
            }

            if ($('#podcast').length) {
                const link = $(this).data('link');
                if (link) {
                    $('#podcast_link').attr('href', link);
                }
            }


        });

        $('.podcast-box-search>.search_toggle').on('click', function () {
            $(this).next().toggleClass('active');
        });

        $.podcastSelect2($('#podcast_search #keyword'));

        // Change country select2
        $('#podcast_box_change_country, #podcast_box_country_search_field, #podcast_box_category_search_field').select2({
            allowClear: true,
            placeholder: function () {
                $(this).data('placeholder')
            },

        });

    });
})(jQuery);