<?php
if ($segment_start):
    echo js_tag('jwplayer', 'javascripts/jwplayer');
    echo js_tag('pfUtils', 'javascripts');
    echo js_tag('jquery', 'javascripts/jwplayer');
    echo js_tag('jquery-ui-1.10.3.custom', 'javascripts/jwplayer');
?>
<div id="vid_player">
    <div id="jwplayer_plugin"><?php echo __('Player failed to load...'); ?></div>
    <div id="vidcontrols">
        <ul class="vidControlsLayout" style="width='<?php echo get_option('videostream_jwplayer_width_public');?>'">
            <li id="start_img"><img src="<?php echo img('pause.png'); ?>" title="Start/Stop" class="btnPlay" /></li>
            <li id="playback-display"><span class="current">0:00:00</span></li>
            <li class="progressBar"></li>
            <li id="slider-display"><span class="duration">0:00:00</span> </li>
            <li id="vol_img"><img class="muted" src="<?php echo img('volume_speaker.png'); ?>" /></a></li>
            <li class="volumeBar"></li>
        </ul>
    </div>
    <script type="text/javascript">
        var is_play = true;
        var startTime= calculateTime(<?php echo json_encode($segment_start); ?>);
        var endTime = calculateTime(<?php echo json_encode($segment_end); ?>);

        jwplayer("jwplayer_plugin").setup({
            playlist: [{
                sources: <?php echo $sources; ?>
            }],
            <?php if (get_option('videostream_jwplayer_flash_primary')): ?>
            primary: "flash",
            <?php endif; ?>
            autostart: false,
            controls: false,
            width: "100%",
            height: <?php echo json_encode(get_option('videostream_jwplayer_height_public')); ?>
        });

        jwplayer("jwplayer_plugin").onReady(function() {
            jQuery('.current').text(getFormattedTimeString(startTime));
            jQuery('.duration').text(getFormattedTimeString(endTime));
            jwplayer("jwplayer_plugin").seek(startTime);
            <?php if (get_option('videostream_jwplayer_autostart') == 0): ?>
            jwplayer("jwplayer_plugin").pause();
            <?php endif; ?>
        });

        jQuery( ".progressBar" ).slider({
            min: startTime,
            max: endTime,
            range: "max",
            slide: function(event, ui) {
                jwplayer().seek(ui.value);
            },
            change: function(event,ui) {
                if (jwplayer().getPosition() > endTime) {
                    jwplayer().seek(startTime);
                }
            }
        });

        jQuery( ".volumeBar" ).slider({
            min: 0,
            max: 100,
            range: "max",
            slide: function(event, ui) {
                jwplayer().setVolume(ui.value);
            },
            change: function(event,ui) {
                jwplayer().setVolume(ui.value);
            }
        });

        jwplayer("jwplayer_plugin").onTime(function(event) {
            jQuery(".progressBar").slider("value", jwplayer("jwplayer_plugin").getPosition());
            jQuery('.current').text(getFormattedTimeString(jwplayer("jwplayer_plugin").getPosition()));
        });

        jwplayer("jwplayer_plugin").onPlay(function() {
            jQuery('.btnPlay').attr("src", "<?php echo img('pause.png'); ?>");
        });

        jwplayer("jwplayer_plugin").onPause(function() {
            jQuery('.btnPlay').attr("src", "<?php echo img('play.png'); ?>");
        });

        jwplayer("jwplayer_plugin").onMute(function(event) {
            if (event.mute) {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker_mute.png'); ?>");
            } else {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker.png'); ?>");
            }
        });

        jwplayer("jwplayer_plugin").onVolume(function(event) {
            if (event.volume <= 0 ) {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker_mute.png'); ?>");
            } else {
                jQuery('.muted').attr("src", "<?php echo img('volume_speaker.png'); ?>");
            }
        });

        jQuery('.btnPlay').on('click', function() {
            if (jwplayer().getPosition() > endTime) {
                jwplayer().seek(startTime);
            }
            jwplayer().play();
            return false;
        });

        jQuery('.btnStop').on('click', function() {
            jwplayer().stop();
            jwplayer().seek(startTime);
            jQuery(".progressBar").slider("value", jwplayer().getPosition());
            jQuery('.current').text(getFormattedTimeString(jwplayer().getPosition()));
            return false;
        });

        jQuery('.muted').click(function() {
            jwplayer().setMute();
            return false;
        });

        jQuery('#vid_player')[0].onmouseover = (function() {
            var onmousestop = function() {
                jQuery('#vidcontrols').css('display', 'none');
            }, thread;

            return function() {
                jQuery('#vidcontrols').css('display', 'block');
                clearTimeout(thread);
                thread = setTimeout(onmousestop, 3000);
            };
        })();

        jQuery('#vid_player')[0].onmousedown = (function() {
            var moveend = function() {
                jQuery('#vidcontrols').css('display', 'none');
            }, thread;

            return function() {
                jQuery('#vidcontrols').css('display', 'block');
                clearTimeout(thread);
                thread = setTimeout(moveend, 3000);
            };
        })();
    </script>
</div>
<?php endif;
