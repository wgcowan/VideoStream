<?php
$elementIds = json_decode(get_option('videostream_elements_ids'), true);
?>
<div class="field">
    <div id="videosegmentation_form" class="two columns alpha" >
        <input type="hidden" name="videomeka_slider_start" id="videomeka_slider_start" />
        <input type="hidden" name="videomeka_slider_end" id="videomeka_slider_end" />
    </div>
<?php // Query("input[]") on array, button click must wrap in document ready. ?>
    <div id="vid_player" style="margin:0 auto; width: 100%;">
        <div id="jwplayer_plugin" style="margin:0 auto;"><?php echo __('Player failed to load...'); ?></div>
    </div>
    <script type="text/javascript">
        var startTime= calculateTime(<?php echo json_encode($segment_start); ?>);
        var endTime = calculateTime(<?php echo json_encode($segment_end); ?>);

        jwplayer("jwplayer_plugin").setup({
            playlist:  [{
                sources: <?php echo $sources; ?>
            }],
            <?php if (get_option('videostream_jwplayer_flash_primary')): ?>
            primary: "flash",
            <?php endif; ?>
            autostart: false,
            controls: true,
            width: "95%",
            height: <?php echo json_encode(get_option('videostream_jwplayer_height_public')); ?>
        });

        jwplayer("jwplayer_plugin").onReady(function() {
            jwplayer("jwplayer_plugin").seek(calculateTime(<?php echo json_encode($segment_start); ?>));
            jQuery("#CurrentPos").val(getFormattedTimeString(<?php echo json_encode($segment_end); ?>));
            jwplayer("jwplayer_plugin").pause();
        });

        jwplayer("jwplayer_plugin").onTime(function() {
            jQuery("#CurrentPos").val(getFormattedTimeString(jwplayer("jwplayer_plugin").getPosition()));
        });
    </script>
    <p>
        <label for="CurrentPos" style="float:left;"><?php echo __('Current position:'); ?></label>
        <input type="text" id="CurrentPos" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " />
        <pauseButton>&#62;&#47;&#61;</pauseButton>
    </p>
    <p>
        <label for="SegmentStart" style="float:left;"><?php echo __('Segment start:'); ?></label>
        <input type="text" id="SegmentStart" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " />
        <startButton><?php echo __('Set'); ?></startButton>
    </p>
    <p>
        <label for="SegmentEnd" style="float:left;"><?php echo __('Segment End:'); ?></label>
        <input type="text" id="SegmentEnd" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " />
        <endButton><?php echo __('Set'); ?></endButton>
    </p>
    <div class="inputs five columns omega">
        <div class="input-block">
            <label><?php echo __('Description:'); ?></label>
            <textarea id="videomeka_description" name="videomeka_description"></textarea>
            <saveButton><?php echo __('Update'); ?></saveButton>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    var v_desc = jQuery("#Elements-<?php echo $elementIds['Dublin Core:Description']; ?>-0-text").val();

                    jQuery("#SegmentStart").val(getFormattedTimeString(startTime));
                    jQuery("#SegmentEnd").val(getFormattedTimeString(endTime));
                    jQuery("pauseButton").button();
                    jQuery("startButton").button();
                    jQuery("endButton").button();
                    jQuery("saveButton").button();
                    jQuery("#videomeka_description").val(v_desc);
                    jQuery("pauseButton").click(function() {
                        jwplayer("jwplayer_plugin").pause();
                    });
                    jQuery("startButton").click(function() {
                        jQuery("#SegmentStart").val(getFormattedTimeString(jwplayer().getPosition()));
                        jQuery("#Elements-<?php echo $elementIds['Streaming Video:Segment Start']; ?>-0-text").val(jQuery("#SegmentStart").val());
                    });
                    jQuery("endButton").click(function() {
                        jQuery("#SegmentEnd").val(getFormattedTimeString(jwplayer().getPosition()));
                        jQuery("#Elements-<?php echo $elementIds['Streaming Video:Segment End']; ?>-0-text").val(jQuery("#SegmentEnd").val());
                    });
                    jQuery("saveButton").click(function() {
                        if (jQuery("#videomeka_description").val().length == 0) {
                            var desc = jQuery("#Elements-<?php echo $elementIds['Dublin Core:Description']; ?>-0-text").val();
                        } else {
                            var desc = jQuery("#videomeka_description").val();
                        }
                        jQuery("#Elements-<?php echo $elementIds['Dublin Core:Description']; ?>-0-text").val(desc);
                        jQuery("#Elements-<?php echo $elementIds['Streaming Video:Segment Start']; ?>-0-text").val(jQuery("#SegmentStart").val());
                        jQuery("#Elements-<?php echo $elementIds['Streaming Video:Segment End']; ?>-0-text").val(jQuery("#SegmentEnd").val());
                    });
                });
            </script>
        </div>
    </div>
</div>
