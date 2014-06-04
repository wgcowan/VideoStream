<?php
if (!defined('VIDEOSTREAM_PLUGIN_DIR')) {
    define('VIDEOSTREAM_PLUGIN_DIR', dirname(__FILE__));
}
add_plugin_hook('public_head', 'jwplayer_public_head');
add_plugin_hook('admin_head', 'jwplayer_admin_head');
 
require_once VIDEOSTREAM_PLUGIN_DIR . '/VideoStreamPlugin.php';
require_once VIDEOSTREAM_PLUGIN_DIR . '/functions.php';
$videostreamPlugin = new VideoStreamPlugin;
$videostreamPlugin->setUp();
