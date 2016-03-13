<?php

class CareGiver extends Person {

    private static $db = array(
        'PhoneHome' => 'Varchar',
        'PhoneWork' => 'Varchar',
        'PhoneMobile' => 'Varchar',
    );

    private static $has_one = array(
        'Family' => 'Family',
	);
}
