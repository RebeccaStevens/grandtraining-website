<?php

class Family extends Person {

    private static $has_many = array(
        'CareGivers' => 'CareGiver',
        'Children' => 'Student',
        'Bookings' => 'Booking'
	);
}
