<fieldset id="fieldset-videostream-public-theme"><legend><?php echo __('Public Theme'); ?></legend>
    <p><?php echo __('Main of public options can be overridden by the theme.'); ?></p>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_jwplayer_width_public',
                __('Viewer Width')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php echo $this->formText('videostream_jwplayer_width_public', get_option('videostream_jwplayer_width_public'), array('size' => 5)); ?>
            <p class="explanation">
                <?php echo __('Viewer width for css.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_jwplayer_height_public',
                __('Viewer Height')); ?>
        </div>
        <div class='inputs five columns omega'>
            <?php echo $this->formText('videostream_jwplayer_height_public', get_option('videostream_jwplayer_height_public'), array('size' => 5)); ?>
            <p class="explanation">
                <?php echo __('Viewer height for css.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_jwplayer_external_control',
                __('Use External Controls')); ?>
        </div>
        <div class='inputs five columns omega'>
            <p>
                <?php echo $this->formCheckbox('videostream_jwplayer_external_control', true,
                    array('checked' => (boolean) get_option('videostream_jwplayer_external_control'))); ?>
                <?php echo __('Display Specific Segment Using External Controls'); ?>
            </p>
            <p class="explanation">
                <?php echo __('Whether the JW Player plugin should use external controls instead of the builtin player controls.'); ?>
                <?php echo __('You should check this option if you want to restrict access to the specific video segment represented by the item on display.'); ?>
                <?php echo __('This option does not allow the user to scrub beyond the start and stop points for the video segment.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_display_current',
                __('Display Current')); ?>
        </div>
        <div class='inputs five columns omega'>
            <p>
                <?php echo $this->formCheckbox('videostream_display_current', true,
                    array('checked' => (boolean) get_option('videostream_display_current'))); ?>
                <?php echo __('Display information about currently playing video segment'); ?>
            </p>
            <p class="explanation">
                <?php echo __('Whether the JW Player plugin should display information about the current video segment.'); ?>
                <?php echo __('Use this option with or without external controls to see information about the current video segment appear below the video player.'); ?>
                <?php echo __('This information may be different from the current item being displayed because it is based on where you are in the video file.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_jwplayer_autostart',
                __('Autostart Video')); ?>
        </div>
        <div class='inputs five columns omega'>
            <p>
                <?php echo $this->formCheckbox('videostream_jwplayer_autostart', true,
                    array('checked' => (boolean) get_option('videostream_jwplayer_autostart'))); ?>
                <?php echo __('Should the video start playing when the page is loaded'); ?>
            </p>
            <p class="explanation">
                <?php echo __('Whether the JW Player plugin should automatically start playing the current video segment when the page loads.'); ?>
            </p>
        </div>
    </div>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_jwplayer_streaming_protocols',
                __('Streaming Protocols')); ?>
        </div>
        <div class='inputs five columns omega'>
            <ul style="list-style-type: none;">
                <li>
                    <?php echo $this->formCheckbox('videostream_jwplayer_flash_streaming', true,
                        array('checked' => (boolean) get_option('videostream_jwplayer_flash_streaming'))); ?>
                    <?php echo __('Flash Streaming'); ?>
                </li>
                <li>
                    <?php echo $this->formCheckbox('videostream_jwplayer_flash_primary', true,
                        array('checked' => (boolean) get_option('videostream_jwplayer_flash_primary'))); ?>
                    <?php echo __('Use flash if possible'); ?>
                </li>
                <li>
                    <?php echo $this->formCheckbox('videostream_jwplayer_http_streaming', true,
                        array('checked' => (boolean) get_option('videostream_jwplayer_http_streaming'))); ?>
                    <?php echo __('HTTP Streaming'); ?>
                </li>
                <li>
                    <?php echo $this->formCheckbox('videostream_jwplayer_hls_streaming', true,
                        array('checked' => (boolean) get_option('videostream_jwplayer_hls_streaming'))); ?>
                    <?php echo __('HLS (Apple) Streaming'); ?>
                </li>
            </ul>
            <p class="explanation">
                <?php echo __('Decide if you want one or more types of streaming available to your site.'); ?>
                <?php echo __('Each type of streaming is preferred by different browsers.'); ?>
                <?php echo __("For instance if available, Safari prefers Apple's HLS streaming, the only type of video playback that iPad uses."); ?>
                <?php echo __('Also HLS files need to be prepared for streaming using ffmpeg or software from Apple and then uploaded to the proper directories on your web server.'); ?>
                <?php echo __('In addition, which type you use will be determined by which streaming service you have available.'); ?>
                <?php echo __('To use Flash streaming, you need a flash video streaming server such as Adobe Media Server, Wowza or Red5.'); ?>
                <?php echo __('HTTP Streaming uses the file system on your web server.'); ?>
                <?php echo __('If you have a flash streaming server, you may want a browser, even Safari, to use flash if possible.'); ?>
                <?php echo __('Check the Use Flash if possible box to do this.'); ?>
            </p>
        </div>
    </div>
</fieldset>
<fieldset id="fieldset-videostream-admin-theme"><legend><?php echo __('Admin Theme'); ?></legend>
    <div class="field">
        <div class="two columns alpha">
            <?php echo $this->formLabel('videostream_display_tuning',
                __('Segment Tuning Panel')); ?>
        </div>
        <div class='inputs five columns omega'>
            <p>
                <?php echo $this->formCheckbox('videostream_display_tuning', true,
                    array('checked' => (boolean) get_option('videostream_display_tuning'))); ?>
                <?php echo __('Display Segment Tuning Panel'); ?>
            </p>
            <p class="explanation">
                <?php echo __('Whether the Segment Tuning Panel should be available to the user when editing the video segment item in Administration.'); ?>
            </p>
        </div>
    </div>
</fieldset>
