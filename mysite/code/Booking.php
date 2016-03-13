<?php

class Booking extends DataObject {

    private static $db = array(
        'DateMade' => 'Date',
        'Price' => 'Currency',
        'Piad' => 'Boolean'
    );

    private static $has_one = array(
        'Family' => 'Family'
    );

	private static $has_many = array(
        'StudentBookings' => 'StudentBooking'
	);
}
