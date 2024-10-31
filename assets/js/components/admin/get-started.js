;(function ($) {
    $(document).ready(function () {

        const page = $('.podcast-box-get-started');

        /*--- Init Tab ---*/
        let activeTab = localStorage.getItem('podcast_box_get_started_tab');
        activeTab = activeTab ? activeTab : 'setup';


        if (activeTab) {
            $('.tab-links .tab-link, .tab-content').removeClass('active');

            $(`[data-target="${activeTab}"]`).addClass('active');
            $(`#${activeTab}`).addClass('active');

        }

        /*---- Handle tab links -----*/
        page.on('click', '.tab-links .tab-link', function (e) {
            e.preventDefault();

            $('.tab-links .tab-link, .tab-content').removeClass('active');
            $(this).addClass('active');

            const target = $(this).data('target');
            $(`#${target}`).addClass('active');

            localStorage.setItem('podcast_box_get_started_tab', target);

        });


        /*----- Handle FAQ collapse -------*/
        $(`#shortcodes .tab-content-section-title, #faq .tab-content-section-title, .log-wrap h4`, page).on('click', function () {
            $('i', $(this)).toggleClass('dashicons-plus-alt dashicons-minus');
            $(this).next().slideToggle('slow');
        });

    });
})(jQuery);