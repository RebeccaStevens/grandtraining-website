<?php

class ScheduledCourse extends DataObject {

    private static $db = array (

    );

	private static $has_one = array(

	);

    private static $summary_fields = array (

    );

    public function getCMSFields() {
		$fields = FieldList::create(TabSet::create('Root'));

        return $fields;
    }
}
