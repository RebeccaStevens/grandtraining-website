<?php

class Family extends Person {

    private static $db = array(
        'Registered' => 'Boolean'
    );

    private static $has_many = array(
        'CareGivers' => 'CareGiver',
        'Children' => 'Student',
        'Bookings' => 'Booking'
	);
}
