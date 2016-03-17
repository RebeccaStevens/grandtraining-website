<?php

class Course extends DataObject {

    private static $db = array (
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
		'TypicalPrice' => 'Currency',
		'MinAge' => 'Int',
		'MaxAge' => 'Int'
    );

    private static $has_many = array(
		'ScheduledCourses' => 'ScheduledCourse'
	);

    private static $summary_fields = array (
        'Title' => 'Title',
        'TypicalPrice.Nice' => 'TypicalPrice',
        'CoursePage.Title' => 'Category'
    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
            DropdownField::create('CoursePageID', 'Category')
                ->setSource(CoursePage::get()->map('ID', 'Title')),
			TextField::create('Title'),
            DropdownField::create('MinAge', 'Min Recommend Age')
                ->setSource(ArrayLib::valuekey(range(5, 17))),
            DropdownField::create('MaxAge', 'Max Recommend Age')
                ->setSource(ArrayLib::valuekey(range(17, 5))),
            LabelField::create('How much this course typically cost.<br>This is display on courses that aren\'t currently available to book.'),
            CurrencyField::create('TypicalPrice', 'Typical Price'),
            HtmlEditorField::create('Description')
        ));

        return $fields;
    }
}
