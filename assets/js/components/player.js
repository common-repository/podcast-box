;(function ($) {

    const app = {

        /**
         * Initialize stuffs
         */
        init: () => {

            app.initPlayer();
            app.handlePopup();
            app.initVolumeSlider();

            $(document).on('click', '.podcast-play-pause, .podcast-box-player-loader', app.handlePlayPause);
            $(document).on('click', '.podcast-box-player-prev, .podcast-box-player-next', app.handlePrevNext);
            $(document).on('click', '.podcast-box-player-toggle', app.togglePlayer);
            $(document).on('input', '.volume-bar-seek', app.handleVolumeSlider);
            $(document).on('input', '.podcast-box-player-progress-seek', app.handleProgress);
            $(document).on('click', '.podcast-box-player-rewind, .podcast-box-player-forward', app.handleRewindForward);
            $(document).on('click', '.open-in-parent', app.openInParent);

        },

        /**
         * Player instance
         */
        player: new MediaElementPlayer('podcast_player_media', {

            success: (mediaElement, originalNode, instance) => {

                // Preload the file
                instance.load();

                let player = instance.container.parents('.podcast-box-player');

                // On error
                $(mediaElement).on('error', () => {
                    $('.podcast-play-pause').removeClass(`active seeking`);
                });

                // On playing
                $(mediaElement).on('playing', () => {

                    app.player.volume = app.getVolume();

                    let playPause = $(`#podcast-play-pause`);

                    const episode = JSON.parse(sessionStorage.getItem('episode'));
                    if (episode) {
                        const episode_id = parseInt(episode.episode_id);
                        const podcast_id = parseInt(episode.podcast_id);

                        playPause = playPause.add(`#episode-${episode_id} .podcast-play-pause`);
                        playPause = playPause.add(`#episode-play-${episode_id}`);
                        playPause = playPause.add(`#podcast-box-shortcode-player-${podcast_id} .podcast-play-pause`);
                    }


                    $('.podcast-play-pause').removeClass('active seeking');

                    playPause.removeClass('seeking pause').addClass('active');
                });

                //On pause
                $(mediaElement).on('pause', () => {

                    $('.podcast-play-pause').removeClass(`active seeking`);

                    let playPause = $(`#podcast-play-pause`);

                    const episode = JSON.parse(sessionStorage.getItem('episode'));
                    if (episode) {
                        const episode_id = parseInt(episode.episode_id);
                        const podcast_id = parseInt(episode.podcast_id);

                        playPause = playPause.add(`#episode-${episode_id} .podcast-play-pause`);
                        playPause = playPause.add(`#episode-play-${episode_id}`);
                        playPause = playPause.add(`#podcast-box-shortcode-player-${podcast_id} .podcast-play-pause`);
                    }

                    playPause.addClass('pause');

                });

                //On seeking
                $(mediaElement).on('seeking', () => {

                    let playPause = $(this).add(`#podcast-play-pause`);

                    const episode = JSON.parse(sessionStorage.getItem('episode'));
                    if (episode) {
                        const episode_id = parseInt(episode.episode_id);
                        const podcast_id = parseInt(episode.podcast_id);

                        playPause = playPause.add(`#play-${episode_id}`);
                        playPause = playPause.add(`#podcast-box-shortcode-player-${podcast_id} .podcast-play-pause`);
                    }

                    if (playPause.hasClass('active')) {
                        playPause.removeClass(`active`).addClass(`seeking`);
                    }
                });

                //On ended
                $(mediaElement).on('ended', () => {

                    $('.podcast-play-pause').removeClass('seeking pause active');
                });

                //loadedMetaData
                $(mediaElement).on('loadedmetadata', () => {
                    const {duration} = instance;

                    //set duration
                    $('.podcast-box-player-duration', player).text(duration.toHHMMSS());
                });

                //update progressbar
                $(mediaElement).on('timeupdate', () => {

                    const {duration, currentTime} = instance;
                    const percentage = currentTime / duration * 100;

                    const episode = JSON.parse(sessionStorage.getItem('episode'));
                    if (episode) {
                        const podcast_id = parseInt(episode.podcast_id);
                        player = player.add(`#podcast-box-shortcode-player-${podcast_id}`);
                    }

                    //update progressbar
                    $('.podcast-box-player-progress-seek, .podcast-box-player-progress-bar', player).val(!isNaN(percentage) ? percentage : 0);

                    //update time
                    $('.podcast-box-player-time', player).text(new Date(currentTime * 1000).toISOString().substr(14, 5));

                    sessionStorage.setItem('podcast_player_last_time', currentTime);

                });

            },

        }),

        handleProgress: function () {
            const player_id = $('#podcast-box-player').find('.mejs-container').attr('id');
            const player = mejs.players[player_id];

            const val = $(this).val();
            const bar = $(this).next();

            bar.val(val);

            player.currentTime = val * (player.duration / 100);
            player.play();

            $(this).blur();
        },

        play: () => {
            app.player.muted = false;
            app.player.play();
        },

        stop: () => {
            app.player.pause();
        },

        /**
         * Initialize the player
         */
        initPlayer: () => {

            const isPopupWindow = podcastBox.isPopupWindow;

            const episode = isPopupWindow ? JSON.parse($('.podcast-box-player.popup .podcast-play-pause ').attr('data-episode')) : JSON.parse(sessionStorage.getItem('episode'));

            /*show-hide the player on initialize*/
            const showHide = 'show' === sessionStorage.getItem('podcast_player_display') && episode ? 'show' : 'hide';

            const isPlay = 'true' === sessionStorage.getItem('podcast_player_playing');

            app.togglePlayer(isPlay, showHide, true);

            if (!episode) return;

            app.setPlayerData(episode);


            //play if was playing or popup window
            if (isPlay) {

                let last_time = sessionStorage.getItem('podcast_player_last_time');
                last_time = last_time ? parseFloat(last_time) : 0;

                $(`#podcast-play-pause, playe#episode-${episode.episode_id} .podcast-play-pause`).addClass('active');
                app.player.setCurrentTime(last_time);
            }

            if (isPlay || isPopupWindow) {
                app.play();
            }

        },

        /**
         * Set the episode data to player components
         *
         * @param episode
         */
        setPlayerData: (episode) => {

            const player = $('#podcast-box-player');

            if (typeof (episode) === 'undefined') return;

            const {episode_id, podcast_id, media, episode_url, episode_title, episode_logo, podcast_url, podcast_title, podcast_logo} = episode;

            const shortcodePlayer = $(`#podcast-box-shortcode-player-${podcast_id}`);

            //set player src
            const instance_id = player.find('.mejs-container').attr('id');
            const instance = mejs.players[instance_id];

            instance.setSrc(media);

            //Set fixed player data
            $('.podcast-box-player-url', player).attr({'href': episode_url, 'title': episode_title});
            $('.podcast-box-player-thumbnail', player).attr('src', episode_logo);
            $('.podcast-box-player-title', player).text(episode_title);

            $('.episode-podcast-url', player).attr({'href': podcast_url, 'title': podcast_title});
            $('.episode-podcast-thumbnail', player).attr('src', podcast_logo);
            $('.episode-podcast-title', player).text(podcast_title);

            // Set shortcode player data
            if (shortcodePlayer.length) {

                $('.podcast-box-player-url', shortcodePlayer).attr({'href': episode_url, 'title': episode_title});
                $('.podcast-box-player-thumbnail', shortcodePlayer).attr('src', episode_logo);
                $('.podcast-box-player-title', shortcodePlayer).text(episode_title);

                $('.episode-podcast-url', shortcodePlayer).attr({'href': podcast_url, 'title': podcast_title});
                $('.episode-podcast-thumbnail', shortcodePlayer).attr('src', podcast_logo);
                $('.episode-podcast-title', shortcodePlayer).text(podcast_title);
            }

            // set data-episode attribute
            $('#podcast-play-pause').attr({'data-episode': JSON.stringify(episode), 'title': 'Pause'});

            // set session episode
            sessionStorage.setItem('episode', JSON.stringify(episode));

        },

        /**
         * Handle player play pause
         */
        handlePlayPause: function (e) {
            e.preventDefault();

            //stop playing
            app.stop();

            // return if popup player
            if ($(this).hasClass('popup-play')) return;

            if ($(this).hasClass('pause')) {
                app.play();
                return;
            }

            let episode = $(this).data('episode');

            //may play
            if (!$(this).hasClass(`active`)) {

                //remove active/ seeking class from all
                $('.podcast-play-pause').removeClass('active seeking');

                let playPause = $(`#podcast-play-pause`);
                if (episode) {
                    const episode_id = parseInt(episode.episode_id);
                    const podcast_id = parseInt(episode.podcast_id);

                    playPause = playPause.add(`#episode-${episode_id} .podcast-play-pause`);
                    playPause = playPause.add(`#episode-play-${episode_id}`);
                    playPause = playPause.add(`#podcast-box-shortcode-player-${podcast_id} .podcast-play-pause`);
                }

                playPause.addClass('seeking');

                app.togglePlayer(true);
                $('#podcast-box-player').removeClass('init-hide hide');

                app.setPlayerData(episode);
                app.play();

                sessionStorage.setItem('podcast_player_playing', 'true');

            } else {

                $('.podcast-play-pause').removeClass('active');

                app.stop();
                sessionStorage.setItem('podcast_player_playing', 'false');
            }

        },

        handlePopup: function (episode_id = false) {

            const isPopupWindow = 'popupPlayer' === window.name;

            if (isPopupWindow) return;

            function openPopup(e) {
                e.preventDefault();

                app.stop();

                $('#podcast-box-player').addClass('hide');
                $('.podcast-play-pause').removeClass('active seeking');

                sessionStorage.setItem('podcast_player__playing', 'false');
                sessionStorage.setItem('podcast_player_display', 'hide');


                const episode_id = episode_id ? episode_id : JSON.parse($(this).attr('data-episode')).episode_id;

                window.open(`${podcastBox.siteUrl}/?podcast_player=${episode_id}`, 'popupPlayer', `width=${podcastBox.popup_width},height=${podcastBox.popup_height}`);

            }

            if (episode_id) {
                openPopup();
            }

            $(document).on('click', '.popup-play', openPopup);

        },

        /**
         * Handle player previous/ next
         */
        handlePrevNext: function () {
            app.stop();

            const selector = $(this);
            const parent = $(this).parents('.podcast-box-player');

            const currentPlayPause = $('.podcast-play-pause', parent);

            let currentId = JSON.parse(currentPlayPause.attr('data-episode')).episode_id;

            if ('undefined' === currentId) {
                if ('undefined' !== typeof sessionStorage.episode) {
                    currentId = JSON.parse(sessionStorage.episode).episode_id;
                }
            }

            const prevNext = selector.hasClass('podcast-box-player-prev') ? 'prev' : 'next';

            wp.ajax.send('podcast_box_player_next_prev', {
                beforeSend: () => {
                    setTimeout(() => currentPlayPause.removeClass('active pause').addClass('seeking'), 100);
                },

                data: {
                    currentId,
                    prevNext,
                    nonce: podcastBox.nonce
                },

                success: (data) => {
                    data = JSON.parse(data);
                    currentPlayPause.attr({'data-episode': JSON.stringify(data)});
                    app.setPlayerData(data);
                    app.play();
                },

                error: error => console.log(error),

                complete: () => {
                    currentPlayPause.removeClass('seeking');
                },

            });

        },

        handleRewindForward: function () {
            const player_id = $('#podcast-box-player').find('.mejs-container').attr('id');
            const player = mejs.players[player_id];

            const {currentTime} = player;

            const rewind = 10;
            const forward = 15;

            player.currentTime = $(this).hasClass('podcast-box-player-rewind') ? currentTime - rewind : currentTime + forward;

            player.play();

        },

        initVolumeSlider: () => {

            $(document).on('click', '.podcast-box-player .volume-icon', function () {
                $('.podcast-box-player-volume').toggleClass('active');
            });

            $('.volume-bar-seek, .volume-bar-slide').val(app.getVolume());
        },

        openInParent: function (e) {
            e.preventDefault();
            const url = $(this).hasClass('open-popup') ? $(this).attr('data-url') : $(this).attr('href');

            parent.window.open(url);
        },

        /**
         * hanlde volume slider
         */
        handleVolumeSlider: function () {
            const val = $(this).val();

            $('.volume-bar-seek, .volume-bar-slide').val(val);
            app.player.volume = val;

            localStorage.setItem('podcast_player_volume', val);
        },

        getVolume: () => {
            const saved = localStorage.getItem('podcast_player_volume');
            if (saved && 'NaN' != saved) {
                return parseFloat(saved);
            }

            return parseFloat(podcastBox.volume) / 100;
        },

        /**
         * Toggle the footer fixed radio player
         *
         * @param $play
         * @param $init
         * @param isInit //Check if it is the initial loading
         */
        togglePlayer: function ($play = false, $init = 'hide', isInit = false) {

            const player = $('#podcast-box-player');

            if (player.hasClass('hide')) {
                return;
            }

            if (player.hasClass('collapsed') || 'show' === $init) {
                player.removeClass('collapsed');
                $('body').css('margin-bottom', player.outerHeight());
                $(this).attr('title', 'Hide');
                sessionStorage.setItem('podcast_player_display', 'show');
            } else {

                if ($play === true) return;

                if (isInit) {
                    player.addClass('init-hide');
                }

                player.addClass('collapsed');
                $('body').css('margin-bottom', '0');
                $(this).attr('title', 'Show');
                sessionStorage.setItem('podcast_player_display', 'hide');
            }

        },

    };

    $(document).ready(app.init);

})(jQuery);

//Add seconds to time format function to the number prototype
Number.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10); // don't forget the second param
    var hours = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours < 10) {
        hours = "0" + hours;
    }
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
    return hours + ':' + minutes + ':' + seconds;
}