<?php

class Booking extends DataObject {

    private static $db = array (

    );

	private static $has_one = array(
        'ScheduledCourse' => 'ScheduledCourse'
	);

    private static $many_many = array(
        'Students' => 'Student'
	);
}
