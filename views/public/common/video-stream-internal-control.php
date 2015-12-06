<?php if ($segment_start): ?>
<div id="vid_player" style="width:100%; margin:0 auto;">
    <div id="jwplayer_plugin" style="margin:0 auto;"><?php echo __('Player failed to load...'); ?></div>
</div>
<?php endif; ?>
<script type="text/javascript">
    var is_play = true;
    var startTime= calculateTime(<?php echo json_encode($segment_start); ?>);
    var endTime = calculateTime(<?php echo json_encode($segment_end); ?>);

    jwplayer("jwplayer_plugin").setup({
        playlist:  [{
            sources: [
                <?php echo $sources; ?>
            ]
        }],
        <?php if (get_option('videostream_jwplayer_flash_primary')): ?>
        primary: "flash",
        <?php endif;?>
        autostart: false,
        width: "95%",
        height: <?php echo get_option('videostream_jwplayer_height_public'); ?>
    });

    jwplayer("jwplayer_plugin").onReady(function() {
        jwplayer("jwplayer_plugin").seek(startTime);
        <?php if (get_option('videostream_jwplayer_autostart') == 0): ?>
        jwplayer("jwplayer_plugin").pause();
        <?php endif; ?>
    });
</script>
