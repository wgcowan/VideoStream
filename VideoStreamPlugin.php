<?php
if (!defined('VIDEOSTREAM_PLUGIN_DIR')) {
    define('VIDEOSTREAM_PLUGIN_DIR', dirname(__FILE__));
}
//add_plugin_hook('public_head', 'jwplayer_public_head');
//add_plugin_hook('admin_head', 'jwplayer_admin_head');
 
require_once VIDEOSTREAM_PLUGIN_DIR . '/VideoStreamPlugin.php';
//require_once VIDEOSTREAM_PLUGIN_DIR . '/functions.php';
//$videostreamPlugin = new VideoStreamPlugin;
//$videostreamPlugin->setUp();

class VideoStreamPlugin extends Omeka_Plugin_AbstractPlugin
{
    const DEFAULT_VIEWER_WIDTH = 640;
    const DEFAULT_VIEWER_HEIGHT = 480;
    const DEFAULT_VIEWER_CONTROL = 0;
    const DEFAULT_VIEWER_DISPLAY = 0;
    const DEFAULT_VIEWER_SKIN = 'basic';
    const DEFAULT_VIEWER_FLASH = 0;
    const DEFAULT_VIEWER_HTTP = 0;
    const DEFAULT_VIEWER_HLS = 0;
    const DEFAULT_VIEWER_PRIMARY = 0;
    const DEFAULT_VIEWER_AUTOSTART = 0;
    const DEFAULT_VIEWER_TUNING = 0;
    
    protected $_hooks = array('install',
    'uninstall',
    'config_form',
    'config',
    'public_items_show',
	'public_head',
	'admin_head'
    );
	
	protected $_filters = array(
		'admin_items_form_tabs',
	);
        
    public function hookInstall()
    {
        set_option('jwplayer_width_public', VideoStreamPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('jwplayer_height_public', VideoStreamPlugin::DEFAULT_VIEWER_HEIGHT);
        set_option('jwplayer_external_control', VideoStreamPlugin::DEFAULT_VIEWER_CONTROL);
        set_option('jwplayer_display_current', VideoStreamPlugin::DEFAULT_VIEWER_DISPLAY);
        set_option('jwplayer_external_skin', VideoStreamPlugin::DEFAULT_VIEWER_SKIN);
        set_option('jwplayer_flash_streaming', VideoStreamPlugin::DEFAULT_VIEWER_FLASH);
        set_option('jwplayer_http_streaming', VideoStreamPlugin::DEFAULT_VIEWER_HTTP);
        set_option('jwplayer_hls_streaming', VideoStreamPlugin::DEFAULT_VIEWER_HLS);
        set_option('jwplayer_flash_primary', VideoStreamPlugin::DEFAULT_VIEWER_PRIMARY);
        set_option('jwplayer_autostart', VideoStreamPlugin::DEFAULT_VIEWER_AUTOSTART);
        set_option('jwplayer_tuning', VideoStreamPlugin::DEFAULT_VIEWER_TUNING);

        $db = get_db();

	// Don't install if an element set named "Streaming Video" already exists.
  if ($db->getTable('ElementSet')->findByName('Streaming Video')) {
          throw new Exception('An element set by the name "Streaming Video" already exists. You must delete that '
                         . 'element set to install this plugin.');
}

		$elementSetMetadata = array(
			'record_type'        => "Item", 
			'name'        => "Streaming Video", 
			'description' => "Elements needed for streaming video for the VideoStream Plugin"
		);
		$elements = array(
			array(
				'name'           => "Video Filename",
				'description'    => "Actual filename of the video on the video source server"
			), 
			array(
				'name'           => "Video Streaming URL",
				'description'    => "Actual URL of the streaming server without the filename"
			), 
			array(
				'name'           => "Video Type",
				'description'    => "Encoding for the video; mp4, flv, mov, and so forth"
			), 
			array(
				'name'           => "HLS Streaming Directory",
				'description'    => "Directory location on your server for the HLS .m3u8 file."
			), 
			array(
				'name'           => "HLS Video Filename",
				'description'    => "Filename for HLS video file. Include any subdirectories."
			), 
			array(
				'name'           => "HTTP Streaming Directory",
				'description'    => "Directory location for files to HTTP stream directly from Web Server."
			), 
			array(
				'name'           => "HTTP Video Filename",
				'description'    => "Actual filename of the video on the web server"
			), 
			array(
				'name'           => "Segment Start",
				'description'    => "Start point in video in either seconds or hh:mm:ss"
			), 
			array(
				'name'           => "Segment End",
				'description'    => "End point in video in either seconds or hh:mm:ss"
			), 
			array(
				'name'           => "Segment Type",
				'description'    => "Use segment type to help determine how segment is to be displayed. For instance, an event may encompass many scenes, etc."
			), 
			array(
				'name'           => "Show Item",
				'description'    => "Should item be shown in a list. Can be useful in cetain types of displays where you may not want to have all items shown."
			), 
			array(
				'name'           => "Video Source",
				'description'    => "Source of video. Streaming server, YouTube, etc."
			) 
			// etc.
		);
	insert_element_set($elementSetMetadata, $elements);
    }
    
    public  function hookUninstall()
    {
        delete_option('jwplayer_width_public');
        delete_option('jwplayer_height_public');
        delete_option('jwplayer_external_control');
        delete_option('jwplayer_display_current');
        delete_option('jwplayer_external_skin');
        delete_option('jwplayer_flash_streaming');
        delete_option('jwplayer_http_streaming');
        delete_option('jwplayer_hls_streaming');
        delete_option('jwplayer_flash_primary');
        delete_option('jwplayer_autostart');
        delete_option('jwplayer_tuning');
       if ($elementSet = get_db()->getTable('ElementSet')->findByName("Streaming Video")) {
            $elementSet->delete();
        }
    }
	
    /**
* Appends a warning message to the uninstall confirmation page.
*/
    public static function admin_append_to_plugin_uninstall_message()
    {
        echo '<p><strong>Warning</strong>: This will permanently delete the Streaming Video element set and all its associated metadata. You may deactivate this plugin if you do not want to lose data.</p>';
    }	
    
    public function hookConfigForm()
    {
        include 'config_form.php';
    }
    
    public function hookConfig()
    {
        if (!is_numeric($_POST['jwplayer_width_public']) ||
        !is_numeric($_POST['jwplayer_height_public'])) {
            throw new Omeka_Validator_Exception('The width and height must be numeric.');
        }
        set_option('jwplayer_width_public', $_POST['jwplayer_width_public']);
        set_option('jwplayer_height_public', $_POST['jwplayer_height_public']);
        set_option('jwplayer_external_control', $_POST['jwplayer_external_control']);
        set_option('jwplayer_external_skin', $_POST['jwplayer_external_skin']);
        set_option('jwplayer_display_current', $_POST['jwplayer_display_current']);
        set_option('jwplayer_flash_streaming', $_POST['jwplayer_flash_streaming']);
        set_option('jwplayer_http_streaming', $_POST['jwplayer_http_streaming']);
        set_option('jwplayer_hls_streaming', $_POST['jwplayer_hls_streaming']);
        set_option('jwplayer_flash_primary', $_POST['jwplayer_flash_primary']);
        set_option('jwplayer_autostart', $_POST['jwplayer_autostart']);
        set_option('jwplayer_tuning', $_POST['jwplayer_tuning']);
    }

	public function hookPublicHead($args)
	{?>
            <?php echo queue_css_file("vidStyle");?>
            <?php echo queue_css_file("jquery-ui-1.10.3.custom");?>
            <?php echo js_tag('jwplayer');?>
            <?php echo js_tag('pfUtils');?>
            <?php echo js_tag('jquery');?>
            <?php echo js_tag('jquery-ui-1.10.3.custom');
		}

		public function hookAdminHead($args)
		{?>
	        <?php echo queue_css_file("jquery-ui-1.10.3.custom");?>
	        <?php echo js_tag('jwplayer');?>
	        <?php echo js_tag('pfUtils');?>
	        <?php echo js_tag('jquery');?>
	        <?php echo js_tag('jquery-ui-1.10.3.custom');		
			}


    public function hookPublicItemsShow($args)
    {
	
        $this->append($args);
    }

    
    public function append($args)
    {
        ?>
        <?php if (get_option('jwplayer_external_control')) {
            ?>
        <?php if (metadata('item',array('Streaming Video','Segment Start'))) {
            ?>
            <div id="vid_player">
             <div id="jwplayer_plugin">Player failed to load...  </div>
			 <div id="vidcontrols">
	            <ul class="vidControlsLayout" style="width='<?php echo get_option("jwplayer_width_public");?>'">
                    <li id="start_img"><img src="<?php echo img('pause.png'); ?>"  title="Start/Stop" class="btnPlay"/></li>	            	
		            <li id="playback-display"><span class="current">0:00:00</span></li>
					<li class="progressBar"></li>   
		            <li id="slider-display"><span class="duration">0:00:00</span> </li>
		            <li id="vol_img"><img class="muted" src="<?php echo img('volume_speaker.png'); ?>" /></a></li>
	                <li class="volumeBar"></li>		
	            </ul>
	         </div>

        <script type="text/javascript">
        var is_play = true;

		var startTime= calculateTime("<?php echo metadata('item', array('Streaming Video','Segment Start'));?>");

		var endTime = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>");
		jwplayer("jwplayer_plugin").setup({
		playlist:  [{
		sources: [
		<?php if(get_option('jwplayer_hls_streaming')){?>
		{
		file: '<?php echo metadata('item',array("Streaming Video","HLS Streaming Directory"));?><?php echo metadata('item',array("Streaming Video","HLS Video Filename"));?>' },
		<?php }?>
		<?php if(get_option('jwplayer_flash_streaming')){?>
		{
		file: '<?php echo metadata("item",array ("Streaming Video","Video Streaming URL"));?><?php echo metadata("item",array("Streaming Video","Video Type"));?><?php echo metadata('item',array("Streaming Video","Video Filename"));?>'} ,
		<?php }?>
		<?php if(get_option('jwplayer_http_streaming')){?>
		{
		file: '<?php echo metadata("item",array("Streaming Video","HTTP Streaming Directory"));?><?php echo metadata("item",array("Streaming Video","HTTP Video Filename"));?>'},
		<?php }?>
		]
		}
		],
		<?php if(get_option('jwplayer_flash_primary')){?>
		primary: "flash",
		<?php }?>
		autostart: false,
		controls: false,
		width: "100%",
		height: "<?php echo get_option('jwplayer_height_public');?>",
		}
		);
		jwplayer("jwplayer_plugin").onReady(function(){
				jQuery('.current').text(getFormattedTimeString(startTime));
				jQuery('.duration').text(getFormattedTimeString(endTime));
				jwplayer("jwplayer_plugin").seek(startTime);
                            <?php if(get_option('jwplayer_autostart')==0){?>
                                jwplayer("jwplayer_plugin").pause();
                            <?php }?>
				}
				);
		jQuery( ".progressBar" ).slider(
						{
		min: startTime,
		max: endTime,
		range: "max",
		slide: function(event, ui) {
		jwplayer().seek(ui.value);
		},
		change: function(event,ui){
		if (jwplayer().getPosition() > endTime){
		jwplayer().seek(startTime);
		}
		}
		});
		jQuery( ".volumeBar" ).slider(
				{
		min: 0,
		max: 100,
		range: "max",
		slide: function(event, ui) {
		jwplayer().setVolume(ui.value);		
		},
		change: function(event,ui){
		jwplayer().setVolume(ui.value);		
		}
		});
		jwplayer("jwplayer_plugin").onTime(function(event){
			jQuery(".progressBar").slider("value", jwplayer("jwplayer_plugin").getPosition());
			jQuery('.current').text(getFormattedTimeString(jwplayer("jwplayer_plugin").getPosition()));
				});
		jwplayer("jwplayer_plugin").onPlay(function(){
			jQuery('.btnPlay').attr("src","<?php echo img('pause.png'); ?>");
		});
		jwplayer("jwplayer_plugin").onPause(function(){
			jQuery('.btnPlay').attr("src","<?php echo img('play.png'); ?>");
		});
		jwplayer("jwplayer_plugin").onMute(function(event){
				if (event.mute){
			jQuery('.muted').attr("src","<?php echo img('volume_speaker_mute.png'); ?>");
		}else{
			jQuery('.muted').attr("src","<?php echo img('volume_speaker.png'); ?>");
		}
		}
				);
		jwplayer("jwplayer_plugin").onVolume(function(event){
				if (event.volume <= 0 ){
			jQuery('.muted').attr("src","<?php echo img('volume_speaker_mute.png'); ?>");
			
		}else{
			jQuery('.muted').attr("src","<?php echo img('volume_speaker.png'); ?>");
			
		}
		}
				);
		jQuery('.btnPlay').on('click', function() {
			   if (jwplayer().getPosition() > endTime){
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
        <?php } ; ?>
		<?php
	} else {
	//use jwplayer control
	?>
		<?php if ($bText = metadata('item',array('Streaming Video','Segment Start'))) {
			?>
				<div id="vid_player" style="width:100%; margin:0 auto;">
				    <div id="jwplayer_plugin" style="margin:0 auto;">Player failed to load...  </div>
				</div>
		<?php } ; ?>
        <script type="text/javascript">
        var is_play = true;

	    var startTime= calculateTime("<?php echo metadata('item', array('Streaming Video','Segment Start'));?>");

	    var endTime = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>");
	    jwplayer("jwplayer_plugin").setup({
		playlist:  [{
		sources: [
		<?php if(get_option('jwplayer_hls_streaming')){?>
		{
		file: '<?php echo metadata('item',array("Streaming Video","HLS Streaming Directory"));?><?php echo metadata('item',array("Streaming Video","HLS Video Filename"));?>' },
		<?php }?>
		<?php if(get_option('jwplayer_flash_streaming')){?>
		{
		file: '<?php echo metadata("item",array ("Streaming Video","Video Streaming URL"));?><?php echo metadata("item",array("Streaming Video","Video Type"));?><?php echo metadata('item',array("Streaming Video","Video Filename"));?>'} ,
		<?php }?>

		<?php if(get_option('jwplayer_http_streaming')){?>
		{
		file: '<?php echo metadata("item",array("Streaming Video","HTTP Streaming Directory"));?><?php echo metadata("item",array("Streaming Video","HTTP Video Filename"));?>'},
		<?php }?>
		]
		}
		],
		<?php if(get_option('jwplayer_flash_primary')){?>
		primary: "flash",
		<?php }?>
		autostart: false,
		width: "95%",
		height: <?php echo get_option('jwplayer_height_public'); ?>
		}
		);
		jwplayer("jwplayer_plugin").onReady(function(){
				jwplayer("jwplayer_plugin").seek(startTime);
                            <?php if(get_option('jwplayer_autostart')==0){?>
                                jwplayer("jwplayer_plugin").pause();
                            <?php }?>
		}
		);
		<?php   } ?>
        </script>
            <?php if (get_option('jwplayer_display_current')) {
                ?>
                
                <?php $orig_item=get_current_record('item');
				$orig_video = metadata("item", array("Streaming Video","Video Filename"));
                ?>
                <?php $items=get_records('item',array('collection'=>metadata('item','collection id'),'sort_field'=>'Streaming Video,Segment Start'),null);
                ?>
                
                <?php set_loop_records('items', $items);
                if (has_loop_records('items')) {
                    $items = get_loop_records('items');
                }
                ?>
                <?php foreach(loop('items') as $item): ?>
                <?php if ((metadata('item',array('Streaming Video','Segment Type'))=='Scene') && (metadata('item',array('Streaming Video','Video Filename'))== $orig_video)){
                    ?>
                    <div class="scene" id="<?php echo metadata('item',array('Streaming Video','Segment Start'));
                    ?>" title="<?php echo metadata('item',array('Streaming Video','Segment End'));
                    ?>" style="display:none;">
                    <h2>Current video segment:</h2>
                    <h3><?php echo link_to_item(metadata('item',array('Dublin Core','Title')));
                    ?></h3>
                    <div style="overflow:auto; max-height:150px;">
                    <p> <?php echo metadata('item',array('Dublin Core', 'Description'));
                    ?> </p>
                    </div>
                    <p>Segment:&nbsp<?php echo metadata('item',array('Streaming Video','Segment Start'));
                    ?>
                    --
                    <?php echo metadata('item',array('Streaming Video','Segment End'));
                    ?>
                    </p>
                    </div> <!-- end of loop div for display -->
                <?php }
                ;
                ?>
                <?php endforeach;
                ?>
                <hr style="color:lt-gray;"/>
                <?php set_current_record('item',$orig_item);
                ?>
                
                <script type="text/javascript">
                function getElementsByClass(searchClass, domNode, tagName)
                {
                    if (domNode == null) {
                        domNode = document;
                    }
                    if (tagName == null) {
                        tagName = '*';
                    }
                    var el = new Array();
                    var tags = domNode.getElementsByTagName(tagName);
                    var tcl = " "+searchClass+" ";
                    for (i=0,j=0; i<tags.length; i++) {
                        var test = " " + tags[i].className + " ";
                        if (test.indexOf(tcl) != -1) {
                            el[j++] = tags[i];
                        }
                    }
                    return el;
                }
                
                jwplayer("jwplayer_plugin").onTime(function(event){
                    var ctime = "0:00:00";
                    var scenes;
                    var sel;
                    var i = 0;
                    ctime = getTimeString(jwplayer("jwplayer_plugin").getPosition());
                    
                    scenes = getElementsByClass("scene");
                    for (i; i < scenes.length; i++) {
                        sel = scenes[i];
                        if (sel.getAttribute('id') <= ctime && sel.getAttribute('title') >= ctime) {
                            sel.style.display = 'block';
                        } else {
                            sel.style.display = 'none';
                        }
                    }
                }
                );
                </script>
                <?php
            }
            ?>
            <?php
        }
	
	/*
	* Save jQuery slider to Streaming Video element and Description to Dublin Core before save item
	* use update_item in AfterSaveItem cause infinite loop where elements updated several times until above packet_limit
			$post = $_POST;
        $item = $args['record'];   
			update_item($item, array (
			'overwriteElementTexts' => "true"
		), array (
			'Streaming Video' => array (
				'Segment Start' => array ( array (
					'text' => "$slider_start",
					'html' => false)
				),
				'Segment End' => array ( array (
					'text' => "$slider_end",
					'html' => false)
				)
			),
			'Dublin Core' => array (
				'Description' => array ( array (
					'text' => "$description",
					'html' => false)
				)
			)
		))
	*
	*/
	

	public function filterAdminItemsFormTabs($tabs, $args)
    {

        // insert the Segmenting Video tab before the Miscellaneous tab
        $item = $args['item'];
        if(get_option('jwplayer_tuning')==0){
        $tabs['Segment Tuning'] = $this->_segmentForm($item);
	}
        
        return $tabs;     
    }
	
	/**
	 * Returns the form code for segmenting video for items
     * @param Item $item
     * @return string
     **/    
    protected function _segmentForm($item)
    {
		$segment_flash_file = metadata("item", array("Streaming Video","Video Filename"));
		$segment_flash_type = metadata("item", array("Streaming Video","Video Type"));
		$segment_flash_url = metadata("item", array("Streaming Video","Video Streaming URL"));
		$segment_http_dir = metadata("item", array("Streaming Video","HTTP Streaming Directory"));
		$segment_http_file = metadata("item", array("Streaming Video","HTTP Video Filename"));
		$segment_hls_dir = metadata("item", array("Streaming Video","HLS Streaming Directory"));
		$segment_hls_file = metadata("item", array("Streaming Video","HLS Video Filename"));
		$segment_start = metadata("item", array("Streaming Video","Segment Start"));
		$segment_end = metadata('item', array('Streaming Video','Segment End'));
		$segment_desc = metadata('item',array('Dublin Core','Description'));
		$source = '';
		if(get_option('jwplayer_flash_streaming')){
			$source .= "\n" . '{' . "\n" . 'file: ' . "'" . metadata("item",array ("Streaming Video","Video Streaming URL")) . metadata("item",array("Streaming Video","Video Type")) . metadata("item",array("Streaming Video","Video Filename")) . "'" . '},' . "\n";
		}
		if(get_option('jwplayer_http_streaming')){
			$source .= "\n" . '{' . "\n" . 'file: ' . "'"  . metadata("item",array("Streaming Video","HTTP Streaming Directory")) . metadata("item",array("Streaming Video","HTTP Video Filename")) . "'" . '},' . "\n";
		}
		if(get_option('jwplayer_hls_streaming')){
			$source .= "\n" . '{' . "\n" . 'file: ' . "'" . metadata('item',array("Streaming Video","HLS Streaming Directory")) . metadata('item',array("Streaming Video","HLS Video Filename")) . "'" . '},' . "\n";
		}
		$height = get_option('jwplayer_height_public');
		$jwplayer = '';
		
		if(get_option('jwplayer_flash_primary')){
			$jwplayer .= 
<<<JW_Str1

		jwplayer("jwplayer_plugin").setup({
		playlist:  [{
		sources: [
			$source
		]}
		],
		primary: "flash",
		autostart: false,
		controls: true,
		width: "95%",
		height: $height
		});
JW_Str1;
		} else {
			$jwplayer .= 
<<<JW_Str2

		jwplayer("jwplayer_plugin").setup({
		playlist:  [{
		sources: [
			$source
		]}
		],
		autostart: false,
		controls: true,
		width: "95%",
		height: $height
		});
JW_Str2;
		}
		
		$html = '';
		
		$html .= '<div class="field">';
		$html .=     '<div id="videosegmentation_form" class="two columns alpha" >';
		$html .=         '<input type="hidden" name="videomeka_slider_start" id="videomeka_slider_start" />';
        $html .=         '<input type="hidden" name="videomeka_slider_end" id="videomeka_slider_end" />';
		$html .=     '</div>';
		
		$html .= 
		/*heredoc must has its own line, with no other characters before and after
		*jQuery("input[]") on array, button click must wrap in document ready
		*/
<<<HTML
            <div id="vid_player" style="margin:0 auto; width: 100%;">
             <div id="jwplayer_plugin" style="margin:0 auto;">Player failed to load...  </div>
	    </div>
	    <script type="text/javascript">
		var startTime= calculateTime("$segment_start");
		var endTime = calculateTime("$segment_end");
HTML;
		$html .= $jwplayer . "\n";
		$html .= 
<<<HTML_1
		jwplayer("jwplayer_plugin").onReady(function(){
				jwplayer("jwplayer_plugin").seek(calculateTime("$segment_start"));
			jQuery("#CurrentPos").val(getFormattedTimeString("$segment_start"));
				jwplayer("jwplayer_plugin").pause();
				}
				);
		jwplayer("jwplayer_plugin").onTime(function(){
			jQuery("#CurrentPos").val(getFormattedTimeString(jwplayer("jwplayer_plugin").getPosition()));
		});
		</script>
			
HTML_1;
                $html .=
<<<Slider_
                <p>
		<label for="CurrentPos" style="float:left;">Current position:</label>
                <input type="text" id="CurrentPos" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " /><pauseButton>&#62;&#47;&#61;</pauseButton></p>
		<p>
                <label for="SegmentStart" style="float:left;">Segment start:</label>
                <input type="text" id="SegmentStart" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " /><startButton>Set</startButton></p>
		<p>
                <label for="SegmentEnd" style="float:left;">Segment End:</label>
                <input type="text" id="SegmentEnd" style="border: 0; color: #f6931f; font-weight: bold; width:20%; " /><endButton>Set</endButton>
                </p>
		
Slider_;


		
		$html .=     '<div class="inputs five columns omega">';
        $html .=          '<div class="input-block">';
		$html .= 		  '<label>Description:</label>';
		$html .=	 	  '<textarea id="videomeka_description" name="videomeka_description">';
		$html .='</textarea>';
		$html .=
<<<JS
		<saveButton>Update</saveButton>
		<script type="text/javascript">
			jQuery(document).ready(function(){
                        var v_desc = jQuery("#Elements-41-0-text").val();
			jQuery("#SegmentStart").val(getFormattedTimeString(startTime));
			jQuery("#SegmentEnd").val(getFormattedTimeString(endTime));
			jQuery("pauseButton").button();
			jQuery("startButton").button();
			jQuery("endButton").button();
			jQuery("saveButton").button();
                        jQuery("#videomeka_description").val(v_desc);
jQuery("pauseButton").click(function(){
 jwplayer("jwplayer_plugin").pause(); 
}); 
jQuery("startButton").click(function(){
  jQuery("#SegmentStart").val(getFormattedTimeString(jwplayer().getPosition()));
  jQuery("#Elements-71-0-text").val(jQuery("#SegmentStart").val());
}); 
jQuery("endButton").click(function(){
  jQuery("#SegmentEnd").val(getFormattedTimeString(jwplayer().getPosition()));
  jQuery("#Elements-72-0-text").val(jQuery("#SegmentEnd").val());
}); 
			 jQuery("saveButton").click(function(){
				if(jQuery("#videomeka_description").val().length == 0)
				{
				var desc = jQuery("#Elements-41-0-text").val();
				}
				else
				{
				var desc = jQuery("#videomeka_description").val();
				}
				jQuery("#Elements-41-0-text").val(desc);
  				jQuery("#Elements-71-0-text").val(jQuery("#SegmentStart").val());
  				jQuery("#Elements-72-0-text").val(jQuery("#SegmentEnd").val());
			 });
			});
		</script>
JS;
        $html .=          '</div>';
        $html .=     '</div>';
		$html .= '</div>';
		return $html;
    
	}
	
}
