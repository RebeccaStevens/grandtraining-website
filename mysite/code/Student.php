<?php

class Student extends Person {

    private static $db = array (
        'Age' => 'Int'
    );

    private static $belongs_many_many = array(
        'Parents' => 'CareGiver',
        'Bookings' => 'Booking'
	);
}
