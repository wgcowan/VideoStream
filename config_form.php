<h3>Public Theme</h3>
<label style="font-weight:bold;" for="jwplayer_width_public">Viewer width, in pixels:</label>
<p><?php echo get_view()->formText('jwplayer_width_public', 
                              get_option('jwplayer_width_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="jwplayer_height_public">Viewer height, in pixels:</label>
<p><?php echo get_view()->formText('jwplayer_height_public', 
                              get_option('jwplayer_height_public'), 
                              array('size' => 5));?></p>
<label style="font-weight:bold;" for="jwplayer_external_control">Display Specific Segment Using External Controls</label>
<ul style="list-style-type:none;">
<li>Use External Controls?&nbsp<?php
echo get_view()->formCheckbox('jwplayer_external_control', null, array('checked' => get_option('jwplayer_external_control')));?></li>
</ul>
<p class="explanation">Whether the Jwplayer plugin should use external controls instead of the builtin player controls. You should check this option if you want to restrict access to the specific video segment represented by the item on display. This option does not allow the user to scrub beyond the start and stop points for the video segment</p>
<label style="font-weight:bold;" for="jwplayer_display_current">Display information about currently playing video segment</label>
<ul style="list-style-type:none;">
<li>Display Current?&nbsp<?php 
echo get_view()->formCheckbox('jwplayer_display_current',null, array('checked' => (get_option('jwplayer_display_current'))));?></li>
</ul>
<p class="explanation">Whether the Jwplayer plugin should display information about the current video segment. Use this option with or without external controls to see information about the current video segment appear below the video player. This information may be different from the current item being displayed because it is based on where you are in the video file.</p>
<label style="font-weight:bold;" for="jwplayer_autostart">Should the video start playing when the page is loaded?</label>
<ul style="list-style-type:none;">
<li>Autostart Video?&nbsp<?php
echo get_view()->formCheckbox('jwplayer_autostart',null, array('checked' => (get_option('jwplayer_autostart'))));?></li>
</ul>
<p class="explanation">Whether the Jwplayer plugin should automatically start playing the current video segment when the page loads.</p>
<label style="font-weight:bold;" for="jwplayer_flash_streaming">Which Streaming Protocol</label>
<ul style="list-style-type:none;">
<li>Flash Streaming?&nbsp<?php 
echo get_view()->formCheckbox('jwplayer_flash_streaming',null, array('checked' => (get_option('jwplayer_flash_streaming'))));?>&nbspUse flash if possible&nbsp<?php
echo get_view()->formCheckbox('jwplayer_flash_primary',null, array('checked' => (get_option('jwplayer_flash_primary'))&&(get_option('jwplayer_flash_streaming'))));?></li>
<li>HTTP Streaming?&nbsp<?php 
echo get_view()->formCheckbox('jwplayer_http_streaming',null, array('checked' => (get_option('jwplayer_http_streaming'))));?></li>
<li>HLS (Apple) Streaming?&nbsp<?php 
echo get_view()->formCheckbox('jwplayer_hls_streaming',null, array('checked' => (get_option('jwplayer_hls_streaming'))));?></li>
</ul>
<p class="explanation">Decide if you want one or more types of streaming available to your site. Each type of streaming is preferred by different browsers. For instance if available, Safari prefers Apple's HLS streaming, the only type of video playback that iPad uses. Also HLS files need to be prepared for streaming using ffmpeg or software from Apple and then uploaded to the proper directories on your web server. In addition, which type you use will be determined by which streaming service you have available. To use Flash streaming, you need a flash video streaming server such as Adobe Media Server, Wowza or Red5. HTTP Streaming uses the file system on your web server. If you have a flash streaming server, you may want a browser, even Safari, to use flash if possible. Check the Use Flash if possible box to do this.</p> 
<label style="font-weight:bold;" for="jwplayer_tuning">Should the user be able to use the Segment Tuning Panel in Omeka Admin?</label>
<ul style="list-style-type:none;">
<li>Turn off Segment Tuning Panel?&nbsp<?php
echo get_view()->formCheckbox('jwplayer_tuning',null, array('checked' => (get_option('jwplayer_tuning'))));?></li>
</ul>
<p class="explanation">Whether the Segment Tuning Panel should be available when editing the video segment item in Administration.</p>
