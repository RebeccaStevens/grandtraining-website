<?php

class ScheduledCourse extends DataObject {

    private static $db = array (
        'StartDay' => 'Date',
        'EndDay' => 'Date',
        'Price' => 'Currency'
    );

	private static $has_one = array(
        'Course' => 'Course'
	);

    private static $summary_fields = array (
        'Course.Title' => 'Course',
        'StartDay' => 'StartDay',
        'EndDay' => 'EndDay',
        'Price' => 'Price'
    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', array(
            DropdownField::create('CourseID', 'Course')
                ->setSource(Course::get()->map('ID', 'Title')),
			DateField::create('StartDay')
                ->setConfig('showcalendar', true)
                ->setConfig('dateformat', 'dd-MM-yyyy')
                ->setAttribute('placeholder', 'dd-MM-yyyy'),
            DateField::create('EndDay')
                ->setConfig('showcalendar', true)
                ->setConfig('dateformat', 'dd-MM-yyyy')
                ->setAttribute('placeholder', 'dd-MM-yyyy'),
            CurrencyField::create('Price')
        ));

        return $fields;
    }
}
