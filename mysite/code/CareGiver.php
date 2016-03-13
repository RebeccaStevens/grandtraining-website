<?php

class CareGiver extends Person {

    private static $db = array (
        'PhoneHome' => 'Varchar',
        'PhoneWork' => 'Varchar',
        'PhoneMobile' => 'Varchar',
    );

    private static $many_many = array(
        'Children' => 'Student'
	);
}
