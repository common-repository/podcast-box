<?php

$is_hidden = isset( $is_hidden ) ? $is_hidden : true;

$transient_key  = 'podcast_box_promo_time';
$countdown_time = get_transient( $transient_key );

if ( ! $countdown_time ) {

	$date = date( 'Y-m-d-H-i', strtotime( '+ 14 hours' ) );

	$date_parts = explode( '-', $date );

	$countdown_time = [
		'year'   => $date_parts[0],
		'month'  => $date_parts[1],
		'day'    => $date_parts[2],
		'hour'   => $date_parts[3],
		'minute' => $date_parts[4],
	];

	set_transient( $transient_key, $countdown_time, 14 * HOUR_IN_SECONDS );

}

$title = __('Unlock the PRO features', 'podcast-box');

?>

<style>
    .syotimer {
        text-align: center;
        margin: 30px auto 0;
        padding: 0 0 10px;
    }

    .syotimer-cell {
        display: inline-block;
        margin: 0 5px;
        width: 60px;
        background: url(<?php echo PODCAST_BOX_ASSETS.'/images/timer.png'; ?>) no-repeat;
        background-size: contain;
    }

    .syotimer-cell__value {
        font-size: 24px;
        height: 60px;
        line-height: 2;
        margin: 0 0 5px;
        color: orangered;

    }

    .syotimer-cell__unit {
        font-family: Arial, serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #F8D83B;
    }
</style>
<div class="podcast-box-promo <?php echo $is_hidden ? 'hidden' : ''; ?>">
    <div class="podcast-box-promo-inner">

		<?php if ( $is_hidden ) { ?>
            <span class="close-promo">&times;</span>
		<?php } ?>


        <img src="<?php echo PODCAST_BOX_ASSETS . '/images/crown.svg'; ?>" class="promo-img">

        <h3><?php echo $title; ?></h3>
        <h3 class="discount-text"><?php _e('50% OFF', 'podcast-box'); ?></h3>
        <h3 style="font-size: 18px;"><?php _e('LIMITED TIME ONLY', 'podcast-box'); ?></h3>
        <div class="simple_timer"></div>
        <a href="<?php echo PODCAST_BOX_PRICING; ?>"><?php _e('GET PRO', 'podcast-box'); ?></a>

    </div>
</div>

<script>
    (function ($) {
        $(document).ready(function () {

            $(document).on('click', '.close-promo', function () {
                $(this).closest('.podcast-box-promo').addClass('hidden');
            });

            $('.podcast-box-promo').on('click', function (e) {
                if (e.target !== this) {
                    return;
                }

                $(this).addClass('hidden');
            });

            if (typeof window.timer_set === 'undefined') {
                window.timer_set = $('.simple_timer').syotimer({
                    year: <?php echo $countdown_time['year']; ?>,
                    month: <?php echo $countdown_time['month']; ?>,
                    day: <?php echo $countdown_time['day']; ?>,
                    hour: <?php echo $countdown_time['hour']; ?>,
                    minute: <?php echo $countdown_time['minute']; ?>
                });
            }
        })
    })(jQuery);
</script>