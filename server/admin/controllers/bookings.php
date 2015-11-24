<?php  namespace GrandTraining\admin\controllers;

use GrandTraining\www\bases\BaseController;

class bookings extends BaseController {

	function __construct(){
		parent::__construct();
	}

	/**
	 * URL: /bookings
	 */
	public function index($data) {
		$this->_requireLoggedIn();
		echo 'bookings controller';
	}
}
