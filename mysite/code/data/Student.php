<?php

class Student extends Person {

    private static $db = array(
        'Age' => 'Int'
    );

    private static $has_one = array(
        'Family' => 'Family',
	);

    private static $has_many = array(
        'StudentBooking' => 'StudentBooking'
	);
}
