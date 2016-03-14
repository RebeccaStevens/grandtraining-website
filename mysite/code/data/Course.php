<?php

class Course extends DataObject {

    private static $db = array (
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
		'Price' => 'Currency',
		'MinAge' => 'Int',
		'MaxAge' => 'Int'
    );

    private static $has_many = array(
		'ScheduledCourse' => 'ScheduledCourse'
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
            DropdownField::create('MinAge', 'Min Recommend Age')
                ->setSource(ArrayLib::valuekey(range(5, 17))),
            DropdownField::create('MaxAge', 'Max Recommend Age')
                ->setSource(ArrayLib::valuekey(range(17, 5))),
            HtmlEditorField::create('Description')
        ));

        return $fields;
    }
}
