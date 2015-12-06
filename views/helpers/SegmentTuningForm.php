<?php
/**
 * Helper to display a Segment Tuning Form.
 */
class VideoStream_View_Helper_SegmentTuningForm extends Zend_View_Helper_Abstract
{
    /**
     * Returns the form code for segmenting video for items.
     *
     * @param Item $item
     * @return string Html string.
     */
    public function segmentTuningForm($item = null)
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

        $html = $view->partial('common/segment-tuning-form.php', array(
            'item' => $item,
            'source' => $source,
            // String is needed to simplify javascript.
            'segment_start' => (string) metadata($item, array('Streaming Video', 'Segment Start')),
            'segment_end' => (string) metadata($item, array('Streaming Video', 'Segment End')),
            // TODO This description is currently not used.
            'segment_description' => (string) metadata($item, array('Dublin Core', 'Description')),
        ));

        return $html;
    }
}
