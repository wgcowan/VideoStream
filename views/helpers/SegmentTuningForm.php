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

        $sources = $view->videoStreamSources($item);
        $sources = version_compare(phpversion(), '5.4.0', '<')
            ? json_encode($sources)
            : json_encode($sources, JSON_UNESCAPED_SLASHES);

        $html = $view->partial('common/segment-tuning-form.php', array(
            'item' => $item,
            'sources' => $sources,
            // String is needed to simplify javascript.
            'segment_start' => (string) metadata($item, array('Streaming Video', 'Segment Start')),
            'segment_end' => (string) metadata($item, array('Streaming Video', 'Segment End')),
            // TODO This description is currently not used.
            'segment_description' => (string) metadata($item, array('Dublin Core', 'Description')),
        ));

        return $html;
    }
}
