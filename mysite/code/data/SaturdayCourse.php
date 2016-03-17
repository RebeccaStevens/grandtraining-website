<?php

class SaturdayCourse extends Course {

    private static $db = array (
		'StartTime' => 'Time',
		'EndTime' => 'Time'
    );

	private static $has_one = array(
		'CoursePage' => 'CoursePage'
	);

    private static $summary_fields = array (
        'Title' => 'Title',
        'TypicalPrice.Nice' => 'TypicalPrice',
        'StartTime.Nice' => 'Start Time',
        'EndTime.Nice' => 'End Time',
        'CoursePage.Title' => 'Category'
    );

    public function getCMSFields() {
		$fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', array(
            TimeField::create('StartTime')
                ->setConfig('timeformat', 'HH:mm'),
            TimeField::create('EndTime')
                ->setConfig('timeformat', 'HH:mm')
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
            )
        );
    }
}
