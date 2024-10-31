;(function ($) {
    $(document).ready(function () {
        
        $('.podcast-box-lazy-load').lazy({
            appendScroll: $('.podcast-box-lazy-load-scroll')
        });

        //hide-seek
        if ($('.podcast-box-country-search').length) {
            $('.podcast-box-country-search').hideseek({
                noData: 'No country found!',
                highlight: true
            });
        }

        $('.podcast-box-country-list-toggle').click(function () {
            const list = $('.podcast-box-country-list');
            $(this).find('.dashicons').toggleClass('dashicons-no');

            list.toggle();
        });
    });
})(jQuery);