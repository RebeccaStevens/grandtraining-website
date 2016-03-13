<?php

class FAQ extends DataObject {

    private static $db = array (
		'Question' => 'Varchar(200)',
		'Answer' => 'Text'
    );

	private static $has_one = array(
		'FAQPage' => 'FAQPage'
	);

    private static $summary_fields = array (
        'Question' => 'Question',
        'Answer' => 'Answer'
    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
			TextField::create('Question', 'Question', '', 200),
            TextareaField::create('Answer')
        ));

        return $fields;
    }

    public function searchableFields() {
        return array (
            'Question' => array (
                'filter' => 'PartialMatchFilter',
                'title' => 'Question',
                'field' => 'TextField'
            ),
            'Answer' => array (
                'filter' => 'PartialMatchFilter',
                'title' => 'Answer',
                'field' => 'TextField'
            )
        );
    }
}
