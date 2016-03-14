<?php

class FAQPage extends Page {

	private static $has_many = array (
        'FAQs' => 'FAQ'
    );

	public function getCMSFields() {
	    $fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Questions', GridField::create(
			'FAQs',
			'FAQs',
			$this->FAQs(),
			GridFieldConfig_RecordEditor::create()
		));

	    return $fields;
	}

}

class FAQPage_Controller extends Page_Controller {

}
