<?php
    function jwplayer_public_head()
    {
        ?>
               <!-- slider -->
                <?php echo queue_css_file("vidStyle");?>
                <?php echo queue_css_file("jquery-ui-1.10.3.custom");?>
                <?php echo js_tag('jwplayer');?>
                <?php echo js_tag('pfUtils');?>
                <?php echo js_tag('jquery');?>
                <?php echo js_tag('jquery-ui-1.10.3.custom');
    }

	/*
	* Admin with jwplayer
	*/
    function jwplayer_admin_head()
    {
        ?>
               <!-- slider -->
<!--                <?php echo queue_css_file("vidStyle");?> -->
                <?php echo queue_css_file("jquery-ui-1.10.3.custom");?>
                <?php echo js_tag('jwplayer');?>
                <?php echo js_tag('pfUtils');?>
                <?php echo js_tag('jquery');?>
                <?php echo js_tag('jquery-ui-1.10.3.custom');
    }	
?>
