<?php

class Address extends DataObject {

    private static $db = array (
        'Line1' => 'Varchar',
        'Line2' => 'Varchar',
        'Suburb' => 'Varchar',
        'City' => 'Varchar',
        'Country' => 'Varchar'
    );

	private static $has_many = array(
        'Dwellers' => 'Person'
	);
}
