<?php

class HolidayCourse extends Course {

    private static $db = array (
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
		$fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', array(
			DropdownField::create('Days', 'Number of Days')
				->setSource(ArrayLib::valuekey(range(1, 5)))
        ), 'MinAge');

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
