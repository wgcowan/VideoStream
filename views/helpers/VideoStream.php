<?php
/**
 * Helper to display a Video Stream.
 */
class VideoStream_View_Helper_VideoStream extends Zend_View_Helper_Abstract
{
    /**
     * Get the specified JW Player to display a video stream.
     *
     * @param Item $item
     * @return string Html string.
     */
    public function videoStream($item = null)
    {
        $view = $this->view;
        $db = get_db();

        if (is_null($item)) {
            $item = get_current_record('item');
        }

        $source = '';
        if (get_option('videostream_jwplayer_flash_streaming')) {
            $segmentFlashUrl = metadata($item, array('Streaming Video', 'Video Streaming URL'));
            $segmentFlashType = metadata($item, array('Streaming Video', 'Video Type'));
            $segmentFlashFile = metadata($item, array('Streaming Video', 'Video Filename'));
            $source .= sprintf('{file: %s},' . PHP_EOL,
                json_encode($segmentFlashUrl . $segmentFlashType . $segmentFlashFile));
        }

        if (get_option('videostream_jwplayer_http_streaming')) {
            $segmentHttpDir = metadata($item, array('Streaming Video', 'HTTP Streaming Directory'));
            $segmentHttpFile = metadata($item, array('Streaming Video', 'HTTP Video Filename'));
            $source .= sprintf('{file: %s},' . PHP_EOL,
                json_encode($segmentHttpDir . $segmentHttpFile));
        }

        if (get_option('videostream_jwplayer_hls_streaming')) {
            $segmentHlsDir = metadata($item, array('Streaming Video', 'HLS Streaming Directory'));
            $segmentHlsFile = metadata($item, array('Streaming Video', 'HLS Video Filename'));
            $source .= sprintf('{file: %s},' . PHP_EOL,
                json_encode($segmentHlsDir . $segmentHlsFile));
        }

        $partial = get_option('videostream_jwplayer_external_control')
            ? 'video-stream-external-control'
            : 'video-stream-internal-control';

        $html = $view->partial('common/' . $partial . '.php', array(
            'item' => $item,
            'source' => $source,
            // String is needed to simplify javascript (avoids null).
            'segment_start' => (string) metadata($item, array('Streaming Video', 'Segment Start')),
            'segment_end' => (string) metadata($item, array('Streaming Video', 'Segment End')),
            // TODO This description is currently not used.
            'segment_description' => (string) metadata($item, array('Dublin Core', 'Description')),
        ));

        if (get_option('videostream_display_current')) {
            $html .= $view->partial('common/video-stream-current.php', array(
                'item' => $item,
                'video_filename' => (string) metadata($item, array('Streaming Video', 'Video Filename')),
            ));
        }

        return $html;
    }
}
