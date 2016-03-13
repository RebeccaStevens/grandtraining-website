<?php

class Person extends DataObject {

    private static $db = array (
        'FirstName' => 'Varchar',
        'Surname' => 'Varchar',
        'Email' => 'Varchar(254)'
    );

    private static $has_one = array(
        'Address' => 'Address'
	);
}
