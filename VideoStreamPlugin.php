<?php
class VideoStreamPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Hooks for the plugin.
     */
    protected $_hooks = array(
        'install',
        'upgrade',
        'uninstall',
        'uninstall_message',
        'config_form',
        'config',
        'public_head',
        'admin_head',
        'public_items_show',
    );

    /**
     * @var array Filters for the plugin.
     */
    protected $_filters = array(
        'admin_items_form_tabs',
    );

    /**
     * @var array Options and their default values.
     */
    protected $_options = array(
        'videostream_jwplayer_width_public' => 640,
        'videostream_jwplayer_height_public' => 480,
        'videostream_jwplayer_external_control' => 0,
        'videostream_display_current' => 0,
        'videostream_jwplayer_external_skin' => 'basic',
        'videostream_jwplayer_flash_streaming' => 0,
        'videostream_jwplayer_http_streaming' => 0,
        'videostream_jwplayer_hls_streaming' => 0,
        'videostream_jwplayer_flash_primary' => 0,
        'videostream_jwplayer_autostart' => 0,
        'videostream_display_tuning' => 1,
        'videostream_elements_ids' => '',
    );

    /**
     * Installs the plugin.
     */
    public function hookInstall()
    {
        // Load elements to add.
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elements.php';

        $elementSetName = $elementSetMetadata['name'];
        $elementSet = get_record('ElementSet', array('name' => $elementSetName));
        // Don't install if an element set named "Streaming Video" already exists.
        if ($elementSet) {
            throw new Omeka_Plugin_Exception('An element set by the name "' . $elementSetName . '" already exists.'
                . ' ' . 'You must delete that element set before to install this plugin.');
        }

        insert_element_set($elementSetMetadata, $elements);

        // Prepare element ids for javascript.
        $elementIds = array();
        $elementsTable = $this->_db->getTable('Element');
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Title');
        $elementIds['Dublin Core:Title'] = $element->id;
        $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Description');
        $elementIds['Dublin Core:Description'] = $element->id;
        $elements = $elementsTable->findBySet($elementSetName);
        foreach ($elements as $element) {
            $elementIds[$elementSetName . ':' . $element->name] = $element->id;
        }
        $this->_options['videostream_elements_ids'] = json_encode($elementIds);

        $this->_installOptions();
    }

    /**
     * Upgrade the plugin.
     */
    public function hookUpgrade($args)
    {
        $oldVersion = $args['old_version'];
        $newVersion = $args['new_version'];
        $db = $this->_db;

        if (version_compare($oldVersion, '2.2', '<')) {
            // Normalize the name of options.
            set_option('videostream_jwplayer_width_public', get_option('jwplayer_width_public'));
            set_option('videostream_jwplayer_height_public', get_option('jwplayer_height_public'));
            set_option('videostream_jwplayer_external_control', get_option('jwplayer_external_control'));
            set_option('videostream_display_current', get_option('jwplayer_display_current'));
            set_option('videostream_jwplayer_external_skin', get_option('jwplayer_external_skin'));
            set_option('videostream_jwplayer_flash_streaming', get_option('jwplayer_flash_streaming'));
            set_option('videostream_jwplayer_http_streaming', get_option('jwplayer_http_streaming'));
            set_option('videostream_jwplayer_hls_streaming', get_option('jwplayer_hls_streaming'));
            set_option('videostream_jwplayer_flash_primary', get_option('jwplayer_flash_primary'));
            set_option('videostream_jwplayer_autostart', get_option('jwplayer_autostart'));
            // This option is inverted to be more natural.
            set_option('videostream_display_tuning', !get_option('jwplayer_tuning'));

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
        }

        if (version_compare($oldVersion, '2.2.1', '<')) {
            // Load elements to add.
            require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elements.php';

            $elementSetName = $elementSetMetadata['name'];
            // Prepare element ids for javascript.
            $elementIds = array();
            $elementsTable = $db->getTable('Element');
            $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Title');
            $elementIds['Dublin Core:Title'] = $element->id;
            $element = $elementsTable->findByElementSetNameAndElementName('Dublin Core', 'Description');
            $elementIds['Dublin Core:Description'] = $element->id;
            $elements = $elementsTable->findBySet($elementSetName);
            foreach ($elements as $element) {
                $elementIds[$elementSetName . ':' . $element->name] = $element->id;
            }
            set_option('videostream_elements_ids', json_encode($elementIds));
        }
    }

    /**
     * Uninstalls the plugin.
     */
    public  function hookUninstall()
    {
        // Load elements to remove.
        require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'elements.php';

        $elementSetName = $elementSetMetadata['name'];
        $elementSet = get_record('ElementSet', array('name' => $elementSetName));

        if ($elementSet) {
            $elements = $elementSet->getElements();
            foreach ($elements as $element) {
                $element->delete();
            }
            $elementSet->delete();
        }

        $this->_uninstallOptions();
    }

    /**
     * Appends a warning message to the uninstall confirmation page.
     */
    public function hookUninstallMessage()
    {
        echo __('%sWarning%s: This will permanently delete the %s element set and all its associated metadata. You may deactivate this plugin if you do not want to lose data.%s',
             '<p><strong>', '</strong>', 'Streaming Video', '</p>');
     }

    /**
     * Shows plugin configuration page.
     *
     * @return void
     */
    public function hookConfigForm($args)
    {
        $view = get_view();
        echo $view->partial(
            'plugins/video-stream-config-form.php'
        );
    }

    /**
     * Processes the configuration form.
     *
     * @param array Options set in the config form.
     * @return void
     */
    public function hookConfig($args)
    {
        $post = $args['post'];
        foreach ($this->_options as $optionKey => $optionValue) {
            if (isset($post[$optionKey])) {
                set_option($optionKey, $post[$optionKey]);
            }
        }
    }

    public function hookAdminHead($args)
    {
        echo queue_css_file("jquery-ui-1.10.3.custom");
        echo js_tag('jwplayer');
        echo js_tag('pfUtils');
        echo js_tag('jquery');
        echo js_tag('jquery-ui-1.10.3.custom');
    }

	public function hookPublicHead($args)
	{
        echo queue_css_file("vidStyle");
        echo js_tag('jwplayer');
        echo queue_css_file("jquery-ui-1.10.3.custom");
        echo js_tag('pfUtils');
        echo js_tag('jquery');
        echo js_tag('jquery-ui-1.10.3.custom');
    }

    public function hookPublicItemsShow($args)
    {
        $view = $args['view'];
        $item = $args['item'];

        echo $view->videoStream($item);
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
        // Insert the Segmenting Video tab before the Miscellaneous tab.
        $item = $args['item'];
        if (get_option('videostream_display_tuning')) {
            $tabs['Segment Tuning'] = get_view()->segmentTuningForm($item);
        }
        return $tabs;
    }
}
