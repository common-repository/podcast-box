;(function ($) {
    const app = {
        init: () => {

            $.podcastSelect2($('#podcast_box_episode_metabox #podcast'));

            /*--- Handle media uploader ---*/
            $('.podcast_box_select_img').on('click', app.handleImageUploader);
            $('.podcast_box_select_file').on('click', app.handleAudioUploader);

            /*--- Delete media ---*/
            $('.podcast_box_delete_img').on('click', app.deleteImage);
            $('.podcast_box_delete_file').on('click', app.deleteFile);

            /*--- Logo Change ---*/
            $('#logo').on('change, paste, keyup', function () {
                $('.logo-metabox-preview').attr('src', $(this).val());
            });

            /*init range slider*/
            app.initSlider();

            /*---Init Datepicker---*/
            $('#podcast_box_episode_metabox #date').datepicker({
                prevText: '',
                nextText: '',
            });

            /*--- Delete Episodes ---*/
            $('.delete-episodes').on('click', app.deleteEpisodes);

        },

        deleteEpisodes: function () {

            const yes = confirm("Are sure want to delete all the episodes of this podcasts?");

            if (!yes) return;

            $(this).find('i').removeClass('dashicons dashicons-trash').addClass('spinner is-active');

            const podcast_id = $(this).attr('data-podcast');

            wp.ajax.send('delete_episodes', {
                data: {
                    podcast_id
                },

                error: error => console.log(error),
                complete: () => {
                    $('.latest-episodes').html('<h4>No Episodes Found</h4>');
                    $('.latest-episodes-footer').remove();
                }
            })

        },

        deleteImage: function (e) {
            e.preventDefault();

            $('#logo').val('').change();
            $('.logo-metabox-preview').attr('src', '');
        },

        deleteFile: function (e) {
            e.preventDefault();

            $('#file').val('');
        },

        initSlider: () => {
            const slider = $('.frequency_interval_slider');
            const value = slider.val();

            slider.slider({
                range: 'min',
                min: 1,
                max: 100,
                value,
                create: function () {
                    const handle = $(".wpmilitary-slider-handle", slider);
                    handle.text($(this).slider("value"));
                },

                slide: function (event, ui) {
                    const handle = $(".wpmilitary-slider-handle", slider);
                    $("input", slider).val(ui.value);
                    handle.text(ui.value);
                }
            });
        },

        handleImageUploader: function (event) {
            event.preventDefault();

            // Create the media frame.
            const file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Insert image',
                library: {
                    type: 'image'
                },
                button: {
                    text: 'Use this image',
                },
                multiple: false
            });

            file_frame.on('select', function () {
                const attachment = file_frame.state().get('selection').first().toJSON();
                $('#logo').val(attachment.url).change();
                $('.logo-metabox-preview').attr('src', attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        },

        handleAudioUploader: function (event) {
            event.preventDefault();

            // Create the media frame.
            const file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select File',
                library: {
                    type: 'audio'
                },
                button: {
                    text: 'Done',
                },
                multiple: false
            });

            file_frame.on('select', function () {
                const attachment = file_frame.state().get('selection').first().toJSON();
                $('#file').val(attachment.url);
            });

            // Finally, open the modal
            file_frame.open();
        },

    };

    $(document).ready(app.init);
})(jQuery);