<?php
/**
 * Helper to get the sources for a Video Stream of an item.
 */
class VideoStream_View_Helper_VideoStreamSources extends Zend_View_Helper_Abstract
{
    /**
     * Get the sources for a video stream.
     *
     * @param Item $item
     * @return array The list of sources.
     */
    public function videoStreamSources($item = null)
    {
        $view = $this->view;

        if (is_null($item)) {
            $item = get_current_record('item');
        }

        $sources = array();
        if (get_option('videostream_jwplayer_flash_streaming')) {
            $segmentFlashUrl = metadata($item, array('Streaming Video', 'Video Streaming URL'));
            $segmentFlashType = metadata($item, array('Streaming Video', 'Video Type'));
            $segmentFlashFile = metadata($item, array('Streaming Video', 'Video Filename'));
            $sources[] = array('file' => $segmentFlashUrl . $segmentFlashType . $segmentFlashFile);
        }

        if (get_option('videostream_jwplayer_http_streaming')) {
            $segmentHttpDir = metadata($item, array('Streaming Video', 'HTTP Streaming Directory'));
            $segmentHttpFile = metadata($item, array('Streaming Video', 'HTTP Video Filename'));
            $sources[] = array('file' => $segmentHttpDir . $segmentHttpFile);
        }

        if (get_option('videostream_jwplayer_hls_streaming')) {
            $segmentHlsDir = metadata($item, array('Streaming Video', 'HLS Streaming Directory'));
            $segmentHlsFile = metadata($item, array('Streaming Video', 'HLS Video Filename'));
            $sources[] = array('file' => $segmentHlsDir . $segmentHlsFile);
        }

        return $sources;
    }
}
