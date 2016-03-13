<?php

class StudentBooking extends DataObject {

    private static $db = array(
        'Confirmed' => 'Boolean',
    );

	private static $has_one = array(
        'Booking' => 'Booking',
        'ScheduledCourse' => 'ScheduledCourse',
        'Student' => 'Student'
	);

    private static $summary_fields = array(
        'Student.FirstName' => 'First Name',
        'Student.Surname' => 'Surname',
        'ScheduledCourse.Title' => 'Course',
        'ScheduledCourse.StartDay' => 'Start Day',
        'ScheduledCourse.EndDay' => 'End Day',
        'Confirmed' => 'Confirmed'
    );
}
