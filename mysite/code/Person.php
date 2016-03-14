<?php

class Person extends DataObject {

    private static $db = array (
        'FirstName' => 'Varchar',
        'Surname' => 'Varchar',
        'Gender' => 'Enum("Male,Female,Unspecified", "Unspecified")'
    );

    private static $has_one = array(
        'Address' => 'Address'
	);
}
