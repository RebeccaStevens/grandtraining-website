<?php

class Family extends DataObject {

    private static $db = array(
        'Email' => 'Varchar(254)',
        'Registered' => 'Boolean'
    );

    private static $has_many = array(
        'CareGivers' => 'CareGiver',
        'Children' => 'Student',
        'Bookings' => 'Booking'
	);
}
