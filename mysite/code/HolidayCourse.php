<?php

class HolidayCourse extends DataObject {

    private static $db = array (
		'Title' => 'Varchar',
		'Description' => 'Text',
		'Price' => 'Currency',
		'MinAge' => 'Int',
		'MaxAge' => 'Int',
		'Days' => 'Int'
    );

	private static $has_one = array(
		'CoursePage' => 'CoursePage'
	);

    private static $summary_fields = array (
        'Title' => 'Title',
        'Price.Nice' => 'Price',
        'Days' => 'Days',
        'CoursePage.Title' => 'Category'
    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
            DropdownField::create('CoursePageID', 'Category')
                ->setSource(CoursePage::get()->map('ID', 'Title')),
			TextField::create('Title'),
            TextareaField::create('Description'),
            CurrencyField::create('Price'),
			DropdownField::create('Days', 'Number of Days')
				->setSource(ArrayLib::valuekey(range(1, 5))),
            DropdownField::create('MinAge', 'Min Recommend Age')
                ->setSource(ArrayLib::valuekey(range(5, 17))),
            DropdownField::create('MaxAge', 'Max Recommend Age')
                ->setSource(ArrayLib::valuekey(range(17, 5)))
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
            ),
            'Days' => array (
                'filter' => 'ExactMatchFilter',
                'title' => 'Number of Days',
                'field' => DropdownField::create('Days')
                    ->setSource(ArrayLib::valuekey(range(1, 5)))
                    ->setEmptyString('-- Any --')
            )
        );
    }
}
