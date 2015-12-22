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

        $sources = $view->videoStreamSources($item);
        $sources = version_compare(phpversion(), '5.4.0', '<')
            ? json_encode($sources)
            : json_encode($sources, JSON_UNESCAPED_SLASHES);

        $partial = get_option('videostream_jwplayer_external_control')
            ? 'video-stream-external-control'
            : 'video-stream-internal-control';

        $html = $view->partial('common/' . $partial . '.php', array(
            'item' => $item,
            'sources' => $sources,
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
