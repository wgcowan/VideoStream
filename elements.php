<?php
$elementSetMetadata = array(
    'name' => 'Streaming Video',
    'description' => 'Elements needed for streaming video for the VideoStream Plugin',
    'record_type' => 'Item',
);

$elements = array(
    array(
        'name' => 'Video Filename',
        'description' => 'Actual filename of the video on the video source server'
    ),
    array(
        'name' => 'Video Streaming URL',
        'description' => 'Actual URL of the streaming server without the filename'
    ),
    array(
        'name' => 'Video Type',
        'description' => 'Encoding for the video; mp4, flv, mov, and so forth'
    ),
    array(
        'name' => 'HLS Streaming Directory',
        'description' => 'Directory location on your server for the HLS .m3u8 file.'
    ),
    array(
        'name' => 'HLS Video Filename',
        'description' => 'Filename for HLS video file. Include any subdirectories.'
    ),
    array(
        'name' => 'HTTP Streaming Directory',
        'description' => 'Directory location for files to HTTP stream directly from Web Server.'
    ),
    array(
        'name' => 'HTTP Video Filename',
        'description' => 'Actual filename of the video on the web server'
    ),
    array(
        'name' => 'Segment Start',
        'description' => 'Start point in video in either seconds or hh:mm:ss'
    ),
    array(
        'name' => 'Segment End',
        'description' => 'End point in video in either seconds or hh:mm:ss'
    ),
    array(
        'name' => 'Segment Type',
        'description' => 'Use segment type to help determine how segment is to be displayed. For instance, an event may encompass many scenes, etc.'
    ),
    array(
        'name' => 'Show Item',
        'description' => 'Should item be shown in a list. Can be useful in cetain types of displays where you may not want to have all items shown.'
    ),
    array(
        'name' => 'Video Source',
        'description' => 'Source of video. Streaming server, YouTube, etc.'
    )
    // etc.
);
