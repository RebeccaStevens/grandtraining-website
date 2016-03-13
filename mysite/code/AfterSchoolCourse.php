<?php

class AfterSchoolCourse extends DataObject {

    private static $db = array (
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
		'Price' => 'Currency',
		'MinAge' => 'Int',
		'MaxAge' => 'Int'
    );

	private static $has_one = array(
		'CoursePage' => 'CoursePage'
	);

    private static $summary_fields = array (
        'Title' => 'Title',
        'Price.Nice' => 'Price',
        'CoursePage.Title' => 'Category'
    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
            DropdownField::create('CoursePageID', 'Category')
                ->setSource(CoursePage::get()->map('ID', 'Title')),
            TextField::create('Title'),
            CurrencyField::create('Price'),
            DropdownField::create('MinAge')
                ->setSource(ArrayLib::valuekey(range(5, 17))),
            DropdownField::create('MaxAge')
                ->setSource(ArrayLib::valuekey(range(17, 5))),
            HtmlEditorField::create('Description')
        ));

        return $fields;
    }

    public function searchableFields() {
        return array (
            'CoursePageID' => array (
                'filter' => 'ExactMatchFilter',
                'title' => 'Category',
                'field' => DropdownField::create('CoursePageID')
                    ->setSource(CoursePage::get()->map('ID','Title'))
                    ->setEmptyString('-- Any Category --')
            ),
            'Title' => array (
                'filter' => 'PartialMatchFilter',
                'title' => 'Title',
                'field' => 'TextField'
            )
        );
    }
}
